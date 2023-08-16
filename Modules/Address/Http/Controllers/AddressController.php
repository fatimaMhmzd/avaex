<?php

namespace Modules\Address\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Modules\Address\Entities\Address;
use Modules\Address\Entities\Area;
use Modules\Address\Entities\City;
use Modules\Address\Entities\Country;
use Modules\Address\Entities\Province;
use Modules\Agent\Entities\Agent;
use Modules\Compony\Entities\ComponyTypePost;
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
use Modules\Wallet\Entities\Wallet;
use PDF;
use App\Exports\UsersExport;
use App\Exports\WalletExport;
use Maatwebsite\Excel\Facades\Excel;

class AddressController extends Controller
{

    public function postTest()
    {
        $token = getPostToken();
        $weight = 1000;
        $cost = 100000;

        $Price = (object)array(
            'ShopID' => 81728,
            'ToCityID' => 91,
            'ServiceTypeID' => 1,
            'PayTypeID' => 1,
            'Weight' => $weight,
            'ParcelValue' => $cost,
            'CollectNeed' => false,
            'NonStandardPackage' => false,
            'SMSService' => false,
        );

        $data = (object)array(
            'ClientOrderID' => '1',
            'CustomerNID' => '0702423408',
            'CustomerName' => "محمدجواد",
            'CustomerFamily' => "یوسفی",
            'CustomerPostalCode' => "4951919941",
            'CustomerMobile' => "09157076552",
            'CustomerAddress' => "تست",
            'ParcelContent' => "کیف",
            'IsReadyToAccept' => true,
            'Price' => $Price,
        );


        //$data = http_build_query($data);

        $cURLConnection4 = curl_init();
        $urlll4 = "https://ecommrestapi.post.ir/api/v1/Parcel/New";

        curl_setopt($cURLConnection4, CURLOPT_URL, $urlll4);
        curl_setopt($cURLConnection4, CURLOPT_SSL_CIPHER_LIST, 'DEFAULT@SECLEVEL=1');
        curl_setopt($cURLConnection4, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($cURLConnection4, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURLConnection4, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer $token",
            'Content-Type: application/json-patch+json'
        ));

        curl_setopt($cURLConnection4, CURLOPT_POSTFIELDS,
            json_encode($data,true));

        $response4 = curl_exec($cURLConnection4);

        if ($response4 === false) {
            return "CURL Error: " . curl_error($response4);
        }

        if (curl_errno($cURLConnection4)) {
            $error_msg = curl_error($cURLConnection4);
        }

        curl_close($cURLConnection4);
        if (isset($error_msg)) {
            return $error_msg;
        }
        $ress = json_decode($response4);
        return $ress;
    }

    public function postTest2()
    {
        $token = getPostToken();
        $weight = 1000;
        $cost = 100000;

        $Price = array(
            'ShopID' => 81728,
            'ToCityID' => 1,
            'ServiceTypeID' => 1,
            'PayTypeID' => 1,
            'Weight' => $weight,
            'ParcelValue' => $cost,
            'CollectNeed' => false,
            'NonStandardPackage' => false,
            'SMSService' => false,
        );

        $data = array(
            'ClientOrderID' => '1',
            'CustomerNID' => '0702423408',
            'CustomerName' => "محمدجواد",
            'CustomerFamily' => "یوسفی",
            'CustomerPostalCode' => "9195811837",
            'CustomerMobile' => "09157076552",
            'CustomerAddress' => "تست",
            'ParcelContent' => "کیف",
            'IsReadyToAccept' => true,
            'Price' => $Price,
        );


        $postdata = http_build_query(
            array(
                'ShopID' => 81728,
                'ToCityID' => 1,
                'ServiceTypeID' => 1,
                'PayTypeID' => 1,
                'Weight' => $weight,
                'ParcelValue' => $cost,
                'CollectNeed' => false,
                'NonStandardPackage' => false,
                'SMSService' => false,
                'bulk' => $data,
            )
        );

        $opts = array(
            "ssl" => array(
                'ciphers' => 'DEFAULT:!DH'
            ),
            'http' =>
                array(
                    'method' => 'POST',
                    'header' => "Content-Type: application/json-patch+json\r\n" .
                        "Authorization: Bearer $token\r\n",
                    'content' => $postdata
                )
        );

         $context = stream_context_create($opts);

        $result = file_get_contents('https://ecommrestapi.post.ir/api/v1/Parcel/Price', false, $context);
        $res = json_decode($result);
        return $res;


        $cURLConnection4 = curl_init();
        $urlll4 = "https://ecommrestapi.post.ir/api/v1/Parcel/Price";

        curl_setopt($cURLConnection4, CURLOPT_URL, $urlll4);
        curl_setopt($cURLConnection4, CURLOPT_SSL_CIPHER_LIST, 'DEFAULT@SECLEVEL=1');
        curl_setopt($cURLConnection4, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($cURLConnection4, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURLConnection4, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer $token",
            'Content-Type: application/json-patch+json'
        ));

        curl_setopt($cURLConnection4, CURLOPT_POSTFIELDS,
            json_encode($data));

        $response4 = curl_exec($cURLConnection4);

        if ($response4 === false) {
            return "CURL Error: " . curl_error($response4);
        }

        if (curl_errno($cURLConnection4)) {
            $error_msg = curl_error($cURLConnection4);
        }

        curl_close($cURLConnection4);
        if (isset($error_msg)) {
            return $error_msg;
        }
        $ress = json_decode($response4);
        return $ress;
    }


