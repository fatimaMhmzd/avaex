<?php

function create_image($string)
{
    $img = imagecreate(200, 30);

    imagecolorallocate($img, 255, 255, 255);
    $text_color = imagecolorallocate($img, 0, 0, 0);
    imagettftext($img, 12, 0, 0, 20, $text_color, 'fonts/fonts/ttf/IRANSansWeb(FaNum).ttf', $string);

//    header('Content-Type: image/png');
//    $save = 'pdf-image/image.png';
//    imagepng($img, $save);
//    imagedestroy($img);
}

?>





<!doctype html>
<html lang=>

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <style>
        html,
        body,
        div,
        span,
        applet,
        object,
        iframe,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        p,
        blockquote,
        pre,
        a,
        abbr,
        acronym,
        address,
        big,
        cite,
        code,
        del,
        dfn,
        em,
        img,
        ins,
        kbd,
        q,
        s,
        samp,
        small,
        strike,
        strong,
        sub,
        sup,
        tt,
        var,
        b,
        u,
        i,
        center,
        dl,
        dt,
        dd,
        ol,
        ul,
        li,
        fieldset,
        form,
        label,
        legend,
        table,
        caption,
        tbody,
        tfoot,
        thead,
        tr,
        th,
        td,
        article,
        aside,
        canvas,
        details,
        embed,
        figure,
        figcaption,
        footer,
        header,
        hgroup,
        menu,
        nav,
        output,
        ruby,
        section,
        summary,
        time,
        mark,
        audio,
        video {
            margin: 0;
            padding: 0;
            text-align: right;
            direction: rtl;
            box-sizing: border-box;
            font: inherit;
            font-size: 12px;
        }

        .h-100 {
            height: 100%;
        }

        .w-10 {
            width: 10%;
        }

        .w-20 {
            width: 20%;
        }

        .w-30 {
            width: 30%;
        }

        .w-40 {
            width: 40%;
        }

        .w-40 {
            width: 40%;
        }

        .w-45 {
            width: 45%;
        }

        .w-50 {
            width: 50%;
        }

        .w-60 {
            width: 60%;
        }

        .w-70 {
            width: 70%;
        }

        .w-80 {
            width: 80%;
        }

        .w-90 {
            width: 90%;
        }

        .w-100 {
            width: 100%;
        }

        .float {
            float: left;
        }

        .inline {
            display: inline-block;
        }

        .round {
            border-radius: 20px;
        }

        .border-1 {
            border: 1px solid black;
        }

        .border-2 {
            border: 2px solid black;
        }

        .logo {
            height: 70px;
            width: 170px;
            margin: 25px;
            object-fit: cover;
        }

        td {
            height: 20px;
            border: 1px solid black;
            text-align: center;
        }

        .tr-info td {
            text-align: right;
        }

        .table {
            margin: 0 5px 0 5px;
        }

        .text-center {
            text-align: center;
        }

        .post-logo {
            width: 60px;
            height: 61px;
            display: inline-block;
        }

        .table-info {

            width: 100px;
            height: 100px;
        }

        .table-info-number {
            width: 100%;
            height: 100%;
            font-size: 40px;
            color: red;
        }

        .img-markup {
            width: 48px !important;
            height: 80px !important;
            object-fit: cover;
            margin: 10px 0 0 20px;
        }
    </style>
</head>

