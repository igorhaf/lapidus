<?php

namespace App\Http\Controllers\Api\V1\Home;

use App\Http\Controllers\Controller;
use App\Domain\Home\Events\HomePageViewed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        Event::dispatch(new HomePageViewed(
            $user?->id ?? 0,
            new \DateTimeImmutable()
        ));

        return Inertia::render('Home', [
            // Passe os dados necessários aqui, por exemplo:
            // 'user' => $user,
        ]);
    }
}
