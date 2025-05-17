<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Website extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'url',
        'api_key',
        'secret_key',
        'is_active',
        'contact_email',
        'description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'settings' => 'array',
    ];

    /**
     * Boot function to handle model events
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($website) {
            // Generate API key if not provided
            if (empty($website->api_key)) {
                $website->api_key = Str::random(32);
            }

            // Generate secret key if not provided
            if (empty($website->secret_key)) {
                $website->secret_key = Str::random(64);
            }

            // Default to active if not set
            if (!isset($website->is_active)) {
                $website->is_active = true;
            }
        });
    }

    /**
     * Get the contacts for the website.
     */
    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    /**
     * Get the users managing this website.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'website_user', 'website_id', 'user_id');
    }

    /**
     * Scope a query to only include active websites.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Verify if the provided API key and secret are valid
     *
     * @param string $apiKey
     * @param string $secretKey
     * @return bool
     */
    public function verifyApiCredentials($apiKey, $secretKey = null): bool
    {
        if ($this->api_key !== $apiKey) {
            return false;
        }

        if ($secretKey !== null && $this->secret_key !== $secretKey) {
            return false;
        }

        return true;
    }
}
