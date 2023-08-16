<?php

namespace Modules\User\Http\Controllers;

use DateTime;
use DateTimeZone;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Modules\Address\Entities\Address;
use Modules\Address\Entities\Area;
use Modules\Address\Entities\City;
use Modules\Address\Entities\Country;
use Modules\Address\Entities\Province;
use Modules\Role\Entities\RoleUser;
use Modules\User\Entities\User;
use Modules\Agent\Entities\Agent;

class UserController extends Controller
{

    public function register1(Request $request)
    {
        $url = "https://portal.amootsms.com/webservice2.asmx/SendWithPattern_REST";

        $url = $url . "?" . "UserName=09157076552";
        $url = $url . "&" . "Password=7275327Mj";


        $user = User::withoutTrashed()->where('nationalCode', $request->nationalCode)->orWhere('phone', $request->phone)->get();


        if ($user->isEmpty()) {

            if ($request->password == $request->repassword) {
                $randomNumber = rand(1111, 9999);
                $type = false;
                $cityname = City::find($request->city)->faName;
                $provicename = Province::find($request->provinces)->faName;
                $countryname = Country::find(1)->faName;

                $type = 1;
                if ($request->type == "حقیقی") {
                    $type = 0;
                }

                $userId = User::create([
                    'name' => $request->name,
                    'family' => $request->family,
                    'phone' => $request->phone,
                    'password' => Hash::make($request->password),
                    'nationalCode' => $request->nationalCode,
                    'registrNumber' => $request->registrNumber,
                    'email' => $request->email,
                    'code' => $randomNumber,
                    'status' => false,
                    'type' => $type,
                ])->id;

                Address::create([
                    'userId' => $userId,
                    'cityId' => $request->city,
                    'provinceId' => $request->provinces,
                    'countryId' => 1,
                    'address' => $request->address,
                    'postCode' => $request->postCode,
                    'name' => $request->name,
                    'family' => $request->family,
                    'tel' => $request->tel,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'compony' => $request->name,
                    'totalAddress' => $countryname . " " . $provicename . " " . $cityname . " " . $request->address . " " . $request->name . " " . $request->family . " " . $request->phone . " " . $request->compony,
                    'description' => $request->description,
                    'enDescription' => $request->enDescription,
                    'Default' => 1,
                    'type' => $type,
                    'nationalCode' => $request->nationalCode,
                    'senderOrgetter' => 0
                ]);
                RoleUser:: create([
                    'userId' => $userId,
                    'roleId' => 1
                ]);

                $userName = $request->name . " " . $request->family;
                $userName = urlencode($userName);
                $random = $randomNumber;
                $mobile = preg_replace('/^0/', '', $request->phone);

                $url = $url . "&" . "Mobile=" . $mobile;
                $url = $url . "&" . "PatternCodeID=1629";
                $url = $url . "&" . "PatternValues=$userName,$random";


                $json = file_get_contents($url);
                $objectResult = json_decode($json);

                $result = (object)[
                    'data' => $objectResult->Status,
                ];
                return Response::json($result, 200);

            } else {
                $result = (object)[
                    'data' => 'رمز و تکرار رمز تطابق ندارد.'
                ];
                return Response::json($result, 444);
            }


        } else {

            $result = (object)[
                'data' => 'اطلاعات واردشده قبلادرسیستم ثبت شده است'
            ];
            return Response::json($result, 401);

        }


    }

