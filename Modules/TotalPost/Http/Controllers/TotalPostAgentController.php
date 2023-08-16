<?php

namespace Modules\TotalPost\Http\Controllers;

use Hekmatinasser\Verta\Facades\Verta;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Address\Entities\Address;
use Modules\Address\Entities\City;
use Modules\Agent\Entities\Agent;
use Modules\Compony\Entities\ComponyService;
use Modules\ExternalPost\Entities\ExternalOrder;
use Modules\ExternalPost\Entities\ExternalPost;
use Modules\HeavyPost\Entities\HeavyOrder;
use Modules\HeavyPost\Entities\HeavyPost;
use Modules\InternalPost\Entities\InternalOrder;
use Modules\InternalPost\Entities\InternalPost;
use Modules\InUrbanePost\Entities\PeykOrder;
use Modules\InUrbanePost\Entities\PeykPost;
use Modules\Agent\Entities\Driver;
use Modules\Setting\Entities\Setting;
use Modules\TotalPost\Entities\TotalPost;
use App\Exports\MarsoleExport;

class TotalPostAgentController extends Controller
{
    public function show(Request $request)
    {
        $data = [];
        $agent = Agent::where('userId', $request->user()->id)->first();
        $cityId = $agent->cityId;

        $query = TotalPost::where('factorstatus', 'close')->where('agentId', $agent->id);

		if ($request->status && $request->status != null && $request->status != "" && $request->status != "0" && $request->status != "اننخاب نشده") {
            $query = $query->where('status', $request->status);
        }

        if ($request->company && $request->company != null && $request->company != "" && $request->company != 0) {
            $query = $query->where('componyId', $request->company);
        }

        if ($request->barname && $request->barname != null && $request->barname != "") {
            $query = $query->where('numberParcel', $request->barname);
        }

        if ($request->fromDate && $request->fromDate != null && $request->fromDate != "") {
            $miladiDate = convertToMiladi($this->convert($request->fromDate));
            $query = $query->where('created_at', '>=',$miladiDate);
        }

        if ($request->toDate && $request->toDate != null && $request->toDate != "") {
            $miladiDate = convertToMiladi($this->convert($request->toDate));
            $query = $query->where('created_at', '<=', $miladiDate);
        }


        $numbers = $query->get()->count() / $request->numberitems;

        $totalPosts = $query->skip(($request->numberpage - 1) * $request->numberitems)->take($request->numberitems)->orderBy('id', 'DESC')->get();
        foreach ($totalPosts as $item) {

            $partsdetail = [];
            $address = Address::withTrashed()->with('countryName', 'provinceName', 'cityName')->find($item->addressId);
            $getadress = Address::withTrashed()->with('countryName', 'provinceName', 'cityName')->find($item->getterAddressId);

            $componyservice = ComponyService::with('compony')->with('service')->with('componyTypePost')->find($item->componyServicesId);
            if ($address and $address->cityId == $cityId) {
                if ($item->typeSerId == 1) {
                    $post = InternalPost::where('totalPostId', $item->id)->first();
                    $parts = InternalOrder::where('internalPostId', $post->id)->get();

                } elseif ($item->typeSerId == 2) {
                    $post = ExternalPost::where('totalPostId', $item->id)->first();
                    $parts = ExternalOrder::where('externalPostId', $post->id)->get();


                } elseif ($item->typeSerId == 3) {
                    $post = PeykPost::where('totalPostId', $item->id)->first();
                    $parts = PeykOrder::where('externalPostId', $post->id)->get();


                } else {
                    $post = HeavyPost::where('totalPostId', $item->id)->first();
                    $parts = HeavyOrder::where('externalPostId', $post->id)->get();

                }

                $res = (object)[
                    'item' => $item,
                    'date' => dateTimeToDate($item->created_at),
                    'time' => dateTimeToTime($item->created_at),
                    'componyservice' => $componyservice,
                    'post' => $post,
                    'address' => $address,
                    'getadress' => $getadress,
                    'partsdetail' => $parts,

                ];
                array_push($data, $res);
            }else{
               // return $item;
            }


        }

        $driver = [];
        if ($agent) {
            $driver = Driver::where('agentId', $agent->id)->get();
        }

        $totalData = (object)[
            'totalItems' => $data,
            'number' => ceil($numbers),
            'drivers' => $driver,
            'UI' => $request->user()->id,
        ];


        $result = (object)[
            'data' => $totalData
        ];
        return Response::json($result, 200);


    }
    public function showExel(Request $request ,$id)
    {
        $data = [];
        $agent = Agent::where('userId', $id)->first();
        $cityId = $agent->cityId;

        $query = TotalPost::where('factorstatus', 'close')->where('agentId', $agent->id);

        if ($request->status && $request->status != null && $request->status != "" && $request->status != 0 && $request->status != "اننخاب نشده") {
            $query = $query->where('status', $request->status);
        }

        if ($request->company && $request->company != null && $request->company != "" && $request->company != 0) {
            $query = $query->where('componyId', $request->company);
        }

        if ($request->barname && $request->barname != null && $request->barname != "") {
            $query = $query->where('numberParcel', $request->barname);
        }

        if ($request->fromDate && $request->fromDate != null && $request->fromDate != "") {
            $miladiDate = convertToMiladi($this->convert($request->fromDate));
            $query = $query->where('created_at', '>=',$miladiDate);
        }

        if ($request->toDate && $request->toDate != null && $request->toDate != "") {
            $miladiDate = convertToMiladi($this->convert($request->toDate));
            $query = $query->where('created_at', '<=', $miladiDate);
        }

        $totalPosts = $query->orderBy('id', 'DESC')->get();
        foreach ($totalPosts as $item) {

            $partsdetail = [];
            $address = Address::withTrashed()->with('countryName', 'provinceName', 'cityName')->find($item->addressId);
            $getadress = Address::withTrashed()->with('countryName', 'provinceName', 'cityName')->find($item->getterAddressId);

            $componyservice = ComponyService::with('compony')->with('service')->with('componyTypePost')->find($item->componyServicesId);
            if ($address and $address->cityId == $cityId) {
                if ($item->typeSerId == 1) {
                    $post = InternalPost::where('totalPostId', $item->id)->first();
                    $parts = InternalOrder::where('internalPostId', $post->id)->get();

                } elseif ($item->typeSerId == 2) {
                    $post = ExternalPost::where('totalPostId', $item->id)->first();
                    $parts = ExternalOrder::where('externalPostId', $post->id)->get();


                } elseif ($item->typeSerId == 3) {
                    $post = PeykPost::where('totalPostId', $item->id)->first();
                    $parts = PeykOrder::where('externalPostId', $post->id)->get();


                } else {
                    $post = HeavyPost::where('totalPostId', $item->id)->first();
                    $parts = HeavyOrder::where('externalPostId', $post->id)->get();

                }

                $print = "خیر" ;
                if ($item->printFactor !=0){
                    $print = "بله" ;
                }
                $packaging = "خیر";
                if ($item->Packaging !=0){
                    $packaging = "بله" ;
                }

                $res = array(
                    $item->numberParcel,
                    $componyservice->service->name." ".$componyservice->componyTypePost->name,
                    $componyservice->compony->name,
                    $address->totalAddress,
                    $getadress->totalAddress,
                    $item->realPayable,
                    $item->serviceInPlace,
                    $print,
                    $packaging,
                    $item->status,
                    dateTimeToDate($item->updated_at),
                    dateTimeToTime($item->updated_at),
                );
                array_push($data, $res);


            }else{
               // return $item;
            }


        }


        $export = new MarsoleExport($data);

        return Excel::download($export, 'marsolat.xlsx');


    }

