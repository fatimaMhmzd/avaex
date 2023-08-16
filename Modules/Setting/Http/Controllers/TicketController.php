<?php

namespace Modules\Setting\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Modules\Setting\Entities\Ticket;

class TicketController extends Controller
{

    public function add(Request $request)
    {

        if (Auth::check()) {

            Ticket::create([

                'userId' => $request->user()->id,
                'name' => $request->name,
                'family' => $request->family,
                'mobile' => $request->mobile,
                'numberParcel' => $request->numberParcel,
                'partnumber' => $request->partnumber,
                'typeTicket' => $request->typeTicket,
                'subject' => $request->subject,
                'Handlingunit' => $request->Handlingunit,
                'importance' => $request->importance,
                'message' => $request->message,
                'status' => 'پاسخ داده نشده',

            ]);
            $response = (object)[
                'data' => 'با موفقیت انجام شد .'
            ];
            return Response::json($response, 200);
        }else{
            $response = (object)[
                'data' => 'برای ثبت تیکت وارد حساب کاربری خود شوید'
            ];
            return Response::json($response, 401);
        }
    }

    public function update(Request $request)
    {
        Ticket::where('id', $request->id)->Update([
            'answer' => $request->answer,
            'status' => $request->status,
        ]);
        $response = (object)[
            'data' => 'با موفقیت انجام شد .'
        ];
        return Response::json($response, 200);
    }

    public function delete($id)
    {

        Ticket::where('id', $id)->delete();
        $response = (object)[
            'data' => 'با موفقیت انجام شد .'
        ];
        return Response::json($response, 200);


    }

    public function show()
    {

        $response = (object)[
            'data' => Ticket::all()
        ];
        return Response::json($response, 200);


    }

    public function detail($id)
    {

        $response = (object)[
            'data' => Ticket::find($id)
        ];
        return Response::json($response, 200);


    }

    public function all(Request $request)
    {
        $all = [];
        $ticket = Ticket::where('userId', $request->user()->id)
            ->skip(($request->numberpage - 1) * $request->numberitems)
            ->take($request->numberitems)->orderBy('id', 'DESC')->get();

        $notSeen = Ticket::where('userId', $request->user()->id)->where("status", "خوانده نشده")->get()->count();

        $numbers = count(
                Ticket::where('userId', $request->user()->id)->get()) / $request->numberitems;

        foreach ($ticket as $item) {
            $res = (object)[
                'item' => $item,
                'date' => dateTimeToDate($item->created_at),
                'time' => dateTimeToTime($item->created_at),
            ];
            array_push($all, $res);
        }

        $res = (object)[
            'all' => $all,
            'number' => ceil($numbers),
            'counUnread' => $notSeen
        ];

        $response = (object)[
            'data' => $res,
        ];
        return Response::json($response, 200);
    }

    public function changeHandlingunit(Request $request)
    {
        Ticket::where('id', $request->id)->Update([
            'Handlingunit' => $request->Handlingunit,
            'status' => $request->status,
        ]);
        $response = (object)[
            'data' => 'با موفقیت انجام شد .'
        ];
        return Response::json($response, 200);
    }
    public function allDashboard(Request $request)
    {
        $all = [];
        $query = Ticket::where('id','>',0);
        if ($request->Handlingunit){
            $query = $query->where('Handlingunit' ,$request->Handlingunit) ;
        }
        if ($request->status){
            $query = $query->where('status' ,$request->status) ;
        }
        $ticket = $query->skip(($request->numberpage - 1) * $request->numberitems)
            ->take($request->numberitems)->orderBy('id', 'DESC')->get();

        /*$notSeen = $query->where("status", "خوانده نشده")->get()->count();*/

        $numbers = count(
                $query->get()) / $request->numberitems;

        foreach ($ticket as $item) {
            $res = (object)[
                'item' => $item,
                'date' => dateTimeToDate($item->created_at),
                'time' => dateTimeToTime($item->created_at),
            ];
            array_push($all, $res);
        }

        $res = (object)[
            'all' => $all,
            'number' => ceil($numbers),
          /*  'counUnread' => $notSeen*/
        ];

        $response = (object)[
            'data' => $res,
        ];
        return Response::json($response, 200);
    }

}
