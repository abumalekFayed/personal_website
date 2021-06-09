@props(['rows' => [], 'headers'])


<table class="table border">
    <thead>
        <tr>
            @foreach ($headers as $header)
                <th scope="col">{{ __('messages.' . $header) }}</th>
            @endforeach
        </tr>
    </thead>

    <tbody>
        @foreach ($rows as $row)
            <tr>
                @foreach ($headers as $header)
                    <td scope="col">{{ asset($row[$header]) ? $row[$header] : '' }}</th>
                @endforeach
            </tr>
        @endforeach
        {{ $slot }}
    </tbody>
</table>
