<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Address\Entities\Address;
use Modules\Address\Entities\City;
use Modules\Address\Entities\Province;
use Modules\Agent\Entities\Agent;
use Modules\Compony\Entities\Compony;
use Modules\Compony\Entities\ComponyService;
use Modules\Compony\Entities\ComponyTypePost;
use Modules\Compony\Entities\Service;
use Modules\ExternalPost\Entities\ExternalOrder;
use Modules\ExternalPost\Entities\ExternalPost;
use Modules\HeavyPost\Entities\HeavyOrder;
use Modules\HeavyPost\Entities\HeavyPost;
use Modules\InternalPost\Entities\InternalOrder;
use Modules\InternalPost\Entities\InternalPost;
use Modules\InUrbanePost\Entities\PeykOrder;
use Modules\InUrbanePost\Entities\PeykPost;
use Modules\Setting\Entities\Setting;
use Modules\TotalPost\Entities\TotalPost;
use Modules\User\Entities\User;
use Modules\Wallet\Entities\Wallet;
use App\Exports\MyPartExport;



use PDF;


class NewprintController extends Controller
{
    // public function print1($id = 102468){

    //     $factors = explode(",", $id);
    //     $datas = [];

    //     //$totalPosts = TotalPost::find(2517);

    //     foreach ($factors as $item) {
    //         $totalPosts = TotalPost::find(2517);
    //         if ($totalPosts->typeSerId == 1) {
    //             $post = InternalPost::where('totalPostId', $totalPosts->id)->first();
    //             $parts = InternalOrder::where('internalPostId', $post->id)->get();

    //         } elseif ($totalPosts->typeSerId == 2) {
    //             $post = ExternalPost::where('totalPostId', $totalPosts->id)->first();
    //             $parts = ExternalOrder::where('externalPostId', $post->id)->get();


    //         } elseif ($totalPosts->typeSerId == 3) {
    //             $post = PeykPost::where('totalPostId', $totalPosts->id)->first();
    //             $parts = PeykOrder::where('externalPostId', $post->id)->get();


    //         } else {
    //             $post = HeavyPost::where('totalPostId', $totalPosts->id)->first();
    //             $parts = HeavyOrder::where('externalPostId', $post->id)->get();

    //         }

    //         $sender = Address::withTrashed()->find($totalPosts->addressId);
    //         if ($sender) {
    //             $senderCity = City::find($sender->cityId)->faName;
    //             $senderProvice = Province::find($sender->provinceId)->faName;
    //         } else {
    //             $senderCity = "0";
    //             $senderProvice = "0";
    //         }


    //         $address = (object)[
    //             'sender' => $sender,
    //             'city' => $senderCity,
    //             'provice' => $senderProvice,
    //         ];


    //         $getter = Address::find($totalPosts->getterAddressId);
    //         if ($getter) {
    //             $getterCity = City::find($getter->cityId)->faName;
    //             $getterProvice = Province::find($getter->provinceId)->faName;

    //         } else {
    //             $getterCity = "0";
    //             $getterProvice = "0";
    //         }

    //         $geteradress = (object)[
    //             'getter' => $getter,
    //             'city' => $getterCity,
    //             'provice' => $getterProvice,
    //         ];
    //         $componyservice = ComponyService::find($totalPosts->componyServicesId);
    //         $compony = Compony::find($componyservice->componyId)->name;
    //         $service = Service::find($componyservice->serviceId)->name;
    //         $componytype = ComponyTypePost::find($componyservice->componyTypeId)->name;
    //         $componyservice = (object)[
    //             'compony' => $compony,
    //             'image' => Compony::find($componyservice->componyId)->image,
    //             'service' => $service,
    //             'componytype' => $componytype,
    //             'componyservice' => $componyservice,
    //         ];

    //         foreach ($parts as $part) {
    //             if ($totalPosts->byAgent == 1) {
    //                 $PackagingPrice = $part->packaging;
    //             } else {
    //                 //$PackagingPrice = round($totalPosts->Packaging / $totalPosts->totalNumber);
    //                 $PackagingPrice = $part->packaging;
    //             }

