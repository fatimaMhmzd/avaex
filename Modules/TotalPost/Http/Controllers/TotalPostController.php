<?php

namespace Modules\TotalPost\Http\Controllers;


use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Address\Entities\Address;
use Modules\Address\Entities\City;
use Modules\Address\Entities\Province;
use Modules\Agent\Entities\Agent;
use Modules\Compony\Entities\Compony;
use Modules\Compony\Entities\ComponyService;
use Modules\Compony\Entities\ComponyTypePost;
use Modules\Compony\Entities\Service;
use Modules\ExternalPost\Entities\ExternalOrder;
use Modules\ExternalPost\Entities\ExternalPost;
use Modules\HeavyPost\Entities\HeavyOrder;
use Modules\HeavyPost\Entities\HeavyPost;
use Modules\InternalPost\Entities\InternalOrder;
use Modules\InternalPost\Entities\InternalPost;
use Modules\InUrbanePost\Entities\PeykOrder;
use Modules\InUrbanePost\Entities\PeykPost;
use Modules\Setting\Entities\Setting;
use Modules\TotalPost\Entities\TotalPost;
use Modules\User\Entities\User;
use Modules\Wallet\Entities\Wallet;
use PDF;
use App\Exports\MyPartExport;

class TotalPostController extends Controller
{
    public function payment(Request $request, $type, $totalPostIda)
    {
        $allIds = explode(',', $totalPostIda);

        foreach ($allIds as $totalPostId) {

            $totalPosts = TotalPost::find($totalPostId);
            if ($type == 1) {

                $userWallet = User::find($request->user()->id)->wallet;

                if ($userWallet < $totalPosts->realPayable) {
                    $result = (object)[
                        'data' => 'موجودی کیف پول کافی نیست.',
                        'stats' => 400,
                    ];
                    return Response::json($result, 400);
                } else {

                    if ($totalPosts->componyId == 3) {
                        /**/
                        /*$status = paybyWallet($request->user()->id, $totalPostId);
                        return $status;*/
                        /**/
                        $res = chaparRequest($totalPostId);

                        if ($res->result && $res->result == 'true') {
                            $status = paybyWallet($request->user()->id, $totalPostId, $request->dc);

                            TotalPost::where('id', $totalPostId)->Update([
                                'factorstatus' => 'close'
                            ]);
                            foreach ($res->objects->result as $item) {
                                InternalOrder::where('partnumber', $item->reference)->Update([
                                    'serviceNumberParcel' => $item->tracking,
                                    'servicePartnumber' => $item->package[0]
                                ]);
                            }

                            if ($totalPosts->byAgent == 0){
                                sendSubmitSms($totalPostId);
                            }

                        } else {
                            $result = (object)[
                                'data' => $res->message,
                                'stats' => 400,
                            ];
                            return Response::json($result, 400);
                        }

                    } else if ($totalPosts->componyId == 2) {

                        $res = mahexRequest($totalPostId);

                        if ($res->status->state == "Success") {
                            $status = paybyWallet($request->user()->id, $totalPostId, $request->dc);

                            TotalPost::where('id', $totalPostId)->Update([
                                'factorstatus' => 'close',
                                // 'serviceNumberParcel' => '4ba8bd7b-84b2-49c9-a03d-1c0273b05a53',
                            ]);

                            if ($totalPosts->byAgent == 0){
                                sendSubmitSms($totalPostId);
                            }
                            /*foreach ($res->objects->result as $item) {
                                InternalOrder::where('partnumber', $item->reference)->Update([
                                    'serviceNumberParcel' => $item->tracking,
                                    'servicePartnumber' => $item->package[0]
                                ]);
                            }*/
                            /*                            $result = (object)[
                                                            'data' => 'http://avaex.ir/successful/' . $totalPostId,
                                                            'stats' => 200,
                                                        ];
                                                        return Response::json($result, 200);*/


                        } else {
                            $result = (object)[
                                'data' => $res->message,
                                'stats' => 400,
                            ];
                            return Response::json($result, 400);
                        }
                    } else if ($totalPosts->componyId == 1) {
                        $res = postRequest($totalPostId);
                        if ($res->ResMsg == "موفق") {
                            $status = paybyWallet($request->user()->id, $totalPostId, $request->dc);

                            TotalPost::where('id', $totalPostId)->Update([
                                'factorstatus' => 'close',
                                // 'serviceNumberParcel' => '4ba8bd7b-84b2-49c9-a03d-1c0273b05a53',
                            ]);

                            if ($totalPosts->byAgent == 0){
                                sendSubmitSms($totalPostId);
                            }

                        } else {
                            $result = (object)[
                                'data' => $res->ResMsg,
                                'stats' => 400,
                            ];
                            return Response::json($result, 400);
                        }
                    }
                }


            } elseif ($type == 2) {

                $userWallet = User::find($request->user()->id)->wallet;


                if ($totalPosts->componyId == 3) {
                    $res = chaparRequest($totalPostId);
                    if ($res->result && $res->result == 'true') {

                        $status = paybyWallet($request->user()->id, $totalPostId, $request->dc);

                        TotalPost::where('id', $totalPostId)->Update([
                            'factorstatus' => 'close'
                        ]);
                        foreach ($res->objects->result as $item) {
                            InternalOrder::where('partnumber', $item->reference)->Update([
                                'serviceNumberParcel' => $item->tracking,
                                'servicePartnumber' => $item->package[0]
                            ]);
                        }

                        sendSubmitSms($totalPostId);
                    } else {
                        $result = (object)[
                            'data' => $res->message,
                            'stats' => 400,
                        ];
                        return Response::json($result, 400);
                    }
                } else if ($totalPosts->componyId == 2) {

                    $res = mahexRequest($totalPostId);
                    if ($res->status->state == "Success") {

                        $status = paybyWallet($request->user()->id, $totalPostId, $request->dc);

                        TotalPost::where('id', $totalPostId)->Update([
                            'factorstatus' => 'close',
                            //'serviceNumberParcel' => '4ba8bd7b-84b2-49c9-a03d-1c0273b05a53',
                        ]);

                        sendSubmitSms($totalPostId);
                    } else {
                        $result = (object)[
                            'data' => $res->message,
                            'stats' => 400,
                        ];
                        return Response::json($result, 400);
                    }
                }else if ($totalPosts->componyId == 1) {
                    $res = postRequest($totalPostId);
                    if ($res->ResMsg == "موفق") {
                        $status = paybyWallet($request->user()->id, $totalPostId, $request->dc);

                        TotalPost::where('id', $totalPostId)->Update([
                            'factorstatus' => 'close',
                            // 'serviceNumberParcel' => '4ba8bd7b-84b2-49c9-a03d-1c0273b05a53',
                        ]);

                        if ($totalPosts->byAgent == 0){
                            sendSubmitSms($totalPostId);
                        }

                    } else {
                        $result = (object)[
                            'data' => $res->ResMsg,
                            'stats' => 400,
                        ];
                        return Response::json($result, 400);
                    }
                }
            } else {

            }
        }
        if ($type == 1 or $type == 2) {

            $result = (object)[
                'data' => 'http://avaex.ir/successful/' . $totalPostIda,
                'stats' => 200,
            ];
            return Response::json($result, 200);
        } else {
            $result = (object)[
                'stats' => 200,
                'data' => 'https://back.avaex.ir/api/totalpost/v1/zarinpal/request?userId=' . $request->user()->id . '&totalId=' . $totalPostId . '&dc=' . $request->dc,
            ];
            return Response::json($result, 200);
        }
    }


