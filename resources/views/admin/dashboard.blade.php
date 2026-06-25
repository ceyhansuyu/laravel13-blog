<x-layouts.app :title="__('Dashboard')">

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-300 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="p-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 dark:border-gray-700">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Posts') }}</div>
                    <div class="mt-2 text-3xl font-semibold text-gray-900 dark:text-gray-300">{{ $totalPosts ?? 0 }}</div>
                </div>

                <div class="p-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 dark:border-gray-700">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Views') }}</div>
                    <div class="mt-2 text-3xl font-semibold text-gray-900 dark:text-gray-300">{{ number_format($totalViews ?? 0) }}</div>
                </div>

                <div class="p-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 dark:border-gray-700">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Pending Comments') }}</div>
                    <div class="mt-2 text-3xl font-semibold text-yellow-600 dark:text-yellow-400">{{ $pendingComments ?? 0 }}</div>
                </div>

                <div class="p-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 dark:border-gray-700">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Uploaded Media') }}</div>
                    <div class="mt-2 text-3xl font-semibold text-gray-900 dark:text-gray-300">{{ $totalMedia ?? 0 }}</div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <div class="lg:col-span-2 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 dark:border-gray-700 p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-300">{{ __('Recent Blog Posts') }}</h3>
                        <a href="{{ route('admin.blogs.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">{{ __('See All') }}</a>
                    </div>
                    
                    <div class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($recentPosts as $post)
                            <div class="py-3 flex justify-between items-center">
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-800 dark:text-gray-300">{{ $post->title }}</h4>
                                    <p class="text-xs text-gray-400">
                                        {{ $post->status === 'publish' ? __('Published: ') : __('Draft Created: ') }} 
                                        {{ $post->created_at->diffForHumans() }}
                                    </p>
                                </div>
                                @if($post->status === 'publish')
                                    <span class="px-2.5 py-1 text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 rounded-full">{{ __('Publish') }}</span>
                                @elseif($post->status === 'draft')
                                    <span class="px-2.5 py-1 text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 rounded-full">{{ __('Draft') }}</span>
                                @endif
                            </div>
                        @empty
                            <div class="py-3 text-sm text-gray-500 dark:text-gray-400 text-center">
                                {{ __('No blog posts have been added yet.') }}
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 dark:border-gray-700 p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-300 mb-4">{{ __('Quick Actions') }}</h3>
                        <div class="grid grid-cols-2 gap-2">
                            <a href="{{ route('posts.create') }}" class="flex flex-col items-center justify-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-indigo-950/30 transition group">
                                <span class="text-xs font-medium group-hover:text-indigo-600 dark:group-hover:text-indigo-400">+ {{ __('New Post') }}</span>
                            </a>
                            <a href="{{ route('admin.comments.index') }}" class="flex flex-col items-center justify-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-indigo-950/30 transition group">
                                <span class="text-xs font-medium group-hover:text-indigo-600 dark:group-hover:text-indigo-400">💬 {{ __('Comments') }}</span>
                            </a>
                            <a href="{{ route('admin.settings.edit') }}" class="flex flex-col items-center justify-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-indigo-950/30 transition group">
                                <span class="text-xs font-medium group-hover:text-indigo-600 dark:group-hover:text-indigo-400">⚙️ {{ __('Settings') }}</span>
                            </a>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 dark:border-gray-700 p-6">
                        <h3 class="text-sm font-medium text-gray-900 dark:text-gray-300 mb-3">{{ __('System Status') }}</h3>
                        <div class="space-y-4  text-xs text-gray-600 dark:text-gray-400">
                            <div class="flex justify-between">
                                <span>{{ __('Laravel Version:') }}</span>
                                <span class="font-mono text-gray-900 dark:text-gray-300">{{ app()->version() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>{{ __('Security Filter (Purifier):') }}</span>
                                @if($isPurifierActive)
                                    <span class="text-green-600 dark:text-green-400 font-medium">{{ __('Active') }}</span>
                                @else
                                    <span class="text-red-600 dark:text-red-400 font-medium">{{ __('Passive / Not Installed') }}</span>
                                @endif
                            </div>
                            <div class="flex justify-between">
                                <span>{{ __('Cache Status:') }}</span>
                                @if($isCached)
                                    <span class="text-green-600 dark:text-green-400 font-medium">{{ __('Optimized') }}</span>
                                @else
                                    <span class="text-yellow-600 dark:text-yellow-400 font-medium">{{ __('Not Cached') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-layouts.app>