<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Http\Requests\ContactFormRequest;
use App\Services\ContactService;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    protected $contactService;

    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;
    }

    public function index()
    {
        $contacts = Contact::with('emails')
            ->latest()
            ->paginate(20);

        return view('contacts.index', compact('contacts'));
    }

    public function show(Contact $contact)
    {
        return view('contacts.show', compact('contact'));
    }

    public function store(ContactFormRequest $request)
    {
        $data = $request->validated();
        $data['ip_address'] = $request->ip();

        $contact = $this->contactService->createContact($data);

        return redirect()->back()->with('success', 'Thank you for contacting us!');
    }
}