    public function register
    (Request $request)
    {
        $url = "https://portal.amootsms.com/webservice2.asmx/SendWithPattern_REST";

        $url = $url . "?" . "UserName=09157076552";
        $url = $url . "&" . "Password=7275327Mj";


        $user = User::withoutTrashed()->where('nationalCode', $request->nationalCode)->first();

        if ($user) {

            if ($user->status) {
                $result = (object)[
                    'data' => 'اطلاعات واردشده قبلادرسیستم ثبت شده است'
                ];
                return Response::json($result, 401);
            }
            else {

                $randomNumber = rand(1111, 9999);
                User::where('id', $user->id)->Update([
                    'phone' => $request->phone,
                    'code' => $randomNumber,
                    'status' => false,
                ]);

                $userName = $request->name . " " . $request->family;
                $userName = urlencode($userName);
                $random = $randomNumber;
                $mobile = preg_replace('/^0/', '', $request->phone);

                $url = $url . "&" . "Mobile=" . $mobile;
                $url = $url . "&" . "PatternCodeID=1629";
                $url = $url . "&" . "PatternValues=$userName,$random";


                $json = file_get_contents($url);
                $objectResult = json_decode($json);
                $result = (object)[
                    'data' => $objectResult->Status,
                ];
                return Response::json($result, 200);
            }

        }
        else {
            $userPhone =User::withoutTrashed()->where('phone', $request->phone)->first();

            if ($userPhone){
                $result = (object)[
                    'data' => 'اطلاعات واردشده قبلادرسیستم ثبت شده است'
                ];
                return Response::json($result, 401);
            }
            else{

                $randomNumber = rand(1111, 9999);
                $cityname = City::find($request->city)->faName;
                $provicename = Province::find($request->provinces)->faName;
                $countryname = Country::find(1)->faName;
                $areaname = '';
                    if($request->area !=0){
                        $areaname = Area::find($request->area)->faName;
                    }


                $type = 1;
                if ($request->type == "حقیقی") {
                    $type = 0;
                }

                $userId = User::create([
                    'name' => $request->name,
                    'family' => $request->family,
                    'phone' => $request->phone,
                    'password' => Hash::make($request->password),
                    'nationalCode' => $request->nationalCode,
                    'registrNumber' => $request->registrNumber,
                    'email' => $request->email,
                    'code' => $randomNumber,
                    'status' => false,
                    'type' => $type,
                ])->id;

                Address::create([
                    'userId' => $userId,
                    'cityId' => $request->city,
                    'provinceId' => $request->provinces,
                    'areaId' => $request->area,
                    'countryId' => 1,
                    'address' => $request->address,
                    'postCode' => $request->postCode,
                    'name' => $request->name,
                    'family' => $request->family,
                    'tel' => $request->tel,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'compony' => $request->name,
                    'totalAddress' => $countryname . " " . $provicename . " " . $cityname . " " . $request->address . " " . $request->name . " " . $request->family . " " . $request->phone . " " . $request->compony ." " . $areaname,
                    'description' => $request->description,
                    'enDescription' => $request->enDescription,
                    'Default' => 1,
                    'type' => $type,
                    'nationalCode' => $request->nationalCode,
                    'senderOrgetter' => 0
                ]);
                RoleUser:: create([
                    'userId' => $userId,
                    'roleId' => 1
                ]);

                $userName = $request->name . " " . $request->family;
                $userName = urlencode($userName);
                $random = $randomNumber;
                $mobile = preg_replace('/^0/', '', $request->phone);

                $url = $url . "&" . "Mobile=" . $mobile;
                $url = $url . "&" . "PatternCodeID=1629";
                $url = $url . "&" . "PatternValues=$userName,$random";


                $json = file_get_contents($url);
                $objectResult = json_decode($json);

                $result = (object)[
                    'data' => $objectResult->Status,
                ];
                return Response::json($result, 200);

            }


        }


    }

    public function code(Request $request)
    {

        $data = User::where('phone', $request->phone)->where('code', $request->code)->get();
        if ($data->isEmpty()) {
            $result = (object)[
                'data' => 'کد اشتباه است',
            ];
            return Response::json($result, 401);
        } else {
            $token = $data[0]->createToken('token_base_name')->plainTextToken;

            User::where('id', $data[0]->id)->Update([
                'status' => true

            ]);
            $result = (object)[
                'data' => $token,
                'user' => $data[0],
            ];
            return Response::json($result, 200);
        }

    }

    public function updatPassword(Request $request)
    {
        $user = User::find($request->user()->id);
        if ($request->newPass == $request->repeatPass) {
            if (Hash::check($request->oldPass, $user->password)) {
                User::where('id', $request->user()->id)->Update([
                    'password' => Hash::make($request->newPass),
                ]);
                $result = (object)[
                    'data' => 'با  موفقیت انجام شد.',
                ];
                return Response::json($result, 200);
            } else {

                $result = (object)[
                    'data' => 'رمز عبور اشتباه است.',

                ];
                return Response::json($result, 401);
            }
        } else {
            $result = (object)[
                'data' => 'رمز و تکرار رمز تطابق ندارد.'
            ];
            return Response::json($result, 444);
        }

    }


