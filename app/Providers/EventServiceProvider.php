<?php
namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

use App\Domain\Home\Events\HomePageViewed;
use App\Domain\Home\Events\ContactSubmitted;
use App\Listeners\LogHomeView;
use App\Listeners\LogContactSubmission;
use App\Listeners\SendContactNotification;
use App\Listeners\UpdateContactStats;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        HomePageViewed::class => [
            LogHomeView::class,
        ],
        ContactSubmitted::class => [
            LogContactSubmission::class,
            SendContactNotification::class,
            UpdateContactStats::class,
        ],
    ];

    public function boot(): void
    {
        parent::boot();
    }
}
