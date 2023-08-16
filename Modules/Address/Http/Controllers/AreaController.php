<?php

namespace Modules\Address\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use Modules\Address\Entities\City;
use Modules\Address\Entities\Province;
use Modules\Address\Entities\Area;

class AreaController extends Controller
{
    public function add(Request $request)
    {
        Area::create([
            'faName'=>$request->faName,
            'enName'=>$request->enName,
            'cityId' => $request->cityId,
            'provinceId' => $request->provinceId,
            'agentId' => 0,

        ]);
        $result = (object)[
            'data' => 'با موفقیت انجام شد .'
        ];
        return Response::json($result, 200);
    }
    public function update(Request $request)
    {
        Area::where('id' , $request->id)->Update([
            'faName'=>$request->faName,
            'enName'=>$request->enName,
            'cityId' => $request->cityId,
            'provinceId' => $request->provinceId,
            'agentId' => 0,

        ]);
        $result = (object)[
            'data'=>'با موفقیت انجام شد'
        ];
        return Response::json($result, 200);
    }
    public function delete($id)
    {
        Area::where('id',$id)->delete();
        $result = (object)[
            'data' => 'با موفقیت انجام شد .'
        ];
        return Response::json($result, 200);
    }

    public function all(Request $request,$numberpage , $numberitems){
        $data = [];
        $query = Area::where('faName', 'like', '%' . $request->srcinput . '%');

        if($request->provinceId and $request->provinceId != "0"){

            $query =$query->where('provinceId',$request->provinceId );
        }
        if($request->cityId and $request->cityId != "0"){
            $query =$query->where('cityId',$request->cityId );
        }

        $totalNumber = $query->count();
        $cities = $query->skip(($numberpage-1) * $numberitems)->take($numberitems)->get();

        $fanalCities = [];

        foreach ($cities as $item){
            $province = Province::find($item->provinceId);
            if ($province){
                $province=$province->faName;
            }

            $city = City::find($item->cityId);

            if ($city){
                $city=$city->faName;
            }

            $result=(object)[
                'id'=>$item->id,
                'faName'=>$item->faName,
                'enName'=>$item->enName,
                'provinceId'=>$item->provinceId,
                'cityId'=>$item->cityId,
                'province'=>$province,
                'city'=>$city,
            ];
            array_push($fanalCities,$result);
        }
        $numbers = $totalNumber/$numberitems;
        $totalData = (object)[
            'area' => $fanalCities ,
            'number' => ceil($numbers),
            /*'allCountry'=>Province::all(),*/
        ];


//        $values = (object)[
//            'data'=>$fanalCities,
//            'allCountry'=>Country::all(),
//        ];


        $result = (object)[
            'data'=> $totalData
        ];
        return Response::json($result, 200);


    }
    public function show($id){
        $data = Area::where('cityId', $id)->get();

        $result = (object)[
            'data'=> $data
        ];
        return Response::json($result, 200);


    }
}
