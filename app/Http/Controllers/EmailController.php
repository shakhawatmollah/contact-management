<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use App\Models\Email;
use App\Models\Contact;
use App\Http\Requests\EmailRequest;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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

    public function compose()
    {
        $contacts = Contact::orderBy('name')->get();
        return view('emails.compose', compact('contacts'));
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'contact_id' => 'required|exists:contacts,id',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'save_template' => 'nullable|boolean'
        ]);

        $contact = Contact::findOrFail($validated['contact_id']);

        $email = new Email([
            'contact_id' => $contact->id,
            'subject' => $validated['subject'],
            'body' => $validated['body'],
            'from_email' => config('mail.from.address'),
            'to_email' => $contact->email,
        ]);

        // Save the email first
        $email->save();

        // Send the email
        Mail::to($contact->email)->send(new ContactMail($email));

        // Update sent_at after successful sending
        $email->update(['sent_at' => now()]);

        if ($request->save_template) {
            // Save as template logic here
        }

        return redirect()->route('emails.index')
            ->with('success', 'Email sent successfully!');
    }
}
