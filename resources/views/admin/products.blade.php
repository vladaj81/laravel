@extends('layouts.appadmin')

@section('title')
    Products
@endsection

@section('content')
{{Form::hidden('', $increment = 1)}}
<div class="card">
    <div class="card-body">
      <h4 class="card-title">Products</h4>
      @if (Session::has('status'))
      <div class="alert alert-success">
          {{Session::get('status')}}
      </div>
     @endif
      <div class="row">
        <div class="col-12">
          <div class="table-responsive">
            <table id="order-listing" class="table">
              <thead>
                <tr>
                    <th>Order #</th>
                    <th>Image</th>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($productsi as $product)

                <tr>
                    <td>{{$increment}}</td>
                    <td><img src="/storage/product_images/{{$product->product_image}}" alt=""></td>
                    <td>{{$product->product_name}}</td>
                    <td>{{$product->product_price}}</td>
                    <td>{{$product->product_category}}</td>

                    @if ($product->product_status == 1)
                      <td>
                        <label class="badge badge-success">Active</label>
                      </td>
                    @else
                      <td>
                        <label class="badge badge-danger">Unactive</label>
                      </td>
                    @endif

                    <td>
                      <a class="btn btn-outline-primary" href="{{URL::to('/edit_product/'.$product->id)}}">Edit</a>
					  <a href="/delete_product/{{$product->id}}"  class="btn btn-outline-danger" id="delete">Delete</a>
					  
					  @if ($product->product_status == 1)
					  	<button class="btn btn-outline-warning" onclick="window.location ='{{url('/unactivate_product/'.$product->id)}}'">Unactivate</button>
					  @else
					  	<button class="btn btn-outline-success" onclick="window.location ='{{url('/activate_product/'.$product->id)}}'">Activate</button>
					  @endif
                    </td>
                </tr>
				{{Form::hidden('', $increment++)}}
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
    <script src="{{asset('backend/js/data-table.js')}}"></script>
@endsection