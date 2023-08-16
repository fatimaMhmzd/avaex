<?php

use Carbon\Carbon;
use Hekmatinasser\Verta\Facades\Verta;
use Illuminate\Http\UploadedFile;
use Modules\Address\Entities\Address;
use Modules\Address\Entities\City;
use Modules\Agent\Entities\Agent;
use Modules\Compony\Entities\ComponyTypePost;
use Modules\Discount\Entities\Discount;
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
use Modules\Agent\Entities\Driver;

if (!function_exists('uploadImage')) {
    function uploadImage(UploadedFile $file, $maxWidth = 320, $path = null)
    {

        $img = Image::make($file);


        $img->resize($maxWidth, null, function ($constraint) {
            $constraint->aspectRatio();
        });

        if ($path) {

        } else {
            $path = 'common';
        }

        $imageName = rand(11111, 99999) . time() . '.' . $file->getClientOriginalExtension();

        $imageAddres = url('/') . '/' . $path . '/' . $imageName;


        $img->save(public_path($path) . '/' . $imageName);

        return $imageAddres;


    }
}
if (!function_exists('calculatCollector')) {
    function calculatCollector($totalCollector, $key, $item)
    {
        $collector = 290423 * (exp(0.0349 * $item['massUnround']));
        if (($key % 4) == 0) {
            $realCollectorPrice[] = $collector;
            $totalCollector += $collector;
        } elseif (($key % 4) == 1) {
            $totalCollector += ($collector * 0.8);
            $realCollectorPrice[] = $collector * 0.8;
        } elseif (($key % 4) == 2) {
            $totalCollector += ($collector * 0.7);
            $realCollectorPrice[] = $collector * 0.7;
        } elseif (($key % 4) == 3) {
            $totalCollector += ($collector * 0.6);
            $realCollectorPrice[] = $collector * 0.6;
        }

        return 'asdasd';


    }
}
if (!function_exists('dateTimeJalali')) {

    function dateTimeJalali($date)
    {
        $miladidates = explode(' ', $date);

        $miladidate = explode('-', $miladidates[0]);

        $miladidateArray = Verta::getJalali($miladidate[0], $miladidate[1], $miladidate[2]);


        $miladiBirthDay = $miladidateArray[0] . '-' . $miladidateArray[1] . '-' . $miladidateArray[2];
        return $miladiBirthDay;
    }
}

if (!function_exists('convertToShamsi')) {
    function convertToShamsi($miladiDate)
    {
        $miladiDateArray = explode('-', $miladiDate);
        $shamsiDateArray = Verta::getJalali($miladiDateArray[0], $miladiDateArray[1], $miladiDateArray[2]);
        $shamsiDate = "$shamsiDateArray[0]/$shamsiDateArray[1]/$shamsiDateArray[2]";
        return $shamsiDate;
    }
}


if (!function_exists('convertToMiladi')) {
    function convertToMiladi($shamsiDate)
    {
        $shamsiDateArray = explode('/', $shamsiDate);

        foreach ($shamsiDateArray as $key => $item) {
            if (is_string($item)) {
                $shamsiDateArray[$key] = (int)$item;
            }
        }

        $miladiDateArray = Verta::getGregorian($shamsiDateArray[0], $shamsiDateArray[1], $shamsiDateArray[2]);
        $miladiDate = "$miladiDateArray[0]-$miladiDateArray[1]-$miladiDateArray[2]";
        return $miladiDate;
    }
}

if (!function_exists('dateTimeToDate')) {
    function dateTimeToDate($miladiDate)
    {
        $dateArray = explode(' ', $miladiDate);
        $miladiDateArray = explode('-', $dateArray[0]);

        $shamsiDateArray = Verta::getJalali($miladiDateArray[0], $miladiDateArray[1], $miladiDateArray[2]);
        $shamsiDate = "$shamsiDateArray[0]/$shamsiDateArray[1]/$shamsiDateArray[2]";
        return $shamsiDate;
    }
}
if (!function_exists('dateTimeToTime')) {
    function dateTimeToTime($miladiDate)
    {
        $dateArray = explode(' ', $miladiDate);
        $time = "$dateArray[1]";
        return $time;
    }
}