    public
    function request(Request $request)
    {

        $postItem = InternalOrder::where('internalPostId', $request->totalId)->get();

        $packaginPrice = 0;

        foreach ($postItem as $value) {
            $packaginPrice = $packaginPrice + $value->packaging;
        }

        //$realPayable = ($totalPost->Payable) + ($packaginPrice * 1.09);


        $amont = TotalPost::find($request->totalId)->Payable + ($packaginPrice * 1.09);

        $walletId = Wallet::create([

            'userId' => $request->userId,
            'amount' => $amont,
            'cash' => User::find($request->userId)->wallet,
            'type' => 'واریز',
            'status' => 'معلق'

        ])->id;
        $dc = "0";
        if ($request->dc) {
            $dc = $request->dc;
        }
        $response = zarinpal()
            ->amount($amont) // مبلغ تراکنش
            ->request()
            ->description('واریز به کیف پول') // توضیحات تراکنش
            ->callbackUrl('https://back.avaex.ir/api/totalpost/v1/zarinpal/verification/' . $request->userId . '/' . $amont . '/' . $request->totalId . '/' . $walletId . '/' . $dc) // آدرس برگشت پس از پرداخت
            ->send();

        if (!$response->success()) {
            return $response->error()->message();
        }

// ذخیره اطلاعات در دیتابیس
        $response->authority();
        return $response->redirect();
// هدایت مشتری به درگاه پرداخت
//        return $response->redirect();

        /*$response = (object)[
            'data' =>  $response->redirect()
        ];
        return Response::json($response, 200);*/


    }

    public
    function verification($userId, $amount, $totalId, $walletId, $dc)
    {
        $authority = request()->query('Authority'); // دریافت کوئری استرینگ ارسال شده توسط زرین پال
        $status = request()->query('Status'); // دریافت کوئری استرینگ ارسال شده توسط زرین پال

        $response = zarinpal()
            ->amount($amount)
            ->verification()
            ->authority($authority)
            ->send();

        if (!$response->success()) {

//            return $response->error()->message();
            return redirect('http://avaex.ir/failed');


        } else {

            Wallet::where('id', $walletId)->update([
                'trackingCode' => $response->referenceId(),
                'cash' => User::find($userId)->wallet + $amount,
                'status' => 'واریز به کیف پول'

            ]);
            User::where('id', $userId)->update([
                'wallet' => User::find($userId)->wallet + $amount,

            ]);


            $totalPosts = TotalPost::find($totalId);


            if ($totalPosts->componyId == 3) {
                $res = chaparRequest($totalId);
                if ($res->result && $res->result == 'true') {
                    $status = paybyWallet($userId, $totalId, $dc);

                    TotalPost::where('id', $totalId)->Update([
                        'factorstatus' => 'close'
                    ]);

                    foreach ($res->objects->result as $item) {
                        InternalOrder::where('partnumber', $item->reference)->Update([
                            'serviceNumberParcel' => $item->tracking,
                            'servicePartnumber' => $item->package[0]
                        ]);
                    }

                    sendSubmitSms($totalId);
                    $result = (object)[
                        'data' => 'http://avaex.ir/successful/' . $totalId,
                        'stats' => 200,
                    ];
                    return Response::json($result, 200);


                } else {
                    $result = (object)[
                        'data' => $res->message,
                        'stats' => 400,
                    ];
                    return Response::json($result, 400);


                }

            } else if ($totalPosts->componyId == 2) {
                $res = mahexRequest($totalId);
                if ($res->result) {
                    $status = paybyWallet($userId, $totalId, $dc);

                    TotalPost::where('id', $totalId)->Update([
                        'factorstatus' => 'close'
                    ]);
                    foreach ($res->objects->result as $item) {
                        InternalOrder::where('partnumber', $item->reference)->Update([
                            'serviceNumberParcel' => $item->tracking,
                            'servicePartnumber' => $item->package[0]
                        ]);
                    }

                    sendSubmitSms($totalId);
                    $result = (object)[
                        'data' => 'http://avaex.ir/successful/' . $totalId,
                        'stats' => 200,
                    ];
                    return Response::json($result, 200);


                } else {
                    $result = (object)[
                        'data' => $res->message,
                        'stats' => 400,
                    ];
                    return Response::json($result, 400);


                }

            }


        }

// دریافت هش شماره کارتی که مشتری برای پرداخت استفاده کرده است
// $response->cardHash();

// دریافت شماره کارتی که مشتری برای پرداخت استفاده کرده است (بصورت ماسک شده)
// $response->cardPan();

// پرداخت موفقیت آمیز بود
// دریافت شماره پیگیری تراکنش و انجام امور مربوط به دیتابیس
//        return $response->referenceId();
        /* $response = (object)[
             'data' => $response->referenceId()
         ];
         return Response::json($response, 200);*/


    }


    public
    function preview($id)
    {

        $factors = explode(",", $id);
        $datas = [];

        foreach ($factors as $item) {

            $totalPosts = TotalPost::find($item);
            $sender = Address::find($totalPosts->addressId);

            if ($sender) {
                $senderCity = City::find($sender->cityId)->faName;
                $senderProvice = Province::find($sender->provinceId)->faName;
            } else {
                $senderCity = "0";
                $senderProvice = "0";
            }


            $address = (object)[
                'sender' => $sender,
                'city' => $senderCity,
                'provice' => $senderProvice,
            ];


            $getter = Address::find($totalPosts->getterAddressId);

            if ($getter) {
                $getterCity = City::find($getter->cityId)->faName;
                $getterProvice = Province::find($getter->provinceId)->faName;

            } else {
                $getterCity = "0";
                $getterProvice = "0";
            }

            $geteradress = (object)[
                'getter' => $getter,
                'city' => $getterCity,
                'provice' => $getterProvice,
            ];
            $componyservice = ComponyService::find($totalPosts->componyServicesId);
            $compony = Compony::find($componyservice->componyId)->name;
            $service = Service::find($componyservice->serviceId)->name;
            $componytype = ComponyTypePost::find($componyservice->componyTypeId)->name;
            $componyservice = (object)[
                'compony' => $compony,
                'service' => $service,
                'componytype' => $componytype,
                'componyservice' => $componyservice,
            ];

            if ($totalPosts->typeSerId == 1) {
                $post = InternalPost::where('totalPostId', $totalPosts->id)->first();
                $parts = InternalOrder::where('internalPostId', $post->id)->get();


            } elseif ($totalPosts->typeSerId == 2) {
                $post = ExternalPost::where('totalPostId', $totalPosts->id)->first();
                $parts = ExternalOrder::where('externalPostId', $post->id)->get();


            } elseif ($totalPosts->typeSerId == 3) {
                $post = PeykPost::where('totalPostId', $totalPosts->id)->first();
                $parts = PeykOrder::where('externalPostId', $post->id)->get();


            } else {
                $post = HeavyPost::where('totalPostId', $totalPosts->id)->first();
                $parts = HeavyOrder::where('externalPostId', $post->id)->get();

            }

            $res = (object)[
                'item' => $totalPosts,
                'date' => dateTimeToDate($totalPosts->updated_at),
                'time' => dateTimeToTime($totalPosts->updated_at),
                'componyservice' => $componyservice,
                'post' => $post,
                'address' => $address,
                'geteradress' => $geteradress,
                'partsdetail' => $parts,
            ];

            array_push($datas, $res);

        }

        $result = (object)[
            'data' => $datas,
            'wallet' => Auth::user()->wallet,
        ];
        return Response::json($result, 200);


    }

