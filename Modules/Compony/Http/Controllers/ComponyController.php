<?php

namespace Modules\Compony\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use Modules\Compony\Entities\Compony;
use Modules\Compony\Entities\ComponyService;
use Modules\Compony\Entities\ComponyTypePost;
use Modules\Compony\Entities\Service;

class ComponyController extends Controller
{


    public function addCompony(Request $request)
    {
        $imageAddres = uploadImage($request->image);
        Compony::create([
            'name' => $request->name,
            'image' => $imageAddres,

        ]);
        $result = (object)[
            'data' => 'با موفقیت انجام شد.'
        ];
        return Response::json($result, 200);
    }

    public function updateCompony(Request $request)
    {
        if ($request->image != null) {
            $imageAddres = uploadImage($request->image);
            Compony::where('id', $request->id)->update([
                'name' => $request->name,
                'image' => $imageAddres,

            ]);
        } else {
            Compony::where('id', $request->id)->update([
                'name' => $request->name,
            ]);

        }

        $result = (object)[
            'data' => 'با موفقیت انجام شد.'
        ];
        return Response::json($result, 200);
    }

    public function deleteCompony($id)
    {
        Compony::where('id', $id)->delete();
        $result = (object)[
            'data' => 'با موفقیت انجام شد.'
        ];
        return Response::json($result, 200);
    }

    public function showCompony()
    {
        $data = Compony::all();
        $result = (object)[
            'data' => $data
        ];
        return Response::json($result, 200);
    }

    public function addComponyType(Request $request)
    {
        ComponyTypePost::create([
            'name' => $request->name,
            'number' => $request->number,

        ]);
        $result = (object)[
            'data' => 'با موفقیت انجام شد.'
        ];
        return Response::json($result, 200);
    }


    public function updateComponyType(Request $request)
    {
        ComponyTypePost::where('id', $request->id)->update([

            'name' => $request->name,
            'number' => $request->number,

        ]);
        $result = (object)[
            'data' => 'با موفقیت انجام شد.'
        ];
        return Response::json($result, 200);
    }

    public function deleteComponyType($id)
    {
        ComponyTypePost::where('id', $id)->delete();
        $result = (object)[
            'data' => 'با موفقیت انجام شد.'
        ];
        return Response::json($result, 200);
    }

    public function showComponyType()
    {
        $data = ComponyTypePost::all();
        $result = (object)[
            'data' => $data
        ];
        return Response::json($result, 200);
    }


    public function addComponyServices(Request $request)
    {

        if ($request->srvInternal == true) {
            ComponyService::create([
                'componyId' => $request->componyId,
                'componyTypeId' => $request->componyTypeId,
                'serviceId' => 1,
                'codeOrder' => $request->codInternal,
                'onlineOrder' => $request->onlineInternal,
                'pastOrder' => $request->pastInternal,

            ]);

        }
        if ($request->srvExternal == true) {
            ComponyService::create([
                'componyId' => $request->componyId,
                'componyTypeId' => $request->componyTypeId,
                'serviceId' => 2,
                'codeOrder' => $request->codExternal,
                'onlineOrder' => $request->onlineExternal,
                'pastOrder' => $request->pastExternal,

            ]);

        }
        if ($request->srvUrban == true) {
            ComponyService::create([
                'componyId' => $request->componyId,
                'componyTypeId' => $request->componyTypeId,
                'serviceId' => 3,
                'codeOrder' => $request->codUrban,
                'onlineOrder' => $request->onlineUrban,
                'pastOrder' => $request->pastUrban,

            ]);

        }
        if ($request->srvHeavy == true) {
            ComponyService::create([
                'componyId' => $request->componyId,
                'componyTypeId' => $request->componyTypeId,
                'serviceId' => 4,
                'codeOrder' => $request->codHeavy,
                'onlineOrder' => $request->onlineHeavy,
                'pastOrder' => $request->pastHeavy,

            ]);

        }


        $result = (object)[
            'data' => 'با موفقیت انجام شد.'
        ];
        return Response::json($result, 200);
    }


    public function updateComponyServices(Request $request)
    {
        ComponyService::where('id', $request->id)->update([
            'componyTypeId' => $request->componyTypeId,
            'serviceId' => $request->serviceId,
            'codeOrder' => $request->codeOrder,
            'onlineOrder' => $request->onlineOrder,
            'pastOrder' => $request->pastOrder,

        ]);
        $result = (object)[
            'status' => 200,
            'data' => 'sucsses',
        ];
        return json_encode($result);
    }

    public function deleteComponyServices($id)
    {
        ComponyService::where('id', $id)->delete();
        $result = (object)[
            'data' => 'با موفقیت انجام شد'
        ];
        return Response::json($result, 200);
    }

    public function showComponyServices()
    {
        $data = [];
        $componies = ComponyService::all();
        foreach ($componies as $item) {
            $compony = Compony::find($item->componyId);
            $service = Service::find($item->serviceId);
            $componytype = ComponyTypePost::find($item->componyTypeId);

            $res = (object)[
                'componyservice' => $item,
                'compony' => $compony->name,
                'service' => $service->name,
                'componytype' => $componytype->name,
            ];
            array_push($data, $res);
        }
        $finalResult = (object)[
            'finalResult' => $data,
            'allCompony' => Compony::all(),
            'allcomponytype' => ComponyTypePost::all(),
            'allService' => Service::all(),
        ];
        $result = (object)[
            'data' => $finalResult
        ];
        return Response::json($result, 200);
    }


    public function all()
    {
        return ComponyService::with('compony')->with('service')->with('componyTypePost')->get();
    }

    public function allBulk()
    {
        $data = [];

        $araa = ComponyService::with('compony')->with('service')->with('componyTypePost')->get();

        foreach ($araa as $item) {

            if ($item->id == 6){
                $item1 = $item->replicate();
                $title1 = $item->compony->name . '-' . $item->componyTypePost->name . "-" . "پیش کرایه";

                $item1->title = $title1;
                $item1->id = $item->id;
                $item1->data = "$item->id" . "-pre";
                array_push($data, $item1);

            }else {

                if ($item->id == 8) {

                }else{
                    $item1 = $item->replicate();
                    $item2 = $item->replicate();
                    $item3 = $item->replicate();
                    $title1 = $item->compony->name . '-' . $item->componyTypePost->name . "-" . "پیش کرایه";
                    $title2 = $item->compony->name . '-' . $item->componyTypePost->name . "-" . "پس کرایه";
                    $title3 = $item->compony->name . '-' . $item->componyTypePost->name . "-" . "پس کرایه-COD";

                    $item1->title = $title1;
                    $item1->id = $item->id;
                    $item1->data = "$item->id" . "-pre";
                    array_push($data, $item1);
                    $item2->title = $title2;
                    $item2->id = $item->id;
                    $item2->data = "$item->id" . "-after";
                    //array_push($data, $item2);
                    $item3->title = $title3;
                    $item3->id = $item->id;
                    $item3->data = "$item->id" . "-cod";
                    array_push($data, $item3);
                }
            }
        }
        return $data;
        //return ComponyService::with('compony')->with('service')->with('componyTypePost')->get();
    }


}
