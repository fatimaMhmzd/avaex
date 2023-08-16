<?php

namespace Modules\Wallet\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use Modules\Wallet\Entities\AccountBank;
class AccountBankController extends Controller
{
    public function add(Request $request)
    {

        AccountBank::create([
            'userId'=>$request->userId,
            'name'=>User::find($request->userId)->name,
            'bankName' => $request->bankName,
            'accountNumber' => $request->accountNumber,
            'shaba' => $request->shaba

        ]);
        $result = (object)[
            'data' => 'با موفقیت انجام شد .'
        ];
        return Response::json($result, 200);
    }
    public function update(Request $request)
    {
        AccountBank::where('id' , $request->id)->Update([
            'bankName' => $request->bankName,
            'accountNumber' => $request->accountNumber,
            'shaba' => $request->shaba

        ]);
        $result = (object)[
            'data'=>'با موفقیت انجام شد'
        ];
        return Response::json($result, 200);
    }
    public function delete($id)
    {
        AccountBank::where('id',$id)->delete();
        $result = (object)[
            'data' => 'با موفقیت انجام شد .'
        ];
        return Response::json($result, 200);
    }

    public function all(){

        $all = AccountBank::all();
        $users = User::where('id','<',16)->get();
        $res = (object)[
            'all'=>$all,
            'user'=>$users

        ];
        $result = (object)[
            'data'=>$res,

        ];

        return Response::json($result, 200);


    }

    public function show($id){
        $data = AccountBank::find($id);

        $result = (object)[
            'data'=> $data
        ];
        return Response::json($result, 200);


    }

}
