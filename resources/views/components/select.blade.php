@props(['disabled' => false])

<select {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'block w-full px-4 py-2 mt-1 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:ring-opacity-50 dark:focus:ring-opacity-30 transition ease-in-out duration-150 disabled:opacity-50 appearance-none cursor-pointer']) !!}>
    {{ $slot }}
</select>