    public
    function detail($id)
    {

        $factors = explode(",", $id);
        $datas = [];
        foreach ($factors as $item) {
            $totalPosts = TotalPost::find($item);


            $sender = Address::withTrashed()->find($totalPosts->addressId);

            if ($sender) {
                $senderCity = City::find($sender->cityId)->faName;
                $senderProvice = Province::find($sender->provinceId)->faName;
            } else {
                $senderCity = "0";
                $senderProvice = "0";
            }


            $address = (object)[
                'sender' => $sender,
                'city' => $senderCity,
                'provice' => $senderProvice,
            ];


            $getter = Address::find($totalPosts->getterAddressId);
            if ($getter) {
                $getterCity = City::find($getter->cityId)->faName;
                $getterProvice = Province::find($getter->provinceId)->faName;

            } else {
                $getterCity = "0";
                $getterProvice = "0";
            }

            $geteradress = (object)[
                'getter' => $getter,
                'city' => $getterCity,
                'provice' => $getterProvice,
            ];
            $componyservice = ComponyService::find($totalPosts->componyServicesId);
            $compony = Compony::find($componyservice->componyId);
            $service = Service::find($componyservice->serviceId)->name;
            $componytype = ComponyTypePost::find($componyservice->componyTypeId)->name;
            $componyservice = (object)[
                'compony' => $compony->name,
                'componyImage' => $compony->image,
                'service' => $service,
                'componytype' => $componytype,
                'componyservice' => $componyservice,
            ];


            if ($totalPosts->typeSerId == 1) {
                $post = InternalPost::where('totalPostId', $totalPosts->id)->first();
                $parts = InternalOrder::where('internalPostId', $post->id)->get();
            } elseif ($totalPosts->typeSerId == 2) {
                $post = ExternalPost::where('totalPostId', $totalPosts->id)->first();
                $parts = ExternalOrder::where('externalPostId', $post->id)->get();


            } elseif ($totalPosts->typeSerId == 3) {
                $post = PeykPost::where('totalPostId', $totalPosts->id)->first();
                $parts = PeykOrder::where('externalPostId', $post->id)->get();


            } else {
                $post = HeavyPost::where('totalPostId', $totalPosts->id)->first();
                $parts = HeavyOrder::where('externalPostId', $post->id)->get();

            }

            $res = (object)[
                'item' => $totalPosts,
                'date' => dateTimeToDate($totalPosts->updated_at),
                'time' => dateTimeToTime($totalPosts->updated_at),
                'componyservice' => $componyservice,
                'post' => $post,
                'address' => $address,
                'geteradress' => $geteradress,
                'partsdetail' => $parts,
            ];

            array_push($datas, $res);

        }
        $result = (object)[
            'data' => $datas
        ];
        return Response::json($result, 200);

    }

    public
    function detailByBarname($id)
    {

        $factors = explode(",", $id);
        $datas = [];


        foreach ($factors as $item) {
            $totalPosts = TotalPost::where('numberParcel', $item)->first();

            $sender = Address::withTrashed()->find($totalPosts->addressId);
            if ($sender) {
                $senderCity = City::find($sender->cityId)->faName;
                $senderProvice = Province::find($sender->provinceId)->faName;
            } else {
                $senderCity = "0";
                $senderProvice = "0";
            }


            $address = (object)[
                'sender' => $sender,
                'city' => $senderCity,
                'provice' => $senderProvice,
            ];


            $getter = Address::find($totalPosts->getterAddressId);
            if ($getter) {
                $getterCity = City::find($getter->cityId)->faName;
                $getterProvice = Province::find($getter->provinceId)->faName;

            } else {
                $getterCity = "0";
                $getterProvice = "0";
            }

            $geteradress = (object)[
                'getter' => $getter,
                'city' => $getterCity,
                'provice' => $getterProvice,
            ];
            $componyservice = ComponyService::find($totalPosts->componyServicesId);
            $compony = Compony::find($componyservice->componyId);
            $service = Service::find($componyservice->serviceId)->name;
            $componytype = ComponyTypePost::find($componyservice->componyTypeId)->name;
            $componyservice = (object)[
                'compony' => $compony->name,
                'componyImage' => $compony->image,
                'service' => $service,
                'componytype' => $componytype,
                'componyservice' => $componyservice,
            ];

            if ($totalPosts->typeSerId == 1) {
                $post = InternalPost::where('totalPostId', $totalPosts->id)->first();
                $parts = InternalOrder::where('internalPostId', $post->id)->get();


            } elseif ($totalPosts->typeSerId == 2) {
                $post = ExternalPost::where('totalPostId', $totalPosts->id)->first();
                $parts = ExternalOrder::where('externalPostId', $post->id)->get();


            } elseif ($totalPosts->typeSerId == 3) {
                $post = PeykPost::where('totalPostId', $totalPosts->id)->first();
                $parts = PeykOrder::where('externalPostId', $post->id)->get();


            } else {
                $post = HeavyPost::where('totalPostId', $totalPosts->id)->first();
                $parts = HeavyOrder::where('externalPostId', $post->id)->get();

            }

            $res = (object)[
                'item' => $totalPosts,
                'date' => dateTimeToDate($totalPosts->updated_at),
                'time' => dateTimeToTime($totalPosts->updated_at),
                'componyservice' => $componyservice,
                'post' => $post,
                'address' => $address,
                'geteradress' => $geteradress,
                'partsdetail' => $parts,
            ];

            array_push($datas, $res);

        }
        $result = (object)[
            'data' => $datas
        ];
        return Response::json($result, 200);

    }

    public
    function mainFactor($id)
    {
        $factors = explode(",", $id);
        $datas = [];

        foreach ($factors as $item) {
            $totalPosts = TotalPost::find($item);
            if ($totalPosts->typeSerId == 1) {
                $post = InternalPost::where('totalPostId', $totalPosts->id)->first();
                $parts = InternalOrder::where('internalPostId', $post->id)->get();

            } elseif ($totalPosts->typeSerId == 2) {
                $post = ExternalPost::where('totalPostId', $totalPosts->id)->first();
                $parts = ExternalOrder::where('externalPostId', $post->id)->get();


            } elseif ($totalPosts->typeSerId == 3) {
                $post = PeykPost::where('totalPostId', $totalPosts->id)->first();
                $parts = PeykOrder::where('externalPostId', $post->id)->get();


            } else {
                $post = HeavyPost::where('totalPostId', $totalPosts->id)->first();
                $parts = HeavyOrder::where('externalPostId', $post->id)->get();

            }

            $sender = Address::withTrashed()->find($totalPosts->addressId);
            if ($sender) {
                $senderCity = City::find($sender->cityId)->faName;
                $senderProvice = Province::find($sender->provinceId)->faName;
            } else {
                $senderCity = "0";
                $senderProvice = "0";
            }


            $address = (object)[
                'sender' => $sender,
                'city' => $senderCity,
                'provice' => $senderProvice,
            ];


            $getter = Address::find($totalPosts->getterAddressId);
            if ($getter) {
                $getterCity = City::find($getter->cityId)->faName;
                $getterProvice = Province::find($getter->provinceId)->faName;

            } else {
                $getterCity = "0";
                $getterProvice = "0";
            }

            $geteradress = (object)[
                'getter' => $getter,
                'city' => $getterCity,
                'provice' => $getterProvice,
            ];
            $componyservice = ComponyService::find($totalPosts->componyServicesId);
            $compony = Compony::find($componyservice->componyId)->name;
            $service = Service::find($componyservice->serviceId)->name;
            $componytype = ComponyTypePost::find($componyservice->componyTypeId)->name;
            $componyservice = (object)[
                'compony' => $compony,
                'image' => Compony::find($componyservice->componyId)->image,
                'service' => $service,
                'componytype' => $componytype,
                'componyservice' => $componyservice,
            ];

            foreach ($parts as $part) {
                if ($totalPosts->byAgent == 1) {
                    $PackagingPrice = $part->packaging;
                } else {
                    //$PackagingPrice = round($totalPosts->Packaging / $totalPosts->totalNumber);
                    $PackagingPrice = $part->packaging;
                }

                $Insurance = ($part->value) * (0.002);

                /*                    if (dataxa.item.byAgent == 1) {
                                        PackagingPrice = Math.round(data.packaging);
                                    } else {
                                        PackagingPrice = Math.round(dataxa.item['Packaging'] / dataxa.item.totalNumber);
                                    }*/

                $eachx = round(($totalPosts->Payable - 1.09 * ($totalPosts->Insurance)) / $totalPosts->totalNumber);
                $eachCOD = round($totalPosts->amountCOD / $totalPosts->totalNumber);

                $serviceInPlaceTaxItem = 0;
                if ($totalPosts->serviceInPlace and $totalPosts->serviceInPlace != 0) {
                    $serviceInPlaceTaxItem = round(($totalPosts->serviceInPlace * 0.09) / $totalPosts->totalNumber);
                }

                $eachxPart = round($eachx + $part->cost + ($PackagingPrice) * 1.09 + ($Insurance * 0.09));

                $eachxaa = $eachxPart + $serviceInPlaceTaxItem;

                $realPay = round($totalPosts->realPayable / $totalPosts->totalNumber);

                /*if(count($parts) ==1 and $totalPosts->realPayable){
                    $eachxaa = $totalPosts->realPayable;
                }*/
                $partDitail = (object)[
                    'partDitail' => $part,
                    'item' => $totalPosts,
                    'date' => dateTimeToDate($totalPosts->updated_at),
                    'time' => dateTimeToTime($totalPosts->updated_at),
                    'componyservice' => $componyservice,
                    'post' => $post,
                    'address' => $address,
                    'geteradress' => $geteradress,
                    /*                        'eachx' => round((($totalPosts->Payable - $totalPosts->Insurance) / $totalPosts->totalNumber) + $PackagingPrice * 1.09) + $part->cost,*/
                    'eachx' => $realPay,
                    'eachCOD' => round($part->amountCOD),
                ];

                array_push($datas, $partDitail);

            }
            if (count($parts) != 0) {
            }
        }
        $data = [
            'data' => $datas
        ];

        $totalPost1 = TotalPost::find($factors[0]);
        if ($totalPost1->componyId == 1){
//            return $data;
//             return view('Print.print1',$data);
            $pdf = PDF::loadView('Print.print1', $data, [], [
                'format' => 'A4-P'
            ]);

            return $pdf->stream('document.pdf');
        }else {

            $pdf = PDF::loadView('print', $data);
            return $pdf->stream("avaxfactor.pdf");
        }
        /*     $result = (object)[
                 'data' => $datas
             ];
             return Response::json($result, 200);*/

    }



