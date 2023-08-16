<?php

namespace Modules\Address\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use Modules\Address\Entities\City;
use Modules\Address\Entities\Country;
use Modules\Address\Entities\Province;

class CountryController extends Controller
{
    public function add(Request $request)
    {
        Country::create([
            'faName'=>$request->faName,
            'enName'=>$request->enName,

        ]);
        $result = (object)[
            'data' => 'با موفقیت انجام شد .'
        ];
        return Response::json($result, 200);
    }
    public function update(Request $request)
    {
        Country::where('id' , $request->id)->Update([
            'faName'=>$request->faName,
            'enName'=>$request->enName,

        ]);
        $result = (object)[
            'data'=>'با موفقیت انجام شد'
        ];
        return Response::json($result, 200);
    }
    public function delete($id)
    {
      /*  Country::where('id',$id)->delete();*/
        $result = (object)[
            'data' => 'با موفقیت انجام شد .'
        ];
        return Response::json($result, 200);
    }

    public function all(){

        $result = (object)[
            'data'=> Country::all()
        ];
        return Response::json($result, 200);


    }

    public function test()
    {
        /*$ch = curl_init();
        $client = new SoapClient("http://example.com/webservices?wsdl");*/
        $page = file_get_contents("https://app.krch.ir/v1/get_city");
        $data = json_decode($page)->objects->city;
        //return $data;
        foreach ($data as $item){
            if ($item->state_no and $item->state_no!= 0 and $item->state_no != '0') {
                City::create([
                    'countryId' => 1,
                    'provinceId' => $item->state_no,
                    'chaparNumber' => $item->no,
                    'chaparLandTime' => $item->land_time,
                    'chaparAirTime' => $item->air_time,
                    'faName' => $item->name,
                ]);
            }
        }
    }
}
