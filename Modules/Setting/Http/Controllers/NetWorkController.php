<?php

namespace Modules\Setting\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use Modules\Setting\Entities\NetWork;

class NetWorkController extends Controller
{


    public function add(Request $request)
    {
        $iconAddres = uploadImage($request->image);
        NetWork::create([
            'icon' => $iconAddres,
            'title' => $request->title,
            'address' => $request->address,

        ]);
        $response = (object)[
            'data' => 'با موفقیت انجام شد .'
        ];
        return Response::json($response, 200);


    }

    public function update(Request $request)
    {
        if ($request->image) {
            $iconAddres = uploadImage($request->image);
            NetWork::where('id', $request->id)->Update([
                'icon' => $iconAddres,
                'title' => $request->title,
                'address' => $request->address,
            ]);
        } else {

            NetWork::where('id', $request->id)->Update([

                'title' => $request->title,
                'address' => $request->address,
            ]);
        }

        $response = (object)[
            'data' => 'با موفقیت انجام شد .'
        ];
        return Response::json($response, 200);


    }

    public function delete($id)
    {

        NetWork::where('id', $id)->delete();
        $response = (object)[
            'data' => 'با موفقیت انجام شد .'
        ];
        return Response::json($response, 200);


    }

    public function all()
    {

        $response = (object)[
            'data' => NetWork::all()
        ];
        return Response::json($response, 200);


    }

    public function show($id)
    {

        $response = (object)[
            'data' => NetWork::find($id)
        ];
        return Response::json($response, 200);


    }


}