    function mainFactor2($id)
    {
        $factors = explode(",", $id);
        $datas = [];


        foreach ($factors as $item) {
            $totalPosts = TotalPost::find($item);
            if ($totalPosts->typeSerId == 1) {
                $post = InternalPost::where('totalPostId', $totalPosts->id)->first();
                $parts = InternalOrder::where('internalPostId', $post->id)->get();


            } elseif ($totalPosts->typeSerId == 2) {
                $post = ExternalPost::where('totalPostId', $totalPosts->id)->first();
                $parts = ExternalOrder::where('externalPostId', $post->id)->get();


            } elseif ($totalPosts->typeSerId == 3) {
                $post = PeykPost::where('totalPostId', $totalPosts->id)->first();
                $parts = PeykOrder::where('externalPostId', $post->id)->get();


            } else {
                $post = HeavyPost::where('totalPostId', $totalPosts->id)->first();
                $parts = HeavyOrder::where('externalPostId', $post->id)->get();

            }

            $sender = Address::withTrashed()->find($totalPosts->addressId);
            if ($sender) {
                $senderCity = City::find($sender->cityId)->faName;
                $senderProvice = Province::find($sender->provinceId)->faName;
            } else {
                $senderCity = "0";
                $senderProvice = "0";
            }


            $address = (object)[
                'sender' => $sender,
                'city' => $senderCity,
                'provice' => $senderProvice,
            ];


            $getter = Address::find($totalPosts->getterAddressId);
            if ($getter) {
                $getterCity = City::find($getter->cityId)->faName;
                $getterProvice = Province::find($getter->provinceId)->faName;

            } else {
                $getterCity = "0";
                $getterProvice = "0";
            }

            $geteradress = (object)[
                'getter' => $getter,
                'city' => $getterCity,
                'provice' => $getterProvice,
            ];
            $componyservice = ComponyService::find($totalPosts->componyServicesId);
            $compony = Compony::find($componyservice->componyId)->name;
            $service = Service::find($componyservice->serviceId)->name;
            $componytype = ComponyTypePost::find($componyservice->componyTypeId)->name;
            $componyservice = (object)[
                'compony' => $compony,
                'image' => Compony::find($componyservice->componyId)->image,
                'service' => $service,
                'componytype' => $componytype,
                'componyservice' => $componyservice,
            ];


            if (count($parts) != 0) {
                foreach ($parts as $part) {
                    $partDitail = (object)[
                        'partDitail' => $part,
                        'item' => $totalPosts,
                        'date' => dateTimeToDate($totalPosts->updated_at),
                        'time' => dateTimeToTime($totalPosts->updated_at),
                        'componyservice' => $componyservice,
                        'post' => $post,
                        'address' => $address,
                        'geteradress' => $geteradress,
                        'eachx' => round(($totalPosts->Payable - $totalPosts->Insurance) / $totalPosts->totalNumber) + $part->cost,
                        'eachCOD' => round(($totalPosts->amountCOD) / $totalPosts->totalNumber),
                    ];
                    array_push($datas, $partDitail);

                }
            }
        }

        $data = [
            'data' => $datas
        ];
        $pdf = PDF::loadView('print', $data);
        //return $pdf->output();
        // return $pdf->save("avaxfactor.pdf");
        return $pdf->download("avaxfactor.pdf");
        return $pdf->stream("avaxfactor.pdf");
        /*     $result = (object)[
                 'data' => $datas
             ];
             return Response::json($result, 200);*/

    }

    public
    function detail1($id)
    {

        $totalPosts = TotalPost::find($id);
        $sender = Address::withTrashed()->find($totalPosts->addressId);
        if ($sender) {
            $senderCity = City::find($sender->cityId)->faName;
            $senderProvice = Province::find($sender->provinceId)->faName;
        } else {
            $senderCity = "0";
            $senderProvice = "0";
        }


        $address = (object)[
            'sender' => $sender,
            'city' => $senderCity,
            'provice' => $senderProvice,
        ];


        $getter = Address::find($totalPosts->getterAddressId);
        if ($getter) {
            $getterCity = City::find($getter->cityId)->faName;
            $getterProvice = Province::find($getter->provinceId)->faName;

        } else {
            $getterCity = "0";
            $getterProvice = "0";
        }

        $geteradress = (object)[
            'getter' => $getter,
            'city' => $getterCity,
            'provice' => $getterProvice,
        ];
        $componyservice = ComponyService::find($totalPosts->componyServicesId);
        $compony = Compony::find($componyservice->componyId)->name;
        $service = Service::find($componyservice->serviceId)->name;
        $componytype = ComponyTypePost::find($componyservice->componyTypeId)->name;
        $componyservice = (object)[
            'compony' => $compony,
            'service' => $service,
            'componytype' => $componytype,
            'componyservice' => $componyservice,
        ];

        if ($totalPosts->typeSerId == 1) {
            $post = InternalPost::where('totalPostId', $totalPosts->id)->first();
            $parts = InternalOrder::where('internalPostId', $post->id)->get();


        } elseif ($totalPosts->typeSerId == 2) {
            $post = ExternalPost::where('totalPostId', $totalPosts->id)->first();
            $parts = ExternalOrder::where('externalPostId', $post->id)->get();


        } elseif ($totalPosts->typeSerId == 3) {
            $post = PeykPost::where('totalPostId', $totalPosts->id)->first();
            $parts = PeykOrder::where('externalPostId', $post->id)->get();


        } else {
            $post = HeavyPost::where('totalPostId', $totalPosts->id)->first();
            $parts = HeavyOrder::where('externalPostId', $post->id)->get();

        }

        $res = (object)[
            'item' => $totalPosts,
            'date' => dateTimeToDate($totalPosts->updated_at),
            'time' => dateTimeToTime($totalPosts->updated_at),
            'componyservice' => $componyservice,
            'post' => $post,
            'address' => $address,
            'geteradress' => $geteradress,
            'partsdetail' => $parts,


        ];

        $result = (object)[
            'data' => $res
        ];
        return Response::json($result, 200);


    }

