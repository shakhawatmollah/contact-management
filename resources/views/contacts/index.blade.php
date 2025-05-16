@extends('layouts.app')

@section('content')
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-2xl font-semibold text-gray-800">Contact Messages</h2>
        </div>

        <div class="divide-y divide-gray-200">
            @foreach ($contacts as $contact)
                <div class="p-6 hover:bg-gray-50">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">{{ $contact->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $contact->email }}</p>
                            <p class="mt-1 text-sm text-gray-600 truncate">{{ Str::limit($contact->message, 100) }}</p>
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ $contact->created_at->diffForHumans() }}
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end">
                        <a href="{{ route('contacts.show', $contact) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                            View Details
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="px-6 py-4 border-t border-gray-200">
            {{ $contacts->links() }}
        </div>
    </div>
@endsection
