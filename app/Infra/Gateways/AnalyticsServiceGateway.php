<?php

namespace App\Infra\Gateways;

/**
 * Gateway para serviço de analytics (ex: Google Analytics, Mixpanel)
 */
class AnalyticsServiceGateway extends BaseGateway
{
    private const TTL_ANALYTICS_DATA = 1800; // 30 minutos

    protected function initializeConfig(): void
    {
        $this->baseUrl = config('services.analytics.base_url', 'https://www.googleapis.com/analytics/v3/');
        $this->headers = [
            'Authorization' => 'Bearer ' . config('services.analytics.access_token'),
        ];
    }

    /**
     * Registrar evento de visualização de página
     */
    public function trackPageView(string $page, array $userData = []): array
    {
        $data = [
            'v' => 1,
            'tid' => config('services.analytics.tracking_id'),
            'cid' => $userData['client_id'] ?? uniqid(),
            't' => 'pageview',
            'dp' => $page,
            'dt' => $userData['title'] ?? 'Página Inicial',
        ];

        if (!empty($userData['user_id'])) {
            $data['uid'] = $userData['user_id'];
        }

        return $this->makeRequest('post', 'collect', $data);
    }

    /**
     * Registrar evento de contato
     */
    public function trackContactSubmission(string $contactId, array $metadata = []): array
    {
        $data = [
            'v' => 1,
            'tid' => config('services.analytics.tracking_id'),
            'cid' => $metadata['client_id'] ?? uniqid(),
            't' => 'event',
            'ec' => 'Contact',
            'ea' => 'Submit',
            'el' => $contactId,
        ];

        if (!empty($metadata['is_urgent'])) {
            $data['ev'] = 1; // Event value
        }

        return $this->makeRequest('post', 'collect', $data);
    }

    /**
     * Obter dados de analytics
     */
    public function getAnalyticsData(string $startDate, string $endDate, array $metrics = []): array
    {
        $cacheKey = "analytics_{$startDate}_{$endDate}_" . md5(serialize($metrics));
        
        return $this->cache($cacheKey, function () use ($startDate, $endDate, $metrics) {
            $params = [
                'ids' => 'ga:' . config('services.analytics.view_id'),
                'start-date' => $startDate,
                'end-date' => $endDate,
                'metrics' => implode(',', $metrics ?: ['ga:pageviews', 'ga:sessions']),
                'dimensions' => 'ga:date',
            ];

            return $this->makeRequest('get', 'data/ga?' . http_build_query($params));
        }, self::TTL_ANALYTICS_DATA);
    }
} 