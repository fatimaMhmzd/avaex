<?php

namespace Modules\Agent\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Address\Entities\City;
use Modules\Address\Entities\Country;
use Modules\Address\Entities\Province;
use Modules\Agent\Entities\Agent;
use Modules\Agent\Entities\AgentArea;
use Modules\User\Entities\User;
use Modules\Address\Entities\Area;
use App\Exports\AgentExport;

class AgentAdminController extends Controller
{
    public function add(Request $request)
    {

        $user = User::where('nationalCode', $request->nationalCode)->first();
        if (!$user) {
            //$randomNumber = rand(1111, 9999);
            $randomNumber = 123456;
            $userId = User::create([
                'name' => $request->name,
                'family' => $request->family,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'nationalCode' => $request->nationalCode,
                'email' => $request->email,
                'code' => $randomNumber,
                'IsAgent' => true,
                'status' => false,
                'type' => false,
            ])->id;
        } else {
            $userId = $user->id;
        }
        $agent = Agent::where('userId', $userId)->first();
        if ($agent) {
            $result = (object)[
                'data' => 'برای این کاربر قبلا نمایندگی ثبت شده است .'
            ];
            return Response::json($result, 404);

        }
        else {
            $canUpdate = false;
            if ($request->canUpdate == "دارد") {
                $canUpdate = true;
            }

            Agent::create([
                'userId' => $userId,
                'cityId' => $request->city,
                'provinceId' => $request->provinces,
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
                'canUpdate' => $canUpdate,
                'havingOffice' => $request->havingOffice,
                'relatedExperience' => $request->relatedExperience,
                'equipAbility' => $request->equipAbility,
                'salaryAbility' => $request->salaryAbility,
                'acquaintance' => $request->acquaintance,
                'status' => $request->status,
                'guaranty' => $request->guaranty,
                'gender' => $request->gender,
            ]);
            if ($request->status == "تایید") {
                City::where('id', $request->cityId)->update([
                    'hasAgent' => true
                ]);
                if ($request->areaIds) {
                    $itemss = explode(",", $request->areaIds);
                    foreach ($itemss as $value) {
                        AgentArea::create([
                            "agentId" => $request->id,
                            "areaId" => $value,
                            "status" => "فعال",
                        ]);
                        Area::where('id', $value)->Update([
                            'hasAgent' => true
                        ]);
                        //sendDeriverSms($value,$driverId);
                    }
                }

            }
            $result = (object)[
                'data' => ' با موفقیت انجام شد'
            ];
            return Response::json($result, 200);
        }



    }

