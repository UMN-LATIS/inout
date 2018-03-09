@extends('base')

@section('content')
    <div class="container">
        <div class="row">
            <users boardadmin="{{ $boardAdmin }}" :board="{{ $board }}"></users>
        </div>
    </div>
@endsection