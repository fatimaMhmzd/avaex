<?php

namespace Modules\Address\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use Modules\Address\Entities\City;
use Modules\Address\Entities\Country;
use Modules\Address\Entities\Province;

class CityController extends Controller
{
    public function add(Request $request)
    {
        City::create([
            'faName'=>$request->faName,
            'enName'=>$request->enName,
            'countryId' => $request->countryId,
            'provinceId' => $request->provinceId,

        ]);
        $result = (object)[
            'data' => 'با موفقیت انجام شد .'
        ];
        return Response::json($result, 200);
    }
    public function update(Request $request)
    {
        City::where('id' , $request->id)->Update([
            'faName'=>$request->faName,
            'enName'=>$request->enName,
            'countryId' =>$request->countryId,
            'provinceId' =>$request->provinceId,

        ]);
        $result = (object)[
            'data'=>'با موفقیت انجام شد'
        ];
        return Response::json($result, 200);
    }
    public function delete($id)
    {
       /* City::where('id',$id)->delete();*/
        $result = (object)[
            'data' => 'با موفقیت انجام شد .'
        ];
        return Response::json($result, 200);
    }

    public function all($numberpage , $numberitems , $countryId,$provinceId , $srcinput = ''){
        $data = [];
        $query = City::where('faName', 'like', '%' . $srcinput . '%');
        if($provinceId != "0"){
            $query =$query->where('provinceId',$provinceId );


        }
        if($countryId != "0"){

            $query =$query->where('countryId',$countryId );

        }
        $totalNumber = $query->count();
        $cities = $query->skip(($numberpage-1) * $numberitems)->take($numberitems)->get();

        $fanalCities = [];

        foreach ($cities as $item){
            $province = Province::find($item->provinceId);
            if ($province){
                $province=$province->faName;
            }

            $country = Country::find($item->countryId);
            if ($country){
                $country=$country->faName;
            }

            $result=(object)[
                'id'=>$item->id,
                'faName'=>$item->faName,
                'enName'=>$item->enName,
                'provinceId'=>$item->provinceId,
                'countryId'=>$item->countryId,
                'province'=>$province,
                'country'=>$country,
            ];
            array_push($fanalCities,$result);
        }

        $numbers = $totalNumber/$numberitems;
        $totalData = (object)[
            'cities' => $fanalCities ,
            'number' => ceil($numbers),
            'allCountry'=>Country::all(),

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
        $data = City::where('provinceId', $id)->get();

        $result = (object)[
            'data'=> $data
        ];
        return Response::json($result, 200);


    }
}
