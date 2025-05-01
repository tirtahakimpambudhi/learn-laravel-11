<x-base>
    <x-slot:title>
        Class Conditional
    </x-slot:title>

    <h4>The Conditional Style Example : <span style="{{ $color ? 'color: ' . $color . ';' : 'color: #252525;' }}"> Color </span></h4>

    {{--  The Challenges  --}}
    <button @class([
    'bg-sky-500 hover:bg-sky-700' => $isPrimary && !$isDisabled,
    'bg-gray-500 hover:bg-gray-700' => $isDisabled && !$isPrimary
])>Click Me</button>
</x-base>
