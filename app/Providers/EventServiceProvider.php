<?php
namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

use App\Domain\Home\Events\HomePageViewed;
use App\Listeners\LogHomeView;

use App\Domain\Pedidos\Events\PedidoAprovado;
use App\Listeners\EnviaEmailPedidoAprovado;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        HomePageViewed::class => [
            LogHomeView::class,
        ],
        
    ];

    public function boot(): void
    {
        parent::boot();
    }
}
