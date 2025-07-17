<?php
namespace App\Listeners;

use App\Domain\Home\Events\HomePageViewed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogHomeView implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(HomePageViewed $event): void
    {
        Log::info('Home view recorded', [
            'userId'    => $event->userId,
            'viewedAt'  => $event->viewedAt->format('c'),
        ]);
    }
}
