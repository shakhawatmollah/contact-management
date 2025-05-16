<?php

namespace App\Services;

use App\Models\Email;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactReplyMail;
use Illuminate\Support\Facades\Log;

class EmailService
{
    public function sendEmail(Email $email): bool
    {
        try {
            Mail::to($email->to_email)
                ->send(new ContactReplyMail($email));

            $email->update(['sent_at' => now()]);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send email: ' . $e->getMessage());
            return false;
        }
    }

    public function createEmail(array $data): Email
    {
        return Email::create($data);
    }

    public function markAsRead(Email $email): bool
    {
        return $email->update(['read_at' => now()]);
    }
}
