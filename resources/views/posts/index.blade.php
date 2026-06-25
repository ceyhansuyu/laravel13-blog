<x-layouts.posts>
    <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 p-6 md:p-8 rounded-lg shadow-md dark:shadow-2xl dark:shadow-gray-950">
        
        {{-- EKLENEN KISIM: Yazar Filtresi Aktifse Zarif Bir Başlık Göster --}}
        @isset($author)
        <div class="mb-6 flex items-center gap-3 pb-4 border-b border-gray-100 dark:border-gray-700">
            <div class="p-2 bg-emerald-100 dark:bg-emerald-900/30 rounded-full">
                <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                </svg>
            </div>
                <h2 class="text-lg md:text-xl font-bold text-gray-800 dark:text-white">
                @if($author)
                    {{ __('Author') }}: <span class="font-normal text-gray-500 dark:text-gray-400 text-lg md:text-xl">{{ $author->name }}</span>
                @else
                    {{ __('All Posts') }}
                @endif
            </h2>
        </div>
        @endisset
        {{-- EKLENEN KISIM BİTİŞ --}}

        @if($enableSearch)
        <div class="mb-6">
            <form action="{{ route('posts.index') }}" method="GET" class="flex">
                <div class="flex-1">
                    <input type="text" name="search" placeholder="{{ __('Search posts...') }}" value="{{ $search }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-l-lg focus:outline-none focus:ring-4 focus:ring-blue-100 dark:focus:ring-blue-900/50 dark:focus:border-blue-500 focus:border-transparent transition placeholder-gray-400 dark:placeholder-gray-300">
                </div>
                <button type="submit" class="cursor-pointer bg-emerald-500 hover:bg-emerald-600 dark:bg-emerald-600 dark:hover:bg-emerald-700 text-white font-semibold py-2 px-6 rounded-r-lg transition duration-200 shadow-sm focus:outline-none focus:ring-4 focus:ring-emerald-100 dark:focus:ring-emerald-900/50">
                    {{ __('Search') }}
                </button>
                {{-- Clear butonunun şartına isset($author) eklendi --}}
                @if($search || $categoryId || $status || isset($author))
                    <a href="{{ route('posts.index') }}" class="cursor-pointer bg-gray-400 hover:bg-gray-500 dark:bg-gray-600 dark:hover:bg-gray-700 text-white font-semibold ml-2 py-2 px-6 rounded-md transition duration-200 shadow-sm focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700/50">
                        {{ __('Clear') }}
                    </a>
                @endif
            </form>
            @if($search)
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">"{{ $search }}" {{ __('results found for') }} <strong>{{ $posts->total() }}</strong></p>
            @endif
            @if($categoryId && !$search)
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">{{ __('Category filtering is active') }}</p>
            @endif
            @if($status && !$search)
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">{{ __('Status filtering is active:') }} <strong>{{ __(ucfirst($status)) }}</strong></p>
            @endif
        </div>
        @endif

        <x-toast />

        <div class="md:rounded-lg md:border md:border-gray-200 md:dark:border-gray-700 md:shadow-sm md:dark:shadow-lg md:overflow-x-auto w-full">
            <table class="w-full divide-y divide-gray-200 dark:divide-gray-700 block md:table table-auto">
               <thead class="hidden md:table-header-group bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <tr>
                        <th class="px-6 py-0 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Post') }}</th>
                        
                        <th x-data="{ 
                        tooltipCategory: false, 
                        tooltipTop: 0, 
                        tooltipLeft: 0, 
                        timeout: null 
                    }" 
                        @mouseenter="
                            clearTimeout(timeout);
                            if(window.innerWidth >= 1024) {
                                // 1. Önce pozisyonu hesapla
                                const rect = $el.getBoundingClientRect();
                                tooltipTop = rect.bottom + 8;
                                tooltipLeft = rect.left;
                                
                                // 2. Sonra görünür yap
                                tooltipCategory = true;
                            }
                        "
                        @mouseleave="
                            timeout = setTimeout(() => { tooltipCategory = false; }, 150);
                        "
                        @click.away="tooltipCategory = false"
                        class="px-6 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider relative group cursor-pointer lg:cursor-help">
                        
                        <div @click="tooltipCategory = !tooltipCategory; 
                                    if (!tooltipCategory) {
                                        // Açılacaksa önce hesapla
                                        const rect = $el.getBoundingClientRect();
                                        tooltipTop = rect.bottom + 8;
                                        tooltipLeft = rect.left;
                                        tooltipCategory = true;
                                    } else {
                                        // Kapanacaksa direkt kapat
                                        tooltipCategory = false;
                                    }
                                "
                            class="inline-flex items-center gap-1 border-b border-dashed border-gray-300 dark:border-gray-500 pb-0.5">
                            <span>{{ __('Category') }}</span>
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                        
                        <div x-show="tooltipCategory"
                            @mouseenter="clearTimeout(timeout)"
                            @mouseleave="timeout = setTimeout(() => { tooltipCategory = false; }, 150)"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:leave="transition ease-in duration-200"
                            class="hidden md:block fixed z-50 w-56 p-3 text-sm bg-white dark:bg-gray-900 rounded-xl shadow-2xl backdrop-blur-xl border border-gray-200 dark:border-gray-700"
                            x-bind:style="{ top: tooltipTop + 'px', left: tooltipLeft + 'px' }"
                            style="display: none;">
                                
                                <div class="font-semibold text-gray-900 dark:text-white mb-2 pb-2 border-b border-gray-100 dark:border-gray-800 tracking-normal capitalize flex justify-between items-center">
                                    {{ __('All Categories') }}
                                </div>
                                
                                <div class="flex flex-col gap-1 max-h-60 overflow-y-auto custom-scrollbar tracking-normal normal-case">
                                    @isset($categories)
                                        @forelse($categories as $category)
                                            <a href="{{ route('categories.show', ['id' => $category->id, 'slug' => $category->slug]) }}" 
                                               class="text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-gray-50 dark:hover:bg-gray-800 px-2 py-1.5 rounded-md transition-colors duration-150 flex items-center justify-between">
                                                <span>{{ $category->name }}</span>
                                                <svg class="w-3 h-3 opacity-0 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                            </a>
                                        @empty
                                            <span class="text-xs text-gray-500 dark:text-gray-400 px-2">{{ __('Kategori bulunamadı.') }}</span>
                                        @endforelse
                                        
                                        <div class="w-4/5 border-t border-gray-200 dark:border-gray-700 my-2 mr-auto"></div>
                                        <a href="{{ route('categories.show-uncategorized') }}" 
                                           class="text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-gray-50 dark:hover:bg-gray-800 px-2 py-1.5 rounded-md transition-colors duration-150 flex items-center justify-between italic">
                                            <span>{{ __('Uncategorized') }}</span>
                                            <svg class="w-3 h-3 opacity-0 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                        </a>
                                    @else
                                        <span class="text-xs text-amber-500 dark:text-amber-400 px-2 italic">{{ __('Lütfen Controller\'dan $categories değişkenini gönderin.') }}</span>
                                    @endisset
                                </div>
                            </div>
                        </th>
                        @auth
                        @can('is-author')
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Status') }}</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Actions') }}</th>
                        @endcan
                        @endauth
                    </tr>
                </thead>
                
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 block md:table-row-group">
                    @forelse($posts as $post)
                    <tr class="flex flex-col md:table-row   @if($post->is_featured == 'featured')  bg-gray-50 dark:bg-gray-900 @else  bg-white dark:bg-gray-800  @endif       mb-4 md:mb-0 border border-gray-200 dark:border-gray-700 md:border-none rounded-xl md:rounded-none shadow-sm md:shadow-none hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors duration-150">
                        
                        <td x-data="{ 
                                tooltip: false, 
                                tooltipTop: 0, 
                                tooltipLeft: 0, 
                                loading: true, 
                                content: '',
                                createdAt: '',
                                createdAtHuman: ''
                            }" 
                            @click="window.innerWidth < 768 && (window.location.href = '{{ route('posts.show', ['post' => $post->id, 'slug' => $post->slug]) }}')"
                            class="block md:table-cell px-5 md:px-6 py-4 text-base font-medium text-gray-900 dark:text-white cursor-pointer md:cursor-default selection:bg-transparent relative">
                            
                            <span class="md:hidden text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest block mb-2">{{ __('Post Title') }}</span>
                            
                            <div class="relative inline-block w-full md:w-auto">
                               <a @mouseenter="window.innerWidth >= 768 && (async () => {
                                        tooltip = true;
                                        loading = true;
                                        content = '';
                                        createdAt = '';
                                        createdAtHuman = '';
                                        $nextTick(() => { 
                                            const linkRect = $event.target.getBoundingClientRect();
                                            tooltipTop = linkRect.top;
                                            tooltipLeft = linkRect.right + 12; 
                                        });
                                        try {
                                            const response = await fetch('{{ route('posts.preview', ['post' => $post->id]) }}');
                                            const data = await response.json();
                                            content = data.content;
                                            createdAt = data.created_at;
                                            createdAtHuman = data.created_at_human;
                                        } catch (error) {
                                            content = '{{ __('Error: Content could not be loaded') }}';
                                        } finally {
                                            loading = false;
                                        }
                                    })()" 
                                    @mouseleave="tooltip = false" 
                                    href="{{ route('posts.show', ['post' => $post->id, 'slug' => $post->slug]) }}" 
                                    @click="window.innerWidth < 768 && $event.stopPropagation()"
                                    class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 underline underline-offset-7 transition-colors duration-200 block md:inline-block break-words">
                                    {{ $post->title }}
                                </a>

                                @if($post->is_featured == 'featured')
                                    <span title="{{ __('Featured Post') }}" class="opacity-65">📌</span> 
                                @endif

                                <div x-show="tooltip"
                                    x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                    x-transition:leave="transition ease-in duration-200"
                                    x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                    x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
                                    class="hidden md:block fixed z-50 w-96 p-4 text-sm bg-white dark:bg-gray-900 rounded-xl shadow-2xl backdrop-blur-xl border border-gray-200 dark:border-gray-700"
                                    x-bind:style="{ top: tooltipTop + 'px', left: tooltipLeft + 'px' }"
                                    style="display: none;">
                                    
                                    <div class="tooltip-arrow"></div> 

                                    <div x-show="loading" class="flex items-center justify-center py-8">
                                        <div class="space-y-2 w-full">
                                            <div class="h-3 bg-gradient-to-r from-gray-200 via-gray-100 to-gray-200 dark:from-gray-700 dark:via-gray-600 dark:to-gray-700 rounded-full animate-pulse"></div>
                                            <div class="h-2 bg-gradient-to-r from-gray-200 via-gray-100 to-gray-200 dark:from-gray-700 dark:via-gray-600 dark:to-gray-700 rounded-full animate-pulse w-5/6"></div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 text-center mt-2">{{ __('Loading...') }}</p>
                                        </div>
                                    </div>

                                    <div x-show="!loading" class="space-y-3">
                                        <div class="prose prose-sm dark:prose-invert max-w-none">
                                            <p class="text-gray-700 dark:text-gray-300  text-base leading-relaxed m-0" x-text="content"></p>
                                        </div>

                                        <div class="my-3 h-px bg-gradient-to-r from-transparent via-gray-300 dark:via-gray-600 to-transparent"></div>

                                        <div class="flex items-center justify-between pt-1">
                                            
                                            <div class="flex items-center gap-2 ml-4">
                                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <div class="flex flex-col">
                                                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">{{ __('PUBLISHED Date') }}</span>
                                                    <span class="text-sm font-semibold text-gray-900 dark:text-white" x-text="createdAt"></span>
                                                    <span class="text-xs text-gray-500 dark:text-gray-400 italic" x-text="createdAtHuman"></span>
                                                </div>
                                            </div>

                                            <div class="flex items-center gap-1.5 text-gray-500 dark:text-gray-400 mr-7">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                                                </svg> 
                                                <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $post->view_count }} {{ __('Views') }}</span>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="block md:table-cell px-5 md:px-6 py-3 md:py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 border-t border-dashed border-gray-100 dark:border-gray-700/60 md:border-none">
                            
                            <div x-data="{ tooltipCategoryMobile: false, tooltipTop: 0, tooltipLeft: 0 }" class="md:hidden mb-4" @click.away="tooltipCategoryMobile = false">
                                <span @click="tooltipCategoryMobile = !tooltipCategoryMobile; 
                                             if(tooltipCategoryMobile) { 
                                                 $nextTick(() => { 
                                                     const rect = $el.getBoundingClientRect(); 
                                                     tooltipTop = rect.bottom + 8; 
                                                     tooltipLeft = 20; /* Ekrana sığması için sola sabitliyoruz */
                                                 })
                                             }" 
                                      class="text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest cursor-pointer inline-flex items-center gap-1 border-b border-dashed border-gray-400 dark:border-gray-500 pb-[1px]">
                                    {{__('Category')}}
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </span>

                                <div x-show="tooltipCategoryMobile"
                                    x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                    x-transition:leave="transition ease-in duration-200"
                                    x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                    x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
                                    class="fixed z-50 w-56 max-w-[90vw] p-3 text-sm bg-white dark:bg-gray-900 rounded-xl shadow-2xl backdrop-blur-xl border border-gray-200 dark:border-gray-700"
                                    x-bind:style="{ top: tooltipTop + 'px', left: tooltipLeft + 'px' }"
                                    style="display: none;">
                                    
                                    <div class="font-semibold text-gray-900 dark:text-white mb-2 pb-2 border-b border-gray-100 dark:border-gray-800 tracking-normal capitalize flex justify-between items-center">
                                        {{ __('All Categories') }}
                                        <button @click.stop="tooltipCategoryMobile = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </div>
                                    
                                    <div class="flex flex-col gap-1 max-h-60 overflow-y-auto custom-scrollbar tracking-normal normal-case">
                                        @isset($categories)
                                            @forelse($categories as $category)
                                                <a href="{{ route('categories.show', ['id' => $category->id, 'slug' => $category->slug]) }}" 
                                                   class="text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-gray-50 dark:hover:bg-gray-800 px-2 py-1.5 rounded-md transition-colors duration-150 flex items-center justify-between">
                                                    <span>{{ $category->name }}</span>
                                                </a>
                                            @empty
                                                <span class="text-xs text-gray-500 dark:text-gray-400 px-2">{{ __('Category not found.') }}</span>
                                            @endforelse
                                            
                                            <div class="my-1 h-px bg-gray-100 dark:bg-gray-700"></div>
                                            <a href="{{ route('categories.show-uncategorized') }}" 
                                               class="text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-gray-50 dark:hover:bg-gray-800 px-2 py-1.5 rounded-md transition-colors duration-150 flex items-center justify-between italic">
                                                <span>{{ __('Uncategorized') }}</span>
                                            </a>
                                        @else
                                            <span class="text-xs text-amber-500 dark:text-amber-400 px-2 italic">{{ __('Failed to load.') }}</span>
                                        @endisset
                                    </div>
                                </div>
                            </div>
                            @if($post->category)
                                <a href="{{ route('categories.show', ['id' => $post->category->id, 'slug' => $post->category->slug]) }}" 
                                class="text-gray-500 dark:text-gray-400 hover:underline underline-offset-4 transition-all duration-150">
                                    {{ $post->category->name }}
                                </a>
                            @else
                                <a href="{{ route('categories.show-uncategorized') }}" 
                                class="text-gray-500 dark:text-gray-400 hover:underline underline-offset-4 transition-all duration-150">
                                    {{ __('Uncategorized') }}
                                </a>
                            @endif

                        </td>

                        @auth
                        @can('manage-post', $post)
                        <td class="block md:table-cell px-5 md:px-6 py-3 md:py-3 whitespace-nowrap text-sm border-t border-dashed border-gray-100 dark:border-gray-700/60 md:border-none">
                            <span class="md:hidden text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest block mb-2">{{ __('Status') }}</span>
                            
                            <a href="{{ route('posts.index', ['status' => $post->status]) }}" 
                            class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full {{ $post->status === 'publish' ? 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300 hover:underline' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 hover:underline' }} transition-all duration-150 underline-offset-2">
                                {{ __(ucfirst($post->status)) }}
                            </a>
                        </td>

                        <td class="block md:table-cell px-5 md:px-6 py-4 whitespace-nowrap text-sm font-medium border-t border-gray-100 dark:border-gray-700 md:border-none bg-gray-50/50 dark:bg-gray-800/50 md:bg-transparent rounded-b-xl md:rounded-none">
                            
                            <div class="flex flex-row items-center justify-center gap-4 md:gap-3">
                                <span class="md:hidden text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest m-0">{{ __('Actions') }}:</span>
                                
                                <div class="flex items-center gap-6 md:gap-3">
                                    <a href="{{ route('posts.edit', $post->id) }}" class="text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition-colors" title="{{ __('Edit') }}">
                                        <svg class="w-6 h-6 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>

                                    <form action="{{ route('posts.destroy', $post) }}" method="POST" class="inline-block m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('{{ __('Are you sure you want to delete this post?') }}')" title="{{ __('Delete Post Permanently') }}" class="cursor-pointer -mt-0.5 text-gray-400 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition duration-200 flex items-center">
                                            <svg class="w-6 h-6 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            
                        </td>
                        @endcan
                        @endauth
                    </tr>
                    @empty
                    <tr class="block md:table-row">
                        <td colspan="5" class="block md:table-cell px-6 py-10 text-center text-sm text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 rounded-xl md:rounded-none border border-gray-200 dark:border-gray-700 md:border-none">
                            {{ __('No posts found.') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $posts->appends(request()->query())->links() }}
        </div>
    </div>
</x-layouts.posts>