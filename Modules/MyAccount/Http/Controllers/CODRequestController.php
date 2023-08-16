<?php

namespace Modules\MyAccount\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use Modules\MyAccount\Entities\CODRequest;

class CODRequestController extends Controller
{
    public function add(Request $request)
    {


        CODRequest::create([

            'userId' => $request->user()->id,
            'tel' => $request->tel,
            'nationalCode' => $request->nationalCode,
            'serial' => $request->serial,
            'postCode' => $request->postCode,
            'birthday' => $request->birthday,
            'phone' => $request->phone,
            'email' => $request->email,
            'accountNumber' => $request->accountNumber,
            'shabaNumber' => $request->shabaNumber,
            'bankBranchName' => $request->bankBranchName,
            'cityId' => $request->cityId,
            'provinceId' => $request->provinceId,
            'description' => $request->description,
            'status' => 'تایید نشده',
        ]);
        $result = (object)[
            'data' => 'با موفقیت ثبت شد.'
        ];
        return Response::json($result, 200);
    }
    public function all(Request $request)
    {
        $data = CODRequest::where('userId' , $request->user()->id)->first();
        if ($data){
            $result = (object)[
                'data' => $data
            ];
            return Response::json($result, 200);
        }
        else{
            $result = (object)[
                'data' => 'درخواستی وجود ندارد.'
            ];
            return Response::json($result, 400);
        }


    }
}