    //             $Insurance = ($part->value) * (0.002);

    //             /*                    if (dataxa.item.byAgent == 1) {
    //                                     PackagingPrice = Math.round(data.packaging);
    //                                 } else {
    //                                     PackagingPrice = Math.round(dataxa.item['Packaging'] / dataxa.item.totalNumber);
    //                                 }*/

    //             $eachx = round(($totalPosts->Payable - 1.09 * ($totalPosts->Insurance)) / $totalPosts->totalNumber);
    //             $eachCOD = round($totalPosts->amountCOD / $totalPosts->totalNumber);

    //             $serviceInPlaceTaxItem = 0;
    //             if ($totalPosts->serviceInPlace and $totalPosts->serviceInPlace != 0) {
    //                 $serviceInPlaceTaxItem = round(($totalPosts->serviceInPlace * 0.09) / $totalPosts->totalNumber);
    //             }

    //             $eachxPart = round($eachx + $part->cost + ($PackagingPrice) * 1.09 + ($Insurance * 0.09));

    //             $eachxaa = $eachxPart + $serviceInPlaceTaxItem;

    //             $realPay = round($totalPosts->realPayable / $totalPosts->totalNumber);

    //             /*if(count($parts) ==1 and $totalPosts->realPayable){
    //                 $eachxaa = $totalPosts->realPayable;
    //             }*/
    //             $partDitail = (object)[
    //                 'partDitail' => $part,
    //                 'item' => $totalPosts,
    //                 'date' => dateTimeToDate($totalPosts->updated_at),
    //                 'time' => dateTimeToTime($totalPosts->updated_at),
    //                 'componyservice' => $componyservice,
    //                 'post' => $post,
    //                 'address' => $address,
    //                 'geteradress' => $geteradress,
    //                 /*                        'eachx' => round((($totalPosts->Payable - $totalPosts->Insurance) / $totalPosts->totalNumber) + $PackagingPrice * 1.09) + $part->cost,*/
    //                 'eachx' => $realPay,
    //                 'eachCOD' => round($part->amountCOD),
    //             ];

    //             array_push($datas, $partDitail);

    //         }
    //         if (count($parts) != 0) {
    //         }
    //     }


    //     $data = [
    //         'data' => $datas
    //     ];

    //     $pdf = PDF::loadView('Print.print1', $data, [], [
    //         'format' => 'A4-P'
    //       ]);

    //     return $pdf->stream('document.pdf');


    // }

