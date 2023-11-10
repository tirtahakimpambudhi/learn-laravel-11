<x-base>
    <x-slot:title>
        400 - Bad Request
    </x-slot:title>
    <h1> Bad Request </h1>
    @if(!empty($exception))
        <p> Message: {{ $exception->getMessage() }} </p>
    @endif
</x-base>
