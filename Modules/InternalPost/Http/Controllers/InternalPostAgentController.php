<?php

namespace Modules\InternalPost\Http\Controllers;

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

class InternalPostAgentController extends Controller
{
    /*public function add(Request $request)
    {

        $agent = Agent::where('userId', $request->user()->id)->first();
            $dasd =$request->data;

            $TAX = 0;
            $printFactor = 0;
            $totalprintFactor = 0;
            $packaging = 0;
            $totalCollector = 0;
            $totalCost = 20000;
            $Insurance=0;
            $totalpackaging=0;
            $hasNotifRequest = 0;
            $packagePercent = 0;


            $realCollectorPrice = [];
            $realPackagingPrice = [];

            foreach ($dasd as $key => $item) {

                if ($item['needKarton']) {

                    $packaginga = env('BASE_PACKAGING') + (env('UNIT_PACKAGING') * $item['massUnround']);
                    $packagePercent +=$packaginga*env('PACKAGING_PERCENT');

                    $totalpackaging +=$packaginga+($packaginga*env('PACKAGING_PERCENT'));


                    $realPackagingPrice[] = $packaginga+($packaginga*env('PACKAGING_PERCENT'));;


                }

                if ( $item['cost'] > 10000000){
                    $Insurance += $item['cost'] * 0.002;
                }
                else{
                    $Insurance +=$totalCost ;
                }


            }

            $TAX += $totalpackaging+$Insurance;
            if ($request->printFactor) {
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


            $Payable = $request->price  + ($request->price * 0.12) +$TAX+ ($TAX * 0.09);


            $totalPostId = TotalPost::create([
                'userId' => $request->user()->id,
                'agentId' => $agent->id,
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
                'MethodPayment' => $request->MethodPayment,
                'factorstatus' => 'open',
                'Freight' => $request->price,
                'serviceInPlace' => $request->serviceInPlace,
                'Packaging' => $totalpackaging,
                'collector' => $totalCollector,
                'Payable' => $Payable ,
                'ServicesAt' => $request->price * 0.12  ,
                'TAX' => ($TAX * 0.09),
                'Insurance' => $Insurance,
                'amountServices' => $TAX ,

                'totalNumber' => $request->totalNumber,
                'totalCollectiveWeight' => $request->totalCollectiveWeight,
                'totalGrossWeight' => $request->totalGrossWeight,
                'totalWeightPayable' => $request->totalWeightPayable,
                'totalCost' => $request->totalCost,
                'amountCOD' => $request->amountCOD * (($request->amountCOD * 2) / 100),
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
                $lastItem = InternalOrder::where('flag' , 'n')->orderBy('id', 'desc')->first();
                if ($lastItem) {
                    $partNumber = $lastItem->partnumber + 1;
                } else {
                    $partNumber = 100001;
                }

                if ( $item['cost'] > 10000000){
                    $cost = $item['cost'] * 0.002;
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
                    'packaging' => $realPackagingPrice[$key],
                    'collector' => $collectorPart,
                    'realCollector' => $realCollectorPrice[$key],

                ]);

            }

            $result = (object)[
                'data' => $totalPostId,
            ];
            return Response::json($result, 200);



    }*/



