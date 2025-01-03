@include('base')
<form 
@if($entry->id == null)
    action="{{ route('create', [$table]) }}" 
@else
    action="{{ route('edit', [$table]) }}" 
@endif
method="post">
    @csrf
        <input type="hidden" name="id" id="id" value="{{ $entry->id }}">

        @include('adminModule.forms.'.$viewName)

        <button type="submit" class="create_element_button">
            @if($entry->id == null)
                Izveidot
            @else
                Mainīt
            @endif
        </button>
    </form>

    @if($entry->id != null)
        <form id="deleteForm" action="{{ route('delete', [$table]) }}" method="POST">
        @csrf
            <input type="hidden" name="id" id="id" value="{{ $entry->id }}">
            <button type="button" id="deleteButton" class="delete_element_button">DzēstTODO double  check</button>  
        </form>
        <div id="confirmDeleteWindow">
            <button id="confirmDeleteButton">Dzēst</button>
            <button id="cancelButton">Atcelt</button>
        </div>
        <script>
            $(document).ready(function() {
                $("#deleteButton").on("click", function(){
                    $("#confirmDeleteWindow").show();
                });
                $("#confirmDeleteButton").on("click", function(){
                    $("#deleteForm").submit();
                    $("#confirmDeleteWindow").hide();
                });
                $("#cancelButton").on("click", function(){
                    $("#confirmDeleteWindow").hide();
                });
            });
        </script>
    @endif

@include('footer')