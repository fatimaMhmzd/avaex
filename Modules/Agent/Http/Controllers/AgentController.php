<?php

namespace Modules\Agent\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Modules\Address\Entities\City;
use Modules\Agent\Entities\Agent;
use Modules\Agent\Entities\Driver;
use Modules\Agent\Entities\DriverItem;
use Modules\TotalPost\Entities\TotalPost;
use Modules\User\Entities\User;

class AgentController extends Controller
{

    public function add(Request $request)
    {
        $user = User::find($request->user()->id);
        if ($user) {
            $agent = Agent::where('userId', $request->user()->id)->first();
            if ($agent) {
                $result = (object)[
                    'data' => 'برای این کاربر قبلا نمایندگی ثبت شده است .'
                ];
                return Response::json($result, 404);

            }
            else {
                Agent::create([
                    'userId' => $request->user()->id,
                    'cityId' => $request->cityId,
                    'provinceId' => $request->provinceId,
                    'address' => $request->address,
                    'major' => $request->major,
                    'degree' => $request->degree,
                    'tel' => $request->tel,
                    'mobile' => $request->phone,
                    'email' => $request->email,
                    'married' => $request->married,
                    'age' => $request->age,
                    'description' => $request->description,
                    'military' => $request->military,
                    'havingOffice' => $request->havingOffice,
                    'relatedExperience' => $request->relatedExperience,
                    'equipAbility' => $request->equipAbility,
                    'salaryAbility' => $request->salaryAbility,
                    'acquaintance' => $request->acquaintance,
                    'canUpdate' => false,
                    'guaranty' => $request->guaranty,
                    'gender' => $request->gender,

                ]);
                $result = (object)[
                    'data' => ' با موفقیت انجام شد'
                ];
                return Response::json($result, 200);
            }



        } else {
            $result = (object)[
                'data' => 'کاربر وجود ندارد یا احراز هویت نشده'
            ];
            return Response::json($result, 401);

        }


    }

    public function driver(Request $request)
    {
        $agent = Agent::where("userId", $request->user()->id)->first();
        $driver = [];
        if ($agent) {
            $driver = Driver::where('agentId', $agent->id)->get();
        }
        $response = (object)[
            'data' => $driver,
        ];
        return Response::json($response, 200);
    }

    public function setDriver(Request $request, $driverId, $itemId)
    {

        DriverItem::create([
            "driverId" => $driverId,
            "itemId" => $itemId,
            "status" => "جمع آوری نشده",
        ]);

        TotalPost::where('id',$itemId)->Update([
            'status' => "ارجاع به راننده"
        ]);


        $result = (object)[
            'data' => ' با موفقیت انجام شد'
        ];
        return Response::json($result, 200);


    }
    public function setGroupDriver(Request $request, $driverId, $itemId)
    {
        $itemss = explode(",",$itemId);
        foreach ($itemss as $value){
            DriverItem::create([
                "driverId" => $driverId,
                "itemId" => $value,
                "status" => "جمع آوری نشده",
            ]);
            TotalPost::where('id',$value)->Update([
                'status' => "ارجاع به راننده"
            ]);
            //sendDeriverSms($value,$driverId);
        }



        $result = (object)[
            'data' => ' با موفقیت انجام شد'
        ];
        return Response::json($result, 200);


    }
    public function setGroupDriverRemember(Request $request, $itemId)
    {
        $itemss = explode(",",$itemId);
        foreach ($itemss as $value){
            $driverItem = DriverItem::where('itemId',$value)->first();
            if ($driverItem){
                sendDeriverSms($value,$driverItem->driverId);
            }
        }

        $result = (object)[
            'data' => ' با موفقیت انجام شد'
        ];
        return Response::json($result, 200);

    }

}
