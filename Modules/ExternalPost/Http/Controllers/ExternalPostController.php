<?php

namespace Modules\ExternalPost\Http\Controllers;


use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\ExternalPost\Entities\ExternalOrder;
use Modules\ExternalPost\Entities\ExternalPost;
use Modules\TotalPost\Entities\TotalPost;


class ExternalPostController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('externalpost::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */


    public function create()
    {
        return view('externalpost::create');
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
        return view('externalpost::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('externalpost::edit');
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

    /**
     * @OA\Post(
     *   path="/api/ExternalPost/v1/addExternalPost",
     *   tags={"externalPostCreat"},
     *   summary="درخواست پست خارجی",
     *       description="توضیحات",
     *   @OA\Parameter(
     *      name="email",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *)
     **/

    public function addExternalPost(Request $request){


       $totalPostId= TotalPost::create([
           'userId' => $request->user()->id,
           'addressId' => $request->addressId ,
           'typeSerId' => 2 ,
           'printFactor' => $request->printFactor,
           'discountCouponCode' => $request->discountCouponCode,
           'hasNotifRequest' => $request->hasNotifRequest,
           'RequestPrintAvatar' => $request->RequestPrintAvatar,
           'status'=> 'open' ,
        ])->id;

       $externalPostId = ExternalPost::create([
           'totalPostId' => $totalPostId ,

       ])->id;

       ExternalOrder::create([
           'externalPostId' => $externalPostId,
           'shipment' => $request->shipment,
           'Weight' => $request->Weight,
           'width' => $request->width,
           'height' => $request->height,
           'lenght' => $request->lenght,
           'Value' => $request->Value,
           'brand' => $request->brand,
           'Link' => $request->Link,
           'boxnumber' => $request->boxnumber,
           'typeId' => $request->typeId,
           'sizeId' => $request->sizeId,
           'needKarton' => $request->needKarton,
           'insuranceId' => $request->insuranceId,
           'image1' => $request->image1,
           'image2' => $request->image2,
           'image3' => $request->image3,
           'serviceId' => $request->serviceId,
           'isUsed' => $request->isUsed,
           'getterAddressId' => $request->getterAddressId,
           'price' => $request->price,


       ]);
        $result =(object)[
            'status'=>200,
            'data'=>'sucsses',
        ];
        return json_encode($result);
    }

    public function externalPostUpdateItem(Request $request){
        externalOrder::where('externalPostId',$request->id)->Update([
            'shipment' => $request->shipment,
            'Weight' => $request->Weight,
            'width' => $request->width,
            'height' => $request->height,
            'lenght' => $request->lenght,
            'Value' => $request->Value,
            'brand' => $request->brand,
            'Link' => $request->Link,
            'boxnumber' => $request->boxnumber,
            'typeId' => $request->typeId,
            'sizeId' => $request->sizeId,
            'needKarton' => $request->needKarton,
            'insuranceId' => $request->insuranceId,
            'image1' => $request->image1,
            'image2' => $request->image2,
            'image3' => $request->image3,
            'serviceId' => $request->serviceId,
            'isUsed' => $request->isUsed,
            'getterAddressId' => $request->getterAddressId,
            'price' => $request->price,

        ]);
        $result =(object)[
            'status'=>200,
            'data'=>'sucsses',
        ];
        return json_encode($result);

    }
    public function addItemExternalOrder(Request $request){

        externalOrder::create([
            'externalPostId' => $request->externalPostId,
            'shipment' => $request->shipment,
            'Weight' => $request->Weight,
            'width' => $request->width,
            'height' => $request->height,
            'lenght' => $request->lenght,
            'Value' => $request->Value,
            'brand' => $request->brand,
            'Link' => $request->Link,
            'boxnumber' => $request->boxnumber,
            'typeId' => $request->typeId,
            'sizeId' => $request->sizeId,
            'needKarton' => $request->needKarton,
            'insuranceId' => $request->insuranceId,
            'image1' => $request->image1,
            'image2' => $request->image2,
            'image3' => $request->image3,
            'serviceId' => $request->serviceId,
            'isUsed' => $request->isUsed,
            'getterAddressId' => $request->getterAddressId,
            'price' => $request->price,


        ]);
        $result =(object)[
            'status'=>200,
            'data'=>'sucsses',
        ];
        return json_encode($result);

    }

    public function deleteExternalOrder($id){
        ExternalOrder::where('id',$id)->delete();
        $result =(object)[
            'status'=>200,
            'data'=>'sucsses',
        ];
        return json_encode($result);

    }
}