    public function count(Request $request)
    {
        $agent = Agent::where('userId', $request->user()->id)->first();

        $city = City::find($agent->cityId)->faName;

        $notCollected = TotalPost::where('factorstatus', 'close')->where('agentId', $agent->id)->where('status', 'جمع آوری نشده')->pluck('id');


        $result = (object)[
            'data' => TotalPost::where('factorstatus', 'close')->where('agentId', $agent->id)->where('status', 'جمع آوری نشده')->count(),
            'part' => InternalOrder::whereIn('internalPostId',$notCollected)->count(),
            'driver' => TotalPost::where('factorstatus', 'close')->where('agentId', $agent->id)->where('status', 'ارجاع به راننده')->count(),
            'overhung' => TotalPost::where('factorstatus', 'close')->where('agentId', $agent->id)->where('status', 'معلق')->count(),
            /*'returned' =>  TotalPost::where('factorstatus', 'close')->where('agentId',$agent->id)->where('status', 'برگشتی')->count(),
            'delivered' =>  TotalPost::where('factorstatus', 'close')->where('agentId',$agent->id)->where('status', 'تحویل داده شده')->count(),*/
            'transferring' => TotalPost::where('factorstatus', 'close')->where('agentId', $agent->id)->where('status', 'تحویل داده شده')->count(),
            'city' => $city,
        ];
        return Response::json($result, 200);
    }

    function convert($string) {
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $arabic = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];

        $num = range(0, 9);
        $convertedPersianNums = str_replace($persian, $num, $string);
        $englishNumbersOnly = str_replace($arabic, $num, $convertedPersianNums);

        return $englishNumbersOnly;
    }

}
