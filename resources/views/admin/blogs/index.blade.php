<x-app-layout :title="__('Blogs')">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Blog List') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg dark:bg-green-900/40 dark:border-green-800 dark:text-green-200">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg dark:bg-red-900/40 dark:border-red-800 dark:text-red-200">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <form action="{{ route('admin.blogs.index') }}" method="GET" class="flex flex-col lg:flex-row items-center justify-between gap-4">
                    @if(request('all') == 1)
                        <input type="hidden" name="all" value="1">
                    @endif

                    <div class="w-full lg:flex-1">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('Search by blog title...') }}" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm shadow-sm py-2.5 px-4">
                    </div>

                    <div class="w-full lg:w-auto flex flex-col sm:flex-row items-stretch sm:items-center gap-3 flex-wrap">
                        

                        <select name="category_id" class="min-w-[160px] h-10 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm shadow-sm py-2.5 px-4">
                            <option value="">{{ __('Category (All)') }}</option>
                            <option value="none" {{ request('category_id') == 'none' ? 'selected' : '' }}>{{ __('Uncategorized Posts') }}</option> 
                            
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>


                        <select name="is_featured" class="min-w-[160px] h-10 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm shadow-sm py-2.5 px-4">
                            <option value="">{{ __('Featured (All)') }}</option>
                            <option value="featured" {{ request('is_featured') == 'featured' ? 'selected' : '' }}>{{ __('Featured Posts') }}</option>
                            <option value="normal" {{ request('is_featured') == 'normal' ? 'selected' : '' }}>{{ __('Normal Posts') }}</option>
                        </select>

                        <select name="sort_by" class="min-w-[140px] h-10 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm shadow-sm py-2.5 px-4">
                            <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>{{ __('By Date') }}</option>
                            <option value="title" {{ request('sort_by') == 'title' ? 'selected' : '' }}>{{ __('By Title') }}</option>
                            <option value="status" {{ request('sort_by') == 'status' ? 'selected' : '' }}>{{ __('By Status') }}</option>
                            <option value="is_featured" {{ request('sort_by') == 'is_featured' ? 'selected' : '' }}>{{ __('By Featured') }}</option>
                        </select>

                        <select name="sort_direction" class="min-w-[140px] h-10 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm shadow-sm py-2.5 px-4">
                            <option value="desc" {{ request('sort_direction') == 'desc' ? 'selected' : '' }}>{{ __('Newest to Oldest') }}</option>
                            <option value="asc" {{ request('sort_direction') == 'asc' ? 'selected' : '' }}>{{ __('Oldest to Newest') }}</option>
                        </select>

                        <button type="submit" class="cursor-pointer bg-gray-800 dark:bg-indigo-600 hover:bg-indigo-700 dark:hover:bg-indigo-500 text-white px-5 py-2.5 rounded-lg text-sm font-semibold shadow-sm transition whitespace-nowrap">
                            {{ __('Filter') }}
                        </button>

                        @if(request()->filled('search') || request()->filled('sort_by') || request()->filled('is_featured') || request()->filled('category_id'))
                            <a href="{{ route('admin.blogs.index', request()->only('all')) }}" class="text-sm text-red-600 dark:text-red-400 hover:underline px-2 self-center text-center">
                                {{ __('Clear') }}
                            </a>
                        @endif
                    </div>
                </form>
            </div>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form action="{{ route('admin.blogs.bulk-action') }}" method="POST" id="bulk-form">
                    @csrf
                    
                    <div class="flex items-center space-x-3 mb-6 flex-wrap gap-y-3">
                        <select name="action" id="bulk-action-select" class="min-w-[240px] h-10 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm shadow-sm py-2.5 px-4">
                            <option value="">{{ __('-- Select Bulk Action --') }}</option>
                            <option value="delete">{{ __('Delete Selected') }}</option>
                            <option value="publish">{{ __('Publish Selected') }}</option>
                            <option value="draft">{{ __('Return Selected to Draft') }}</option>
                            <option value="feature">{{ __('Feature Selected') }}</option>
                            <option value="unfeature">{{ __('Unfeature Selected') }}</option>
                        </select>
                        
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white px-5 py-2.5 rounded-lg text-sm font-semibold shadow-sm transition">
                            {{ __('Apply') }}
                        </button>

                        @if(request()->query('all') == 1)
                            <a href="{{ route('admin.blogs.index', request()->except('all')) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2.5 rounded-lg text-sm font-semibold shadow-sm transition">
                                {{ __('Show Paginated') }}
                            </a>
                        @endif
                        @if(request()->query('all') != 1)
                            <a href="{{ route('admin.blogs.index', array_merge(request()->query(), ['all' => 1])) }}" class="bg-emerald-600 hover:bg-emerald-700 dark:bg-emerald-500 dark:hover:bg-emerald-600 text-white px-5 py-2.5 rounded-lg text-sm font-semibold shadow-sm transition">
                                {{ __('List All Blogs') }}
                            </a>
                        @endif
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-100 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300 text-sm font-semibold">
                                    <th class="p-4 w-12 text-center">
                                        <input type="checkbox" id="select-all" class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-900 text-indigo-600 focus:ring-indigo-500">
                                    </th>
                                    <th class="p-4">{{ __('Title') }}</th>
                                    <th class="p-4">{{ __('Category') }}</th>
                                    <th class="p-4">{{ __('Status') }}</th>
                                    <th class="p-4">{{ __('Featured Post') }}</th> 
                                    <th class="p-4">{{ __('Created Date') }}</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm text-gray-700 dark:text-gray-300 divide-y divide-gray-100 dark:divide-gray-700">
                                @forelse($blogs as $blog)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                        <td class="p-4 text-center">
                                            <input type="checkbox" name="ids[]" value="{{ $blog->id }}" class="blog-checkbox rounded border-gray-300 dark:border-gray-600 dark:bg-gray-900 text-indigo-600 focus:ring-indigo-500">
                                        </td>
                                        <td class="p-4 font-medium text-gray-900 dark:text-gray-100">
                                            <a href="{{ route('posts.show', ['post' => $blog->id, 'slug' => $blog->slug]) }}" target="_blank" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 underline underline-offset-7 transition-colors duration-200 block md:inline-block break-words">
                                                {{ $blog->title }}
                                            </a>
                                        </td>
                                        <td class="p-4 text-gray-600 dark:text-gray-400">
                                            {{ $blog->category?->name ?? __('No Category') }}
                                        </td>
                                        <td class="p-4">
                                            @if($blog->status === 'publish')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900/60 dark:text-green-300">
                                                    {{ __('Published') }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                                    {{ __('Draft') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="p-4">
                                            @if($blog->is_featured === 'featured')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                                                    ★ {{ __('Featured') }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400">
                                                    {{ __('Normal') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="p-4 text-gray-500 dark:text-gray-400">{{ $blog->created_at->format('d.m.Y H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="p-4 text-center text-gray-500 dark:text-gray-400">{{ __('No blog posts found matching your criteria.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($blogs instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <div class="mt-4">
                            {{ $blogs->links() }}
                        </div>
                    @endif
                </form>

            </div>
        </div>
    </div>

    <script>
        document.getElementById('select-all').addEventListener('change', function() {
            let checkboxes = document.querySelectorAll('.blog-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        document.getElementById('bulk-form').addEventListener('submit', function(e) {
            let actionSelect = document.getElementById('bulk-action-select');
            let selectedAction = actionSelect.value;
            let checkedBoxes = document.querySelectorAll('.blog-checkbox:checked');

            if (checkedBoxes.length === 0) {
                e.preventDefault();
                alert('{{ __('Please select at least one blog post to perform an action!') }}');
                return;
            }

            if (!selectedAction) {
                e.preventDefault();
                alert('{{ __('Please select the bulk action you want to perform!') }}');
                return;
            }

            if (selectedAction === 'delete') {
                let confirmation = confirm('{{ __('Are you sure you want to delete all selected blog posts? This action cannot be undone!') }}');
                if (!confirmation) {
                    e.preventDefault();
                }
            }
        });
    </script>
</x-app-layout>