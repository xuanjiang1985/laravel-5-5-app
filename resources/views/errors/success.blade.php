@if (Session::has('status'))
    <div class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert">x</button>
        <span class="fa fa-check"></span>
        {{ Session::get('status') }}
    </div>
@endif