    public function login(Request $request)
    {
        $userData = User::where('nationalCode', $request->nationalCode)->get();

        if ($userData->isEmpty()) {
            $result = (object)[
                'data' => 'اطلاعات وارد شده درست نمی باشد . ',
            ];
            return Response::json($result, 401);
        } else if (!$userData[0]->status) {
            $result = (object)[
                'data' => 'کاربر احراز هویت نشده.',
            ];
            return Response::json($result, 401);
        } else {
            if (Hash::check($request->password, $userData[0]->password)) {
                $token = $userData[0]->createToken('token_base_name')->plainTextToken;
                $agent = Agent::where('userId', $userData[0]->id)->first();
                $city = "";
                if ($agent) {
                    $city = City::find($agent->cityId)->faName;
                }
                $result = (object)[
                    'data' => $token,
                    'user' => $userData[0],
                    'city' => $city,
                ];
                return Response::json($result, 200);

            } else {
                $result = (object)[
                    'data' => 'اطلاعات وارد شده درست نمی باشد .',
                ];
                return Response::json($result, 401);

            }

        }


    }

    public function loginAgent(Request $request)
    {

        $userData = User::where('nationalCode', $request->nationalCode)->first();
        if (!$userData) {
            $result = (object)[
                'data' => 'اطلاعات وارد شده درست نمی باشد . ',
            ];
            return Response::json($result, 401);
        } else {
            $userAgent = Agent::where('userId', $userData->id)->first();


            if (Hash::check($request->password, $userData->password)) {

                if ($userAgent) {
                    $token = $userData->createToken('token_base_name')->plainTextToken;

                    $result = (object)[
                        'data' => $token,
                        'user' => $userData,
                        'agent' => $userAgent
                    ];
                    return Response::json($result, 200);
                } else {

                    $result = (object)[
                        'data' => 'اطلاعات وارد شده درست نمی باشد .',
                    ];
                    return Response::json($result, 401);

                }


            } else {
                return 'tttttttttttttttttt';
                $result = (object)[
                    'data' => 'اطلاعات وارد شده درست نمی باشد .',
                ];
                return Response::json($result, 401);

            }

        }


    }


    public function loginAdmin(Request $request)
    {

        $userData = User::where('nationalCode', $request->nationalCode)->first();

        if (!$userData) {
            $result = (object)[
                'data' => 'اطلاعات وارد شده درست نمی باشد . ',
            ];
            return Response::json($result, 401);
        } else {

            if (Hash::check($request->password, $userData->password)) {

                if ($userData->IsAdmin) {
                    $token = $userData->createToken('token_base_name')->plainTextToken;

                    $result = (object)[
                        'data' => $token,
                        'user' => $userData,
                    ];
                    return Response::json($result, 200);
                } else {

                    $result = (object)[
                        'data' => 'اطلاعات وارد شده درست نمی باشد .',
                    ];
                    return Response::json($result, 401);

                }


            } else {

                $result = (object)[
                    'data' => 'اطلاعات وارد شده درست نمی باشد .',
                ];
                return Response::json($result, 401);

            }

        }


    }

    public function loginAgentWithToken(Request $request)
    {
        $tokennn = str_replace("Bearer ", "", $request->header('Authorization'));
        $userData = Auth::user();
        if (!$userData) {
            $result = (object)[
                'data' => 'اطلاعات وارد شده درست نمی باشد . ',
            ];
            return Response::json($result, 401);
        } else {
            $userAgent = Agent::where('userId', $userData->id)->first();


            if ($userAgent) {
                //$token = $userData->createToken('token_base_name')->plainTextToken;

                $result = (object)[
                    'data' => $tokennn,
                    'user' => $userData,
                    'agent' => $userAgent
                ];
                return Response::json($result, 200);
            } else {

                $result = (object)[
                    'data' => 'اطلاعات وارد شده درست نمی باشد .',
                ];
                return Response::json($result, 401);

            }

        }


    }

    public function loginAdminWithToken(Request $request)
    {
        $tokennn = str_replace("Bearer ", "", $request->header('Authorization'));
        $userData = Auth::user();
        if (!$userData) {
            $result = (object)[
                'data' => 'اطلاعات وارد شده درست نمی باشد . ',
            ];
            return Response::json($result, 401);
        } else {


            if ($userData->IsAdmin) {
                //$token = $userData->createToken('token_base_name')->plainTextToken;

                $result = (object)[
                    'data' => $tokennn,
                    'user' => $userData,
                ];
                return Response::json($result, 200);
            } else {

                $result = (object)[
                    'data' => 'اطلاعات وارد شده درست نمی باشد .',
                ];
                return Response::json($result, 401);

            }

        }


    }

