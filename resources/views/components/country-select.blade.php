@props(['selected' => '', 'name' => 'country'])

@php
    $countries = [
        'Southeast Asia' => [
            'BN'=>['🇧🇳','Brunei'],'KH'=>['🇰🇭','Cambodia'],
            'ID'=>['🇮🇩','Indonesia'],'LA'=>['🇱🇦','Laos'],
            'MY'=>['🇲🇾','Malaysia'],'MM'=>['🇲🇲','Myanmar'],
            'PH'=>['🇵🇭','Philippines'],'SG'=>['🇸🇬','Singapore'],
            'TH'=>['🇹🇭','Thailand'],'VN'=>['🇻🇳','Vietnam'],
        ],
        'East Asia' => [
            'CN'=>['🇨🇳','China'],'HK'=>['🇭🇰','Hong Kong'],
            'JP'=>['🇯🇵','Japan'],'KR'=>['🇰🇷','South Korea'],
            'TW'=>['🇹🇼','Taiwan'],'MN'=>['🇲🇳','Mongolia'],
        ],
        'South Asia' => [
            'BD'=>['🇧🇩','Bangladesh'],'IN'=>['🇮🇳','India'],
            'NP'=>['🇳🇵','Nepal'],'PK'=>['🇵🇰','Pakistan'],
            'LK'=>['🇱🇰','Sri Lanka'],'MV'=>['🇲🇻','Maldives'],
        ],
        'Middle East' => [
            'BH'=>['🇧🇭','Bahrain'],'EG'=>['🇪🇬','Egypt'],
            'IR'=>['🇮🇷','Iran'],'IQ'=>['🇮🇶','Iraq'],
            'JO'=>['🇯🇴','Jordan'],'KW'=>['🇰🇼','Kuwait'],
            'LB'=>['🇱🇧','Lebanon'],'OM'=>['🇴🇲','Oman'],
            'QA'=>['🇶🇦','Qatar'],'SA'=>['🇸🇦','Saudi Arabia'],
            'TR'=>['🇹🇷','Turkey'],'AE'=>['🇦🇪','UAE'],
            'YE'=>['🇾🇪','Yemen'],
        ],
        'Europe' => [
            'AT'=>['🇦🇹','Austria'],'BE'=>['🇧🇪','Belgium'],
            'BG'=>['🇧🇬','Bulgaria'],'HR'=>['🇭🇷','Croatia'],
            'CZ'=>['🇨🇿','Czech Republic'],'DK'=>['🇩🇰','Denmark'],
            'FI'=>['🇫🇮','Finland'],'FR'=>['🇫🇷','France'],
            'DE'=>['🇩🇪','Germany'],'GR'=>['🇬🇷','Greece'],
            'HU'=>['🇭🇺','Hungary'],'IE'=>['🇮🇪','Ireland'],
            'IT'=>['🇮🇹','Italy'],'NL'=>['🇳🇱','Netherlands'],
            'NO'=>['🇳🇴','Norway'],'PL'=>['🇵🇱','Poland'],
            'PT'=>['🇵🇹','Portugal'],'RO'=>['🇷🇴','Romania'],
            'RU'=>['🇷🇺','Russia'],'ES'=>['🇪🇸','Spain'],
            'SE'=>['🇸🇪','Sweden'],'CH'=>['🇨🇭','Switzerland'],
            'UA'=>['🇺🇦','Ukraine'],'GB'=>['🇬🇧','United Kingdom'],
        ],
        'Africa' => [
            'DZ'=>['🇩🇿','Algeria'],'AO'=>['🇦🇴','Angola'],
            'CM'=>['🇨🇲','Cameroon'],'ET'=>['🇪🇹','Ethiopia'],
            'GH'=>['🇬🇭','Ghana'],'KE'=>['🇰🇪','Kenya'],
            'MA'=>['🇲🇦','Morocco'],'NG'=>['🇳🇬','Nigeria'],
            'ZA'=>['🇿🇦','South Africa'],'TZ'=>['🇹🇿','Tanzania'],
            'TN'=>['🇹🇳','Tunisia'],'UG'=>['🇺🇬','Uganda'],
        ],
        'Americas' => [
            'AR'=>['🇦🇷','Argentina'],'BR'=>['🇧🇷','Brazil'],
            'CA'=>['🇨🇦','Canada'],'CL'=>['🇨🇱','Chile'],
            'CO'=>['🇨🇴','Colombia'],'MX'=>['🇲🇽','Mexico'],
            'PE'=>['🇵🇪','Peru'],'US'=>['🇺🇸','United States'],
            'VE'=>['🇻🇪','Venezuela'],
        ],
        'Oceania' => [
            'AU'=>['🇦🇺','Australia'],'FJ'=>['🇫🇯','Fiji'],
            'NZ'=>['🇳🇿','New Zealand'],
        ],
    ];
@endphp

<select
    name="{{ $name }}"
    {{ $attributes->merge(['class' => 'w-full bg-gray-800 border border-gray-700 rounded-xl px-3 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500']) }}>
    <option value="">🌍 Select your country</option>
    @foreach($countries as $region => $list)
    <optgroup label="{{ $region }}">
        @foreach($list as $code => [$flag, $name])
        <option value="{{ $code }}" {{ $selected === $code ? 'selected' : '' }}>
            {{ $flag }} {{ $name }}
        </option>
        @endforeach
    </optgroup>
    @endforeach
    <option value="other">🌐 Other</option>
</select>