    public function all($numberpage, $numberitems, $provinceId, $cityId, $srcinput = '')
    {
        $usersId = [];
        $users = User::where('family', 'like', '%' . $srcinput . '%')
            ->orWhere('name', 'like', '%' . $srcinput . '%')
            ->orWhere('phone', 'like', '%' . $srcinput . '%')
            ->orWhere('nationalCode', 'like', '%' . $srcinput . '%')->get();
        if (!$users->isEmpty()) {
            foreach ($users as $user) {
                array_push($usersId, $user->id);

            }
        }

        $query = Agent::whereIn('userId', $usersId)->where('status',"تایید");


        if ($provinceId != "0") {
            $query = $query->where('provinceId', $provinceId);


        }
        if ($cityId != "0") {

            $query = $query->where('cityId', $cityId);

        }
        $totalNumber = $query->count();

        $agents = $query->skip(($numberpage - 1) * $numberitems)->take($numberitems)->get();

        $finalAgents = [];

        foreach ($agents as $item) {
            $province = Province::find($item->provinceId);
            if ($province) {
                $province = $province->faName;
            }

            $country = Country::find($item->countryId);
            if ($country) {
                $country = $country->faName;
            }
            $city = City::find($item->cityId);
            if ($city) {
                $city = $city->faName;
            }
            $user = User::find($item->userId);


            $result = (object)[
                'agent' => $item,
                'user' => $user,
                'province' => $province,
                'country' => $country,
                'city' => $city,
            ];
            array_push($finalAgents, $result);
        }

        $numbers = $totalNumber / $numberitems;
        $totalData = (object)[
            'agents' => $finalAgents,
            'number' => ceil($numbers),
            'allprovince' => Province::all()

        ];

        $result = (object)[
            'data' => $totalData,

        ];
        return Response::json($result, 200);
    }
    public function allExel($provinceId, $cityId, $srcinput = '')
    {
        $usersId = [];
        $users = User::where('family', 'like', '%' . $srcinput . '%')
            ->orWhere('name', 'like', '%' . $srcinput . '%')
            ->orWhere('phone', 'like', '%' . $srcinput . '%')
            ->orWhere('nationalCode', 'like', '%' . $srcinput . '%')->get();
        if (!$users->isEmpty()) {
            foreach ($users as $user) {
                array_push($usersId, $user->id);

            }
        }

        $query = Agent::whereIn('userId', $usersId)->where('status',"تایید");


        if ($provinceId != "0") {
            $query = $query->where('provinceId', $provinceId);


        }
        if ($cityId != "0") {

            $query = $query->where('cityId', $cityId);

        }
        $totalNumber = $query->count();

        $agents = $query->get();

        $finalAgents = [];

        foreach ($agents as $item) {
            $province = Province::find($item->provinceId);
            if ($province) {
                $province = $province->faName;
            }

            $country = Country::find($item->countryId);
            if ($country) {
                $country = $country->faName;
            }
            $city = City::find($item->cityId);
            if ($city) {
                $city = $city->faName;
            }
            $user = User::find($item->userId);


            $result = array(
                $user->nationalCode,
                $user->phone,
                $user->name,
                $user->family,
                $province,
                $city,
                $item->degree,
                $user->email,

            );
            array_push($finalAgents, $result);


        }


        $export = new AgentExport($finalAgents);

        return Excel::download($export, 'agent.xlsx');
    }
    public function reservation($numberpage, $numberitems, $provinceId, $cityId, $srcinput = '')
    {
        $usersId = [];
        $users = User::where('family', 'like', '%' . $srcinput . '%')
            ->orWhere('name', 'like', '%' . $srcinput . '%')
            ->orWhere('phone', 'like', '%' . $srcinput . '%')
            ->orWhere('nationalCode', 'like', '%' . $srcinput . '%')->get();
        if (!$users->isEmpty()) {
            foreach ($users as $user) {
                array_push($usersId, $user->id);

            }
        }

        $query = Agent::whereIn('userId', $usersId)->where('status',"عدم تایید");


        if ($provinceId != "0") {
            $query = $query->where('provinceId', $provinceId);


        }
        if ($cityId != "0") {

            $query = $query->where('cityId', $cityId);

        }
        $totalNumber = $query->count();

        $agents = $query->skip(($numberpage - 1) * $numberitems)->take($numberitems)->get();

        $finalAgents = [];

        foreach ($agents as $item) {
            $province = Province::find($item->provinceId);
            if ($province) {
                $province = $province->faName;
            }

            $country = Country::find($item->countryId);
            if ($country) {
                $country = $country->faName;
            }
            $city = City::find($item->cityId);
            if ($city) {
                $city = $city->faName;
            }
            $user = User::find($item->userId);


            $result = (object)[
                'agent' => $item,
                'user' => $user,
                'province' => $province,
                'country' => $country,
                'city' => $city,
            ];
            array_push($finalAgents, $result);
        }

        $numbers = $totalNumber / $numberitems;
        $totalData = (object)[
            'agents' => $finalAgents,
            'number' => ceil($numbers),
            'allprovince' => Province::all()

        ];

        $result = (object)[
            'data' => $totalData,

        ];
        return Response::json($result, 200);
    }
    public function reservationExel( $provinceId, $cityId, $srcinput = '')
    {
        $usersId = [];
        $users = User::where('family', 'like', '%' . $srcinput . '%')
            ->orWhere('name', 'like', '%' . $srcinput . '%')
            ->orWhere('phone', 'like', '%' . $srcinput . '%')
            ->orWhere('nationalCode', 'like', '%' . $srcinput . '%')->get();
        if (!$users->isEmpty()) {
            foreach ($users as $user) {
                array_push($usersId, $user->id);

            }
        }

        $query = Agent::whereIn('userId', $usersId)->where('status',"عدم تایید");


        if ($provinceId != "0") {
            $query = $query->where('provinceId', $provinceId);


        }
        if ($cityId != "0") {

            $query = $query->where('cityId', $cityId);

        }


        $agents = $query->get();

        $finalAgents = [];

        foreach ($agents as $item) {
            $province = Province::find($item->provinceId);
            if ($province) {
                $province = $province->faName;
            }

            $country = Country::find($item->countryId);
            if ($country) {
                $country = $country->faName;
            }
            $city = City::find($item->cityId);
            if ($city) {
                $city = $city->faName;
            }
            $user = User::find($item->userId);


            $result = array(
                $user->nationalCode,
                $user->phone,
                $user->name,
                $user->family,
                $province,
                $city,
                $item->degree,
                $user->email,

            );
            array_push($finalAgents, $result);


        }


        $export = new AgentExport($finalAgents);

        return Excel::download($export, 'agent.xlsx');
    }

