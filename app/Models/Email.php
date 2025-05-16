<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Email extends Model
{
    use HasFactory;

    protected $fillable = [
        'contact_id', 'subject', 'body',
        'from_email', 'to_email', 'sent_at', 'read_at'
    ];

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
}
