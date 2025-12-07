<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.tickets.index') }}"
                class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
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
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Success Message -->
            @if (session('success'))
                <div
                    class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 rounded-xl p-4 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="shrink-0">
                            <svg class="h-5 w-5 text-emerald-600 dark:text-emerald-400" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-emerald-800 dark:text-emerald-200">{{ session('success') }}
                        </p>
                    </div>
                </div>
            @endif

            <!-- Error Messages -->
            @if ($errors->any())
                <div
                    class="bg-rose-50 dark:bg-rose-900/30 border border-rose-200 dark:border-rose-800 rounded-xl p-4 shadow-sm">
                    <div class="flex items-center gap-3 mb-2">
                        <svg class="h-5 w-5 text-rose-600 dark:text-rose-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                        <p class="text-sm font-medium text-rose-800 dark:text-rose-200">
                            {{ __('Please fix the following errors:') }}</p>
                    </div>
                    <ul class="list-disc list-inside text-sm text-rose-700 dark:text-rose-300 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Ticket Header Card -->
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl border border-gray-200 dark:border-gray-700">
                <div class="p-8">
                    <div class="flex items-start justify-between gap-6 mb-6">
                        <div class="flex-1 min-w-0">
                            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">{{ $ticket->title }}</h1>
                            <div class="flex flex-wrap items-center gap-6 text-sm text-gray-600 dark:text-gray-400">
                                <span class="flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                        </path>
                                    </svg>
                                    <span class="font-medium">{{ $ticket->user->name }}</span>
                                </span>
                                <span class="flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>{{ $ticket->created_at->format('F d, Y \a\t g:i A') }}</span>
                                </span>
                            </div>
                        </div>
                        <span
                            class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold shadow-sm {{ $ticket->status->badgeClasses() }}">
                            {{ $ticket->status->label() }}
                        </span>
                    </div>

                    <!-- Ticket Message -->
                    <div
                        class="mt-6 p-6 bg-linear-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 rounded-xl border border-gray-200 dark:border-gray-600">
                        <p class="text-gray-900 dark:text-white">
                            {{ $ticket->message }}
                        </p>
                    </div>

                    <!-- Attachment -->
                    @if ($ticket->file_url)
                        <div
                            class="mt-6 flex items-center gap-3 p-4 bg-indigo-50 dark:bg-indigo-900/30 rounded-xl border border-indigo-200 dark:border-indigo-800">
                            <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400 shrink-0" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                                </path>
                            </svg>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-indigo-900 dark:text-indigo-200">
                                    {{ __('Attachment') }}</p>
                                <a href="{{ asset('storage/' . $ticket->file_url) }}" target="_blank"
                                    class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-200 underline inline-flex items-center gap-1 mt-1">
                                    <span>{{ __('View File') }}</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                        </path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            @php
                $currentAdmin = Auth::user();
                $canProcess = Gate::forUser($currentAdmin)->allows('process', $ticket);
                $isAdmin1 = $currentAdmin->isAdmin1();
                $isAdmin2 = $currentAdmin->isAdmin2();
            @endphp

            <!-- Action Form - Only show if admin can process this ticket -->
            @if ($canProcess)
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl border border-gray-200 dark:border-gray-700">
                    <div class="p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="p-2 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg">
                                <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ $isAdmin1 ? __('Review Ticket') : __('Final Review') }}
                            </h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Approve Form -->
                            <form method="POST" action="{{ route('admin.tickets.approve', $ticket) }}"
                                id="approve-form" class="space-y-6">
                                @csrf
                                <input type="hidden" name="action" value="approve">

                                <div
                                    class="p-5 rounded-xl border-2 border-emerald-500 bg-emerald-50 dark:bg-emerald-900/20 dark:border-emerald-400">
                                    <div class="flex items-center gap-2 mb-3">
                                        <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
                                            {{ $isAdmin1 ? __('Approve Ticket') : __('Approve and Send to Web Service') }}
                                        </h4>
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                        {{ $isAdmin1 ? __('Approve this ticket and forward it to Admin 2 for final review.') : __('Approve this ticket and send it to the web service for processing.') }}
                                    </p>

                                    <!-- Message Field -->
                                    <div class="space-y-2">
                                        <label for="approve-message"
                                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                            {{ __('Message') }} <span
                                                class="text-gray-500 dark:text-gray-400">({{ __('Optional') }})</span>
                                        </label>
                                        <textarea id="approve-message" name="message" rows="4"
                                            class="block w-full px-4 py-3 rounded-xl border-gray-300 dark:border-gray-600 shadow-sm focus:border-emerald-500 dark:focus:border-emerald-400 focus:ring-emerald-500 dark:focus:ring-emerald-400 focus:ring-2 focus:outline-none transition-all duration-200 dark:bg-gray-700 dark:text-white placeholder:text-gray-400 dark:placeholder:text-gray-500 resize-none"></textarea>
                                    </div>

                                    <!-- Submit Button -->
                                    <button type="submit"
                                        class="mt-4 w-full px-6 py-3 text-sm font-semibold text-white bg-emerald-600 dark:bg-emerald-500 rounded-xl hover:bg-emerald-700 dark:hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:focus:ring-emerald-400 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-all duration-200 shadow-sm hover:shadow-md disabled:opacity-50 disabled:cursor-not-allowed">
                                        <span class="flex items-center justify-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span>{{ __('Approve Ticket') }}</span>
                                        </span>
                                    </button>
                                </div>
                            </form>

                            <!-- Reject Form -->
                            <form method="POST" action="{{ route('admin.tickets.reject', $ticket) }}"
                                id="reject-form" class="space-y-6">
                                @csrf
                                <input type="hidden" name="action" value="reject">

                                <div
                                    class="p-5 rounded-xl border-2 border-rose-500 bg-rose-50 dark:bg-rose-900/20 dark:border-rose-400">
                                    <div class="flex items-center gap-2 mb-3">
                                        <svg class="w-6 h-6 text-rose-600 dark:text-rose-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z">
                                            </path>
                                        </svg>
                                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
                                            {{ __('Reject Ticket') }}
                                        </h4>
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                        {{ __('Reject this ticket with a reason. The ticket will be closed.') }}
                                    </p>

                                    <!-- Message Field -->
                                    <div class="space-y-2">
                                        <label for="reject-message"
                                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                            {{ __('Message') }} <span
                                                class="text-rose-600 dark:text-rose-400">*</span>
                                        </label>
                                        <textarea id="reject-message" name="message" rows="4" required
                                            class="block w-full px-4 py-3 rounded-xl border-gray-300 dark:border-gray-600 shadow-sm focus:border-rose-500 dark:focus:border-rose-400 focus:ring-rose-500 dark:focus:ring-rose-400 focus:ring-2 focus:outline-none transition-all duration-200 dark:bg-gray-700 dark:text-white placeholder:text-gray-400 dark:placeholder:text-gray-500 resize-none"></textarea>
                                    </div>

                                    <!-- Submit Button -->
                                    <button type="submit"
                                        class="mt-4 w-full px-6 py-3 text-sm font-semibold text-white bg-rose-600 dark:bg-rose-500 rounded-xl hover:bg-rose-700 dark:hover:bg-rose-600 focus:outline-none focus:ring-2 focus:ring-rose-500 dark:focus:ring-rose-400 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-all duration-200 shadow-sm hover:shadow-md disabled:opacity-50 disabled:cursor-not-allowed">
                                        <span class="flex items-center justify-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            <span>{{ __('Reject Ticket') }}</span>
                                        </span>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Cancel Button -->
                        <div class="flex justify-end pt-4 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('admin.tickets.index') }}"
                                class="px-6 py-3 text-sm font-semibold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <!-- Info Message if admin cannot process -->
                <div
                    class="bg-amber-50 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-800 rounded-xl p-6 shadow-sm">
                    <div class="flex items-center gap-3">
                        <svg class="w-6 h-6 text-amber-600 dark:text-amber-400 shrink-0" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-amber-900 dark:text-amber-200">
                                {{ __('No Action Available') }}</p>
                            <p class="text-sm text-amber-800 dark:text-amber-300 mt-1">
                                {{ __('This ticket is not in a status that requires your action.') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Conversation History -->
            @if ($ticket->logs->count() > 0)
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl border border-gray-200 dark:border-gray-700">
                    <div class="p-8">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">
                            {{ __('Conversation History') }}
                        </h3>
                        <div class="space-y-4">
                            @foreach ($ticket->logs->sortByDesc('created_at') as $log)
                                <div
                                    class="p-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                                    <div class="flex items-start justify-between gap-4 mb-2">
                                        <div class="flex items-center gap-2">
                                            <span class="font-semibold text-gray-900 dark:text-white">
                                                {{ $log->user?->name ?? __('System') }}
                                            </span>
                                            @if ($log->user?->isAdmin())
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300">
                                                    {{ __('Admin') }}
                                                </span>
                                            @endif
                                        </div>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $log->created_at->format('M d, Y g:i A') }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap">
                                        {{ $log->message }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
