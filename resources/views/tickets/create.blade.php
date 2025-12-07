<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('tickets.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h2 class="font-semibold text-lg text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Create New Ticket') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    <form method="POST" action="{{ route('tickets.store') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        <!-- Title -->
                        <div>
                            <x-input-label for="title" :value="__('Subject')" class="mb-5 block" />
                            <x-text-input id="title" class="block w-full px-5 py-4 transition-all duration-300 ease-in-out border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:ring-2 focus:outline-none dark:bg-gray-700 dark:text-white placeholder:text-gray-400 dark:placeholder:text-gray-400" type="text" name="title" :value="old('title')" required autofocus :placeholder="__('Enter ticket subject')" />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <!-- Description/Message -->
                        <div>
                            <x-input-label for="message" :value="__('Message')" class="mb-5 block" />
                            <textarea id="message" name="message" rows="6" required
                                class="block w-full px-5 py-4 rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:ring-2 focus:outline-none transition-all duration-300 ease-in-out dark:bg-gray-700 dark:text-white placeholder:text-gray-400 dark:placeholder:text-gray-400 resize-none"
                                :placeholder="__('Describe your issue or request in detail...')">{{ old('message') }}</textarea>
                            <x-input-error :messages="$errors->get('message')" class="mt-2" />
                        </div>

                        <!-- File Upload -->
                        <div>
                            <x-input-label for="file_url" :value="__('file_url')" class="mb-5 block" />
                            <div class="mt-5 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-md hover:border-indigo-400 dark:hover:border-indigo-500 bg-gray-50 dark:bg-gray-900/50 transition-all duration-300 ease-in-out">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-4h12m-4-4v12m0 0l-4-4m4 4l-4 4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600 dark:text-gray-300">
                                        <label for="file_url" class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 dark:focus-within:ring-offset-gray-800 focus-within:ring-indigo-500 dark:focus-within:ring-indigo-400 px-3 py-1.5 transition-colors duration-200">
                                            <span>{{ __('Upload a file') }}</span>
                                            <input id="file_url" name="file_url" type="file" accept=".pdf,.jpg,.jpeg,.png" required class="sr-only">
                                        </label>
                                        <p class="pl-1 self-center">{{ __('or drag and drop') }}</p>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('PDF, JPG, PNG up to 10MB') }}</p>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('file_url')" class="mt-2" />
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('tickets.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-all ease-in-out duration-200">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button class="inline-flex items-center">
                                <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                {{ __('Create Ticket') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