    public function update($id)
    {
        $allAreaAgent = [];
        $agent = Agent::find($id);
        $city = City::where('provinceId', $agent->provinceId)->get();
        $areas = Area::where('cityId', $agent->cityId)->where(function ($query) use ($id) {
            $query->where('agentId', 0)
                ->orWhere('agentId',  $id);
        })->get();

        //$areas = Area::where('cityId', $agent->cityId)->get();
        if (!$areas->isEmpty()){
        foreach ($areas as $area) {
            $areaAgent = false;
            if ($area->agentId == $id) {
                $areaAgent = true;
            }
            $areaStatus = (object)[
                'area' => $area,
                'status' => $areaAgent,
            ];
            array_push($allAreaAgent, $areaStatus);


        }
        }

        if ($agent) {
            $user = User::find($agent->userId);
            $data = (object)[
                'agent' => $agent,
                'user' => $user,
                'province' => Province::all(),
                'city' => $city,
                'allArea' =>  $allAreaAgent,
                'date' => dateTimeToDate($agent->created_at),
                'time' => dateTimeToTime($agent->created_at),

            ];


        } else {
            $result = (object)[
                'data' => 'نماینده مورد نظر وجود ندارد'
            ];
            return Response::json($result, 401);
        }


        $result = (object)[
            'data' => $data
        ];
        return Response::json($result, 200);

    }

    public function storeUpdate(Request $request)
    {

        $isAgent = false;

//        $user = User::where('nationalCode', $request->nationalCode)->first();
        $agent = Agent::find($request->id);
        if ($agent->status == "تایید") {
            City::where('id', $agent->cityId)->update([
                'hasAgent' => false,
            ]);
            $isAgent = true;

                $pastAreas = Area::where('agentId', $request->id)->get();
                foreach ($pastAreas as $value) {
                    Area::where('id', $value->id)->Update([
                        'agentId' => 0,
                    ]);
                    //sendDeriverSms($value,$driverId);
                }
           
        }
        User::where('id', $request->userId)->update([
            'name' => $request->name,
            'family' => $request->family,
            /* 'password' => Hash::make($request->password),*/
            'email' => $request->email,
            'IsAgent' => $isAgent,
        ]);


        Agent::where('id', $request->id)->update([
            /*   'cityId' => $request->city,
               'provinceId' => $request->province,*/
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
            'canUpdate' => $request->canUpdate,
            'havingOffice' => $request->havingOffice,
            'relatedExperience' => $request->relatedExperience,
            'equipAbility' => $request->equipAbility,
            'salaryAbility' => $request->salaryAbility,
            'acquaintance' => $request->acquaintance,
            'status' => $request->status,
            'gender' => $request->gender,
            'guaranty' => $request->guaranty,
        ]);

        if ($request->status == "تایید") {
            City::where('id', $request->cityId)->update([
                'hasAgent' => true
            ]);
            if ($request->areas) {
                foreach ($request->areas as $value) {
                    Area::where('id', $value)->Update([
                     
                        'agentId' => $request->id,
                    ]);
                   /* AgentArea::create([
                        "agentId" => $request->id,
                        "areaId" => $value,
                        "status" => "فعال",
                    ]);
                    Area::where('id', $value)->Update([
                        'hasAgent' => true
                    ]);*/
                    //sendDeriverSms($value,$driverId);
                }
            }

        }
        $result = (object)[
            'data' => ' با موفقیت انجام شد'
        ];
        return Response::json($result, 200);

    }

    public function delete($id)
    {
        Agent::where('id', $id)->delete();
        User::where('id', $id)->update([

            'IsAgent' => 0,
        ]);
        $result = (object)[
            'data' => ' با موفقیت انجام شد'
        ];
        return Response::json($result, 200);
    }
}
