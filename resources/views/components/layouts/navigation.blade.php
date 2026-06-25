<nav x-data="{ open: false }" class="bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800 transition-colors duration-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <div class="hidden space-x-6 sm:-my-px sm:ms-10 sm:flex">

                @can('is-admin')
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <x-nav-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.*')">
                        {{ __('Categories') }}
                    </x-nav-link>

                    <x-nav-link :href="route('admin.blogs.index')" :active="request()->routeIs('admin.blogs.*')">
                        {{ __('Blogs') }}
                    </x-nav-link>

                    <x-nav-link :href="route('admin.comments.index')" :active="request()->routeIs('admin.comments.*')">
                        {{ __('Comments') }}
                    </x-nav-link>

                    <x-nav-link :href="route('admin.pages.index')" :active="request()->routeIs('admin.pages.*')">
                        {{ __('Pages') }}
                    </x-nav-link>

                    <x-nav-link :href="route('admin.settings.edit')" :active="request()->routeIs('admin.settings.*')">
                        {{ __('Settings') }}
                    </x-nav-link>

                    <x-nav-link :href="route('admin.security.edit')" :active="request()->routeIs('admin.security.*')">
                        {{ __('Security') }}
                    </x-nav-link>

                    <x-nav-link :href="route('admin.ads.index')" :active="request()->routeIs('admin.ads.*')">
                        {{ __('Ad Management') }}
                    </x-nav-link>

                    <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                        {{ __('Users') }}
                    </x-nav-link>

                    @endcan

                    <x-nav-link :href="route('posts.index')" :active="request()->routeIs('posts.*')">
                        {{ __('Home') }}
                    </x-nav-link>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-4">
                <button @click="theme = theme === 'light' ? 'dark' : 'light'" 
                        class="cursor-pointer p-2 transition duration-200 text-xl inline-flex items-center justify-center w-10 h-10 min-h-[40px] rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-500 dark:text-gray-400" 
                        title="{{ __('Change Theme') }}">
                    <span x-show="theme === 'light'" x-cloak>🌙</span>
                    <span x-show="theme === 'dark'" x-cloak>☀️</span>
                </button>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="cursor-pointer inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-900 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            
                            <img class="w-8 h-8 rounded-full border mr-2 border-gray-300 dark:border-gray-600 text-gray-500 dark:text-gray-400 object-cover" 
                                        src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&color=7F9CF5&background=EBF4FF' }}" 
                                        alt="{{ Auth::user()->name }}">
                        
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('admin.profile.edit')" class="cursor-pointer block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-blue-600 dark:hover:text-blue-400 transition duration-150">
                            {{ __('Profile Setting') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();" 
                                    class="block cursor-pointer w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/30 hover:text-red-700 dark:hover:text-red-300 transition duration-150">
                                {{ __('Logout') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-800 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white dark:bg-gray-900 border-t border-gray-100 dark:border-gray-800">
        <div class="pt-2 pb-3 space-y-1">
        @can('is-admin')
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            
            <x-responsive-nav-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.*')">
                {{ __('Categories') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('admin.blogs.index')" :active="request()->routeIs('admin.blogs.*')">
                {{ __('Blogs') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('admin.comments.index')" :active="request()->routeIs('admin.comments.*')">
                {{ __('Comments') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('admin.pages.index')" :active="request()->routeIs('admin.pages.*')">
                {{ __('Pages') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('admin.security.edit')" :active="request()->routeIs('admin.security.*')">
                {{ __('Security') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('admin.settings.edit')" :active="request()->routeIs('admin.settings.*')">
                {{ __('Settings') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('admin.ads.index')" :active="request()->routeIs('admin.ads.*')">
                {{ __('Ad Management') }}
            </x-responsive-nav-link>            
            
            <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                {{ __('Users') }}
            </x-responsive-nav-link>
        @endcan
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-800">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('admin.profile.edit')" :active="request()->routeIs('admin.profile.edit')">
                    {{ __('Profile Setting') }}
                </x-responsive-nav-link>


                <x-responsive-nav-link :href="route('posts.index')" :active="request()->routeIs('posts.*')">
                    {{ __('Home') }}
                </x-responsive-nav-link>

                <div class="px-3 py-2 flex items-center">
                    <button @click="theme = theme === 'light' ? 'dark' : 'light'" 
                            class="cursor-pointer p-2 transition duration-200 text-xl inline-flex items-center justify-center w-10 h-10 min-h-[40px] rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-500 dark:text-gray-400" 
                            title="{{ __('Change Theme') }}">
                        <span x-show="theme === 'light'" x-cloak>🌙</span>
                        <span x-show="theme === 'dark'" x-cloak>☀️</span>
                    </button>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Logout') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>