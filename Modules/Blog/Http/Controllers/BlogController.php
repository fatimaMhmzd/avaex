<?php

namespace Modules\Blog\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Blog\Entities\Blog;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('blog::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('blog::create');
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
        return view('blog::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('blog::edit');
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
     *     path="/api/blog/v1/getBlog",
     *     tags={"blog"},
     *     summary="لیست بلاگ",
     *     description="توضیحات",
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *     ),
     * )
     */

    public function getBlog()
    {
        $Blog = Blog::all();

        $result = (object)[
            'status' => 200,
            'data' => $Blog,
        ];

        return json_encode($result);
    }


    /**
     * @OA\Post(
     *   path="/api/blog/v1/StoreBlog",
     *   tags={"blog"},
     *   summary="افزودن بلاگ",
     *       description="توضیحات",
     *   @OA\Parameter(
     *      name="title",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="shortDescription",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="longDescription",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="groupId",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="integer"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="image",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="seoDescription",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="seoKeyboard",
     *      in="query",
     *      required=false,
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
    public function StoreBlog(Request $request)
    {
        $imageName = rand(11111, 99999) . time() . '.' . request()->image->getClientOriginalExtension();
        request()->image->move(public_path('blogImage'), $imageName);
        $imageAddres = url('/') . '/' . 'blogImage' . '/' . $imageName;

        Blog::create([
            'title' => $request->title,
            'groupId' => $request->groupId,
            'shortDescription' => $request->shortDescription,
            'longDescription' => $request->longDescription,
            'seoDescription' => $request->seoDescription,
            'seoKeyboard' => $request->seoKeyboard,
            'image' => $imageAddres,
        ]);

        $result = (object)[
            'status' => 200,
            'data' => 'بلاگ با موفقیت ایجاد شد',
        ];

        return json_encode($result);
    }


    /**
     * @OA\Get(
     *     path="/api/blog/v1/UpdateBlog/{id}",
     *     tags={"blog"},
     *     summary="ویرایش بلاگ",
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

    public function UpdateBlog($id)
    {
        $UpdateBlog = Blog::find($id);

        $result = (object)[
            'status' => 200,
            'data' => $UpdateBlog,
        ];
        return json_encode($result);
    }


    /**
     * @OA\Post(
     *   path="/api/blog/v1/StoreUpdateBlog",
     *   tags={"blog"},
     *   summary="ویرایش بلاگ",
     *       description="توضیحات",
     *   @OA\Parameter(
     *      name="title",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="shortDescription",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="longDescription",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="groupId",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="integer"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="image",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="seoDescription",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="seoKeyboard",
     *      in="query",
     *      required=false,
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

    public function StoreUpdateBlog(Request $request)
    {


        if ($request->image != null) {
            $imageName = rand(11111, 99999) . time() . '.' . request()->image->getClientOriginalExtension();
            request()->image->move(public_path('blogImage'), $imageName);
            $imageAddres = url('/') . '/' . 'blogImage' . '/' . $imageName;
        }
        Blog::where('id', $request->id)->update([
            'title' => $request->title,
            'groupId' => $request->groupId,
            'shortDescription' => $request->shortDescription,
            'longDescription' => $request->longDescription,
            'seoDescription' => $request->seoDescription,
            'seoKeyboard' => $request->seoKeyboard,
            'image' => $imageAddres,
        ]);

        $result = (object)[
            'status' => 200,
            'data' => 'بلاگ شما با موفقیت ویرایش شد',
        ];
        return json_encode($result);
    }


    /**
     * @OA\Get(
     *     path="/api/blog/v1/removeBlog/{id}",
     *     tags={"blog"},
     *     summary="حذف بلاگ",
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

    public function removeBlog($id)
    {

        Blog::where('id', $id)->delete();

        $result = (object)[
            'status' => 200,
            'data' => 'بلاگ شما با موفقیت حذف شد',
        ];
        return json_encode($result);

    }


}
