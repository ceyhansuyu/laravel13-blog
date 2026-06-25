<x-layouts.posts :title="$page->title" :includePostContent="true">

    {{-- show.blade.php'den alınan dış kabuk ve arka plan yapısı --}}
    <article class="max-w-4xl mx-auto bg-white dark:bg-gray-800 p-4 md:p-7 rounded-lg shadow-md dark:shadow-2xl dark:shadow-gray-950 mt-4 md:mt-6">
        
        {{-- Başlık ve meta bilgileri <header> içine alıyoruz --}}
        <header class="flex flex-col md:flex-row justify-between items-start mb-6 pb-6 border-b border-gray-200 dark:border-gray-700 gap-4">
            
            <div class="w-full">
                <h1 class="text-xl md:text-2xl font-bold text-gray-900 dark:text-gray-300 mb-3 leading-tight">
                    {{ $page->title }}
                </h1>
            </div>
        </header>

        <x-toast />

        {{-- show.blade.php'deki içerik tipografisi (prose) birebir kullanıldı --}}
        <div class="prose dark:prose-invert text-sm sm:text-base md:text-lg leading-relaxed post-content max-w-none px-0 md:px-2 dynamic-article-content text-gray-800 dark:text-gray-300 prose-img:rounded-lg prose-img:my-4">
            {!! $page->content !!}
        </div>

    </article>

</x-layouts.posts>