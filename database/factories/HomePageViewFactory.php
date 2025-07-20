<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\HomePageView;
use App\Models\User;
use App\Domain\Home\Enums\PageViewType;

/**
 * Factory para gerar dados de teste para visualizações da página
 */
class HomePageViewFactory extends Factory
{
    protected $model = HomePageView::class;

    public function definition(): array
    {
        $viewType = fake()->randomElement(PageViewType::cases());
        $userId = $viewType === PageViewType::AUTHENTICATED 
            ? User::factory()->create()->id 
            : null;

        return [
            'view_id' => fake()->uuid(),
            'user_id' => $userId,
            'user_ip' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
            'view_type' => $viewType,
            'viewed_at' => fake()->dateTimeBetween('-30 days', 'now'),
            'metadata' => [
                'session_duration' => fake()->numberBetween(10, 600), // segundos
                'referrer' => fake()->randomElement([
                    'https://google.com',
                    'https://facebook.com',
                    'https://instagram.com',
                    'direct',
                    null
                ]),
                'device_type' => fake()->randomElement(['desktop', 'mobile', 'tablet']),
                'browser' => fake()->randomElement(['Chrome', 'Firefox', 'Safari', 'Edge']),
                'os' => fake()->randomElement(['Windows', 'macOS', 'Linux', 'iOS', 'Android']),
            ],
        ];
    }

    /**
     * Estado para visitante não autenticado
     */
    public function guest(): static
    {
        return $this->state(fn () => [
            'user_id' => null,
            'view_type' => PageViewType::GUEST,
        ]);
    }

    /**
     * Estado para usuário autenticado
     */
    public function authenticated(): static
    {
        return $this->state(fn () => [
            'user_id' => User::factory()->create()->id,
            'view_type' => PageViewType::AUTHENTICATED,
        ]);
    }

    /**
     * Estado para visualização de hoje
     */
    public function today(): static
    {
        return $this->state(fn () => [
            'viewed_at' => fake()->dateTimeBetween('today', 'now'),
        ]);
    }

    /**
     * Estado para visualização desta semana
     */
    public function thisWeek(): static
    {
        return $this->state(fn () => [
            'viewed_at' => fake()->dateTimeBetween('this week', 'now'),
        ]);
    }

    /**
     * Estado para visualização mobile
     */
    public function mobile(): static
    {
        return $this->state(fn () => [
            'user_agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/605.1.15',
            'metadata' => array_merge($this->definition()['metadata'], [
                'device_type' => 'mobile',
                'browser' => 'Safari',
                'os' => 'iOS',
            ]),
        ]);
    }
} 