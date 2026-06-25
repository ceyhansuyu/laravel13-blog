<x-app-layout :title="__('Security')">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Cyber Security and Brute Force Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('Brute Force Protection Settings') }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ __('You can enable or disable the mechanism that protects your login panel against bots, or flex the limits from here.') }}
                            </p>
                        </header>

                        {{-- Fix: We used (int)$settings[...] === 1 for both int and string 1 check --}}
                        <form method="post" action="{{ route('admin.security.settings.update') }}" class="mt-6 space-y-6" x-data="{ enabled: {{ ((int) $settings['brute_force_enabled'] === 1) ? 'true' : 'false' }} }">
                            @csrf
                            
                            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/30 rounded-lg">
                                <div class="flex flex-col">
                                    <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ __('Firewall Status') }}</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ __('When disabled, the admin panel is opened to everyone without rate limiting.') }}</span>
                                </div>
                                
                                <button type="button" 
                                        @click="enabled = !enabled"
                                        :class="enabled ? 'bg-indigo-600' : 'bg-gray-200 dark:bg-gray-700'" 
                                        class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2" role="switch">
                                    <span :class="enabled ? 'translate-x-5' : 'translate-x-0'" class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                                </button>
                                
                                {{-- Fix: We removed the static value to prevent conflicts, left the control entirely to the dynamic :value context --}}
                                <input type="hidden" name="brute_force_enabled" :value="enabled ? 1 : 0">
                            </div>

                            <div class="space-y-6 transition-all duration-300" x-show="enabled" x-transition>
                                <div>
                                    <x-input-label for="max_attempts" :value="__('Maximum Failed Login Attempts')" />
                                    <x-text-input id="max_attempts" name="max_attempts" type="number" class="mt-1 block w-full" :value="old('max_attempts', $settings['max_attempts'])" min="1" max="20" x-bind:required="enabled" />
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('If the user makes the specified number of incorrect logins, they will be temporarily blocked.') }}</p>
                                    <x-input-error class="mt-2" :messages="$errors->get('max_attempts')" />
                                </div>

                                <div>
                                    <x-input-label for="lockout_time" :value="__('Lockout / Block Duration (Minutes)')" />
                                    <x-text-input id="lockout_time" name="lockout_time" type="number" class="mt-1 block w-full" :value="old('lockout_time', $settings['lockout_time'])" min="1" max="1440" x-bind:required="enabled" />
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Determine how many minutes the attacker\'s IP address will be completely locked.') }}</p>
                                    <x-input-error class="mt-2" :messages="$errors->get('lockout_time')" />
                                </div>
                            </div>

                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Save Security Settings') }}</x-primary-button>
                            </div>
                        </form>
                    </section>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
    <div>
        <section>
            <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                <div>
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ __('Brute Force Tracking & Failed Login Attempts') }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Live analysis of bots trying to infiltrate your panel or failed login attempts.') }}
                    </p>
                </div>
                <form method="POST" action="{{ route('admin.security.clear-logs') }}">
                    @csrf
                    <x-danger-button type="submit" onclick="return confirm('{{ __('Are you sure you want to clear all security logs?') }}')">
                        {{ __('Clear Logs') }}
                    </x-danger-button>
                </form>
            </header>

            <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">{{ __('Date / Time') }}</th>
                            <th scope="col" class="px-6 py-3">{{ __('IP Address') }}</th>
                            <th scope="col" class="px-6 py-3">{{ __('Attempted Username') }}</th>
                            <th scope="col" class="px-6 py-3">{{ __('Browser / Bot Info') }}</th>
                            <th scope="col" class="px-6 py-3">{{ __('Status') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($failedAttempts as $attempt)
                            <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-gray-100">{{ $attempt['time'] }}</td>
                                <td class="px-6 py-4 text-red-600 dark:text-red-400 font-semibold">{{ $attempt['ip'] }}</td>
                                <td class="px-6 py-4"><span class="bg-gray-100 dark:bg-gray-900 px-2 py-0.5 rounded text-gray-700 dark:text-gray-300">{{ $attempt['username'] }}</span></td>
                                <td class="px-6 py-4 text-xs">{{ $attempt['browser'] }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs font-semibold bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300 rounded-full">{{ __('Rejected') }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr class="bg-white dark:bg-gray-800">
                                <td colspan="5" class="px-6 py-8 text-center text-gray-400">{{ __('No suspicious brute force login attempt has been detected so far. You are safe!') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>

        </div>
    </div>
</x-app-layout>