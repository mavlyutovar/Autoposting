@extends('layouts.app')
<script src="{{ asset('js/app.js') }}"></script>
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Companies</div>

                    <prop-component :data="{{ json_encode($data) }}"></prop-component>
                    <div class="panel-body table-responsive">
                        <example-component></example-component>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection
