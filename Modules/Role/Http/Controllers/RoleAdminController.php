<?php

namespace Modules\Role\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use Modules\Role\Entities\Roles;

class RoleAdminController extends Controller
{


    public function showRole($srcinput = '')
    {
        $result = (object)[
            'data' =>  Roles::where('title', 'like', '%' . $srcinput . '%')->get(),

        ];
        return Response::json($result, 200);
    }




    public function deleteRole($id)
    {
        $rolesData = Roles::where('id', $id)->get();
        if ($rolesData->isEmpty()) {
            $result = (object)[
                'data' => 'نقش مورد نظر یافت نشد ',
            ];
            return Response::json($result, 444 );
        } else {
            Roles::where('id', $id)->delete();
            $result = (object)[
                'data' => 'با موفقیت انجام شد '
            ]; }
        return Response::json($result, 200);
    }


    public function updateRole(Request $request)
    {
        $rolesData = Roles::where('id', $request->roleId)->get();

        if ($rolesData->isEmpty()) {
            $result = (object)[
                'data' => 'کاربر مورد نظر یافت نشد ',
            ];
            return Response::json($result, 444 );
        } else {
            if($rolesData->can_update == true){
                Roles::where('id', $request->roleId)->Update([
                    'title' => $request->title,

                ]);
                $result = (object)[
                    'data' => 'با موفقیت انجام شد '
                ];}
            return Response::json($result, 200);
            }


    }


    public function addRole(Request $request)
    {

        Roles::create([
            'title' => $request->title,
            'can_delete' => $request->candelete,
            'can_update' => $request->canupdate,
            ]);
            $result = (object)[
                'data' => 'با موفقیت انجام شد .'
            ];
            return Response::json($result, 200);


    }



}
