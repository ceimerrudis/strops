@include("base")
<br>    
    @foreach($reportsByObject as $object)
        <div class="report_tables">
            <div class="report_button_container">
                <button class="collapsor" code="{{ $object['code'] }}">{{ $object['code'] }} - {{ $object['name'] }} -</button>
            
                <form class="create_report_button_form" action="{{ route('addReport') }}" method="get">
                    <input type="hidden" name="object" value="{{$object['id']}}">
                    <button class="create_report_btn" code="{{ $object['code'] }}">Pievienot jaunu atskaiti</button>
                </form>
            </div>
            <div id="{{ $object['code'] }}" class="content">
                <div class="table_container">
                    <table class="table columns_3">
                        <thead>
                            <tr>
                                <th>Progress</th>
                                <th>Datums</th>
                                <th>Rediģēt</th>
                            </tr>
                        </thead>
                        <tbody>   
                            @foreach($object['reports'] as $report)
                                <tr>
                                    <td>{{ $report['progress'] }}</td>
                                    <td>{{ $report['date'] }}</td>
                                    <td>
                                        <form action="{{ route('editReport') }}" method="get">
                                            <input value="{{ $report['id'] }}" type="hidden" name="id"> 
                                            <button type="submit" class="">
                                                Rediģēt
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endforeach
    <div  class="spacer"></div>
    <div  class="spacer"></div>
<script>
    $(document).ready(function() {
        $('.collapsor').click(function() {
            let code = $(this).attr('code');
            let html = $(this).html();

            if($("#"+code).css('display') !== 'none')
            {
                $("#"+code).hide();
                $(this).html(html.slice(0, -1) + '+');
            }
            else
            {
                $("#"+code).show();
                $(this).html(html.slice(0, -1) + '-');
            }
        });
    });
</script>
@include("footer")
