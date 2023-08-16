<?php

namespace Modules\MyAccount\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use Modules\MyAccount\Entities\Damage;

class DamageController extends Controller
{
    public function add(Request $request)
    {
        $image1 = uploadImage($request->image1, 320,  'damage');
        $image2 = uploadImage($request->image2 , 320,  'damage');
        $image3 = uploadImage($request->image3 , 320,  'damage');
        $image4 = uploadImage($request->image4, 320,  'damage');
        $cardImage = uploadImage($request->cardImage, 320,  'damage/card');

        Damage::create([
            'numberParcel' => $request->numberParcel,
            'userId' => $request->user()->id,
            'typeShipment' => $request->typeShipment,
            'Shipment' => $request->Shipment,
            'price' => $request->price,
            'brandName' => $request->brandName,
            'shabaNumber' => $request->shabaNumber,
            'description' => $request->description,
            'image1' => $image1,
            'image2' => $image2,
            'image3' => $image3,
            'image4' => $image4,
            'cardImage' => $cardImage,
            'status' => 'تایید نشده',
        ]);
        $result = (object)[
            'data' => 'با موفقیت ثبت شد.'
        ];
        return Response::json($result, 200);
    }
    public function all(Request $request)
    {
        $data = Damage::where('userId' , $request->user()->id)->get();

        $result = (object)[
            'data' => $data
        ];
        return Response::json($result, 200);
    }
}