if (!function_exists('paybyWallet1')) {

    function paybyWallet1($userId, $totalId)
    {

        $totalPost = TotalPost::find($totalId);
        $senderAddress = Address::find($totalPost->addressId);
        $agentId = Agent::where('cityId', $senderAddress->cityId)->first();

        $walletId = Wallet::create([
            'userId' => $userId,
            'amount' => $totalPost->Payable,
            'cash' => User::find($userId)->wallet - $totalPost->Payable,
            'numberParcel' => $totalPost->numberParcel,
            'type' => 'برداشت',
            'status' => 'کسر از کیف پول',
            'description' => 'بابت سفارش' . $totalPost->numberParcel . 'از حساب شما کسر شد.',
        ])->id;

        User::where('id', $userId)->update([
            'wallet' => User::find($userId)->wallet - $totalPost->Payable,

        ]);
//        $walletId =  Wallet::create([
//            'userId' => $userId,
//            'amount' => ($totalPost->Freight *0.1),
//            'cash' => User::find($userId)->wallet + ($totalPost->Freight *0.1),
//            'numberParcel'=>$totalPost->numberParcel,
//            'fatherId'=>$walletId,
//            'type' => 'واریز',
//            'status' => 'افزایش اعتبار',
//            'description' => 'ده درصد از مبلغ سفارش'.$totalPost->numberParcel.' به عنوان هدیه از طرف پستاپ به حساب شما منظور گردید.',
//        ])->id;
//        User::where('id', $userId)->update([
//            'wallet' => User::find($userId)->wallet + ($totalPost->Freight *0.1),
//
//        ]);


        Wallet::create([
            'userId' => $totalPost->componyId,
            'amount' => $totalPost->Freight,
            'cash' => User::find($totalPost->componyId)->wallet + $totalPost->Freight,
            'fatherId' => $walletId,
            'numberParcel' => $totalPost->numberParcel,
            'type' => 'واریز',
            'status' => 'افزایش اعتبار',


        ]);

        User::where('id', $totalPost->componyId)->update([
            'wallet' => User::find($totalPost->componyId)->wallet + $totalPost->Freight,

        ]);


        Wallet::create([
            'userId' => 15,
            'amount' => $totalPost->ServicesAt,
            'cash' => User::find(15)->wallet + $totalPost->ServicesAt,
            'fatherId' => $walletId,
            'numberParcel' => $totalPost->numberParcel,
            'type' => 'واریز',
            'status' => 'افزایش اعتبار',
            'description' => 'خدمات فنی مهندسی 12 درصد 12 درصد'

        ]);
        User::where('id', 15)->update([
            'wallet' => User::find(15)->wallet + $totalPost->ServicesAt,

        ]);
        Wallet::create([
            'userId' => 15,
            'amount' => $totalPost->TAX,
            'cash' => User::find(15)->wallet + $totalPost->TAX,
            'fatherId' => $walletId,
            'numberParcel' => $totalPost->numberParcel,
            'type' => 'واریز',
            'status' => 'افزایش اعتبار',
            'description' => 'ارزش افزوده'


        ]);
        User::where('id', 15)->update([
            'wallet' => User::find(15)->wallet + $totalPost->TAX,

        ]);

        if ($totalPost->hasNotifRequest) {
            Wallet::create([
                'userId' => 15,
                'amount' => $totalPost->hasNotifRequest,
                'cash' => User::find(15)->wallet + $totalPost->hasNotifRequest,
                'fatherId' => $walletId,
                'numberParcel' => $totalPost->numberParcel,
                'type' => 'واریز',
                'status' => 'افزایش اعتبار',
                'description' => 'پیامک'


            ]);

            User::where('id', 15)->update([
                'wallet' => User::find(15)->wallet + $totalPost->hasNotifRequest,

            ]);
        }


        Wallet::create([
            'userId' => $agentId->userId,
            'amount' => $totalPost->collector,
            'cash' => User::find($agentId->userId)->wallet + $totalPost->collector,
            'fatherId' => $walletId,
            'numberParcel' => $totalPost->numberParcel,
            'type' => 'واریز',
            'status' => 'افزایش اعتبار',
            'description' => 'جمع آوری'


        ]);


        User::where('id', $agentId->userId)->update([
            'wallet' => User::find($agentId->userId)->wallet + $totalPost->collector,

        ]);

        if ($totalPost->packaging) {
            Wallet::create([
                'userId' => $agentId->userId,
                'amount' => $totalPost->packaging,
                'cash' => User::find($agentId->userId)->wallet + $totalPost->packaging,
                'fatherId' => $walletId,
                'numberParcel' => $totalPost->numberParcel,
                'type' => 'واریز',
                'status' => 'افزایش اعتبار',
                'description' => 'بسته بندی'


            ]);
            User::where('id', $agentId->userId)->update([
                'wallet' => User::find($agentId->userId)->wallet + $totalPost->packaging,

            ]);
        }
        if ($totalPost->printFactor) {

            Wallet::create([
                'userId' => $agentId->userId,
                'amount' => $totalPost->printFactor,
                'cash' => User::find($agentId->userId)->wallet + $totalPost->printFactor,
                'fatherId' => $walletId,
                'numberParcel' => $totalPost->numberParcel,
                'type' => 'واریز',
                'status' => 'افزایش اعتبار',
                'description' => 'چاپ فاکتور'

            ]);
            User::where('id', $agentId->userId)->update([
                'wallet' => User::find($agentId->userId)->wallet + $totalPost->printFactor,

            ]);
        }


        return 'ok';

    }

}
if (!function_exists('paybyWalletAgent')) {

    function paybyWalletAgent($userId, $totalId, $allForPostup = 0)
    {

        $totalPost = TotalPost::find($totalId);

        $agentId = Agent::find($totalPost->agentId);

        $walletId = Wallet::create([
            'userId' => $userId,
            'amount' => $totalPost->Payable,
            'cash' => User::find($userId)->wallet - $totalPost->Payable,
            'numberParcel' => $totalPost->numberParcel,
            'type' => 'برداشت',
            'status' => 'کسر از کیف پول',
            'description' => 'بابت سفارش' . $totalPost->numberParcel . 'از حساب شما کسر شد.',
        ])->id;

        User::where('id', $userId)->update([
            'wallet' => User::find($userId)->wallet - $totalPost->Payable,
        ]);


        Wallet::create([
            'userId' => $totalPost->componyId,
            'amount' => $totalPost->Freight,
            'cash' => User::find($totalPost->componyId)->wallet + $totalPost->Freight,
            'fatherId' => $walletId,
            'numberParcel' => $totalPost->numberParcel,
            'type' => 'واریز',
            'status' => 'افزایش اعتبار',


        ]);

        User::where('id', $totalPost->componyId)->update([
            'wallet' => User::find($totalPost->componyId)->wallet + $totalPost->Freight,

        ]);


        Wallet::create([
            'userId' => 15,
            'amount' => $totalPost->ServicesAt,
            'cash' => User::find(15)->wallet + $totalPost->ServicesAt,
            'fatherId' => $walletId,
            'numberParcel' => $totalPost->numberParcel,
            'type' => 'واریز',
            'status' => 'افزایش اعتبار',
            'description' => 'خدمات فنی مهندسی12درصد'

        ]);
        User::where('id', 15)->update([
            'wallet' => User::find(15)->wallet + $totalPost->ServicesAt,

        ]);
        Wallet::create([
            'userId' => 15,
            'amount' => $totalPost->TAX,
            'cash' => User::find(15)->wallet + $totalPost->TAX,
            'fatherId' => $walletId,
            'numberParcel' => $totalPost->numberParcel,
            'type' => 'واریز',
            'status' => 'افزایش اعتبار',
            'description' => 'ارزش افزوده 9 درصد'


        ]);
        User::where('id', 15)->update([
            'wallet' => User::find(15)->wallet + $totalPost->TAX,

        ]);


        if ($totalPost->userId == $agentId->userId) {

            if ($allForPostup == 1) {


                $feeAmountService = $totalPost->amountServices - ($totalPost->Packaging);
                $packajingProfit = 0;
                if ($totalPost->Packaging and $totalPost->Packaging != 0) {
                    $packajingProfit = $totalPost->Packaging - ($totalPost->Packaging / (1 + env('PACKAGING_PERCENT')));
                }
                /*sood kol az marsoole*/
                $feeAmountService = $feeAmountService + $packajingProfit * env('PACKAGING_PERCENT');

                $postupCash = $feeAmountService;

                $agentCash = (($totalPost->Freight) / 1.1) * 0.1;


                /*'amount' => $totalPost->Packaging - ($totalPost->Packaging / (1 + env('PACKAGING_PERCENT'))),
                    'cash' => User::find($agentId->userId)->wallet + ($totalPost->amountServices * 0.04),*/

                Wallet::create([
                    'userId' => 15,
                    'amount' => $postupCash,
                    'cash' => User::find(15)->wallet + $postupCash,
                    'fatherId' => $walletId,
                    'numberParcel' => $totalPost->numberParcel,
                    'type' => 'واریز',
                    'status' => 'افزایش اعتبار',
                    'description' => 'مجموع خدمات'
                ]);
                User::where('id', 15)->update([
                    'wallet' => User::find(15)->wallet + ($postupCash),

                ]);
                Wallet::create([
                    'userId' => $agentId->userId,
                    'amount' => $agentCash,
                    'cash' => User::find($agentId->userId)->wallet + $agentCash,
                    'fatherId' => $walletId,
                    'numberParcel' => $totalPost->numberParcel,
                    'type' => 'واریز',
                    'status' => 'افزایش اعتبار',
                    'description' => 'بابت سفارش' . $totalPost->numberParcel . 'به حساب شما واریز شد.',
                ]);


                User::where('id', $agentId->userId)->update([
                    'wallet' => User::find($agentId->userId)->wallet + ($totalPost->amountServices * 0.06),
                ]);
            } else {

                $feeAmountService = $totalPost->amountServices - ($totalPost->Packaging);
                $packajingProfit = 0;
                if ($totalPost->Packaging and $totalPost->Packaging != 0) {
                    $packajingProfit = $totalPost->Packaging - ($totalPost->Packaging / (1 + env('PACKAGING_PERCENT')));
                }
                /*sood kol az marsoole*/
                $feeAmountService = $feeAmountService + $packajingProfit * env('PACKAGING_PERCENT');

                $postupCash = $feeAmountService * 0.4;
                $agentCash = $feeAmountService * 0.6;


                Wallet::create([
                    'userId' => 15,
                    'amount' => $postupCash,
                    'cash' => User::find(15)->wallet + $postupCash,
                    'fatherId' => $walletId,
                    'numberParcel' => $totalPost->numberParcel,
                    'type' => 'واریز',
                    'status' => 'افزایش اعتبار',
                    'description' => 'مجموع خدمات'
                ]);
                User::where('id', 15)->update([
                    'wallet' => User::find(15)->wallet + ($postupCash),

                ]);
                Wallet::create([
                    'userId' => $agentId->userId,
                    'amount' => $agentCash,
                    'cash' => User::find($agentId->userId)->wallet + $agentCash,
                    'fatherId' => $walletId,
                    'numberParcel' => $totalPost->numberParcel,
                    'type' => 'واریز',
                    'status' => 'افزایش اعتبار',
                    'description' => 'بابت سفارش' . $totalPost->numberParcel . 'به حساب شما واریز شد.',
                ]);


                User::where('id', $agentId->userId)->update([
                    'wallet' => User::find($agentId->userId)->wallet + ($totalPost->amountServices * 0.06),
                ]);

            }

            if ($totalPost->serviceInPlace and $totalPost->serviceInPlace != 0) {

                $serviceInPla = $totalPost->serviceInPlace;

                $taxAmount = $serviceInPla * 0.09;

                $feeServiceInPla = $serviceInPla * 0.91;

                Wallet::create([
                    'userId' => $agentId->userId,
                    'amount' => $feeServiceInPla * 0.85,
                    'cash' => User::find($agentId->userId)->wallet + $feeServiceInPla * 0.85,
                    'fatherId' => $walletId,
                    'numberParcel' => $totalPost->numberParcel,
                    'type' => 'واریز',
                    'status' => 'افزایش اعتبار',
                    'description' => 'سرویس در محل'
                ]);

                User::where('id', $agentId->userId)->update([
                    'wallet' => User::find($agentId->userId)->wallet + $feeServiceInPla * 0.85,

                ]);
                Wallet::create([
                    'userId' => 15,
                    'amount' => $feeServiceInPla * 0.15,
                    'cash' => User::find(15)->wallet + ($feeServiceInPla * 0.15),
                    'fatherId' => $walletId,
                    'numberParcel' => $totalPost->numberParcel,
                    'type' => 'واریز',
                    'status' => 'افزایش اعتبار',
                    'description' => 'سرویس در محل'


                ]);

                User::where('id', $agentId->userId)->update([
                    'wallet' => User::find(15)->wallet + ($feeServiceInPla * 0.15),

                ]);

                Wallet::create([
                    'userId' => 15,
                    'amount' => $taxAmount,
                    'cash' => User::find(15)->wallet + ($taxAmount),
                    'fatherId' => $walletId,
                    'numberParcel' => $totalPost->numberParcel,
                    'type' => 'واریز',
                    'status' => 'افزایش اعتبار',
                    'description' => 'سرویس در محل'


                ]);

                User::where('id', $agentId->userId)->update([
                    'wallet' => User::find(15)->wallet + ($taxAmount),

                ]);
            }


        } else {
            Wallet::create([
                'userId' => 15,
                'amount' => $totalPost->amountServices * 0.04,
                'cash' => User::find(15)->wallet + ($totalPost->amountServices * 0.04),
                'fatherId' => $walletId,
                'numberParcel' => $totalPost->numberParcel,
                'type' => 'واریز',
                'status' => 'افزایش اعتبار',
                'description' => 'مجموع خدمات'


            ]);
            User::where('id', 15)->update([
                'wallet' => User::find(15)->wallet + ($totalPost->amountServices * 0.04),

            ]);
            Wallet::create([
                'userId' => $agentId->userId,
                'amount' => ($totalPost->amountServices * 0.06),
                'cash' => User::find($agentId->userId)->wallet + ($totalPost->amountServices * 0.06),
                'fatherId' => $walletId,
                'numberParcel' => $totalPost->numberParcel,
                'type' => 'واریز',
                'status' => 'افزایش اعتبار',
                'description' => 'بابت سفارش' . $totalPost->numberParcel . 'به حساب شما واریز شد.',


            ]);


            User::where('id', $agentId->userId)->update([
                'wallet' => User::find($agentId->userId)->wallet + ($totalPost->amountServices * 0.06),

            ]);


        }


        return 'ok';

    }

}


