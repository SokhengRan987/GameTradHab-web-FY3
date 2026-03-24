@php
    use App\Helpers\Countries;
    $countries = Countries::all();
    $selected  = $selected ?? '';
@endphp

<select {{ $attributes->merge(['class' => 'w-full bg-gray-800 border border-gray-700 rounded-xl px-3 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500']) }}>
    <option value="">🌍 Select your country</option>
    @foreach($countries as $code => $country)
    <option value="{{ $code }}" {{ $selected === $code ? 'selected' : '' }}>
        {{ $country['flag'] }} {{ $country['name'] }}
    </option>
    @endforeach
</select>
