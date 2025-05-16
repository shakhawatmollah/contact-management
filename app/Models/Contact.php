<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'phone', 'message',
        'source_website', 'ip_address', 'metadata'
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function emails()
    {
        return $this->hasMany(Email::class);
    }
}
