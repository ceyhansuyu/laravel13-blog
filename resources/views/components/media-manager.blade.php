<div x-data="mediaManager()" 
     x-on:open-media-manager.window="openManager($event.detail.onSelect)"
     x-show="isOpen" 
     class="fixed inset-0 z-50 overflow-y-auto" 
     aria-labelledby="modal-title" 
     role="dialog" 
     aria-modal="true"
     style="display: none;">
    
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-80 transition-opacity" 
         x-show="isOpen" 
         x-transition:enter="ease-out duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="ease-in duration-200" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0"
         @click="closeManager()"></div>

    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="relative bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-4xl sm:w-full flex flex-col h-[80vh]"
             x-show="isOpen" 
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
             x-transition:leave="ease-in duration-200" 
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             @click.stop>
            
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">
                    {{ __('Media Manager') }}
                </h3>
                <button @click="closeManager()" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="border-b border-gray-200 dark:border-gray-700">
                <nav class="-mb-px flex" aria-label="Tabs">
                    <button @click="activeTab = 'library'" :class="activeTab === 'library' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400 dark:border-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'" class="flex-1 py-4 px-1 text-center border-b-2 font-medium text-sm cursor-pointer">
                        {{ __('Library') }}
                    </button>
                    <button @click="activeTab = 'title_alt'" :class="activeTab === 'title_alt' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400 dark:border-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'" class="flex-1 py-4 px-1 text-center border-b-2 font-medium text-sm cursor-pointer" x-show="uploadedImage || selectedItem">
                        {{ __('Title & Alt') }}
                    </button>
                    <button @click="activeTab = 'crop'" :class="activeTab === 'crop' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400 dark:border-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'" class="flex-1 py-4 px-1 text-center border-b-2 font-medium text-sm cursor-pointer" x-show="uploadedImage">
                        {{ __('Edit Image') }}
                    </button>
                    <button @click="activeTab = 'upload'" :class="activeTab === 'upload' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400 dark:border-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'" class="flex-1 py-4 px-1 text-center border-b-2 font-medium text-sm cursor-pointer">
                        {{ __('Upload Image') }}
                    </button>
                </nav>
            </div>

            <div class="flex-1 p-6 overflow-y-auto bg-gray-50 dark:bg-gray-900">
                
                <div x-show="activeTab === 'upload'" class="h-full flex flex-col justify-center items-center">
                    <div class="w-full max-w-lg">
                        <label class="flex justify-center w-full h-64 px-4 transition bg-white dark:bg-gray-800 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-md appearance-none cursor-pointer hover:border-indigo-400 dark:hover:border-indigo-500 focus:outline-none"
                               @dragover.prevent="$el.classList.add('border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900/20')"
                               @dragleave.prevent="$el.classList.remove('border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900/20')"
                               @drop.prevent="handleDrop($event); $el.classList.remove('border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900/20')">
                            
                            <span class="flex items-center space-x-2" x-show="!isUploading">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                <span class="font-medium text-gray-600 dark:text-gray-400">
                                    {{ __('Drag & drop image or') }} <span class="text-indigo-600 dark:text-indigo-400 underline">{{ __('browse') }}</span>
                                </span>
                            </span>
                            
                            <span class="flex flex-col items-center space-y-3" x-show="isUploading" style="display: none;">
                                <svg class="animate-spin h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('Uploading') }} <span x-text="uploadProgress + '%'"></span></span>
                            </span>
                            
                            <input type="file" name="image" class="hidden" accept="image/*" @change="handleFileSelect" multiple>
                        </label>
                    </div>
                </div>

                <div x-show="activeTab === 'title_alt'" class="h-full flex flex-col gap-4">
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6 w-full max-w-4xl mx-auto flex flex-col md:flex-row gap-6">
                        
                        <div class="w-full md:w-1/3 flex flex-col items-center justify-center bg-gray-50 dark:bg-gray-900 rounded-lg p-2 border border-gray-200 dark:border-gray-700">
                            <template x-if="selectedItem">
                                <img :src="selectedItem.url" class="max-w-full max-h-64 object-contain rounded shadow-sm">
                            </template>
                            <template x-if="!selectedItem">
                                <div class="text-sm text-gray-500 dark:text-gray-400 text-center py-12 flex flex-col items-center">
                                    <svg class="w-12 h-12 mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    {{ __('Please select an image from the library') }}
                                </div>
                            </template>
                        </div>

                        <div class="w-full md:w-2/3 space-y-5">
                            <div class="mb-2">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                                    {{ __('Image Details') }}
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('You can enter Title and Alt tags before adding the selected image to the page (Optional).') }}
                                </p>
                            </div>

                            <div>
                                <label for="imageTitle" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    {{ __('Image Title') }} 
                                </label>
                                <input type="text" id="imageTitle" x-model="imageTitle" 
                                    class="w-full rounded-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-300 focus:ring-1 sm:text-sm p-2.5 border" 
                                    placeholder="{{ __('Title to appear on hover (Optional)...') }}">
                            </div>

                            <div>
                                <label for="imageAlt" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    {{ __('Alternative Text (Alt)') }}
                                </label>
                                <input type="text" id="imageAlt" x-model="imageAlt" 
                                    class="w-full rounded-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-300 focus:ring-1  sm:text-sm p-2.5 border" 
                                    placeholder="{{ __('Describe the image for search engines (Optional)...') }}">
                            </div>

                            <div class="pt-4 border-t border-gray-200 dark:border-gray-700 flex justify-end">
                                <button @click="insertSelected()" 
                                        class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-indigo-700 cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                    </svg>
                                    {{ __('Insert Image') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div x-show="activeTab === 'crop'" class="h-full flex flex-col gap-4">

                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-4">

                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-200">
                                {{ __('Crop Ratio') }}
                            </h3>

                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                {{ __('Select the desired ratio') }}
                            </span>
                        </div>

                        <div class="flex flex-wrap gap-2">

                            <button
                                @click="setAspectRatio(null)"
                                :class="aspectRatio === null
                                    ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/25'
                                    : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                                class="px-4 py-2 rounded-lg font-medium text-sm transition-all duration-200 hover:scale-105">
                                {{ __('Free') }}
                            </button>

                            <button
                                @click="setAspectRatio(1/1)"
                                :class="Math.abs(aspectRatio - (1/1)) < 0.01
                                    ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/25'
                                    : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                                class="px-4 py-2 rounded-lg font-medium text-sm transition-all duration-200 hover:scale-105">
                                1:1
                            </button>

                            <button
                                @click="setAspectRatio(4/3)"
                                :class="Math.abs(aspectRatio - (4/3)) < 0.01
                                    ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/25'
                                    : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                                class="px-4 py-2 rounded-lg font-medium text-sm transition-all duration-200 hover:scale-105">
                                4:3
                            </button>

                            <button
                                @click="setAspectRatio(16/9)"
                                :class="Math.abs(aspectRatio - (16/9)) < 0.01
                                    ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/25'
                                    : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                                class="px-4 py-2 rounded-lg font-medium text-sm transition-all duration-200 hover:scale-105">
                                16:9
                            </button>

                            <button
                                @click="setAspectRatio(21/9)"
                                :class="Math.abs(aspectRatio - (21/9)) < 0.01
                                    ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/25'
                                    : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                                class="px-4 py-2 rounded-lg font-medium text-sm transition-all duration-200 hover:scale-105">
                                21:9
                            </button>

                        </div>
                    </div>

                    <div
                        class="flex-1 min-h-[400px] rounded-2xl overflow-hidden border border-gray-200 dark:border-gray-700 bg-gradient-to-br from-gray-900 via-gray-800 to-black shadow-xl">

                        <div class="w-full h-full flex items-center justify-center p-6">

                            <img
                                x-show="uploadedImage"
                                :src="uploadedImage"
                                alt="Crop"
                                id="cropImage"
                                class="max-w-full max-h-full object-contain rounded-lg">

                        </div>

                    </div>

                    <div
                        class="flex items-center justify-center gap-2 text-sm text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-800 rounded-lg p-3 border border-gray-200 dark:border-gray-700">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-4 h-4"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01"/>
                        </svg>

                        <span>
                            {{ __('Drag the area') }} • {{ __('Resize from corners') }} • {{ __('Zoom with mouse wheel') }}
                        </span>

                    </div>

                </div>

                <div x-show="activeTab === 'library'">
                    <div x-show="isLoading" class="flex justify-center py-12">
                        <svg class="animate-spin h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                    
                    <div x-show="!isLoading && mediaItems.length === 0" class="text-center py-12 text-gray-500 dark:text-gray-400" style="display: none;">
                        {{ __('No images have been uploaded yet.') }}
                    </div>

                    <div x-show="!isLoading && mediaItems.length > 0" class="grid grid-cols-2 md:grid-cols-4 gap-4" style="display: none;">
                        <template x-for="item in mediaItems" :key="item.id">
                            <div class="relative group rounded-lg overflow-hidden bg-gray-200 dark:bg-gray-700 border-2 transition-all cursor-pointer"
                                 :class="selectedItem && selectedItem.id === item.id ? 'border-indigo-500 shadow-md ring-2 ring-indigo-500' : 'border-transparent hover:border-gray-300 dark:hover:border-gray-500'"
                                 @click="selectImage(item)">
                                
                                <div class="aspect-square overflow-hidden bg-gray-100 dark:bg-gray-800">
                                    <img :src="item.url" :alt="item.name" class="object-cover w-full h-full">
                                </div>
                                
                                <div class="absolute inset-0 bg-linear-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex flex-col justify-end p-3">
                                    <div class="text-white text-xs space-y-1">
                                        <p class="font-semibold truncate" :title="item.name" x-text="item.name"></p>
                                        <p class="text-gray-200" x-text="item.format"></p>
                                        <p class="text-gray-300" x-text="item.width + ' × ' + item.height + ' px'"></p>
                                        <p class="text-gray-300" x-text="formatFileSize(item.size)"></p>
                                    </div>
                                </div>
                                
                                <div x-show="selectedItem && selectedItem.id === item.id" class="absolute top-2 right-2 bg-indigo-500 text-white rounded-full p-1 shadow">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                
                                <button @click.stop="deleteMedia(item.id)" class="absolute bottom-2 right-2 bg-red-600 text-white rounded p-1.5 opacity-0 group-hover:opacity-100 transition-opacity shadow hover:bg-red-700">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
                
            </div>

            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex justify-end gap-3 bg-gray-50 dark:bg-gray-800">
                <button type="button" @click="closeManager()" class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    {{ __('Cancel') }}
                </button>
                <template x-if="activeTab === 'crop'">
                    <button type="button" 
                            @click="applyCrop()" 
                            :disabled="!uploadedImage"
                            :class="!uploadedImage ? 'opacity-50 cursor-not-allowed' : 'hover:bg-green-700 cursor-pointer'"
                            class="px-4 py-2 bg-green-600 border border-transparent rounded-md shadow-sm text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        {{ __('Crop & Save to Library') }}
                    </button>
                </template>
                <template x-if="activeTab !== 'crop'">
                    <button type="button" 
                            @click="insertSelected()" 
                            class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none">
                        {{ __('Insert') }}
                    </button>
                </template>
            </div>
        </div>
    </div>
</div>

<script>
    function mediaManager() {
        return {
            isOpen: false,
            activeTab: 'library',
            mediaItems: [],
            isLoading: false,
            isUploading: false,
            uploadProgress: 0,
            selectedItem: null,
            onSelectCallback: null,
            uploadedImage: null,
            uploadedFile: null,
            aspectRatio: null,
            cropper: null,
            
            imageTitle: '',
            imageAlt: '',
            
            formatFileSize(bytes) {
                if (bytes === 0) return '0 B';
                const k = 1024;
                const sizes = ['B', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return Math.round((bytes / Math.pow(k, i)) * 100) / 100 + ' ' + sizes[i];
            },
            
            init() {
                this.fetchMedia();
            },
            
            openManager(callback) {
                // Eğer callback gelmiyorsa, en azından bir boş fonksiyon ata ki hata vermesin
                this.onSelectCallback = typeof callback === 'function' ? callback : null;
                
                this.isOpen = true;
                this.activeTab = 'library';
                this.selectedItem = null;
                this.uploadedImage = null;
                this.uploadedFile = null;
                this.imageTitle = '';
                this.imageAlt = '';
                this.fetchMedia();
            },
            
            closeManager() {
                this.isOpen = false;
                this.selectedItem = null;
                this.uploadedImage = null;
                this.uploadedFile = null;
                this.imageTitle = '';
                this.imageAlt = '';
                if (this.cropper) {
                    this.cropper.destroy();
                    this.cropper = null;
                }
            },

            // Resmi seçince çalışacak ve sekmeyi Title/Alt tarafına geçirecek fonksiyon
            selectImage(item) {
                this.selectedItem = item;
                this.imageTitle = ''; // Her yeni seçimde kutuları temizle
                this.imageAlt = '';   // Her yeni seçimde kutuları temizle
                this.activeTab = 'title_alt';
            },
            
            // mediaManager fonksiyonu içerisindeki güncel fonksiyon:
            insertSelected() {
                console.log("Debug: Button clicked.");
                
                if (!this.selectedItem) {
                    alert("{{ __('Please select an image first.') }}");
                    return;
                }

                if (this.onSelectCallback) {
                    try {
                        console.log("Debug: Data before callback:", {
                            url: this.selectedItem.url,
                            title: this.imageTitle,
                            alt: this.imageAlt
                        });
                        
                        // Running the callback
                        this.onSelectCallback({
                            url: this.selectedItem.url,
                            title: this.imageTitle || '',
                            alt: this.imageAlt || ''
                        });
                        
                        console.log("Debug: Callback executed successfully.");
                        this.closeManager();
                    } catch (e) {
                        console.error("CRITICAL ERROR: Error during callback execution!", e);
                        alert("{{ __('An error occurred while adding the image. Check the console!') }}");
                    }
                } else {
                    console.error("CRITICAL ERROR: Callback is not defined!");
                }
            },
            
            async fetchMedia() {
                this.isLoading = true;
                try {
                    const response = await fetch('/media');
                    if (response.ok) {
                        this.mediaItems = await response.json();
                    }
                } catch (error) {
                    console.error('Error fetching media:', error);
                } finally {
                    this.isLoading = false;
                }
            },
            
            handleFileSelect(event) {
                const files = Array.from(event.target.files);
                if (files.length > 0) {
                    this.prepareForCrop(files[0]);
                }
            },
            
            handleDrop(event) {
                const files = Array.from(event.dataTransfer.files);
                if (files.length > 0 && files[0].type.startsWith('image/')) {
                    this.prepareForCrop(files[0]);
                }
            },
            
            prepareForCrop(file) {
                this.uploadedFile = file;
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.uploadedImage = e.target.result;
                    this.activeTab = 'crop';
                    this.aspectRatio = null;
                    
                    this.$nextTick(() => {
                        setTimeout(() => {
                            this.initCropper();
                        }, 100);
                    });
                };
                reader.readAsDataURL(file);
            },
            
            initCropper() {
                const img = document.getElementById('cropImage');
                if (!img || !window.Cropper) {
                    console.error('Cropper not ready');
                    return;
                }
                
                if (this.cropper) {
                    this.cropper.destroy();
                }
                
                this.cropper = new Cropper(img, {
                    aspectRatio: this.aspectRatio,
                    viewMode: 1,
                    autoCropArea: 1,
                    responsive: true,
                    restore: true,
                    guides: true,
                    center: true,
                    highlight: true,
                    cropBoxMovable: true,
                    cropBoxResizable: true,
                    toggleDragModeOnDblclick: true,
                    background: true,
                    modal: true,
                    zoomable: true,
                    wheelZoomRatio: 0.1,
                    minContainerWidth: 200,
                    minContainerHeight: 200,
                });
            },
            
            setAspectRatio(ratio) {
                this.aspectRatio = ratio;
                if (this.cropper) {
                    this.cropper.setAspectRatio(ratio || NaN);
                }
            },
            
            async applyCrop() {
                if (!this.cropper) {
                    alert('Lütfen resmi yükleyin');
                    return;
                }
                
                try {
                    this.isUploading = true;
                    this.uploadProgress = 0;
                    
                    const canvas = this.cropper.getCroppedCanvas({
                        maxWidth: 4096,
                        maxHeight: 4096,
                        fillColor: '#fff',
                        imageSmoothingEnabled: true,
                        imageSmoothingQuality: 'high'
                    });
                    
                    if (canvas.width > 1000) {
                        const scaledCanvas = document.createElement('canvas');
                        const scale = 1000 / canvas.width;
                        scaledCanvas.width = 1000;
                        scaledCanvas.height = canvas.height * scale;
                        
                        const ctx = scaledCanvas.getContext('2d');
                        ctx.drawImage(canvas, 0, 0, scaledCanvas.width, scaledCanvas.height);
                        
                        scaledCanvas.toBlob(
                            (blob) => this.uploadCroppedImage(blob, this.uploadedFile),
                            'image/jpeg',
                            0.95
                        );
                    } else {
                        canvas.toBlob(
                            (blob) => this.uploadCroppedImage(blob, this.uploadedFile),
                            'image/jpeg',
                            0.95
                        );
                    }
                } catch (error) {
                    console.error('Crop error:', error);
                    alert('Kırpma işleminde hata oluştu');
                    this.isUploading = false;
                }
            },
            
            async uploadCroppedImage(blob, originalFile) {
                const formData = new FormData();
                formData.append('image', blob, 'cropped-' + Date.now() + '.jpg');
                
                if (originalFile) {
                    formData.append('original_image', originalFile);
                }
                            
                try {
                    const progressInterval = setInterval(() => {
                        if (this.uploadProgress < 90) this.uploadProgress += 10;
                    }, 100);
                    
                    const tokenMeta = document.querySelector('meta[name="csrf-token"]');
                    const tokenInput = document.querySelector('input[name="_token"]');
                    const headers = { 'Accept': 'application/json' };
                    if (tokenMeta && tokenMeta.getAttribute('content')) {
                        headers['X-CSRF-TOKEN'] = tokenMeta.getAttribute('content');
                    } else if (tokenInput && tokenInput.value) {
                        headers['X-CSRF-TOKEN'] = tokenInput.value;
                    }

                    const response = await fetch('/media', {
                        method: 'POST',
                        body: formData,
                        headers,
                        credentials: 'same-origin'
                    });
                    
                    clearInterval(progressInterval);
                    this.uploadProgress = 100;
                    
                    if (response.ok) {
                        const result = await response.json();
                        this.mediaItems.unshift(result.media);
                        
                        if (this.cropper) {
                            this.cropper.destroy();
                            this.cropper = null;
                        }

                        this.uploadedImage = null;
                        this.uploadedFile = null;

                        // Yükleme sonrası resmi seç ve Title/Alt sekmesine git
                        this.selectImage(result.media);
                        
                        setTimeout(() => {
                            this.isUploading = false;
                            this.uploadProgress = 0;
                        }, 500);
                    } else {
                        let errMsg = '{{ __('Upload failed.') }}';
                        try {
                            const clone = response.clone();
                            const err = await clone.json();
                            errMsg = err.message || err.error || errMsg;
                        } catch (parseErr) {
                            try {
                                const cloneText = response.clone();
                                const txt = await cloneText.text();
                                if (txt) errMsg = txt;
                            } catch (txtErr) {}
                        }
                        alert(errMsg);
                        this.isUploading = false;
                        this.uploadProgress = 0;
                    }
                } catch (error) {
                    console.error('Upload error:', error);
                    alert('Yükleme sırasında hata oluştu');
                    this.isUploading = false;
                    this.uploadProgress = 0;
                }
            },
            
            async deleteMedia(id) {
                if (!confirm('{{ __('Are you sure you want to delete this image?') }}')) return;
                
                try {
                    const tokenMeta = document.querySelector('meta[name="csrf-token"]');
                    const tokenInput = document.querySelector('input[name="_token"]');
                    const headers = {};
                    if (tokenMeta && tokenMeta.getAttribute('content')) {
                        headers['X-CSRF-TOKEN'] = tokenMeta.getAttribute('content');
                    } else if (tokenInput && tokenInput.value) {
                        headers['X-CSRF-TOKEN'] = tokenInput.value;
                    }

                    const response = await fetch(`/media/${id}`, {
                        method: 'DELETE',
                        headers,
                        credentials: 'same-origin'
                    });
                    
                    if (response.ok) {
                        this.mediaItems = this.mediaItems.filter(item => item.id !== id);
                        if (this.selectedItem && this.selectedItem.id === id) {
                            this.selectedItem = null;
                        }
                    }
                } catch (error) {
                    console.error('Delete error:', error);
                }
            }
        }
    }
</script>