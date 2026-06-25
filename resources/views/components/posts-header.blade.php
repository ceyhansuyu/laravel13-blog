<div class="max-w-4xl mx-auto mb-6 mt-4" x-data="{ mobileMenuOpen: false }">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md dark:shadow-2xl dark:shadow-gray-950 p-4 md:p-5">
        <div class="flex justify-between items-center">
            <h1 class="text-xl md:text-2xl font-bold text-gray-900 dark:text-white shrink-0">
                <a href="{{ route('home') }}" class="flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 hover:underline underline-offset-8">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chat-quote-fill me-2 h-7 w-auto fill-current opacity-80 hover:opacity-100 transition duration-300" viewBox="0 0 16 16">
                        <path d="M16 8c0 3.866-3.582 7-8 7a9 9 0 0 1-2.347-.306c-.584.296-1.925.864-4.181 1.234-.2.032-.352-.176-.273-.362.354-.836.674-1.95.77-2.966C.744 11.37 0 9.76 0 8c0-3.866 3.582-7 8-7s8 3.134 8 7M7.194 6.766a1.7 1.7 0 0 0-.227-.272 1.5 1.5 0 0 0-.469-.324l-.008-.004A1.8 1.8 0 0 0 5.734 6C4.776 6 4 6.746 4 7.667c0 .92.776 1.666 1.734 1.666.343 0 .662-.095.931-.26-.137.389-.39.804-.81 1.22a.405.405 0 0 0 .011.59c.173.16.447.155.614-.01 1.334-1.329 1.37-2.758.941-3.706a2.5 2.5 0 0 0-.227-.4zM11 9.073c-.136.389-.39.804-.81 1.22a.405.405 0 0 0 .012.59c.172.16.446.155.613-.01 1.334-1.329 1.37-2.758.942-3.706a2.5 2.5 0 0 0-.228-.4 1.7 1.7 0 0 0-.227-.273 1.5 1.5 0 0 0-.469-.324l-.008-.004A1.8 1.8 0 0 0 10.07 6c-.957 0-1.734.746-1.734 1.667 0 .92.777 1.666 1.734 1.666.343 0 .662-.095.931-.26z"/>
                    </svg>
                    <span class="truncate max-w-[180px] sm:max-w-none text-xl sm:text-2xl font-bold">
                        {{ $siteSettings['site_name'] }} 
                    </span>
                </a>
            </h1>

            <div class="flex items-center space-x-2 md:space-x-4">
                <button @click="theme = theme === 'light' ? 'dark' : 'light'" class="cursor-pointer p-2 transition duration-200 text-xl inline-flex items-center justify-center w-10 h-10 min-h-[40px]" title="{{ __('Change Theme') }}">
                    <span x-show="theme === 'light'" x-cloak>🌙</span>
                    <span x-show="theme === 'dark'" x-cloak>☀️</span>
                </button>

                <div class="hidden md:flex items-center space-x-4">
                    @auth
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" @click.away="open = false" class="cursor-pointer flex items-center space-x-2 text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white focus:outline-none transition duration-150 ease-in-out">
                                
                                <img class="w-8 h-8 rounded-full border border-gray-300 dark:border-gray-600 text-gray-500 dark:text-gray-400 object-cover" 
                                    src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&color=7F9CF5&background=EBF4FF' }}" 
                                    alt="{{ Auth::user()->name }}">

                                <span class="font-semibold">{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>

                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-200" 
                                 x-transition:enter-start="transform opacity-0 scale-95" 
                                 x-transition:enter-end="transform opacity-100 scale-100" 
                                 x-transition:leave="transition ease-in duration-75" 
                                 x-transition:leave-start="transform opacity-100 scale-100" 
                                 x-transition:leave-end="transform opacity-0 scale-95" 
                                 class="absolute right-0 w-48 mt-2 origin-top-right bg-white dark:bg-gray-700 border border-gray-100 dark:border-gray-600 rounded-md shadow-lg dark:shadow-2xl dark:shadow-gray-950 z-50 py-1" style="display: none;">
                                
                                
                                <a href="{{ route('admin.profile') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 hover:text-blue-600 dark:hover:text-blue-400 transition duration-150">
                                   {{__('Profile') }} 
                                </a>
                                @can('is-admin')
                                <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 hover:text-blue-600 dark:hover:text-blue-400 transition duration-150">
                                    {{__('Dashboard')}}
                                </a>
                                @endcan

                                <div class="border-t border-gray-100 dark:border-gray-600"></div>
                                
                                <form method="POST" action="{{ route('logout') }}" class="m-0">
                                    @csrf
                                    @method('POST')
                                    <button type="submit" class="block cursor-pointer w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-gray-600 transition duration-150">
                                        {{ __('Logout') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                        @can('is-author')
                        <a href="{{ route('posts.create') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-600 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200 font-semibold py-1 px-3 rounded-md transition duration-200 shadow-sm dark:shadow-md">
                            ✏️ {{ __('New Post') }}
                        </a>
                        @endcan
                    @else
                        <div class="flex items-center gap-4">
                            <a href="{{ route('login') }}" class="flex items-center gap-1.5 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-white text-sm font-medium">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-in-right" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M6 3.5a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-2a.5.5 0 0 0-1 0v2A1.5 1.5 0 0 0 6.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-8A1.5 1.5 0 0 0 5 3.5v2a.5.5 0 0 0 1 0z"/>
                                    <path fill-rule="evenodd" d="M11.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H1.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
                                </svg> 
                                <span>{{ __('Login') }}</span>
                            </a>
                            @if($siteSettings['enable_registration'] ?? false)
                            <a href="{{ route('register') }}" class="flex items-center gap-1.5 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-white text-sm font-medium">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check2-square" viewBox="0 0 16 16">
                                    <path d="M3 14.5A1.5 1.5 0 0 1 1.5 13V3A1.5 1.5 0 0 1 3 1.5h8a.5.5 0 0 1 0 1H3a.5.5 0 0 0-.5.5v10a.5.5 0 0 0 .5.5h10a.5.5 0 0 0 .5-.5V8a.5.5 0 0 1 1 0v5a1.5 1.5 0 0 1-1.5 1.5z"/>
                                    <path d="m8.354 10.354 7-7a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0"/>
                                </svg>
                                <span>{{ __('Register') }}</span>
                            </a>
                            @endif
                        </div>
                    @endauth
                </div>

                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden cursor-pointer p-2 rounded-md text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none w-10 h-10 inline-flex items-center justify-center" title="{{ __('Menu') }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path :class="{'hidden': mobileMenuOpen, 'inline-flex': !mobileMenuOpen }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        <path :class="{'inline-flex': mobileMenuOpen, 'hidden': !mobileMenuOpen }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <div x-show="mobileMenuOpen" 
             x-collapse
             x-cloak
             class="md:hidden mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
            <div class="flex flex-col space-y-3">
                @auth
                    <div class="flex items-center space-x-2 px-2 py-1 bg-gray-50 dark:bg-gray-700/50 rounded-md">
                        <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="font-medium text-base text-gray-700 dark:text-gray-300 truncate">{{ Auth::user()->name }}</span>
                    </div>
                    @can('is-admin')
                    <a href="{{ route('dashboard') }}" class="block px-2 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition">
                        📊 Dashboard
                    </a>
                    @endcan

                    @can('is-author')
                    <a href="{{ route('posts.create') }}" class="block px-2 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition">
                        ✏️ {{ __('New Post') }}
                    </a>
                    @endcan

                    <form method="POST" action="{{ route('logout') }}" class="m-0 pt-2 border-t border-gray-100 dark:border-gray-700">
                        @csrf
                        @method('POST')
                        <button type="submit" class="cursor-pointer w-full text-left px-2 py-2 text-base font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-gray-700 rounded-md transition">
                            🚪 {{ __('Logout') }}
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="flex items-center gap-2 px-2 py-2 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-in-right" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M6 3.5a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-2a.5.5 0 0 0-1 0v2A1.5 1.5 0 0 0 6.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-8A1.5 1.5 0 0 0 5 3.5v2a.5.5 0 0 0 1 0z"/>
                            <path fill-rule="evenodd" d="M11.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H1.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
                        </svg>
                        <span>{{ __('Login') }}</span>
                    </a>

                    <a href="{{ route('register') }}" class="flex items-center gap-2 px-2 py-2 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check2-square" viewBox="0 0 16 16">
                            <path d="M3 14.5A1.5 1.5 0 0 1 1.5 13V3A1.5 1.5 0 0 1 3 1.5h8a.5.5 0 0 1 0 1H3a.5.5 0 0 0-.5.5v10a.5.5 0 0 0 .5.5h10a.5.5 0 0 0 .5-.5V8a.5.5 0 0 1 1 0v5a1.5 1.5 0 0 1-1.5 1.5z"/>
                            <path d="m8.354 10.354 7-7a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0"/>
                        </svg>
                        <span>{{ __('Register') }}</span>
                    </a>
                @endauth
            </div>
        </div>
    </div>
</div>

@if($ad_settings['header_active'])
     {!! $ad_settings['header_code'] !!}
@endif