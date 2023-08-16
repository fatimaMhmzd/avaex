<?php

namespace Modules\DiscountCode\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\DiscountCode\Entities\DiscountCode;

class DiscountCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('discountcode::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('discountcode::create');
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
        return view('discountcode::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('discountcode::edit');
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
     * @OA\Get(
     *     path="/api/discountCode/v1/getDiscountCode",
     *     tags={"DiscountCode"},
     *     summary="لیست تخفیف ها",
     *     description="توضیحات",
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *     ),
     * )
     */

    public function getDiscountCode()
    {
        $DiscountCode = DiscountCode::all();

        $result = (object)[
            'status' => 200,
            'data' => $DiscountCode,
        ];

        return json_encode($result);
    }


    /**
     * @OA\Post(
     *   path="/api/discountCode/v1/StoreDiscountCode",
     *   tags={"DiscountCode"},
     *   summary="افزودن تخفیف",
     *       description="توضیحات",
     *   @OA\Parameter(
     *      name="code",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="price",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="integer"
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
    public function StoreDiscountCode(Request $request)
    {
        $userId = $request->user()->id;
        DiscountCode::create([
            'userId' => $userId,
            'code' => $request->code,
            'price' => $request->price,
        ]);
    }


    /**
     * @OA\Get(
     *     path="/api/discountCode/v1/UpdateDiscountCode/{id}",
     *     tags={"DiscountCode"},
     *     summary="ویرایش تخفیف",
     *     description="توضیحات",
     *   @OA\Parameter(
     *      name="id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *     ),
     * )
     */

    public function UpdateDiscountCode($id)
    {
        $UpdateDiscountCode = DiscountCode::find($id);
        $userId = User::find($UpdateDiscountCode->userId);


        $result = (object)[
            'DiscountCode' => $UpdateDiscountCode,
            'userId' => $userId,
        ];

        $results = (object)[
            'status' => 200,
            'data' => $result,
        ];
        return json_encode($results);
    }


    /**
     * @OA\Post(
     *   path="/api/discountCode/v1/StoreUpdateDiscountCode",
     *   tags={"DiscountCode"},
     *   summary="ویرایش تخفیف",
     *       description="توضیحات",
     *   @OA\Parameter(
     *      name="code",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="price",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="integer"
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

    public function StoreUpdateDiscountCode(Request $request)
    {
        $userId = $request->user()->id;
        DiscountCode::where('id', $request->id)->update([
            'userId' => $userId,
            'code' => $request->code,
            'price' => $request->price,
        ]);

        $result = (object)[
            'status' => 200,
            'data' => 'تخفیف شما با موفقیت ویرایش شد',
        ];
        return json_encode($result);
    }


    /**
     * @OA\Get(
     *     path="/api/discountCode/v1/removeDiscountCode/{id}",
     *     tags={"DiscountCode"},
     *     summary="حذف تخفیف",
     *     description="توضیحات",
     *   @OA\Parameter(
     *      name="id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *     ),
     * )
     */

    public function removeDiscountCode($id)
    {

        DiscountCode::where('id', $id)->delete();

        $result = (object)[
            'status' => 200,
            'data' => 'تخفیف شما با موفقیت حذف شد',
        ];
        return json_encode($result);

    }
}