if (!function_exists('paybyWallet')) {

    function paybyWallet($userId, $totalId, $discountCode = 0)
    {

        $totalPost = TotalPost::find($totalId);


        $serviceType = "پیشکرایه";

        if ($totalPost->isAfterRent) {
            $serviceType = "پسکرایه";
        }


        if ($totalPost->isCod) {
            $serviceType = "COD";
        }

        $isBlock = 0;
        if ($totalPost->isAfterRent or $totalPost->isCod) {
            $isBlock = 1;
        }

        $postItem = InternalOrder::where('internalPostId', $totalId)->get();


        $packaginPrice = 0;

        foreach ($postItem as $value) {
            $packaginPrice = $packaginPrice + $value->packaging;
        }

        $agentId = Agent::find($totalPost->agentId);

        $realPayable = ($totalPost->Payable) + ($packaginPrice * 1.09);


        if ($serviceType == "پیشکرایه") {

            /* if ($totalPost->serviceInPlace != null && $totalPost->serviceInPlace != 0) {
                 $realPayable = $realPayable + (0.09 * $totalPost->serviceInPlace);
             }*/

            $walletId = null;
            if ($isBlock == "0") {
                $walletId = Wallet::create([
                    'userId' => $userId,
                    'amount' => $realPayable,
                    'cash' => User::find($userId)->wallet - $realPayable,
                    'numberParcel' => $totalPost->numberParcel,
                    'componyId' => $totalPost->componyId,
                    'type' => 'برداشت',
                    'status' => 'کسر از کیف پول',
                    'serviceType' => $serviceType,
                    'description' => 'بابت سفارش' . $totalPost->numberParcel . 'از حساب شما کسر شد.',
                ])->id;


                User::where('id', $userId)->update([
                    'wallet' => User::find($userId)->wallet - $totalPost->Payable,

                ]);
            }
//        $walletId =  Wallet::create([
//            'userId' => $userId,
//            'amount' => ($totalPost->Freight *0.1),
//            'cash' => User::find($userId)->wallet + ($totalPost->Freight *0.1),
//            'numberParcel'=>$totalPost->numberParcel,
//            'fatherId'=>$walletId,
//            'type' => 'واریز',
//            'status' => 'افزایش اعتبار',
//            'description' => 'ده درصد از مبلغ سفارش'.$totalPost->numberParcel.' به عنوان هدیه از طرف پستاپ به حساب شما منظور گردید.',
//        ])->id;
//        User::where('id', $userId)->update([
//            'wallet' => User::find($userId)->wallet + ($totalPost->Freight *0.1),
//
//        ]);
            $backPayable = 0;

            $serviceCompanyCash = $totalPost->Freight;
            if ($totalPost->componyId == 2) {
                Wallet::create([
                    'userId' => 15,
                    'amount' => $serviceCompanyCash * (0.15),
                    'cash' => User::find(15)->wallet + $serviceCompanyCash * (0.15),
                    'fatherId' => $walletId,
                    'numberParcel' => $totalPost->numberParcel,
                    'type' => 'واریز',
                    'status' => 'افزایش اعتبار',
                    'isBlock' => $isBlock,
                    'componyId' => $totalPost->componyId,
                    'description' => 'تخفیف ماهکس به آواکس',
                    'serviceType' => $serviceType,

                ]);
                if ($isBlock == "0") {
                    User::where('id', 15)->update([
                        'wallet' => User::find(15)->wallet + $serviceCompanyCash * (0.15),
                    ]);
                }
                $serviceCompanyCash = $serviceCompanyCash * (0.85);
            }

            Wallet::create([
                'userId' => $totalPost->componyId,
                'amount' => $serviceCompanyCash,
                'cash' => User::find($totalPost->componyId)->wallet + $serviceCompanyCash,
                'fatherId' => $walletId,
                'numberParcel' => $totalPost->numberParcel,
                'type' => 'واریز',
                'isBlock' => $isBlock,
                'componyId' => $totalPost->componyId,
                'status' => 'افزایش اعتبار',
                'serviceType' => $serviceType,
            ]);
            if ($isBlock == "0") {
                User::where('id', $totalPost->componyId)->update([
                    'wallet' => User::find($totalPost->componyId)->wallet + $serviceCompanyCash,
                ]);
            }

            Wallet::create([
                'userId' => 15,
                'amount' => $totalPost->ServicesAt,
                'cash' => User::find(15)->wallet + $totalPost->ServicesAt,
                'fatherId' => $walletId,
                'numberParcel' => $totalPost->numberParcel,
                'type' => 'واریز',
                'status' => 'افزایش اعتبار',
                'isBlock' => $isBlock,
                'componyId' => $totalPost->componyId,
                'description' => 'خدمات فنی مهندسی12درصد',
                'serviceType' => $serviceType,
            ]);
            if ($isBlock == "0") {
                User::where('id', 15)->update([
                    'wallet' => User::find(15)->wallet + $totalPost->ServicesAt,

                ]);
            }


            $newTotalPrice = $totalPost->amountServices;

            if ($totalPost->serviceInPlace and $totalPost->serviceInPlace != 0) {

                $newTotalPrice = $newTotalPrice - $totalPost->serviceInPlace;

            }

            /*برای پیامک*/
            if ($totalPost->hasNotifRequest) {
                $newTotalPrice = $newTotalPrice - env('SMS_COST');
                Wallet::create([
                    'userId' => 15,
                    'amount' => env('SMS_COST'),
                    'cash' => User::find(15)->wallet + env('SMS_COST'),
                    'fatherId' => $walletId,
                    'numberParcel' => $totalPost->numberParcel,
                    'type' => 'واریز',
                    'status' => 'افزایش اعتبار',
                    'componyId' => $totalPost->componyId,
                    'isBlock' => $isBlock,
                    'description' => 'هزینه پیامک',
                    'serviceType' => $serviceType,

                ]);
                if ($isBlock == "0") {
                    User::where('id', 15)->update([
                        'wallet' => User::find(15)->wallet + env('SMS_COST'),

                    ]);
                }
            }


            /*برای بیمه*/
            if ($totalPost->Insurance) {
                $newTotalPrice = $newTotalPrice - $totalPost->Insurance;
                Wallet::create([
                    'userId' => 15,
                    'amount' => $totalPost->Insurance,
                    'cash' => User::find(15)->wallet + $totalPost->Insurance,
                    'fatherId' => $walletId,
                    'numberParcel' => $totalPost->numberParcel,
                    'type' => 'واریز',
                    'status' => 'افزایش اعتبار',
                    'componyId' => $totalPost->componyId,
                    'isBlock' => $isBlock,
                    'description' => 'هزینه بیمه',
                    'serviceType' => $serviceType,

                ]);
                if ($isBlock == "0") {
                    User::where('id', 15)->update([
                        'wallet' => User::find(15)->wallet + $totalPost->Insurance,

                    ]);
                }
            }


            $tax = $totalPost->TAX + ($packaginPrice * 0.09);

            Wallet::create([
                'userId' => 15,
                'amount' => $tax,
                'cash' => User::find(15)->wallet + $totalPost->TAX,
                'fatherId' => $walletId,
                'isBlock' => $isBlock,
                'numberParcel' => $totalPost->numberParcel,
                'type' => 'واریز',
                'status' => 'افزایش اعتبار',
                'componyId' => $totalPost->componyId,
                'description' => 'ارزش افزوده 9 درصد',
                'serviceType' => $serviceType,
            ]);

            if ($isBlock == "0") {
                User::where('id', 15)->update([
                    'wallet' => User::find(15)->wallet + $tax,
                ]);
            }

            if ($packaginPrice != 0) {
                Wallet::create([
                    'userId' => 15,
                    'amount' => $packaginPrice * 0.15,
                    'cash' => User::find(15)->wallet + ($packaginPrice * 0.15),
                    'fatherId' => $walletId,
                    'isBlock' => $isBlock,
                    'componyId' => $totalPost->componyId,
                    'numberParcel' => $totalPost->numberParcel,
                    'type' => 'واریز',
                    'status' => 'افزایش اعتبار',
                    'description' => 'هزینه بسته بندی بابت سفارش' . $totalPost->numberParcel . 'به حساب شما واریز شد.',
                    'serviceType' => $serviceType,
                ]);
                if ($isBlock == "0") {
                    User::where('id', 15)->update([
                        'wallet' => User::find(15)->wallet + ($packaginPrice * 0.15),
                    ]);
                }


                /*packeging correction*/
                Wallet::create([
                    'userId' => $agentId->userId,
                    'amount' => $packaginPrice * 0.85,
                    'cash' => User::find($agentId->userId)->wallet + ($packaginPrice * 0.85) - ($packaginPrice*1.09),
                    'fatherId' => $walletId,
                    'isBlock' => $isBlock,
                    'componyId' => $totalPost->componyId,
                    'numberParcel' => $totalPost->numberParcel,
                    'type' => 'واریز',
                    'status' => 'افزایش اعتبار',
                    'description' => 'هزینه بسته بندی بابت سفارش' . $totalPost->numberParcel . 'به حساب شما واریز شد.',
                    'serviceType' => $serviceType,
                ]);
                if ($isBlock == "0") {
                    User::where('id', $agentId->userId)->update([
                        'wallet' => User::find($agentId->userId)->wallet + ($packaginPrice * 0.85)- ($packaginPrice*1.09),
                    ]);
                }

            }


            if ($totalPost->userId == $agentId->userId) {

                /*60% avaex*/
                Wallet::create([
                    'userId' => 15,
                    'isBlock' => $isBlock,
                    'amount' => $newTotalPrice * 0.4,
                    'cash' => User::find(15)->wallet + ($newTotalPrice * 0.4),
                    'fatherId' => $walletId,
                    'numberParcel' => $totalPost->numberParcel,
                    'type' => 'واریز',
                    'componyId' => $totalPost->componyId,
                    'status' => 'افزایش اعتبار',
                    'description' => 'مجموع خدمات',
                    'serviceType' => $serviceType,
                ]);
                if ($isBlock == "0") {
                    User::where('id', 15)->update([
                        'wallet' => User::find(15)->wallet + ($newTotalPrice * 0.4),
                    ]);
                }

                if ($totalPost->serviceInPlace != 0) {

                    $totalKhadamat = $totalPost->serviceInPlace;
                    $taxKhadamat = $totalKhadamat * 0.09;
                    $realKhadamat = $totalKhadamat * 0.91;


                    /*85% khadamat*/
                    Wallet::create([
                        'userId' => $agentId->userId,
                        'amount' => $realKhadamat * 0.85,
                        'cash' => User::find($agentId->userId)->wallet + ($realKhadamat * 0.85),
                        'fatherId' => $walletId,
                        'numberParcel' => $totalPost->numberParcel,
                        'type' => 'واریز',
                        'componyId' => $totalPost->componyId,
                        'isBlock' => $isBlock,
                        'status' => 'افزایش اعتبار',
                        'description' => 'سرویس در محل',
                        'serviceType' => $serviceType,
                    ]);

                    User::where('id', $agentId->userId)->update([
                        'wallet' => User::find($agentId->userId)->wallet + ($realKhadamat * 0.85),
                    ]);

                    /*15% khadamat*/
                    Wallet::create([
                        'userId' => 15,
                        'isBlock' => $isBlock,
                        'amount' => $realKhadamat * 0.15,
                        'cash' => User::find(15)->wallet + ($realKhadamat * 0.15),
                        'fatherId' => $walletId,
                        'numberParcel' => $totalPost->numberParcel,
                        'type' => 'واریز',
                        'componyId' => $totalPost->componyId,
                        'status' => 'افزایش اعتبار',
                        'description' => 'سرویس در محل',
                        'serviceType' => $serviceType,
                    ]);

                    User::where('id', 15)->update([
                        'wallet' => User::find(15)->wallet + ($realKhadamat * 0.15),
                    ]);

                    /*خدمات در محل مالیاتش*/
                    Wallet::create([
                        'userId' => 15,
                        'isBlock' => $isBlock,
                        'amount' => $taxKhadamat,
                        'cash' => User::find(15)->wallet + ($taxKhadamat),
                        'fatherId' => $walletId,
                        'numberParcel' => $totalPost->numberParcel,
                        'type' => 'واریز',
                        'componyId' => $totalPost->componyId,
                        'status' => 'افزایش اعتبار',
                        'description' => 'مالیات خدمات در محل',
                        'serviceType' => $serviceType,
                    ]);

                    User::where('id', 15)->update([
                        'wallet' => User::find(15)->wallet + ($taxKhadamat),
                    ]);

                }

                /* fatima added */
                $newTotalPrice = $newTotalPrice - $totalPost->totalSpecialService;
                Wallet::create([
                    'userId' => $agentId->userId,
                    'amount' => ($newTotalPrice * 0.6),
                    'cash' => User::find($agentId->userId)->wallet + ($newTotalPrice * 0.6),
                    'fatherId' => $walletId,
                    'numberParcel' => $totalPost->numberParcel,
                    'type' => 'واریز',
                    'componyId' => $totalPost->componyId,
                    'isBlock' => $isBlock,
                    'status' => 'افزایش اعتبار',
                    'description' => 'بابت سفارش' . $totalPost->numberParcel . 'به حساب شما واریز شد.',
                    'serviceType' => $serviceType,
                ]);

                if ($isBlock == "0") {
                    User::where('id', $agentId->userId)->update([
                        'wallet' => User::find($agentId->userId)->wallet + ($newTotalPrice * 0.6),

                    ]);
                }
            } else {
                Wallet::create([
                    'userId' => 15,
                    'amount' => $newTotalPrice * 0.6,
                    'cash' => User::find(15)->wallet + ($newTotalPrice * 0.6),
                    'fatherId' => $walletId,
                    'numberParcel' => $totalPost->numberParcel,
                    'type' => 'واریز',
                    'componyId' => $totalPost->componyId,
                    'isBlock' => $isBlock,
                    'status' => 'افزایش اعتبار',
                    'description' => 'مجموع خدمات',
                    'serviceType' => $serviceType,


                ]);
                if ($isBlock == "0") {
                    User::where('id', 15)->update([
                        'wallet' => User::find(15)->wallet + ($newTotalPrice * 0.6),

                    ]);
                }

                Wallet::create([
                    'userId' => $agentId->userId,
                    'amount' => ($newTotalPrice * 0.4),
                    'cash' => User::find($agentId->userId)->wallet + ($newTotalPrice * 0.4),
                    'fatherId' => $walletId,
                    'numberParcel' => $totalPost->numberParcel,
                    'type' => 'واریز',
                    'isBlock' => $isBlock,
                    'componyId' => $totalPost->componyId,
                    'status' => 'افزایش اعتبار',
                    'description' => 'بابت سفارش' . $totalPost->numberParcel . 'به حساب شما واریز شد.',
                    'serviceType' => $serviceType,
                ]);
                if ($isBlock == "0") {
                    User::where('id', $agentId->userId)->update([
                        'wallet' => User::find($agentId->userId)->wallet + ($newTotalPrice * 0.4),
                    ]);
                }



                if ($totalPost->serviceInPlace != 0) {

                    $totalKhadamat = $totalPost->serviceInPlace;
                    $taxKhadamat = $totalKhadamat * 0.09;
                    $realKhadamat = $totalKhadamat * 0.91;


                    /*85% khadamat*/
                    Wallet::create([
                        'userId' => $agentId->userId,
                        'amount' => $realKhadamat * 0.85,
                        'cash' => User::find($agentId->userId)->wallet + ($realKhadamat * 0.85),
                        'fatherId' => $walletId,
                        'numberParcel' => $totalPost->numberParcel,
                        'type' => 'واریز',
                        'componyId' => $totalPost->componyId,
                        'isBlock' => $isBlock,
                        'status' => 'افزایش اعتبار',
                        'description' => 'سرویس در محل',
                        'serviceType' => $serviceType,
                    ]);

                    User::where('id', $agentId->userId)->update([
                        'wallet' => User::find($agentId->userId)->wallet + ($realKhadamat * 0.85),
                    ]);

                    /*15% khadamat*/
                    Wallet::create([
                        'userId' => 15,
                        'isBlock' => $isBlock,
                        'amount' => $realKhadamat * 0.15,
                        'cash' => User::find(15)->wallet + ($realKhadamat * 0.15),
                        'fatherId' => $walletId,
                        'numberParcel' => $totalPost->numberParcel,
                        'type' => 'واریز',
                        'componyId' => $totalPost->componyId,
                        'status' => 'افزایش اعتبار',
                        'description' => 'سرویس در محل',
                        'serviceType' => $serviceType,
                    ]);

                    User::where('id', 15)->update([
                        'wallet' => User::find(15)->wallet + ($realKhadamat * 0.15),
                    ]);

                    /*خدمات در محل مالیاتش*/
                    Wallet::create([
                        'userId' => 15,
                        'isBlock' => $isBlock,
                        'amount' => $taxKhadamat,
                        'cash' => User::find(15)->wallet + ($taxKhadamat),
                        'fatherId' => $walletId,
                        'numberParcel' => $totalPost->numberParcel,
                        'type' => 'واریز',
                        'componyId' => $totalPost->componyId,
                        'status' => 'افزایش اعتبار',
                        'description' => 'مالیات خدمات در محل',
                        'serviceType' => $serviceType,
                    ]);

                    User::where('id', 15)->update([
                        'wallet' => User::find(15)->wallet + ($taxKhadamat),
                    ]);

                }
            }


            if ($discountCode and $discountCode != 0) {
                $discount = Discount::query()->find($discountCode);
                if ($discount and Carbon::now()->between($discount->start_date, $discount->end_date)) {
                    if ($totalPost->userId == $agentId->userId) {
                        if ($discount->allAgent) {
                            $dis = 1;
                        } else {
                            $dis = 0;
                        }
                    } else {
                        if ($discount->groupy) {
                            $dis = 1;
                        } else {
                            $dis = 0;
                        }
                    }

                    if ($dis) {
                        if ($discount->type == "درصد") {
                            $backPayable = $realPayable * ($discount->amount / 100);
                        } else {
                            $backPayable = $discount->amount;
                        }
                        Wallet::create([
                            'userId' => 15,
                            'amount' => $backPayable,
                            'cash' => User::find(15)->wallet - $backPayable,
                            'fatherId' => $walletId,
                            'numberParcel' => $totalPost->numberParcel,
                            'type' => 'برداشت',
                            'componyId' => $totalPost->componyId,
                            'isBlock' => $isBlock,
                            'discountCodeId' => $discount->id,
                            'status' => 'کسر از حساب',
                            'description' => 'بابت کد تخفیف سفارش' . $totalPost->numberParcel . 'از حساب شما کسر شد.',
                            'serviceType' => $serviceType,


                        ]);

                        User::where('id', 15)->update([
                            'wallet' => User::find(15)->wallet - $backPayable,

                        ]);


                        Wallet::create([
                            'userId' => $userId,
                            'amount' => $backPayable,
                            'cash' => User::find($userId)->wallet + $backPayable,
                            'numberParcel' => $totalPost->numberParcel,
                            'type' => 'واریز',
                            'componyId' => $totalPost->componyId,
                            'status' => 'افزایش اعتبار',
                            'serviceType' => $serviceType,
                            'discountCodeId' => $discount->id,
                            'description' => 'بابت کد تخفیف سفارش' . $totalPost->numberParcel . 'به حساب شما واریز شد.',
                        ])->id;
                        User::where('id', $userId)->update([
                            'wallet' => User::find($userId)->wallet + $backPayable,

                        ]);
                    }


                }
            }
        } elseif ($serviceType == "پسکرایه") {


            Wallet::create([
                'userId' => $totalPost->componyId,
                'amount' => $realPayable,
                'cash' => User::find($totalPost->componyId)->wallet - $realPayable,
                'numberParcel' => $totalPost->numberParcel,
                'type' => 'برداشت',
                'status' => 'کسر از کیف پول',
                'description' => 'کسر از کیف پول بابت سفارش پس کرایه ' . $totalPost->numberParcel,
                'componyId' => $totalPost->componyId,
                'serviceType' => $serviceType,
            ]);
            User::where('id', $totalPost->componyId)->update([
                'wallet' => User::find($totalPost->componyId)->wallet - $realPayable,
            ]);

        } elseif ($serviceType == "COD") {

            Wallet::create([
                'userId' => $totalPost->componyId,
                'amount' => $realPayable + $totalPost->amountCOD,
                'cash' => User::find($totalPost->componyId)->wallet - ($realPayable + $totalPost->amountCOD),
                'numberParcel' => $totalPost->numberParcel,
                'type' => 'برداشت',
                'status' => 'کسر از کیف پول',
                'description' => 'کسر از کیف پول بابت سفارش پس کرایه ' . $totalPost->numberParcel,
                'componyId' => $totalPost->componyId,
                'serviceType' => $serviceType,
            ]);
            User::where('id', $totalPost->componyId)->update([
                'wallet' => User::find($totalPost->componyId)->wallet - ($realPayable + $totalPost->amountCOD),
            ]);
        }
        return 'ok';

    }
}


