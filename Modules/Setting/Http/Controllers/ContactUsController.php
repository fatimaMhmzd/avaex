<?php

namespace Modules\Setting\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use Modules\Setting\Entities\ContactUs;

class ContactUsController extends Controller
{
    public function add(Request $request){

        ContactUs::create([

            'userId' => $request->userId,
            'name' => $request->name,
            'family' => $request->family,
            'subject' => $request->subject,
            'mobile' => $request->mobile,
            'message' => $request->message,
            'status' => 'پاسخ داده نشده',

        ]);
        $response = (object)[
            'data' => 'با موفقیت انجام شد .'
        ];
        return Response::json($response, 200);


    }
    public function update(Request $request){


        ContactUs::where('id', $request->id)->Update([

            'answer' => $request->answer,
            'status' => $request->status,
        ]);
        $response = (object)[
            'data' => 'با موفقیت انجام شد .'
        ];
        return Response::json($response, 200);


    }


    public function delete($id){

        ContactUs::where('id', $id)->delete();
        $response = (object)[
            'data' => 'با موفقیت انجام شد .'
        ];
        return Response::json($response, 200);


    }
    public function all(){

        $response = (object)[
            'data' => ContactUs::all()
        ];
        return Response::json($response, 200);


    }
    public function show($id){

        $response = (object)[
            'data' =>  ContactUs::find($id)
        ];
        return Response::json($response, 200);


    }
}
