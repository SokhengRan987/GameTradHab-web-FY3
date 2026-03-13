@props(['disabled' => false])

<input {{ $attributes->merge([
    'class' =>
    'border-gray-700 bg-gray-800 text-white
    focus:border-indigo-500 focus:ring-indigo-500
    rounded-md shadow-sm'
    ]) }}>
