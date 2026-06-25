<x-layouts.posts :title="__('Edit Post') . ' - ' . $post->title" :includeQuill="true">
    <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 p-6 md:p-8 rounded-lg shadow-md dark:shadow-2xl dark:shadow-gray-950">
        @if($errors->any())
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-500/30 text-red-600 dark:text-red-400 px-4 py-3 rounded-lg mb-6">
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="post-form" action="{{ route('posts.update', $post) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Post Title') }}</label>
                <input type="text" id="title" name="title" value="{{ old('title', $post->title) }}" required 
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-md focus:ring-blue-500 focus:border-blue-500 outline-none transition placeholder-gray-400 dark:placeholder-gray-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Category') }}</label>
                <select name="category_id" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-blue-500 focus:border-blue-500 outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition">
                    <option value="">{{ __('Select Category (Optional)') }}</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $post->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Content') }}</label>
                <div id="editor"></div>
                <textarea name="content" id="content" style="display: none;"></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Status') }}</label>
                <select name="status" required 
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-blue-500 focus:border-blue-500 outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition">
                    <option value="draft" {{ old('status', $post->status) == 'draft' ? 'selected' : '' }}>{{ __('Draft') }}</option>
                    <option value="publish" {{ old('status', $post->status) == 'publish' ? 'selected' : '' }}>{{ __('Publish') }}</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Featured Post') }} 📌</label>
                <select name="is_featured" required 
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-blue-500 focus:border-blue-500 outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition">
                    <option value="normal" {{ old('status', $post->is_featured) == 'normal' ? 'selected' : '' }}>{{ __('Normal Post') }}</option>
                    <option value="featured" {{ old('status', $post->is_featured) == 'featured' ? 'selected' : '' }}>{{ __('Featured') }}</option>
                </select>
            </div>    

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Meta Description</label>
                <div class="flex gap-2">
                    <textarea id="meta_description_input" rows="3" name="meta_description" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-blue-500 focus:border-blue-500 outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition">{{ old('meta_description', $post->meta_description) }}</textarea>
                    
                    <button type="button" id="btn-generate-seo" class="bg-gray-200 hover:bg-gray-300 text-gray-600 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200 font-semibold py-1 px-3 rounded-md transition duration-200 shadow-sm dark:shadow-md">
                        {{ __('Generate') }} ✨
                    </button>
                </div>
            </div>




            <div class="pt-2 flex justify-end">
                <button type="submit" class="flex items-center gap-2 cursor-pointer bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-700 dark:hover:bg-indigo-800 text-white font-semibold py-2 px-6 rounded-md transition duration-200 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-up" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M3.5 6a.5.5 0 0 0-.5.5v8a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5v-8a.5.5 0 0 0-.5-.5h-2a.5.5 0 0 1 0-1h2A1.5 1.5 0 0 1 14 6.5v8a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-8A1.5 1.5 0 0 1 3.5 5h2a.5.5 0 0 1 0 1z"/>
                        <path fill-rule="evenodd" d="M7.646.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 1.707V10.5a.5.5 0 0 1-1 0V1.707L5.354 3.854a.5.5 0 1 1-.708-.708z"/>
                    </svg>
                    <span>{{ __('Update Post') }}</span>
                </button>
            </div>
        </form>
    </div>

    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>
        // 1. Medya Yöneticisi Yardımcı Fonksiyonu
        function openMediaManager(callback) {
            console.log('Custom Quill media manager opening');
            window.dispatchEvent(new CustomEvent('open-media-manager', {
                detail: {
                    onSelect: callback
                }
            }));
        }

        // 2. HTML Kaynak Kodu İşleyicisi (Handler)
        const htmlSourceHandler = function() {
            const htmlContainer = this.quill.container.querySelector('.ql-html-debugger');
            if (htmlContainer) {
                // Eğer kaynak kodu alanı zaten açıksa, HTML'i geri Quill'e yükle ve alanı kapat
                this.quill.root.innerHTML = htmlContainer.value;
                htmlContainer.remove();
                this.quill.root.style.display = 'block';
            } else {
                // Açık değilse, mevcut HTML'i al ve bir textarea içinde göster
                const currentHtml = this.quill.root.innerHTML;
                const txtArea = document.createElement('textarea');
                txtArea.className = 'ql-html-debugger';
                txtArea.value = currentHtml;
                
                // Basit bir stillendirme (editörün içine tam oturması için)
                txtArea.style.width = '100%';
                txtArea.style.height = this.quill.root.clientHeight + 'px';
                txtArea.style.padding = '10px';
                txtArea.style.boxSizing = 'border-box';

                
                txtArea.style.fontSize = '16px'; // Yazıyı bir tık büyüttük (İstediğin gibi 15px veya 18px yapabilirsin)
                txtArea.style.fontFamily = 'Consolas, Monaco, Courier New, monospace'; // Kodların daha nizami görünmesini sağlar
                
                this.quill.root.style.display = 'none';
                this.quill.container.appendChild(txtArea);
            }
        };

        // 3. Tek ve Birleştirilmiş Quill Kurulumu
        const quill = new Quill('#editor', {
            theme: 'snow',
            placeholder: '{{ __('Write your content...') }}',
            modules: {
                clipboard: {
                    matchVisual: true
                },
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
                        ['link', 'image', 'video', 'code-html'] // 'video' eklendi
                    ],
                    handlers: {
                        // Kaynak kodu butonu tetikleyicisi
                        'code-html': htmlSourceHandler,
                        
                        // YouTube Video İşleyicisi
                        'video': function() {
                            const url = prompt('Lütfen YouTube video bağlantısını (URL) yapıştırın:');
                            if (url) {
                                let embedUrl = url;
                                
                                // Standart YouTube linklerini (watch veya youtu.be) iframe için embed formatına çevir
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

                        // Özel Medya Yöneticisi / Resim tetikleyicisi
                        image: function() {
                            const range = quill.getSelection(true);
                            if (!range) return;

                            openMediaManager((data) => {
                                const imageUrl = typeof data === 'object' ? data.url : data;

                                // 1. Resmi temiz bir şekilde ekle
                                quill.insertEmbed(range.index, 'image', imageUrl, 'user');
                                
                                // 2. İmleci bir ileri kaydır
                                quill.setSelection(range.index + 1);

                                // 3. Title ve alt bilgilerini ekle
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

        // 4. Kaynak Kodu Butonunun Görsel Ayarı
        const sourceBtn = document.querySelector('.ql-code-html');
        if (sourceBtn) {
            sourceBtn.innerHTML = '‹/›';
            sourceBtn.style.fontWeight = 'bold';
            sourceBtn.title = '{{ __('Source Code') }}';
        }

        // 5. Veri Yükleme Alanı (Laravel Blade Çıktıları)
        setTimeout(function() {
            document.querySelector('#editor .ql-editor').innerHTML = `{!! addslashes($post->content) !!}`;
        }, 100);

        @if(old('content'))
            setTimeout(function() {
                document.querySelector('#editor .ql-editor').innerHTML = `{!! addslashes(old('content')) !!}`;
            }, 100);
        @endif

        // 6. Form Submit Yönetimi (BURASI DÜZELTİLDİ 🛠️)
        document.getElementById('post-form').addEventListener('submit', function(e) {
            const htmlContainer = document.querySelector('.ql-html-debugger');
            let editorContent;

            // Eğer kullanıcı HTML görünümündeyken formu gönderirse veriyi textarea'dan çekiyoruz
            if (htmlContainer) {
                editorContent = htmlContainer.value;
            } else {
                // Normal görünümdeyse her zamanki gibi Quill editöründen çekiyoruz
                editorContent = document.querySelector('#editor .ql-editor').innerHTML;
            }
            
            const contentInput = document.querySelector('#content');
            
            if (editorContent.trim() === '<p><br></p>' || editorContent.trim() === '') {
                e.preventDefault();
                alert('{{ __('Please write your content...') }}');
                return false;
            }
            
            contentInput.value = editorContent;
        });

        // 7. Alpine.js Event Listener Köprüsü
        window.addEventListener('open-media-manager', (e) => {
            const managerElement = document.querySelector('[x-data="mediaManager()"]');
            if (managerElement) {
                const manager = Alpine.$data(managerElement);
                manager.openManager(e.detail.onSelect);
            }
        });

        // 8. Resme Çift Tıklayınca Düzenleme Modunu Açma (Double Click)
        quill.root.addEventListener('dblclick', (e) => {
            if (e.target.tagName === 'IMG') {
                const img = e.target;
                
                const currentData = {
                    url: img.getAttribute('src'),
                    title: img.getAttribute('title') || '',
                    alt: img.getAttribute('alt') || ''
                };

                openMediaManager((updatedData) => {
                    img.setAttribute('src', updatedData.url);
                    img.setAttribute('title', updatedData.title);
                    img.setAttribute('alt', updatedData.alt);
                });

                const managerElement = document.querySelector('[x-data="mediaManager()"]');
                const manager = Alpine.$data(managerElement);
                
                manager.selectedItem = { url: currentData.url };
                manager.imageTitle = currentData.title;
                manager.imageAlt = currentData.alt;
                manager.activeTab = 'title_alt';
            }
        });



        // Seo Meta Description
        document.getElementById('btn-generate-seo').addEventListener('click', async function() {
            // Kaynak kodu modu açıksa oradan, değilse editörden al
            const htmlContainer = document.querySelector('.ql-html-debugger');
            const content = htmlContainer ? htmlContainer.value : quill.root.innerHTML;
            
            const btn = this;
            const originalText = btn.innerHTML;

            btn.innerText = '{{ __('Processing...') }}';
            btn.disabled = true;
            btn.classList.add('opacity-50', 'cursor-not-allowed');

            try {
                const response = await fetch('{{ route("admin.blog.generate-description") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ content: content })
                });

                if (!response.ok) throw new Error('{{ __('Server error.') }}');
                
                const data = await response.json();
                document.getElementById('meta_description_input').value = data.description;
            } catch (error) {
            console.error('{{ __('Error:') }}', error);
            alert('{{ __('An error occurred while generating the meta description.') }}');
            } finally {
                btn.innerHTML = originalText;
                btn.disabled = false;
                btn.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        });

    </script>
</x-layouts.posts>

<x-media-manager />