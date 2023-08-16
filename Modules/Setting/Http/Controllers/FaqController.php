<?php

namespace Modules\Setting\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use Modules\Setting\Entities\Faq;

class FaqController extends Controller
{


    public function add(Request $request){

        Faq::create([

            'title' => $request->title,
            'body' => $request->body,

        ]);
        $response = (object)[
            'data' => 'با موفقیت انجام شد .'
        ];
        return Response::json($response, 200);


    }
    public function update(Request $request){


        Faq::where('id', $request->id)->Update([

            'title' => $request->title,
            'body' => $request->body,
        ]);
        $response = (object)[
            'data' => 'با موفقیت انجام شد .'
        ];
        return Response::json($response, 200);


    }


    public function delete($id){

        Faq::where('id', $id)->delete();
        $response = (object)[
            'data' => 'با موفقیت انجام شد .'
        ];
        return Response::json($response, 200);


    }
    public function all(){

        $response = (object)[
            'data' => Faq::all()
        ];
        return Response::json($response, 200);


    }
    public function show($id){

        $response = (object)[
            'data' =>  Faq::find($id)
        ];
        return Response::json($response, 200);


    }


}
