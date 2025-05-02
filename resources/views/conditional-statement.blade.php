<x-base>
    <x-slot:title>
        Conditional Statement
    </x-slot:title>
    @if($ifQuery)
        <p>If Condition True</p>
    @else
        <p>If Condition False</p>
    @endif

    @unless($unlessQuery)
        <p>Unless Condition False</p>
    @endunless

    @unless(!$unlessQuery)
        <p>Unless Condition True</p>
    @endunless

    @switch($switchQuery)
        @case('A'):
            <p>Your Case is A value</p>
            @break
        @case('B'):
            <p>Your Case is B value</p>
            @break
        @default
            <p>Your Case is other A and B</p>
    @endswitch
</x-base>