    /*git backup*/
//    public function add(Request $request)
//    {
//
//        $senderAddress = Address::find($request->addressId);
//
//        $agent = Agent::where('cityId', $senderAddress->cityId)->first();
//        $areaAgent = 0;
//        if ($agent) {
//
//            if ($senderAddress->areaId != 0) {
//                $area = Area::find($senderAddress->areaId);
//                if ($area->agentId != 0) {
//                    $areaAgent = 1;
//                    $agentId = $area->agentId;
//                } else {
//                    $areaAgent = 0;
//                }
//            } else {
//                $areaAgent = 1;
//                $agentId = $agent->id;
//            }
//
//        }
//
//        if ($areaAgent) {
//
//            $dasd = $request->data;
//
//            $TAX = 0;
//            $printFactor = 0;
//            $totalprintFactor = 0;
//            $packaging = 0;
//            $totalCollector = 0;
//            $totalCost = 20000;
//            $Insurance = 0;
//            $totalpackaging = 0;
//            $hasNotifRequest = 0;
//            $packaginPrice = 0;
//            $realPayable = 0;
//
//            $realCollectorPrice = [];
//
//            foreach ($dasd as $key => $item) {
//                $collector = env('AGENT_BASE_COLLECTOR') * (exp(0.0349 * $item['massUnround']));
//                /*if (($key % 4) == 0) {
//                    $realCollectorPrice[] = $collector;
//                    $totalCollector += $collector;
//                } elseif (($key % 4) == 1) {
//                    $totalCollector += ($collector * 0.9);
//                    $realCollectorPrice[] = $collector* 0.9;
//                } elseif (($key % 4) == 2) {
//                    $totalCollector += ($collector*0.8);
//                    $realCollectorPrice[] = $collector* 0.8;
//                } elseif (($key % 4) == 3) {
//                    $totalCollector += ($collector*0.7);
//                    $realCollectorPrice[] = $collector* 0.7;
//                }*/
//
//                if (($key % 10) == 0) {
//                    $realCollectorPrice[] = $collector;
//                    $totalCollector += $collector;
//                } elseif (($key % 10) == 1) {
//                    $totalCollector += ($collector * 0.9);
//                    $realCollectorPrice[] = $collector * 0.9;
//                } elseif (($key % 10) == 2) {
//                    $totalCollector += ($collector * 0.8);
//                    $realCollectorPrice[] = $collector * 0.8;
//                } elseif (($key % 10) == 3) {
//                    $totalCollector += ($collector * 0.7);
//                    $realCollectorPrice[] = $collector * 0.7;
//                } elseif (($key % 10) == 4) {
//                    $totalCollector += ($collector * 0.6);
//                    $realCollectorPrice[] = $collector * 0.6;
//                } elseif (($key % 10) == 5) {
//                    $totalCollector += ($collector * 0.5);
//                    $realCollectorPrice[] = $collector * 0.5;
//                } elseif (($key % 10) == 6) {
//                    $totalCollector += ($collector * 0.4);
//                    $realCollectorPrice[] = $collector * 0.4;
//                } elseif (($key % 10) == 7) {
//                    $totalCollector += ($collector * 0.3);
//                    $realCollectorPrice[] = $collector * 0.3;
//                } elseif (($key % 10) == 8) {
//                    $totalCollector += ($collector * 0.2);
//                    $realCollectorPrice[] = $collector * 0.2;
//                } elseif (($key % 10) == 9) {
//                    $totalCollector += ($collector * 0.1);
//                    $realCollectorPrice[] = $collector * 0.1;
//                }
//                $packaginga = 0;
//                if ($item['needKarton']) {
//
//                    $packaginga = 54000 + (42283 * $item['massUnround']);
//                    $totalpackaging += $packaginga;
//
////                    $TAX += $packaginga;
//
//                }
//                $packaginPrice = $packaginPrice + $packaginga;
//                if ($item['cost'] > 10000000) {
//                    $Insurance += $item['cost'] * 0.002;
//                } else {
//                    $Insurance += $totalCost;
//                }
//            }
//
//            /*$TAX += $totalCollector+$totalpackaging+$Insurance;*/
//            $TAX += $totalCollector + $Insurance;
//            /* if ($request->printFactor || $request->needKarton) {*/
//            $printFactor = 35000;
//            $TAX += ($request->totalNumber * 35000);
//            $totalprintFactor = ($request->totalNumber * 35000);
//
//            /*}*/
//            if ($request->hasNotifRequest) {
//                $TAX += 10000;
//                $hasNotifRequest = 10000;
//            }
//
//
//            $lastItem = TotalPost::orderBy('id', 'desc')->first();
//            if ($lastItem) {
//                $numberParcel = $lastItem->numberParcel + 1;
//            } else {
//                $numberParcel = 100001;
//            }
//
//
//            $Payable = $request->price + ($request->price * 0.12) + $TAX + ($TAX * 0.09);
//
//            $serviceInPlace = 0;
//            if ($request->totalSpecialService) {
//                $serviceInPlace = $request->totalSpecialService;
//                //$TAX += $request->totalSpecialService;
//            }
//            $Payable = $Payable + $serviceInPlace;
//
//            $MethodPayment = "پیشکرایه";
//
//            $isAfterRent = 0;
//            if ($request->isAfterRent) {
//                $MethodPayment = "پس کرایه";
//                $isAfterRent = 1;
//            }
//            $isCod = 0;
//            if ($request->isCod) {
//                $MethodPayment = "پرداخت در محل-COD";
//                $isCod = 1;
//            }
//            $realPayable = ($Payable) + ($packaginPrice * 1.09);
//            /*if ($request->totalSpecialService != null && $request->totalSpecialService != 0) {
//                $realPayable = $realPayable + (0.09 * $request->totalSpecialService);
//            }*/
//
//
//            $totalPostId = TotalPost::create([
//                'userId' => $request->user()->id,
//                'agentId' => $agentId,
//                'numberParcel' => $numberParcel,
//                'addressId' => $request->addressId,
//                'getterAddressId' => $request->getterAddressId,
//                'typeSerId' => 1,
//                'componyId' => $request->componyId,
//                'componyTypeId' => $request->componyTypeId,
//                'componyServicesId' => $request->componyServicesId,
//                'printFactor' => $totalprintFactor,
//                'discountCouponCode' => $request->discountCouponCode,
//                'hasNotifRequest' => $hasNotifRequest,
//                'RequestPrintAvatar' => $request->RequestPrintAvatar,
//                'status' => 'ارجاع به راننده',
//                'MethodPayment' => $MethodPayment,
//                'factorstatus' => 'open',
//                'Freight' => $request->price,
//                'serviceInPlace' => $request->totalSpecialService,
//                'Packaging' => $totalpackaging,
//                'collector' => $totalCollector,
//                'Payable' => $Payable,
//                'realPayable' => $realPayable,
//                'ServicesAt' => $request->price * 0.12,
//                'TAX' => ($TAX * 0.09),
//                'Insurance' => $Insurance,
//                'amountServices' => $TAX+$serviceInPlace,
//                'totalNumber' => $request->totalNumber,
//                'totalCollectiveWeight' => $request->totalCollectiveWeight,
//                'totalGrossWeight' => $request->totalGrossWeight,
//                'totalWeightPayable' => $request->totalWeightPayable,
//                'totalCost' => $request->totalCost,
//                'amountCOD' => $request->amountCOD,
//                'isAfterRent' => $isAfterRent,
//                'isCod' => $isCod,
//                'byAgent' => 1,
//            ])->id;
//            $typeService = ComponyTypePost::find($request->componyTypeId);
//            $PostId = InternalPost::create([
//                'totalPostId' => $totalPostId,
//                'typeSerId' => 1,
//                'componyId' => $request->componyId,
//                'componyTypeId' => 1
//            ])->id;
//
//
//            $collectorPart = $totalCollector / $request->totalNumber;
//            foreach ($dasd as $key => $item) {
//                $cost = 20000;
//                $packaging = 0;
//                if ($item['needKarton']) {
//                    $packaging = 54000 + (42283 * $item['massUnround']);
//                }
//                $lastItem = InternalOrder::where('flag', 'n')->orderBy('id', 'desc')->first();
//                if ($lastItem) {
//                    $partNumber = $lastItem->partnumber + 1;
//                } else {
//                    $partNumber = 100001;
//                }
//
//                if ($item['cost'] > 10000000) {
//                    $cost = $item['cost'] * 0.002;
//                }
//
//                $itemCod = 0;
//                if (isset($item['amountCODD']) and $item['amountCODD'] and $item['amountCODD'] != null and $item['amountCODD'] != "") {
//                    $itemCod = $item['amountCODD'];
//                }
//
//                InternalOrder::create([
//                    'internalPostId' => $PostId,
//                    'partnumber' => $partNumber,
//                    'shipment' => $item['content'],
//                    'weight' => $item['weight'],
//                    'width' => $item['widthh'],
//                    'height' => $item['heightt'],
//                    'lenght' => $item['lengtha'],
//                    'cost' => $cost,
//                    'value' => $item['cost'],
//                    'boxnumber' => 1,
//                    'typeId' => $item['packaging'],
//                    'mass' => $item['mass'],
//                    'massUnround' => $item['massUnround'],
//                    'needKarton' => $request->needKarton,
//                    'insuranceId' => 1,
//                    'getterAddressId' => $request->getterAddressId,
//                    'serviceId' => 1,
//                    'status' => 'ارجاع به راننده',
//                    'print' => $printFactor,
//                    'packaging' => $packaging,
//                    'collector' => $collectorPart,
//                    'amountCOD' => $itemCod,
//                    'realCollector' => $realCollectorPrice[$key],
//                ]);
//            }
//
//            $result = (object)[
//                'data' => $totalPostId,
//            ];
//            return Response::json($result, 200);
//
//
//        } else {
//            $result = (object)[
//                'data' => 'با عرض پوزش این سرویس درحال حاضر در شهر یا منطقه مبدا شما فعال نمی باشد.',
//            ];
//            return Response::json($result, 444);
//
//        }
//
//
//    }


