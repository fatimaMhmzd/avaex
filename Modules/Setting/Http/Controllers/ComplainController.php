<?php

namespace Modules\Setting\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use Modules\Setting\Entities\Complain;

class ComplainController extends Controller
{
    public function add(Request $request){

        Complain::create([

            'userId' => $request->userId,
            'name' => $request->name,
            'family' => $request->family,
            'mobile' => $request->mobile,
            'subject' => $request->subject,
            'Handlingunit' => $request->Handlingunit,
            'importance' => $request->importance,
            'message' => $request->message,
            'status' => 'پاسخ داده نشده',

        ]);
        $response = (object)[
            'data' => 'با موفقیت انجام شد .'
        ];
        return Response::json($response, 200);


    }
    public function update(Request $request){


        Complain::where('id', $request->id)->Update([

            'answer' => $request->answer,
            'status' => $request->status,
        ]);
        $response = (object)[
            'data' => 'با موفقیت انجام شد .'
        ];
        return Response::json($response, 200);


    }
    public function delete($id){

        Complain::where('id', $id)->delete();
        $response = (object)[
            'data' => 'با موفقیت انجام شد .'
        ];
        return Response::json($response, 200);


    }
    public function all(){

        $response = (object)[
            'data' => Complain::all()
        ];
        return Response::json($response, 200);


    }
    public function show($id){

        $response = (object)[
            'data' =>  Complain::find($id)
        ];
        return Response::json($response, 200);


    }
}
