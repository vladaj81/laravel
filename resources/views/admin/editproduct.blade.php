@extends('layouts.appadmin')

@section('title')
    Edit product
@endsection

@section('content')
    
    <div class="row grid-margin">
        <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
            <h4 class="card-title">Edit product</h4>
                @if (Session::has('status'))
                <div class="alert alert-success">
                        {{Session::get('status')}}
                    </div>
                @endif
                @if (Session::has('status1'))
                    <div class="alert alert-danger">
                        {{Session::get('status1')}}
                    </div>
                @endif
                @if (Session::has('status2'))
                <div class="alert alert-success">
                        {{Session::get('status')}}
                    </div>
                @endif
                {!!Form::open(['action' => 'ProductController@updateproduct', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'class' => 'cmxform', 'id' => 'commentForm'])!!}
                
                {{ csrf_field() }}
                <div class="form-group">
                    {{Form::hidden('id', $product->id)}}
                    {{Form::label('', 'Product Name', ['for' => 'product_name'])}}
                    {{Form::text('product_name', $product->product_name, ['id' => 'product_name', 'class' => 'form-control', 'minlength' => '2'])}}
                </div>

                <div class="form-group">
                    {{Form::label('', 'Product Price', ['for' => 'product_price'])}}
                    {{Form::number('product_price', $product->product_price, ['id' => 'product_price', 'class' => 'form-control'])}}
                </div>
                
                <div class="form-group">
                    {{Form::label('', 'Product Category', ['for' => 'product_category'])}}
                    {{Form::select('product_category', $categories, $product->product_category, ['id' => 'product_category', 'class' => 'form-control'])}}
                </div>
                
                <div class="form-group">
                    {{Form::label('', 'Product Image', ['for' => 'product_image'])}}
                    {{Form::file('product_image', ['id' => 'product_image', 'class' => 'form-control'])}}
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