<?php

namespace Modules\Setting\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Agent\Entities\Agent;
use Modules\Setting\Entities\Complain;
use Modules\Setting\Entities\ContactUs;
use Modules\Setting\Entities\Setting;
use Modules\Setting\Entities\Ticket;
use Modules\TotalPost\Entities\TotalPost;

class SettingController extends Controller
{

    public function update(Request $request){
        if( $request->id == 1){
            $value = uploadImage($request->image);
        }
        else {
            $value = $request->value ;
        }

        Setting::where('id', $request->id)->Update([
            'value' => $value,

        ]);
        $result = (object)[
            'data' => 'با موفقیت انجام شد .'
        ];
        return Response::json($result, 200);


    }


    public function all(){
        $result = (object)[
            'data' => Setting::all()
        ];
        return Response::json($result, 200);

    }

    public function start()
    {
        $hasData = false;

        $ticketMali = Ticket::where('status','پاسخ داده نشده')->where('Handlingunit','مالی و حسابداری')->count();
        $ticketSocial = Ticket::where('status','پاسخ داده نشده')->where('Handlingunit','روابط عمومی')->count();
        $ticketPoshtibani = Ticket::where('status','پاسخ داده نشده')->where('Handlingunit','پشتیبانی')->count();
        $ticketAgent = Ticket::where('status','پاسخ داده نشده')->where('Handlingunit','امور نمایندگان')->count();
        $ticketManager = Ticket::where('status','پاسخ داده نشده')->where('Handlingunit','مدیریت')->count();
        $ticketAnfo = Ticket::where('status','پاسخ داده نشده')->where('Handlingunit','انفورماتیک')->count();
        $ticket = Ticket::where('status','پاسخ داده نشده')->count();
        $complain = Complain::where('status','پاسخ داده نشده')->count();
        $contactUs = ContactUs::where('status','پاسخ داده نشده')->count();
        $agent = Agent::where('status','عدم تایید')->count();

        if ($ticket > 0 or $complain or $contactUs){
            $hasData = true;
        }

        $data = (object)[
            'ticket'=>$ticket,
            'ticketMali'=>$ticketMali,
            'ticketSocial'=>$ticketSocial,
            'ticketPoshtibani'=>$ticketPoshtibani,
            'ticketAgent'=>$ticketAgent,
            'ticketManager'=>$ticketManager,
            'ticketAnfo'=>$ticketAnfo,
            'complain'=>$complain,
            'contactUs'=>$contactUs,
            'hasData'=>$hasData,
            'agent'=>$agent,
        ];
        $result = (object)[
            'data' => $data
        ];
        return Response::json($result, 200);

    }

    public function test()
    {
        $agents = Agent::all();
        foreach ($agents as $agent){
            $marsolle = TotalPost::where('agentId', $agent->id)->where('factorstatus', 'close')->where('status', 'جمع آوری نشده')->count();
            if ($marsolle > 0){
                sendRemember($agent->cityId);
            }
        }
    }

}
