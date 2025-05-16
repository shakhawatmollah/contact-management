@extends('layouts.app')

@section('content')
    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800">Compose New Email</h2>
                </div>

                <form action="{{ route('emails.send') }}" method="POST">
                    @csrf

                    <div class="px-6 py-4 space-y-6">
                        <div>
                            <label for="contact_id" class="block text-sm font-medium text-gray-700">Recipient</label>
                            <select id="contact_id" name="contact_id" required
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="">Select a contact</option>
                                @foreach($contacts as $contact)
                                    <option value="{{ $contact->id }}">{{ $contact->name }} &lt;{{ $contact->email }}&gt;</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700">Subject</label>
                            <input type="text" name="subject" id="subject" required
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="body" class="block text-sm font-medium text-gray-700">Message</label>
                            <textarea id="body" name="body" rows="12"
                                      class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                        </div>

                        <div class="flex items-center">
                            <input id="save_template" name="save_template" type="checkbox"
                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label for="save_template" class="ml-2 block text-sm text-gray-900">
                                Save as template
                            </label>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end">
                        <a href="{{ route('emails.index') }}"
                           class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancel
                        </a>
                        <button type="submit"
                                class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Send Email
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Simple editor enhancement (could be replaced with a proper editor like TinyMCE)
            const textarea = document.getElementById('body');
            textarea.addEventListener('keydown', function(e) {
                if (e.key === 'Tab') {
                    e.preventDefault();
                    const start = this.selectionStart;
                    const end = this.selectionEnd;

                    this.value = this.value.substring(0, start) + '\t' + this.value.substring(end);
                    this.selectionStart = this.selectionEnd = start + 1;
                }
            });
        });
    </script>
@endpush
