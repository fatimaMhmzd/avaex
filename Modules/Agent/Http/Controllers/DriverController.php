<?php

namespace Modules\Agent\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use Modules\Agent\Entities\Driver;

class DriverController extends Controller
{

    public function add(Request $request)
    {
        Driver::create([
            "agentId"=>$request->agentId,
            "mobile"=>$request->mobile,
            "name"=>$request->name,
        ]);

        $result = (object)[
            'data' => ' با موفقیت انجام شد'
        ];
        return Response::json($result, 200);

    }
    public function update(Request $request)
    {
        Driver::where('id',$request->id)->update([
            "mobile"=>$request->mobile,
            "name"=>$request->name,
        ]);

        $result = (object)[
            'data' => ' با موفقیت انجام شد'
        ];
        return Response::json($result, 200);
    }
    public function delete($id)
    {
        Driver::where('id',$id)->delete();
    }
    public function list($agentId)
    {
        $data = Driver::where('agentId',$agentId)->get();

        $result = (object)[
            'data' => $data
        ];
        return Response::json($result, 200);
    }
    public function single($id)
    {
        $data = Driver::find($id);
        $result = (object)[
            'data' => $data
        ];
        return Response::json($result, 200);
    }

}
