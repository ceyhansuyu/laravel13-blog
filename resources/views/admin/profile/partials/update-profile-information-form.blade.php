<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-200 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" class="font-medium" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full transition-shadow focus:ring-2 focus:ring-indigo-500/50" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" class="font-medium" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full transition-shadow focus:ring-2 focus:ring-indigo-500/50" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3 p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800/50 rounded-lg">
                    <p class="text-sm text-amber-800 dark:text-amber-300">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="ml-1 underline font-medium text-amber-600 dark:text-amber-400 hover:text-amber-900 dark:hover:text-amber-200 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 dark:focus:ring-offset-gray-800 transition-colors cursor-pointer">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif

            <div class="mt-4 flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors inline-block w-full sm:w-auto">
                <label for="show_email" class="inline-flex relative items-center cursor-pointer w-full">
                    <input type="hidden" name="show_email" value="0">
                    <input type="checkbox" 
                           id="show_email" 
                           name="show_email" 
                           value="1" 
                           class="sr-only peer"
                           {{ old('show_email', $user->show_email) == 1 ? 'checked' : '' }}>
                    
                    <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-indigo-500/50 dark:peer-focus:ring-indigo-500/30 rounded-full peer dark:bg-gray-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-500 peer-checked:bg-indigo-600 dark:peer-checked:bg-indigo-500"></div>
                    
                    <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300 select-none">
                        {{ __('Show my email address on my profile') }}
                    </span>
                </label>
                <x-input-error class="mt-1" :messages="$errors->get('show_email')" />
            </div>
        </div>

        <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
            <x-input-label for="avatar" :value="__('Profile Picture')" class="font-medium" />
            
            @if($user->avatar)
                <div class="mt-3 mb-4 flex items-center gap-4 bg-gray-50 dark:bg-gray-800/50 p-4 rounded-xl border border-gray-200 dark:border-gray-700 max-w-md transition-colors hover:border-indigo-300 dark:hover:border-indigo-500/50">
                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-16 h-16 rounded-full object-cover border-2 border-white dark:border-gray-600 shadow-md">
                    <div>
                        <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ __('Your Current Profile Picture') }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ __('This will be kept unless you select a new picture.') }}</p>
                    </div>
                </div>
            @endif

            <input id="avatar" name="avatar" type="file" accept="image/*" class="mt-2 block w-full text-sm text-gray-600 dark:text-gray-400
                file:mr-4 file:py-2.5 file:px-4
                file:rounded-lg file:border-0
                file:text-sm file:font-semibold
                file:bg-indigo-50 file:text-indigo-700
                hover:file:bg-indigo-100
                dark:file:bg-gray-700 dark:file:text-indigo-300 dark:hover:file:bg-gray-600 transition-all cursor-pointer border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 shadow-sm" />
                
            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1.5">
                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ __('Allowed formats:') }} <strong class="text-gray-700 dark:text-gray-300">PNG, JPG, WEBP</strong> | {{ __('Size:') }} <strong class="text-gray-700 dark:text-gray-300">{{ __('Max 2 MB') }}</strong>
            </p>
            
            <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
        </div>

        <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
            <x-input-label for="bio" :value="__('About Me (Biography)')" class="font-medium" />
            <textarea id="bio" name="bio" class="mt-2 p-3 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/50 rounded-lg shadow-sm text-sm transition-shadow" rows="4" placeholder="{{ __('Write a short biography that will appear below your posts...') }}">{{ old('bio', $user->bio) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('bio')" />
        </div>

        <div class="pt-4 border-t border-gray-200 dark:border-gray-700 space-y-4">
            <h3 class="text-sm font-medium text-gray-900 dark:text-gray-200 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                </svg>
                {{ __('Social Media Links') }}
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="github_url" :value="__('GitHub Profile')" class="text-xs text-gray-500 dark:text-gray-400" />
                    <x-text-input id="github_url" name="github_url" type="url" class="mt-1 block w-full transition-shadow focus:ring-2 focus:ring-indigo-500/50" :value="old('github_url', $user->github_url)" placeholder="https://github.com/kullaniciadi" />
                    <x-input-error class="mt-2" :messages="$errors->get('github_url')" />
                </div>

                <div>
                    <x-input-label for="linkedin_url" :value="__('LinkedIn Profile')" class="text-xs text-gray-500 dark:text-gray-400" />
                    <x-text-input id="linkedin_url" name="linkedin_url" type="url" class="mt-1 block w-full transition-shadow focus:ring-2 focus:ring-indigo-500/50" :value="old('linkedin_url', $user->linkedin_url)" placeholder="https://linkedin.com/in/kullaniciadi" />
                    <x-input-error class="mt-2" :messages="$errors->get('linkedin_url')" />
                </div>

                <div class="md:col-span-2">
                    <x-input-label for="twitter_url" :value="__('Twitter / X Profile')" class="text-xs text-gray-500 dark:text-gray-400" />
                    <x-text-input id="twitter_url" name="twitter_url" type="url" class="mt-1 block w-full transition-shadow focus:ring-2 focus:ring-indigo-500/50" :value="old('twitter_url', $user->twitter_url)" placeholder="https://x.com/kullaniciadi" />
                    <x-input-error class="mt-2" :messages="$errors->get('twitter_url')" />
                </div>
            </div>
        </div>

        <div class="flex items-center gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
            <x-primary-button class="cursor-pointer px-6 py-2.5 transition-transform hover:scale-105 shadow-md hover:shadow-lg flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                {{ __('Save') }}
            </x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    data-start
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2500)"
                    class="text-sm font-medium text-green-600 dark:text-green-400 flex items-center gap-1"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ __('Saved.') }}
                </p>
            @endif
        </div>
    </form>
</section>