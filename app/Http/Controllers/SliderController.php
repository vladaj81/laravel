<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Slider;

class SliderController extends Controller
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

    public function addslider()
    {
        return view('admin.addslider');
    }

    public function sliders()
    {
        $sliders = Slider::get();
        return view('admin.sliders', compact('sliders'));
    }

    public function saveslider(Request $request)
    {
        $this->validate($request, [
            'description_one' => 'required',
            'description_two' => 'required',
            'slider_image' => 'image|nullable|max:1999'
      ]);

      $fileNameWithExt = $request->file("slider_image")->getClientOriginalName();

        if($request->has("slider_image")) {
                    
            $fileNameWithExt = $request->file("slider_image")->getClientOriginalName();

            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);

            $extension = $request->file("slider_image")->getClientOriginalExtension();

            $fileNameToStore = $fileName .'_' .time(). '.' .$extension;
            
            $path = $request->file('slider_image')->storeAs('public/slider_image', $fileNameToStore);
        }

        else {

            $fileNameToStore = 'noimage.jpg';
        }

        $slider = new Slider();

        $slider->description1 = $request->input('description_one');
        $slider->description2 = $request->input('description_two');
        $slider->slider_image = $fileNameToStore;
        $slider->status = 1;

        $slider->save();

        return redirect('/addslider')->with('status', 'The slider has been saved successfully.');
    }

    public function edit_slider($id)
    {
        $slider = Slider::find($id);

        return view('admin.editslider', compact('slider'));
    }

    public function updateslider(Request $request)
    {
        $this->validate($request, [
            'description_one' => 'required',
            'description_two' => 'required',
            'slider_image' => 'image|nullable|max:1999'
      ]);

        $slider = Slider::find($request->input('id'));

        $slider->description1 = $request->input('description_one');
        $slider->description2 = $request->input('description_two');

        if($request->has("slider_image")) {
                
            $fileNameWithExt = $request->file("slider_image")->getClientOriginalName();

            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);

            $extension = $request->file("slider_image")->getClientOriginalExtension();

            $fileNameToStore = $fileName .'_' .time(). '.' .$extension;
            
            $path = $request->file('slider_image')->storeAs('public/slider_image', $fileNameToStore);

            //$old_image = Product::find($request->input('id'));
            
            if ($slider->slider_image != 'noimage.jpg') {

                Storage::delete('public/slider_image/' .$slider->slider_image);
            }
            
            $slider->slider_image = $fileNameToStore;
        }

        $slider->update();

        return redirect('/sliders')->with('status', 'The slider has been updated successfully.');

    }

    public function delete_slider($id)
    {
        $slider = Slider::find($id);

        if ($slider->slider_image != 'noimage.jpg') {

            Storage::delete('public/slider_image/' .$slider->slider_image);
        }

        $slider->delete();

        return redirect('/sliders')->with('status', 'The slider has been deleted successfully.');
    }

    public function activate_slider($id)
    {
        $slider = Slider::find($id);

        $slider->status = 1;

        $slider->save();

        return redirect('/sliders')->with('status', 'The slider has been activated successfully.');
    }

    public function unactivate_slider($id)
    {
        $slider = Slider::find($id);

        $slider->status = 0;

        $slider->save();

        return redirect('/sliders')->with('status', 'The slider has been unactivated successfully.');
    }
}