    public
    function update(Request $request)
    {
        TotalPost::where('id', $request->id)->Update([
            'status' => $request->status
        ]);
        $result = (object)[
            'data' => 'با موفقیت انجام شد.'
        ];
        return Response::json($result, 200);

    }

    public
    function groupUpdate(Request $request)
    {
        $itemss = explode(",", $request->itemId);
        foreach ($itemss as $value) {
            TotalPost::where('id', $value)->Update([
                'status' => $request->status
            ]);
        }
        $result = (object)[
            'data' => 'با موفقیت انجام شد.'
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


//            $externalPost = ExternalOrder::where('partnumber', (int)$request->partnumber)->first();
//            $peykPost = PeykOrder::where('partnumber',(int) $request->partnumber)->first();
//            $heavyPost = HeavyOrder::where('partnumber',(int) $request->partnumber)->first();
            if ($internalPost) {


                $post = InternalPost::where('id', $internalPost->internalPostId)->first();

                $factor = TotalPost::where('id', $post->totalPostId)->first();
                array_push($listNumberParcel, $factor->id);

            }

//            else if ($externalPost) {
//
//                $post =ExternalPost::where('id', $externalPost->externalPostId)->first();
//                $factor = TotalPost::where('id', $post->totalPostId)->first();
//                array_push($listNumberParcel,$factor->id);
//
//            }
//            else if ($peykPost) {
//                $post = PeykPost::where('id', $peykPost->peykPostId)->first();
//                $factor = TotalPost::where('id', $post->totalPostId)->first();
//                array_push($listNumberParcel,$factor->id);
//
//            }
//            else {
//                $post = HeavyPost::where('id', $peykPost->peykPostId)->first();
//                $factor = TotalPost::where('id', $post->totalPostId)->first();
//                array_push($listNumberParcel,$factor->id);
//            }

        }

        if ($request->weight) {

            $internalPost = InternalOrder::where('Weight', (int)$request->Weight)->first();
//            $externalPost = ExternalOrder::where('Weight',(int) $request->Weight)->first();
//            $peykPost = PeykOrder::where('Weight',(int) $request->Weight)->first();
//            $heavyPost = HeavyOrder::where('Weight', (int)$request->Weight)->first();
            if ($internalPost) {
                $post = InternalPost::where('id', $internalPost->internalPostId)->first();
                $factor = TotalPost::where('id', $post->totalPostId)->first();
                array_push($listNumberParcel, $factor->id);

            }
//            else if ($externalPost) {
//                $post =ExternalPost::where('id', $externalPost->externalPostId)->first();
//                $factor = TotalPost::where('id', $post->totalPostId)->first();
////                $val = (object)[
////                    'factor' =>$factor,
////                    'post' =>$post,
////                    'parts'=>$externalPost
////                ];
////                array_push($myParts,$val);
//                array_push($listNumberParcel,$factor->id);
//
//            }
//            else if ($peykPost) {
//                $post = PeykPost::where('id', $peykPost->peykPostId)->first();
//                $factor = TotalPost::where('id', $post->totalPostId)->first();
//                array_push($listNumberParcel,$factor->id);
//
//            }
//            else {
//                $post = HeavyPost::where('id', $peykPost->peykPostId)->first();
//                $factor = TotalPost::where('id', $post->totalPostId)->first();
//                array_push($listNumberParcel,$factor->id);
//            }

        }


        $query = TotalPost::where('userId', $request->user()->id)->where('factorstatus', 'close');

//        return ceil(count($query->get()) / $request->numberitems);

        if (count($listNumberParcel) > 0) {

            $query = $query->whereIn('id', $listNumberParcel);
        }

        if ($request->numberParcel) {
            $query = $query->where('numberParcel', (int)$request->numberParcel);
        }
        if ($request->startDate and !$request->endDate) {
            $query = $query->whereDate('created_at', '>', Carbon::parse(convertToMiladi($request->startDate)));
        } else if ($request->endDate and !$request->startDate) {
            $query = $query->whereDate('created_at', '<', Carbon::parse(convertToMiladi($request->endDate)));
        } else if ($request->startDate and $request->endDate) {
            $query = $query->whereBetween('created_at', [Carbon::parse(convertToMiladi($request->startDate)), Carbon::parse(convertToMiladi($request->endDate))]);
        }


        if ($request->status) {
            $query = $query->where('status', $request->status);

        }
        if ($request->componyTypeId) {
            $query = $query->where('componyTypeId', $request->componyTypeId);

        }
        if ($request->senderName) {

            $address = Address::where('userId', $request->user()->id)->where('senderOrgetter', 0)
                ->where('totalAddress', 'like', '%' . $request->senderName . '%')->pluck('id');
            $query = $query->whereIn('addressId', $address);

        }
        if ($request->getterName) {
            $address = Address::where('userId', $request->user()->id)->where('senderOrgetter', 1)
                ->where('totalAddress', 'like', '%' . $request->getterName . '%')->pluck('id');
            $query = $query->whereIn('getterAddressId', $address);

        }
        if ($request->factorstatus) {
            $query = $query->where('factorstatus', $request->factorstatus);

        }

        if ($request->senderProvince) {

            $addressSender = Address::where('userId', $request->user()->id)->where('senderOrgetter', 0)
                ->where('provinceId', $request->senderProvince)->pluck('id');
            $query = $query->whereIn('addressId', $addressSender);

        }
        if ($request->getterProvince) {
            $addressGetter = Address::where('userId', $request->user()->id)->where('senderOrgetter', 1)
                ->where('provinceId', $request->getterProvince)->pluck('id');
            $query = $query->whereIn('getterAddressId', $addressGetter);

        }
        if ($request->senderCity) {
            $addressSend = Address::where('userId', $request->user()->id)->where('senderOrgetter', 0)
                ->where('cityId', $request->senderCity)->pluck('id');

            $query = $query->whereIn('addressId', $addressSend);

        }
        if ($request->getterCity) {

            $address = Address::where('userId', $request->user()->id)->where('senderOrgetter', 1)
                ->where('cityId', $request->getterCity)->pluck('id');

            $query = $query->whereIn('getterAddressId', $address);

        }

        $numbers = count($query->get()) / $request->numberitems;

        $totalPost = $query->skip(($request->numberpage - 1) * $request->numberitems)->take($request->numberitems)->orderBy('id', 'DESC')->get();


        foreach ($totalPost as $item) {
            if ($item->typeSerId == 1) {
                $toalPrice = 0;
                $post = InternalPost::where('totalPostId', $item->id)->first();
                $parts = InternalOrder::where('internalPostId', $post->id)->get();
                $toalPrice = $toalPrice + $item->Payable + $item->Packaging * 1.09;
                $compony = Compony::find($item->componyId);
                $val = (object)[
                    'factor' => $item,
                    'post' => $post,
                    'parts' => $parts,
                    'compony' => $compony,
                    'toalPrice' => round($toalPrice),
                    'date' => dateTimeToDate($item->updated_at),
                    'time' => dateTimeToTime($item->updated_at),


                ];
                array_push($myParts, $val);


            } elseif ($item->typeSerId == 2) {
                $toalPrice = 0;
                $post = ExternalPost::where('totalPostId', $item->id)->first();
                $parts = ExternalOrder::where('externalPostId', $post->id)->get();
                $toalPrice = $toalPrice + $item->Payable + $item->Packaging * 1.09;
                $compony = Compony::find($item->componyId);
                $val = (object)[
                    'factor' => $item,
                    'post' => $post,
                    'parts' => $parts,
                    'compony' => $compony,
                    'toalPrice' => round($toalPrice),
                    'date' => dateTimeToDate($item->updated_at),
                    'time' => dateTimeToTime($item->updated_at),
                ];
                array_push($myParts, $val);

            } elseif ($item->typeSerId == 3) {
                $toalPrice = 0;
                $post = PeykPost::where('totalPostId', $item->id)->first();
                $parts = PeykOrder::where('externalPostId', $post->id)->get();
                $toalPrice = $toalPrice + $item->Payable + $item->Packaging * 1.09;
                $compony = Compony::find($item->componyId);
                $val = (object)[
                    'factor' => $item,
                    'post' => $post,
                    'parts' => $parts,
                    'compony' => $compony,
                    'toalPrice' => round($toalPrice),
                    'date' => dateTimeToDate($item->updated_at),
                    'time' => dateTimeToTime($item->updated_at),
                ];
                array_push($myParts, $val);

            } else {
                $toalPrice = 0;
                $post = HeavyPost::where('totalPostId', $item->id)->first();
                $parts = HeavyOrder::where('externalPostId', $post->id)->get();
                $toalPrice = $toalPrice + $item->Payable + $item->Packaging * 1.09;
                $compony = Compony::find($item->componyId);

                $val = (object)[
                    'factor' => $item,
                    'post' => $post,
                    'parts' => $parts,
                    'compony' => $compony,
                    'toalPrice' => round($toalPrice),
                    'date' => dateTimeToDate($item->updated_at),
                    'time' => dateTimeToTime($item->updated_at),
                ];
                array_push($myParts, $val);
            }
        }
        $res = (object)[
            'all' => $myParts,
            'number' => ceil($numbers),
            'UI' => $request->user()->id,
        ];

        $result = (object)[
            'data' => $res
        ];
        return Response::json($result, 200);

    }