    public function print1(){
        $datas= [];

        $item = 2654;
        $totalPosts = TotalPost::find($item);
            if ($totalPosts->typeSerId == 1) {
                $post = InternalPost::where('totalPostId', $totalPosts->id)->first();
                $parts = InternalOrder::where('internalPostId', $post->id)->get();

            } elseif ($totalPosts->typeSerId == 2) {
                $post = ExternalPost::where('totalPostId', $totalPosts->id)->first();
                $parts = ExternalOrder::where('externalPostId', $post->id)->get();


            } elseif ($totalPosts->typeSerId == 3) {
                $post = PeykPost::where('totalPostId', $totalPosts->id)->first();
                $parts = PeykOrder::where('externalPostId', $post->id)->get();


            } else {
                $post = HeavyPost::where('totalPostId', $totalPosts->id)->first();
                $parts = HeavyOrder::where('externalPostId', $post->id)->get();

            }

            $sender = Address::withTrashed()->find($totalPosts->addressId);
            if ($sender) {
                $senderCity = City::find($sender->cityId)->faName;
                $senderProvice = Province::find($sender->provinceId)->faName;
            } else {
                $senderCity = "0";
                $senderProvice = "0";
            }


            $address = (object)[
                'sender' => $sender,
                'city' => $senderCity,
                'provice' => $senderProvice,
            ];


            $getter = Address::find($totalPosts->getterAddressId);
            if ($getter) {
                $getterCity = City::find($getter->cityId)->faName;
                $getterProvice = Province::find($getter->provinceId)->faName;

            } else {
                $getterCity = "0";
                $getterProvice = "0";
            }

            $geteradress = (object)[
                'getter' => $getter,
                'city' => $getterCity,
                'provice' => $getterProvice,
            ];
            $componyservice = ComponyService::find($totalPosts->componyServicesId);
            $compony = Compony::find($componyservice->componyId)->name;
            $service = Service::find($componyservice->serviceId)->name;
            $componytype = ComponyTypePost::find($componyservice->componyTypeId)->name;
            $componyservice = (object)[
                'compony' => $compony,
                'image' => Compony::find($componyservice->componyId)->image,
                'service' => $service,
                'componytype' => $componytype,
                'componyservice' => $componyservice,
            ];

            foreach ($parts as $part) {
                if ($totalPosts->byAgent == 1) {
                    $PackagingPrice = $part->packaging;
                } else {
                    //$PackagingPrice = round($totalPosts->Packaging / $totalPosts->totalNumber);
                    $PackagingPrice = $part->packaging;
                }

                $Insurance = ($part->value) * (0.002);

                /*                    if (dataxa.item.byAgent == 1) {
                                        PackagingPrice = Math.round(data.packaging);
                                    } else {
                                        PackagingPrice = Math.round(dataxa.item['Packaging'] / dataxa.item.totalNumber);
                                    }*/

                $eachx = round(($totalPosts->Payable - 1.09 * ($totalPosts->Insurance)) / $totalPosts->totalNumber);
                $eachCOD = round($totalPosts->amountCOD / $totalPosts->totalNumber);

                $serviceInPlaceTaxItem = 0;
                if ($totalPosts->serviceInPlace and $totalPosts->serviceInPlace != 0) {
                    $serviceInPlaceTaxItem = round(($totalPosts->serviceInPlace * 0.09) / $totalPosts->totalNumber);
                }

                $eachxPart = round($eachx + $part->cost + ($PackagingPrice) * 1.09 + ($Insurance * 0.09));

                $eachxaa = $eachxPart + $serviceInPlaceTaxItem;

                $realPay = round($totalPosts->realPayable / $totalPosts->totalNumber);

                /*if(count($parts) ==1 and $totalPosts->realPayable){
                    $eachxaa = $totalPosts->realPayable;
                }*/
                $partDitail = (object)[
                    'partDitail' => $part,
                    'item' => $totalPosts,
                    'date' => dateTimeToDate($totalPosts->updated_at),
                    'time' => dateTimeToTime($totalPosts->updated_at),
                    'componyservice' => $componyservice,
                    'post' => $post,
                    'address' => $address,
                    'geteradress' => $geteradress,
                    /*                        'eachx' => round((($totalPosts->Payable - $totalPosts->Insurance) / $totalPosts->totalNumber) + $PackagingPrice * 1.09) + $part->cost,*/
                    'eachx' => $realPay,
                    'eachCOD' => round($part->amountCOD),
                ];

                array_push($datas, $partDitail);

            }
            if (count($parts) != 0) {
            }


            
            // return $datas;

            $data = [
                'data' => $datas
            ];

            

            $pdf = PDF::loadView('Print.print1', $data, [], [
                'format' => 'A4-P'
            ]);

            return $pdf->stream('document.pdf');
    
            $totalPost1 = TotalPost::find($item);
            if ($totalPost1->componyId == 1){
                $pdf = PDF::loadView('Print.print1', $data, [], [
                    'format' => 'A4-P'
                ]);
    
                return $pdf->stream('document.pdf');
            }else {
    
                $pdf = PDF::loadView('print', $data);
                return $pdf->stream("avaxfactor.pdf");
            }
    }

//    public function print2(){
//        $data = [
//            'foo' => 'bar'
//        ];
//        $pdf = Pdf::loadView('pdf.document', $data);
//        return $pdf->stream('document.pdf');
//    }
//    public function print3(){
//        $data = [
//            'foo' => 'bar'
//        ];
//        $pdf = Pdf::loadView('pdf.document', $data);
//        return $pdf->stream('document.pdf');
//    }
//    public function print4(){
//        $data = [
//            'foo' => 'bar'
//        ];
//        $pdf = Pdf::loadView('pdf.document', $data);
//        return $pdf->stream('document.pdf');
//    }

}
