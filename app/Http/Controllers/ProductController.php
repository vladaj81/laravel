<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use App\Product;
use App\Category;
use Session;
use App\Cart;


class ProductController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function addproduct()
    {
        $categories = Category::all()->pluck('category_name', 'category_name');
        return view('admin.addproduct')->with('categories', $categories);
    }


    public function products()
    {
        $productsi = Product::get();
        return view('admin.products', compact('productsi'));
    }

    public function saveproduct(Request $request) {

        
        $this->validate($request, ['product_name' => 'required',
                                    'product_price' => 'required',
                                    //'product_image' => 'image|nullable|max:1999'
                                  ]);
        
        if($request->input('product_category')) {

            if($request->has("product_image")) {
                
                $fileNameWithExt = $request->file("product_image")->getClientOriginalName();

                $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
    
                $extension = $request->file("product_image")->getClientOriginalExtension();
    
                $fileNameToStore = $fileName .'_' .time(). '.' .$extension;
                
                $path = $request->file('product_image')->storeAs('public/product_images', $fileNameToStore);
            }
            else {
    
                //return 'greska';
                $fileNameToStore = 'noimage.jpg';
            }
    
            $product = new Product();
    
            $product->product_name = $request->input('product_name');
            $product->product_price = $request->input('product_price');
            $product->product_category = $request->input('product_category');
            $product->product_image = $fileNameToStore;
            $product->product_status = 1;
    
            $product->save();

            if(!$product->save()) {
                return redirect('/addproduct')->with('status2', 'Greska kod upisa u bazu.'); 
            }
    
            return redirect('/addproduct')->with('status', 'The product ' .$product->product_name. 
            ' has been saved successfully.');
        }
        else {

            return redirect('/addproduct')->with('status1', 'Select a category please.');
        }

        
    }

    public function editproduct($id)
    {
        $categories = Category::all()->pluck('category_name', 'category_name');
        $product = Product::find($id);

        return view('admin.editproduct',compact('product', 'categories'));
    }

    public function updateproduct(Request $request)
    {
        $this->validate($request, ['product_name' => 'required',
                                    'product_price' => 'required',
                                    //'product_image' => 'image|nullable|max:1999'
                                  ]);

        $product = Product::find($request->input('id'));

        $product->product_name = $request->input('product_name');
        $product->product_price = $request->input('product_price');
        $product->product_category = $request->input('product_category');

        if($request->has("product_image")) {
                
            $fileNameWithExt = $request->file("product_image")->getClientOriginalName();

            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);

            $extension = $request->file("product_image")->getClientOriginalExtension();

            $fileNameToStore = $fileName .'_' .time(). '.' .$extension;
            
            $path = $request->file('product_image')->storeAs('public/product_images', $fileNameToStore);

            //$old_image = Product::find($request->input('id'));
            
            if ($product->product_image != 'noimage.jpg') {

                Storage::delete('public/product_images/' .$product->product_image);
            }
            
            $product->product_image = $fileNameToStore;
        }

        $product->update();

        return redirect('/products')->with('status', 'The product ' .$product->product_name. 
            ' has been updated successfully.');
    }

    public function delete_product($id)
    {
        $product = Product::find($id);

        if ($product->product_image != 'noimage.jpg') {

            Storage::delete('public/product_images/' .$product->product_image);
        }

        $product->delete();

        return redirect('/products')->with('status', 'The product ' .$product->product_name. 
            ' has been deleted successfully.');
    }

    public function activate_product($id)
    {
        $product = Product::find($id);

        $product->product_status = 1;

        $product->save();

        return redirect('/products')->with('status', 'The product ' .$product->product_name. 
        ' has been activated successfully.');
    }

    public function unactivate_product($id)
    {
        $product = Product::find($id);

        $product->product_status = 0;

        $product->save();

        return redirect('/products')->with('status', 'The product ' .$product->product_name. 
        ' has been unactivated successfully.');
    }
}
