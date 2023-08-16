<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Response;
use Modules\Address\Entities\Address;
use Modules\Address\Entities\City;
use Modules\Agent\Entities\Agent;
use Modules\Compony\Entities\ComponyTypePost;
use Modules\InternalPost\Entities\InternalOrder;
use Modules\InternalPost\Entities\InternalPost;
use Modules\TotalPost\Entities\TotalPost;
use Modules\User\Entities\User;


/**
 * @OA\Info(
 *     title="پستاپ ",
 *     description="توضیحات",
 *     version="1",
 * )
 *  @OA\SecurityScheme(
 *  type="http",
 *  description="Authentication Bearer Token",
 *  name="Authentication Bearer Token",
 *  in="header",
 *  scheme="bearer",
 *  bearerFormat="JWT",
 *  securityScheme="apiAuth",
 *  )
 */


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function request(Request $request)
    {
        $amont = TotalPost::find($request->totalId)->Payable;

        $walletId = Wallet::create([

            'userId' => $request->userId,
            'amount' => $amont,
            'cash' => User::find($request->userId)->wallet,
            'type' => 'واریز',
            'status' => 'معلق'

        ])->id;

        $response = zarinpal()
            ->amount($amont) // مبلغ تراکنش
            ->request()
            ->description('واریز به کیف پول') // توضیحات تراکنش
            ->callbackUrl('https://back.postupex.ir/api/internalpost/v1/zarinpal/verification/' . $request->userId . '/' . $amont . '/' . $request->totalId . '/' . $walletId) // آدرس برگشت پس از پرداخت
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

    public function verification($userId, $amount, $totalId, $walletId,$dc)
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
            return redirect('https://postupex.ir/failed');


        } else {

            Wallet::where('id', $walletId)->update([
                'trackingCode' => $response->referenceId(),
                'cash' => User::find($userId)->wallet + $amount,
                'status' => 'واریز به کیف پول'

            ]);


            $allItems = [];
            $totalPost = TotalPost::find($totalId);
            $PostId = InternalPost::where('totalPostId', $totalId)->first();
            $dasd = InternalOrder::where('internalPostId', $PostId->id)->get();
            $typeService = ComponyTypePost::find($totalPost->typeSerId);
            $senderAddress = Address::find($totalPost->addressId);
            $senderCity = City::find($senderAddress->cityId);
            $getterAddress = Address::find($totalPost->getterAddressId);
            $getterCity = City::find($getterAddress->cityId);
            $agent = Agent::where('cityId', $senderAddress->cityId)->first();
            if ($totalPost->componyId == 3) {
                $res = $this->chaparRequest($totalId);
                if ($res->result) {
                    $status = $this->paybyWallet($userId, $totalPost->componyId, $agent->userId, $amount, $totalId,$dc);

                    TotalPost::where('id', $totalId)->Update([
                        'factorstatus' => 'close'
                    ]);
                    foreach ($res->objects->result as $item) {

                        InternalOrder::where('partnumber', $item->reference)->Update([
                            'serviceNumberParcel' => $item->tracking,
                            'servicePartnumber' => $item->package[0]
                        ]);
                    }

                    return redirect('https://postupex.ir/successful');


                } else {
                    $result = (object)[
                        'data' => $res->message,
                    ];
                    return Response::json($result, 400);


                }

            }

//            foreach ($dasd as $item) {
//
//                $part = array(
//                    'cn' => array(
//                        'reference' => $item->partnumber,
//                        'date' => Carbon::now()->format('Y-m-d'),
//                        'assinged_pieces' => 1,
//                        'service' => $typeService->chaparId,
//                        'value' => $item->cost,
//                        'payment_term' => $totalPost->MethodPayment,
//                        'weight' => $item->weight,
//                        'content' => $item->content,
//                    ),
//                    'sender' => array(
//                        'person' => $senderAddress->name,
//                        'company' => $senderAddress->compony,
//                        'city_no' => $senderCity->chaparNumber,
//                        'telephone' => $senderAddress->phone,
//                        'mobile' => $senderAddress->phone,
//                        'email' => $senderAddress->email,
//                        'address' => $senderAddress->address,
//                        'postcode' => $senderAddress->postCode,
//                    ),
//                    'receiver' => array(
//                        'person' => $getterAddress->name,
//                        'company' => $getterAddress->compony,
//                        'city_no' => $getterCity->chaparNumber,
//                        'telephone' => $getterAddress->phone,
//                        'mobile' => $getterAddress->phone,
//                        'email' => $getterAddress->email,
//                        'address' => $getterAddress->address,
//                        'postcode' => $getterAddress->postCode,
//                    ),
//                );
//                array_push($allItems, $part);
//            }
//
//
//            $postdata = http_build_query(
//                array(
//                    'input' => json_encode(array(
//                        'user' => array(
//                            'username' => 'pishro.tarabar',
//                            'password' => 'ptm316Mj',
//                        ),
//                        'bulk' => $allItems,
//                    ))
//                )
//            );
//
//            $opts = array(
//                'http' =>
//                    array(
//                        'method' => 'POST',
//                        'header' => 'Content-Type: application/x-www-form-urlencoded',
//                        'content' => $postdata
//                    )
//            );
//
//
//            $context = stream_context_create($opts);
//
//
//            $result = file_get_contents('https://app.krch.ir/v1/bulk_import', false, $context);
//            $res = json_decode($result);
//


//            $walletId =  Wallet::create([
//                'userId' => $userId,
//                'amount' => $amount,
//                'numberParcel'=>$res->objects->result[0]->tracking,
//                'type' => 'برداشت',
//                'status' => 'کسر از کیف پول'
//
//            ])->id;
//            Wallet::create([
//                'userId' => 15,
//                'amount' => ($amount *100) /100,
//                'fatherId'=>$walletId,
//                'numberParcel'=>$res->objects->result[0]->tracking,
//                'type' => 'واریز',
//                'status' => 'افزایش اعتبار'
//
//            ]);
//
//
//
//
//
//            TotalPost::where('id', $totalPost->id)->Update([
//                'numberParcel' => $res->objects->result[0]->tracking,
//                'factorstatus' => 'close'
//            ]);


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


}
