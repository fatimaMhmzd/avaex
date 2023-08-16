<?php

namespace Modules\TotalPost\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Address\Entities\Address;
use Modules\Address\Entities\City;
use Modules\Address\Entities\Province;
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
use Modules\TotalPost\Entities\TotalPost;
use Modules\User\Entities\User;
use App\Exports\MarsoleExport;

class TotalPostAdminController extends Controller
{
    public function show(Request $request)
    {

        $data = [];

        $query = TotalPost::where('factorstatus', 'close');

        if ($request->status && $request->status != null && $request->status != "" && $request->status != "اننخاب نشده") {

            $query = $query->where('status', $request->status);
        }


        if ($request->provincId && $request->provincId != null && $request->provincId != "" && $request->provincId != "0" && $request->provincId != 0) {
            $provinc = $request->provincId;
            $query = $query->whereHas('addressAt', function ($queryy) use ($provinc) {
                $queryy->where('provinceId', $provinc);
            });
        }
        $agent = "";
        $user = "";
        if ($request->cityId && $request->cityId != null && $request->cityId != "" && $request->cityId != "0" && $request->cityId != 0) {
            $city = $request->cityId;
            $query = $query->whereHas('addressAt', function ($queryy) use ($city) {
                $queryy->where('cityId', $city);
            });

            $agent = Agent::where('cityId', $request->cityId)->first();
            if ($agent) {
                $user = User::find($agent->userId);
            }
        }
        if ($request->componyId && $request->componyId != null && $request->componyId != "" && $request->componyId != 0 && $request->componyId != "0") {
            $query = $query->where('componyId', $request->componyId);
        }
        if ($request->methodP && $request->methodP != null && $request->methodP != "" && $request->methodP != 0 && $request->methodP != "0") {
            if ($request->methodP == 1){
                $query = $query->where('isAfterRent',0)->where('isCod',0);
            }elseif ($request->methodP == 2){
                $query = $query->where('isAfterRent',1);
            }elseif ($request->methodP == 3){
                $query = $query->where('isCod',1);
            }
        }

        if ($request->fromDate && $request->fromDate != null && $request->fromDate != "") {
            $miladiDate = convertToMiladi($this->convert($request->fromDate));
            $query = $query->where('created_at', '>=',$miladiDate);
        }

        if ($request->toDate && $request->toDate != null && $request->toDate != "") {
            $miladiDate = convertToMiladi($this->convert($request->toDate));
            $query = $query->where('created_at', '<=', $miladiDate);
        }


        if ($request->srcinput && $request->srcinput != null && $request->srcinput != "") {
            $srcinput = $request->srcinput;
            $query = $query->where('numberParcel', 'like', '%' . $srcinput . '%')
                ->orWhereHas('addressAt', function ($queryy) use ($srcinput) {
                    $queryy->where('totalAddress', 'like', '%' . $srcinput . '%');
                });
        }


        $numbers = $query->get()->count() / $request->numberitems;


        $totalPosts = $query->skip(($request->numberpage - 1) * $request->numberitems)->take($request->numberitems)->orderBy('id', 'DESC')->get();

        foreach ($totalPosts as $item) {
            $partsdetail = [];
            $address = Address::with('countryName', 'provinceName', 'cityName')->find($item->addressId);
            $getadress = Address::with('countryName', 'provinceName', 'cityName')->find($item->getterAddressId);
            $componyservice = ComponyService::with('compony')->with('service')->with('componyTypePost')->find($item->componyServicesId);

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
                'date' => dateTimeToDate($item->updated_at),
                'time' => dateTimeToTime($item->updated_at),
                'componyservice' => $componyservice,
                'post' => $post,
                'address' => $address,
                'getadress' => $getadress,
                'partsdetail' => $parts,

            ];
            array_push($data, $res);


        }


        $city = "";
        if ($agent) {
            $city = City::find($agent->cityId);
            $province = Province::find($agent->provinceId);
        }
        $totalData = (object)[
            'totalItems' => $data,
            'number' => ceil($numbers),
            'province' => Province::all(),
            'agent' => $agent,
            'user' => $user,
            'city' => $city,
            /*'province'=>$province,*/
        ];


