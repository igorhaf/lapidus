<?php

namespace App\Infra\Gateways;

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;

/**
 * Gateway para serviço de cache (Redis)
 */
class CacheServiceGateway extends BaseGateway
{
    private const TTL_DEFAULT = 3600; // 1 hora
    private const TTL_SHORT = 300; // 5 minutos
    private const TTL_LONG = 86400; // 24 horas

    protected function initializeConfig(): void
    {
        // Redis não precisa de baseUrl para HTTP
        $this->baseUrl = '';
    }

    /**
     * Armazenar dados com TTL
     */
    public function set(string $key, mixed $value, int $ttl = self::TTL_DEFAULT): bool
    {
        try {
            $serializedValue = is_array($value) ? json_encode($value) : $value;
            return Redis::setex($key, $ttl, $serializedValue);
        } catch (\Exception $e) {
            Log::error('Failed to set cache', [
                'key' => $key,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Obter dados do cache
     */
    public function get(string $key): mixed
    {
        try {
            $value = Redis::get($key);
            if ($value === null) {
                return null;
            }

            // Tentar deserializar JSON
            $decoded = json_decode($value, true);
            return json_last_error() === JSON_ERROR_NONE ? $decoded : $value;

        } catch (\Exception $e) {
            Log::error('Failed to get cache', [
                'key' => $key,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Remover dados do cache
     */
    public function delete(string $key): bool
    {
        try {
            return (bool) Redis::del($key);
        } catch (\Exception $e) {
            Log::error('Failed to delete cache', [
                'key' => $key,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Armazenar estatísticas de contato
     */
    public function setContactStats(array $stats): bool
    {
        return $this->set('contact_stats', $stats, self::TTL_SHORT);
    }

    /**
     * Obter estatísticas de contato
     */
    public function getContactStats(): ?array
    {
        return $this->get('contact_stats');
    }

    /**
     * Incrementar contador de contatos
     */
    public function incrementContactCount(): int
    {
        try {
            return Redis::incr('contact_count');
        } catch (\Exception $e) {
            Log::error('Failed to increment contact count', [
                'error' => $e->getMessage(),
            ]);
            return 0;
        }
    }

    /**
     * Armazenar dados de sessão do usuário
     */
    public function setUserSession(string $userId, array $sessionData): bool
    {
        return $this->set("user_session:{$userId}", $sessionData, self::TTL_LONG);
    }

    /**
     * Obter dados de sessão do usuário
     */
    public function getUserSession(string $userId): ?array
    {
        return $this->get("user_session:{$userId}");
    }
} 