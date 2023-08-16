<?php

namespace Modules\Wallet\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Agent\Entities\Agent;
use Modules\User\Entities\User;
use Modules\Wallet\Entities\Wallet;
use App\Exports\WalletExport;
use App\Exports\AvaxWalletExport;
use App\Exports\MyWalletExport;

class WalletController extends Controller
{
    public function request(Request $request)
    {
        $walletId = Wallet::create([
            'userId' => $request->userId,
            'amount' => $request->amount,
            'cash' => User::find($request->userId)->wallet,
            'type' => 'واریز',
            'status' => 'معلق'
        ])->id;
        $response = zarinpal()
            ->amount($request->amount) // مبلغ تراکنش
            ->request()
            ->description('واریز به کیف پول') // توضیحات تراکنش
            ->callbackUrl('https://back.postupex.ir/api/wallet/v1/zarinpal/verification/' . $request->userId . '/' . $request->amount . '/' . $walletId) // آدرس برگشت پس از پرداخت
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

    public function verification($userId, $amount, $id)
    {

        $authority = request()->query('Authority'); // دریافت کوئری استرینگ ارسال شده توسط زرین پال
        $status = request()->query('Status'); // دریافت کوئری استرینگ ارسال شده توسط زرین پال

        $response = zarinpal()
            ->amount($amount)
            ->verification()
            ->authority($authority)
            ->send();


        if (!$response->success()) {
            var_dump($response->error());
//            return $response->error()->message();
            return redirect('https://avaex.ir/failed');
        } else {

            Wallet::where('id', $id)->update([
                'trackingCode' => $response->referenceId(),
                'cash' => User::find($userId)->wallet + $amount,
                'status' => 'واریز به کیف پول'

            ]);

            $wallet = User::find($userId)->wallet;
            User::where('id', $userId)->update([
                'wallet' => $wallet + $amount
            ]);
            return redirect('https://avaex.ir/success');


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


    public function all(Request $request)
    {


        $all = [];
        $wallet = Wallet::where('userId', $request->user()->id)
            ->skip(($request->numberpage - 1) * $request->numberitems)
            ->take($request->numberitems)->orderBy('id', 'DESC')->get();


        $numbers = count(
                Wallet::where('userId', $request->user()->id)->get()) / $request->numberitems;


        foreach ($wallet as $item) {
            $res = (object)[
                'item' => $item,
                'date' => dateTimeToDate($item->created_at),
                'time' => dateTimeToTime($item->created_at),

            ];
            array_push($all, $res);

        }

        $res = (object)[
            'all' => $all,
            'amont' => User::find($request->user()->id)->wallet,
            'number' => ceil($numbers),
            'UI' => $request->user()->id,


        ];

        $response = (object)[
            'data' => $res,
        ];
        return Response::json($response, 200);


    }

    public function allExel(Request $request, $id)
    {

        $all = [];
        $wallet = Wallet::where('userId', $id)->orderBy('id', 'DESC')->get();


        foreach ($wallet as $item) {
            $res = array(
                $item->numberParcel,
                $item->type,
                $item->amount,
                $item->cash,
                $item->blockAmount,
                $item->status,
                $item->description,
                dateTimeToDate($item->created_at),
                dateTimeToTime($item->created_at),

            );
            array_push($all, $res);

        }


        $export = new MyWalletExport($all);

        return Excel::download($export, 'myWallet.xlsx');


    }

    public function adminAll(Request $request)
    {

        $all = [];
        $wallet = Wallet::where('userId', $request->userId)
            ->skip(($request->numberpage - 1) * $request->numberitems)
            ->take($request->numberitems)->orderBy('id', 'DESC')->get();

        $numbers = count(
                Wallet::where('userId', $request->userId)->get()) / $request->numberitems;

        foreach ($wallet as $item) {
            $res = (object)[
                'item' => $item,
                'date' => dateTimeToDate($item->created_at),
                'time' => dateTimeToTime($item->created_at),

            ];
            array_push($all, $res);

        }

        $res = (object)[
            'all' => $all,
            'amont' => User::find($request->userId)->wallet,
            'number' => ceil($numbers),


        ];

        $response = (object)[
            'data' => $res,
        ];
        return Response::json($response, 200);


    }

    public function collector(Request $request)
    {

        $all = [];

        /* $query = Wallet::where('userId', $request->user()->id)->where(function ($query) {
             $query->where('isAgent', 0)
                 ->orWhereNull('isAgent');
         })->whereNotNull('numberParcel')->where("type", "واریز");*/
        $query = Wallet::where('userId', $request->user()->id)->where(function ($query) {
            $query->where('isAgent', 0)
                ->orWhereNull('isAgent');
        });

        /* if ($request->status && $request->status != null && $request->status != "" && $request->status != 0 && $request->status != "اننخاب نشده") {
             $query = $query->where('status', $request->status);
         }*/

        if ($request->company && $request->company != null && $request->company != "" && $request->company != 0) {
            $query = $query->where('componyId', $request->company);
        }

        if ($request->fromDate && $request->fromDate != null && $request->fromDate != "") {
            $miladiDate = convertToMiladi($this->convert($request->fromDate));
            $query = $query->where('created_at', '>=', $miladiDate);
        }

        if ($request->toDate && $request->toDate != null && $request->toDate != "") {
            $miladiDate = convertToMiladi($this->convert($request->toDate));
            $query = $query->where('created_at', '<=', $miladiDate);
        }

        /*$wallet =Wallet::where('userId',$request->user()->id)->where(function ($query) {
            $query->where('isAgent', 0)
                ->orWhereNull('isAgent');
        })->whereNotNull('numberParcel')->where("type","واریز")->skip(($request->numberpage - 1) * $request->numberitems)
            ->take($request->numberitems)->orderBy('id', 'DESC')->get();*/


        $numbers = $query->count() / $request->numberitems;

        $wallet = $query->skip(($request->numberpage - 1) * $request->numberitems)->take($request->numberitems)->orderBy('id', 'DESC')->get();


        foreach ($wallet as $item) {
            $res = (object)[
                'item' => $item,
                'date' => dateTimeToDate($item->created_at),
                'time' => dateTimeToTime($item->created_at),

            ];
            array_push($all, $res);

        }

        $res = (object)[
            'all' => $all,
            'amont' => User::find($request->user()->id)->wallet,
            'number' => ceil($numbers),
            'UI' => $request->user()->id,


        ];

        $response = (object)[
            'data' => $res,
        ];
        return Response::json($response, 200);
    }

    public function collectorExel(Request $request, $id)
    {

        $all = [];

        /*   $query = Wallet::where('userId', $id)->where(function ($query) {
               $query->where('isAgent', 0)
                   ->orWhereNull('isAgent');
           })->whereNotNull('numberParcel')->where("type", "واریز");*/
        $query = Wallet::where('userId', $id)->where(function ($query) {
            $query->where('isAgent', 0)
                ->orWhereNull('isAgent');
        });

        /* if ($request->status && $request->status != null && $request->status != "" && $request->status != 0 && $request->status != "اننخاب نشده") {
             $query = $query->where('status', $request->status);
         }*/

        if ($request->company && $request->company != null && $request->company != "" && $request->company != 0) {
            $query = $query->where('componyId', $request->company);
        }

        if ($request->fromDate && $request->fromDate != null && $request->fromDate != "") {
            $miladiDate = convertToMiladi($this->convert($request->fromDate));
            $query = $query->where('created_at', '>=', $miladiDate);
        }

        if ($request->toDate && $request->toDate != null && $request->toDate != "") {
            $miladiDate = convertToMiladi($this->convert($request->toDate));
            $query = $query->where('created_at', '<=', $miladiDate);
        }


        $wallet = $query->orderBy('id', 'DESC')->get();


        foreach ($wallet as $item) {
            $res = array(
                $item->numberParcel,
                $item->type,
                $item->amount,
                $item->cash,
                $item->blockAmount,
                $item->status,
                $item->description,
                dateTimeToDate($item->created_at),
                dateTimeToTime($item->created_at),

            );
            array_push($all, $res);

        }


        $export = new MyWalletExport($all);

        return Excel::download($export, 'myWallet.xlsx');
    }


    public function adminCollector(Request $request)
    {

        $all = [];

        $agent = Agent::find($request->agentId);
        $userId = $agent->userId;

        /*        $query = Wallet::where('userId', $userId)->where(function ($query) {
                    $query->where('isAgent', 0)
                        ->orWhereNull('isAgent');
                })->whereNotNull('numberParcel')->where("type", "واریز");*/
        $query = Wallet::where('userId', $userId)->where(function ($query) {
            $query->where('isAgent', 0)
                ->orWhereNull('isAgent');
        });

        /* if ($request->status && $request->status != null && $request->status != "" && $request->status != 0 && $request->status != "اننخاب نشده") {
             $query = $query->where('status', $request->status);
         }*/

        if ($request->company && $request->company != null && $request->company != "" && $request->company != 0) {
            $query = $query->where('componyId', $request->company);
        }

        if ($request->fromDate && $request->fromDate != null && $request->fromDate != "") {
            $miladiDate = convertToMiladi($this->convert($request->fromDate));
            $query = $query->where('created_at', '>=', $miladiDate);
        }

        if ($request->toDate && $request->toDate != null && $request->toDate != "") {
            $miladiDate = convertToMiladi($this->convert($request->toDate));
            $query = $query->where('created_at', '<=', $miladiDate);
        }


        $numbers = $query->count() / $request->numberitems;

        $wallet = $query->skip(($request->numberpage - 1) * $request->numberitems)->take($request->numberitems)->orderBy('id', 'DESC')->get();


        foreach ($wallet as $item) {
            $res = (object)[
                'item' => $item,
                'date' => dateTimeToDate($item->created_at),
                'time' => dateTimeToTime($item->created_at),

            ];
            array_push($all, $res);

        }

        $res = (object)[
            'all' => $all,
            'amont' => User::find($userId)->wallet,
            'number' => ceil($numbers),
        ];

        $response = (object)[
            'data' => $res,
        ];
        return Response::json($response, 200);
    }

    public function dashboardService(Request $request)
    {

        $all = [];
        if (!$request->company) {
            $query = Wallet::where(function ($query) {
                $query->where('isAgent', 0)
                    ->orWhereNull('isAgent');
            })->whereNotNull('numberParcel');
        } else {
            $query = Wallet::where('componyId', $request->company)->where('userId', $request->company)->where(function ($query) {
                $query->where('isAgent', 0)
                    ->orWhereNull('isAgent');
            })->whereNotNull('numberParcel');
        }


        if ($request->fromDate && $request->fromDate != null && $request->fromDate != "") {
            $miladiDate = convertToMiladi($this->convert($request->fromDate));
            $query = $query->where('created_at', '>=', $miladiDate);
        }

        if ($request->toDate && $request->toDate != null && $request->toDate != "") {
            $miladiDate = convertToMiladi($this->convert($request->toDate));
            $query = $query->where('created_at', '<=', $miladiDate);
        }


        $numbers = $query->count() / $request->numberitems;

        $wallet = $query->skip(($request->numberpage - 1) * $request->numberitems)->take($request->numberitems)->orderBy('id', 'DESC')->get();

        foreach ($wallet as $item) {
            $res = (object)[
                'item' => $item,
                'date' => dateTimeToDate($item->created_at),
                'time' => dateTimeToTime($item->created_at),

            ];
            array_push($all, $res);

        }

        $companyAmount = 0;
        if ($request->company) {
            $companyAmount = User::find($request->company)->wallet;
        }

        $res = (object)[
            'all' => $all,
            'amont' => $companyAmount,
            'number' => ceil($numbers),
        ];

        $response = (object)[
            'data' => $res,
        ];
        return Response::json($response, 200);
    }

    public function dashboardServiceExel(Request $request)
    {

        $all = [];
        if (!$request->company) {
            $query = Wallet::where(function ($query) {
                $query->where('isAgent', 0)
                    ->orWhereNull('isAgent');
            })->whereNotNull('numberParcel');
        } else {
            $query = Wallet::where('componyId', $request->company)->where('userId', $request->company)->where(function ($query) {
                $query->where('isAgent', 0)
                    ->orWhereNull('isAgent');
            })->whereNotNull('numberParcel');
        }


        if ($request->fromDate && $request->fromDate != null && $request->fromDate != "") {
            $miladiDate = convertToMiladi($this->convert($request->fromDate));
            $query = $query->where('created_at', '>=', $miladiDate);
        }

        if ($request->toDate && $request->toDate != null && $request->toDate != "") {
            $miladiDate = convertToMiladi($this->convert($request->toDate));
            $query = $query->where('created_at', '<=', $miladiDate);
        }

        $wallet = $query->orderBy('id', 'DESC')->get();

        foreach ($wallet as $item) {
            $debtor = 0;
            $creditor = 0;
            if ($item->serviceType == 'پسکرایه' || $item->serviceType == 'COD') {
                $creditor = $item->amount;
            } else if ($item->serviceType == 'پیشکرایه') {

                $debtor = $item->amount;
            }

            $res = array(
                $item->numberParcel,
                $item->serviceType,
                $debtor,
                $creditor,
                $item->cash,
                dateTimeToDate($item->created_at),
                dateTimeToTime($item->created_at),

            );
            array_push($all, $res);

        }

        //return $all;

        $export = new WalletExport($all);

        return Excel::download($export, 'invoices.xlsx');

    }

    public function dashboardServicePayment(Request $request)
    {
        Wallet::create([
            'trackingCode' => $request->referenceId,
            'cash' => User::find($request->serviceProvider)->wallet - $request->amount,
            'status' => 'بابت تصفیه حساب',
            'amount' => $request->amount,
            'description' => $request->description,
            'type' => $request->type,
            'userId' => $request->serviceProvider,
            'toAccount' => $request->toAccount,
            'fromAccount' => $request->fromAccount,
        ]);

        User::where('id', $request->serviceProvider)->update([
            'wallet' => User::find($request->serviceProvider)->wallet - $request->amount
        ]);

        $response = (object)[
            'data' => "ok",
        ];
        return Response::json($response, 200);
    }

    public function avax(Request $request)
    {
        $all = [];

        $query = Wallet::where('userId', 15)->where(function ($query) {
            $query->where('isAgent', 0)
                ->orWhereNull('isAgent');
        })->whereNotNull('numberParcel');

        /* if ($request->status && $request->status != null && $request->status != "" && $request->status != 0 && $request->status != "اننخاب نشده") {
             $query = $query->where('status', $request->status);
         }*/

        if ($request->company && $request->company != null && $request->company != "" && $request->company != 0) {
            $query = $query->where('componyId', $request->company);
        }

        if ($request->fromDate && $request->fromDate != null && $request->fromDate != "") {
            $miladiDate = convertToMiladi($this->convert($request->fromDate));
            $query = $query->where('created_at', '>=', $miladiDate);
        }

        if ($request->toDate && $request->toDate != null && $request->toDate != "") {
            $miladiDate = convertToMiladi($this->convert($request->toDate));
            $query = $query->where('created_at', '<=', $miladiDate);
        }

        /*$wallet =Wallet::where('userId',$request->user()->id)->where(function ($query) {
            $query->where('isAgent', 0)
                ->orWhereNull('isAgent');
        })->whereNotNull('numberParcel')->where("type","واریز")->skip(($request->numberpage - 1) * $request->numberitems)
            ->take($request->numberitems)->orderBy('id', 'DESC')->get();*/


        $numbers = $query->count() / $request->numberitems;

        $wallet = $query->skip(($request->numberpage - 1) * $request->numberitems)->take($request->numberitems)->orderBy('id', 'DESC')->get();


        foreach ($wallet as $item) {
            $res = (object)[
                'item' => $item,
                'date' => dateTimeToDate($item->created_at),
                'time' => dateTimeToTime($item->created_at),

            ];
            array_push($all, $res);

        }

        $res = (object)[
            'all' => $all,
            'amont' => User::find(15)->wallet,
            'number' => ceil($numbers),
        ];

        $response = (object)[
            'data' => $res,
        ];
        return Response::json($response, 200);
    }

    public function avaxExel(Request $request)
    {
        $all = [];

        $query = Wallet::where('userId', 15)->where(function ($query) {
            $query->where('isAgent', 0)
                ->orWhereNull('isAgent');
        })->whereNotNull('numberParcel')->where("type", "واریز");

        /* if ($request->status && $request->status != null && $request->status != "" && $request->status != 0 && $request->status != "اننخاب نشده") {
             $query = $query->where('status', $request->status);
         }*/

        if ($request->company && $request->company != null && $request->company != "" && $request->company != 0) {
            $query = $query->where('componyId', $request->company);
        }

        if ($request->fromDate && $request->fromDate != null && $request->fromDate != "") {
            $miladiDate = convertToMiladi($this->convert($request->fromDate));
            $query = $query->where('created_at', '>=', $miladiDate);
        }

        if ($request->toDate && $request->toDate != null && $request->toDate != "") {
            $miladiDate = convertToMiladi($this->convert($request->toDate));
            $query = $query->where('created_at', '<=', $miladiDate);
        }

        /*$wallet =Wallet::where('userId',$request->user()->id)->where(function ($query) {
            $query->where('isAgent', 0)
                ->orWhereNull('isAgent');
        })->whereNotNull('numberParcel')->where("type","واریز")->skip(($request->numberpage - 1) * $request->numberitems)
            ->take($request->numberitems)->orderBy('id', 'DESC')->get();*/

        $wallet = $query->orderBy('id', 'DESC')->get();

        foreach ($wallet as $item) {
            $res = array(
                $item->numberParcel,
                $item->amount,
                $item->cash,
                $item->status,
                $item->description,
                dateTimeToDate($item->created_at),
                dateTimeToTime($item->created_at),

            );
            array_push($all, $res);

        }

        //return $all;

        $export = new AvaxWalletExport($all);

        return Excel::download($export, 'invoices.xlsx');
    }

    public function show($id)
    {
        $response = (object)[
            'data' => Wallet::find($id)
        ];
        return Response::json($response, 200);
    }


    public function transferCredit(Request $request)
    {
        $senderUser = User::find($request->user()->id);
        $getterUser = User::where('phone', $request->phone)->first();

        if ($getterUser) {
            if ($request->amount <= $senderUser->wallet) {
                Wallet::create([
                    'userId' => $request->user()->id,
                    'amount' => $request->amount,
                    'type' => ' انتقال به حساب دیگری',
                    'status' => 'برداشت شد',
                    'trackingCode' => $getterUser->id

                ]);
                User::where('id', $request->user()->id)->update([
                    'wallet' => $senderUser->wallet - $request->amount
                ]);

                Wallet::create([
                    'userId' => $getterUser->id,
                    'amount' => $request->amount,
                    'type' => 'انتقال از حساب دیگری',
                    'status' => 'واریزشد',
                    'trackingCode' => $request->user()->id

                ]);
                User::where('id', $getterUser->id)->update([
                    'wallet' => $getterUser->wallet + $request->amount
                ]);

                $response = (object)[
                    'data' => 'با موفقیت انجام شد.',
                ];
                return Response::json($response, 200);
            } else {
                $response = (object)[
                    'data' => 'مبلغ انتقالی بیشتر از سقف موجودی کیف پول می باشد.',
                ];
                return Response::json($response, 444);

            }
        } else {
            $response = (object)[
                'data' => 'کاربری با شماره موبایل' . $request->phone . ' وجود ندارد.',
            ];
            return Response::json($response, 401);

        }
    }

    public function transferAmount(Request $request)
    {


        $senderUser = User::find(15);
        if ($request->agentId) {
            $agent = Agent::find($request->agentId);

            $getterUser = User::find($agent->userId);
        } else {
            $getterUser = User::find($request->userId);
        }

        if ($getterUser) {
            if ($request->status == 'واریز') {
                if ($request->avaex) {
                    Wallet::create([
                        'userId' => 15,
                        'amount' => $request->amount,
                        'cash' => $senderUser->wallet - $request->amount,
                        'type' => 'برداشت',
                        'status' => 'کسر از کیف پول',
                        'trackingCode' => $request->referenceId,
                        'description' => $request->description,

                    ]);
                    User::where('id', 15)->update([
                        'wallet' => $senderUser->wallet - $request->amount
                    ]);
                }


                Wallet::create([
                    'userId' => $getterUser->id,
                    'amount' => $request->amount,
                    'cash' => $getterUser->wallet + $request->amount,
                    'type' => 'واریز',
                    'status' => 'افزایش اعتبار',
                    'trackingCode' => $request->referenceId,
                    'description' => $request->description,

                ]);
                User::where('id', $getterUser->id)->update([
                    'wallet' => $getterUser->wallet + $request->amount
                ]);

            } else {
                if ($request->avaex) {
                    Wallet::create([
                        'userId' => 15,
                        'amount' => $request->amount,
                        'cash' => $senderUser->wallet + $request->amount,
                        'type' => 'واریز',
                        'status' => 'افزایش اعتبار',
                        'trackingCode' => $request->referenceId,
                        'description' => $request->description,

                    ]);
                    User::where('id', 15)->update([
                        'wallet' => $senderUser->wallet + $request->amount
                    ]);
                }


                Wallet::create([
                    'userId' => $getterUser->id,
                    'amount' => $request->amount,
                    'cash' => $getterUser->wallet - $request->amount,
                    'type' => 'برداشت',
                    'status' => 'کسر از کیف پول',
                    'trackingCode' => $request->referenceId,
                    'description' => $request->description,

                ]);
                User::where('id', $getterUser->id)->update([
                    'wallet' => $getterUser->wallet - $request->amount
                ]);

            }
            $response = (object)[
                'data' => 'با موفقیت انجام شد.',
            ];
            return Response::json($response, 200);

        } else {
            $response = (object)[
                'data' => 'کاربری وجود ندارد.',
            ];
            return Response::json($response, 401);

        }
    }

    function convert($string)
    {
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $arabic = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];

        $num = range(0, 9);
        $convertedPersianNums = str_replace($persian, $num, $string);
        $englishNumbersOnly = str_replace($arabic, $num, $convertedPersianNums);

        return $englishNumbersOnly;
    }

}
