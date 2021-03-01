@extends('layouts.appadmin')

@section('title')
    Add slider
@endsection

@section('content')
    
    <div class="row grid-margin">
        <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
            <h4 class="card-title">Create slider</h4>
            @if (Session::has('status'))
            <div class="alert alert-success">
                {{Session::get('status')}}
            </div>
            @endif
                {!!Form::open(['action' => 'SliderController@saveslider', 'method' => 'POST', 'class' => 'cmxform', 'id' => 'commentForm', 'enctype' => 'multipart/form-data'])!!}
                
                {{ csrf_field() }}
                <div class="form-group">
                    {{Form::label('', 'Description one', ['for' => 'description one'])}}
                    {{Form::text('description_one', '', ['id' => 'description one', 'class' => 'form-control', 'minlength' => '2'])}}
                </div>

                <div class="form-group">
                    {{Form::label('', 'Description two', ['for' => 'description two'])}}
                    {{Form::text('description_two', '', ['id' => 'description two', 'class' => 'form-control', 'minlength' => '2'])}}
                </div>

                <div class="form-group">
                    {{Form::label('', 'Slider Image', ['for' => 'slider_image'])}}
                    {{Form::file('slider_image', ['id' => 'slider_image', 'class' => 'form-control'])}}
                </div>

                {{Form::submit('Save', ['class' => 'btn btn-primary'])}}

                {!!Form::close()!!}
            </div>
        </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{asset('backend/js/bt-maxLength.js')}}"></script>
@endsection