    public function mypartExel(Request $request, $id)
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


        $query = TotalPost::where('userId', $id)->where('factorstatus', 'close');


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

            $address = Address::where('userId', $request->user()->id)->where('senderOrgetter', 0)
                ->where('totalAddress', 'like', '%' . $request->senderName . '%')->pluck('id');
            $query = $query->whereIn('addressId', $address->id);

        }
        if ($request->getterName) {
            $address = Address::where('userId', $request->user()->id)->where('senderOrgetter', 1)
                ->where('totalAddress', 'like', '%' . $request->getterName . '%')->pluck('id');
            $query = $query->whereIn('getterAddressId', $address->id);

        }
        if ($request->factorstatus) {
            $query = $query->whereIn('factorstatus', $request->factorstatus);

        }

        if ($request->senderProvince) {

            $address = Address::where('userId', $request->user()->id)->where('senderOrgetter', 0)
                ->where('provinceId', $request->senderProvince)->get('id');
            $query = $query->whereIn('addressId', $address->id);

        }
        if ($request->getterProvince) {
            $address = Address::where('userId', $request->user()->id)->where('senderOrgetter', 1)
                ->where('provinceId', $request->getterProvince)->pluck('id');
            $query = $query->whereIn('getterAddressId', $address->id);

        }
        if ($request->senderCity) {
            $address = Address::where('userId', $request->user()->id)->where('senderOrgetter', 0)
                ->where('cityId', $request->senderCity)->pluck('id');

            $query = $query->whereIn('addressId', $address->id);

        }
        if ($request->getterCity) {

            $address = Address::where('userId', $request->user()->id)->where('senderOrgetter', 1)
                ->where('cityId', $request->getterCity)->pluck('id');

            $query = $query->whereIn('getterAddressId', $address);

        }


        $totalPost = $query->orderBy('id', 'DESC')->get();


        foreach ($totalPost as $item) {
            if ($item->typeSerId == 1) {
                $toalPrice = 0;
                $post = InternalPost::where('totalPostId', $item->id)->first();
                $parts = InternalOrder::where('internalPostId', $post->id)->get();
                $toalPrice = $toalPrice + $item->Payable + $item->Packaging * 1.09;
                $compony = Compony::find($item->componyId);
                $val = (object)[
                    'factor' => $item,
                    'post' => $post,
                    'parts' => $parts,
                    'compony' => $compony,
                    'toalPrice' => round($toalPrice),
                    'date' => dateTimeToDate($item->updated_at),
                    'time' => dateTimeToTime($item->updated_at),


                ];
                array_push($myParts, $val);


            } elseif ($item->typeSerId == 2) {
                $toalPrice = 0;
                $post = ExternalPost::where('totalPostId', $item->id)->first();
                $parts = ExternalOrder::where('externalPostId', $post->id)->get();
                $toalPrice = $toalPrice + $item->Payable + $item->Packaging * 1.09;
                $compony = Compony::find($item->componyId);
                $val = (object)[
                    'factor' => $item,
                    'post' => $post,
                    'parts' => $parts,
                    'compony' => $compony,
                    'toalPrice' => round($toalPrice),
                    'date' => dateTimeToDate($item->updated_at),
                    'time' => dateTimeToTime($item->updated_at),
                ];
                array_push($myParts, $val);

            } elseif ($item->typeSerId == 3) {
                $toalPrice = 0;
                $post = PeykPost::where('totalPostId', $item->id)->first();
                $parts = PeykOrder::where('externalPostId', $post->id)->get();
                $toalPrice = $toalPrice + $item->Payable + $item->Packaging * 1.09;
                $compony = Compony::find($item->componyId);
                $val = (object)[
                    'factor' => $item,
                    'post' => $post,
                    'parts' => $parts,
                    'compony' => $compony,
                    'toalPrice' => round($toalPrice),
                    'date' => dateTimeToDate($item->updated_at),
                    'time' => dateTimeToTime($item->updated_at),
                ];
                array_push($myParts, $val);

            } else {
                $toalPrice = 0;
                $post = HeavyPost::where('totalPostId', $item->id)->first();
                $parts = HeavyOrder::where('externalPostId', $post->id)->get();
                $toalPrice = $toalPrice + $item->Payable + $item->Packaging * 1.09;
                $compony = Compony::find($item->componyId);

                $val = (object)[
                    'factor' => $item,
                    'post' => $post,
                    'parts' => $parts,
                    'compony' => $compony,
                    'toalPrice' => round($toalPrice),
                    'date' => dateTimeToDate($item->updated_at),
                    'time' => dateTimeToTime($item->updated_at),
                ];
                array_push($myParts, $val);
            }
        }
        $finalData = [];
        foreach ($myParts as $item) {
            $address = Address::with('countryName', 'provinceName', 'cityName')->find($item->factor->addressId);
            $getadress = Address::with('countryName', 'provinceName', 'cityName')->find($item->factor->getterAddressId);
            $res = array(
                $item->factor->numberParcel,
                $item->factor->MethodPayment,
                $item->factor->realPayable,
                $item->factor->status,
                $item->factor->Insurance,
                $item->factor->serviceInPlace,
                $item->factor->Packaging,
                $item->factor->totalCollectiveWeight,
                $item->factor->totalWeightPayable,
                $item->factor->totalGrossWeight,
                count($item->parts),
                $address->family . " " . $address->name,
                $address->cityName->faName,
                $getadress->family . " " . $getadress->name,
                $getadress->cityName->faName,
                $item->date,
                $item->time,

            );
            array_push($finalData, $res);

        }


        $export = new MyPartExport($finalData);

        return Excel::download($export, 'myPart.xlsx');

    }

    public function factor(Request $request)
    {
        $myParts = [];
        $query = TotalPost::where('userId', $request->user()->id);
        if ($request->startDate && !$request->endDate) {
            $query = $query->whereDate('created_at', '<', Carbon::parse(convertToMiladi($request->startDate)));
        } else if ($request->endDate && !$request->startDate) {
            $query = $query->whereDate('created_at', '>', Carbon::parse(convertToMiladi($request->endDate)));
        } else if ($request->endDate && $request->startDate) {
            $query = $query->whereBetween('created_at', [Carbon::parse(convertToMiladi($request->startDate)), Carbon::parse(convertToMiladi($request->endDate))]);
        }

        $totalPost = $query->get();
        foreach ($totalPost as $item) {
            if ($item->typeSerId == 1) {
                $post = InternalPost::where('totalPostId', $item->id)->first();
                $parts = InternalOrder::where('internalPostId', $post->id)->get();
                $val = (object)[
                    'factor' => $item,
                    'post' => $post,
                    'parts' => $parts
                ];
                array_push($myParts, $val);


            } elseif ($item->typeSerId == 2) {
                $post = ExternalPost::where('totalPostId', $item->id)->first();
                $parts = ExternalOrder::where('externalPostId', $post->id)->get();
                $val = (object)[
                    'factor' => $item,
                    'post' => $post,
                    'parts' => $parts
                ];
                array_push($myParts, $val);

            } elseif ($item->typeSerId == 3) {
                $post = PeykPost::where('totalPostId', $item->id)->first();
                $parts = PeykOrder::where('externalPostId', $post->id)->get();
                $val = (object)[
                    'factor' => $item,
                    'post' => $post,
                    'parts' => $parts
                ];
                array_push($myParts, $val);

            } else {
                $post = HeavyPost::where('totalPostId', $item->id)->first();
                $parts = HeavyOrder::where('externalPostId', $post->id)->get();
                $val = (object)[
                    'factor' => $item,
                    'post' => $post,
                    'parts' => $parts
                ];
                array_push($myParts, $val);
            }
        }

        $result = (object)[
            'data' => $myParts
        ];
        return Response::json($result, 200);


    }


    public function show(Request $request)
    {

        $data = [];

        $totalPosts = TotalPost::where('userId', $request->user()->id)->orderBy('id', 'DESC')->get();
        foreach ($totalPosts as $item) {
            $partsdetail = [];
            $address = Address::with('countryName', 'provinceName', 'cityName')->find($item->addressId);
            $getadress = Address::with('countryName', 'provinceName', 'cityName')->find($item->getterAddressId);
            $componyservice = ComponyService::with('compony')->with('service')->with('componyTypePost')->find($item->componyServicesId);

            if ($item->typeSerId == 1) {
                $post = InternalPost::where('totalPostId', $item->id)->first();
                $parts = InternalOrder::where('internalPostId', $post->id)->get();
                foreach ($parts as $part) {
                    $getadress = Address::with('countryName', 'provinceName', 'cityName')->find($part->getterAddressId);
                    $res = (object)[
                        'part' => $part,
//                        'getadress' => $getadress ,


                    ];
                    array_push($partsdetail, $res);

                }

            } elseif ($item->typeSerId == 2) {
                $post = ExternalPost::where('totalPostId', $item->id)->first();
                $parts = ExternalOrder::where('externalPostId', $post->id)->get();
                foreach ($parts as $part) {
                    $getadress = Address::with('countryName', 'provinceName', 'cityName')->find($part->getterAddressId);
                    $res = (object)[
                        'part' => $part,
                        'getadress' => $getadress,


                    ];
                    array_push($partsdetail, $res);

                }

            } elseif ($item->typeSerId == 3) {
                $post = PeykPost::where('totalPostId', $item->id)->first();
                $parts = PeykOrder::where('externalPostId', $post->id)->get();
                foreach ($parts as $part) {
                    $getadress = Address::with('countryName', 'provinceName', 'cityName')->find($part->getterAddressId);
                    $res = (object)[
                        'part' => $part,
                        'getadress' => $getadress,


                    ];
                    array_push($partsdetail, $res);

                }

            } else {
                $post = HeavyPost::where('totalPostId', $item->id)->first();
                $parts = HeavyOrder::where('externalPostId', $post->id)->get();
                foreach ($parts as $part) {
                    $getadress = Address::with('countryName', 'provinceName', 'cityName')->find($part->getterAddressId);
                    $res = (object)[
                        'part' => $part,
                        'getadress' => $getadress,


                    ];
                    array_push($partsdetail, $res);

                }
            }

            $res = (object)[
                'item' => $item,
                'date' => dateTimeToDate($item->updated_at),
                'componyservice' => $componyservice,
                'post' => $post,
                'address' => $address,
                'getadress' => $getadress,
                'partsdetail' => $partsdetail,

            ];
            array_push($data, $res);
        }
        $result = (object)[
            'data' => $data
        ];
        return Response::json($result, 200);
    }

    public function showExel(Request $request)
    {

        $data = [];
        return $data;

        $totalPosts = TotalPost::where('userId', $request->user()->id)->orderBy('id', 'DESC')->get();
        foreach ($totalPosts as $item) {
            $partsdetail = [];
            $address = Address::with('countryName', 'provinceName', 'cityName')->find($item->addressId);
            $getadress = Address::with('countryName', 'provinceName', 'cityName')->find($item->getterAddressId);
            $componyservice = ComponyService::with('compony')->with('service')->with('componyTypePost')->find($item->componyServicesId);

            if ($item->typeSerId == 1) {
                $post = InternalPost::where('totalPostId', $item->id)->first();
                $parts = InternalOrder::where('internalPostId', $post->id)->get();
                foreach ($parts as $part) {
                    $getadress = Address::with('countryName', 'provinceName', 'cityName')->find($part->getterAddressId);
                    $res = (object)[
                        'part' => $part,
//                        'getadress' => $getadress ,


                    ];
                    array_push($partsdetail, $res);

                }

            } elseif ($item->typeSerId == 2) {
                $post = ExternalPost::where('totalPostId', $item->id)->first();
                $parts = ExternalOrder::where('externalPostId', $post->id)->get();
                foreach ($parts as $part) {
                    $getadress = Address::with('countryName', 'provinceName', 'cityName')->find($part->getterAddressId);
                    $res = (object)[
                        'part' => $part,
                        'getadress' => $getadress,


                    ];
                    array_push($partsdetail, $res);

                }

            } elseif ($item->typeSerId == 3) {
                $post = PeykPost::where('totalPostId', $item->id)->first();
                $parts = PeykOrder::where('externalPostId', $post->id)->get();
                foreach ($parts as $part) {
                    $getadress = Address::with('countryName', 'provinceName', 'cityName')->find($part->getterAddressId);
                    $res = (object)[
                        'part' => $part,
                        'getadress' => $getadress,


                    ];
                    array_push($partsdetail, $res);

                }

            } else {
                $post = HeavyPost::where('totalPostId', $item->id)->first();
                $parts = HeavyOrder::where('externalPostId', $post->id)->get();
                foreach ($parts as $part) {
                    $getadress = Address::with('countryName', 'provinceName', 'cityName')->find($part->getterAddressId);
                    $res = (object)[
                        'part' => $part,
                        'getadress' => $getadress,


                    ];
                    array_push($partsdetail, $res);

                }
            }

            $res = (object)[
                'item' => $item,
                'date' => dateTimeToDate($item->updated_at),
                'componyservice' => $componyservice,
                'post' => $post,
                'address' => $address,
                'getadress' => $getadress,
                'partsdetail' => $partsdetail,

            ];
            array_push($data, $res);
        }
        /*  foreach ($wallet as $item) {
              $res =array(
                  $item->numberParcel,
                  $item->amount,
                  $item->cash,
                  $item->status,
                  $item->description,
                  dateTimeToDate($item->created_at),
                  dateTimeToTime($item->created_at),

              );
              array_push($all, $res);

          }*/


        $export = new MarsolatExport($all);

        return Excel::download($export, 'marsolat.xlsx');
    }

    public function check($number)
    {

        $totalId = [];

        $internalIds = InternalOrder::where('serviceNumberParcel', $number)->pluck('internalPostId')->toArray();


        if (count($internalIds) != 0) {

            $totalIds = InternalPost::whereIn('id', $internalIds)->pluck('totalPostId')->toArray();
            if (count($totalIds) != 0) {
                $totalId = $totalIds;
            }
        }


        $data = TotalPost::whereIn('id', $totalId)->orWhere(function ($query) use ($number) {
            $query->where('numberParcel', $number)->orWhere('serviceNumberParcel', $number);
        })->get();

        $result = (object)[
            'data' => $data
        ];
        return Response::json($result, 200);
    }

    public function checkBarname($id)
    {


        $allIds = explode(',', $id);


        foreach ($allIds as $totalPostId) {

            $responseee = true;

            $totalPost = TotalPost::find($totalPostId);

            if ($totalPost and $totalPost->serviceUuid and $totalPost->componyId == 2) {
                if ($totalPost->serviceNumberParcel) {

                } else {
                    $cURLConnection3 = curl_init();
                    $mahexToken = env('MAHEX_TOKEN');

                    $urlll3 = "http://api.mahex.com/v2/shipments/$totalPost->serviceUuid";


                    curl_setopt($cURLConnection3, CURLOPT_URL, $urlll3);
                    curl_setopt($cURLConnection3, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($cURLConnection3, CURLOPT_HTTPHEADER, array(
                        "Authorization: Basic $mahexToken",
                        'Content-Type: application/json'
                    ));
                    $phoneList = curl_exec($cURLConnection3);
                    curl_close($cURLConnection3);
                    $response = json_decode($phoneList);


                    if ($response->status->code == 200) {
                        TotalPost::where('id', $totalPostId)->update([
                            'serviceNumberParcel' => $response->data->waybill_number
                        ]);
                        InternalOrder::where('internalPostId', $totalPostId)->update([
                            'serviceNumberParcel' => $response->data->waybill_number
                        ]);

                    } else {
                        $responseee = false;
                    }
                }


            } else {

            }
        }

        return $responseee;

    }

    public function deleteFactor($id)
    {
        $factors = explode(",", $id);
        $responseee = false;
        foreach ($factors as $item) {
            $totalPost = TotalPost::where('numberParcel', $item)->first();
            if ($totalPost and $totalPost->componyId) {
                $componyId = $totalPost->componyId;
                $serviceNumberParcel = $totalPost->serviceNumberParcel;
                if (!$serviceNumberParcel) {
                    $InternalOrder = InternalOrder::query()->where('internalPostId', $totalPost->id)->first();
                    if ($InternalOrder and $InternalOrder->serviceNumberParcel) {
                        $serviceNumberParcel = $InternalOrder->serviceNumberParcel;
                    }
                }
                if ($componyId == 2) {
                    if ($serviceNumberParcel) {
                        $cURLConnection3 = curl_init();
                        $mahexToken = env('MAHEX_TOKEN');

                        $urlll3 = "http://api.mahex.com/v2/shipments/$serviceNumberParcel/void";


                        curl_setopt($cURLConnection3, CURLOPT_URL, $urlll3);
                        curl_setopt($cURLConnection3, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($cURLConnection3, CURLOPT_CUSTOMREQUEST, "PUT");
                        curl_setopt($cURLConnection3, CURLOPT_HTTPHEADER, array(
                            "Authorization: Basic $mahexToken",
                            'Content-Type: application/json'
                        ));
                        $phoneList = curl_exec($cURLConnection3);
                        curl_close($cURLConnection3);
                        $response = json_decode($phoneList);
                        if ($response->status->code == 200) {
                            TotalPost::where('id', $totalPost->id)->update([
                                'status' => 'لغو شده'
                            ]);
                            InternalOrder::where('internalPostId', $totalPost->id)->update([
                                'status' => 'لغو شده'
                            ]);
                            $responseee = true;
                        } else {
                            $responseee = false;
                        }

                    } else {

                    }

                } elseif ($componyId == 3) {

                    $postdata = http_build_query(
                        array(
                            'input' => json_encode(array(
                                'user' => array(
                                    'username' => 'pishro.tarabar',
                                    'password' => 'ptm316Mj',
                                ),
                                'consignment_no' => $serviceNumberParcel,
                                'reason' => 'آزمایشی',
                            ))
                        )
                    );

                    $opts = array(
                        'http' =>
                            array(
                                'method' => 'POST',
                                'header' => 'Content-Type: application/x-www-form-urlencoded',
                                'content' => $postdata
                            )
                    );

                    $context = stream_context_create($opts);

                    $result = file_get_contents('https://app.krch.ir/v1/cancel_pickup', false, $context);
                    $res = json_decode($result);
                    TotalPost::where('id', $totalPost->id)->update([
                        'status' => 'لغو شده'
                    ]);
                    InternalOrder::where('internalPostId', $totalPost->id)->update([
                        'status' => 'لغو شده'
                    ]);

                }
            }
        }

        if ($responseee == true) {
            $this->returnWallet($id);
            $result = (object)[
                'data' => "مرسوله با موفقیت حذف شد",
                'status' => 200,
            ];
            return Response::json($result, 200);
        } else {
            $result = (object)[
                'data' => "امکان حذف این مرسوله وجود ندارد",
                'status' => 400,
            ];
            return Response::json($result, 200);
        }

    }

    public function returnWallet($id)
    {
        $factors = explode(",", $id);

        foreach ($factors as $item) {

            $totalPost = TotalPost::where('numberParcel', $item)->first();

            $serviceType = "پیشکرایه";

            if ($totalPost->isAfterRent) {
                $serviceType = "پسکرایه";
            }

            if ($totalPost->isCod) {
                $serviceType = "COD";
            }

            $agentId = Agent::find($totalPost->agentId);

            if ($serviceType == "پیشکرایه") {

                $avaexPrice = Wallet::query()->where('numberParcel', $item)->where('userId', 15)->sum('amount');
                $userPrice = Wallet::query()->where('numberParcel', $item)->where('userId', $totalPost->userId)->where('type','برداشت')->sum('amount');
                $agentPrice = Wallet::query()->where('numberParcel', $item)->where('userId', $agentId->userId)->where('type','واریز')->sum('amount');
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


            } elseif ($serviceType == "پسکرایه") {

                $servicePrice = Wallet::query()->where('numberParcel', $item)->where('userId', $totalPost->componyId)->sum('amount');

                Wallet::create([
                    'userId' => $totalPost->componyId,
                    'amount' => $servicePrice,
                    'cash' => User::find($totalPost->componyId)->wallet + $servicePrice,
                    'numberParcel' => $totalPost->numberParcel,
                    'type' => 'واریز',
                    'status' => 'افزایش اعتبار',
                    'description' => 'بابت لغو مرسوله' . $totalPost->numberParcel . 'به حساب شما واریز شد.',
                    'componyId' => $totalPost->componyId,
                    'serviceType' => $serviceType,
                ]);
                User::where('id', $totalPost->componyId)->update([
                    'wallet' => User::find($totalPost->componyId)->wallet + $servicePrice,
                ]);

            } elseif ($serviceType == "COD") {
                $servicePrice = Wallet::query()->where('numberParcel', $item)->where('userId', $totalPost->componyId)->sum('amount');

                Wallet::create([
                    'userId' => $totalPost->componyId,
                    'amount' => $servicePrice,
                    'cash' => User::find($totalPost->componyId)->wallet + $servicePrice,
                    'numberParcel' => $totalPost->numberParcel,
                    'type' => 'واریز',
                    'status' => 'افزایش اعتبار',
                    'description' => 'بابت لغو مرسوله' . $totalPost->numberParcel . 'به حساب شما واریز شد.',
                    'componyId' => $totalPost->componyId,
                    'serviceType' => $serviceType,
                ]);
                User::where('id', $totalPost->componyId)->update([
                    'wallet' => User::find($totalPost->componyId)->wallet + $servicePrice,
                ]);
            }
            return 'ok';
        }
    }
}
