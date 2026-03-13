@props(['value'])

<label {{ $attributes }}>
    <span class="block font-medium text-sm text-gray-300">{{ $value ?? $slot }}</span>
</label>
