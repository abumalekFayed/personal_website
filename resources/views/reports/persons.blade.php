@extends('reports.layout')
@section('content')


    <x-tables.vtable :data="[]">
        @if ($options['committee_filter'])
            <tr>
                <th>المنطقة</th>
                <td>{{ $options->committee_filter }}</td>
            </tr>
        @endif

        @if ($options['family_filter'])
            <tr>
                <th>القبيلة</th>
                <td>{{ $options->family_filter }}</td>
            </tr>
        @endif


        @if ($options['key'])
            <tr>
                <th>المفتاح</th>
                <td>{{ $options->key }}</td>
            </tr>
        @endif

        @if ($options['gender'])
            <tr>
                <th>النوع</th>
                <td>{{ $options->gender }}</td>
            </tr>
        @endif
        @if ($options['search'])
            <tr>
                <th>مفتاح البحث</th>
                <td>{{ $options->search }}</td>
            </tr>
        @endif

        <tr>
            <th>عدد الناخبين</th>
            <td>{{ $data->count() }}</td>
        </tr>
    </x-tables.vtable>



    <x-tables.ltable :headers="$columns">

        @foreach ($data as $d)
            <tr>

                @foreach ($columns as $column)
                    @if (isset($d->{$column}) && gettype($d->{$column}) != 'object')

                        @if ($column == 'done')
                            <td>{{ $d->{$column} == 1 ? 'تم التصويت' : 'لم يتم التصويت' }}</td>
                        @else
                            <td>{{ $d->{$column} }}</td>
                        @endif

                    @elseif ($column == 'keys')
                        <td>
                            {{ $d->kyes ? $d->keys->count() : 0 }}
                        </td>
                    @elseif ($column == 'keys_info')
                        <td>
                            <ol>
                                @foreach ($d->keys as $key)
                                    <li>
                                        {{ $key->name }}
                                    </li>
                                @endforeach
                            </ol>
                        </td>

                    @else
                        <td>{{ $d->{$column} }}</td>
                    @endif
                @endforeach
            </tr>
        @endforeach

    </x-tables.ltable>
@endsection
