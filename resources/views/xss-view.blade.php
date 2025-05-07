<x-base>
    <x-slot:title>
        XSS Example
    </x-slot:title>
    @if(!empty($tag))
        {{--  For disable html encode from laravel with sign  {!!  !!} --}}
        {{--  please be careful using it --}}
        {!! $tag !!}
    @else
        <h1> Empty Query Params Tag </h1>
    @endif

</x-base>
