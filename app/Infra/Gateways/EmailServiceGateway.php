<?php

namespace App\Infra\Gateways;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

/**
 * Gateway base para serviços externos
 */
abstract class BaseGateway
{
    protected string $baseUrl;
    protected array $headers = [];
    protected int $timeout = 30;
    protected int $retries = 3;

    public function __construct()
    {
        $this->initializeConfig();
    }

    /**
     * Inicializar configurações específicas do gateway
     */
    abstract protected function initializeConfig(): void;

    /**
     * Fazer requisição HTTP com retry
     */
    protected function makeRequest(string $method, string $endpoint, array $data = []): array
    {
        $attempts = 0;
        
        while ($attempts < $this->retries) {
            try {
                $response = Http::timeout($this->timeout)
                    ->withHeaders($this->headers)
                    ->$method($this->baseUrl . $endpoint, $data);

                if ($response->successful()) {
                    return $response->json();
                }

                Log::warning("Gateway request failed", [
                    'gateway' => static::class,
                    'endpoint' => $endpoint,
                    'status' => $response->status(),
                    'attempt' => $attempts + 1,
                ]);

            } catch (\Exception $e) {
                Log::error("Gateway request exception", [
                    'gateway' => static::class,
                    'endpoint' => $endpoint,
                    'error' => $e->getMessage(),
                    'attempt' => $attempts + 1,
                ]);
            }

            $attempts++;
            
            if ($attempts < $this->retries) {
                sleep(pow(2, $attempts)); // Exponential backoff
            }
        }

        throw new \Exception("Gateway request failed after {$this->retries} attempts");
    }

    /**
     * Cache com TTL específico do gateway
     */
    protected function cache(string $key, callable $callback, int $ttl = 300): mixed
    {
        $cacheKey = static::class . ':' . $key;
        
        return Cache::remember($cacheKey, $ttl, $callback);
    }
} 