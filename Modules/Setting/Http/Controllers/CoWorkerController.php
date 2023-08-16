<?php

namespace Modules\Setting\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use Modules\Setting\Entities\CoWorker;

class CoWorkerController extends Controller
{
    public function add(Request $request){
        $imageAddres = uploadImage($request->image);

        CoWorker::create([

            'image' => $imageAddres,
            'title' => $request->title,


        ]);
        $response = (object)[
            'data' => 'با موفقیت انجام شد .'
        ];
        return Response::json($response, 200);


    }
    public function update(Request $request){
        if ($request->image){
            $imageAddres = uploadImage($request->image);
            CoWorker::where('id', $request->id)->Update([
                'image' => $imageAddres,
                'title' => $request->title,
            ]);

        }
        else{
            CoWorker::where('id', $request->id)->Update([
                'title' => $request->title,
            ]);

        }



        $response = (object)[
            'data' => 'با موفقیت انجام شد .'
        ];
        return Response::json($response, 200);


    }
    public function delete($id){

        CoWorker::where('id', $id)->delete();
        $response = (object)[
            'data' => 'با موفقیت انجام شد .'
        ];
        return Response::json($response, 200);


    }
    public function all(){

        $response = (object)[
            'data' => CoWorker::all()
        ];
        return Response::json($response, 200);


    }
    public function show($id){

        $response = (object)[
            'data' =>  CoWorker::find($id)
        ];
        return Response::json($response, 200);


    }
}