    public function addAddress(Request $request)
    {

        $cityname = City::find($request->cityId)->faName;
        $provicename = Province::find($request->provinceId)->faName;
        $countryname = Country::find($request->countryId)->faName;
        $areaname = '';
        if ($request->areaId != 0) {
            $areaname = Area::find($request->areaId)->faName;
        }

        $addressId = Address::create([
            'userId' => $request->user()->id,
            'cityId' => $request->cityId,
            'provinceId' => $request->provinceId,
            'countryId' => $request->countryId,
            'areaId' => $request->areaId,
            'address' => $request->address,
            'postCode' => $request->postCode,
            'name' => $request->name,
            'family' => $request->family,
            'phone' => $request->phone,
            'tel' => $request->tel,
            'email' => $request->email,
            'compony' => $request->compony,
            'totalAddress' => $countryname . " " . $provicename . " " . $cityname . " " . $request->address . " " . $request->name . " " . $request->family . " " . $request->phone . " " . $request->compony . " " . $areaname,
            'description' => $request->description,
            'enDescription' => $request->enDescription,
            'Default' => $request->Default,
            'type' => $request->type,
            'nationalCode' => $request->nationalCode,
            'senderOrgetter' => $request->senderOrgetter
        ])->id;
        $res = (object)[
            'address' => Address::with('countryName', 'provinceName', 'cityName')->find($addressId),
            'areaname' => $areaname,
        ];


        $result = (object)[
            'data' => $res,
        ];
        return Response::json($result, 200);
    }

    public function updateAddress(Request $request)
    {
        $cityname = City::find($request->cityId)->name;
        $provicename = Province::find($request->provinceId)->faName;
        $countryname = Country::find($request->countryId)->faName;
        $areaname = '';
        if ($request->areaId != 0) {
            $areaname = Area::find($request->areaId)->faName;
        }
        $type = false;
        if ($request->type == 'حقوقی') {
            $type = true;
        }
        Address::where('id', $request->id)->update([
            'city' => $request->cityId,
            'province' => $request->provinceId,
            'country' => $request->countryId,
            'areaId' => $request->areaId,
            'address' => $request->address,
            'postCode' => $request->postCode,
            'name' => $request->name,
            'family' => $request->family,
            'phone' => $request->phone,
            'email' => $request->email,
            'totalAddress' => $countryname . " " . $provicename . " " . $cityname . " " . $request->address . " " . $request->name . " " . $request->family . " " . $request->phone . " " . $request->compony . " " . $areaname,
            'description' => $request->description,
            'enDescription' => $request->enDescription,
            'Default' => $request->Default,
            'type' => $type,
            'nationalCode' => $request->nationalCode,
            'senderOrgetter' => $request->senderOrgetter
        ]);
        $result = (object)[
            'data' => ' با موفقیت انجام شد'
        ];
        return Response::json($result, 200);
    }

    public function deleteAddress($id)
    {

        Address::where('id', $id)->delete();
        $result = (object)[
            'data' => ' با موفقیت انجام شد'
        ];
        return Response::json($result, 200);
    }

    public function showAddress(Request $request, $numberpage, $srcinput = '')

    {
        $result = (object)[
            'data' => Address::with('countryName', 'provinceName', 'cityName')
                ->where('userId', $request->user()->id)
                ->where('totalAddress', 'like', '%' . $srcinput . '%')
                ->orderBy('id', 'DESC')
                ->skip(($numberpage - 1) * 10)->take(10)->get(),

        ];
        return Response::json($result, 200);
    }

    public function search(Request $request, $type, $srcinput = '')

    {

        $addresses = [];
        $addressList = Address::with('countryName', 'provinceName', 'cityName')
            ->where('userId', $request->user()->id)->where('senderOrgetter', $type)
            ->where('totalAddress', 'like', '%' . $srcinput . '%')
            ->orderBy('id', 'DESC')->get();
        foreach ($addressList as $address) {
            $areaname = '';
            if ($address->areaId != 0) {
                if (Area::find($address->areaId)) {
                    $areaname = Area::find($address->areaId)->faName;
                }
            }
            $res = (object)[
                'address' => $address,
                'areaname' => $areaname,
            ];
            array_push($addresses, $res);

        }
        $result = (object)[
            'data' => $addresses,

        ];
        return Response::json($result, 200);
    }

    public function userAdress($id)

    {
        $address = Address::with('countryName', 'provinceName', 'cityName')->find($id);
        $province = Province::all();
        $city = City::where('provinceId', $address->provinceId);
        $res = (object)[
            'address' => $address,
            'city' => $city,
            'province' => $province,


        ];
        $result = (object)[
            'data' => $res,


        ];
        return Response::json($result, 200);
    }

