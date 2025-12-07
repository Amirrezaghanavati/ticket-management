<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('tickets.index') }}"
                class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Ticket Details') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Ticket Header -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ $ticket->title }}</h1>
                            <div class="flex items-center gap-4 text-sm text-gray-600 dark:text-gray-400">
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                        </path>
                                    </svg>
                                    {{ $ticket->user->name }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $ticket->created_at->format('F d, Y \a\t g:i A') }}
                                </span>
                            </div>
                        </div>
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $ticket->status->badgeClasses() }}">
                            {{ $ticket->status->label() }}
                        </span>
                    </div>

                    <!-- Ticket Message -->
                    <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <p class="text-gray-900 dark:text-white whitespace-pre-wrap">{{ $ticket->message }}</p>
                    </div>

                    <!-- Attachment -->
                    @if ($ticket->file_url)
                        <div class="mt-4 flex items-center gap-2 p-3 bg-indigo-50 dark:bg-indigo-900 rounded-lg">
                            <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                                </path>
                            </svg>
                            <span
                                class="text-sm text-indigo-700 dark:text-indigo-300 font-medium">{{ __('Attachment') }}</span>
                            <a href="{{ asset('storage/' . $ticket->file_url) }}" target="_blank"
                                class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-200 underline">
                                {{ __('View File') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Conversation Thread -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('Response') }}</h2>

                    <!-- Admin Response -->
                    @if ($adminResponse)
                        <div class="mb-6">
                            <div class="flex gap-4">
                                <div class="shrink-0">
                                    <div
                                        class="h-10 w-10 rounded-full bg-linear-to-br from-blue-500 to-cyan-600 flex items-center justify-center shadow-lg">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div
                                        class="bg-blue-50 dark:bg-blue-900/30 rounded-xl p-5 border border-blue-200 dark:border-blue-800 shadow-sm">
                                        <div class="flex items-center justify-between mb-3">
                                            <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                                {{ $adminResponse->user ? $adminResponse->user->name : __('Admin') }}
                                            </span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $adminResponse->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                        <p
                                            class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap leading-relaxed">
                                            {{ $adminResponse->message ?? __('No message') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-gray-400 dark:text-gray-600 mx-auto mb-4" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                </path>
                            </svg>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('No admin response yet.') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
