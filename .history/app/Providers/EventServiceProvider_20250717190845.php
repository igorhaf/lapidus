<?php
namespace App\Providers;

protected $listen = [
    \App\Domain\Home\Events\HomePageViewed::class => [
        \App\Listeners\LogHomeView::class,
    ],
    // outros eventos...
];
