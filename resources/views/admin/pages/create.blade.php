<x-app-layout :title="__('Add New Page')">

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add New Page') }}
        </h2>
    </x-slot>

    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    
    <style>
        /* Dark Mode Quill JS Adjustments */
        .dark .ql-toolbar.ql-snow {
            background-color: #1f2937; /* gray-800 */
            border-color: #374151; /* gray-700 */
        }
        .dark .ql-toolbar.ql-snow .ql-stroke {
            stroke: #d1d5db; /* gray-300 */
        }
        .dark .ql-toolbar.ql-snow .ql-fill {
            fill: #d1d5db;
        }
        .dark .ql-toolbar.ql-snow .ql-picker {
            color: #d1d5db;
        }
        .dark .ql-container.ql-snow {
            border-color: #374151; /* gray-700 */
            background-color: #111827; /* gray-900 */
            color: #d1d5db; /* gray-300 */
            min-height: 300px;
            font-size: 1rem;
            border-bottom-left-radius: 0.5rem;
            border-bottom-right-radius: 0.5rem;
        }
        .dark .ql-toolbar.ql-snow + .ql-container.ql-snow {
            border-top: 0px;
        }
        .dark .ql-snow .ql-picker-options {
            background-color: #1f2937;
            border-color: #374151;
        }
        .ql-toolbar.ql-snow {
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
            font-family: inherit;
        }
        .ql-container.ql-snow {
            min-height: 300px;
            font-family: inherit;
            border-bottom-left-radius: 0.5rem;
            border-bottom-right-radius: 0.5rem;
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                @if ($errors->any())
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg dark:bg-red-900/40 dark:border-red-800 dark:text-red-200">
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.pages.store') }}" method="POST" id="page-form">
                    @csrf

                    <div class="mb-6">
                        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Page Title') }}</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm py-2.5 px-4">
                    </div>

                    <div class="mb-6">
                        <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('URL (Slug)') }} <span class="text-gray-400 dark:text-gray-500 text-xs font-normal">{{ __('- Leave empty to generate automatically from the title') }}</span></label>
                        <input type="text" name="slug" id="slug" value="{{ old('slug') }}" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm py-2.5 px-4">
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Page Content') }}</label>
                        
                        <div id="editor-container"></div>
                        
                        <input type="hidden" name="content" id="hidden-content">
                    </div>

                    <div class="mb-6 flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-indigo-600 focus:ring-indigo-500 w-5 h-5 cursor-pointer">
                        <label for="is_active" class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer">
                            {{ __('Make Page Active (Publish)') }}
                        </label>
                    </div>

                    <div class="flex justify-end gap-3 border-t border-gray-200 dark:border-gray-700 pt-5">
                        <a href="{{ route('admin.pages.index') }}" class="px-5 py-2.5 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-semibold hover:bg-gray-300 dark:hover:bg-gray-600 transition">{{ __('Cancel') }}</a>
                        <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white rounded-lg text-sm font-semibold shadow-sm transition">
                            {{ __('Save Page') }}
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <x-media-manager />

    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            // 1. Media Manager Helper Function
            function openMediaManager(callback) {
                window.dispatchEvent(new CustomEvent('open-media-manager', {
                    detail: {
                        onSelect: callback
                    }
                }));
            }

            // 2. HTML Source Code Handler
            const htmlSourceHandler = function() {
                const htmlContainer = this.quill.container.querySelector('.ql-html-debugger');
                if (htmlContainer) {
                    this.quill.root.innerHTML = htmlContainer.value;
                    htmlContainer.remove();
                    this.quill.root.style.display = 'block';
                } else {
                    const currentHtml = this.quill.root.innerHTML;
                    const txtArea = document.createElement('textarea');
                    txtArea.className = 'ql-html-debugger';
                    txtArea.value = currentHtml;
                    
                    txtArea.style.width = '100%';
                    txtArea.style.height = this.quill.root.clientHeight + 'px';
                    txtArea.style.padding = '10px';
                    txtArea.style.boxSizing = 'border-box';
                    txtArea.style.fontSize = '16px';
                    txtArea.style.fontFamily = 'Consolas, Monaco, Courier New, monospace';
                    
                    this.quill.root.style.display = 'none';
                    this.quill.container.appendChild(txtArea);
                }
            };

            // 3. Single and Advanced Quill Setup
            const quill = new Quill('#editor-container', { 
                theme: 'snow',
                placeholder: '{{ __("Write page content here...") }}',
                modules: {
                    clipboard: { matchVisual: true },
                    toolbar: {
                        container: [
                            ['bold', 'italic', 'underline', 'strike'],
                            ['blockquote', 'code-block'],
                            [{ 'header': 1 }, { 'header': 2 }],
                            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                            [{ 'indent': '-1'}, { 'indent': '+1' }],
                            [{ 'size': ['small', false, 'large', 'huge'] }],
                            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                            [{ 'color': [] }, { 'background': [] }],
                            [{ 'font': [] }],
                            [{ 'align': [] }],
                            ['clean'],
                            ['link', 'image', 'video', 'code-html']
                        ],
                        handlers: {
                            'code-html': htmlSourceHandler,
                            'video': function() { // YouTube Video Handler
                                const url = prompt('{{ __("Please paste the YouTube video link (URL):") }}');
                                if (url) {
                                    let embedUrl = url;
                                    
                                    if (url.includes('youtube.com/watch?v=')) {
                                        const videoId = url.split('v=')[1].split('&')[0];
                                        embedUrl = `https://www.youtube.com/embed/${videoId}`;
                                    } else if (url.includes('youtu.be/')) {
                                        const videoId = url.split('youtu.be/')[1].split('?')[0];
                                        embedUrl = `https://www.youtube.com/embed/${videoId}`;
                                    }
                                    
                                    const range = quill.getSelection(true);
                                    quill.insertEmbed(range.index, 'video', embedUrl, 'user');
                                    quill.setSelection(range.index + 1);
                                }
                            },
                            'image': function() {
                                let range = quill.getSelection(true);
                                let insertIndex = range ? range.index : quill.getLength();
                                
                                openMediaManager((data) => {
                                    const imageUrl = typeof data === 'object' ? data.url : data;
                                    quill.insertEmbed(insertIndex, 'image', imageUrl, 'user');
                                    quill.setSelection(insertIndex + 1);
                                    if (typeof data === 'object') {
                                        setTimeout(() => {
                                            const img = document.querySelector('.ql-editor img[src="' + imageUrl + '"]');
                                            if (img) {
                                                if (data.title) img.setAttribute('title', data.title);
                                                if (data.alt) img.setAttribute('alt', data.alt);
                                            }
                                        }, 50);
                                    }
                                });
                            }
                        }
                    }
                }
            });

            // 4. Set Source Code Button Appearance
            const sourceBtn = document.querySelector('.ql-code-html');
            if (sourceBtn) {
                sourceBtn.innerHTML = '‹/›';
                sourceBtn.style.fontWeight = 'bold';
                sourceBtn.title = '{{ __("Source Code") }}';
            }

            // 5. Data Loading (Restore old validation data)
            @if(old('content'))
                setTimeout(function() {
                    document.querySelector('#editor-container .ql-editor').innerHTML = `{!! addslashes(old('content')) !!}`;
                }, 100);
            @endif

            // 6. Form Submit Management
            const form = document.getElementById('page-form');
            const hiddenContent = document.getElementById('hidden-content');

            if(form && hiddenContent) {
                form.addEventListener('submit', function(e) {
                    const htmlContainer = document.querySelector('.ql-html-debugger');
                    let editorContent;

                    // Get from textarea if source code mode is active, otherwise from normal editor
                    if (htmlContainer) {
                        editorContent = htmlContainer.value;
                    } else {
                        editorContent = document.querySelector('#editor-container .ql-editor').innerHTML;
                    }
                    
                    // Empty content check
                    if (editorContent.trim() === '<p><br></p>' || editorContent.trim() === '') {
                        hiddenContent.value = ''; 
                    } else {
                        hiddenContent.value = editorContent;
                    }
                });
            }

            // 7. Alpine.js Event Listener
            window.addEventListener('open-media-manager', (e) => {
                const managerElement = document.querySelector('[x-data*="mediaManager"]');
                
                if (managerElement && typeof Alpine !== 'undefined') {
                    const manager = Alpine.$data(managerElement);
                    if (manager && typeof manager.openManager === 'function') {
                        manager.openManager(e.detail.onSelect);
                    } else {
                        console.warn('{{ __("Media Manager element found but openManager function is not defined!") }}');
                    }
                } else {
                    console.warn('{!! __("No component with x-data=\\'mediaManager\\' attribute found on the page!") !!}');
                }
            });

            // 8. Image Editing (Double Click)
            quill.root.addEventListener('dblclick', (e) => {
                if (e.target.tagName === 'IMG') {
                    const img = e.target;
                    openMediaManager((updatedData) => {
                        img.setAttribute('src', updatedData.url);
                        img.setAttribute('title', updatedData.title);
                        img.setAttribute('alt', updatedData.alt);
                    });

                    const managerElement = document.querySelector('[x-data*="mediaManager"]');
                    if (managerElement && typeof Alpine !== 'undefined') {
                        const manager = Alpine.$data(managerElement);
                        if (manager) {
                            manager.selectedItem = { url: img.getAttribute('src') };
                            manager.imageTitle = img.getAttribute('title') || '';
                            manager.imageAlt = img.getAttribute('alt') || '';
                            manager.activeTab = 'title_alt';
                        }
                    }
                }
            });

        });
    </script>
</x-app-layout>