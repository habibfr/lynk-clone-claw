<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Dashboard') }}
            </h2>
            <a href="{{ route('links.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Add New Link
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Profile Info -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if(auth()->user()->profile)
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-lg font-semibold">Your Profile</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ config('app.url') }}/{{ auth()->user()->profile->username }}
                                </p>
                            </div>
                            <a href="{{ route('public.profile', auth()->user()->profile->username) }}" 
                               target="_blank"
                               class="text-blue-500 hover:text-blue-700">
                                View Public Profile →
                            </a>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-600 dark:text-gray-400 mb-4">You haven't set up your profile yet.</p>
                            <a href="{{ route('profile.edit') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Setup Profile
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Stats -->
            @if(auth()->user()->profile)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm text-gray-600 dark:text-gray-400">Total Links</div>
                        <div class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                            {{ auth()->user()->profile->links->count() }}
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm text-gray-600 dark:text-gray-400">Total Clicks</div>
                        <div class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                            {{ auth()->user()->profile->links->sum('clicks') }}
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm text-gray-600 dark:text-gray-400">Active Links</div>
                        <div class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                            {{ auth()->user()->profile->links->where('is_active', true)->count() }}
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
