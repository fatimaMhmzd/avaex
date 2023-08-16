<?php

namespace Modules\Address\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Address\Entities\City;
use Modules\Address\Entities\Country;
use Modules\Address\Entities\Province;
use Illuminate\Support\Facades\Response;


class ProvinceController extends Controller
{
    public function add(Request $request)
    {
        Province::create([
            'faName'=>$request->faName,
            'enName'=>$request->enName,
            'countryId' => $request->countryId

        ]);
        $result = (object)[
            'data' => 'با موفقیت انجام شد .'
        ];
        return Response::json($result, 200);
    }
    public function update(Request $request)
    {
        Province::where('id' , $request->id)->Update([
            'faName'=>$request->faName,
            'enName'=>$request->enName,
            'countryId' =>$request->countryId

        ]);
        $result = (object)[
            'data'=>'با موفقیت انجام شد'
        ];
        return Response::json($result, 200);
    }
    public function delete($id)
    {
  /*      Province::where('id',$id)->delete();*/
        $result = (object)[
            'data' => 'با موفقیت انجام شد .'
        ];
        return Response::json($result, 200);
    }

    public function all(){
        $data = [];
        $provinces = Province::all();
        foreach ($provinces as $item){
            $country = Country::find($item->countryId);
            $res = (object)[
                'province' => $item,
                'country' => $country,

            ];
            array_push($data, $res);
        }
        $values = (object)[
            'data'=>$data,
            'allCountry'=>Country::all()
        ];


        $result = (object)[
            'data'=>$values
        ];
        return Response::json($result, 200);


    }

    public function show($id){
        $data = Province::where('countryId', $id)->get();

        $result = (object)[
            'data'=> $data
        ];
        return Response::json($result, 200);


    }


}
