<x-base>
    <x-slot:title>
        Loop Statement
    </x-slot:title>

    @isset($users)
    {{--   Loop With Foreach     --}}
        <ul>
            @foreach($users as $user)
                <li>
                    <h6>No.{{ $loop->iteration }}</h6>
                    <p>ID : {{ $user['_id'] }}</p>
                    <p>Name : {{ $user['name'] }}</p>
                    <p>Email : {{ $user['email'] }}</p>
                </li>
            @endforeach
        </ul>
    {{--   Loop With Forelse   --}}
        <br>
        <ul>
            @forelse($orders as $order)
                <li>
                    <p>ID : {{ $order['_id'] }}</p>
                    <p>Name : {{ $order['name'] }}</p>
                </li>
            @empty
                <li>There are no list orders</li>
            @endforelse
        </ul>
    @endisset
    <br>
    @isset($totalItems)
        {{--    Loop with For    --}}
        <ul>
            @for($i = 0;$i < $totalItems;$i++)
                <li>
                    <p>ID : {{ Illuminate\Support\Str::ulid() }}</p>
                    <p>Name : {{ fake()->country }}</p>
                </li>
            @endfor
        </ul>
        <br>
        @php $counter = 0; @endphp
        <ul>
            @while($counter < $totalItems)
                <li>
                    <p>ID : {{ Illuminate\Support\Str::ulid() }}</p>
                    <p>Name : {{ fake()->colorName() }}</p>
                </li>
                @php $counter++; @endphp
            @endwhile
        </ul>
    @endisset
</x-base>
