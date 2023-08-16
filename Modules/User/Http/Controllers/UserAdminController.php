<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Address\Entities\Address;
use Modules\Address\Entities\City;
use Modules\Address\Entities\Country;
use Modules\Address\Entities\Province;
use Modules\Role\Entities\Roles;
use Modules\Role\Entities\RoleUser;
use Modules\User\Entities\User;
use App\Exports\UsersExport;


class UserAdminController extends Controller
{
    public function showUser($numberpage , $numberitems , $srcinput = '')
    {

/*        $users = User::with('roleUser')->where('id', '<',15)->where('family', 'like', '%' . $srcinput . '%')
            ->orWhere('name', 'like', '%' . $srcinput . '%')
            ->orWhere('phone', 'like', '%' . $srcinput . '%')
            ->orWhere('nationalCode', 'like', '%' . $srcinput . '%')
            ->skip(($numberpage-1) * $numberitems)->take($numberitems)->get();*/

        $users =User::with('roleUser')->where(function ($query) {
            $query->where('id', '>',15);
        })->where(function ($query) use ($srcinput) {
            $query->where('family', 'like', '%' . $srcinput . '%')
                ->orWhere('name', 'like', '%' . $srcinput . '%')
                ->orWhere('phone', 'like', '%' . $srcinput . '%')
                ->orWhere('nationalCode', 'like', '%' . $srcinput . '%');
        })->skip(($numberpage-1) * $numberitems)->take($numberitems)->get();

        $numbers = count (User::with('roleUser')->where(function ($query) {
                $query->where('id', '>',15);
            })->where(function ($query) use ($srcinput) {
                $query->where('family', 'like', '%' . $srcinput . '%')
                    ->orWhere('name', 'like', '%' . $srcinput . '%')
                    ->orWhere('phone', 'like', '%' . $srcinput . '%')
                    ->orWhere('nationalCode', 'like', '%' . $srcinput . '%');
            })->get())/$numberitems;
        $totalData = (object)[
            'users' => $users ,
            'number' => ceil($numbers),

        ];

        $result = (object)[
            'data' => $totalData ,

        ];
        return Response::json($result, 200);
    }
    public function showUserExel($srcinput = '')
    {
        $data = [];

        $users = User::with('roleUser')->where(function ($query) {
            $query->where('id', '>',15);
        })->where(function ($query) use ($srcinput) {
            $query->where('family', 'like', '%' . $srcinput . '%')
                ->orWhere('name', 'like', '%' . $srcinput . '%')
                ->orWhere('phone', 'like', '%' . $srcinput . '%')
                ->orWhere('nationalCode', 'like', '%' . $srcinput . '%');
        })->get();
        foreach ($users as $item){
            $status = "غیرفعال";
            if ($item->status == 1){
                $status = "فعال";
            }
            $IsAdmin = "خیر";
            if ($item->IsAdmin == 1){
                $IsAdmin = "بلی";
            }
            $type ="حقیقی";
            if ($item->type == 1){
                $type = "حقوقی";
            }
            $res = array(
                $item->nationalCode,
                $item->name,
                $item->family,
                $item->phone,
                $item->email,
                $status,
                $IsAdmin,
                $type,

            );
            array_push($data, $res);
        }

        $export = new UsersExport($data);

        return Excel::download($export, 'user.xlsx');

    }
    public function update($id)
    {

        $user = User::with('roleUser')->find($id);
        $roles =  Roles::all();
        $res = (object)[
            'user' => $user ,
            'role' => $roles

        ];

        $result = (object)[
            'data' => $res ,

        ];
        return Response::json($result, 200);
    }
    public function storeUpdate(Request $request)
    {


        $userData = User::find($request->id);

        if ($userData == null) {
            $result = (object)[
                'data' => 'کاربر مورد نظر یافت نشد ',
            ];
            return Response::json($result, 444 );
        } else {
            $password =$userData->password;

            if($request->type == 0){
                if ($request->password != null) {
                    $password = Hash::make($request->password);
                }

                User::where('id', $request->id)->Update([
                    'name' => $request->name,
                    'family' => $request->family,
                    'phone' => $request->phone,
                    'password' => $password ,
                    'nationalCode' => $request->nationalCode,
                    'email' => $request->email,
                    'status' => $request->status,
                    'IsAdmin' => $request->IsAdmin,



                ]);

            }else{
                if ($request->Cpasswordword != null) {
                    $password = Hash::make($request->Cpasswordword);
                }

                User::where('id', $request->id)->Update([
                    'name' => $request->Cname,
                    'phone' => $request->Cphone,
                    'password' => $password ,
                    'nationalCode' => $request->CshenaseMeklii,
                    'email' => $request->Cemail,
                    'registrNumber' => $request->CshomareSabt,
                    'status' => $request->status,
                    'IsAdmin' => $request->IsAdmin,

                ]);

            }
            $result = (object)[
                'data' => 'با موفقیت انجام شد '
            ];
            return Response::json($result, 200);
        }



    }

    public function deleteUser($id)
    {
        $userData = User::where('id', $id)->get();
        if ($userData->isEmpty()) {
            $result = (object)[
                'data' => 'کاربر مورد نظر یافت نشد ',
            ];
            return Response::json($result, 444 );
        } else {
        User::where('id', $id)->delete();
        RoleUser::where('userId', $id)->delete();
        $result = (object)[
            'data' => 'با موفقیت انجام شد '
        ]; }
        return Response::json($result, 200);
    }

    public function add(Request $request)
    {

        $user = User::withoutTrashed()->where('nationalCode', $request->nationalCode)->orWhere('phone', $request->phone)->get();


        if ($user->isEmpty()) {

            if ($request->password == $request->repassword) {
                $randomNumber = rand(1111, 9999);
                $cityname = City::find($request->city)->faName;
                $provicename = Province::find($request->provinces)->faName;
                $countryname = Country::find(1)->faName;

                $type = 1;
                if ($request->type == "حقیقی"){
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

                $result = (object)[
                    'data' => "با موفقیت انجام شد."
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



}