<body dir="rtl" style="font-family: 'fa'">
    @foreach ($data as $item)
        <div class="border-2">
            <div class="border-2 round" style="margin-top:5px;margin-right:2.5%; width: 95%;padding-top: 5px;">
                <div class="float inline w-30">
                    <img src="https://www.avaex.ir/logoTemp.png"
                        style="margin:5px 0 0 0;height: 70px;width: 170px;object-fit: cover;">
                </div>
                <div class="float inline w-40">
                    <p><strong>شرکت واسط اینترنتی:</strong>شرکت پیشرو ترابر مهیار شرق</p>
                    <p>نشان اینترنتی شرکت:www.avaex.ir</p>
                    <p>آدرس ایمیل شرکت:pishrotarabar@yahoo.com</p>
                    <p> تلفن تماس واحد پشتیبانی:05137288532</p>
                </div>
                <div class="float inline w-30 text-center" style="margin-top: -3px"><img
                        src="{{ $item->componyservice->image }}" style="height:95px;width: 92px;object-fit: cover;" />
                </div>
            </div>

            <div style="margin-top:5px;">
                <div class="float inline w-50 border-1" style="margin-left:15px;padding:4px">
                    <p><strong>فرستنده (فروشگاه):</strong>{{ $item->address->sender->name }}
                        {{ $item->address->sender->family }}</p>
                    <p><span><strong>استان:</strong>{{ $item->address->provice }}</span><span><strong>شهر:</strong>{{ $item->address->city }}</span>
                    </p>


                    <p><strong>آدرس:</strong>{{ $item->address->sender->address }}</p>
                    <p><span><strong>کدپستی:</strong>{{ $item->address->sender->postCode }}</span><span>
                            <strong>تلفن:</strong>{{ $item->address->sender->phone }}</span></p>
                    <p>زمان پاسخگویی:.................شناسه نماد اعتماد:..............</p>
                    <p>آدرس اینترنتی:......................ایمیل:.........................</p>
                </div>
                <div class="float inline w-40" style="margin: 0 0 0 30px">
                    <div class="border-1" style="margin-bottom: 5px; padding:4px;">تمامی مسئولیت های مرتبط با محتوای
                        سفارش
                        اعم از مطابقت سفارش با درخواست مشتری و رعایت قوانین و مقررات مربوطه صرفا به عهده فروشگاه
                        می‌باشد.
                    </div>
                    <div class="border-1" style="margin-bottom: 5px;padding:4px;font-size:16px;"><strong
                            style="font-size:16px;">شناسه
                            سفارش:</strong>{{ $item->item->numberParcel }}<span>-</span>{{ $item->partDitail->partnumber }}
                    </div>
                    <div class="border-1" style="padding:4px"><strong style="font-size:14px;">اطلاعات محصول خریداری
                            شده:</strong>............</div>
                </div>
            </div>

            <table class="table w-100" style="border-collapse: collapse;">
                <thead>
                    <tr>
                        <td>ردیف</td>
                        <td>نام محصول</td>
                        <td>تعداد</td>
                        <td>قیمت واحد(ریال)</td>
                        <td>تخفیف(ریال)</td>
                        <td>مجموع(ریال)</td>
                    </tr>
                </thead>

                <tbody>
                    <tr class="table-tr">
                        <td>1</td>
                        <td>{{ $item->partDitail->shipment }}</td>
                        <td>{{ $item->partDitail->boxnumber }}</td>
                        <td>{{ $item->eachx }}</td>
                        <td>0</td>
                        <td>{{ $item->eachx }}</td>
                    </tr>

                    <tr>
                        <td colspan="3"><span>نوع پرداخت:</span>{{ $item->item->MethodPayment }}</td>
                        <td colspan="3"><span>مجموع:</span>{{ $item->item->totalCost }}</td>
                    </tr>
                </tbody>
            </table>

            <div style="margin-top:5px;">
                <div class="float inline w-40 border-1 round" style="margin-left:15px">
                    <div class="float inline w-50 text-center" style="height: 180px;border-right: 1px dashed black;">محل
                        امضا خریدار:</div>
                    <div class="float inline w-40 text-center" style="height: 180px;">محل ضرب مهر فروشگاه:</div>
                </div>

                <div class="float inline w-50 border-1" style="margin: 0 0 0 30px;padding:4px;">
                    <p><strong>گیرنده (خریدار):</strong>{{ $item->geteradress->getter->name }}
                        {{ $item->geteradress->getter->family }}</p>
                    <p><strong>استان:{{ '  ' }}</strong>{{ $item->geteradress->provice }}<strong>شهر:{{ '  ' }}</strong>{{ $item->geteradress->city }}
                    </p>
                    <p><strong>آدرس:</strong>{{ $item->geteradress->getter->address }}</p>
                    <p><strong>تلفن:</strong>{{ $item->geteradress->getter->phone }}<strong>کدپستی</strong>{{ $item->geteradress->getter->postCode }}
                    </p>
                    <p>زمان پاسخگویی:...............شناسه نماد اعتماد:............</p>
                    <p>آدرس اینترنتی:...................ایمیل:....................</p>
                </div>

            </div>

            <hr />

            <div class="w-100" style="margin:0 0 2px 0;padding:0 5px;">
                <div class="float inline w-60" style="margin-left: 2px">
                    <div style="border: 2px dashed black">
                        <div class="float inline text-center" style="width: 70%">
                            {{-- <img class="float img-markup" src="./images/water.png" alt=""> --}}
                            <img src="{{ $item->componyservice->image }}" alt="" class="post-logo" />
                            <p class="text-center"><strong style="font-size:10px">خدمات تجارت الکترونیک</strong></p>
                            <p class="text-center" style="margin-top:5px">نوع
                                ارسال:{{ $item->componyservice->service }}-{{ $item->componyservice->componytype }}
                            </p>
                            <table class="table w-100">
                                <tbody>
                                    <tr class="tr-info">
                                        <td colspan="2">مبدا:{{ $item->address->provice }}</td>
                                        <td rowspan="3" class="table-info" style="text-align: center;"><span
                                                class="table-info-number">10</span></td>
                                    </tr>
                                    <tr class="tr-info">
                                        <td>مقصد:{{ $item->geteradress->provice }}</td>
                                        <td>وزن:{{ $item->partDitail->weight }}</td>
                                    </tr>
                                    <tr class="tr-info">
                                        <td>تاریخ:{{ $item->date }}</td>
                                        <td>زمان:{{ $item->time }}</td>
                                    </tr>
                                    <tr class="tr-info">
                                        <td>کرایه پستی:
                                            <br>
                                            {{number_format($item->item->Freight)}}ریال</td>
                                        <td colspan="2">مالیت ارزش افزوده:{{number_format(ceil($item->item->Freight * 9 / 100))}}ریال</td>
                                    </tr>
                                </tbody>

                            </table>

                            @php

                                $generatorPNG = new Picqer\Barcode\BarcodeGeneratorPNG();

                            @endphp

                            @if ($item->partDitail->serviceNumberParcel)
                                <img style="margin-top:30px;width:200px;height:40px; margin-left: 30px;"
                                    src="data:image/png;base64,{{ base64_encode($generatorPNG->getBarcode($item->partDitail->serviceNumberParcel, $generatorPNG::TYPE_CODE_128)) }}">
                            @else
                                <img style="margin-top:30px;width:200px;height:40px;margin-left: 30px;"
                                    src="data:image/png;base64,{{ base64_encode($generatorPNG->getBarcode('1', $generatorPNG::TYPE_CODE_128)) }}">
                            @endif

                            <p style="text-align: center;margin-top:1px;margin-bottom: 20px;margin-left: 20px">
                                {{ $item->partDitail->serviceNumberParcel }}</p>
                        </div>


                        <div class="float inline" style="width:10%;padding-top:110px ;font-size:42px;">
                            @php

                                $generatorPNG = new Picqer\Barcode\BarcodeGeneratorPNG();
                                create_image($item->partDitail->serviceNumberParcel);

                            @endphp

                            @if ($item->partDitail->serviceNumberParcel)
                                <img style="width:200px;height:40px;margin-bottom: 100px;transform:rotate(90deg)"
                                    src="data:image/png;base64,{{ base64_encode($generatorPNG->getBarcode($item->partDitail->serviceNumberParcel, $generatorPNG::TYPE_CODE_128)) }}">
                            @else
                                <img style="width:200px;height:40px;margin-bottom: 100px;transform:rotate(90deg)"
                                    src="data:image/png;base64,{{ base64_encode($generatorPNG->getBarcode('1', $generatorPNG::TYPE_CODE_128)) }}">
                            @endif




                        </div>

                        <div class="float inline" style="width:7%;padding-top:50px;font-size:30px;">
                            <img style="transform:rotate(-90deg);margin:100px 0 0 0;" src="pdf-image/image.png"
                                alt="">
                        </div>


                    </div>

                    <div class="border-2 text-center" style="border-radius: 5px; margin-bottom:50px;">تلفن ارتباط با امور
                        مشتریان شرکت ملی پست: 193</div>
                </div>

                <div class="float inline" style="width: 38%">
                    <div class="border-1" style="margin: 0 0 0 15px;padding:4px;">
                        <p><strong>علت برگشتی:</strong></p>
                        <div class="w-100">

                            <div class="float inline w-50">
                                <div>
                                    <input type="checkbox" name="" id="">
                                    <span>نقل مکان</span>
                                </div>
                                <div>
                                    <input type="checkbox" name="" id="">
                                    <span>مغایرت مبلغ فاکتور</span>
                                </div>
                                <div>
                                    <input type="checkbox" name="" id="">
                                    <span>تاخیر در ارسال سفارش</span>
                                </div>
                                <div>
                                    <input type="checkbox" name="" id="">
                                    <span>گیرنده شناخته نشد</span>
                                </div>
                                <div>
                                    <input type="checkbox" name="" id="">
                                    <span>سایر موارد:...</span>
                                </div>
                            </div>

                            <div class="float inline w-50">
                                <div>
                                    <input type="checkbox" name="" id="">
                                    <span>عدم اطلاع از سفارش</span>
                                </div>
                                <div>
                                    <input type="checkbox" name="" id="">
                                    <span>عدم وجود وجه نقد/کارت بانکی</span>
                                </div>
                                <div>
                                    <input type="checkbox" name="" id="">
                                    <span>درخواست بازگشایی</span>
                                </div>
                                <div>
                                    <input type="checkbox" name="" id="">
                                    <span>انصراف از خرید</span>
                                </div>
                                <div>
                                    <input type="checkbox" name="" id="">
                                    <span>سفارش تکراری</span>
                                </div>
                            </div>

                        </div>
                        <p><strong>نام و نام خانوادگی و امضا:</strong></p>
                    </div>


                    <div class="w-100 text-center" style="margin: 5px 0 0 0">
                        @if ($item->componyservice->componytype === 'پیشتاز')
                            <img src="https://back.avaex.ir/common/pishtazPost.jpg" class="w-70">
                        @elseif($item->componyservice->componytype === 'ویژه')
                            <img src="https://back.avaex.ir/common/specialPost.jpg" class="w-80"
                                style="margin:0 0 0 10px">
                        @endif
                    </div>








                </div>
            </div>





        </div>

        <div class="text-center" style="margin-top:5px;"><strong
                style="font-size:18px;">مقصد:{{ $item->geteradress->provice }}-{{ $item->geteradress->city }}</strong>
        </div>
    @endforeach