    public function defultAdress(Request $request, $type)
    {

        $address = Address::with('countryName', 'provinceName', 'cityName')->where('userId', $request->user()->id)->where('Default', 1)->first();

        $lastItem = InternalOrder::where('flag', $type)->orderBy('id', 'desc')->first();

        if ($lastItem) {
            $last = $lastItem->partnumber;

        } else {
            $last = 100001;
        }


        $agent = Agent::where('userId', $request->user()->id)->first();

        $countt = 0;
        $partCount = 0;
        if ($agent) {

            $totalpostIds = TotalPost::where('factorstatus', 'close')->where('agentId', $agent->id)->where('status', 'جمع آوری نشده')->pluck('id');

            $partCount = InternalOrder::whereIn('internalPostId', $totalpostIds)->count();

            $countt = TotalPost::where('factorstatus', 'close')->where('agentId', $agent->id)->where('status', 'جمع آوری نشده')->get()->count();
        }
        $areaname = '';
        if ($address and $address->areaId and Area::find($address->areaId)) {
            $areaname = Area::find($address->areaId)->faName;
        }


        $res = (object)[
            'address' => $address,
            'lastItem' => $last,
            'count' => $countt,
            'areaname' => $areaname,
            'partCount' => $partCount,
            'user' => Auth::user()
        ];

        $result = (object)[
            'data' => $res,
        ];
        return Response::json($result, 200);
    }


    public function test()
    {
        $data = [
            'foo' => 'bar'
        ];
        $pdf = PDF::loadView('print', $data);
        return $pdf->stream('documentsadasd.pdf');
    }

    public function testPreview()
    {

        return view('print');

    }

