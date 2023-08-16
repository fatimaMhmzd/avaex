<?php

namespace App\Console;

use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Modules\Agent\Entities\Agent;
use Modules\Discount\Entities\Discount;
use Modules\InternalPost\Entities\InternalOrder;
use Modules\TotalPost\Entities\TotalPost;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        $schedule->call(function () {
            $agents = Agent::all();
            foreach ($agents as $agent){
                $marsolle = TotalPost::where('agentId', $agent->id)->where('factorstatus', 'close')->where('status', 'جمع آوری نشده')->count();
                if ($marsolle > 0){
                    sendRemember($agent->cityId);
                }
            }

            $allIds = TotalPost::where('status', 'در حال جابه جایی')->whereBetween('created_at', [Carbon::now()->subDay(), Carbon::now()->subDay(12)])->get();

            foreach ($allIds as $totalPost) {

                if ($totalPost->serviceUuid and $totalPost->componyId == 2) {

                    $cURLConnection3 = curl_init();
                    $mahexToken = env('MAHEX_TOKEN');

                    $urlll3 = "http://api.mahex.com/v2/shipments/$totalPost->serviceUuid";

                    curl_setopt($cURLConnection3, CURLOPT_URL, $urlll3);
                    curl_setopt($cURLConnection3, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($cURLConnection3, CURLOPT_HTTPHEADER, array(
                        "Authorization: Basic $mahexToken",
                        'Content-Type: application/json'
                    ));
                    $phoneList = curl_exec($cURLConnection3);
                    curl_close($cURLConnection3);
                    $response = json_decode($phoneList);
                    if ($response->status->code == 200) {
                        foreach ($response->data->parcels as $parcel) {
                            if ($parcel->state == "DELIVERED") {
                                TotalPost::where('id', $totalPost->id)->update([
                                    'status' => "تحویل داده شد"
                                ]);
                                InternalOrder::where('internalPostId', $totalPost->id)->update([
                                    'status' => "تحویل داده شد"
                                ]);
                            } else if ($parcel->state == "RETURNED") {
                                TotalPost::where('id', $totalPost->id)->update([
                                    'status' => "برگشتی"
                                ]);
                                InternalOrder::where('internalPostId', $totalPost->id)->update([
                                    'status' => "برگشتی"
                                ]);
                            }
                        }


                    }

                }
            }
        })->hourly();



        // $schedule->command('inspire')->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
