@include('base')
<form 
@if($entry->id == null)
    action="{{ route('create') }}" 
@else
    action="{{ route('edit') }}" 
@endif
method="post">
    @csrf
        <input type="hidden" name="id" id="id" value="{{ $entry->id }}">
        <input type="hidden" name="table" id="table" value="{{ $table }}">

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
        <form id="deleteForm" action="{{ route('delete') }}" method="POST">
        @csrf
            <input type="hidden" name="id" id="id" value="{{ $entry->id }}">
            <input type="hidden" name="table" id="table" value="{{ $table }}">
            <button type="button" id="deleteButton" class="delete_element_button">Dzēst</button>  
        </form>
        <div id="confirmDeleteWindow">
            <div id="confirmDeleteWindowBackground"></div>
            <div id="confirmDeleteWindowBox">
                <p>Vai dzēst šo objektu?</p>
                <div id="buttons">
                    <button id="confirmDeleteButton">Dzēst</button>
                    <button id="cancelButton">Atcelt</button>
                </div>
            </div>
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
<div class="spacer"></div>
@include('footer')