<?php

namespace Modules\Discount\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use Modules\Agent\Entities\Agent;
use Modules\Discount\Entities\Discount;
use Modules\Discount\Entities\CustomerDiscount;

class DiscountController extends Controller
{
    public function add(Request $request)
    {

        Discount::create([

            'title' => $request->title,
            'code' => $request->code,
            'type' => $request->type,
            'amount' => $request->amount,
            'min_value' => $request->minValue,
            'max_value' => $request->maxValue,
            'start_date' => Carbon::parse(convertToMiladi($this->convert($request->startDate))),
            'end_date' => Carbon::parse(convertToMiladi($this->convert($request->endDate))),
            'user_limit_number' => $request->limitNumber,
            'description' => $request->description,


        ]);
        $result = (object)[
            'data' => 'با موفقیت ثبت شد.'
        ];
        return Response::json($result, 200);
    }
    public function addDiscountUser(Request $request)
    {

        CustomerDiscount::create([

            'discount_id' => $request->discountId,
            'user_id' => $request->userId,
            'value' => $request->value,
            'status' => $request->status,

        ]);
        $result = (object)[
            'data' => 'با موفقیت ثبت شد.'
        ];
        return Response::json($result, 200);
    }
    public function all(Request $request)
    {
        $all=[];
        $data = Discount::all();

        foreach ($data as $item) {
            $startDate = "00/00/00";
            $endDate = "00/00/00";
            if ($item->start_date){
                $startDate = dateTimeToDate($item->start_date);
            }
            if ($item->end_date){
                $endDate = dateTimeToDate($item->end_date);
            }

            if (Carbon::now()->between($item->start_date, $item->end_date)){
                $status = 'فعال';
            }else{
                $status = 'غیر فعال';
            }
            $res = (object)[
                'id' => $item->id,
                'title' => $item->title,
                'code' => $item->code,
                'type' => $item->type,
                'amount' => $item->amount,
                'description' => $item->description,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'status' => $status,
                'group' =>  $item->groupy,
                'allAgent' =>  $item->allAgent,

            ];
            array_push($all, $res);

        }
        if ($data){
            $result = (object)[
                'data' => $all
            ];
            return Response::json($result, 200);
        }
        else{
            $result = (object)[
                'data' => 'داده ای وجود ندارد.'
            ];
            return Response::json($result, 400);
        }


    }

    public function delete($id)
    {
        Discount::where('id', $id)->delete();
        CustomerDiscount::where('discount_id', $id)->delete();
        $result = (object)[
            'data' => ' با موفقیت انجام شد'
        ];
        return Response::json($result, 200);


    }
    public function deleteDiscountUser($id)
    {
        CustomerDiscount::where('id', $id)->delete();
        $result = (object)[
            'data' => ' با موفقیت انجام شد'
        ];
        return Response::json($result, 200);


    }

    function convert($string) {
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $arabic = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];

        $num = range(0, 9);
        $convertedPersianNums = str_replace($persian, $num, $string);
        $englishNumbersOnly = str_replace($arabic, $num, $convertedPersianNums);

        return $englishNumbersOnly;
    }

    function checkDiscount(Request $request){

       $discount = Discount::query()->where('code',$request->dc)->whereDate('start_date', '<=', Carbon::now())->whereDate('end_date', '>=', Carbon::now())->first();

       if ($discount){

           $agent = Agent::query()->where('userId', $request->user()->id)->where('status','تایید')->first();
           if ($agent){


               if ($discount->allAgent){
                   $result = (object)[
                       'data' =>  (object)[
                           'message' => 'کد تخفیف با موفقیت اعمال شد.',
                           'discount' => $discount,
                       ],
                       'status' => 200,
                   ];
                   return Response::json($result, 200);
               }
               $result = (object)[
                   'data' => 'کد وارد شده معتبر نمی باشد',
                   'status' => 400,
               ];
               return Response::json($result, 400);

           }
         else {
             if ($discount->groupy){
                 $result = (object)[
                     'data' =>  (object)[
                         'message' => 'کد تخفیف با موفقیت اعمال شد.',
                         'discount' => $discount,
                     ],
                     'status' => 200,
                 ];
                 return Response::json($result, 200);

             }
             else{
                 $customer = CustomerDiscount::query()->where('user_id' , $request->user()->id)->where('discount_id',$discount->id)->get();
                 if ($customer ){
                     $result = (object)[
                         'data' =>  (object)[
                             'message' => 'کد تخفیف با موفقیت اعمال شد.',
                             'discount' => $discount,
                         ],
                         'status' => 200,
                     ];
                     return Response::json($result, 200);

                 }
                 else{
                     $result = (object)[
                         'data' => 'کد وارد شده معتبر نمی باشد',
                         'status' => 400,
                     ];
                     return Response::json($result, 400);
                 }
             }

         }

       }
       else{

           $result = (object)[
               'data' => 'کد وارد شده معتبر نمی باشد',
               'status' => 400,
           ];
           return Response::json($result, 400);
       }
    }
    function userDiscount(Request $request){
        $all = [];

        $agent = Agent::query()->where('userId', $request->user()->id)->where('status','تایید')->first();


       $discount = Discount::query()->whereDate('start_date', '<=', Carbon::now())->whereDate('end_date', '>=', Carbon::now())->get();

       foreach ($discount as $item){

           if (!$agent){
               if ($item->groupy){

                   array_push($all,$item) ;


               }
               else{

                   $customer = CustomerDiscount::query()->where('user_id' , $request->user()->id)->where('discount_id',$item->id)->get();
                   if (count($customer) != 0 ){

                       array_push($all,$item) ;


                   }
               }



           }
           else {
               if ($item->allAgent){
                   array_push($all,$item) ;
               }


           }

       }

        $result = (object)[
            'data' =>  $all
        ];
        return Response::json($result, 200);

    }
}
