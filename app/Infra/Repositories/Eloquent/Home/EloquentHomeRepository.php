<?php

namespace App\Infra\Repositories\Eloquent\Home;

use App\Domain\Home\Interfaces\Repositories\HomeRepositoryInterface;
use App\Domain\Home\Entities\HomePageView;
use App\Models\HomePageView as EloquentHomePageView;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

/**
 * Implementação Eloquent do repositório de Home
 */
class EloquentHomeRepository implements HomeRepositoryInterface
{
    public function savePageView(HomePageView $pageView): void
    {
        try {
            // Salvar no banco usando Model Eloquent
            EloquentHomePageView::create([
                'view_id' => $pageView->getId()->getValue(),
                'user_id' => $pageView->getUserId()?->getValue(),
                'user_ip' => $pageView->getUserIp(),
                'user_agent' => $pageView->getUserAgent(),
                'view_type' => $pageView->getViewType(),
                'viewed_at' => $pageView->getViewedAt()->format('Y-m-d H:i:s'),
                'metadata' => [
                    'session_id' => session()->getId(),
                    'referrer' => request()->headers->get('referer'),
                    'is_guest' => $pageView->isGuest(),
                ],
            ]);

            // Log para auditoria
            \Log::info('Home page view saved successfully', [
                'view_id' => $pageView->getId()->getValue(),
                'user_id' => $pageView->getUserId()?->getValue(),
                'user_ip' => $pageView->getUserIp(),
                'view_type' => $pageView->getViewType()->value,
            ]);

            // Invalidar cache de estatísticas
            Cache::forget('home_page_stats');
            Cache::forget('page_views_today');
            Cache::forget('page_views_week');

        } catch (\Exception $e) {
            \Log::error('Error saving page view', [
                'error' => $e->getMessage(),
                'view_id' => $pageView->getId()->getValue(),
                'user_ip' => $pageView->getUserIp(),
            ]);
            
            throw $e;
        }
    }

    public function getPageStats(): array
    {
        return Cache::remember('home_page_stats', 300, function () {
            $totalViews = EloquentHomePageView::count();
            $uniqueVisitors = EloquentHomePageView::distinct('user_ip')->count('user_ip');
            $todayViews = EloquentHomePageView::today()->count();
            $weekViews = EloquentHomePageView::thisWeek()->count();
            $monthViews = EloquentHomePageView::thisMonth()->count();
            
            // Estatísticas por tipo de usuário
            $guestViews = EloquentHomePageView::guests()->count();
            $authenticatedViews = EloquentHomePageView::authenticated()->count();

            return [
                'total_views' => $totalViews,
                'unique_visitors' => $uniqueVisitors,
                'today_views' => $todayViews,
                'week_views' => $weekViews,
                'month_views' => $monthViews,
                'guest_views' => $guestViews,
                'authenticated_views' => $authenticatedViews,
                'bounce_rate' => $guestViews > 0 ? round(($guestViews / $totalViews) * 100, 1) . '%' : '0%',
                'conversion_rate' => $totalViews > 0 ? round(($authenticatedViews / $totalViews) * 100, 1) . '%' : '0%',
                'last_updated' => now()->toISOString(),
            ];
        });
    }

    public function getRecentViews(int $limit = 10): array
    {
        return Cache::remember('recent_views_' . $limit, 300, function () use ($limit) {
            $recentViews = EloquentHomePageView::with('user')
                                             ->orderBy('viewed_at', 'desc')
                                             ->limit($limit)
                                             ->get();

            return [
                'views' => $recentViews->map(function ($view) {
                    return [
                        'view_id' => $view->view_id,
                        'user_id' => $view->user_id,
                        'user_name' => $view->user?->name ?? 'Visitante',
                        'user_ip' => $view->user_ip,
                        'view_type' => $view->view_type,
                        'viewed_at' => $view->viewed_at->format('d/m/Y H:i:s'),
                    ];
                })->toArray(),
                'total' => $recentViews->count(),
            ];
        });
    }
} 