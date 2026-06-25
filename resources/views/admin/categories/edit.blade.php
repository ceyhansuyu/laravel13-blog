<x-app-layout :title="__('Edit Category')">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Category') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 sm:p-8">

                    <form method="post" action="{{ route('admin.categories.update', $category) }}" class="space-y-6 max-w-xl">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="name" :value="__('Category Name')" />
                            <x-text-input
                                id="name"
                                name="name"
                                type="text"
                                class="mt-1 block w-full"
                                :value="old('name', $category->name)"
                                required
                                autofocus
                            />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="slug" :value="__('Slug (automatic)')" />
                            <x-text-input
                                id="slug"
                                type="text"
                                class="mt-1 block w-full opacity-60 cursor-not-allowed"
                                :value="$category->slug"
                                disabled
                            />
                        </div>

                        <div class="flex gap-3 pt-2">
                            <x-primary-button class="cursor-pointer">
                                {{ __('Update') }}
                            </x-primary-button>
                            <a href="{{ route('admin.categories.index') }}"
                               class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-md font-semibold text-sm hover:bg-gray-300 dark:hover:bg-gray-600 transition cursor-pointer">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>