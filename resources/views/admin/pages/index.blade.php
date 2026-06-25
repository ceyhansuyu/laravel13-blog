<x-app-layout :title="__('Pages')">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Pages') }}
            </h2>
            <a href="{{ route('admin.pages.create') }}" class="bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-semibold shadow-sm transition">
                + {{ __('Add New Page') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg dark:bg-green-900/40 dark:border-green-800 dark:text-green-200">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-100 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300 text-sm font-semibold">
                                <th class="p-4">{{ __('Title') }}</th>
                                <th class="p-4">{{ __('URL (Slug)') }}</th>
                                <th class="p-4 text-center">{{ __('Status') }}</th>
                                <th class="p-4">{{ __('Date') }}</th>
                                <th class="p-4 text-right">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-gray-700 dark:text-gray-300 divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($pages as $page)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                    <td class="p-4 font-medium text-gray-900 dark:text-gray-300">{{ $page->title }}</td>
                                    <td class="p-4 text-gray-500 dark:text-gray-400">{{ $page->slug }}</td>
                                    <td class="p-4 text-center">
                                        @if($page->is_active)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900/60 dark:text-green-300">{{ __('Active') }}</span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">{{ __('Passive') }}</span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-gray-500 dark:text-gray-400">{{ $page->created_at->format('d.m.Y H:i') }}</td>
                                    <td class="p-4 text-right">
                                        
                                        <div class="flex justify-end gap-3">
                                            <a href="{{ route('admin.pages.edit', $page) }}"
                                            class="px-3 py-1.5 rounded-md text-sm font-medium bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600 transition cursor-pointer">
                                                ✏️ {{ __('Edit') }}
                                            </a>
                                            <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" class="inline m-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        onclick="return confirm('{{ __('Are you sure you want to delete this page?') }}')"
                                                        class="px-3 py-1.5 rounded-md text-sm font-medium bg-red-100 text-red-700 dark:bg-red-700 dark:text-red-100 hover:bg-red-200 dark:hover:bg-red-600 transition cursor-pointer">
                                                    🗑️ {{ __('Delete') }}
                                                </button>
                                            </form>
                                        </div>

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-4 text-center text-gray-500 dark:text-gray-400">{{ __('No pages added yet.') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($pages->hasPages())
                    <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                        {{ $pages->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>