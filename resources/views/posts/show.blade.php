<x-layouts.posts :title="$post->title" :includePostContent="true">
    {{-- Ana içeriği div yerine anlamsal (semantic) <article> etiketiyle sarıyoruz --}}
    <article class="max-w-4xl mx-auto bg-white dark:bg-gray-800 p-4 md:p-7 rounded-lg shadow-md dark:shadow-2xl dark:shadow-gray-950 mt-4 md:mt-6">
        
        {{-- Başlık ve meta bilgileri <header> içine alıyoruz --}}
        <header class="flex flex-col md:flex-row justify-between items-start mb-6 pb-6 border-b border-gray-200 dark:border-gray-700 gap-4">
            
            <div class="w-full">
                <h1 class="text-xl md:text-2xl font-bold text-gray-900 dark:text-gray-300 mb-3 leading-tight">
                    {{ $post->title }}

                    @if($post->is_featured == 'featured')
                        {{-- Emojiler ekran okuyucuları şaşırtmasın diye aria-hidden ekliyoruz --}}
                        <span title="{{ __('Featured Post') }}" class="opacity-65" aria-hidden="true">📌</span> 
                        <span class="sr-only">{{ __('Featured Post') }}</span>
                    @endif
                </h1>
                
                <div class="flex flex-wrap items-center gap-4 text-xs md:text-sm text-gray-500 dark:text-gray-400">
                    
                @if($post->category)
                    @if(preg_match('/\p{So}/u', $post->category->name))
                        {{-- Kategori VAR ve içinde 🖥️ gibi bir sembol VARSA --}}
                        <a href="{{ route('categories.show', ['id' => $post->category->id, 'slug' => $post->category->slug]) }}" 
                        class="text-gray-500 dark:text-gray-400 hover:underline underline-offset-4 transition-all duration-150">
                            {{ $post->category->name }}
                        </a>
                    @else
                        {{-- Kategori VAR ama içinde sembol YOKSA (Başına klasör emojisi ekledik) --}}
                        <a href="{{ route('categories.show', ['id' => $post->category->id, 'slug' => $post->category->slug]) }}" 
                        class="text-gray-500 dark:text-gray-400 hover:underline underline-offset-4 transition-all duration-150">
                            <span aria-hidden="true">📁</span> {{ $post->category->name }}
                        </a>
                    @endif
                @else
                    {{-- Kategori HİÇ YOKSA (Uncategorized durumu) --}}
                    <a href="{{ route('categories.show-uncategorized') }}" 
                    class="text-gray-500 dark:text-gray-400 hover:underline underline-offset-4 transition-all duration-150">
                        {{ __('Uncategorized') }}
                    </a>
                @endif

                    @auth
                    @can('manage-post', $post)
                        <a href="{{ route('posts.index', ['status' => $post->status]) }}" 
                           class="px-2 py-0.5 inline-flex text-[10px] md:text-xs font-semibold rounded-full {{ $post->status === 'publish' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' }}">
                            {{ __(ucfirst($post->status)) }}
                        </a>
                    @endcan
                    @endauth

                    <div class="views-count flex items-center gap-1.5">
                        {{-- Süsleyici ikonları ekran okuyuculardan gizliyoruz --}}
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500 dark:text-gray-400">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                        </svg> 
                        <span class="text-gray-600 dark:text-gray-400"><strong class="dark:text-gray-300">{{ $post->view_count }}</strong> {{ __('times read.') }}</span>
                    </div>


                    
                    @if($siteSettings['show_post_date'] ?? false)
                    <span class="flex items-center gap-2" title="{{ __('Created at') }} ">
                                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clock" viewBox="0 0 16 16">
                                    <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71z"/>
                                    <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0"/>
                                </svg>
                                <time datetime="{{ $post->created_at->toIso8601String() }}">{{ $post->created_at->translatedFormat('d M, Y') }}</time>
                    </span>
                    @endif

                    @if($siteSettings['show_updated_date'] ?? false)
                    <span class="flex items-center gap-2" title="{{ __('Updated at') }}">
                         @if($post->updated_at->gt($post->created_at))
                                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                </svg>
                                <time datetime="{{ $post->updated_at->toIso8601String() }}">{{ $post->updated_at->translatedFormat('d M, Y') }}</time>
                        @endif
                    </span>
                    @endif
                </div>
            </div>

            @auth
            @can('manage-post', $post)
            {{-- Rol tanımları ve erişilebilirlik etiketleri eklendi --}}
            <div class="flex w-full md:w-auto gap-2 border-t md:border-none pt-4 md:pt-0 border-gray-100 dark:border-gray-700" role="group" aria-label="{{ __('Post Actions') }}">
                <a href="{{ route('posts.edit', $post->id) }}" title="{{ __('Edit Post') }}" aria-label="{{ __('Edit Post') }}" class="flex-1 md:flex-none flex items-center justify-center p-2 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:text-green-600 transition-colors">
                    <svg aria-hidden="true" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </a>
                <form action="{{ route('posts.destroy', $post) }}" method="POST" class="flex-1 md:flex-none">
                    @csrf
                    @method('DELETE')
                    <button type="submit" title="{{ __('Delete Post') }}" aria-label="{{ __('Delete Post') }}" onclick="return confirm('{{ __('Are you sure you want to delete this post?') }}')" class="cursor-pointer w-full flex items-center justify-center p-2 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:text-red-600 transition-colors">
                        <svg aria-hidden="true" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </form>
            </div>
            @endcan
            @endauth
        </header>

        <x-toast />

        <div class="prose dark:prose-invert  text-sm sm:text-base md:text-lg leading-relaxed  post-content max-w-none px-0 md:px-2 dynamic-article-content text-gray-800 dark:text-gray-300 prose-img:rounded-lg prose-img:my-4">
            {!! $post->content_with_ads !!}
        </div>
        
        @if($siteSettings['enable_social_share'] ?? false)
        <aside aria-label="{{ __('Social Sharing') }}" class="py-4 mt-8 ml-6 mr-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 select-none">
            <div class="space-y-1">
                <h4 class="text-sm font-medium text-gray-800 dark:text-zinc-200">
                    {{ __('Share this post on social media') }}
                </h4>
                <p class="text-xs text-gray-500 dark:text-zinc-400">
                    {{ __('Knowledge multiplies as it is shared.') }}
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <a href="https://api.whatsapp.com/send?text={{ urlencode(url('/posts/' . $post->id . '/' . $post->slug)) }}" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp'ta paylaş" 
                class="inline-flex items-center justify-center w-10 h-10 rounded-xl text-gray-600 dark:text-zinc-400 bg-gray-50 dark:bg-zinc-900/50 border border-gray-200/60 dark:border-zinc-800/80 hover:text-[#25D366] hover:bg-[#25D366]/10 hover:border-[#25D366]/30 transition-all duration-300 transform hover:-translate-y-0.5">
                <svg aria-hidden="true" class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.662c1.746.953 3.71 1.455 5.703 1.456h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                </a>

                <a href="https://t.me/share/url?url={{ urlencode(url('/posts/' . $post->id . '/' . $post->slug)) }}" target="_blank" rel="noopener noreferrer" aria-label="Telegram'da paylaş" 
                class="inline-flex items-center justify-center w-10 h-10 rounded-xl text-gray-600 dark:text-zinc-400 bg-gray-50 dark:bg-zinc-900/50 border border-gray-200/60 dark:border-zinc-800/80 hover:text-[#24A1DE] hover:bg-[#24A1DE]/10 hover:border-[#24A1DE]/30 transition-all duration-300 transform hover:-translate-y-0.5">
                <svg aria-hidden="true" class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.446 1.394c-.16.16-.295.295-.605.295l.213-3.053 5.56-5.023c.242-.213-.054-.333-.373-.12l-6.87 4.326-2.962-.924c-.643-.204-.657-.643.136-.953l11.57-4.46c.536-.194 1.005.127.84.957z"/></svg>
                </a>

                <a href="https://twitter.com/intent/tweet?url={{ urlencode(url('/posts/' . $post->id . '/' . $post->slug)) }}" target="_blank" rel="noopener noreferrer" aria-label="X'te paylaş" 
                class="inline-flex items-center justify-center w-10 h-10 rounded-xl text-gray-600 dark:text-zinc-400 bg-gray-50 dark:bg-zinc-900/50 border border-gray-200/60 dark:border-zinc-800/80 hover:text-black hover:bg-black/5 dark:hover:text-white dark:hover:bg-white/10 dark:hover:border-zinc-700 transition-all duration-300 transform hover:-translate-y-0.5">
                <svg aria-hidden="true" class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                </a>

                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url('/posts/' . $post->id . '/' . $post->slug)) }}" target="_blank" rel="noopener noreferrer" aria-label="Facebook'ta paylaş" 
                class="inline-flex items-center justify-center w-10 h-10 rounded-xl text-gray-600 dark:text-zinc-400 bg-gray-50 dark:bg-zinc-900/50 border border-gray-200/60 dark:border-zinc-800/80 hover:text-[#1877F2] hover:bg-[#1877F2]/10 hover:border-[#1877F2]/30 transition-all duration-300 transform hover:-translate-y-0.5">
                <svg aria-hidden="true" class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                </a>

                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url('/posts/' . $post->id . '/' . $post->slug)) }}" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn'de paylaş" 
                class="inline-flex items-center justify-center w-10 h-10 rounded-xl text-gray-600 dark:text-zinc-400 bg-gray-50 dark:bg-zinc-900/50 border border-gray-200/60 dark:border-zinc-800/80 hover:text-[#0A66C2] hover:bg-[#0A66C2]/10 hover:border-[#0A66C2]/30 transition-all duration-300 transform hover:-translate-y-0.5">
                <svg aria-hidden="true" class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                </a>

                {{-- Buton olduğu için type="button" eklendi --}}
                <button type="button" onclick="navigator.clipboard.writeText('{{ url('/posts/' . $post->id . '/' . $post->slug) }}'); alert('{{ __('Link copied successfully!') }}');" aria-label="Bağlantıyı kopyala" 
                        class="inline-flex items-center justify-center w-10 h-10 rounded-xl text-gray-600 dark:text-zinc-400 bg-gray-50 dark:bg-zinc-900/50 border border-gray-200/60 dark:border-zinc-800/80 hover:text-amber-500 hover:bg-amber-500/10 hover:border-amber-500/30 transition-all duration-300 transform hover:-translate-y-0.5 cursor-pointer">
                <svg aria-hidden="true" class="w-5 h-5 fill-none stroke-current stroke-2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" /></svg>
                </button>
            </div>
        </aside>
        @endif

    @if($siteSettings['enable_author_card'] ?? false)
        <footer class="mt-8 relative bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700/60 rounded-xl p-5 sm:p-8 shadow-xs  transition-all duration-300 overflow-hidden" aria-label="{{ __('Author Bio') }}">
            <div class="flex flex-col sm:flex-row items-center sm:items-start text-center sm:text-left gap-6 mt-2">
                
                <div class="flex-1 min-w-0 w-full">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-5 sm:gap-4 mb-4 p-1 relative group">
        
                        <div class="flex flex-col sm:flex-row items-center gap-3 sm:gap-4 w-full sm:w-auto">
                            <div class="relative shrink-0 mb-2 sm:mb-0">
                                <div class="absolute inset-0 bg-indigo-500 rounded-full blur opacity-20 group-hover:opacity-40 transition-opacity duration-300"></div>
                                <img class="relative w-20 h-20 sm:w-20 sm:h-20 rounded-full object-cover ring-4 ring-white dark:ring-gray-800 shadow-md" 
                                    src="{{ $post->user->avatar ? asset('storage/' . $post->user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($post->user->name) . '&color=7F9CF5&background=EBF4FF' }}" 
                                    alt="{{ $post->user->name }}">
                            </div>

                            <div class="flex flex-col items-center sm:items-start">
                                <h4 class="text-xl sm:text-2xl font-extrabold text-gray-900 dark:text-white tracking-tight mb-1.5 sm:mb-1">
                                    {{ $post->user->name }}
                                </h4>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 border border-indigo-100 dark:border-indigo-800/50 shadow-sm">
                                    <span aria-hidden="true">✏️</span> {{ __('Author') }}
                                </span>
                            </div>
                        </div>

                        <div class="flex justify-center sm:justify-end mt-1 sm:mt-0 w-full sm:w-auto">
                            <a href="/posts?author={{ $post->user->id }}" class="inline-flex items-center justify-center w-full sm:w-auto gap-1.5 px-5 py-2.5 text-sm font-semibold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-gray-700/50 hover:bg-indigo-100 dark:hover:bg-gray-700 rounded-xl transition-colors group whitespace-nowrap shadow-sm">
                                {{ __('All Posts') }} 
                                <svg aria-hidden="true" class="w-4 h-4 transform group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>

                    </div>

                    <p class="text-base text-gray-600 dark:text-gray-300 leading-relaxed mb-6 px-2 sm:px-0">
                        {{ $post->user->bio ?? __('This author has not added a biography yet.') }}
                    </p>

                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-3 border-t border-gray-100 dark:border-gray-700/50 pt-5">
                        @if($post->user->show_email == 1)
                        <a href="mailto:{{ $post->user->email }}" aria-label="{{ __('Send Email') }}" class="flex items-center justify-center w-11 h-11 sm:w-10 sm:h-10 rounded-full bg-gray-50 dark:bg-gray-700/50 text-gray-500 dark:text-gray-400 hover:bg-red-50 hover:text-red-500 dark:hover:bg-gray-700 dark:hover:text-red-400 transition-all duration-200 shadow-sm">
                            <svg aria-hidden="true" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 002-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </a>
                        @endif

                        @if($post->user->github_url)
                            <a href="{{ $post->user->github_url }}" target="_blank" aria-label="GitHub Profiline Git" class="flex items-center justify-center w-11 h-11 sm:w-10 sm:h-10 rounded-full bg-gray-50 dark:bg-gray-700/50 text-gray-500 dark:text-gray-400 hover:bg-gray-100 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white transition-all duration-200 shadow-sm">
                                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.477 2 12c0 4.42 2.865 8.166 6.839 9.489.5.092.682-.217.682-.48,0-.237-.008-.866-.013-1.7-2.782.603-3.369-1.34-3.369-1.34-.454-1.156-1.11-1.462-1.11-1.462-.908-.62.069-.608.069-.608 1.003.07 1.531 1.03 1.531 1.03.892 1.529 2.341 1.087 2.91.831.092-.646.35-1.086.636-1.336-2.22-.253-4.555-1.11-4.555-4.943 0-1.091.39-1.984 1.029-2.683-.103-.253-.446-1.27.098-2.647 0 0 .84-.269 2.75 1.025A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.294 2.747-1.025 2.747-1.025.546 1.377.203 2.394.1 2.647.64.699 1.028 1.592 1.028 2.683 0 3.842-2.339 4.687-4.566 4.935.359.309.678.919.678 1.852 0 1.336-.012 2.415-.012 2.743 0 .267.18.577.688.479C19.138 20.164 22 16.418 22 12c0-5.523-4.477-10-10-10z"/></svg>
                            </a>
                        @endif

                        @if($post->user->linkedin_url)
                            <a href="{{ $post->user->linkedin_url }}" target="_blank" aria-label="LinkedIn Profiline Git" class="flex items-center justify-center w-11 h-11 sm:w-10 sm:h-10 rounded-full bg-gray-50 dark:bg-gray-700/50 text-gray-500 dark:text-gray-400 hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-gray-700 dark:hover:text-blue-400 transition-all duration-200 shadow-sm">
                                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                            </a>
                        @endif

                        @if($post->user->twitter_url)
                            <a href="{{ $post->user->twitter_url }}" target="_blank" aria-label="Twitter/X Profiline Git" class="flex items-center justify-center w-11 h-11 sm:w-10 sm:h-10 rounded-full bg-gray-50 dark:bg-gray-700/50 text-gray-500 dark:text-gray-400 hover:bg-gray-200 hover:text-black dark:hover:bg-gray-600 dark:hover:text-white transition-all duration-200 shadow-sm">
                                <svg aria-hidden="true" class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            </a>
                        @endif

                    </div>
                </div>
            </div>
        </footer>
    @endif

    </article>


    @if($siteSettings['allow_submit_comments'] ?? false)

    <section aria-labelledby="comments-form-heading" class="max-w-4xl mx-auto mt-16 transition-colors duration-300">
        <div x-data="{ isSubmitting: false, content: @js(old('content', '')) }" class="bg-white dark:bg-gray-800 p-8 rounded-2xl border border-gray-100 dark:border-zinc-800 shadow-sm transition-all">
            <h3 id="comments-heading" class="text-2xl mb-6 text-gray-900 dark:text-zinc-100 tracking-tight" style="font-family: 'EB Garamond', serif;">{{ __('Share Your Thoughts') }}</h3>
            
            <form action="{{ route('comments.store', $post->id) }}" method="POST" 
            @submit.prevent="
                if (typeof hcaptcha !== 'undefined' && hcaptcha.getResponse() === '') {
                    alert('{{ __('Please verify that you are not a robot first!') }}');
                } else {
                    isSubmitting = true; // Start animation only if hCaptcha is filled
                    $el.submit();        // Submit the form
                }
            " id="comment-form">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        {{-- Label and Input connected with for/id --}}
                        <label for="comment_name" class="block text-sm font-medium text-gray-600 dark:text-zinc-400 mb-2">{{ __('Your Name') }}</label>
                        <input type="text" id="comment_name" name="name" required value="{{ old('name', auth()->user()?->name) }}" class="w-full px-4 py-3 bg-gray-50 dark:bg-black/20 border border-gray-200 dark:border-zinc-800 rounded-xl text-gray-900 dark:text-zinc-100 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all placeholder-gray-400 dark:placeholder-zinc-600">
                        @error('name') <span class="text-red-500 text-xs mt-1 block" role="alert">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        {{-- Label and Input connected with for/id --}}
                        <label for="comment_email" class="block text-sm font-medium text-gray-600 dark:text-zinc-400 mb-2">{{ __('Email (Private)') }}</label>
                        <input type="email" id="comment_email" name="email" required value="{{ old('email', auth()->user()?->email) }}" class="w-full px-4 py-3 bg-gray-50 dark:bg-black/20 border border-gray-200 dark:border-zinc-800 rounded-xl text-gray-900 dark:text-zinc-100 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all placeholder-gray-400 dark:placeholder-zinc-600">
                        @error('email') <span class="text-red-500 text-xs mt-1 block" role="alert">{{ $message }}</span> @enderror
                    </div>
                </div>
                
                <div class="mb-6">
                    <div class="flex justify-between items-end mb-2">
                        {{-- Label and Textarea connected with for/id --}}
                        <label for="comment_content" class="block text-sm font-medium text-gray-600 dark:text-zinc-400">{{ __('Your Comment') }}</label>
                        <span class="text-xs text-gray-400 dark:text-zinc-500" x-text="content.length + '/2000'" aria-live="polite"></span>
                    </div>
                    <textarea id="comment_content" name="content" x-model="content" required rows="5" maxlength="2000" class="w-full px-4 py-3 bg-gray-50 dark:bg-black/20 border border-gray-200 dark:border-zinc-800 rounded-xl text-gray-900 dark:text-zinc-100 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all resize-y placeholder-gray-400 dark:placeholder-zinc-600">{{ old('content') }}</textarea>
                    @error('content') <span class="text-red-500 text-xs mt-1 block" role="alert">{{ $message }}</span> @enderror
                </div>
                
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="h-captcha" data-sitekey="{{ \App\Models\Setting::where('key', 'hcaptcha_site_key')->value('value') ?? '' }}"></div>

                    {{-- "Processing" text will be announced immediately to screen readers (aria-live="polite") --}}
                    <button type="submit" x-bind:disabled="isSubmitting || content.length < 5" class="bg-gray-500 dark:bg-zinc-600 text-white dark:text-zinc-900 px-8 py-3 rounded-xl hover:opacity-90 transition-all disabled:opacity-50 font-medium h-[50px] flex items-center justify-center">
                        <span x-show="!isSubmitting">{{ __('Post Comment') }}</span>
                        <span x-show="isSubmitting" class="flex items-center gap-2" aria-live="polite">
                            <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24" aria-hidden="true"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                            {{ __('Processing...') }}
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </section>
    @endif

    @if($siteSettings['allow_show_comments'] ?? false)
    <section aria-labelledby="comments-list-heading" class="max-w-4xl mx-auto mt-16 transition-colors duration-300">

        <div id="comments-container" aria-live="polite" class="bg-white space-y-6 dark:bg-gray-800 p-8 rounded-2xl border border-gray-100 dark:border-zinc-800 shadow-sm mb-12 transition-all">
            <h3 class="text-2xl text-gray-900 dark:text-zinc-100 mb-8" style="font-family: 'EB Garamond', serif;">
                {{ __('Comments') }} <span class="text-gray-400 dark:text-zinc-500 text-lg">({{ $post->comments->count() }})</span>
            </h3>
            
            @forelse($post->comments as $comment)
                <article id="comment-{{ $comment->id }}" 
                class="flex gap-4 p-4 rounded-xl dark:bg-gray-700/50 hover:bg-gray-100 dark:hover:bg-zinc-900/50 
                transition-all duration-700 
                @if(!$loop->last) border-b @endif border-gray-100 dark:border-zinc-700/50 
                target:bg-orange-50 dark:target:bg-amber-950/30 
                target:ring-0 target:ring-orange-100 dark:target:ring-amber-700
                target:scale-[1.01]">

                    <div class="shrink-0 mt-1">

                        @if($comment->user->avatar)
                            {{-- Avatarı varsa bu img çalışacak --}}
                            <img class="w-12 h-12 rounded-full border-4 mr-2 border-gray-100 text-gray-500 dark:text-gray-400 dark:border-gray-600 object-cover" 
                                src="{{ asset('storage/' . $comment->user->avatar) }}" 
                                alt="{{ $comment->user->name }}">
                        @else
                            {{-- Avatarı yoksa bu div çalışacak --}}
                            <div class="w-10 h-10 bg-indigo-50 dark:bg-indigo-900/30 border border-indigo-200 dark:border-indigo-500/20 rounded-full flex items-center justify-center text-indigo-700 dark:text-indigo-200 font-medium shadow-sm transition-colors duration-300" aria-hidden="true">
                                {{ mb_strtoupper(mb_substr($comment->name, 0, 1)) }}
                            </div>
                        @endif
                        
                    </div>
                    <div class="flex-1">
                        <header class="flex items-center gap-3 mb-1">
                            <h4 class="font-semibold text-gray-900 dark:text-zinc-200 text-sm">{{ $comment->name }}</h4>
                            
                            @if($comment->user)
                                @if($comment->user->role === 'founder')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-rose-50 dark:bg-rose-500/10 text-rose-700 dark:text-rose-400 border border-rose-100 dark:border-rose-500/20">
                                        {{ __('Founder') }}
                                    </span>
                                @elseif($comment->user->role === 'admin')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-500/20">
                                        {{ __('Administrator') }}
                                    </span>
                                @elseif($comment->user->role === 'author')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-amber-50 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400 border border-amber-100 dark:border-amber-500/20">
                                        {{ __('Author') }}
                                    </span>
                                @elseif($comment->user->role === 'user')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-blue-50 dark:bg-blue-500/10 text-blue-500 dark:text-blue-400 border border-blue-100 dark:border-blue-500/20">
                                        {{ __('Member') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-gray-50 dark:bg-gray-500/10 text-gray-400 dark:text-gray-500 border border-gray-100 dark:border-gray-500/20">
                                        {{ __('Guest') }}
                                    </span>
                                @endif
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-gray-50 dark:bg-gray-500/10 text-gray-500 dark:text-gray-400 border border-gray-200 dark:border-gray-500/20">
                                    {{ __('Guest') }}
                                </span>
                            @endif
                            <time datetime="{{ $comment->created_at->toIso8601String() }}" class="text-xs text-gray-400 dark:text-zinc-500">{{ $comment->created_at->diffForHumans() }}</time>
                        </header>
                        <div class="text-gray-600 dark:text-zinc-300 text-sm leading-relaxed prose dark:prose-invert max-w-none prose-p:my-1">
                            {!! $comment->content !!}
                        </div>
                    </div>
                </article>
            @empty
                <div class="text-center py-12 text-gray-400 dark:text-zinc-300 italic border-t border-gray-100 dark:border-zinc-800">
                    {{ __('No comments yet. Be the first to write a comment!') }}
                </div>
            @endforelse
        </div>
       
    </section>
    @endif

</x-layouts.posts>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        let hcaptchaLoaded = false;
        const commentForm = document.getElementById('comment-form'); 

        if (commentForm) {
            function loadHcaptcha() {
                if (hcaptchaLoaded) return;

                let script = document.createElement('script');
                script.src = "https://js.hcaptcha.com/1/api.js";
                script.async = true;
                script.defer = true;
                document.head.appendChild(script);

                hcaptchaLoaded = true;

                commentForm.removeEventListener('click', loadHcaptcha);
                commentForm.removeEventListener('focusin', loadHcaptcha);
            }

            commentForm.addEventListener('click', loadHcaptcha);
            commentForm.addEventListener('focusin', loadHcaptcha);
        }
    });
