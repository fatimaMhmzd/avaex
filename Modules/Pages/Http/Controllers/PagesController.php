<?php

namespace Modules\Pages\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use Modules\Pages\Entities\Pages;
use Modules\Slider\Entities\Slider;

class PagesController extends Controller
{



    public function addPage(Request $request){
        $imageAddres = uploadImage($request->image,600);
        Pages::create([
            'title' => $request->title,
            'descripthon' => $request->descripthon,
            'image' => $imageAddres,
            'seo_key_word' => $request->seokeyword,
            'seo_descript' => $request->seodescript,
        ]);
        $response = (object)[
            'data' => 'با موفقیت انجام شد .'
        ];
        return Response::json($response, 200);



    }
    public function deletePage($id){
        Pages::where('id', $id)->delete();
        $response = (object)[
            'data' => 'با موفقیت حذف شد .'
        ];
        return Response::json($response, 200);



    }
    public function updatePage(Request $request){
        if ($request->image != null){

            $imageAddres = uploadImage($request->image,600);
            Pages::where('id', $request->id)->Update([
                'title' => $request->title,
                'descripthon' => $request->descripthon,
                'image' => $imageAddres,
                'seo_key_word' => $request->seokeyword,
                'seo_descript' => $request->seodescript,

            ]);

        }
        else{


            Pages::where('id', $request->id)->Update([
                'title' => $request->title,
                'descripthon' => $request->descripthon,
                'seo_key_word' => $request->seokeyword,
                'seo_descript' => $request->seodescript,

            ]);
        }

//       Slider::where('page_id' , $pageId)->Update([
//          'image' => $request->imageSlider
//       ]);



        $response = (object)[
            'data' => 'با موفقیت انجام شد .'
        ];
        return Response::json($response, 200);



    }
    public function deleteImage($id){

        
            Pages::where('id', $id)->Update([
                'image' => "",

            ]);




        $response = (object)[
            'data' => 'با موفقیت انجام شد .'
        ];
        return Response::json($response, 200);



    }
    public function pageDitail($id){

      $ditail =  Pages::with('imgSlider')->find($id);

          $response = (object)[
              'data' => $ditail
          ];
          return Response::json($response, 200);


    }
    public function allPageDitail(){


      $ditail =  Pages::with('imgSlider')->get();

          $response = (object)[
              'data' => $ditail
          ];
          return Response::json($response, 200);


    }



}
