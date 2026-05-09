<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        Welcome! Let's set up your profile to get started.
    </div>

    <form method="POST" action="{{ route('profile.setup.store') }}">
        @csrf

        <!-- Username -->
        <div>
            <x-input-label for="username" :value="__('Username')" />
            <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')" required autofocus />
            <p class="mt-1 text-xs text-gray-500">This will be your public URL: lynk.hafarou.my.id/<span class="font-semibold">username</span></p>
            <x-input-error :messages="$errors->get('username')" class="mt-2" />
        </div>

        <!-- Display Name -->
        <div class="mt-4">
            <x-input-label for="display_name" :value="__('Display Name (Optional)')" />
            <x-text-input id="display_name" class="block mt-1 w-full" type="text" name="display_name" :value="old('display_name')" />
            <x-input-error :messages="$errors->get('display_name')" class="mt-2" />
        </div>

        <!-- Bio -->
        <div class="mt-4">
            <x-input-label for="bio" :value="__('Bio (Optional)')" />
            <textarea id="bio" name="bio" rows="3" 
                      class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('bio') }}</textarea>
            <x-input-error :messages="$errors->get('bio')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Create Profile') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
