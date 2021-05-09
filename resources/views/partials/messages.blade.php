<div style="right: 20px; bottom: 20px" class="messages position-fixed col-sm-4">
    @if ($message = Session::get('success'))
    <div class="alert alert-success alert-block fade hidden flash-message">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{{ $message }}</strong>
    </div>
    @endif

    @if ($message = Session::get('warning'))
    <div class="alert alert-warning alert-block fade hidden flash-message">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{{ $message }}</strong>
    </div>
    @endif

    @if ($data = Session::get('deleted'))
    <div class="alert alert-danger alert-block fade hidden flash-message">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{{ $data['message'] }}</strong>
        <button class="btn btn-default btn-outline-default" onclick="event.preventDefault(); document.getElementById('restore-form').submit()" >Rückgängig machen</a>
        <form id="restore-form" action="{{route("callcenter.restore", ["id" => $data['id']])}}" method="POST"
            style="display: none;">
            @csrf
        </form>
    </div>
    @endif

    @if($errors->any())
    @foreach ($errors->all() as $error)
    <div class="alert alert-danger alert-block fade hidden flash-message">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{{ $error }}</strong>
    </div>
    @endforeach
    @endif
</div>