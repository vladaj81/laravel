@extends('layouts.appadmin')

@section('title')
    Edit slider
@endsection

@section('content')
    
    <div class="row grid-margin">
        <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
            <h4 class="card-title">Edit slider</h4>
        
                {!!Form::open(['action' => 'SliderController@updateslider', 'method' => 'POST', 'class' => 'cmxform', 'id' => 'commentForm', 'enctype' => 'multipart/form-data'])!!}
                
                {{ csrf_field() }}
                <div class="form-group">
                    {{Form::hidden('id', $slider->id)}}
                    {{Form::label('', 'Description one', ['for' => 'description one'])}}
                    {{Form::text('description_one', $slider->description1, ['id' => 'description one', 'class' => 'form-control', 'minlength' => '2'])}}
                </div>

                <div class="form-group">
                    {{Form::label('', 'Description two', ['for' => 'description two'])}}
                    {{Form::text('description_two', $slider->description2, ['id' => 'description two', 'class' => 'form-control', 'minlength' => '2'])}}
                </div>

                <div class="form-group">
                    {{Form::label('', 'Slider Image', ['for' => 'slider_image'])}}
                    {{Form::file('slider_image', ['id' => 'slider_image', 'class' => 'form-control'])}}
                </div>

                {{Form::submit('Update', ['class' => 'btn btn-primary'])}}

                {!!Form::close()!!}
            </div>
        </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{asset('backend/js/bt-maxLength.js')}}"></script>
@endsection