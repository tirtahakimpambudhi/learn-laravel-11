<x-base>
    <x-slot:title>
        Demo View Page
    </x-slot:title>
    @if(!empty($data))
        <h1>Path : {{$data['path'] ?? "/"}}</h1>
        <hr>
        <p>Method : {{$data['method'] ?? "GET"}}</p>
        <p>Full Url : {{$data['fullUrl'] ?? "http://localhost/"}}</p>
        <p>IP : {{$data['ip'] ?? "127.0.0.1"}}</p>
        <p>User Agent : {{$data['userAgent'] ?? "Symfony"}}</p>
    @endif
</x-base>
