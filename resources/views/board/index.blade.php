@extends('master')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <users :board="'{{ $board }}'"></users>
            </div>
        </div>
    </div>
@endsection