    public function forgetPassword(Request $request)
    {

        $url = "https://portal.amootsms.com/webservice2.asmx/SendWithPattern_REST";

        $url = $url . "?" . "UserName=09157076552";
        $url = $url . "&" . "Password=7275327Mj";


        $userData = User::where('phone', $request->phone)->first();

        if ($userData) {
            $random = rand(111111, 999999);
            User::where('phone', $request->phone)->update([
                'password' => Hash::make($random)
            ]);
            $userName = $userData->name . " " . $userData->family;
            $userName = urlencode($userName);
            $randomNumber = $random;
            $mobile = preg_replace('/^0/', '', $request->phone);

            $url = $url . "&" . "Mobile=" . $mobile;
            $url = $url . "&" . "PatternCodeID=1630";
            $url = $url . "&" . "PatternValues=$userName,$randomNumber";


            $json = file_get_contents($url);
            $objectResult = json_decode($json);

            $result = (object)[
                'pass' => $random,
                'data' => "کد با موفقیت برای شما ارسال شد.",
            ];
            return Response::json($result, 200);

        } else {
            $result = (object)[
                'data' => 'اطلاعات وارد شده درست نمی باشد .',
            ];
            return Response::json($result, 400);

        }
    }

    public function updateUser(Request $request)
    {

        User::where('id', $request->user()->id)->Update([
            'name' => $request->name,
            'family' => $request->family,


        ]);
        $result = (object)[
            'data' => 'با موفقیت انجام شد '
        ];
        return Response::json($result, 200);
    }


    public function showUser(Request $request)
    {

        $result = (object)[
            'data' => $request->user(),

        ];

        return Response::json($result, 200);


    }

    public function test()
    {

        /*$url = "https://portal.amootsms.com/webservice2.asmx/SendQuickOTP_REST";

        $url = $url."?"."UserName=09157076552";
        $url = $url."&"."Password=7275327Mj";
        $url = $url."&"."Mobile=09114227969";
        $url = $url."&"."CodeLength=4";
        $url = $url."&"."OptionalCode=";

        $json = file_get_contents($url);
        echo $json;*/

        $url = "https://portal.amootsms.com/webservice2.asmx/SendWithPattern_REST";
        //$url = "https://portal.amootsms.com/webservice2.asmx/SendWithPattern_REST?UserName=09157076552&Password=7275327Mj";

        $url = $url . "?" . "UserName=09157076552";
        $url = $url . "&" . "Password=7275327Mj";

        //env("IMAP_HOSTNAME_TEST", "somedefaultvalue");

        $userName = "فاطمه محمدزاده" . " " . "asasd";
        $userName = urlencode($userName);
        $randomNumber = 1347;

        $url = $url . "&" . "Mobile=9138546152";
        $url = $url . "&" . "PatternCodeID=1629";
        $url = $url . "&" . "PatternValues=$userName,$randomNumber";


        $json = file_get_contents($url);
        $objectResult = json_decode($json);
        echo $objectResult->Status;


    }

    public function test1()
    {
        $url = "https://portal.amootsms.com/webservice2.asmx/SendWithBackupLine_REST";

        $url = $url . "?" . "UserName=09157076552";
        $url = $url . "&" . "Password=7275327Mj";

        $nowIran = new DateTime('now', new DateTimeZone('IRAN'));

        $url = $url . "&" . "SendDateTime=" . $nowIran->format('c');

        $url = $url . "&" . "SMSMessageText=" . urlencode("پیامک تستی من");
        $url = $url . "&" . "LineNumber=Service";
        $url = $url . "&" . "BackupLineNumber=98";

        $url = $url . "&" . "Mobiles=9114227969";

        $json = file_get_contents($url);
        echo $json;


    }

    public function test2()
    {
        $authority = request()->query('Authority'); // دریافت کوئری استرینگ ارسال شده توسط زرین پال
        $status = request()->query('Status'); // دریافت کوئری استرینگ ارسال شده توسط زرین پال

        $response = zarinpal()
            ->merchantId('00000000-0000-0000-0000-000000000000') // تعیین مرچنت کد در حین اجرا - اختیاری
            ->amount(100)
            ->verification()
            ->authority($authority)
            ->send();

        if (!$response->success()) {
            return $response->error()->message();
        }

// دریافت هش شماره کارتی که مشتری برای پرداخت استفاده کرده است
// $response->cardHash();

// دریافت شماره کارتی که مشتری برای پرداخت استفاده کرده است (بصورت ماسک شده)
// $response->cardPan();

// پرداخت موفقیت آمیز بود
// دریافت شماره پیگیری تراکنش و انجام امور مربوط به دیتابیس
        return $response->referenceId();

    }



}
