<x-base>
    <x-slot:title>
        500 - Internal Server Error
    </x-slot:title>
    <h1> Internal Server Error  </h1>
    @if(!empty($exception))
        <p> Message: {{ $exception->getMessage() }} </p>
    @endif
</x-base>
