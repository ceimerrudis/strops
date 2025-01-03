@include('base')
<div class="table_container">
    <table class="{{'table columns_'.(count($headers)+1)}}">

        <tr>
            @foreach($headers as $header)
                <th>
                    {{$header}}
                </th>
            @endforeach
            <th>Rediģēt</th>
        </tr>
                
        @php
            use App\Enums\EntryTypes;
        @endphp
        @foreach($allEntryData as $entry)
            <tr>
                @include("adminModule.lists.".$viewName)
                
                <td>
                    <a method="GET" href="{{ route('viewEdit', ['table' => $table, 'id' => $entry->id]) }}">
                    @if($table == EntryTypes::ERROR->value)
                        Apskatīt
                    @else
                        Rediģēt
                    @endif
                    </a>
                </td>
            </tr>
        @endforeach


    </table>
</div>
<a href="{{route('viewcreate', ['table' => $table])}}" class="create_new_btn">Izveidot jaunu {{$name}}</a>
<div style="color: white; height:100px"> _</div>

@include('footer')