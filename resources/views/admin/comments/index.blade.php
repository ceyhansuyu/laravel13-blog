<x-app-layout :title="__('Comments')">

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Comment List') }}
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
                <form action="{{ route('admin.comments.index') }}" method="GET" class="flex flex-col lg:flex-row items-center justify-between gap-4">
                    @if(request('all') == 1)
                        <input type="hidden" name="all" value="1">
                    @endif

                    <div class="w-full lg:flex-1">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('Search by comment content, author name, or post title...') }}" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm shadow-sm py-2.5 px-4">
                    </div>

                    <div class="w-full lg:w-auto flex flex-col sm:flex-row items-stretch sm:items-center gap-3 flex-wrap">
                        
                        <select name="status" class="min-w-[160px] h-10 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm shadow-sm py-2.5 px-4">
                            <option value="">{{ __('Status (All)') }}</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>{{ __('Approved Comments') }}</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('Pending Comments') }}</option>
                        </select>

                        <select name="sort_by" class="min-w-[140px] h-10 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm shadow-sm py-2.5 px-4">
                            <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>{{ __('By Date') }}</option>
                            <option value="status" {{ request('sort_by') == 'status' ? 'selected' : '' }}>{{ __('By Status') }}</option>
                        </select>

                        <select name="sort_direction" class="min-w-[140px] h-10 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm shadow-sm py-2.5 px-4">
                            <option value="desc" {{ request('sort_direction') == 'desc' ? 'selected' : '' }}>{{ __('Newest to Oldest') }}</option>
                            <option value="asc" {{ request('sort_direction') == 'asc' ? 'selected' : '' }}>{{ __('Oldest to Newest') }}</option>
                        </select>

                        <button type="submit" class="cursor-pointer bg-gray-800 dark:bg-indigo-600 hover:bg-indigo-700 dark:hover:bg-indigo-500 text-white px-5 py-2.5 rounded-lg text-sm font-semibold shadow-sm transition whitespace-nowrap">
                            {{ __('Filter') }}
                        </button>

                        @if(request()->filled('search') || request()->filled('sort_by') || request()->filled('status'))
                            <a href="{{ route('admin.comments.index', request()->only('all')) }}" class="text-sm text-red-600 dark:text-red-400 hover:underline px-2 self-center text-center">
                                {{ __('Clear') }}
                            </a>
                        @endif
                    </div>
                </form>
            </div>
            
            <div class="bg-white dark:bg-gray-800 overflow-visible shadow-sm sm:rounded-lg p-6"> <form action="{{ route('admin.comments.bulk-action') }}" method="POST" id="bulk-form">
                    @csrf
                    
                    <div class="flex items-center space-x-3 mb-6 flex-wrap gap-y-3">
                        <select name="action" id="bulk-action-select" class="min-w-[240px] h-10 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm shadow-sm py-2.5 px-4">
                            <option value="">{{ __('-- Select Bulk Action --') }}</option>
                            <option value="approve">{{ __('Approve Selected') }}</option>
                            <option value="pending">{{ __('Mark Selected as Pending') }}</option>
                            <option value="delete">{{ __('Delete Selected') }}</option>
                        </select>
                        
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white px-5 py-2.5 rounded-lg text-sm font-semibold shadow-sm transition">
                            {{ __('Apply') }}
                        </button>

                        @if(request()->query('all') == 1)
                            <a href="{{ route('admin.comments.index', request()->except('all')) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2.5 rounded-lg text-sm font-semibold shadow-sm transition">
                                {{ __('Show Paginated') }}
                            </a>
                        @endif
                        @if(request()->query('all') != 1)
                            <a href="{{ route('admin.comments.index', array_merge(request()->query(), ['all' => 1])) }}" class="bg-emerald-600 hover:bg-emerald-700 dark:bg-emerald-500 dark:hover:bg-emerald-600 text-white px-5 py-2.5 rounded-lg text-sm font-semibold shadow-sm transition">
                                {{ __('List All Comments') }}
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
                                    <th class="p-4">{{ __('Author') }}</th>
                                    <th class="p-4">{{ __('Comment') }}</th>
                                    <th class="p-4">{{ __('Related Post') }}</th>
                                    <th class="p-4">{{ __('Status') }}</th>
                                    <th class="p-4">{{ __('Date') }}</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm text-gray-700 dark:text-gray-300 divide-y divide-gray-100 dark:divide-gray-700">
                                @forelse($comments as $comment)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                        <td class="p-4 text-center">
                                            <input type="checkbox" name="ids[]" value="{{ $comment->id }}" class="comment-checkbox rounded border-gray-300 dark:border-gray-600 dark:bg-gray-900 text-indigo-600 focus:ring-indigo-500">
                                        </td>
                                        
                                        <td class="p-4 font-medium text-gray-900 dark:text-gray-300 whitespace-nowrap relative" 
                                            x-data="{ 
                                                open: false, 
                                                copiedIp: false, 
                                                copiedAgent: false,
                                                copy(text, type) {
                                                    navigator.clipboard.writeText(text).then(() => {
                                                        if (type === 'ip') {
                                                            this.copiedIp = true;
                                                            setTimeout(() => this.copiedIp = false, 2000);
                                                        } else {
                                                            this.copiedAgent = true;
                                                            setTimeout(() => this.copiedAgent = false, 2000);
                                                        }
                                                    });
                                                }
                                            }">
                                            
                                            <div class="flex items-center gap-2">
                                                <div>
                                                    {{ $comment->name ?? $comment->user?->name ?? __('Anonymous') }}
                                                    <br>
                                                    <span class="text-xs text-gray-500">{{ $comment->email ?? $comment->user?->email }}</span>
                                                </div>
                                                
                                                <button @click.prevent="open = !open" type="button" class="p-1.5 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 rounded-md hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors focus:outline-none opacity-60" title="{{ __('View IP and Device Info') }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </button>
                                            </div>

                                            <div x-show="open" 
                                                 @click.away="open = false" 
                                                 x-transition:enter="transition ease-out duration-200" 
                                                 x-transition:enter-start="opacity-0 translate-y-1" 
                                                 x-transition:enter-end="opacity-100 translate-y-0" 
                                                 x-transition:leave="transition ease-in duration-150" 
                                                 x-transition:leave-start="opacity-100 translate-y-0" 
                                                 x-transition:leave-end="opacity-0 translate-y-1" 
                                                 class="absolute left-full ml-2 top-0 w-80 z-[100] bg-white dark:bg-gray-800 rounded-xl shadow-2xl border border-gray-200 dark:border-gray-700 p-4 font-normal" 
                                                 style="display: none;">
                                                
                                                <div class="mb-4">
                                                    <div class="flex justify-between items-center mb-1.5">
                                                        <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('IP Address') }}</span>
                                                        <button type="button" @click.prevent="copy('{{ $comment->ip_address }}', 'ip')" class="text-xs flex items-center gap-1 font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 transition-colors">
                                                            <span x-show="!copiedIp" class="flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg> {{ __('Copy') }}</span>
                                                            <span x-show="copiedIp" class="flex items-center gap-1 text-green-600 dark:text-green-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> {{ __('Copied') }}</span>
                                                        </button>
                                                    </div>
                                                    <div class="text-sm text-gray-800 dark:text-gray-200 bg-gray-50 dark:bg-gray-900/50 p-2.5 rounded-lg border border-gray-100 dark:border-gray-700 break-all">
                                                        {{ $comment->ip_address ?? __('Unknown') }}
                                                    </div>
                                                </div>

                                                <div>
                                                    <div class="flex justify-between items-center mb-1.5">
                                                        <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Browser & OS') }}</span>
                                                        <button type="button" @click.prevent="copy('{{ $comment->user_agent }}', 'agent')" class="text-xs flex items-center gap-1 font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 transition-colors">
                                                            <span x-show="!copiedAgent" class="flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg> {{ __('Copy') }}</span>
                                                            <span x-show="copiedAgent" class="flex items-center gap-1 text-green-600 dark:text-green-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> {{ __('Copied') }}</span>
                                                        </button>
                                                    </div>
                                                    <div class="text-sm text-gray-800 dark:text-gray-200 bg-gray-50 dark:bg-gray-900/50 p-2.5 rounded-lg border border-gray-100 dark:border-gray-700 max-h-32 overflow-y-auto whitespace-normal" style="word-break: break-word;">
                                                        {{ $comment->user_agent ?? __('Unknown') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-4 text-gray-600 dark:text-gray-400 max-w-xs truncate" title="{{ $comment->content }}" >
                                            {{ Str::limit($comment->content, 60) }}
                                        </td>
                                        <td class="p-4">
                                            <a href="{{ route('posts.show', ['post' => $comment->post->id, 'slug' => $comment->post->slug]) }}#comment-{{ $comment->id }}" target="_blank" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 underline underline-offset-4 transition-colors duration-200">
                                                {{ Str::limit($comment->post->title, 30) }}
                                            </a>
                                        </td>
                                        <td class="p-4">
                                            @if($comment->status === 'approved')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900/60 dark:text-green-300">
                                                    {{ __('Approved') }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-300">
                                                    {{ __('Pending') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="p-4 text-gray-500 dark:text-gray-400 whitespace-nowrap">{{ $comment->created_at->format('d.m.Y H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="p-4 text-center text-gray-500 dark:text-gray-400">{{ __('No comments found matching your criteria.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($comments instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <div class="mt-4">
                            {{ $comments->links() }}
                        </div>
                    @endif
                </form>

            </div>
        </div>
    </div>

    <script>
        document.getElementById('select-all').addEventListener('change', function() {
            let checkboxes = document.querySelectorAll('.comment-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        document.getElementById('bulk-form').addEventListener('submit', function(e) {
            let actionSelect = document.getElementById('bulk-action-select');
            let selectedAction = actionSelect.value;
            let checkedBoxes = document.querySelectorAll('.comment-checkbox:checked');

            if (checkedBoxes.length === 0) {
                e.preventDefault();
                alert('{{ __("Please select at least one comment to perform an action!") }}');
                return;
            }

            if (!selectedAction) {
                e.preventDefault();
                alert('{{ __("Please select a bulk action to perform!") }}');
                return;
            }

            if (selectedAction === 'delete') {
                let confirmation = confirm('{{ __("Are you sure you want to delete all selected comments? This action cannot be undone!") }}');
                if (!confirmation) {
                    e.preventDefault();
                }
            }
        });
    </script>
</x-app-layout>