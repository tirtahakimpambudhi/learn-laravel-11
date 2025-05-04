<x-base>
    <x-slot:title>
        Example Disable Blade Syntax
    </x-slot:title>
    <h5>Example Blade Syntax Without Verbatim: <span> @@{{ $testing }} </span></h5>
    @verbatim
        @verbatim
            <h5>Example Blade Syntax With Verbatim: <span> {{ $testing }} </span></h5>
        @endverbatim
    @endverbatim
</x-base>
