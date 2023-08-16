<?php

namespace Modules\InternalPost\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use Modules\Address\Entities\Address;
use Modules\Address\Entities\City;
use Modules\Address\Entities\Area;
use Modules\Agent\Entities\Agent;
use Modules\Compony\Entities\ComponyTypePost;
use Modules\InternalPost\Entities\InternalOnlineOrder;
use Modules\InternalPost\Entities\InternalOrder;
use Modules\InternalPost\Entities\InternalPost;
use Modules\InternalPost\Entities\InternalPostOnline;
use Modules\InternalPost\Entities\InternalPostType;
use Modules\TotalPost\Entities\TotalPost;
use Modules\User\Entities\User;
use Modules\Wallet\Entities\Wallet;
use Modules\Wallet\Entities\PostupWallet;

class InternalPostController extends Controller
{

    public function add(Request $request)
    {

        $areaAgent = 0;
        $senderAddress = Address::find($request->addressId);

        $agent = Agent::where('cityId', $senderAddress->cityId)->where('status', "تایید")->first();
        if ($agent) {
            if ($senderAddress->areaId != 0) {
                $area = Area::find($senderAddress->areaId);
                if ($area->agentId != 0) {
                    $areaAgent = 1;
                    $agentId = $area->agentId;
                } else {
                    $areaAgent = 0;
                }
            } else {
                $areaAgent = 1;
                $agentId = $agent->id;
            }

        }

        if ($areaAgent) {


            $dasd = $request->data;

            $TAX = 0;
            $printFactor = 0;
            $totalprintFactor = 0;
            $packaging = 0;
            $totalCollector = 0;
            $totalCost = 20000;
            $Insurance = 0;
            $packaginga = 0;
            $totalpackaging = 0;
            $hasNotifRequest = 0;
            $packaginPrice = 0;
            $realCollectorPrice = [];

            foreach ($dasd as $key => $item) {

                $collector = 290423 * (exp(0.0349 * $item['massUnround']));


                /*if (($key % 4) == 0) {
                    $realCollectorPrice[] = $collector;
                    $totalCollector += $collector;
                } elseif (($key % 4) == 1) {
                    $totalCollector += ($collector * 0.9);
                    $realCollectorPrice[] = $collector* 0.9;
                } elseif (($key % 4) == 2) {
                    $totalCollector += ($collector*0.8);
                    $realCollectorPrice[] = $collector* 0.8;
                } elseif (($key % 4) == 3) {
                    $totalCollector += ($collector*0.7);
                    $realCollectorPrice[] = $collector* 0.7;
                }*/


                if (($key % 10) == 0) {
                    $realCollectorPrice[] = $collector;
                    $totalCollector += $collector;
                } elseif (($key % 10) == 1) {
                    $totalCollector += ($collector * 0.9);
                    $realCollectorPrice[] = $collector * 0.9;
                } elseif (($key % 10) == 2) {
                    $totalCollector += ($collector * 0.8);
                    $realCollectorPrice[] = $collector * 0.8;
                } elseif (($key % 10) == 3) {
                    $totalCollector += ($collector * 0.7);
                    $realCollectorPrice[] = $collector * 0.7;
                } elseif (($key % 10) == 4) {
                    $totalCollector += ($collector * 0.6);
                    $realCollectorPrice[] = $collector * 0.6;
                } elseif (($key % 10) == 5) {
                    $totalCollector += ($collector * 0.5);
                    $realCollectorPrice[] = $collector * 0.5;
                } elseif (($key % 10) == 6) {
                    $totalCollector += ($collector * 0.4);
                    $realCollectorPrice[] = $collector * 0.4;
                } elseif (($key % 10) == 7) {
                    $totalCollector += ($collector * 0.3);
                    $realCollectorPrice[] = $collector * 0.3;
                } elseif (($key % 10) == 8) {
                    $totalCollector += ($collector * 0.2);
                    $realCollectorPrice[] = $collector * 0.2;
                } elseif (($key % 10) == 9) {
                    $totalCollector += ($collector * 0.1);
                    $realCollectorPrice[] = $collector * 0.1;
                }


                if ($request->needKarton) {

                    $packaginga = 54000 + (42283 * $item['massUnround']);
                    $totalpackaging += $packaginga;

//                    $TAX += $packaginga;

                }
                $packaginPrice = $packaginPrice + $packaginga;
                if ($item['cost'] > 10000000) {
                    $Insurance += $item['cost'] * 0.002;
                } else {
                    $Insurance += $totalCost;
                }
            }

            /*$TAX += $totalCollector + $totalpackaging + $Insurance;*/
            $TAX += $totalCollector + $Insurance;
            if ($request->printFactor || $request->needKarton) {
                $printFactor = 35000;
                $TAX += ($request->totalNumber * 35000);
                $totalprintFactor = ($request->totalNumber * 35000);

            }
            if ($request->hasNotifRequest) {
                $TAX += 10000;
                $hasNotifRequest = 10000;
            }


            $lastItem = TotalPost::orderBy('id', 'desc')->first();
            if ($lastItem) {
                $numberParcel = $lastItem->numberParcel + 1;
            } else {
                $numberParcel = 100001;
            }

            $MethodPayment = $request->MethodPayment;
            $Payable = $request->price + ($request->price * 0.12) + $TAX + ($TAX * 0.09);
            $isAfterRent = 0;
            if ($request->isAfterRent) {
                $MethodPayment = "پس کرایه";
                $isAfterRent = 1;
            }
            $isCod = 0;
            if ($request->isCod) {
                $MethodPayment = "پرداخت در محل-COD";
                $isCod = 1;
            }

            $totalPostId = TotalPost::create([
                'userId' => $request->user()->id,
                'agentId' => $agentId,
                'numberParcel' => $numberParcel,
                'addressId' => $request->addressId,
                'getterAddressId' => $request->getterAddressId,
                'typeSerId' => 1,
                'componyId' => $request->componyId,
                'componyTypeId' => $request->componyTypeId,
                'componyServicesId' => $request->componyServicesId,
                'printFactor' => $totalprintFactor,
                'discountCouponCode' => $request->discountCouponCode,
                'hasNotifRequest' => $hasNotifRequest,
                'RequestPrintAvatar' => $request->RequestPrintAvatar,
                'status' => 'جمع آوری نشده',
                'MethodPayment' => $MethodPayment,
                'factorstatus' => 'open',
                'Freight' => $request->price,
                'serviceInPlace' => $request->serviceInPlace,
                'Packaging' => $totalpackaging,
                'collector' => $totalCollector,
                'Payable' => $Payable,
                'realPayable' => $Payable + ($packaginPrice * 1.09),
                'ServicesAt' => $request->price * 0.12,
                'TAX' => ($TAX * 0.09),
                'Insurance' => $Insurance,
                'amountServices' => $TAX,
                'isAfterRent' => $isAfterRent,
                'isCod' => $isCod,
                'totalNumber' => $request->totalNumber,
                'totalCollectiveWeight' => $request->totalCollectiveWeight,
                'totalGrossWeight' => $request->totalGrossWeight,
                'totalWeightPayable' => $request->totalWeightPayable,
                'totalCost' => $request->totalCost,
                'amountCOD' => $request->amountCOD,
            ])->id;
            $typeService = ComponyTypePost::find($request->componyTypeId);
            $PostId = InternalPost::create([
                'totalPostId' => $totalPostId,
                'typeSerId' => 1,
                'componyId' => $request->componyId,
                'componyTypeId' => 1

            ])->id;


            $collectorPart = $totalCollector / $request->totalNumber;
            foreach ($dasd as $key => $item) {
                $cost = 20000;

                if ($request->needKarton) {
                    $packaging = 54000 + (42283 * $item['massUnround']);
                }
                $lastItem = InternalOrder::where('flag', 'n')->orderBy('id', 'desc')->first();
                if ($lastItem) {
                    $partNumber = $lastItem->partnumber + 1;
                } else {
                    $partNumber = 100001;
                }

                if ($item['cost'] > 10000000) {
                    $cost = $item['cost'] * 0.002;
                }


                $itemCodPrice = 0;
                if (isset($item['amountCODD'])) {
                    $itemCodPrice = $item['amountCODD'];
                }

                InternalOrder::create([
                    'internalPostId' => $PostId,
                    'partnumber' => $partNumber,
                    'shipment' => $item['content'],
                    'weight' => $item['weight'],
                    'width' => $item['widthh'],
                    'height' => $item['heightt'],
                    'lenght' => $item['lengtha'],
                    'cost' => $cost,
                    'value' => $item['cost'],
                    'boxnumber' => 1,
                    'typeId' => $item['packaging'],
                    'mass' => $item['mass'],
                    'massUnround' => $item['massUnround'],
                    'needKarton' => $request->needKarton,
                    'insuranceId' => 1,
                    'getterAddressId' => $request->getterAddressId,
                    'serviceId' => 1,
                    'status' => 'جمع آوری نشده',
                    'print' => $printFactor,
                    'packaging' => $packaging,
                    'collector' => $collectorPart,
                    'realCollector' => $realCollectorPrice[$key],
                    'amountCOD' => $itemCodPrice
                ]);

            }

            $result = (object)[
                'data' => $totalPostId,
            ];
            return Response::json($result, 200);


        } else {
            $result = (object)[
                'data' => 'با عرض پوزش این سرویس درحال حاضر در شهر یا منطقه مبدا شما فعال نمی باشد.',
            ];
            return Response::json($result, 444);

        }


    }

