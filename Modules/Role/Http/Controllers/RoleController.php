<?php

namespace Modules\Role\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use Modules\Role\Entities\Roles;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('role::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('role::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('role::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('role::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }


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
            if($rolesData[0] -> can_delete == true){
                Roles::where('id', $id)->delete();
                $result = (object)[
                    'data' => 'با موفقیت انجام شد '
                ];
                return Response::json($result, 200);
            }else{
                $result = (object)[
                    'data' => 'این فرایند امکان پذیر نمی باشد '
                ];
                return Response::json($result, 400);

            }


        }

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
            if($rolesData[0] -> can_update == true){
                Roles::where('id', $request->roleId)->Update([
                    'title' => $request->title,

                ]);
                $result = (object)[
                    'data' => 'با موفقیت انجام شد '
                ];
                return Response::json($result, 200);
            }else{
                $result = (object)[
                    'data' => 'این فرایند امکان پذیر نمی باشد '
                ];
                return Response::json($result, 400);


            }

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
