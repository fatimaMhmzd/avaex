<?php

namespace Modules\HeavyPost\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\HeavyPost\Entities\HeavyOrder;
use Modules\HeavyPost\Entities\HeavyPost;
use Modules\HeavyPost\Entities\Vehicle;
use Modules\HeavyPost\Entities\VehicleOption;
use Modules\TotalPost\Entities\TotalPost;

class HeavyPostController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('heavypost::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('heavypost::create');
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
        return view('heavypost::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('heavypost::edit');
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
     *   path="/api/Heavypost/v1/addVehicles",
     *   tags={"heavyCar"},
     *   summary=" اضافه کردن خودرو",
     *       description="توضیحات",
     *   @OA\Parameter(
     *      name="vehicleName",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
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



    public function addVehicles(Request $request){
        Vehicle::create([
           'name' => $request->vehicleName
        ]);
        $result =(object)[
            'status'=>200,
            'data'=>'sucsses',
        ];
        return json_encode($result);

    }
    /**
     * @OA\Post(
     *   path="/api/Heavypost/v1/updateVehicles",
     *   tags={"heavyCar"},
     *   summary="آپدیت نوع خودرو",
     *       description="توضیحات",
     *   @OA\Parameter(
     *      name="id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="vehicleName",
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
    public function updateVehicles(Request $request){
        Vehicle::where('id' , $request->id)->update([
            'name' => $request->vehicleName
        ]);
        $result =(object)[
            'status'=>200,
            'data'=>'sucsses',
        ];
        return json_encode($result);
    }

    /**
     * @OA\Get(
     *     path="/api/Heavypost/v1/deleteVehicles/{id}",
     *     tags={"heavyCar"},
     *     summary="تست گت",
     *     description="توضیحات",
     *     @OA\Parameter(
     *    description="ID of Vehicles",
     *    in="path",
     *    name="id",
     *    required=true,
     *    example="1",
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *     ),
     *
     * )
     */
    public function deleteVehicles($id){
        Vehicle::where('id',$id)->delete();
        $result =(object)[
            'status'=>200,
            'data'=>'sucsses',
        ];
        return json_encode($result);
    }

    public function addOptionVehicles(Request $request){
        VehicleOption::create([
            'property' => $request->property,
            'vehicleId' => $request->vehicleId,
        ]);
        $result =(object)[
            'status'=>200,
            'data'=>'sucsses',
        ];
        return json_encode($result);

    }
    public function updateOptionVehicles(Request $request){
        VehicleOption::where('id' , $request->id)->update([
            'property' => $request->property,
            'vehicleId' => $request->vehicleId,
        ]);
        $result =(object)[
            'status'=>200,
            'data'=>'sucsses',
        ];
        return json_encode($result);
    }
    public function deleteOptionVehicles($id){
        VehicleOption::where('id' , $id)->delete();
        $result =(object)[
            'status'=>200,
            'data'=>'sucsses',
        ];
        return json_encode($result);
    }


    public function addHeavyPost(Request $request){


        $totalPostId= TotalPost::create([
            'userId' => $request->user()->id,
            'addressId' => $request->addressId ,
            'typeSerId' => 4 ,
            'printFactor' => $request->printFactor,
            'discountCouponCode' => $request->discountCouponCode,
            'hasNotifRequest' => $request->hasNotifRequest,
            'RequestPrintAvatar' => $request->RequestPrintAvatar,
            'status'=> 'open' ,
        ])->id;

        $heavyPostIdId = HeavyPost::create([
            'totalPostId' => $totalPostId ,

        ])->id;

        HeavyOrder::create([
            'heavyPostId' => $heavyPostIdId,
            'shipment' => $request->shipment,
            'Weight' => $request->Weight,
            'width' => $request->width,
            'height' => $request->height,
            'lenght' => $request->lenght,
            'Value' => $request->Value,
            'count' => $request->count,
            'vehicle' => $request->vehicle,
            'packagingId' => $request->packagingId,
            'dispatch_date' => $request->dispatch_date,
            'getterAddressId' => $request->getterAddressId,
            'serviceId' => $request->serviceId,
            'price' => $request->price,


        ]);
        $result =(object)[
            'status'=>200,
            'data'=>'sucsses',
        ];
        return json_encode($result);
    }

    public function HeavyPostUpdateItem(Request $request){
        HeavyOrder::where('heavyPostId',$request->heavyPostId)->Update([
            'shipment' => $request->shipment,
            'Weight' => $request->Weight,
            'width' => $request->width,
            'height' => $request->height,
            'lenght' => $request->lenght,
            'Value' => $request->Value,
            'count' => $request->count,
            'vehicle' => $request->vehicle,
            'packagingId' => $request->packagingId,
            'dispatch_date' => $request->dispatch_date,
            'getterAddressId' => $request->getterAddressId,
            'serviceId' => $request->serviceId,
            'price' => $request->price,
        ]);
        $result =(object)[
            'status'=>200,
            'data'=>'sucsses',
        ];
        return json_encode($result);

    }
    public function addItemHeavyOrder(Request $request){

        HeavyOrder::create([
            'heavyPostId' => $request->heavyPostId,
            'shipment' => $request->shipment,
            'Weight' => $request->Weight,
            'width' => $request->width,
            'height' => $request->height,
            'lenght' => $request->lenght,
            'Value' => $request->Value,
            'count' => $request->count,
            'vehicle' => $request->vehicle,
            'packagingId' => $request->packagingId,
            'dispatch_date' => $request->dispatch_date,
            'getterAddressId' => $request->getterAddressId,
            'serviceId' => $request->serviceId,
            'price' => $request->price,


        ]);
        $result =(object)[
            'status'=>200,
            'data'=>'sucsses',
        ];
        return json_encode($result);

    }

    public function deleteHeavyOrder($id){
        HeavyOrder::where('id',$id)->delete();
        $result =(object)[
            'status'=>200,
            'data'=>'sucsses',
        ];
        return json_encode($result);

    }



}
