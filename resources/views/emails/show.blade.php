@extends('layouts.app')

@section('content')
    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Email Details
                        </h3>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        Sent {{ $email->sent_at?->diffForHumans() }}
                    </span>
                    </div>
                    <div class="mt-1 max-w-2xl text-sm text-gray-500">
                        Message ID: {{ $email->id }}
                    </div>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    <div class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">
                                From
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $email->from_email }}
                            </dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">
                                To
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $email->to_email }}
                            </dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">
                                Contact
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <a href="{{ route('contacts.show', $email->contact) }}" class="text-indigo-600 hover:text-indigo-900">
                                    {{ $email->contact->name }}
                                </a>
                            </dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">
                                Date Sent
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $email->sent_at?->format('F j, Y, g:i a') }}
                            </dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">
                                Subject
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 font-semibold">
                                {{ $email->subject }}
                            </dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">
                                Message
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 whitespace-pre-line">
                                {{ $email->body }}
                            </dd>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-4 sm:px-6 border-t border-gray-200 bg-gray-50 flex justify-end">
                    <a href="{{ route('emails.reply', $email->contact) }}"
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Reply
                    </a>
                    <a href="{{ route('emails.index') }}"
                       class="ml-3 inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Back to Emails
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
