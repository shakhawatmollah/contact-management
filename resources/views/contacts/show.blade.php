@extends('layouts.app')

@section('content')
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-2xl font-semibold text-gray-800">Contact Details</h2>
            <a href="{{ route('emails.reply', $contact) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                Reply
            </a>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Contact Information</h3>
                    <div class="mt-4 space-y-2">
                        <p><span class="font-medium">Name:</span> {{ $contact->name }}</p>
                        <p><span class="font-medium">Email:</span> {{ $contact->email }}</p>
                        <p><span class="font-medium">Phone:</span> {{ $contact->phone ?? 'N/A' }}</p>
                        <p><span class="font-medium">Received:</span> {{ $contact->created_at->format('M j, Y g:i A') }}</p>
                        <p><span class="font-medium">Source:</span> {{ $contact->source_website ?? 'Direct' }}</p>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-medium text-gray-900">Message</h3>
                    <div class="mt-4 p-4 bg-gray-50 rounded-md">
                        {{ $contact->message }}
                    </div>
                </div>
            </div>

            @if($contact->emails->count() > 0)
                <div class="mt-8">
                    <h3 class="text-lg font-medium text-gray-900">Email History</h3>
                    <div class="mt-4 space-y-4">
                        @foreach($contact->emails as $email)
                            <div class="p-4 border border-gray-200 rounded-md">
                                <div class="flex justify-between">
                                    <span class="font-medium">Subject: {{ $email->subject }}</span>
                                    <span class="text-sm text-gray-500">{{ $email->created_at->format('M j, Y g:i A') }}</span>
                                </div>
                                <div class="mt-2 text-sm text-gray-600">
                                    {{ Str::limit($email->body, 200) }}
                                </div>
                                <div class="mt-2 text-right">
                                    <a href="{{ route('emails.show', $email) }}" class="text-indigo-600 hover:text-indigo-900 text-sm">
                                        View Email
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
