<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Domain\Home\Enums\ContactStatus;
use Database\Factories\ContactFactory;

/**
 * Model Eloquent para contatos do módulo Home
 */
class Contact extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'home_contacts';

    protected $fillable = [
        'contact_id',
        'name',
        'email', 
        'phone',
        'subject',
        'message',
        'preferred_contact',
        'newsletter',
        'status',
        'user_ip',
        'user_agent',
    ];

    protected $casts = [
        'newsletter' => 'boolean',
        'status' => ContactStatus::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $dates = [
        'created_at',
        'updated_at', 
        'deleted_at',
    ];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return ContactFactory::new();
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', ContactStatus::PENDING);
    }

    public function scopeUrgent($query)
    {
        return $query->where(function ($q) {
            $q->where('message', 'like', '%urgente%')
              ->orWhere('message', 'like', '%emergência%')
              ->orWhere('message', 'like', '%crítico%')
              ->orWhere('message', 'like', '%problema%');
        });
    }

    public function scopeWithNewsletter($query)
    {
        return $query->where('newsletter', true);
    }

    // Acessors
    public function getIsUrgentAttribute(): bool
    {
        $urgentKeywords = ['urgente', 'emergência', 'crítico', 'problema'];
        $message = strtolower($this->message);
        
        foreach ($urgentKeywords as $keyword) {
            if (str_contains($message, $keyword)) {
                return true;
            }
        }
        
        return false;
    }
} 