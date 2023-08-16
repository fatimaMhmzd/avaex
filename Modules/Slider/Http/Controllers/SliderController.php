<?php

namespace Modules\Slider\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use Modules\Slider\Entities\Slider;

class SliderController extends Controller
{


    public function addSlider(Request $request)
    {
        $imageAddres = uploadImage($request->image, 1200, 'slider');
        $imageApp = uploadImage($request->image, 500, 'slider');
        Slider::create([
            'page_id' => $request->pageid,
            'alt' => $request->alt,
            'image' => $imageAddres,
            'imageApp' => $imageApp,
            'title' => $request->title,

        ]);
        $response = (object)[
            'data' => 'با موفقیت انجام شد .'
        ];
        return Response::json($response, 200);


    }

    public function updateSlider(Request $request)
    {
        $Slider = Slider::find($request->id);
        $imageAddres = $Slider->image;
        $imageApp = $Slider->imageApp;
        if ($request->image) {
            $imageAddres = uploadImage($request->image, 1200, 'slider');
            $imageApp = uploadImage($request->image, 500, 'slider');
        }

        Slider::where('id', $request->id)->Update([
            'page_id' => $request->title,
            'alt' => $request->descripthon,
            'image' => $imageAddres,
            'imageApp' => $imageApp,
            'title' => $request->title,

        ]);
        $response = (object)[
            'data' => 'با موفقیت انجام شد .'
        ];
        return Response::json($response, 200);


    }


    public function deleteSlider($id)
    {

        Slider::where('id', $id)->delete();
        $response = (object)[
            'message' => 'با موفقیت انجام شد .'
        ];
        return Response::json($response, 200);


    }

    public function showsliderpage($pageId)
    {



        $response = (object)[
            'data' => Slider::where('page_id', $pageId)->get()
        ];
        return Response::json($response, 200);


    }


}