</body>

</html>



{{-- <img style="transform:rotate(90deg);margin:0 -150px 0 0;"
src="data:image/png;base64,{{ base64_encode($generatorPNG->getBarcode($item->partDitail->serviceNumberParcel, $generatorPNG::TYPE_CODE_128)) }}"> --}}

{{-- <div class="border-2 text-center" style="border-radius: 5px; margin:2px 20px;">تلفن ارتباط با امور
    مشتریان شرکت ملی پست: 193</div> --}}

{{-- font-size:42px;margin:30px 0 0 0; --}}


{{-- @if ($item->partDitail->serviceNumberParcel)
    <img style="width:200px;height:10px;transform:rotate(90deg);margin:20px -120px 0 0;"
        src="data:image/png;base64,{{ base64_encode($generatorPNG->getBarcode($item->partDitail->serviceNumberParcel, $generatorPNG::TYPE_CODE_128)) }}">


@else
    <img style="width:200px;height:40px;transform:rotate(90deg);margin:20px -120px 0 0;"
        src="data:image/png;base64,{{ base64_encode($generatorPNG->getBarcode('1', $generatorPNG::TYPE_CODE_128)) }}">

@endif

<img style="transform:rotate(-90deg);margin:100px 0px 0 0;" src="pdf-image/image.png" alt=""> --}}
