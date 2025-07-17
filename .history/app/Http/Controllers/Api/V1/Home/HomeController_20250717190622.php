<?php

namespace App\Http\Controllers;
use App\Domain\Home\Events\HomePageViewed;
use Illuminate\Support\Facades\Event;

public function __invoke(Request $request)
{
    $user = $request->user();
    Event::dispatch(new HomePageViewed(
        $user?->id ?? 0, 
        new \DateTimeImmutable()
    ));

    return inertia('Home', []);
}