    public function bulkAdd(Request $request)
    {


        $senderAddress = Address::find($request->addressId);
        $agent = Agent::where('cityId', $senderAddress->cityId)->first();
        $areaAgent = 0;
        if ($agent) {
            if (Area::find($senderAddress->areaId) and $senderAddress->areaId != 0) {
                $area = Area::find($senderAddress->areaId);
                if ($area->agentId != 0) {
                    $areaAgent = 1;
                    $agentId = $area->agentId;
                } else {
                    $areaAgent = 0;
                }
            } else {
                $areaAgent = 1;
                $agentId = $agent->id;
            }

        }
        if ($areaAgent) {
            $totalId = [];

            $dasd = $request->data;
            $TAX = 0;
            $printFactor = 0;
            $totalprintFactor = 0;
            $totalCollector = 0;
            $Insurance = 0;
            $hasNotifRequest = 0;

            $totalWeght = 0;


            foreach ($dasd as $key => $item) {
                $totalWeght = $totalWeght + $item['massUnround'];
                $collector = 290423 * (exp(0.0349 * $item['massUnround']));

                if (($key % 4) == 0) {
                    $totalCollector += $collector;
                } elseif (($key % 4) == 1) {
                    $totalCollector += $collector * 0.9;
                } elseif (($key % 4) == 2) {
                    $totalCollector += $collector * 0.8;
                } elseif (($key % 4) == 3) {
                    $totalCollector += $collector * 0.7;
                }
            }
            $collectorPart = $totalCollector / count($dasd);

            /*if($totalWeght<40){
                $collectorT = 290423 * (exp(0.0249 * $totalWeght));
            }else{
                $collectorT = 290423 * (exp(0.0195 * $totalWeght));
            }*/
            /*$collectorT = 290423 * (exp(0.0349 * $item['massUnround']));

            $collectorPart = $collectorT / count($dasd);*/

            $collectorT = 290423 * (exp(0.0349 * $totalWeght));

            $collectorPart = $collectorT / count($dasd);


            foreach ($dasd as $key => $item) {
                $TAX = 0;
                $TAX += $collectorPart;
                $Insurance = 0;
                $packaginga = 0;
                $packaginPrice = 0;


                if (isset($item['needKarton']) and $item['needKarton']) {
                    $packaginga = 54000 + (42283 * $item['massUnround']);
                    //$TAX += $packaginga;
                }
                $packaginPrice = $packaginPrice + $packaginga;

                /*if ($item['cost'] > env('UNIT_INSURANCE')) {
                    $Insurance += ($item['cost'] * env('INSURANCE')) / 10000000;
                } else {
                    $Insurance += env('INSURANCE');
                }*/


                if ($item['cost'] > 10000000) {
                    $Insurance += $item['cost'] * 0.002;
                } else {
                    $Insurance += 20000;
                }
                $TAX += $Insurance;
                $lastItem = TotalPost::orderBy('id', 'desc')->first();
                if ($lastItem) {
                    $numberParcel = $lastItem->numberParcel + 1;
                } else {
                    $numberParcel = 100001;
                }

                if ($request->printFactor) {
                    $printFactor = env('PRINT_COST');
                    $totalprintFactor = $printFactor;
                    $TAX += $totalprintFactor;
                }
                if ($request->hasNotifRequest) {
                    $TAX += env('SMS_COST');
                    $hasNotifRequest = env('SMS_COST');
                }

                //$Payable = $item['price'] + $Insurance + ($item['price'] * 0.12) + $TAX + ($TAX * 0.09);
                $Payable = $item['price'] + ($item['price'] * 0.12) + $TAX + ($TAX * 0.09);

                $totalWeightPayable = $item['weight'];
                if ($item['mass'] > $item['weight']) {
                    $totalWeightPayable = $item['weight'];
                }

                $MethodPayment = $request->MethodPayment;

                $isAfterRent = 0;
                if ($request->isAfterRent) {
                    $MethodPayment = "پس کرایه";
                    $isAfterRent = 1;
                }
                $isCod = 0;
                if ($request->isCod) {
                    $MethodPayment = "پرداخت در محل-COD";
                    $isCod = 1;
                }

                $serviceInPlace = 0;

                if (isset($item['serviceInPlace'])) {
                    $serviceInPlace = $item['serviceInPlace'];
                }

                $CODPrice = 0;
                if (isset($item['CODPrice'])) {
                    $CODPrice = $item['CODPrice'];
                }


                $totalPostId = TotalPost::create([
                    'userId' => $request->user()->id,
                    'agentId' => $agentId,
                    'numberParcel' => $numberParcel,
                    'addressId' => $request->addressId,
                    'getterAddressId' => $item['getterAddressId'],
                    'typeSerId' => 1,
                    'componyId' => $request->componyId,
                    'componyTypeId' => $request->componyTypeId,
                    'componyServicesId' => $request->componyServicesId,
                    'printFactor' => $totalprintFactor,
                    //'discountCouponCode' => $item['discountCouponCode'],
                    'hasNotifRequest' => $hasNotifRequest,
                    //'RequestPrintAvatar' => $item['RequestPrintAvatar'],
                    'status' => 'جمع آوری نشده',
                    'serviceInPlace' => $serviceInPlace,
                    'factorstatus' => 'open',
                    'Freight' => $item['price'],
                    'Packaging' => $packaginPrice,
                    'collector' => $collectorPart,
                    'Payable' => $Payable,
                    'realPayable' => $Payable + ($packaginPrice * 1.09),
                    'ServicesAt' => ($item['price'] * 0.12),
                    'TAX' => ($TAX * 0.09),
                    'Insurance' => $Insurance,
                    'amountServices' => $TAX,
                    'totalNumber' => 1,
                    'totalCollectiveWeight' => $item['weight'],
                    'totalGrossWeight' => $item['mass'],
                    'totalWeightPayable' => $totalWeightPayable,
                    'totalCost' => $item['cost'],
                    /*'amountCOD' => $item['CODPrice'] * (($item['CODPrice'] * 2) / 100),*/
                    'amountCOD' => $CODPrice,
                    'MethodPayment' => $MethodPayment,
                    'isAfterRent' => $isAfterRent,
                    'isCod' => $isCod,
                ])->id;
                $typeService = ComponyTypePost::find($request->componyTypeId);
                $PostId = InternalPost::create([
                    'totalPostId' => $totalPostId,
                    'typeSerId' => 1,
                    'componyId' => $request->componyId,
                    'componyTypeId' => 1

                ])->id;

                $lastItem = InternalOrder::where('flag', 'b')->orderBy('id', 'desc')->first();
                if ($lastItem) {
                    $partNumber = $lastItem->partnumber + 1;
                } else {
                    $partNumber = 100001;
                }

                $cost = 20000;
                if ($item['cost'] > 10000000) {
                    $cost = $item['cost'] * 0.002;
                }
                $needKarton = 0;
                if (isset($item['needKarton'])) {
                    $needKarton = $item['needKarton'];
                }

                InternalOrder::create([
                    'internalPostId' => $PostId,
                    'partnumber' => $partNumber,
                    'shipment' => $item['content'],
                    'weight' => $item['weight'],
                    'width' => $item['widthh'],
                    'height' => $item['heightt'],
                    'lenght' => $item['lengtha'],
                    'cost' => $cost,
                    'boxnumber' => 1,
                    'typeId' => $item['packaging'],
                    'value' => $item['cost'],
                    'mass' => $item['mass'],
                    'massUnround' => $item['massUnround'],
                    'needKarton' => $needKarton,
                    'insuranceId' => 1,
                    'getterAddressId' => $item['getterAddressId'],
                    'serviceId' => 1,
                    'status' => 'جمع آوری نشده',
                    'print' => $printFactor,
                    'packaging' => $packaginPrice,
                    'collector' => $collectorPart,
                    'realCollector' => $collectorPart,
                    'amountCOD' => $CODPrice,
                    'flag' => 'b',

                ]);
                array_push($totalId, $totalPostId);
            }


            $result = (object)[
                'data' => $totalId,
            ];
            return Response::json($result, 200);


        } else {
            $result = (object)[
                'data' => 'با عرض پوزش این سرویس درحال حاضر در شهر مبدا شما فعال نمی باشد.',
            ];
            return Response::json($result, 444);

        }


    }

