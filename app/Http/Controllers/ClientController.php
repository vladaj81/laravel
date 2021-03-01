<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App\Slider;
use App\Product;
use App\Category;
use App\Cart;
use Stripe\Charge;
use Stripe\Stripe;
use App\Order;
use App\Client;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;


class ClientController extends Controller
{
    public function home()
    {
        $sliders = Slider::where('status', 1)->get();
        $products = Product::where('product_status', 1)->get();

        return view('client.home',compact('sliders', 'products'));
    }

    public function shop()
    {
        $products = Product::where('product_status', 1)->get();
        $categories = Category::get();

        return view('client.shop',compact('products', 'categories'));
    }

    public function view_by_cat($name)
    {
        $categories = Category::get();
        $products = Product::where('product_category', $name)->get();

        return view('client.shop',compact('products', 'categories'));
    }

    public function cart()
    {   
        if (!Session::has('cart')) {

            return view('client.cart');
        }

        $oldcart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldcart);

        return view('client.cart', ['products' => $cart->items]);
        //return dd(Session::get('cart'));
        
    }

    public function addToCart($id)
    {
        $product = Product::find($id);

        $oldcart = Session::has('cart') ? Session::get('cart') : null;

        $cart = new Cart($oldcart);
        $cart->add($product, $id);

        Session::put('cart', $cart);

        return redirect('/shop');

        //dd(Session::get('cart'));
    }

    public function updateqty(Request $request)
    {
        $oldcart = Session::has('cart') ? Session::get('cart') : null;
       
        $cart = new Cart($oldcart);

        $cart->updateQty($request->id, $request->quantity);
        Session::put('cart', $cart);
        
        return redirect('/cart');
       
    }

    public function removeitem($id)
    {
        $oldcart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldcart);

        $cart->removeItem($id);

        if (count($cart->items) > 0) {

            Session::put('cart', $cart);
        }
        else {
            
            Session::forget('cart');
        }
        
        return redirect('/cart');
    }

    public function checkout()
    {
        if(!Session::has('client')) {

            return redirect('/client_login');
        }

        if(!Session::has('cart')) {

            return redirect('/cart');
        }
        
        return view('client.checkout');
    }

    public function postcheckout(Request $request)
    {
        if(!Session::has('cart')) {

            return redirect('/');
        }

        
        
        $oldcart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldcart);
        
        Stripe::setApiKey('sk_test_51IKfhYF2ncrdJJKOYRJUxRrPiHELKu6m8tUXxs82omVbSeITgCIDQk0cJmZiE4GTrrC7wNhUIoH8TRyyAfdNtiXQ00zrkbSKwp');

        try{
            $charge = Charge::create(array(
                "amount" => $cart->totalPrice * 100,
                "currency" => "usd",
                "source" => $request->input('stripeToken'), // obtainded with Stripe.js
                "description" => "Test Charge"
            ));

            $order = new Order();

            $order->name = $request->input('name');
            $order->address = $request->input('address');
            $order->cart = serialize($cart);
            $order->payment_id = $charge->id;
    
            $order->save();

            $orders = Order::where('payment_id', $charge->id)->get();

            $orders->transform(function($order, $key) {

                $order->cart = unserialize($order->cart);
    
                return $order;
            });

            $email = Session::get('client')->email;

            Mail::to($email)->send(new SendMail($orders));
        }
        catch(\Exception $e){
            Session::put('error', $e->getMessage());
            return redirect('/checkout');
        }

        Session::forget('cart');
        //Session::put('success', 'Purchase accomplished successfully !');
        return redirect('/cart')->with('success', 'Purchase accomplished successfully !');
    }

    public function login()
    {
        return view('client.login');
    }

    public function signup()
    {
        return view('client.signup');
    }

    public function createaccount(Request $request)
    {
        $this->validate($request, [

            'email' => 'email|required|unique:clients',
            'password' => 'required|min:4'
        ]);

        $client = new Client();

        $client->email = $request->input('email');
        $client->password = bcrypt($request->input('password'));

        $client->save();

        return back()->with('status', 'Your account has been created successfully.');
    }

    public function accessaccount(Request $request)
    {
        $this->validate($request, [

            'email' => 'email|required',
            'password' => 'required'
        ]);

        $client = Client::where('email', $request->input('email'))->first();

        if ($client) {

            if(Hash::check($request->input('password'), $client->password)) {

                Session::put('client', $client);

                return redirect('/shop');
                //return back()->with('status', 'Your email is: ' .Session::get('client')->email);
            }
            else {
                return back()->with('error', 'Wrong password or email.');
            }
        }
        else {

            return back()->with('error', 'There is no account with entered email address.');
        }
    }

    public function logout()
    {
        Session::forget('client');

        //return back();
        return redirect('/client_login');
    }
}
