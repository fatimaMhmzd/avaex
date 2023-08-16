<?php

namespace Modules\PostupServices\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Modules\PostupServices\Entities\Insurance;
use Modules\PostupServices\Entities\Packaging;
use Modules\PostupServices\Entities\Size;
use Yajra\DataTables\DataTables;

class PostupServicesController extends Controller
{


    /********************** Size ****************************/

    public function createSize()
    {
        return view('postupServices.Resources.views.Size.create');
    }

    public function storeSize(Request $request)
    {
        if ($request->size != null) {
            Size::create([
                'size' => $request->size
            ]);
        }
        Redirect::route('postupServices.Resources.views.Size.create')->with('error', true)->with('message', 'سایز مشخص نشده است.');
    }

    public function listSize(){
        return view('postupServices.Resources.views.Size.list');
    }

    public function ajaxSize()
    {
        $data = Size::all();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '
                <a href="' . route('updateSize', $row->id) . '"class="success"><i class="fa fa-pencil"></i></a>
               <a href="' . route('deleteSize', $row->id) . '"class="danger"><i class="fa fa-trash"></i></a>';
                return $btn;
            })
            ->rawColumn(['action'])
            ->make(true);
    }

    public function deleteSize($id)
    {
        Size::find($id)->delete();
        return Redirect::route('listSize');
    }

    public function updateSize($id)
    {
        $size = Size::find($id)->get();
        return view('postupServices.Resources.views.Size.update', compact('size'));
    }

    public function storeUpdateSize(Request $request)
    {
        if ($request->size != null) {
            Size::where('id', $request->id)->update([
                'size' => $request->size
            ]);
            return back()->with('success', true)->with('message', 'سایز با موفقیت ویرایش شد');
        }
        return back()->with('error', true)->with('message', 'سایز مشخص نشده است');
    }

    /********************** Packaging *******************************/


    public function storePackaging(Request $request)
    {

        $imageAddress = "";
        if ($request->image != null) {
            $imageName = rand(11111, 99999) . time() . '.' . request()->image->getClientOriginalExtension();
            request()->image->move(public_path('imagePackaging'), $imageName);
            $imageAddress = url('/') . '/' . 'imagePackaging' . '/' . $imageName;
        }
        $name = Packaging::where('title', $request->title)->get();
        if ($name->isEmpty()){
            Packaging::create([
                'title' => $request->title,
                'image' => $imageAddress
            ]);
            return back()->with('success', true)->with('message', 'بسته بندی جدید با موفقیت ثبت شد.');
        }
        return back()->with('error', true)->with('message', 'نام بسته بندی تکراری است.');
    }

    public function listPackaging()
    {
        $result = (object)[
            'data'=> Packaging::all(),
        ];
        return Response::json($result, 200);
    }



    public function deletePackaging($id)
    {
        Packaging::find($id)->delete();
        return Redirect::route('postupServices.Resources.views.Packaging.list');
    }

    public function updatePackaging($id)
    {
        $packaging = Packaging::find($id)->get();
        return view('postupServices.Resources.views.Packaging.update', compact('packaging'));
    }

    public function storeUpdatePackaging(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
        ], [
            'title.required' => 'عنوان الزامی است.',
        ]);
        $imageAddress = Packaging::find($request->id)->image;
        if ($request->image != null) {
            if ($imageAddress != null) {
                $url = url('/') . '/';

                $imagePath = str_replace($url, '', $imageAddress);
                if (File::exists($imagePath)) {
                    File::delete($imagePath);
                }
                $imageName = rand(11111, 99999) . time() . '.' . request()->image->getClientOriginalExtension();
                request()->image->move(public_path('imagePackaging'), $imageName);
                $imageAddress = url('/') . '/' . 'imagePackaging' . '/' . $imageName;
            }
        }
        Packaging::where('id' , $request->id)->update([
            'title' => $request->title,
            'image' => $imageAddress
        ]);
        return back()->with('success', true)->with('message', 'بسته بندی با موفقیت ویرایش شد');
    }

    /************************ Price ***********************/

    public function createPrice(){
        $packaging = Packaging::all();
        $size = Size::all();
        return view('postupServices.Resources.views.Price.create', compact('packaging', 'size'));
    }

    public function storePrice(Request $request){}

    public function listPrice(){}

    public function ajaxPrice(){}

    public function deletePrice($id){}

    public function updatePrice($id){}

    public function storeUpdatePrice(Request $request){}

    /********************** Insurance ***************************/

    public function createInsurance(){
        return view('postupServices.Resources.views.Insurance.create');
    }

    public function storeInsurance(Request $request){
        if ($request->amount != null){
            Insurance::create([
                'amount' => $request->amount
            ]);
            return back()->with('success', true)->with('message', 'مقدار بیمه با موفقیت افزوده شد');
        }
        return back()->with('error', true)->with('message', 'مقدار بیمه مشخص نشده است');
    }

    public function listInsurance(){
        return view('postupServices.Resources.views.Insurance.list');
    }

    public function ajaxInsurance(){
        $data = Insurance::all();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '
                <a href="' . route('updateInsurance', $row->id) . '"class="success"><i class="fa fa-pencil"></i></a>
               <a href="' . route('deleteInsurance', $row->id) . '"class="danger"><i class="fa fa-trash"></i></a>';
                return $btn;
            })
            ->rawColumn(['action'])
            ->make(true);
    }

    public function deleteInsurance($id){
        Insurance::find($id)->delete();
        return Redirect::route('listInsurance');
    }

    public function updateInsurance($id){
        $insurance = Insurance::find($id)->get();
        return view('postupServices.Resources.views.Insurance.update', compact('insurance'));
    }

    public function storeUpdateInsurance(Request $request){
        $this->validate($request, [
            'amount' => 'required',
        ], [
            'amount.required' => 'مقدار بیمه الزامی است.',
        ]);
        Insurance::where('id' , $request->id)->update([
            'amount' => 'amount'
        ]);
        return back()->with('success', true)->with('message', 'مقدار بیمه با موفقیت ویرایش شد');
    }
}