if (!function_exists('changeStatus')) {
    function changeStatus($totalId)
    {

        $totalPost = TotalPost::find($totalId);
        $serviceType = "پیشکرایه";
        if ($totalPost->isAfterRent) {
            $serviceType = "پسکرایه";
        }
        if ($totalPost->isCod) {
            $serviceType = "COD";
        }
        $isBlock = 0;
        if ($totalPost->isAfterRent or $totalPost->isCod) {
            $isBlock = 1;
        }
        $postItem = InternalOrder::where('internalPostId', $totalId)->get();

        $packaginPrice = 0;

        foreach ($postItem as $value) {
            $packaginPrice = $packaginPrice + $value->packaging;
        }

        $agentId = Agent::find($totalPost->agentId);

        $realPayable = ($totalPost->Payable) + ($packaginPrice * 1.09);
        $walletId = null;
        $fatherWallet = Wallet::query()->where('userId', $totalPost->componyId)->where('numberParcel', $totalPost->numberParcel)->where('type', 'برداشت')->where('serviceType', $serviceType)->first();
        if ($fatherWallet) {
            $walletId = $fatherWallet->id;
        }

        $serviceCompanyCash = $totalPost->Freight;
        if ($totalPost->componyId == 2) {
            Wallet::create([
                'userId' => 15,
                'amount' => $serviceCompanyCash * (0.15),
                'cash' => User::find(15)->wallet + $serviceCompanyCash * (0.15),
                'fatherId' => $walletId,
                'numberParcel' => $totalPost->numberParcel,
                'type' => 'واریز',
                'status' => 'افزایش اعتبار',
                'isBlock' => $isBlock,
                'componyId' => $totalPost->componyId,
                'description' => 'تخفیف ماهکس به آواکس',
                'serviceType' => $serviceType,

            ]);

            User::where('id', 15)->update([
                'wallet' => User::find(15)->wallet + $serviceCompanyCash * (0.15),
            ]);
            $serviceCompanyCash = $serviceCompanyCash * (0.85);
        }

        Wallet::create([
            'userId' => $totalPost->componyId,
            'amount' => $serviceCompanyCash,
            'cash' => User::find($totalPost->componyId)->wallet + $serviceCompanyCash,
            'fatherId' => $walletId,
            'numberParcel' => $totalPost->numberParcel,
            'type' => 'واریز',
            'isBlock' => $isBlock,
            'componyId' => $totalPost->componyId,
            'status' => 'افزایش اعتبار',
            'serviceType' => $serviceType,
        ]);
        User::where('id', $totalPost->componyId)->update([
            'wallet' => User::find($totalPost->componyId)->wallet + $serviceCompanyCash,
        ]);

        Wallet::create([
            'userId' => 15,
            'amount' => $totalPost->ServicesAt,
            'cash' => User::find(15)->wallet + $totalPost->ServicesAt,
            'fatherId' => $walletId,
            'numberParcel' => $totalPost->numberParcel,
            'type' => 'واریز',
            'status' => 'افزایش اعتبار',
            'isBlock' => $isBlock,
            'componyId' => $totalPost->componyId,
            'description' => 'خدمات فنی مهندسی12درصد',
            'serviceType' => $serviceType,
        ]);
        User::where('id', 15)->update([
            'wallet' => User::find(15)->wallet + $totalPost->ServicesAt,

        ]);


        $newTotalPrice = $totalPost->amountServices;

        if ($totalPost->serviceInPlace and $totalPost->serviceInPlace != 0) {

            $newTotalPrice = $newTotalPrice - $totalPost->serviceInPlace;

        }

        /*برای پیامک*/
        if ($totalPost->hasNotifRequest) {
            $newTotalPrice = $newTotalPrice - env('SMS_COST');
            Wallet::create([
                'userId' => 15,
                'amount' => env('SMS_COST'),
                'cash' => User::find(15)->wallet + env('SMS_COST'),
                'fatherId' => $walletId,
                'numberParcel' => $totalPost->numberParcel,
                'type' => 'واریز',
                'status' => 'افزایش اعتبار',
                'componyId' => $totalPost->componyId,
                'isBlock' => $isBlock,
                'description' => 'هزینه پیامک',
                'serviceType' => $serviceType,

            ]);
            User::where('id', 15)->update([
                'wallet' => User::find(15)->wallet + env('SMS_COST'),

            ]);
        }


        /*برای بیمه*/
        if ($totalPost->Insurance) {
            $newTotalPrice = $newTotalPrice - $totalPost->Insurance;
            Wallet::create([
                'userId' => 15,
                'amount' => $totalPost->Insurance,
                'cash' => User::find(15)->wallet + $totalPost->Insurance,
                'fatherId' => $walletId,
                'numberParcel' => $totalPost->numberParcel,
                'type' => 'واریز',
                'status' => 'افزایش اعتبار',
                'componyId' => $totalPost->componyId,
                'isBlock' => $isBlock,
                'description' => 'هزینه بیمه',
                'serviceType' => $serviceType,

            ]);
            User::where('id', 15)->update([
                'wallet' => User::find(15)->wallet + $totalPost->Insurance,

            ]);
        }


        $tax = $totalPost->TAX + ($packaginPrice * 0.09);

        Wallet::create([
            'userId' => 15,
            'amount' => $tax,
            'cash' => User::find(15)->wallet + $totalPost->TAX,
            'fatherId' => $walletId,
            'isBlock' => $isBlock,
            'numberParcel' => $totalPost->numberParcel,
            'type' => 'واریز',
            'status' => 'افزایش اعتبار',
            'componyId' => $totalPost->componyId,
            'description' => 'ارزش افزوده 9 درصد',
            'serviceType' => $serviceType,
        ]);

        User::where('id', 15)->update([
            'wallet' => User::find(15)->wallet + $tax,
        ]);

        if ($packaginPrice != 0) {
            Wallet::create([
                'userId' => 15,
                'amount' => $packaginPrice * 0.15,
                'cash' => User::find(15)->wallet + ($packaginPrice * 0.15),
                'fatherId' => $walletId,
                'isBlock' => $isBlock,
                'componyId' => $totalPost->componyId,
                'numberParcel' => $totalPost->numberParcel,
                'type' => 'واریز',
                'status' => 'افزایش اعتبار',
                'description' => 'هزینه بسته بندی بابت سفارش' . $totalPost->numberParcel . 'به حساب شما واریز شد.',
                'serviceType' => $serviceType,
            ]);
            User::where('id', 15)->update([
                'wallet' => User::find(15)->wallet + ($packaginPrice * 0.15),
            ]);


            Wallet::create([
                'userId' => $agentId->userId,
                'amount' => $packaginPrice * 0.85,
                'cash' => User::find($agentId->userId)->wallet + ($packaginPrice * 0.85),
                'fatherId' => $walletId,
                'isBlock' => $isBlock,
                'componyId' => $totalPost->componyId,
                'numberParcel' => $totalPost->numberParcel,
                'type' => 'واریز',
                'status' => 'افزایش اعتبار',
                'description' => 'هزینه بسته بندی بابت سفارش' . $totalPost->numberParcel . 'به حساب شما واریز شد.',
                'serviceType' => $serviceType,
            ]);
            User::where('id', $agentId->userId)->update([
                'wallet' => User::find($agentId->userId)->wallet + ($packaginPrice * 0.85),
            ]);

        }


        if ($totalPost->userId == $agentId->userId) {

            /*60% avaex*/
            Wallet::create([
                'userId' => 15,
                'isBlock' => $isBlock,
                'amount' => $newTotalPrice * 0.4,
                'cash' => User::find(15)->wallet + ($newTotalPrice * 0.4),
                'fatherId' => $walletId,
                'numberParcel' => $totalPost->numberParcel,
                'type' => 'واریز',
                'componyId' => $totalPost->componyId,
                'status' => 'افزایش اعتبار',
                'description' => 'مجموع خدمات',
                'serviceType' => $serviceType,
            ]);
            User::where('id', 15)->update([
                'wallet' => User::find(15)->wallet + ($newTotalPrice * 0.4),
            ]);

            if ($totalPost->serviceInPlace != 0) {

                $totalKhadamat = $totalPost->serviceInPlace;
                $taxKhadamat = $totalKhadamat * 0.09;
                $realKhadamat = $totalKhadamat * 0.91;


                /*85% khadamat*/
                Wallet::create([
                    'userId' => $agentId->userId,
                    'amount' => $realKhadamat * 0.85,
                    'cash' => User::find($agentId->userId)->wallet + ($realKhadamat * 0.85),
                    'fatherId' => $walletId,
                    'numberParcel' => $totalPost->numberParcel,
                    'type' => 'واریز',
                    'componyId' => $totalPost->componyId,
                    'isBlock' => $isBlock,
                    'status' => 'افزایش اعتبار',
                    'description' => 'سرویس در محل',
                    'serviceType' => $serviceType,
                ]);

                User::where('id', $agentId->userId)->update([
                    'wallet' => User::find($agentId->userId)->wallet + ($realKhadamat * 0.85),
                ]);

                /*15% khadamat*/
                Wallet::create([
                    'userId' => 15,
                    'isBlock' => $isBlock,
                    'amount' => $realKhadamat * 0.15,
                    'cash' => User::find(15)->wallet + ($realKhadamat * 0.15),
                    'fatherId' => $walletId,
                    'numberParcel' => $totalPost->numberParcel,
                    'type' => 'واریز',
                    'componyId' => $totalPost->componyId,
                    'status' => 'افزایش اعتبار',
                    'description' => 'سرویس در محل',
                    'serviceType' => $serviceType,
                ]);

                User::where('id', 15)->update([
                    'wallet' => User::find(15)->wallet + ($realKhadamat * 0.15),
                ]);

                /*خدمات در محل مالیاتش*/
                Wallet::create([
                    'userId' => 15,
                    'isBlock' => $isBlock,
                    'amount' => $taxKhadamat,
                    'cash' => User::find(15)->wallet + ($taxKhadamat),
                    'fatherId' => $walletId,
                    'numberParcel' => $totalPost->numberParcel,
                    'type' => 'واریز',
                    'componyId' => $totalPost->componyId,
                    'status' => 'افزایش اعتبار',
                    'description' => 'مالیات خدمات در محل',
                    'serviceType' => $serviceType,
                ]);

                User::where('id', 15)->update([
                    'wallet' => User::find(15)->wallet + ($taxKhadamat),
                ]);

            }

            /* fatima added */
            $newTotalPrice = $newTotalPrice - $totalPost->totalSpecialService;
            Wallet::create([
                'userId' => $agentId->userId,
                'amount' => ($newTotalPrice * 0.6),
                'cash' => User::find($agentId->userId)->wallet + ($newTotalPrice * 0.6),
                'fatherId' => $walletId,
                'numberParcel' => $totalPost->numberParcel,
                'type' => 'واریز',
                'componyId' => $totalPost->componyId,
                'isBlock' => $isBlock,
                'status' => 'افزایش اعتبار',
                'description' => 'بابت سفارش' . $totalPost->numberParcel . 'به حساب شما واریز شد.',
                'serviceType' => $serviceType,
            ]);

            User::where('id', $agentId->userId)->update([
                'wallet' => User::find($agentId->userId)->wallet + ($newTotalPrice * 0.6),

            ]);
        } else {
            Wallet::create([
                'userId' => 15,
                'amount' => $newTotalPrice * 0.6,
                'cash' => User::find(15)->wallet + ($newTotalPrice * 0.6),
                'fatherId' => $walletId,
                'numberParcel' => $totalPost->numberParcel,
                'type' => 'واریز',
                'componyId' => $totalPost->componyId,
                'isBlock' => $isBlock,
                'status' => 'افزایش اعتبار',
                'description' => 'مجموع خدمات',
                'serviceType' => $serviceType,


            ]);
            User::where('id', 15)->update([
                'wallet' => User::find(15)->wallet + ($newTotalPrice * 0.6),

            ]);

            Wallet::create([
                'userId' => $agentId->userId,
                'amount' => ($newTotalPrice * 0.4),
                'cash' => User::find($agentId->userId)->wallet + ($newTotalPrice * 0.4),
                'fatherId' => $walletId,
                'numberParcel' => $totalPost->numberParcel,
                'type' => 'واریز',
                'isBlock' => $isBlock,
                'componyId' => $totalPost->componyId,
                'status' => 'افزایش اعتبار',
                'description' => 'بابت سفارش' . $totalPost->numberParcel . 'به حساب شما واریز شد.',
                'serviceType' => $serviceType,
            ]);
            User::where('id', $agentId->userId)->update([
                'wallet' => User::find($agentId->userId)->wallet + ($newTotalPrice * 0.4),
            ]);
        }

        if ($serviceType == "COD") {

            $amountCod = $totalPost->amountCOD;

            if ($amountCod and $amountCod != 0) {
                Wallet::create([
                    'userId' => 15,
                    'amount' => $amountCod * 0.985,
                    'cash' => User::find(15)->wallet + ($amountCod * 0.985),
                    'fatherId' => $walletId,
                    'numberParcel' => $totalPost->numberParcel,
                    'type' => 'واریز',
                    'componyId' => $totalPost->componyId,
                    'isBlock' => $isBlock,
                    'status' => 'افزایش اعتبار',
                    'description' => 'بابت مبلغ cod سفارش' . $totalPost->numberParcel . 'به حساب شما واریز شد.',
                    'serviceType' => $serviceType,
                ]);
                User::where('id', 15)->update([
                    'wallet' => User::find(15)->wallet + ( $amountCod * 0.985),
                ]);


                Wallet::create([
                    'userId' => $totalPost->componyId,
                    'amount' =>  $amountCod * 0.015,
                    'cash' => User::find($totalPost->componyId)->wallet + ($amountCod * 0.015),
                    'fatherId' => $walletId,
                    'numberParcel' => $totalPost->numberParcel,
                    'type' => 'واریز',
                    'isBlock' => $isBlock,
                    'componyId' => $totalPost->componyId,
                    'status' => 'افزایش اعتبار',
                    'description' => 'بابت مبلغ 1.5درصد cod سفارش' . $totalPost->numberParcel . 'به حساب شما واریز شد.',
                    'serviceType' => $serviceType,
                ]);
                User::where('id', $totalPost->componyId)->update([
                    'wallet' => User::find($totalPost->componyId)->wallet + ($amountCod * 0.015),
                ]);

            }
        }
        return 'ok';
    }
}