</script>

<script>
    window.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.post-content pre').forEach((preBloku) => {
            
            // Senin olmadığın arada konuştuğumuz o performans odaklı renklendirme (DOM engellemesini önleyen)
            if (typeof hljs !== 'undefined') {
                hljs.highlightElement(preBloku);
            }

            let butonGövdesi = document.createElement('div');
            butonGövdesi.setAttribute('x-data', `{ 
                copied: false, 
                copyCode() { 
                    navigator.clipboard.writeText(this.$refs.kod.innerText); 
                    this.copied = true; 
                    setTimeout(() => this.copied = false, 2000); 
                } 
            }`);
            butonGövdesi.className = "relative group w-full";

            // Form yollamalarını engellemek için buton type="button" olarak düzeltildi
            butonGövdesi.innerHTML = `
                <button type="button" @click="copyCode()" 
                        class="absolute right-2 top-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200 bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:text-blue-500 dark:hover:text-blue-400 p-1.5 rounded border border-gray-200 dark:border-gray-700 text-xs font-medium shadow-sm cursor-pointer z-10"
                        :title="copied ? '{{ __('Copied!') }}' : '{{ __('Copy Code') }}'">
                    <span x-text="copied ? '✓ {{ __('Copied') }}' : '📋 {{ __('Copy') }}'"></span>
                </button>
            `;

            preBloku.setAttribute('x-ref', 'kod');
            
            preBloku.parentNode.insertBefore(butonGövdesi, preBloku);
            butonGövdesi.appendChild(preBloku);
        });
    });
</script>