    public function add(Request $request)
    {
        $senderAddress = Address::find($request->addressId);

        $agent = Agent::where('cityId', $senderAddress->cityId)->first();
        $areaAgent = 0;
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
            $totalpackaging = 0;
            $hasNotifRequest = 0;
            $packaginPrice = 0;
            $realPayable = 0;

            $realCollectorPrice = [];

            foreach ($dasd as $key => $item) {
                $collector = env('AGENT_BASE_COLLECTOR') * (exp(0.0349 * $item['massUnround']));
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
                $packaginga = 0;
                if ($item['needKarton']) {

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

            if ($request->componyId > 1) {


                /*$TAX += $totalCollector+$totalpackaging+$Insurance;*/
                $TAX += $totalCollector + $Insurance;
                /* if ($request->printFactor || $request->needKarton) {*/
                $printFactor = 35000;
                $TAX += ($request->totalNumber * 35000);
                $totalprintFactor = ($request->totalNumber * 35000);

                /*}*/
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


                $Payable = $request->price + ($request->price * 0.12) + $TAX + ($TAX * 0.09);

                $serviceInPlace = 0;
                if ($request->totalSpecialService) {
                    $serviceInPlace = $request->totalSpecialService;
                    //$TAX += $request->totalSpecialService;
                }
                $Payable = $Payable + $serviceInPlace;

                $MethodPayment = "پیشکرایه";

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
                $realPayable = ($Payable) + ($packaginPrice * 1.09);
                /*if ($request->totalSpecialService != null && $request->totalSpecialService != 0) {
                    $realPayable = $realPayable + (0.09 * $request->totalSpecialService);
                }*/


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
                    'status' => 'ارجاع به راننده',
                    'MethodPayment' => $MethodPayment,
                    'factorstatus' => 'open',
                    'Freight' => $request->price,
                    'serviceInPlace' => $request->totalSpecialService,
                    'Packaging' => $totalpackaging,
                    'collector' => $totalCollector,
                    'Payable' => $Payable,
                    'realPayable' => $realPayable,
                    'ServicesAt' => $request->price * 0.12,
                    'TAX' => ($TAX * 0.09),
                    'Insurance' => $Insurance,
                    'amountServices' => $TAX + $serviceInPlace,
                    'totalNumber' => $request->totalNumber,
                    'totalCollectiveWeight' => $request->totalCollectiveWeight,
                    'totalGrossWeight' => $request->totalGrossWeight,
                    'totalWeightPayable' => $request->totalWeightPayable,
                    'totalCost' => $request->totalCost,
                    'amountCOD' => $request->amountCOD,
                    'isAfterRent' => $isAfterRent,
                    'isCod' => $isCod,
                    'byAgent' => 1,
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
                    $packaging = 0;
                    if ($item['needKarton']) {
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

                    $itemCod = 0;
                    if (isset($item['amountCODD']) and $item['amountCODD'] and $item['amountCODD'] != null and $item['amountCODD'] != "") {
                        $itemCod = $item['amountCODD'];
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
                        'status' => 'ارجاع به راننده',
                        'print' => $printFactor,
                        'packaging' => $packaging,
                        'collector' => $collectorPart,
                        'amountCOD' => $itemCod,
                        'realCollector' => $realCollectorPrice[$key],
                    ]);
                }

                $result = (object)[
                    'data' => $totalPostId,
                ];
                return Response::json($result, 200);

            } else {

                $totalId = [];

                $collectorPart = $totalCollector / count($dasd);

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
                        //$TAX += (count($dasd) * env('PRINT_COST'));
                        //$totalprintFactor = (count($dasd) * env('PRINT_COST'));
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
                    if ($request->totalSpecialService) {
                        $totalServiceInPlace = $request->totalSpecialService;
                        $serviceInPlace = $totalServiceInPlace / count($dasd);
                        /*$serviceInPlace = $item['serviceInPlace'];*/
                    }

                    $Payable = $Payable + $serviceInPlace;

                    $CODPrice = 0;
                    if ($request->amountCOD) {
                        $totalamountCOD = $request->amountCOD;
                        $CODPrice = $totalamountCOD/count($dasd);
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
                        //'discountCouponCode' => $item['discountCouponCode'],
                        'hasNotifRequest' => $hasNotifRequest,
                        //'RequestPrintAvatar' => $item['RequestPrintAvatar'],
                        'status' => 'ارجاع به راننده',
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
                        'amountServices' => $TAX + $serviceInPlace,
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
                        'getterAddressId' => $request->getterAddressId,
                        'serviceId' => 1,
                        'status' => 'ارجاع به راننده',
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
            }


        } else {
            $result = (object)[
                'data' => 'با عرض پوزش این سرویس درحال حاضر در شهر یا منطقه مبدا شما فعال نمی باشد.',
            ];
            return Response::json($result, 444);

        }


    }



    public function bulkAdd1(Request $request)
    {

        $senderAddress = Address::find($request->addressId);
        $agent = Agent::where('cityId', $senderAddress->cityId)->first();
        if ($agent) {
            $totalId = [];

            $dasd = $request->data;
            $TAX = 0;
            $printFactor = 0;
            $totalprintFactor = 0;
            $totalCollector = 0;
            $Insurance = 0;
            $hasNotifRequest = 0;


            foreach ($dasd as $key => $item) {
                $collector = env('AGENT_BASE_COLLECTOR') * (exp(0.0349 * $item['massUnround']));
                if (($key % 4) == 0) {
                    $totalCollector += $collector;
                } elseif (($key % 4) == 1) {
                    $totalCollector += ($collector * 0.8);

                } elseif (($key % 4) == 2) {
                    $totalCollector += ($collector * 0.7);

                } elseif (($key % 4) == 3) {
                    $totalCollector += ($collector * 0.6);
                }
                $TAX += $totalCollector;


                $packaginga = 0;

                if ($item['needKarton']) {

                    $packaginga = env('PACKAGING') + (env('UNIT_PACKAGING') * $item['massUnround']);

                    $TAX += $packaginga;

                }

                if ($item['cost'] > env('UNIT_INSURANCE')) {
                    $Insurance += ($item['cost'] * env('INSURANCE')) / 1000;
                } else {
                    $Insurance += env('INSURANCE');
                }


                $lastItem = TotalPost::orderBy('id', 'desc')->first();
                if ($lastItem) {
                    $numberParcel = $lastItem->numberParcel + 1;
                } else {
                    $numberParcel = 100001;
                }

                /*if ($request->printFactor) {*/
                $printFactor = env('PRINT_COST');
                //$TAX += (count($dasd) * env('PRINT_COST'));
                //$totalprintFactor = (count($dasd) * env('PRINT_COST'));
                $TAX = $printFactor;
                $totalprintFactor = $printFactor;


                /*}*/
                if ($request->hasNotifRequest) {
                    $TAX += env('SMS_COST');
                    $hasNotifRequest = env('SMS_COST');
                }

                $Payable = $item['price'] + $Insurance + ($item['price'] * 0.12) + $TAX + ($TAX * 0.09);

                $totalWeightPayable = $item['weight'];
                if ($item['mass'] > $item['weight']) {
                    $totalWeightPayable = $item['weight'];
                }

                $totalPostId = TotalPost::create([
                    'userId' => $request->user()->id,
                    'agentId' => $agent->id,
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
                    'status' => 'ارجاع به راننده',
                    'MethodPayment' => $request->MethodPayment,
                    'serviceInPlace' => $item['serviceInPlace'],
                    'factorstatus' => 'open',
                    'Freight' => $item['price'],
                    'Packaging' => $packaginga,
                    'collector' => $totalCollector,
                    'Payable' => $Payable,
                    'ServicesAt' => ($item['price'] * 0.12),
                    'TAX' => ($TAX * 0.09),
                    'Insurance' => $Insurance,
                    'totalNumber' => 1,
                    'totalCollectiveWeight' => $item['weight'],
                    'totalGrossWeight' => $item['mass'],
                    'totalWeightPayable' => $totalWeightPayable,
                    'totalCost' => $item['price'],
                    /*'amountCOD' => $item['CODPrice'] * (($item['CODPrice'] * 2) / 100),*/
                    'amountCOD' => 0,
                    'byAgent' => 1,
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

                $cost = 0;
                if ($item['cost'] > 10000000) {
                    $cost = $item['cost'] * 0.002;
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
                    'mass' => $item['mass'],
                    'massUnround' => $item['massUnround'],
                    'needKarton' => $item['needKarton'],
                    'insuranceId' => 1,
                    'getterAddressId' => $item['getterAddressId'],
                    'serviceId' => 1,
                    'status' => 'ارجاع به راننده',
                    'print' => $printFactor,
                    'packaging' => $packaginga,
                    'collector' => $totalCollector,
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


    public function bulkAdd2(Request $request)
    {

        $senderAddress = Address::find($request->addressId);
        $agent = Agent::where('cityId', $senderAddress->cityId)->first();
        if ($agent) {
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

            $collectorT = 290423 * (exp(0.0249 * $totalWeght));
            $collectorPart = $collectorT / count($dasd);

            foreach ($dasd as $key => $item) {
                $TAX = 0;
                $TAX += $collectorPart;
                $Insurance = 0;
                $packaginga = 0;

                if ($item['needKarton']) {
                    $packaginga = env('PACKAGING') + (env('UNIT_PACKAGING') * $item['massUnround']);
                    $TAX += $packaginga;
                }

                if ($item['cost'] > env('UNIT_INSURANCE')) {
                    $Insurance += ($item['cost'] * env('INSURANCE')) / 1000;
                } else {
                    $Insurance += env('INSURANCE');
                }


                $lastItem = TotalPost::orderBy('id', 'desc')->first();
                if ($lastItem) {
                    $numberParcel = $lastItem->numberParcel + 1;
                } else {
                    $numberParcel = 100001;
                }

                if ($request->printFactor) {
                    $printFactor = env('PRINT_COST');
                    $TAX += (count($dasd) * env('PRINT_COST'));
                    $totalprintFactor = (count($dasd) * env('PRINT_COST'));
                    $totalprintFactor = $printFactor;
                }
                if ($request->hasNotifRequest) {
                    $TAX += env('SMS_COST');
                    $hasNotifRequest = env('SMS_COST');
                }

                $Payable = $item['price'] + $Insurance + ($item['price'] * 0.12) + $TAX + ($TAX * 0.09);

                $totalWeightPayable = $item['weight'];
                if ($item['mass'] > $item['weight']) {
                    $totalWeightPayable = $item['weight'];
                }

                $totalPostId = TotalPost::create([
                    'userId' => $request->user()->id,
                    'agentId' => $agent->id,
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
                    'status' => 'ارجاع به راننده',
                    'MethodPayment' => $request->MethodPayment,
                    'serviceInPlace' => $item['serviceInPlace'],
                    'factorstatus' => 'open',
                    'Freight' => $item['price'],
                    'Packaging' => $packaginga,
                    'collector' => $collectorPart,
                    'Payable' => $Payable,
                    'ServicesAt' => ($item['price'] * 0.12),
                    'TAX' => ($TAX * 0.09),
                    'Insurance' => $Insurance,
                    'totalNumber' => 1,
                    'totalCollectiveWeight' => $item['weight'],
                    'totalGrossWeight' => $item['mass'],
                    'totalWeightPayable' => $totalWeightPayable,
                    'totalCost' => $item['price'],
                    /*'amountCOD' => $item['CODPrice'] * (($item['CODPrice'] * 2) / 100),*/
                    'amountCOD' => 0,
                    'byAgent' => 1,
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

                $cost = 0;
                if ($item['cost'] > 10000000) {
                    $cost = $item['cost'] * 0.002;
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
                    'mass' => $item['mass'],
                    'massUnround' => $item['massUnround'],
                    'needKarton' => $item['needKarton'],
                    'insuranceId' => 1,
                    'getterAddressId' => $item['getterAddressId'],
                    'serviceId' => 1,
                    'status' => 'جمع آوری نشده',
                    'print' => $printFactor,
                    'packaging' => $packaginga,
                    'collector' => $collectorPart,
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


    public function bulkAddBackUp(Request $request)
    {

        $senderAddress = Address::find($request->addressId);
        $agent = Agent::where('cityId', $senderAddress->cityId)->first();
        if ($agent) {
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

            /*            $collectorT = 290423 * (exp(0.0349 * $totalWeght));*/

            if ($totalWeght < 40) {
                $collectorT = 290423 * (exp(0.0249 * $totalWeght));
            } else {
                $collectorT = 290423 * (exp(0.0195 * $totalWeght));
            }

            $collectorPart = $collectorT / count($dasd);


            $serviceInPlace = 0;

            if ($request->totalSpecialService) {
                $serviceInPlace = ($request->totalSpecialService) / count($dasd);
            }

            foreach ($dasd as $key => $item) {
                $TAX = 0;
                $TAX += $collectorPart;
                $Insurance = 0;
                $packaginga = 0;

                if ($item['needKarton']) {
                    $packaginga = env('PACKAGING') + (env('UNIT_PACKAGING') * $item['massUnround']);
                    $TAX += $packaginga;
                }

                if ($item['cost'] > env('UNIT_INSURANCE')) {
                    $Insurance += ($item['cost'] * env('INSURANCE')) / 10000000;
                } else {
                    $Insurance += env('INSURANCE');
                }


                $lastItem = TotalPost::orderBy('id', 'desc')->first();
                if ($lastItem) {
                    $numberParcel = $lastItem->numberParcel + 1;
                } else {
                    $numberParcel = 100001;
                }

                if ($request->printFactor) {
                    $printFactor = env('PRINT_COST');
                    $TAX += (count($dasd) * env('PRINT_COST'));
                    $totalprintFactor = (count($dasd) * env('PRINT_COST'));
                    $totalprintFactor = $printFactor;
                }
                if ($request->hasNotifRequest) {
                    $TAX += env('SMS_COST');
                    $hasNotifRequest = env('SMS_COST');
                }

                $Payable = $item['price'] + $Insurance + ($item['price'] * 0.12) + $TAX + ($TAX * 0.09);

                if ($request->totalSpecialService) {
                    $Payable = $Payable + $serviceInPlace;
                }

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

                $totalPostId = TotalPost::create([
                    'userId' => $request->user()->id,
                    'agentId' => $agent->id,
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
                    'status' => 'ارجاع به راننده',
                    'MethodPayment' => $request->MethodPayment,
                    'serviceInPlace' => $serviceInPlace,
                    'factorstatus' => 'open',
                    'Freight' => $item['price'],
                    'Packaging' => $packaginga,
                    'collector' => $collectorPart,
                    'Payable' => $Payable,
                    'ServicesAt' => ($item['price'] * 0.12),
                    'TAX' => ($TAX * 0.09),
                    'Insurance' => $Insurance,
                    'totalNumber' => 1,
                    'totalCollectiveWeight' => $item['weight'],
                    'totalGrossWeight' => $item['mass'],
                    'totalWeightPayable' => $totalWeightPayable,
                    'totalCost' => $item['price'],
                    /*'amountCOD' => $item['CODPrice'] * (($item['CODPrice'] * 2) / 100),*/
                    'amountCOD' => 0,
                    'isAfterRent' => $isAfterRent,
                    'isCod' => $isCod,
                    'byAgent' => 1,
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

                $cost = 0;
                if ($item['cost'] > 10000000) {
                    $cost = $item['cost'] * 0.002;
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
                    'needKarton' => $item['needKarton'],
                    'insuranceId' => 1,
                    'getterAddressId' => $item['getterAddressId'],
                    'serviceId' => 1,
                    'status' => 'جمع آوری نشده',
                    'print' => $printFactor,
                    'packaging' => $packaginga,
                    'collector' => $collectorPart,
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
                'data' => 'با عرض پوزش این سرویس درحال حاضر در شهر یا منطقه مبدا شما فعال نمی باشد.',
            ];
            return Response::json($result, 444);

        }


    }


    public function bulkAddBay(Request $request)
    {

        $senderAddress = Address::find($request->addressId);
        $agent = Agent::where('cityId', $senderAddress->cityId)->first();
        $areaAgent = 0;
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
            $totalId = [];

            $dasd = $request->data;
            $TAX = 0;
            $printFactor = 0;
            $totalprintFactor = 0;
            $totalCollector = 0;
            $Insurance = 0;
            $hasNotifRequest = 0;

            $totalWeght = 0;
            $serviceInPlaceReal = 0;
            if ($request->totalSpecialService != null && $request->totalSpecialService != 0) {
                $serviceInPlaceReal = $request->totalSpecialService / count($dasd);
            }


            foreach ($dasd as $key => $item) {
                $totalWeght = $totalWeght + $item['massUnround'];
                $collector = env('AGENT_BASE_COLLECTOR') * (exp(0.0349 * $item['massUnround']));
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

            if ($totalWeght < 40) {
                $collectorT = env('AGENT_BASE_COLLECTOR') * (exp(0.0249 * $totalWeght));
            } else {
                $collectorT = env('AGENT_BASE_COLLECTOR') * (exp(0.0195 * $totalWeght));
            }

            $collectorPart = $collectorT / count($dasd);


            foreach ($dasd as $key => $item) {
                $TAX = 0;
                $TAX += $collectorPart;
                $Insurance = 0;
                $packaginga = 0;
                $packaginPrice = 0;
                $realPayable = 0;

                if (isset($item['needKarton']) and $item['needKarton']) {
                    $packaginga = env('PACKAGING') + (env('UNIT_PACKAGING') * $item['massUnround']);
                    $TAX += $packaginga;
                }
                $packaginPrice = $packaginPrice + $packaginga;
                if ($item['cost'] > env('UNIT_INSURANCE')) {
                    $Insurance += ($item['cost'] * env('INSURANCE')) / 10000000;
                } else {
                    $Insurance += env('INSURANCE');
                }

                $lastItem = TotalPost::orderBy('id', 'desc')->first();
                if ($lastItem) {
                    $numberParcel = $lastItem->numberParcel + 1;
                } else {
                    $numberParcel = 100001;
                }

                if ($request->printFactor) {
                    $printFactor = env('PRINT_COST');
                    $TAX += (count($dasd) * env('PRINT_COST'));
                    $totalprintFactor = (count($dasd) * env('PRINT_COST'));
                    $totalprintFactor = $printFactor;
                }
                if ($request->hasNotifRequest) {
                    $TAX += env('SMS_COST');
                    $hasNotifRequest = env('SMS_COST');
                }

                $Payable = $item['price'] + $Insurance + ($item['price'] * 0.12) + $TAX + ($TAX * 0.09);

                $totalWeightPayable = $item['weight'];
                if ($item['mass'] > $item['weight']) {
                    $totalWeightPayable = $item['weight'];
                }

                $MethodPayment = "پیشکرایه";

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

                $realPayable = ($Payable) + ($packaginPrice * 1.09);

                $realPayable = $realPayable + (0.09 * $item['serviceInPlace']) + $item['serviceInPlace'];

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
                    'status' => 'ارجاع به راننده',
                    'serviceInPlace' => $item['serviceInPlace'],
                    'factorstatus' => 'open',
                    'Freight' => $item['price'],
                    'Packaging' => $packaginga,
                    'collector' => $collectorPart,
                    'Payable' => $Payable,
                    'realPayable' => $realPayable,
                    'ServicesAt' => ($item['price'] * 0.12),
                    'TAX' => ($TAX * 0.09),
                    'Insurance' => $Insurance,
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

                $itemCod = 0;
                if (isset($item['amountCODD']) and $item['amountCODD'] and $item['amountCODD'] != null and $item['amountCODD'] != "") {
                    $itemCod = $item['amountCODD'];
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
                    'packaging' => $packaginga,
                    'collector' => $collectorPart,
                    'realCollector' => $collectorPart,
                    'amountCOD' => $itemCod,
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
                'data' => 'با عرض پوزش این سرویس درحال حاضر در شهر یا منطقه مبدا شما فعال نمی باشد.',
            ];
            return Response::json($result, 444);

        }


    }

    public function bulkAddBedorUser(Request $request)
    {

        $senderAddress = Address::find($request->addressId);
        $agent = Agent::where('cityId', $senderAddress->cityId)->first();
        $areaAgent = 0;
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
            $totalId = [];

            $dasd = $request->data;

            $totalWeght = 0;


            foreach ($dasd as $key => $item) {
                $totalWeght = $totalWeght + $item['massUnround'];
            }

            if ($totalWeght < 40) {
                // $collectorT = env('AGENT_BASE_COLLECTOR') * (exp(0.0249 * $totalWeght));
                $collectorT = env('AGENT_BASE_COLLECTOR') * (exp(0.0349 * $totalWeght));
            } else {
                $collectorT = env('AGENT_BASE_COLLECTOR') * (exp(0.0195 * $totalWeght));
            }

            $collectorPart = $collectorT / count($dasd);


            foreach ($dasd as $key => $item) {
                $TAX = 0;
                $TAX += $collectorPart;
                $Insurance = 0;
                $packaginga = 0;
                $packaginPrice = 0;

                if (isset($item['needKarton']) and $item['needKarton']) {
                    $packaginga = env('PACKAGING') + (env('UNIT_PACKAGING') * $item['massUnround']);
                    $TAX += $packaginga;
                }
                $packaginPrice = $packaginPrice + $packaginga;

                if ($item['cost'] > env('UNIT_INSURANCE')) {
                    //$Insurance += ($item['cost'] * env('INSURANCE')) / 10000000;
                    $Insurance += $item['cost'] * 0.002;
                } else {
                    $Insurance += env('INSURANCE');
                }

                $TAX += $Insurance;

                $lastItem = TotalPost::orderBy('id', 'desc')->first();
                if ($lastItem) {
                    $numberParcel = $lastItem->numberParcel + 1;
                } else {
                    $numberParcel = 100001;
                }

                $printFactor = env('PRINT_COST');
                $TAX += env('PRINT_COST');

                $hasNotifRequest = 0;
                if ($request->hasNotifRequest) {
                    $TAX += env('SMS_COST');
                    $hasNotifRequest = env('SMS_COST');
                }

               // $Payable = $item['price'] + $Insurance + ($item['price'] * 0.12) + $TAX + ($TAX * 0.09);

                $totalWeightPayable = $item['weight'];
                if ($item['mass'] > $item['weight']) {
                    $totalWeightPayable = $item['weight'];
                }

                $MethodPayment = "پیش کرایه";

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
                    /*$Payable = $Payable +$item['serviceInPlace'];*/
                   // $TAX += $item['serviceInPlace'];
                }

                $Payable = $item['price'] + $serviceInPlace +($item['price'] * 0.12) + $TAX + ($TAX * 0.09);


                $CODPrice = 0;
                if (isset($item['CODPrice'])) {
                    $CODPrice = $item['CODPrice'];
                }

                $realPayable = ($Payable) + ($packaginPrice * 1.09);

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
                    'printFactor' => $printFactor,
                    //'discountCouponCode' => $item['discountCouponCode'],
                    'hasNotifRequest' => $hasNotifRequest,
                    //'RequestPrintAvatar' => $item['RequestPrintAvatar'],
                    'status' => 'معلق',
                    'serviceInPlace' => $serviceInPlace,
                    'factorstatus' => 'open',
                    'Freight' => $item['price'],
                    'Packaging' => $packaginga,
                    'collector' => $collectorPart,
                    'Payable' => $Payable,
                    'realPayable' => $Payable + ($packaginPrice * 1.09),
                    'ServicesAt' => ($item['price'] * 0.12),
                    'TAX' => ($TAX * 0.09),
                    'Insurance' => $Insurance,
                    'totalNumber' => 1,
                    'totalCollectiveWeight' => $item['weight'],
                    'totalGrossWeight' => $item['mass'],
                    'totalWeightPayable' => $totalWeightPayable,
                    'totalCost' => $item['price'],
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
                    'packaging' => $packaginga,
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

    public function bulkAdd(Request $request)
    {

        $senderAddress = Address::find($request->addressId);
        $agent = Agent::where('cityId', $senderAddress->cityId)->first();
        $areaAgent = 0 ;
        if ($agent){
            if (Area::find($senderAddress->areaId) and $senderAddress->areaId != 0){
                $area=Area::find($senderAddress->areaId);
                if ($area->agentId != 0){
                    $areaAgent =1;
                    $agentId = $area->agentId;
                }
                else{
                    $areaAgent =0;
                }
            }
            else{
                $areaAgent =1;
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
                $collector = env('AGENT_BASE_COLLECTOR') * (exp(0.0349 * $item['massUnround']));

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
            $collectorT = env('AGENT_BASE_COLLECTOR') * (exp(0.0349 * $totalWeght));

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
                    //$TAX += (count($dasd) * env('PRINT_COST'));
                    //$totalprintFactor = (count($dasd) * env('PRINT_COST'));
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

                if(isset($item['serviceInPlace'])){
                    $serviceInPlace = $item['serviceInPlace'];
                }

                $Payable = $Payable + $serviceInPlace;

                $CODPrice = 0;
                if(isset($item['CODPrice'])){
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
                    'status' => 'ارجاع به راننده',
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
                    'amountServices' => $TAX+$serviceInPlace,
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
                $needKarton= 0;
                if(isset($item['needKarton'])){
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
                    'status' => 'ارجاع به راننده',
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

}
