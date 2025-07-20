<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Domain\Home\Enums\PageViewType;
use App\Database\Factories\HomePageViewFactory;

/**
 * Model Eloquent para visualizações da página inicial
 */
class HomePageView extends Model
{
    use HasFactory;

    protected $table = 'home_page_views';

    protected $fillable = [
        'view_id',
        'user_id',
        'user_ip',
        'user_agent', 
        'view_type',
        'viewed_at',
        'metadata',
    ];

    protected $casts = [
        'view_type' => PageViewType::class,
        'viewed_at' => 'datetime',
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $dates = [
        'viewed_at',
        'created_at',
        'updated_at',
    ];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return HomePageViewFactory::new();
    }

    // Relacionamentos
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeGuests($query)
    {
        return $query->where('view_type', PageViewType::GUEST);
    }

    public function scopeAuthenticated($query)
    {
        return $query->where('view_type', PageViewType::AUTHENTICATED);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('viewed_at', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('viewed_at', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('viewed_at', now()->month)
                    ->whereYear('viewed_at', now()->year);
    }
} 