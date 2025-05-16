<?php

namespace App\Http\Controllers;

use App\Models\Email;
use App\Models\Contact;
use App\Http\Requests\EmailRequest;
use App\Services\EmailService;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    protected $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    public function index()
    {
        $emails = Email::with('contact')
            ->latest()
            ->paginate(20);

        return view('emails.index', compact('emails'));
    }

    public function show(Email $email)
    {
        $this->emailService->markAsRead($email);
        return view('emails.show', compact('email'));
    }

    public function reply(Contact $contact)
    {
        return view('emails.reply', compact('contact'));
    }

    public function sendReply(EmailRequest $request, Contact $contact)
    {
        $data = $request->validated();
        $data['contact_id'] = $contact->id;
        $data['from_email'] = config('mail.from.address');
        $data['to_email'] = $contact->email;

        $email = $this->emailService->createEmail($data);
        $this->emailService->sendEmail($email);

        return redirect()->route('contacts.show', $contact)
            ->with('success', 'Email sent successfully!');
    }
}
