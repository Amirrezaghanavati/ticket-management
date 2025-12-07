<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('My Tickets') }}
            </h2>
            <a href="{{ route('tickets.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-all duration-200 shadow-sm hover:shadow-md">
                <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                {{ __('Create Ticket') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Success Message -->
            @if (session('success'))
                <div
                    class="mb-6 bg-green-50 dark:bg-green-900 border border-green-200 dark:border-green-700 rounded-lg p-4">
                    <div class="flex">
                        <div class="shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('success') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Tickets List -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-xl">
                <div class="p-6">
                    @if ($tickets->count() > 0)
                        <div class="space-y-4">
                            @foreach ($tickets as $ticket)
                                <a href="{{ route('tickets.show', $ticket) }}"
                                    class="group block p-6 border border-gray-200 dark:border-gray-700 rounded-xl hover:border-indigo-300 dark:hover:border-indigo-600 hover:shadow-lg transition-all duration-300 bg-white dark:bg-gray-800">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-3 mb-2">
                                                <h3
                                                    class="text-lg font-semibold text-gray-900 dark:text-white truncate">
                                                    {{ $ticket->title }}
                                                </h3>
                                                <span
                                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium shrink-0 {{ $ticket->status->badgeClasses() }}">
                                                    {{ $ticket->status->label() }}
                                                </span>
                                            </div>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2 mb-4">
                                                {{ Str::limit($ticket->message ?? '', 150) }}
                                            </p>
                                            <div
                                                class="flex flex-wrap items-center gap-4 text-xs text-gray-500 dark:text-gray-400">
                                                <span class="inline-flex items-center gap-1.5">
                                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                    <span>{{ $ticket->user->name }}</span>
                                                </span>
                                                <span class="inline-flex items-center gap-1.5">
                                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <span>{{ $ticket->created_at->format('M d, Y') }}</span>
                                                </span>
                                                @if ($ticket->file_url)
                                                    <span
                                                        class="inline-flex items-center gap-1.5 text-indigo-600 dark:text-indigo-400">
                                                        <svg class="w-4 h-4 shrink-0" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24"
                                                            aria-hidden="true">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                                        </svg>
                                                        <span>{{ __('Attachment') }}</span>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="ml-4 shrink-0">
                                            <svg class="w-5 h-5 text-gray-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7" />
                                            </svg>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $tickets->links() }}
                        </div>
                    @else
                        <div class="text-center py-16">
                            <svg class="mx-auto h-16 w-16 text-gray-400 dark:text-gray-500" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-4 text-base font-semibold text-gray-900 dark:text-white">
                                {{ __('No tickets found') }}
                            </h3>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                {{ __('Get started by creating a new ticket.') }}
                            </p>
                            <div class="mt-6">
                                <a href="{{ route('tickets.create') }}"
                                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-all duration-200 shadow-sm hover:shadow-md">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                    {{ __('Create Ticket') }}
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
