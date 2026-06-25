@if (session('toast'))
    <div x-data="{ show: true }"
         x-init="setTimeout(() => show = false, {{ $siteSettings['toast_duration'] ?? 3000 }})"
         x-show="show"
         x-cloak
         /* Tailwind v4'ün yeni CSS transition motoruna tam uyumlu Alpine geçişleri */
         x-transition:enter="transition duration-300 ease-out"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition duration-300 ease-in"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         /* v4'te merkeze hizalamanın en güvenli yolu inset-x-0 ve mx-auto ikilisidir */
         class="fixed top-5 inset-x-0 mx-auto z-50 flex w-[calc(100%-2rem)] sm:w-max max-w-md items-center justify-between gap-4 bg-emerald-600 text-white px-5 py-3 rounded-xl shadow-xl border border-emerald-500"
         role="alert">

        <span class="font-semibold">{{ session('toast') }}</span>

        <button @click="show = false" class="ml-4  -mt-1 text-xl font-bold leading-none text-white hover:text-gray-200 cursor-pointer">
            &times;
        </button>
    </div>
@endif

@if (session('error'))
    <div x-data="{ show: true }"
         x-init="setTimeout(() => show = false, {{ $siteSettings['toast_duration'] ?? 3000 }})"
         x-show="show"
         x-cloak
         /* Tailwind v4'ün yeni CSS transition motoruna tam uyumlu Alpine geçişleri */
         x-transition:enter="transition duration-300 ease-out"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition duration-300 ease-in"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         /* v4'te merkeze hizalamanın en güvenli yolu inset-x-0 ve mx-auto ikilisidir */
         class="fixed top-5 inset-x-0 mx-auto z-50 flex w-[calc(100%-2rem)] sm:w-max max-w-md items-center justify-between bg-rose-700 text-white px-6 py-3 rounded-lg shadow-xl border border-rose-500"
         role="alert">

        <span class="font-semibold">{{ session('error') }}</span>

        <button @click="show = false" class="ml-4  -mt-1 text-xl font-bold leading-none text-white hover:text-gray-200 cursor-pointer">
            &times;
        </button>
    </div>
@endif