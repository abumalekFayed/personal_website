@props(['data'=>[]])
<table class="v-table">
    <tbody>
        @foreach ($data as $key => $value)
            <tr>
                <th>{{ $key }}</th>
                <td>{{ $value }}</td>
            </tr>
        @endforeach
        {{ $slot }}
    </tbody>
</table>