    public function mahexRate(Request $request)
    {

        $parcel = [];
        $dasd = $request->data;
        //$dasd = json_decode($request->data, true);

        foreach ($dasd as $item) {
            array_push($parcel, array('weight' => $item['weight']));

        }
        $senderAddress = Address::find($request->addressId['id']);
        $getterAddress = Address::find($request->getterAddressId['id']);
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
            $response = json_decode($phoneList);
            $result = (object)[
                'quote' => round($response->data->rate->amount * (100 / 85)),
                'method_name' => "سرویس اکسپرس ماهکس",
            ];
            $arrayResult = [$result];
            return Response::json($arrayResult, 200);

        } else {
            $result = (object)[
                'data' => 'با عرض پوزش این سرویس درحال حاضر در شهر مبدا شما فعال نمی باشد.',
            ];
            return Response::json($result, 444);
        }
        // $parcel = [array('weight' => 3),array('weight' => 3)];


    }


    public function postRate(Request $request)
    {
        $dasd = $request->data;
        //$dasd = json_decode($request->data, true);


        $senderAddress = Address::find($request->addressId['id']);
        $getterAddress = Address::find($request->getterAddressId['id']);

        $senderCity = City::find($senderAddress->cityId);
        $getterCity = City::find($getterAddress->cityId);

        $pishtazKerayetotalPrice = 0;
        $sefareshiKerayetotalPrice = 0;
        $vijeKerayetotalPrice = 0;

        $pishtazKerayeArrayPrice = [];
        $sefareshiKerayeArrayPrice = [];
        $vijeKerayeArrayPrice = [];


        if ($senderCity and $senderCity->postShop and $getterCity and $getterCity->postNumber) {

            $token = getPostToken();

            $sender = $senderCity->postShop;
            $getter = $getterCity->postNumber;

            if ($request->postmethod) {
                $postMethod = $request->postmethod - 6;

                foreach ($dasd as $item) {
                    $weight = $item['weight'] * 1000;
                    $cost = $item['cost'];
                    $urlll = "https://ecommrestapi.post.ir/api/v1/Parcel/Price";


                    $data1 = array(
                        'ShopID' => $sender,
                        'ToCityID' => $getter,
                        'ServiceTypeID' => $postMethod,
                        'PayTypeID' => 1,
                        'Weight' => $weight,
                        'ParcelValue' => $cost,
                        'CollectNeed' => false,
                        'NonStandardPackage' => false,
                        'SMSService' => false,
                    );



                    $cURLConnection1 = curl_init();
                    curl_setopt($cURLConnection1, CURLOPT_URL, $urlll);
                    curl_setopt($cURLConnection1, CURLOPT_SSL_CIPHER_LIST, 'DEFAULT@SECLEVEL=1');
                    curl_setopt($cURLConnection1, CURLOPT_CUSTOMREQUEST, 'POST');
                    curl_setopt($cURLConnection1, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($cURLConnection1, CURLOPT_HTTPHEADER, array(
                        "Authorization: Bearer $token",
                        'Content-Type: application/json-patch+json'
                    ));
                    curl_setopt($cURLConnection1, CURLOPT_POSTFIELDS,
                        json_encode($data1));
                    $response1 = curl_exec($cURLConnection1);
                    curl_close($cURLConnection1);
                    $ress1 = json_decode($response1);
                    $pistazPrice = 0;
                    if ($ress1->ResMsg == "موفق") {
                        $pishtazKerayetotalPrice = $pishtazKerayetotalPrice + $ress1->Data->TotalPrice;
                        $pistazPrice = $ress1->Data->TotalPrice;
                    }
                    $pishtazKerayeArrayPrice[] = $pistazPrice;


                }

                $result1 = (object)[
                    'id' => $request->postmethod,
                    'pishtazKerayeArrayPrice' => $pishtazKerayeArrayPrice,
                    'quote' => $pishtazKerayetotalPrice,
                    'method_name' => "پیشتاز اداره پست",
                    'image' => "https://back.avaex.ir/common/psotlogo.jpg",
                ];
                $data = [$result1];
                $result = (object)[
                    'data' => $data,
                ];
                return Response::json($result1, 200);
            } else {


                foreach ($dasd as $item) {
                    $weight = $item['weight'] * 1000;
                    $cost = $item['cost'];
                    $urlll = "https://ecommrestapi.post.ir/api/v1/Parcel/Price";


                    $data1 = array(
                        'ShopID' => $sender,
                        'ToCityID' => $getter,
                        'ServiceTypeID' => 1,
                        'PayTypeID' => 1,
                        'Weight' => $weight,
                        'ParcelValue' => $cost,
                        'CollectNeed' => false,
                        'NonStandardPackage' => false,
                        'SMSService' => false,
                    );
                    $data2 = array(
                        'ShopID' => $sender,
                        'ToCityID' => $getter,
                        'ServiceTypeID' => 2,
                        'PayTypeID' => 1,
                        'Weight' => $weight,
                        'ParcelValue' => $cost,
                        'CollectNeed' => false,
                        'NonStandardPackage' => false,
                        'SMSService' => false,
                    );
                    $data3 = array(
                        'ShopID' => $sender,
                        'ToCityID' => $getter,
                        'ServiceTypeID' => 3,
                        'PayTypeID' => 1,
                        'Weight' => $weight,
                        'ParcelValue' => $cost,
                        'CollectNeed' => false,
                        'NonStandardPackage' => false,
                        'SMSService' => false,
                    );


                    $cURLConnection1 = curl_init();
                    curl_setopt($cURLConnection1, CURLOPT_URL, $urlll);
                    curl_setopt($cURLConnection1, CURLOPT_SSL_CIPHER_LIST, 'DEFAULT@SECLEVEL=1');
                    curl_setopt($cURLConnection1, CURLOPT_CUSTOMREQUEST, 'POST');
                    curl_setopt($cURLConnection1, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($cURLConnection1, CURLOPT_HTTPHEADER, array(
                        "Authorization: Bearer $token",
                        'Content-Type: application/json-patch+json'
                    ));
                    curl_setopt($cURLConnection1, CURLOPT_POSTFIELDS,
                        json_encode($data1));
                    $response1 = curl_exec($cURLConnection1);
                    curl_close($cURLConnection1);
                    $ress1 = json_decode($response1);
                    $pistazPrice = 0;
                    if ($ress1->ResMsg == "موفق") {
                        $pishtazKerayetotalPrice = $pishtazKerayetotalPrice + $ress1->Data->TotalPrice;
                        $pistazPrice = $ress1->Data->TotalPrice;
                    }
                    $pishtazKerayeArrayPrice[] = $pistazPrice;


                    $cURLConnection2 = curl_init();
                    curl_setopt($cURLConnection2, CURLOPT_URL, $urlll);
                    curl_setopt($cURLConnection2, CURLOPT_SSL_CIPHER_LIST, 'DEFAULT@SECLEVEL=1');
                    curl_setopt($cURLConnection2, CURLOPT_CUSTOMREQUEST, 'POST');
                    curl_setopt($cURLConnection2, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($cURLConnection2, CURLOPT_HTTPHEADER, array(
                        "Authorization: Bearer $token",
                        'Content-Type: application/json-patch+json'
                    ));
                    curl_setopt($cURLConnection2, CURLOPT_POSTFIELDS,
                        json_encode($data2));
                    $response2 = curl_exec($cURLConnection2);
                    curl_close($cURLConnection2);
                    $ress2 = json_decode($response2);
                    $sefareshiPrice = 0;
                    if ($ress2->ResMsg == "موفق") {
                        $sefareshiKerayetotalPrice = $sefareshiKerayetotalPrice + $ress2->Data->TotalPrice;
                        $sefareshiPrice = $ress2->Data->TotalPrice;
                    }
                    $sefareshiKerayeArrayPrice[] = $sefareshiPrice;


                    $cURLConnection3 = curl_init();
                    curl_setopt($cURLConnection3, CURLOPT_URL, $urlll);
                    curl_setopt($cURLConnection3, CURLOPT_SSL_CIPHER_LIST, 'DEFAULT@SECLEVEL=1');
                    curl_setopt($cURLConnection3, CURLOPT_CUSTOMREQUEST, 'POST');
                    curl_setopt($cURLConnection3, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($cURLConnection3, CURLOPT_HTTPHEADER, array(
                        "Authorization: Bearer $token",
                        'Content-Type: application/json-patch+json'
                    ));
                    curl_setopt($cURLConnection3, CURLOPT_POSTFIELDS,
                        json_encode($data3));
                    $response3 = curl_exec($cURLConnection3);
                    curl_close($cURLConnection3);
                    $ress3 = json_decode($response3);
                    $vijePrice = 0;
                    if ($ress3->ResMsg == "موفق") {
                        $vijeKerayetotalPrice = $vijeKerayetotalPrice + $ress3->Data->TotalPrice;
                        $vijePrice = $ress3->Data->TotalPrice;
                    }
                    $vijeKerayeArrayPrice[] = $vijePrice;

                }

                $result1 = (object)[
                    'id' => 7,
                    'pishtazKerayeArrayPrice' => $pishtazKerayeArrayPrice,
                    'quote' => $pishtazKerayetotalPrice,
                    'method_name' => "پیشتاز",
                    'image' => "https://back.avaex.ir/common/psotlogo.jpg",
                ];
                $result2 = (object)[
                    'id' => 8,
                    'pishtazKerayeArrayPrice' => $sefareshiKerayeArrayPrice,
                    'quote' => $sefareshiKerayetotalPrice,
                    'method_name' => "سفارشی اداره پست",
                    'image' => "https://back.avaex.ir/common/psotlogo.jpg",
                ];
                $result3 = (object)[
                    'id' => 9,
                    'pishtazKerayeArrayPrice' => $vijeKerayeArrayPrice,
                    'quote' => $vijeKerayetotalPrice,
                    'method_name' => "ویژه آواکس",
                    'image' => "https://back.avaex.ir/logoTemp.png",
                ];
                $data = [$result1, $result2, $result3];
                $result = (object)[
                    'data' => $data,
                ];
                return Response::json($result, 200);
            }
        } else {
            $result = (object)[
                'data' => 'با عرض پوزش این سرویس درحال حاضر در شهر مبدا شما فعال نمی باشد.',
            ];
            return Response::json($result, 444);
        }
        // $parcel = [array('weight' => 3),array('weight' => 3)];


    }


}
