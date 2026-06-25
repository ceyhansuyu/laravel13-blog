<x-app-layout :title="__('Settings')">

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-300 leading-tight">
            {{ __('Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg transition-colors duration-200 border border-gray-100 dark:border-gray-700">
                <div class="max-w-2xl">
                    <section>
                        <header class="border-b border-gray-200 dark:border-gray-700 pb-4">
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-200 flex items-center gap-2">
                                
                                {{ __('Blog Settings') }}
                            </h2>

                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                {{ __('You can manage post listing and search behaviors on the blog homepage from here.') }}
                            </p>
                        </header>

                        <form method="post" action="{{ route('admin.settings.update') }}" class="mt-6 space-y-8" x-data="{ maintenanceMode: {{ old('maintenance_mode', $settings['maintenance_mode'] ?? false) ? 'true' : 'false' }}, webpQuality: {{ old('webp_quality', $settings['webp_quality'] ?? 80) }} }">
                            @csrf

                            <div class="space-y-6">
                                <div>
                                    <x-input-label for="site_name" :value="__('Site Name')" class="font-medium" />
                                    <x-text-input id="site_name" name="site_name" type="text" class="mt-1 block w-full transition-shadow focus:ring-2 focus:ring-indigo-500/50" :value="old('site_name', $settings['site_name'] ?? '')" required autofocus />
                                    <x-input-error class="mt-2" :messages="$errors->get('site_name')" />
                                </div>

                                <div>
                                    <x-input-label for="site_description" :value="__('Site Description (Meta Description)')" class="font-medium" />
                                    <textarea id="site_description" name="site_description" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-lg shadow-sm p-3 transition-shadow focus:ring-2 focus:ring-indigo-500/50">{{ old('site_description', $settings['site_description'] ?? '') }}</textarea>
                                    <x-input-error class="mt-2" :messages="$errors->get('site_description')" />
                                </div>

                                <div>
                                    <x-input-label for="google_analytics_id" :value="__('Google Analytics ID')" class="font-medium" />
                                    <x-text-input id="google_analytics_id" name="google_analytics_id" type="text" class="mt-1 block w-full transition-shadow focus:ring-2 focus:ring-indigo-500/50" :value="old('google_analytics_id', $settings['google_analytics_id'] ?? '')" placeholder="Örn: G-XXXXXX" />
                                    <x-input-error class="mt-2" :messages="$errors->get('google_analytics_id')" />
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-gray-200 dark:border-gray-700">
                                    <div>
                                        <x-input-label for="hcaptcha_site_key" :value="__('hCaptcha Site Key')" class="font-medium" />
                                        <x-text-input id="hcaptcha_site_key" name="hcaptcha_site_key" type="text" class="mt-1 block w-full transition-shadow focus:ring-2 focus:ring-indigo-500/50" :value="old('hcaptcha_site_key', $settings['hcaptcha_site_key'] ?? '')" />
                                        <x-input-error class="mt-2" :messages="$errors->get('hcaptcha_site_key')" />
                                    </div>
                                    <div>
                                        <x-input-label for="hcaptcha_secret_key" :value="__('hCaptcha Secret Key')" class="font-medium" />
                                        <x-text-input id="hcaptcha_secret_key" name="hcaptcha_secret_key" type="password" class="mt-1 block w-full transition-shadow focus:ring-2 focus:ring-indigo-500/50" :value="old('hcaptcha_secret_key', $settings['hcaptcha_secret_key'] ?? '')" />
                                        <x-input-error class="mt-2" :messages="$errors->get('hcaptcha_secret_key')" />
                                    </div>
                                </div>

                                <div>
                                    <x-input-label for="pagination_limit" :value="__('Pagination Limit')" class="font-medium" />
                                    <x-text-input id="pagination_limit" name="pagination_limit" type="number" class="mt-1 block w-full transition-shadow focus:ring-2 focus:ring-indigo-500/50" :value="old('pagination_limit', $settings['pagination_limit'] ?? 10)" required min="1" max="50" autocomplete="pagination_limit" />
                                    <x-input-error class="mt-2" :messages="$errors->get('pagination_limit')" />
                                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ __('Maximum number of posts to be shown on a single page on the homepage.') }}
                                    </p>
                                </div>

                                <div>
                                    <x-input-label :value="__('Fields to Search')" class="font-medium mb-2" />
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 p-4 bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-gray-200 dark:border-gray-700">
                                        <label class="flex items-center p-3 border border-gray-200 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-white dark:hover:bg-gray-700 transition-colors">
                                            <input type="checkbox" name="search_fields[]" value="title" class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-900 text-indigo-600 shadow-sm focus:ring-indigo-500 w-5 h-5" {{ in_array('title', old('search_fields', $settings['search_fields'] ?? [])) ? 'checked' : '' }}>
                                            <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Title') }}</span>
                                        </label>
                                        
                                        <label class="flex items-center p-3 border border-gray-200 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-white dark:hover:bg-gray-700 transition-colors">
                                            <input type="checkbox" name="search_fields[]" value="slug" class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-900 text-indigo-600 shadow-sm focus:ring-indigo-500 w-5 h-5" {{ in_array('slug', old('search_fields', $settings['search_fields'] ?? [])) ? 'checked' : '' }}>
                                            <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('URL Extension (Slug)') }}</span>
                                        </label>
                                        
                                        <label class="flex items-center p-3 border border-gray-200 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-white dark:hover:bg-gray-700 transition-colors">
                                            <input type="checkbox" name="search_fields[]" value="content" class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-900 text-indigo-600 shadow-sm focus:ring-indigo-500 w-5 h-5" {{ in_array('content', old('search_fields', $settings['search_fields'] ?? [])) ? 'checked' : '' }}>
                                            <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Content') }}</span>
                                        </label>
                                    </div>
                                    <x-input-error class="mt-2" :messages="$errors->get('search_fields')" />
                                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('Database columns to search for matches when the user searches.') }}</p>
                                </div>
                            </div>

                            <div class="border-t border-gray-200 dark:border-gray-700 pt-8">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-200 mb-5 flex items-center gap-2">
                                    {{ __('Blog View Settings') }}
                                </h3>
                                
                                <div class="grid gap-4 sm:grid-cols-2">
                                    <label class="flex items-start space-x-3 cursor-pointer p-4 rounded-xl border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                        <div class="flex-shrink-0 mt-0.5">
                                            <input type="checkbox" name="enable_social_share" class="w-5 h-5 rounded border-gray-300 dark:border-gray-600 dark:bg-gray-900 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ old('enable_social_share', $settings['enable_social_share'] ?? false) ? 'checked' : '' }}>
                                        </div>
                                        <div>
                                            <span class="block text-sm font-medium text-gray-900 dark:text-gray-200">{{ __('Post Sharing Feature') }}</span>
                                            <span class="block text-xs text-gray-500 dark:text-gray-400 mt-1">{{ __('If disabled, post sharing links will not be visible.') }}</span>
                                        </div>
                                    </label>

                                    <label class="flex items-start space-x-3 cursor-pointer p-4 rounded-xl border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                        <div class="flex-shrink-0 mt-0.5">
                                            <input type="checkbox" name="enable_author_card" class="w-5 h-5 rounded border-gray-300 dark:border-gray-600 dark:bg-gray-900 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ old('enable_author_card', $settings['enable_author_card'] ?? false) ? 'checked' : '' }}>
                                        </div>
                                        <div>
                                            <span class="block text-sm font-medium text-gray-900 dark:text-gray-200">{{ __('Author Card View') }}</span>
                                            <span class="block text-xs text-gray-500 dark:text-gray-400 mt-1">{{ __('If disabled, author cards at the end of posts will be hidden.') }}</span>
                                        </div>
                                    </label>


                                    <label class="flex items-start space-x-3 cursor-pointer p-4 rounded-xl border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                        <div class="flex-shrink-0 mt-0.5">
                                            <input type="checkbox" name="allow_submit_comments" class="w-5 h-5 rounded border-gray-300 dark:border-gray-600 dark:bg-gray-900 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ old('allow_submit_comments', $settings['allow_submit_comments'] ?? false) ? 'checked' : '' }}>
                                        </div>
                                        <div>
                                            <span class="block text-sm font-medium text-gray-900 dark:text-gray-200">{{ __('Enable Comment Submission') }}</span>
                                            <span class="block text-xs text-gray-500 dark:text-gray-400 mt-1">{{ __('If enabled, visitors can submit new comments to posts with hCaptcha protection.') }}</span>
                                        </div>
                                    </label>

                                    <label class="flex items-start space-x-3 cursor-pointer p-4 rounded-xl border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                        <div class="flex-shrink-0 mt-0.5">
                                            <input type="checkbox" name="allow_show_comments" class="w-5 h-5 rounded border-gray-300 dark:border-gray-600 dark:bg-gray-900 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ old('allow_show_comments', $settings['allow_show_comments'] ?? false) ? 'checked' : '' }}>
                                        </div>
                                        <div>
                                            <span class="block text-sm font-medium text-gray-900 dark:text-gray-200">{{ __('List Comments') }}</span>
                                            <span class="block text-xs text-gray-500 dark:text-gray-400 mt-1">{{ __('If enabled, approved old comments will be listed on the post detail page.') }}</span>
                                        </div>
                                    </label>

                                    <label class="flex items-start space-x-3 cursor-pointer p-4 rounded-xl border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                        <div class="flex-shrink-0 mt-0.5">
                                            <input type="checkbox" name="show_post_date" class="w-5 h-5 rounded border-gray-300 dark:border-gray-600 dark:bg-gray-900 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ old('show_post_date', $settings['show_post_date'] ?? false) ? 'checked' : '' }}>
                                        </div>
                                        <div>
                                            <span class="block text-sm font-medium text-gray-900 dark:text-gray-200">{{ __('Show Post Date') }}</span>
                                            <span class="block text-xs text-gray-500 dark:text-gray-400 mt-1">{{ __('If enabled, the creation date of the posts will be displayed on the frontend.') }}</span>
                                        </div>
                                    </label>

                                    <label class="flex items-start space-x-3 cursor-pointer p-4 rounded-xl border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                        <div class="flex-shrink-0 mt-0.5">
                                            <input type="checkbox" name="show_updated_date" class="w-5 h-5 rounded border-gray-300 dark:border-gray-600 dark:bg-gray-900 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ old('show_updated_date', $settings['show_updated_date'] ?? false) ? 'checked' : '' }}>
                                        </div>
                                        <div>
                                            <span class="block text-sm font-medium text-gray-900 dark:text-gray-200">{{ __('Show Updated Date') }}</span>
                                            <span class="block text-xs text-gray-500 dark:text-gray-400 mt-1">{{ __('If enabled, the last update date will be displayed if the post has been edited.') }}</span>
                                        </div>
                                    </label>





                                </div>
                            </div>

                            <div class="border-t border-gray-200 dark:border-gray-700 pt-8">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-200 mb-5 flex items-center gap-2">
                                    
                                    {{ __('System Settings') }}
                                </h3>
                                
                                <div class="space-y-4">
                                    <label class="flex items-start space-x-3 cursor-pointer p-4 rounded-xl border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                        <div class="flex-shrink-0 mt-0.5">
                                            <input type="checkbox" name="enable_search" class="w-5 h-5 rounded border-gray-300 dark:border-gray-600 dark:bg-gray-900 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ old('enable_search', $settings['enable_search'] ?? false) ? 'checked' : '' }}>
                                        </div>
                                        <div>
                                            <span class="block text-sm font-medium text-gray-900 dark:text-gray-200">{{ __('Search Feature Active') }}</span>
                                            <span class="block text-xs text-gray-500 dark:text-gray-400 mt-1">{{ __('If you uncheck this, the search bar on the blog will completely disappear and the search process will stop.') }}</span>
                                        </div>
                                    </label>

                                    <label class="flex items-start space-x-3 cursor-pointer p-4 rounded-xl border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                        <div class="flex-shrink-0 mt-0.5">
                                            <input type="checkbox" name="comment_moderation" value="1" class="w-5 h-5 rounded border-gray-300 dark:border-gray-600 dark:bg-gray-900 text-green-600 shadow-sm focus:ring-green-500" {{ old('comment_moderation', $settings['comment_moderation'] ?? false) ? 'checked' : '' }}>
                                        </div>
                                        <div>
                                            <span class="block text-sm font-medium text-gray-900 dark:text-gray-200">{{ __('Comment Moderation Active') }}</span>
                                            <span class="block text-xs text-gray-500 dark:text-gray-400 mt-1">{{ __('If you check this, new comments will require admin approval before being published.') }}</span>
                                        </div>
                                    </label>

                                    <label class="flex items-start space-x-3 cursor-pointer p-4 rounded-xl border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                        <div class="flex-shrink-0 mt-0.5">
                                            <input type="checkbox" name="enable_registration" class="w-5 h-5 rounded border-gray-300 dark:border-gray-600 dark:bg-gray-900 text-green-600 shadow-sm focus:ring-green-500" {{ old('enable_registration', $settings['enable_registration'] ?? false) ? 'checked' : '' }}>
                                        </div>
                                        <div>
                                            <span class="block text-sm font-medium text-gray-900 dark:text-gray-200">{{ __('New Member Registration Active') }}</span>
                                            <span class="block text-xs text-gray-500 dark:text-gray-400 mt-1">{{ __('If you uncheck this, new users cannot register to the system.') }}</span>
                                        </div>
                                    </label>

                                    <label class="flex items-start space-x-3 p-4 rounded-xl border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                        <div class="flex-shrink-0 mt-0.5">
                                            <select name="toast_duration" class="block w-full rounded-lg border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 text-sm text-gray-900 dark:text-gray-200 shadow-sm focus:border-indigo-400 focus:ring-indigo-400 py-2 px-2 transition-colors duration-200">
                                                @text
                                                @for ($ms = 500; $ms <= 8000; $ms += 500)
                                                    <option value="{{ $ms }}" {{ old('toast_duration', $siteSettings['toast_duration'] ?? '3000') == $ms ? 'selected' : '' }}>
                                                        {{ $ms }} ms ({{ $ms / 1000 }}s)
                                                    </option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="flex-1">
                                            <span class="block text-sm font-medium text-gray-900 dark:text-gray-200">{{ __('Toast Notification Duration') }}</span>
                                            <span class="block text-xs text-gray-500 dark:text-gray-400 mt-1">{{ __('Set how long the toast notification alerts stay on the screen in milliseconds.') }}</span>
                                        </div>
                                    </label>


                                    <div class="rounded-xl border-2 transition-colors duration-300" :class="maintenanceMode ? 'border-red-500/50 bg-gray-50 dark:bg-red-900/10 dark:border-red-500/30' : 'border-transparent bg-gray-50 dark:bg-gray-800/30 border-gray-200 dark:border-gray-700'">
                                        <label class="flex items-start space-x-3 cursor-pointer p-4">
                                            <div class="flex-shrink-0 mt-1">
                                                <input type="checkbox" name="maintenance_mode" x-model="maintenanceMode" class="w-5 h-5 rounded border-gray-300 dark:border-gray-600 dark:bg-gray-900 text-red-600 shadow-sm focus:ring-red-500">
                                            </div>
                                            <div class="flex-1">
                                                <span class="flex items-center text-sm font-bold text-gray-900 dark:text-gray-200" :class="maintenanceMode ? 'text-red-700 dark:text-red-400' : ''">
                                                    {{ __('Maintenance Mode') }}
                                                    
                                                </span>
                                                <span class="block text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                    {{ __('When maintenance mode is active, visitors cannot see the site, only authorized logged-in users can.') }}
                                                </span>
                                            </div>
                                        </label>

                                        <div x-show="maintenanceMode" 
                                             x-transition:enter="transition ease-out duration-300"
                                             x-transition:enter-start="opacity-0 max-h-0 overflow-hidden"
                                             x-transition:enter-end="opacity-100 max-h-40 overflow-visible"
                                             x-transition:leave="transition ease-in duration-200"
                                             x-transition:leave-start="opacity-100 max-h-40 overflow-visible"
                                             x-transition:leave-end="opacity-0 max-h-0 overflow-hidden"
                                             class="px-4 pb-4">
                                            <div class="pt-4 border-t border-red-200 dark:border-red-900/50">
                                                <x-input-label for="maintenance_message" :value="__('Message to Show Visitors')" class="text-red-800 dark:text-red-400 font-medium" />
                                                <textarea id="maintenance_message" name="maintenance_message" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-lg shadow-sm p-3 transition-shadow focus:ring-2 focus:ring-indigo-500/50">{{ old('maintenance_message', $settings['maintenance_message'] ?? '') }}</textarea>
                                                <p class="mt-2 text-xs text-red-600/80 dark:text-red-400/80">{{ __('You can specify the description text that visitors will see on the maintenance page here.') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="border-t border-gray-200 dark:border-gray-700 pt-8">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-200 mb-2 flex items-center gap-2">

                                    {{ __('WebP Image Quality') }}
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">{{ __('Quality of converting images uploaded in the Quill editor to WebP format. High quality means larger file size.') }}</p>

                                <div class="bg-gray-50 dark:bg-gray-800/50 p-6 rounded-xl border border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center gap-6">
                                        <input
                                            type="range"
                                            name="webp_quality"
                                            min="10"
                                            max="100"
                                            step="5"
                                            x-model="webpQuality"
                                            class="w-full h-3 bg-gray-200 dark:bg-gray-700 rounded-lg appearance-none cursor-pointer accent-indigo-600 focus:outline-none focus:ring-1 focus:ring-indigo-500/50 dark:focus:ring-indigo-600/50"
                                        >
                                        <div class="flex-shrink-0 w-20 text-center bg-white dark:bg-gray-900 py-2 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
                                            <span class="text-2xl font-bold text-indigo-600 dark:text-indigo-400" x-text="webpQuality"></span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">/ 100</span>
                                        </div>
                                    </div>

                                    <div class="mt-4 flex justify-between text-xs font-medium text-gray-400 dark:text-gray-500">
                                        <span>{{ __('Low Size') }}</span>
                                        <span x-show="webpQuality < 50" class="text-amber-500 px-2 py-1 bg-amber-50 dark:bg-amber-900/20 rounded-md">{{ __('Low Quality') }}</span>
                                        <span x-show="webpQuality >= 50 && webpQuality < 80" class="text-blue-500 px-2 py-1 bg-blue-50 dark:bg-blue-900/20 rounded-md">{{ __('Balanced') }}</span>
                                        <span x-show="webpQuality >= 80" class="text-green-500 px-2 py-1 bg-green-50 dark:bg-green-900/20 rounded-md">{{ __('High Quality') }}</span>
                                        <span>{{ __('High Quality') }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-end gap-4 pt-6 mt-6 border-t border-gray-200 dark:border-gray-700">
                                <x-primary-button class="cursor-pointer px-6 py-3 text-sm flex items-center gap-2 transition-transform hover:scale-105 shadow-md hover:shadow-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                    </svg>
                                    {{ __('Save Settings') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </section>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg transition-colors duration-200 border border-gray-100 dark:border-gray-700">
                <div class="max-w-2xl">
                    <section>
                        <header class="border-b border-gray-200 dark:border-gray-700 pb-4">
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-200 flex items-center gap-2">
                                {{ __('Performance and System Tools') }}
                            </h2>

                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                {{ __('You can use the operations below to increase blog performance or see code/setting changes instantly.') }}
                            </p>
                        </header>

                        <div class="grid sm:grid-cols-2 gap-4 mt-6">
                            <form action="{{ route('admin.settings.clear-cache') }}" method="POST" class="w-full">
                                @csrf
                                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-4 text-sm font-semibold text-amber-700 dark:text-amber-400 bg-amber-50 dark:bg-amber-950/40 hover:bg-amber-100 dark:hover:bg-amber-900/60 rounded-xl border border-amber-200 dark:border-amber-900/40 transition-all cursor-pointer shadow-sm hover:shadow-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    {{ __('Clear Cache') }}
                                </button>
                            </form>

                            <form action="{{ route('admin.settings.optimize') }}" method="POST" class="w-full">
                                @csrf
                                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-4 text-sm font-semibold text-green-700 dark:text-green-400 bg-green-50 dark:bg-green-950/40 hover:bg-green-100 dark:hover:bg-green-900/60 rounded-xl border border-green-200 dark:border-green-900/40 transition-all cursor-pointer shadow-sm hover:shadow-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                    </svg>
                                    {{ __('Optimize System') }}
                                </button>
                            </form>
                        </div>
                    </section>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>