<?php

namespace Modules\Discount\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use Modules\Agent\Entities\DriverItem;
use Modules\Discount\Entities\CustomerDiscount;
use Modules\Discount\Entities\Discount;
use Modules\TotalPost\Entities\TotalPost;

class DiscountUserController extends Controller
{
    public function setDiscountAll(Request $request)
    {

        if ($request->flag){
            Discount::query()->where('id',$request->discountId)->update(['allAgent'=>1]);
        }
        else {
            Discount::query()->where('id',$request->discountId)->update(['groupy'=>1]);
        }

        $result = (object)[
            'data' => ' با موفقیت انجام شد',
            'status' => 200,
        ];
        return Response::json($result, 200);


    }
    public function updateDiscountAll(Request $request)
    {

        if ($request->flag){
            Discount::query()->where('id',$request->discountId)->update(['allAgent'=>0]);
        }
        else {
            Discount::query()->where('id',$request->discountId)->update(['groupy'=>0]);
        }

        $result = (object)[
            'data' => ' با موفقیت انجام شد',
            'status' => 200,
        ];
        return Response::json($result, 200);


    }

    public function setGroupDiscount(Request $request)
    {
        $itemss = explode(",", $request->itemId);
        foreach ($itemss as $value) {
            CustomerDiscount::create([
                'discount_id' => $request->discountId,
                'user_id' => $value,
            ]);
        }


        $result = (object)[
            'data' => ' با موفقیت انجام شد',
            'status' => 200,
        ];
        return Response::json($result, 200);


    }

    public function all(Request $request)
    {
        $data = CustomerDiscount::all();
        if ($data) {
            $result = (object)[
                'data' => $data
            ];
            return Response::json($result, 200);
        } else {
            $result = (object)[
                'data' => 'داده ای وجود ندارد.'
            ];
            return Response::json($result, 400);
        }


    }

    public function delete($id)
    {
        CustomerDiscount::where('id', $id)->delete();
        $result = (object)[
            'data' => ' با موفقیت انجام شد'
        ];
        return Response::json($result, 200);


    }
    public function showDiscountUser($id)
    {
        CustomerDiscount::where('id', $id)->delete();
        $result = (object)[
            'data' => ' با موفقیت انجام شد'
        ];
        return Response::json($result, 200);


    }

}