if (!function_exists('chaparRequest')) {
    function chaparRequest($totalId)
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

//        $PostId = InternalPost::where('totalPostId', $totalId)->first();
//        $dasd = InternalOrder::where('internalPostId', $PostId->id)->get();
        $typeService = ComponyTypePost::find($totalPost->typeSerId);

        $senderAddress = Address::find($totalPost->addressId);
        $senderCity = City::find($senderAddress->cityId);
        $getterAddress = Address::find($totalPost->getterAddressId);
        $getterCity = City::find($getterAddress->cityId);

        $MethodPayment = 0;
        if($totalPost->MethodPayment == "پیش کرایه" or $totalPost->MethodPayment == "پیشکرایه"){
            $MethodPayment = 0;
        }else{
            $MethodPayment = 1;
        }

        foreach ($parts as $item) {

            $part = array(
                'cn' => array(
                    'reference' => $item->partnumber,
                    'date' => Carbon::now()->format('Y-m-d'),
                    'assinged_pieces' => 1,
                    'service' => $typeService->chaparId,
                    'value' => $item->cost,
                    'payment_term' => $MethodPayment,
                    'weight' => $item->weight,
                    'content' => $item->content,
                    'change_state_url' => "http://back.avaex.ir/changeState/$totalId",
                ),
                'sender' => array(
                    'person' => $senderAddress->name . $senderAddress->family,
                    'company' => $senderAddress->compony,
                    'city_no' => $senderCity->chaparNumber,
                    'telephone' => $senderAddress->phone,
                    'mobile' => $senderAddress->phone,
                    'email' => $senderAddress->email,
                    'address' => $senderAddress->address,
                    'postcode' => $senderAddress->postCode,
                ),
                'receiver' => array(
                    'person' => $getterAddress->name . $getterAddress->family,
                    'company' => $getterAddress->compony,
                    'city_no' => $getterCity->chaparNumber,
                    'telephone' => $getterAddress->phone,
                    'mobile' => $getterAddress->phone,
                    'email' => $getterAddress->email,
                    'address' => $getterAddress->address,
                    'postcode' => $getterAddress->postCode,
                ),
            );
            array_push($allItems, $part);

        }


        $postdata = http_build_query(
            array(
                'input' => json_encode(array(
                    'user' => array(
                        'username' => 'pishro.tarabar',
                        'password' => 'ptm316Mj',
                    ),
                    'bulk' => $allItems,
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

        $result = file_get_contents('https://app.krch.ir/v1/bulk_import', false, $context);
        $res = json_decode($result);
        return $res;
    }

}
if (!function_exists('mahexRequest')) {
    function mahexRequest($totalId)
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


        /*get partNumbers*/
        $cURLConnection2 = curl_init();
        $mahexToken = env('MAHEX_TOKEN');

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
                'phone' => $senderAddress->phone,
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

        if ($response->status->state == "Success") {
            TotalPost::where('id', $totalId)->update([
                "serviceUuid" => $response->data->shipment_uuid
            ]);
        }

        return $response;
    }
}

if (!function_exists('postRequest')) {
    function postRequest($totalId)
    {

        $allItems = [];
        $totalPost = TotalPost::find($totalId);

        $orderitem = InternalOrder::query()->where('internalPostId',$totalId)->first();

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


        $senderAddress = Address::find($totalPost->addressId);

        $senderCity = City::find($senderAddress->cityId);
        $getterAddress = Address::find($totalPost->getterAddressId);
        $getterCity = City::find($getterAddress->cityId);


        $MethodPayment = 1;
        if($totalPost->MethodPayment == "پیش کرایه" or $totalPost->MethodPayment == "پیشکرایه"){
            $MethodPayment = 1;
        }elseif($totalPost->MethodPayment=="پس کرایه"){
            $MethodPayment = 89;
        }else{
            $MethodPayment =0;
        }

        /*get partNumbers*/
        $token = getPostToken();
        $weight = $totalPost->totalCollectiveWeight*1000;
        $cost = $totalPost->totalCost;

        $serviceType = 1;
        if ($totalPost->componyServicesId == 8){
            $serviceType =2;
        }

        if ($totalPost->componyServicesId == 9){
            $serviceType =3;
        }

        $Price = (object)array(
            'ShopID' => $senderCity->postShop,
            'ToCityID' => $getterCity->postNumber,
            'ServiceTypeID' => $serviceType,
            'PayTypeID' => $MethodPayment,
            'Weight' => $weight,
            'ParcelValue' => $cost,
            'CollectNeed' => false,
            'NonStandardPackage' => false,
            'SMSService' => false,
        );

        $name = $getterAddress->name;
        $family = $getterAddress->family;
        if (!$name or !$family){
            $name = $getterAddress->compony;
            $family = $getterAddress->compony;
        }

        $data = (object)array(
            'ClientOrderID' => $totalPost->numberParcel,
            'CustomerNID' => '0702423408',
            'CustomerName' => $name,
            'CustomerFamily' =>  $family,
            'CustomerPostalCode' => "4951919941",
            'CustomerMobile' => $getterAddress->phone,
            'CustomerAddress' => $getterAddress->address,
            'ParcelContent' => $orderitem->shipment,
            'IsReadyToAccept' => true,
            'Price' => $Price,
        );


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
        if ($ress->ResMsg == "موفق") {
            TotalPost::where('id', $totalId)->update([
                "serviceNumberParcel" =>$ress->Data->ParcelCode
            ]);
            InternalOrder::query()->where('internalPostId',$totalId)->update([
                "serviceNumberParcel" =>$ress->Data->ParcelCode,
                "servicePartnumber" =>$ress->Data->ParcelCode,
            ]);
        }
        return $ress;
    }
}


if (!function_exists('sendRemember')) {
    function sendRemember($city_id)
    {
        $url = "https://portal.amootsms.com/webservice2.asmx/SendWithPattern_REST";

        $url = $url . "?" . "UserName=09157076552";
        $url = $url . "&" . "Password=7275327Mj";

        $city = City::find($city_id)->faName;

        $agent = Agent::where('cityId', $city_id)->first();
        $user = User::find($agent->userId);

        $count = 0;

        $addresses = Address::where('cityId', $city_id)->pluck('id')->toArray();
        $marsolle = TotalPost::whereIn('addressId', $addresses)->where('factorstatus', 'close')->where('status', 'جمع آوری نشده')->count();
        if ($marsolle > 0) {
            $count = $marsolle;
        }


        $userName = $user->name . " " . $user->family;
        $userName = urlencode($userName);
        $random = 1234;
        $mobile = preg_replace('/^0/', '', $user->phone);

        $url = $url . "&" . "Mobile=" . $mobile;
        $url = $url . "&" . "PatternCodeID=1665";
        $url = $url . "&" . "PatternValues=$city,$count";

        $json = file_get_contents($url);
        $objectResult = json_decode($json);


    }
}


if (!function_exists('sendSubmitSms')) {
    function sendSubmitSms($totalPostId)
    {

        $totalPostData = TotalPost::find($totalPostId);
        if ($totalPostData and $totalPostData->byAgent != 1) {
            $senderAddress = Address::find($totalPostData->addressId);
            if ($senderAddress->cityId) {

                $city_id = $senderAddress->cityId;


                $url = "https://portal.amootsms.com/webservice2.asmx/SendWithPattern_REST";

                $url = $url . "?" . "UserName=09157076552";
                $url = $url . "&" . "Password=7275327Mj";

                $city = City::find($city_id)->faName;

                //$agent = Agent::where('cityId', $totalPostData)->first();
                $agent = Agent::find($totalPostData->agentId);
                $user = User::find($agent->userId);

                $count = 0;

                $addresses = Address::where('cityId', $city_id)->pluck('id')->toArray();
                $marsolle = TotalPost::whereIn('addressId', $addresses)->where('factorstatus', 'close')->where('status', 'جمع آوری نشده')->count();
                if ($marsolle > 0) {
                    $count = $marsolle;
                }


                $userName = $user->name . " " . $user->family;
                $userName = urlencode($userName);
                $random = 1234;
                $mobile = preg_replace('/^0/', '', $user->phone);

                $url = $url . "&" . "Mobile=" . $mobile;
                $url = $url . "&" . "PatternCodeID=1665";
                $url = $url . "&" . "PatternValues=$city,$count";

                $json = file_get_contents($url);
                $objectResult = json_decode($json);
            }
        }
    }
}

if (!function_exists('sendDeriverSms')) {
    function sendDeriverSms($totalPostId, $driverId)
    {

        $totalPostData = TotalPost::find($totalPostId);
        $driver = Driver::find($driverId);

        if ($driver and $totalPostData) {
            $address = Address::find($totalPostData->addressId)->totalAddress;

            $url = "https://portal.amootsms.com/webservice2.asmx/SendWithPattern_REST";

            $url = $url . "?" . "UserName=09157076552";
            $url = $url . "&" . "Password=7275327Mj";

            $userName = urlencode($address);
            $mobile = preg_replace('/^0/', '', $driver->mobile);

            $url = $url . "&" . "Mobile=" . $mobile;
            $url = $url . "&" . "PatternCodeID=1674";
            $url = $url . "&" . "PatternValues=$userName";

            $json = file_get_contents($url);
            $objectResult = json_decode($json);

        }
    }
}



if (!function_exists('getPostToken')) {
    function getPostToken()
    {
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
        curl_setopt( $cURLConnection, CURLOPT_CUSTOMREQUEST, 'POST' );
        curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURLConnection, CURLOPT_POSTFIELDS,
            $data);
        $response = curl_exec($cURLConnection);
        curl_close($cURLConnection);
        return json_decode($response)->access_token;

    }
}