        $result = (object)[
            'data' => $totalData
        ];
        return Response::json($result, 200);


    }
    public function showExel(Request $request)
    {

        $data = [];

        $query = TotalPost::where('factorstatus', 'close');

        if ($request->status && $request->status != null && $request->status != "" && $request->status != "اننخاب نشده") {

            $query = $query->where('status', $request->status);
        }


        if ($request->provincId && $request->provincId != null && $request->provincId != "" && $request->provincId != "0" && $request->provincId != 0) {
            $provinc = $request->provincId;
            $query = $query->whereHas('addressAt', function ($queryy) use ($provinc) {
                $queryy->where('provinceId', $provinc);
            });
        }
        $agent = "";
        $user = "";
        if ($request->cityId && $request->cityId != null && $request->cityId != "" && $request->cityId != "0" && $request->cityId != 0) {
            $city = $request->cityId;
            $query = $query->whereHas('addressAt', function ($queryy) use ($city) {
                $queryy->where('cityId', $city);
            });

            $agent = Agent::where('cityId', $request->cityId)->first();
            if ($agent) {
                $user = User::find($agent->userId);
            }
        }
        if ($request->componyId && $request->componyId != null && $request->componyId != "" && $request->componyId != 0 && $request->componyId != "0") {
            $query = $query->where('componyId', $request->componyId);
        }
        if ($request->methodP && $request->methodP != null && $request->methodP != "" && $request->methodP != 0 && $request->methodP != "0") {
            if ($request->methodP == 1){
                $query = $query->where('isAfterRent',0)->where('isCod',0);
            }elseif ($request->methodP == 2){
                $query = $query->where('isAfterRent',1);
            }elseif ($request->methodP == 3){
                $query = $query->where('isCod',1);
            }
        }

        if ($request->fromDate && $request->fromDate != null && $request->fromDate != "") {
            $miladiDate = convertToMiladi($this->convert($request->fromDate));
            $query = $query->where('created_at', '>=',$miladiDate);
        }

        if ($request->toDate && $request->toDate != null && $request->toDate != "") {
            $miladiDate = convertToMiladi($this->convert($request->toDate));
            $query = $query->where('created_at', '<=', $miladiDate);
        }


        if ($request->srcinput && $request->srcinput != null && $request->srcinput != "") {
            $srcinput = $request->srcinput;
            $query = $query->where('numberParcel', 'like', '%' . $srcinput . '%')
                ->orWhereHas('addressAt', function ($queryy) use ($srcinput) {
                    $queryy->where('totalAddress', 'like', '%' . $srcinput . '%');
                });
        }




        $totalPosts = $query->orderBy('id', 'DESC')->get();

        foreach ($totalPosts as $item) {
            $partsdetail = [];
            $address = Address::withTrashed()->with('countryName', 'provinceName', 'cityName')->find($item->addressId);
            $getadress = Address::withTrashed()->with('countryName', 'provinceName', 'cityName')->find($item->getterAddressId);
            $componyservice = ComponyService::with('compony')->with('service')->with('componyTypePost')->find($item->componyServicesId);

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


        }



        $export = new MarsoleExport($data);

        return Excel::download($export, 'marsolat.xlsx');


    }


    public function province()
    {
        $provnce = Province::all();
        $data = [];
        foreach ($provnce as $item) {
            $addresses = Address::where('provinceId', $item->id)->pluck('id')->toArray();
            $marsolle = TotalPost::whereIn('addressId', $addresses)->where('factorstatus', 'close')->where('status', 'جمع آوری نشده')->count();
            $marsolleId = TotalPost::whereIn('addressId', $addresses)->where('factorstatus', 'close')->where('status', 'جمع آوری نشده')->pluck('id');
            $partNum = InternalOrder::whereIn('internalPostId',$marsolleId)->count();
            $item->count = $marsolle;
            $item->part = $partNum;
            array_push($data, $item);
        }
        $result = (object)[
            'data' => $data
        ];
        return Response::json($result, 200);

    }

    public function cities($provinceId)
    {
        $cities = City::where('provinceId', $provinceId)->get();
        $data = [];
        foreach ($cities as $item) {
            $addresses = Address::where('cityId', $item->id)->pluck('id')->toArray();
            $marsolle = TotalPost::whereIn('addressId', $addresses)->where('factorstatus', 'close')->where('status', 'جمع آوری نشده')->count();
            if ($marsolle > 0) {

                $marsolleId = TotalPost::whereIn('addressId', $addresses)->where('factorstatus', 'close')->where('status', 'جمع آوری نشده')->pluck('id');
                $partNum = InternalOrder::whereIn('internalPostId',$marsolleId)->count();

                $item->count = $marsolle;
                $item->part = $partNum;
                array_push($data, $item);
            }
        }
        $result = (object)[
            'data' => $data
        ];
        return Response::json($result, 200);
    }

    public function sendedByCity(Request $request)
    {
        $addresses = Address::where('cityId', $request->cityId)->pluck('id')->toArray();
        $query = TotalPost::whereIn('addressId', $addresses)->where('factorstatus', 'close')->where('status', 'جمع آوری نشده');


        $cityId = $request->cityId;


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
            } else {
                // return $item;
            }


        }


        $result = (object)[
            'data' => $data
        ];
        return Response::json($result, 200);
    }


    public function mypart(Request $request)
    {
        $internalPost = "";
        $externalPost = "";
        $peykPost = "";
        $heavyPost = "";
        $listNumberParcel = [];
        $myParts = [];


        if ($request->partnumber) {

            $internalPost = InternalOrder::where('partnumber', $request->partnumber)->first();

            if ($internalPost) {


                $post = InternalPost::where('id', $internalPost->internalPostId)->first();

                $factor = TotalPost::where('id', $post->totalPostId)->first();
                array_push($listNumberParcel, $factor->id);

            }

        }

        if ($request->weight) {

            $internalPost = InternalOrder::where('Weight', (int)$request->Weight)->first();
            if ($internalPost) {
                $post = InternalPost::where('id', $internalPost->internalPostId)->first();
                $factor = TotalPost::where('id', $post->totalPostId)->first();
                array_push($listNumberParcel, $factor->id);

            }

        }


        $query = TotalPost::where('userId', $request->userId)->where('factorstatus', 'close');

        if (count($listNumberParcel) > 0) {

            $query = $query->whereIn('id', $listNumberParcel);
        }

        if ($request->numberParcel) {
            $query = $query->where('numberParcel', (int)$request->numberParcel);
        }
        if ($request->startDate && !$request->endDate) {
            $query = $query->whereDate('created_at', '<', Carbon::parse(convertToMiladi($request->startDate)));
        } else if ($request->endDate && !$request->startDate) {
            $query = $query->whereDate('created_at', '>', Carbon::parse(convertToMiladi($request->endDate)));
        } else if ($request->endDate && $request->endDate) {
            $query = $query->whereBetween('created_at', [Carbon::parse(convertToMiladi($request->startDate)), Carbon::parse(convertToMiladi($request->endDate))]);
        }
        if ($request->status) {
            $query = $query->where('status', $request->status);

        }
        if ($request->componyTypeId) {
            $query = $query->where('status', $request->componyTypeId);

        }
        if ($request->senderName) {

            $address = Address::where('userId', $request->userId)->where('senderOrgetter', 0)
                ->where('totalAddress', 'like', '%' . $request->senderName . '%')->pluck('id');
            $query = $query->whereIn('addressId', $address->id);

        }
        if ($request->getterName) {
            $address = Address::where('userId', $request->userId)->where('senderOrgetter', 1)
                ->where('totalAddress', 'like', '%' . $request->getterName . '%')->pluck('id');
            $query = $query->whereIn('getterAddressId', $address->id);

        }
        if ($request->factorstatus) {
            $query = $query->whereIn('factorstatus', $request->factorstatus);

        }

        if ($request->senderProvince) {
            $address = Address::where('userId', $request->userId)->where('senderOrgetter', 0)
                ->where('provinceId', $request->senderProvince)->get('id');
            $query = $query->whereIn('addressId', $address->id);

        }
        if ($request->getterProvince) {
            $address = Address::where('userId', $request->userId)->where('senderOrgetter', 1)
                ->where('provinceId', $request->getterProvince)->pluck('id');
            $query = $query->whereIn('getterAddressId', $address->id);

        }
        if ($request->senderCity) {
            $address = Address::where('userId', $request->userId)->where('senderOrgetter', 0)
                ->where('cityId', $request->senderCity)->pluck('id');

            $query = $query->whereIn('addressId', $address->id);

        }
        if ($request->getterCity) {

            $address = Address::where('userId', $request->userId)->where('senderOrgetter', 1)
                ->where('cityId', $request->getterCity)->pluck('id');

            $query = $query->whereIn('getterAddressId', $address);

        }

        $numbers = count($query->get()) / $request->numberitems;

        $totalPost = $query->skip(($request->numberpage - 1) * $request->numberitems)->take($request->numberitems)->orderBy('id', 'DESC')->get();


        foreach ($totalPost as $item) {
            if ($item->typeSerId == 1) {
                $post = InternalPost::where('totalPostId', $item->id)->first();
                $parts = InternalOrder::where('internalPostId', $post->id)->get();
                $val = (object)[
                    'factor' => $item,
                    'post' => $post,
                    'parts' => $parts,
                    'date' => dateTimeToDate($item->updated_at),
                    'time' => dateTimeToTime($item->updated_at),

                ];
                array_push($myParts, $val);


            } elseif ($item->typeSerId == 2) {
                $post = ExternalPost::where('totalPostId', $item->id)->first();
                $parts = ExternalOrder::where('externalPostId', $post->id)->get();
                $val = (object)[
                    'factor' => $item,
                    'post' => $post,
                    'parts' => $parts,
                    'date' => dateTimeToDate($item->updated_at),
                    'time' => dateTimeToTime($item->updated_at),
                ];
                array_push($myParts, $val);

            } elseif ($item->typeSerId == 3) {
                $post = PeykPost::where('totalPostId', $item->id)->first();
                $parts = PeykOrder::where('externalPostId', $post->id)->get();
                $val = (object)[
                    'factor' => $item,
                    'post' => $post,
                    'parts' => $parts,
                    'date' => dateTimeToDate($item->updated_at),
                    'time' => dateTimeToTime($item->updated_at),
                ];
                array_push($myParts, $val);

            } else {
                $post = HeavyPost::where('totalPostId', $item->id)->first();
                $parts = HeavyOrder::where('externalPostId', $post->id)->get();
                $val = (object)[
                    'factor' => $item,
                    'post' => $post,
                    'parts' => $parts,
                    'date' => dateTimeToDate($item->updated_at),
                    'time' => dateTimeToTime($item->updated_at),
                ];
                array_push($myParts, $val);
            }
        }
        $res = (object)[
            'all' => $myParts,
            'number' => ceil($numbers),
        ];

        $result = (object)[
            'data' => $res
        ];
        return Response::json($result, 200);

    }

    public function showAgent(Request $request)
    {
        $data = [];
        $agent = Agent::where('id', $request->agentId)->first();
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
            $query = $query->where('created_at', '>=', $miladiDate);
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
            } else {
                // return $item;
            }


        }

        $driver = [];
        /*if ($agent) {
            $driver = Driver::where('agentId', $agent->id)->get();
        }*/

        $totalData = (object)[
            'totalItems' => $data,
            'number' => ceil($numbers),
            'drivers' => $driver,
        ];


        $result = (object)[
            'data' => $totalData
        ];
        return Response::json($result, 200);
    }

    public function find($data)
    {
        return $data;
    }

    function convert($string) {
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $arabic = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];

        $num = range(0, 9);
        $convertedPersianNums = str_replace($persian, $num, $string);
        $englishNumbersOnly = str_replace($arabic, $num, $convertedPersianNums);

        return $englishNumbersOnly;
    }

    public function remember(Request $request)
    {
        sendRemember($request->city_id);
        $result = (object)[
            'data' => "ok"
        ];
        return Response::json($result, 200);
    }

}