    public function rate(Request $request)
    {
        $parcel = [];
        // $dasd = $request->data;
        $dasd = json_decode($request->data, true);

        foreach ($dasd as $item) {
            array_push($parcel, array('weight' => $item['weight']));

        }


        $senderAddress = Address::find($request->addressId);
        $getterAddress = Address::find($request->getterAddressId);
        $senderCity = City::find($senderAddress->cityId);
        $getterCity = City::find($getterAddress->cityId);
        if ($senderCity and $senderCity->mahexNumber) {
            $sender = array('street' => $senderAddress->address,
                'city_code' => $senderCity->mahexNumber);
            $getter = array('street' => $getterAddress->address,
                'city_code' => $getterCity->mahexNumber);

            $data = array(
                'from_address' => $sender,
                'to_address' => $getter,
                'parcels' => $parcel,
            );

            $cURLConnection = curl_init();
            $mahexToken = env('MAHEX_TOKEN');

            curl_setopt($cURLConnection, CURLOPT_URL, 'http://api.mahex.com/v2/rates');
            curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, array(
                "Authorization: Basic $mahexToken",
                'Content-Type: application/json'
            ));
            curl_setopt($cURLConnection, CURLOPT_POSTFIELDS,
                json_encode($data));
            $phoneList = curl_exec($cURLConnection);
            curl_close($cURLConnection);
            return json_decode($phoneList);

        } else {
            $result = (object)[
                'data' => 'با عرض پوزش این سرویس درحال حاضر در شهر مبدا شما فعال نمی باشد.',
            ];
            return Response::json($result, 444);
        }
        // $parcel = [array('weight' => 3),array('weight' => 3)];


    }


    public function test1($totalId)
    {
        $allItems = [];
        $totalPost = TotalPost::find($totalId);

        if ($totalPost->typeSerId == 1) {
            $post = InternalPost::where('totalPostId', $totalId)->first();
            $parts = InternalOrder::where('internalPostId', $post->id)->get();
        } elseif ($totalPost->typeSerId == 2) {
            $post = ExternalPost::where('totalPostId', $totalId)->first();
            $parts = ExternalOrder::where('externalPostId', $post->id)->get();


        } elseif ($totalPost->typeSerId == 3) {
            $post = PeykPost::where('totalPostId', $totalId)->first();
            $parts = PeykOrder::where('externalPostId', $post->id)->get();
        } else {
            $post = HeavyPost::where('totalPostId', $totalId)->first();
            $parts = HeavyOrder::where('externalPostId', $post->id)->get();

        }

        $typeService = ComponyTypePost::find($totalPost->typeSerId);

        $senderAddress = Address::find($totalPost->addressId);

        $senderCity = City::find($senderAddress->cityId);
        $getterAddress = Address::find($totalPost->getterAddressId);
        $getterCity = City::find($getterAddress->cityId);

        foreach ($parts as $item) {
            $part = (object)[
                'id' => $item->partnumber,
                'weight' => $item->weight,
                'content' => $item->shipment,

            ];
            array_push($allItems, $part);
        }

        $data = array(
            'from_address' => array(
                'street' => $senderAddress->address,
                'city_code' => $senderCity->mahexNumber,
                'phone' => $senderCity->phone,
                'first_name' => $senderAddress->name,
                'last_name' => $senderAddress->family,
                'type' => 'LEGAL',
            ),
            'to_address' => array(
                'street' => $getterAddress->address,
                'city_code' => $getterCity->mahexNumber,
                'phone' => $getterAddress->phone,
                'first_name' => $getterAddress->name,
                'last_name' => $getterAddress->family,
                'type' => 'LEGAL',
            ),
            'parcels' => $allItems,
            'reference' => $totalPost->numberParcel,
        );


        $cURLConnection = curl_init();
        $mahexToken = env('MAHEX_TOKEN');

        curl_setopt($cURLConnection, CURLOPT_URL, 'http://api.mahex.com/v2/shipments');
        curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, array(
            "Authorization: Basic $mahexToken",
            'Content-Type: application/json'
        ));
        curl_setopt($cURLConnection, CURLOPT_POSTFIELDS,
            json_encode($data));
        $phoneList = curl_exec($cURLConnection);
        curl_close($cURLConnection);
        $response = json_decode($phoneList);

        return $response;
    }

    public function exeltest()
    {
        $export = new WalletExport([
            [
                'name' => 'Povilas',
                'surname' => 'Korop',
                'email' => 'povilas@laraveldaily.com',
                'twitter' => '@povilaskorop'
            ],
            [
                'name' => 'Taylor',
                'surname' => 'Otwell',
                'email' => 'taylor@laravel.com',
                'twitter' => '@taylorotwell'
            ]
        ]);

        return Excel::download($export, 'invoices.xlsx');
        return Excel::download(new WalletExport(), 'users-data.xlsx');
    }


    public function test2()
    {

        /*fa215394-d72b-40e4-81ef-61267bf8fcaa*/
        /*$cURLConnection3 = curl_init();
        $mahexToken = env('MAHEX_TOKEN');


        $urlll3 = "http://api.mahex.com/v2/shipments/ce011456-e9f9-470d-b19a-0ca3234f095a";

        curl_setopt($cURLConnection3, CURLOPT_URL, $urlll3);
        curl_setopt($cURLConnection3, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURLConnection3, CURLOPT_HTTPHEADER, array(
            "Authorization: Basic $mahexToken",
            'Content-Type: application/json'
        ));
        $phoneList = curl_exec($cURLConnection3);
        curl_close($cURLConnection3);
        $response = json_decode($phoneList);

        return $response;*/


        ///////////////////////////

        $totalId = 1845;


        $cURLConnection2 = curl_init();
        $mahexToken = env('MAHEX_TOKEN');

        $post = InternalPost::where('totalPostId', $totalId)->first();
        $parts = InternalOrder::where('internalPostId', $post->id)->get();

        $partCount = count($parts);

        $urlll = "http://api.mahex.com/v2/parcel-ids?count=$partCount";

        curl_setopt($cURLConnection2, CURLOPT_URL, $urlll);
        curl_setopt($cURLConnection2, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURLConnection2, CURLOPT_HTTPHEADER, array(
            "Authorization: Basic $mahexToken",
            'Content-Type: application/json'
        ));
        $phoneList = curl_exec($cURLConnection2);
        curl_close($cURLConnection2);
        $response = json_decode($phoneList);

        $mahexParts = $response->data;


        $allItems = [];
        $totalPost = TotalPost::find($totalId);

        if ($totalPost->typeSerId == 1) {
            $post = InternalPost::where('totalPostId', $totalId)->first();
            $parts = InternalOrder::where('internalPostId', $post->id)->get();
        } elseif ($totalPost->typeSerId == 2) {
            $post = ExternalPost::where('totalPostId', $totalId)->first();
            $parts = ExternalOrder::where('externalPostId', $post->id)->get();


        } elseif ($totalPost->typeSerId == 3) {
            $post = PeykPost::where('totalPostId', $totalId)->first();
            $parts = PeykOrder::where('externalPostId', $post->id)->get();
        } else {
            $post = HeavyPost::where('totalPostId', $totalId)->first();
            $parts = HeavyOrder::where('externalPostId', $post->id)->get();

        }

        $typeService = ComponyTypePost::find($totalPost->typeSerId);

        $senderAddress = Address::find($totalPost->addressId);

        $senderCity = City::find($senderAddress->cityId);
        $getterAddress = Address::find($totalPost->getterAddressId);
        $getterCity = City::find($getterAddress->cityId);

        foreach ($parts as $key => $item) {
            $part = (object)[
                'id' => $mahexParts[$key],
                'weight' => $item->weight,
                'content' => $item->shipment,
            ];
            InternalOrder::where('id', $item->id)->update([
                'servicePartnumber' => $mahexParts[$key]
            ]);
            array_push($allItems, $part);
        }

        $data = array(
            'from_address' => array(
                'street' => $senderAddress->address,
                'city_code' => $senderCity->mahexNumber,
                'phone' => '09114227969',
                'first_name' => $senderAddress->name,
                'last_name' => $senderAddress->family,
                'type' => 'LEGAL',
            ),
            'to_address' => array(
                'street' => $getterAddress->address,
                'city_code' => $getterCity->mahexNumber,
                'phone' => '09114227969',
                'first_name' => $getterAddress->name,
                'last_name' => $getterAddress->family,
                'type' => 'LEGAL',
            ),
            'parcels' => $allItems,
            'reference' => $totalPost->numberParcel,
        );


        $cURLConnection = curl_init();
        $mahexToken = env('MAHEX_TOKEN');

        curl_setopt($cURLConnection, CURLOPT_URL, 'http://api.mahex.com/v2/shipments');
        curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, array(
            "Authorization: Basic $mahexToken",
            'Content-Type: application/json'
        ));
        curl_setopt($cURLConnection, CURLOPT_POSTFIELDS,
            json_encode($data));
        $phoneList = curl_exec($cURLConnection);
        curl_close($cURLConnection);
        $response = json_decode($phoneList);

        $mahexUuid = $response->data->shipment_uuid;
        return $response;


        $cURLConnection3 = curl_init();
        $mahexToken = env('MAHEX_TOKEN');


        $urlll3 = "http://api.mahex.com/v2/shipments/$mahexUuid";

        curl_setopt($cURLConnection3, CURLOPT_URL, $urlll3);
        curl_setopt($cURLConnection3, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURLConnection3, CURLOPT_HTTPHEADER, array(
            "Authorization: Basic $mahexToken",
            'Content-Type: application/json'
        ));
        $phoneList = curl_exec($cURLConnection3);
        curl_close($cURLConnection3);
        $response = json_decode($phoneList);

        return $response;

    }

    public function testAfter($id)
    {
        return changeStatus($id);
        return $id;
    }

    public function changeState($id, Request $request)
    {
        if ($request->status == "OK") {

            TotalPost::where('id', $id)->update([
                'status' => "تحویل داده شد"
            ]);
            InternalOrder::where('internalPostId', $id)->update([
                'status' => "تحویل داده شد"
            ]);

        } elseif ($request->status == "RO") {
            TotalPost::where('id', $id)->update([
                'status' => "برگشتی"
            ]);
            InternalOrder::where('internalPostId', $id)->update([
                'status' => "برگشتی"
            ]);
        }

        $totalPost = TotalPost::find($id);
        if ($totalPost->isAfterRent or $totalPost->isCod) {
            changeStatus($id);
        }
    }

    public function deleteAddressa()
    {
        Address::query()->where('Default', 0)->delete();
    }


    public function importCity2($id)
    {

        /*postToken*/
        $cURLConnection = curl_init();
        $userName = env('POST_USER');
        $password = env('POST_PASSWORD');

        $urlll = "https://ecommrestapi.post.ir/api/v1/Users/Token";


        $data = array(
            'username' => $userName,
            'password' => $password,
            'grant_type' => "password",
        );

        curl_setopt($cURLConnection, CURLOPT_URL, $urlll);
        curl_setopt($cURLConnection, CURLOPT_SSL_CIPHER_LIST, 'DEFAULT@SECLEVEL=1');
        curl_setopt($cURLConnection, CURLOPT_CUSTOMREQUEST, 'POST');

        curl_setopt($cURLConnection, CURLOPT_POSTFIELDS,
            $data);

        $phoneList = curl_exec($cURLConnection);
        if (curl_errno($cURLConnection)) {
            $error_msg = curl_error($cURLConnection);
        }

        curl_close($cURLConnection);
        if (isset($error_msg)) {
            return $error_msg;
        }

        $response = json_decode($phoneList);

        return $response;

        $cURLConnection = curl_init();
        $mahexToken = env('POST_USER');

        $urlll = "https://ecommrestapi.post.ir/api/v1/BaseInfo/Provinces";

        curl_setopt($cURLConnection, CURLOPT_URL, $urlll);
        curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer $mahexToken"
        ));
        $phoneList = curl_exec($cURLConnection);
        curl_close($cURLConnection);
        $response = json_decode($phoneList);


        return $id;
    }


    public function importCity($id)
    {

        $token = getPostToken();


        $cURLConnection = curl_init();


        $urlll = "https://ecommrestapi.post.ir/api/v1/BaseInfo/City?ProvinceID=$id";


        curl_setopt($cURLConnection, CURLOPT_URL, $urlll);
        curl_setopt($cURLConnection, CURLOPT_SSL_CIPHER_LIST, 'DEFAULT@SECLEVEL=1');
        curl_setopt($cURLConnection, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer $token"
        ));


        $response = curl_exec($cURLConnection);
        curl_close($cURLConnection);
        $datas = json_decode($response)->Data;

        $relationProvince = (object)[
            1 => 8,
            2 => 30,
            3 => 1,
            4 => 12,
            5 => 16,
            6 => 4,
            7 => 10,
            8 => 17,
            9 => 14,
            10 => 18,
            11 => 25,
            12 => 13,
            13 => 24,
            14 => 29,
            15 => 3,
            16 => 2,
            17 => 27,
            18 => 19,
            19 => 21,
            20 => 23,
            21 => 7,
            22 => 20,
            23 => 26,
            24 => 28,
            25 => 31,
            26 => 15,
            27 => 6,
            28 => 22,
            29 => 11,
            30 => 9,
            31 => 5
        ];


        $localProvinceId = $relationProvince->$id;


        foreach ($datas as $item) {

            $hasCity = City::where('faName', 'like', '%' . $item->Title . '%')->where('provinceId', $localProvinceId)->first();

            if ($hasCity) {
                City::where('faName', 'like', '%' . $item->Title . '%')->where('provinceId', $localProvinceId)->update([
                    'postNumber' => $item->ID
                ]);
            } else {
                City::create([
                    'faName' => $item->Title,
                    'provinceId' => $localProvinceId,
                    'postNumber' => $item->ID,
                    'countryId' => 1,
                    'hasAgent' => 0,
                ]);
            }
        }
    }

    public function createStore($id)
    {

        $relationProvince = (object)[
            8 => 1,
            30 => 2,
            1 => 3,
            12 => 4,
            16 => 5,
            4 => 6,
            10 => 7,
            17 => 8,
            14 => 9,
            18 => 10,
            25 => 11,
            13 => 12,
            24 => 13,
            29 => 14,
            3 => 15,
            2 => 16,
            27 => 17,
            19 => 18,
            21 => 19,
            23 => 20,
            7 => 21,
            20 => 22,
            26 => 23,
            28 => 24,
            31 => 25,
            15 => 26,
            6 => 27,
            22 => 28,
            11 => 29,
            9 => 30,
            5 => 31
        ];


        //$cities = City::query()->whereNotNull('postNumber')->get();
        $city = City::query()->find($id);

        /*if (!$city->postShop) {*/

        $PostnodeID = 0;
        $CollectTypeID = 1;
        $PostUnitID = 0;

        $localProvince = $city->provinceId;

        $postProvince = $relationProvince->$localProvince;


        $token = getPostToken();


        /*get node post*/
        $cURLConnection = curl_init();
        $urlll = "https://ecommrestapi.post.ir/api/v1/BaseInfo/PostNode?CityID=$city->postNumber";

        curl_setopt($cURLConnection, CURLOPT_URL, $urlll);
        curl_setopt($cURLConnection, CURLOPT_SSL_CIPHER_LIST, 'DEFAULT@SECLEVEL=1');
        curl_setopt($cURLConnection, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer $token"
        ));
        $response = curl_exec($cURLConnection);
        curl_close($cURLConnection);
        $datas = json_decode($response);

        $PostnodeID = $datas->Data[0]->ID;


        /*get Post Unit*/
        $cURLConnection2 = curl_init();
        $urlll2 = "https://ecommrestapi.post.ir/api/v1/BaseInfo/PostUnits?provinceID=$postProvince";

        curl_setopt($cURLConnection2, CURLOPT_URL, $urlll2);
        curl_setopt($cURLConnection2, CURLOPT_SSL_CIPHER_LIST, 'DEFAULT@SECLEVEL=1');
        curl_setopt($cURLConnection2, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($cURLConnection2, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURLConnection2, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer $token"
        ));
        $response2 = curl_exec($cURLConnection2);
        curl_close($cURLConnection2);
        $datas2 = json_decode($response2);

        $provincePostUnits = $datas2->Data;


        if (count($provincePostUnits) == 1) {
            $PostUnitID = $provincePostUnits[0]->ID;
        } else {
            foreach ($provincePostUnits as $provincePostUnita) {
                $cURLConnection3 = curl_init();
                $urlll3 = "https://ecommrestapi.post.ir/api/v1/BaseInfo/PostUnitCity?PostUnitID=$provincePostUnita->ID";

                curl_setopt($cURLConnection3, CURLOPT_URL, $urlll3);
                curl_setopt($cURLConnection3, CURLOPT_SSL_CIPHER_LIST, 'DEFAULT@SECLEVEL=1');
                curl_setopt($cURLConnection3, CURLOPT_CUSTOMREQUEST, 'GET');
                curl_setopt($cURLConnection3, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($cURLConnection3, CURLOPT_HTTPHEADER, array(
                    "Authorization: Bearer $token"
                ));
                $response3 = curl_exec($cURLConnection3);
                curl_close($cURLConnection3);
                $cityLists = json_decode($response3)->Data;
                foreach ($cityLists as $cityLisa) {
                    if ($cityLisa->ID == $city->postNumber) {
                        $PostUnitID = $provincePostUnita->ID;
                    }
                }
            }
        }


        /*create shop*/

        $cURLConnection4 = curl_init();
        $urlll4 = "https://ecommrestapi.post.ir/api/v1/Shop/Register";

        curl_setopt($cURLConnection4, CURLOPT_URL, $urlll4);
        curl_setopt($cURLConnection4, CURLOPT_SSL_CIPHER_LIST, 'DEFAULT@SECLEVEL=1');
        curl_setopt($cURLConnection4, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($cURLConnection4, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURLConnection4, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer $token",
            'Content-Type: application/json-patch+json'
        ));

        $resssss = array(
            "PostnodeID" => $PostnodeID,
            "PostUnitID" => $PostUnitID
        );



        /*shiraz*/
        /*$data = array(
            "ShopID" => 81732,
            "PostalCode" => "7346146833",
            "Name" => "آواکس شیراز بداقی",
            "Phone" => "05137288530",
            "PostUnitID" => $PostUnitID,
            "CityID" => $city->postNumber,
            "PostnodeID" => $PostnodeID,
            "Enabled" => true,
            "ContractEndDate" => "2026-07-02T13:57:34.633Z",
            "CollectTypeID" => $CollectTypeID,
            "Mob" => "09178006083",
            "Email" => "pishrotarabar2@yahoo.com",
            "ManagerNationalID" => "2301377105",
            "ManagerNationalIDSerial" => "7g36370471",
            "ManagerBirthDate" => "1365/04/11"
        );*/

        /*bane*/
        /*$data = array(
            "ShopID" => 0,
            "PostalCode" => "6691877745",
            "Name" => "آواکس بانه",
            "Phone" => "05137288531",
            "PostUnitID" => $PostUnitID,
            "CityID" => $city->postNumber,
            "PostnodeID" => $PostnodeID,
            "Enabled" => true,
            "ContractEndDate" => "2026-07-02T13:57:34.633Z",
            "CollectTypeID" => $CollectTypeID,
            "Mob" => "09189754281",
            "Email" => "pishrotarabar3@yahoo.com",
            "ManagerNationalID" => "3732449408",
            "ManagerNationalIDSerial" => "9g2060507",
            "ManagerBirthDate" => "1360/05/04"
        );*/

        /*behbahan*/
        /*$data = array(
            "ShopID" => 0,
            "PostalCode" => "6361847042",
            "Name" => "آواکس بهبهان",
            "Phone" => "05137288533",
            "PostUnitID" => $PostUnitID,
            "CityID" => $city->postNumber,
            "PostnodeID" => $PostnodeID,
            "Enabled" => true,
            "ContractEndDate" => "2026-07-02T13:57:34.633Z",
            "CollectTypeID" => $CollectTypeID,
            "Mob" => "09305018430",
            "Email" => "pishrotarabar4@yahoo.com",
            "ManagerNationalID" => "1850475611",
            "ManagerNationalIDSerial" => "om21878079",
            "ManagerBirthDate" => "1380/12/07"
        );*/

        /*$data = array(
            "ShopID" => 81733,
            "PostalCode" => "9186183546",
            "Name" => "آواکس مشهد",
            "Phone" => "05137128764",
            "PostUnitID" => $PostUnitID,
            "CityID" => $city->postNumber,
            "PostnodeID" => $PostnodeID,
            "Enabled" => true,
            "ContractEndDate" => "2026-07-02T13:57:34.633Z",
            "CollectTypeID" => $CollectTypeID,
            "Mob" => "09153046958",
            "Email" => "pishrotarabar5@yahoo.com",
            "ManagerNationalID" => "0702421936",
            "ManagerNationalIDSerial" => "6R10679177",
            "ManagerBirthDate" => "1347/01/02"
        );*/



        /*zahedan*/

       /* $data = array(
            "ShopID" => 0,
            "PostalCode" => "9815656791",
            "Name" => "آواکس زاهدان",
            "Phone" => "05433216760",
            "PostUnitID" => $PostUnitID,
            "CityID" => $city->postNumber,
            "PostnodeID" => $PostnodeID,
            "Enabled" => true,
            "ContractEndDate" => "2026-07-02T13:57:34.633Z",
            "CollectTypeID" => $CollectTypeID,
            "Mob" => "09120466799",
            "Email" => "t.ertebat.h.n@gmail.com",
            "ManagerNationalID" => "0064378632",
            "ManagerNationalIDSerial" => "1R22982636",
            "ManagerBirthDate" => "1358/01/01"
        );*/

         /*$data = array(
            "ShopID" => 0,
            "PostalCode" => "5951738488",
            "Name" => "آواکس بوکان",
            "Phone" => "04446238896",
            "PostUnitID" => $PostUnitID,
            "CityID" => $city->postNumber,
            "PostnodeID" => $PostnodeID,
            "Enabled" => true,
            "ContractEndDate" => "2026-07-02T13:57:34.633Z",
            "CollectTypeID" => $CollectTypeID,
            "Mob" => "09140300035",
            "Email" => "kaveh.gholami333@gmail.com",
            "ManagerNationalID" => "2930079851",
            "ManagerNationalIDSerial" => "7G45059215",
            "ManagerBirthDate" => "1370/01/15"
        );*/

        /*$data = array(
            "ShopID" => 0,
            "PostalCode" => "4741965675",
            "Name" => "آواکس بابلسر",
            "Phone" => "01135272729",
            "PostUnitID" => $PostUnitID,
            "CityID" => $city->postNumber,
            "PostnodeID" => $PostnodeID,
            "Enabled" => true,
            "ContractEndDate" => "2026-07-02T13:57:34.633Z",
            "CollectTypeID" => $CollectTypeID,
            "Mob" => "09119195110",
            "Email" => "sdkhsrwyabwalfdl@gmail.com  ",
            "ManagerNationalID" => "0050846493",
            "ManagerNationalIDSerial" => "8G31855544",
            "ManagerBirthDate" => "1336/01/12"
        );*/


        $data = array(
            "ShopID" => 0,
            "PostalCode" => "5819615447",
            "Name" => "آواکس خوی",
            "Phone" => "04436349966",
            "PostUnitID" => $PostUnitID,
            "CityID" => $city->postNumber,
            "PostnodeID" => $PostnodeID,
            "Enabled" => true,
            "ContractEndDate" => "2026-07-02T13:57:34.633Z",
            "CollectTypeID" => $CollectTypeID,
            "Mob" => "09100533005",
            "Email" => "vahideh.jalilzadeh@yahoo.com",
            "ManagerNationalID" => "2790159807",
            "ManagerNationalIDSerial" => "2G21067770",
            "ManagerBirthDate" => "1369/07/06"
        );

       /* $data = array(
            "ShopID" => 0,
            "PostalCode" => "6681896322",
            "Name" => "آواکس  سقز",
            "Phone" => "08736273168",
            "PostUnitID" => $PostUnitID,
            "CityID" => $city->postNumber,
            "PostnodeID" => $PostnodeID,
            "Enabled" => true,
            "ContractEndDate" => "2026-07-02T13:57:34.633Z",
            "CollectTypeID" => $CollectTypeID,
            "Mob" => "09931458404",
            "Email" => "shahryarkurd79@gmail.com",
            "ManagerNationalID" => "3750568340",
            "ManagerNationalIDSerial" => "4G15780746",
            "ManagerBirthDate" => "1379/04/31"
        );*/



        curl_setopt($cURLConnection4, CURLOPT_POSTFIELDS,
            json_encode($data));

        $response4 = curl_exec($cURLConnection4);

        if ($response4 === false) {
            return "CURL Error: " . curl_error($response4);
        }

        if (curl_errno($cURLConnection4)) {
            $error_msg = curl_error($cURLConnection4);
        }

        curl_close($cURLConnection4);
        if (isset($error_msg)) {
            return $error_msg;
        }

        $ress = json_decode($response4);
        return $ress;

        /*}*/


        /*
         * CollectTypeID = 1
         *
         * */

    }

    public function deleteTest($item)
    {

        $serviceType = "پیشکرایه";
        $totalPost = TotalPost::where('numberParcel', $item)->first();
        $agentId = Agent::find($totalPost->agentId);
        $avaexPrice = Wallet::query()->where('numberParcel', $item)->where('userId', 15)->sum('amount');
        $userPrice = Wallet::query()->where('numberParcel', $item)->where('userId', $totalPost->userId)->where('type', 'برداشت')->sum('amount');
        $agentPrice = Wallet::query()->where('numberParcel', $item)->where('userId', $agentId->userId)->where('type', 'واریز')->sum('amount');
        $servicePrice = Wallet::query()->where('numberParcel', $item)->where('userId', $totalPost->componyId)->sum('amount');

        $walletId = Wallet::create([
            'userId' => $totalPost->userId,
            'amount' => $userPrice,
            'cash' => User::find($totalPost->userId)->wallet + $userPrice,
            'numberParcel' => $totalPost->numberParcel,
            'componyId' => $totalPost->componyId,
            'type' => 'واریز',
            'status' => 'افزایش اعتبار',
            'serviceType' => $serviceType,
            'description' => 'بابت لغو مرسوله' . $totalPost->numberParcel . 'به حساب شما واریز شد.',
        ])->id;


        User::where('id', $totalPost->userId)->update([
            'wallet' => User::find($totalPost->userId)->wallet + $userPrice,
        ]);


        Wallet::create([
            'userId' => 15,
            'amount' => $avaexPrice,
            'cash' => User::find(15)->wallet - $avaexPrice,
            'fatherId' => $walletId,
            'numberParcel' => $totalPost->numberParcel,
            'type' => 'برداشت',
            'status' => 'کسر از کیف پول',
            'componyId' => $totalPost->componyId,
            'description' => 'بابت لغو مرسوله' . $totalPost->numberParcel . 'از حساب شما برداشت شد.',
            'serviceType' => $serviceType,

        ]);

        User::where('id', 15)->update([
            'wallet' => User::find(15)->wallet - $avaexPrice,
        ]);


        Wallet::create([
            'userId' => $totalPost->componyId,
            'amount' => $servicePrice,
            'cash' => User::find($totalPost->componyId)->wallet - $servicePrice,
            'fatherId' => $walletId,
            'numberParcel' => $totalPost->numberParcel,
            'type' => 'برداشت',
            'status' => 'بابت لغو مرسوله' . $totalPost->numberParcel . 'از حساب شما برداشت شد.',
            'componyId' => $totalPost->componyId,
            'description' => 'بابت لغو مرسوله' . $totalPost->numberParcel . 'از حساب شما برداشت شد.',
            'serviceType' => $serviceType,

        ]);

        User::where('id', $totalPost->componyId)->update([
            'wallet' => User::find($totalPost->componyId)->wallet - $servicePrice,
        ]);


        Wallet::create([
            'userId' => $agentId->userId,
            'amount' => $agentPrice,
            'cash' => User::find($agentId->userId)->wallet - $agentPrice,
            'fatherId' => $walletId,
            'numberParcel' => $totalPost->numberParcel,
            'type' => 'برداشت',
            'status' => 'کسر از کیف پول',
            'componyId' => $totalPost->componyId,
            'description' => 'بابت لغو مرسوله' . $totalPost->numberParcel . 'از حساب شما برداشت شد.',
            'serviceType' => $serviceType,

        ]);

        User::where('id', $agentId->userId)->update([
            'wallet' => User::find($agentId->userId)->wallet - $agentPrice,
        ]);


        return $userPrice;

    }


    public function PostPriceTest()
    {

        $token = getPostToken();
        $weight = 1000;
        $cost = 100000;

        $data = array(
            'ShopID' => 81728,
            'ToCityID' => 1,
            'ServiceTypeID' => 1,
            'PayTypeID' => 1,
            'Weight' => $weight,
            'ParcelValue' => $cost,
            'CollectNeed' => false,
            'NonStandardPackage' => false,
            'SMSService' => false,
        );


        $cURLConnection4 = curl_init();
        $urlll4 = "https://ecommrestapi.post.ir/api/v1/Parcel/Price";

        curl_setopt($cURLConnection4, CURLOPT_URL, $urlll4);
        curl_setopt($cURLConnection4, CURLOPT_SSL_CIPHER_LIST, 'DEFAULT@SECLEVEL=1');
        curl_setopt($cURLConnection4, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($cURLConnection4, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURLConnection4, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer $token",
            'Content-Type: application/json-patch+json'
        ));

        curl_setopt($cURLConnection4, CURLOPT_POSTFIELDS,
            json_encode($data));

        $response4 = curl_exec($cURLConnection4);

        if ($response4 === false) {
            return "CURL Error: " . curl_error($response4);
        }

        if (curl_errno($cURLConnection4)) {
            $error_msg = curl_error($cURLConnection4);
        }

        curl_close($cURLConnection4);
        if (isset($error_msg)) {
            return $error_msg;
        }
        $ress = json_decode($response4);
        return $ress;
    }


}
