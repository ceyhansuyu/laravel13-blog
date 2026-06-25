<x-app-layout :title="__('Edit User')">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-300 leading-tight">
            {{ __('Edit User') }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-8 max-w-2xl">
        
        <div class="flex items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">{{ __('Edit User') }}</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ __('You are updating the information of the user named') }} <span class="font-medium text-slate-700 dark:text-slate-300">{{ $user->name }}</span>.</p>
            </div>
            <div>
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors rounded-xl shadow-xs gap-2 shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    {{ __('Back to List') }}
                </a>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl shadow-xs p-6 sm:p-8">
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">{{ __('Full Name') }}</label>
                        <input 
                            type="text" 
                            name="name" 
                            id="name" 
                            value="{{ old('name', $user->name) }}" 
                            required
                            class="block w-full px-4 py-2.5 border @error('name') border-rose-500 dark:border-rose-500 focus:ring-rose-500/20 dark:focus:ring-rose-500/40 focus:border-rose-500 @else border-slate-200 dark:border-slate-700 focus:ring-indigo-500/20 dark:focus:ring-indigo-500/40 focus:border-indigo-500 dark:focus:border-indigo-500 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 transition-colors bg-white dark:bg-slate-900 shadow-xs text-slate-900 dark:text-white"
                        >
                        @error('name')
                            <p class="mt-2 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">{{ __('Email Address') }}</label>
                        <input 
                            type="email" 
                            name="email" 
                            id="email" 
                            value="{{ old('email', $user->email) }}" 
                            required
                            class="block w-full px-4 py-2.5 border @error('email') border-rose-500 dark:border-rose-500 focus:ring-rose-500/20 dark:focus:ring-rose-500/40 focus:border-rose-500 @else border-slate-200 dark:border-slate-700 focus:ring-indigo-500/20 dark:focus:ring-indigo-500/40 focus:border-indigo-500 dark:focus:border-indigo-500 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 transition-colors bg-white dark:bg-slate-900 shadow-xs text-slate-900 dark:text-white"
                        >
                        @error('email')
                            <p class="mt-2 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="role" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">{{ __('User Role') }}</label>
                        <div class="relative">
                            <select 
                                name="role" 
                                id="role" 
                                required
                                class="block w-full px-4 py-2.5 border @error('role') border-rose-500 dark:border-rose-500 focus:ring-rose-500/20 dark:focus:ring-rose-500/40 focus:border-rose-500 @else border-slate-200 dark:border-slate-700 focus:ring-indigo-500/20 dark:focus:ring-indigo-500/40 focus:border-indigo-500 dark:focus:border-indigo-500 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 transition-colors bg-white dark:bg-slate-900 shadow-xs text-slate-900 dark:text-white appearance-none"
                            >
                                @if($user->role === 'founder')
                                    {{-- Eğer düzenlenen kişi founder ise sadece bu seçenek listelenecek --}}
                                    <option value="founder" {{ old('role', $user->role) === 'founder' ? 'selected' : '' }}>{{ __('Founder (Admin)') }}</option>
                                @else
                                    {{-- Düzenlenen kişi founder değilse diğer tüm roller listelenecek --}}
                                    <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>{{ __('Member (Standard User)') }}</option>
                                    <option value="author" {{ old('role', $user->role) === 'author' ? 'selected' : '' }}>{{ __('Author') }}</option>
                                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>{{ __('Administrator (Admin)') }}</option>
                                    <option value="founder" {{ old('role', $user->role) === 'founder' ? 'selected' : '' }}>{{ __('Founder (Admin)') }}</option>
                                @endif
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500 dark:text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                        @error('role')
                            <p class="mt-2 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="relative py-2">
                        <div class="absolute inset-0 flex items-center" aria-hidden="true">
                            <div class="w-full border-t border-slate-100 dark:border-slate-700"></div>
                        </div>
                        <div class="relative flex justify-start text-xs font-medium uppercase tracking-wider">
                            <span class="bg-white dark:bg-slate-800 pr-3 text-slate-400 dark:text-slate-500">{{ __('Security & Password') }}</span>
                        </div>
                    </div>

                    <div class="p-3.5 bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-700/50 rounded-xl text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                        {{ __('If you do not want to change the user password, leave the password fields below blank.') }}
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">{{ __('New Password') }}</label>
                        <input 
                            type="password" 
                            name="password" 
                            id="password" 
                            placeholder="••••••••"
                            class="block w-full px-4 py-2.5 border @error('password') border-rose-500 dark:border-rose-500 focus:ring-rose-500/20 dark:focus:ring-rose-500/40 focus:border-rose-500 @else border-slate-200 dark:border-slate-700 focus:ring-indigo-500/20 dark:focus:ring-indigo-500/40 focus:border-indigo-500 dark:focus:border-indigo-500 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 transition-colors bg-white dark:bg-slate-900 shadow-xs text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500"
                        >
                        @error('password')
                            <p class="mt-2 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">{{ __('Confirm New Password') }}</label>
                        <input 
                            type="password" 
                            name="password_confirmation" 
                            id="password_confirmation" 
                            placeholder="••••••••"
                            class="block w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 focus:ring-indigo-500/20 dark:focus:ring-indigo-500/40 focus:border-indigo-500 dark:focus:border-indigo-500 rounded-xl text-sm focus:outline-none focus:ring-2 transition-colors bg-white dark:bg-slate-900 shadow-xs text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500"
                        >
                    </div>

                    <div>
                        <label for="delete_avatar" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            {{ __('Delete User Avatar') }}
                        </label>
                        <div class="flex items-center frameworks-checked">
                            <input 
                                type="checkbox" 
                                name="delete_avatar" 
                                id="delete_avatar" 
                                value="1"
                                class="w-4 h-4 text-indigo-600 border-slate-200 dark:border-slate-700 rounded-md focus:ring-indigo-500/20 dark:focus:ring-indigo-500/40 focus:border-indigo-500 dark:focus:border-indigo-500 bg-white dark:bg-slate-900 shadow-xs transition-colors cursor-pointer"
                            >
                            <span class="ml-2 text-sm text-slate-500 dark:text-slate-400 select-none cursor-pointer" onclick="document.getElementById('delete_avatar').click()">
                                {{ __('Yes, delete user avatar') }}
                            </span>
                        </div>
                    </div>

                </div>

                <div class="mt-8 flex items-center justify-end gap-3 border-t border-slate-100 dark:border-slate-700 pt-6">
                    <a href="{{ route('admin.users.index') }}" class="px-4 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors rounded-xl">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 transition-colors rounded-xl shadow-sm shadow-indigo-100 dark:shadow-none">
                        {{ __('Save Changes') }}
                    </button>
                </div>
            </form>
        </div>

    </div>
</x-app-layout>