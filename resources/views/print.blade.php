<!doctype html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>فاکتور نهایی</title>


    <style>
        html, body, div, span, applet, object, iframe,
        h1, h2, h3, h4, h5, h6, p, blockquote, pre,
        a, abbr, acronym, address, big, cite, code,
        del, dfn, em, img, ins, kbd, q, s, samp,
        small, strike, strong, sub, sup, tt, var,
        b, u, i, center,
        dl, dt, dd, ol, ul, li,
        fieldset, form, label, legend,
        table, caption, tbody, tfoot, thead, tr, th, td,
        article, aside, canvas, details, embed,
        figure, figcaption, footer, header, hgroup,
        menu, nav, output, ruby, section, summary,
        time, mark, audio, video {
            margin: 0;
            padding: 0;
            border: 0;
            font-size: 100%;
            font: inherit;
            vertical-align: baseline;
        }

        /* HTML5 display-role reset for older browsers */
        article, aside, details, figcaption, figure,
        footer, header, hgroup, menu, nav, section {
            display: block;
        }

        body {
            line-height: 1;
        }

        ol, ul {
            list-style: none;
        }

        blockquote, q {
            quotes: none;
        }

        blockquote:before, blockquote:after,
        q:before, q:after {
            content: '';
            content: none;
        }

        th,td{
            padding-top: 5px;
            padding-bottom: 5px;
        }
    </style>

</head>
<body style="font-family: 'fa'">
<div>
    @foreach($data as $item)

        <div style="border:solid 1px #DCDBDB;direction:rtl;display:flex;justify-content:center;width:25cm; height:29.7cm;">

            <div style="padding:2px">
                <div style="
                                                         border-style:double;
                                                         border-radius:15px;
                                                         align-items:center;
                                                         justify-content:center;
                                                         margin-right:2rem;
                                                         margin-left:2rem;
                                                         margin-top:0.5rem;
                                                         margin-bottom: 0.5rem
">
                    <div style="width: 30%;display: inline-block;float: right; text-align: center; padding:1rem">
                        <img src="/logoTemp.png" style="
                                                                        width: 100%;
                                                                        height: auto;
                                                                        object-fit:cover;
                                                                        padding-right:0.2rem"/>
                    </div>
                    <div style="width: 30%;display: inline-block;float: right;text-align: center">
                        <h5 style="font-size:calc(4px + 1vw); font-weight:bold; margin-top:2rem;margin-bottom:0.1rem">
                            شرکت پیشرو ترابر مهیار شرق</h5>
                        <p style=" font-size:calc(3px + 1vw);margin-bottom:0.1rem">www.avaex.ir</p>
                        <p style="font-size:calc(3px + 1vw);margin-bottom:0.1rem">
                            pishrotarabar@yahoo.com</p>
                        <p style="font-size:calc(3px + 1vw);
                                                                        margin-bottom:0.1rem">05137288532</p>
                    </div>
                    <div style="align-items:center;width: 30%;display: inline-block;float: left;text-align: center;
                                                                    padding:1rem">
                        <img src='{{$item->componyservice->image}}' style="width:100%;
                                                                         height:auto;
                                                                         object-fit:cover;
                                                                         padding-left:0.2rem"/>
                        <p style="font-weight:bold">{{$item->componyservice->compony}}</p>
                    </div>
                </div>

                <div style=" clear:both;">
                    <div style="direction:rtl;float: right;width: 47%;
                                                                    border:solid 2px #000;
                                                                    padding-right:1rem;
                                                                    padding-top:0.3rem;
                                                                    display:flex;
                                                                    flex-direction:column;
                                                                    flex:1;
                                                                    align-items:start">
                        <p style="   font-size:calc(1px + 1vw); font-weight:bold;
                                                                        margin-bottom:0.3rem">فرستنده<span>-</span>فروشگاه<span>:</span>
                            &nbsp;{{$item->address->sender->name}} {{$item->address->sender->family}}
                        </p>
                        <p style="  font-size:calc(1px + 1vw);
                                                                        font-weight:bold;
                                                                        margin-bottom:0.3rem">استان<span>:</span>{{$item->address->provice}}
                            &emsp;&emsp; شهر<span>:</span>{{$item->address->city}}
                        </p>
                        <p style="
                                                                        font-size:calc(1px + 1vw);
                                                                        font-weight:bold;
                                                                        margin-bottom:0.3rem">آدرس<span>:</span>
                            {{$item->address->sender->address}}
                        </p>

                        <p style=" font-size:calc(1px + 1vw);
                                                                        font-weight:bold;
                                                                        margin-bottom:0.3rem"> کدپستی<span>:</span>{{$item->address->sender->postCode}}
                            &emsp;&emsp; تلفن<span>:</span>{{$item->address->sender->tel}}
                        </p>
                        <p style="font-size: calc(1px + 1vw);
                                                                        font-weight:bold;
                                                                        margin-bottom: 0.3rem">تلفنب
                            همراه<span>:</span>{{$item->address->sender->phone}}
                        </p>
                    </div>
                    <div style="  border:solid 2px #000;float: left;width: 47%;
                                                                    padding-right:1rem;
                                                                   padding-top:0.3rem;
                                                                    display:flex;
                                                                    flex-direction:column;
                                                                    flex:1;
                                                                    align-items:start">
                        <p style="    font-size:calc(3px + 1vw);
                                                                        font-weight:bold;
                                                                        margin-bottom: 0.3rem">گیرنده<span>-</span>خریدار<span>:</span>
                            &nbsp;{{$item->geteradress->getter->name}} {{$item->geteradress->getter->family}}

                        </p>
                        <p style=" font-size:calc(1px + 1vw);font-weight:bold; margin-bottom: 0.3rem">
                            استان<span>:</span> {{$item->geteradress->provice}}&emsp;&emsp;
                            شهر<span>:</span>{{$item->geteradress->city}}
                        </p>
                        <p style="  font-size:calc(1px + 1vw);
                                                                        font-weight:bold;
                                                                        margin-bottom:0.3rem">
                            آدرس<span>:</span>{{$item->geteradress->getter->address}}</p>

                        <p style="
                                                                        font-size:calc(1px + 1vw);
                                                                        font-weight:bold;
                                                                        margin-bottom: 0.3rem"> کدپستی<span>:</span>{{$item->geteradress->getter->postCode}}
                            &emsp;&emsp; تلفن<span>:</span>{{$item->geteradress->getter->tel}}
                        </p>
                        <p style="   font-size:calc(1px + 1vw);font-weight:bold; margin-bottom:0.3rem">تلفن
                            همراه<span>:</span>{{$item->geteradress->getter->phone}}
                        </p>
                    </div>
                </div>
                <div style="clear:both;
                                                                display: flex;
                                                                flex-direction: row;
                                                                margin-top:0.5rem;">
                    <table style="width:100%; border:1px solid">
                        <tr>
                            <th style="   border:1px solid;
                                                                            font-size:calc(3px + 1vw);
                                                                            text-align:center">ردیف
                            </th>
                            <th style="  border: 1px solid;
                                                                            font-size:calc(3px + 1vw);
                                                                            text-align:center">نام محصول
                            </th>
                            <th style="
                                                                            border:1px solid;
                                                                            font-size:calc(3px + 1vw);
                                                                            text-align:center">تعداد
                            </th>
                            <th style="    border:1px solid black;
                                                                            font-size:calc(3px + 1vw);
                                                                            text-align:center">قیمت واحد <span>-</span>
                                ريال
                            </th>
                            <th style="     border: 1px solid;
                                                                            font-size:calc(3px + 1vw);
                                                                            text-align:center">تخفیف <span>-</span> ريال
                            </th>
                            <th style=" border:1px solid;
                                                                            font-size:calc(3px + 1vw);
                                                                            text-align:center">مجموع <span>-</span> ريال
                            </th>
                        </tr>
                        <tr>
                            <td style="    border:1px solid;
                                                                            font-size: calc(3px + 1vw);
                                                                            text-align: center">1
                            </td>
                            <td style="   border: 1px solid;
                                                                            font-size: calc(2px + 1vw);
                                                                            text-align: center; font-weight: bold">{{$item->partDitail->shipment}}
                                <span>-</span>
                            </td>
                            <td style="     border: 1px solid;
                                                                            font-size:calc(3px + 1vw);
                                                                            text-align: center">{{$item->partDitail->boxnumber}}
                            </td>
                            <td style="  border:1px solid;
                                                                            font-size: calc(3px + 1vw);
                                                                            text-align:center">
                                {{$item->eachx}}

                            </td>
                            <td style="
                                                                            border:1px solid;
                                                                            font-size:calc(3px + 1vw);
                                                                            text-align: center">0
                            </td>
                            <td style="
                                                                            border: 1px solid;
                                                                            font-size: calc(3px + 1vw);
                                                                            text-align: center">
                                {{$item->eachx}}
                            </td>
                        </tr>
                    </table>
                </div>
                <div style="">
                    <div style="float: right; width: 50%;  border:solid 1px #000;
                                                                    display: flex;
                                                                    flex-direction: column;
                                                                    align-items: center;
                                                                    justify-content: center;
                                                                    flex: 1">
                        <div>
                            @if($item->item->MethodPayment == "پیش کرایه" or $item->item->MethodPayment == "پیشکرایه")
                                <h5 style="text-align: center;
                                                                            font-size: calc(3px + 1vw);
                                                                            font-weight: bold;
                                                                            margin-bottom: 0.1rem;
                                                                            padding-bottom: 1rem;
                                                                            padding-top: 1rem"> نوع
                                    پرداخت<span>:</span><span>{{$item->item->MethodPayment}}</span>
                                </h5>

                            @else
                                <h5 style="text-align: center;background-color: black;color: white;
                                                                            font-size: calc(3px + 1vw);
                                                                            font-weight: bold;
                                                                            margin-bottom: 0.1rem;
                                                                            padding-bottom: 1rem;
                                                                            padding-top: 1rem"> نوع
                                    پرداخت<span>:</span><span>{{$item->item->MethodPayment}}</span>
                                </h5>
                            @endif

                        </div>
                    </div>
                    <div style="float: left;width: 49%;   border:solid 1px #000;
                                                                    display:flex;
                                                                    flex-direction:column;
                                                                    align-items:center;
                                                                    justify-content: center;
                                                                    flex:1">

                        @if($item->item->MethodPayment == "پیش کرایه" or $item->item->MethodPayment == "پیشکرایه")
                            <h5 style="text-align: center;
                                                                        font-size:calc(3px + 1vw);
                                                                        font-weight:bold;
                                                                        margin-bottom:0.1rem;
                                                                        padding-bottom:1rem;
                                                                        padding-top:1rem"> مبلغ قابل پرداخت توسط
                                گیرنده
                                <span>:</span>
                                @if($item->item->isAfterRent)
                                    {{$item->eachx}} ريال
                                @elseif($item->item->isCod)
                                    {{$item->partDitail->amountCOD}} ريال
                                @else
                                    0 ريال
                                @endif

                            </h5>
                        @else
                            <h5 style="text-align: center;background-color: black;color: white;
                                                                        font-size:calc(3px + 1vw);
                                                                        font-weight:bold;
                                                                        margin-bottom:0.1rem;
                                                                        padding-bottom:1rem;
                                                                        padding-top:1rem"> مبلغ قابل پرداخت توسط
                                گیرنده
                                <span>:</span>
                                @if($item->item->isAfterRent)
                                    {{$item->eachx}} ريال
                                @elseif($item->item->isCod)
                                    {{$item->eachx + $item->eachCOD}} ريال
                                @else
                                    0 ريال
                                @endif

                            </h5>
                        @endif

                        {{--    {/* {eachPrice} */}
                            {/* <p style="font-size:calc(3px + 1vw); font-weight:bold; margin-bottom:0.1rem"></p> */}--}}
                    </div>
                </div>
                <div style="clear:both;display:flex;flex-direction:row; margin-top:0.4rem;">
                    <div style=" float: right;width: 50%;   display: flex;
                                                                    flex-direction: column;
                                                                    flex: 1">
                        <div style="  border: 1px solid #000;
                                                                        padding:1rem;
                                                                        font-size:calc(1px + 1vw)">
                            تمامی مسئولیت های مرتبط با محتوای سفارش اعم
                            از مطابقات
                            سفارش با درخواست های مشتری و رعایت قوانین و
                            مقررات مربوطه
                            صرفا بر عهده فروشگاه می باشد.
                        </div>
                        <div style="
                                                                        border:1px solid #000;
                                                                        padding:1rem;
                                                                        font-size:calc(3px + 1vw);
                                                                        font-weight:bold">شماره سفارش<span>:</span>
                            &emsp;<span style="font-size:2rem;
                                                                            font-weight:bold">{{$item->item->numberParcel}}<span>-</span> {{$item->partDitail->partnumber}}</span>
                        </div>
                    </div>
                    <div style=" float: right;width: 50%; display: flex;flex-direction:column; flex:1">
                        <div style=" border:1px solid #000; border-radius:25px;display:flex; flex-direction:row;
                                                                        margin-left:1rem;
                                                                        margin-right:1rem; margin-bottom:0.2rem">
                            <div style="height: 7rem ;float: right;width: 47%;   display: flex;
                                                                            flex-direction:column;
                                                                            align-items:center;
                                                                            justify-content:start;
                                                                            flex:1;
                                                                            border-left:1px dotted #000;
                                                                            min-height:7rem;
                                                                            padding-top:0.3rem;
                                                                            margin-right:0.5rem;
                                                                            font-size:calc(0.1px + 1vw)">محل ضرب مهر
                                فروشگاه
                            </div>
                            <div style="float: left;width: 47%;     display:flex;
                                                                            flex-direction:column;
                                                                            align-items:center;
                                                                            justify-content:start;
                                                                            flex:1;
                                                                            padding-top:0.3rem;
                                                                            font-size:calc(0.1px + 1vw)">محل امضا خریدار
                            </div>
                        </div>
                        <div style="padding: 0.75rem; border:1px solid;
                                                                        margin-top:0.1rem;
                                                                        margin-right: 0.9rem;
                                                                        margin-left: 0.9rem;
                                                                        padding-right: 0.2rem;
                                                                        font-size:calc(1px + 1vw);
                                                                        font-weight: bold">ارزش اظهار شده
                            کالا<span>:</span> &emsp; &emsp;{{$item->partDitail->value}} ريال
                        </div>
                    </div>

                </div>
                <hr style="border-top:2px dashed black"/>

                <div style=" clear:both;    display:flex;
                                                                flex-direction:row;
                                                                margin-top:1rem;
                                                               ">

                    <div style="float: right;width: 50%;   display:flex;
                                                                    flex-direction:column;
                                                                    flex:1;
                                                                    margin-top:0.1rem;
                                                                    margin-right:0.2rem;
                                                                    margin-left:0.2rem">
                        <div style=" border:1px solid #000;
                                                                        border-radius:25px;
                                                                        padding:0.1rem">
                            <div style="   display:flex;
                                                                            flex-direction: row;
                                                                            margin-top: 0.5rem;
                                                                            margin-right: 0.2rem;
                                                                            margin-left: 0.2rem;
                                                                            margin-bottom: 0.5rem;
                                                                            font-size: calc(0.1px + 1vw)">علت
                                برگشتی<span>:</span>
                            </div>
                            <div style="display: flex;flex-direction: row;margin-top: 0.1rem">
                                <div style="float:right;margin-top:0.1rem ; width: 50%;">
                                    <div style="display:flex;flex-direction:row;line-height:normal">
                                        <input type="checkbox"
                                               style="display:flex;flex-direction:row;flex:1;transform:scale(0.5)"/>
                                        <label style="font-size:calc(0.1px + 1vw)"><span style="font-size: 10px">عدم اطلاع از سفارش</span></label>
                                    </div>
                                    <div style="display:flex;flex-direction:row;line-height:normal">
                                        <input type="checkbox"
                                               style="display:flex;flex-direction:row;flex:1;transform:scale(0.5)"/>
                                        <label style="font-size:calc(0.1px + 1vw)"><span style="font-size: 10px">عدم وجود وجه نقد/کارت</span></label>
                                    </div>
                                    <div style="display:flex;flex-direction:row;line-height:normal">
                                        <input type="checkbox"
                                               style="display:flex;flex-direction:row;flex:1;transform:scale(0.5)"/>
                                        <label style="font-size:calc(0.1px + 1vw)"><span style="font-size: 10px">درخواست بازگشایی</span></label>
                                    </div>
                                    <div style="display:flex;flex-direction:row;line-height:normal">
                                        <input type="checkbox"
                                               style="display:flex;flex-direction:row;flex:1;transform:scale(0.5)"/>
                                        <label style="font-size:calc(0.1px + 1vw)"><span style="font-size: 10px">انصراف از خرید</span></label>
                                    </div>
                                    <div style="display:flex;flex-direction:row;line-height:normal">
                                        <input type="checkbox"
                                               style="display:flex;flex-direction:row;flex:1;transform:scale(0.5)"/>
                                        <label style="font-size:calc(0.1px + 1vw)"><span style="font-size: 10px">سفارش تکراری</span></label>
                                    </div>
                                </div>
                                <div style="float: left ;margin-top: 0.1rem ; width: 50%">
                                    <div style="display:flex;flex-direction:row;line-height:normal">
                                        <input type="checkbox"
                                               style="display:flex;flex-direction:row;flex:1;transform:scale(0.5)"/>
                                        <label style="font-size:calc(0.1px + 1vw)"><span style="font-size: 10px">نقل مکان</span></label>
                                    </div>
                                    <div style="display:flex;flex-direction:row;line-height:normal">
                                        <input type="checkbox"
                                               style="display:flex;flex-direction:row;flex:1;transform:scale(0.5)"/>
                                        <label style="font-size:calc(0.1px + 1vw)"><span style="font-size: 10px">مغایرت مبلغ فاکتور</span></label>
                                    </div>
                                    <div style="display:flex;flex-direction:row;line-height:normal">
                                        <input type="checkbox"
                                               style="display:flex;flex-direction:row;flex:1;transform:scale(0.5)"/>
                                        <label style="font-size:calc(0.1px + 1vw)"><span style="font-size: 10px">تاخیر در ارسال سفارش</span></label>
                                    </div>
                                    <div style="display:flex;flex-direction:row;line-height:normal">
                                        <input type="checkbox"
                                               style="display:flex;flex-direction:row;flex:1;transform:scale(0.5)"/>
                                        <label style="font-size:calc(0.1px + 1vw)"><span style="font-size: 10px">گیرنده شناخته نشد</span></label>
                                    </div>
                                    <div style="display:flex;flex-direction:row;line-height:normal">
                                        <input type="checkbox"
                                               style="display:flex;flex-direction:row;flex:1;transform:scale(0.5)"/>
                                        <label style="font-size:calc(0.1px + 1vw)"><span style="font-size: 10px">سایر موارد<span>:</span><span>..............</span></span></label>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div style="       border: 1px solid #000;
                                                                        border-radius: 25px;
                                                                        display: flex;
                                                                        flex-direction: row; margin-bottom: 0.2rem;margin-top: 0.2rem">
                            <div style="
                                                                            margin-top:0.25rem;
                        font-size:calc(1px + 1vw);
                        padding: 0.5rem">
                                مشتری گرامی<span>:</span><br/>
                                شما میتوانید با مراجعه به سایت
                                <span style="
                                                                                font-size: calc(3px + 1vw);
                                                                                font-weight: bold"> avaex.ir </span>
                                براحتی ثبت نام کرده و مرسولات پستی خود
                                را به هر
                                نقطه از کشور و 180 کشور جهان بدون خارج
                                شدن از منزل
                                یا محل کار خود ارسال
                                نمایید<span>.</span><br/>
                                <p style="    font-size: calc(5px + 1vw);
                                                                                font-weight: bold">آواکس برای خدمات
                                    ارزشمندی که شایسته
                                    آنید</p>
                            </div>
                        </div>
                        <div style="  font-size: 2rem;
                                                                        font-weight: bold;
                                                                        text-align: center;
                                                                        margin-top: 2rem">مقصد <span>:</span>
                            {{$item->geteradress->provice}} - {{$item->geteradress->city}}
                        </div>


                    </div>

                    <div style="display: flex;flex-direction: column;flex:1;margin-top:0.1rem; margin-right: 0.2rem; margin-left: 0.2rem">


                        <div style=" display: flex;flex-direction: column;border: 1px dotted #000;">

                            <div style="display:flex;flex-direction:column">
                                <div style="display: flex;flex-direction: row;margin-top: 0.1rem;border-bottom: 1px solid #000">
                                    <div style="text-align: center;display: flex;flex-direction: column;flex: 1;margin-top: 0.1rem;align-items: center;justify-content: center">
                                        <img src='{{$item->componyservice->image}}'
                                             style="width: 80px; height: auto;object-fit: cover"/>
                                        <h5 style="font-size: calc(2px + 1vw)">{{$item->componyservice->compony}}</h5>
                                        <p style="font-size: calc(2px + 1vw)">نوع ارسال<span>:</span>
                                            {{$item->componyservice->service}}-{{$item->componyservice->componytype}}
                                        </p>
                                    </div>
                                </div>
                                <div style="display:flex;flex-direction:row;border-bottom:1px solid #000">
                                    <div style=" width: 88%;display: inline-block;float:right; display: flex;
                                                                                    flex-direction: column;
                                                                                    flex: 10">
                                        <div style="border-bottom: 1px solid #000;padding: 0.5rem;font-size: calc(1px + 1vw);">
                                            مبدا<span>:</span> {{$item->address->provice}}
                                        </div>
                                        <div style="display: flex;flex-direction: row; border-bottom: 1px solid #000; padding: 0.5rem">
                                            <div style="width: 40%;display: inline-block;float:right; display: flex;flex: 2;border-left: 1px solid #000;padding-right: 0.5rem; font-size: calc(1px + 1vw)">
                                                مقصد<span>:</span> {{$item->geteradress->provice}}
                                            </div>
                                            <div style="width: 30%;display: inline-block; float:left ; display: flex;flex: 1;padding-right: 0rem; font-size: calc(1px + 1vw); text-align: right">
                                                وزن<span>:</span> {{$item->partDitail->weight}}
                                            </div>
                                        </div>
                                        <div style="padding: 0.5rem;font-size:calc(1px + 1vw)">تاریخ
                                            ارجاع <span>:</span> {{$item->date}} - {{$item->time}}
                                        </div>
                                    </div>
                                    <div style="width: 10%;display: inline-block;float:left;text-align: center; display:flex;height:3rem;
                                                                                    flex-direction:column;
                                                                                    flex:1;
                                                                                    align-items: center;
                                                                                    justify-content: center;
                                                                                    border-right: 1px solid #000;
                                                                                    font-size: calc(3px + 1vw);
padding-top: 3rem">1
                                    </div>
                                </div>
                                <div style="    display: flex;
                                                                                flex-direction: row;
                                                                                border-bottom: 1px solid #000">


                                </div>


                            </div>
                            <div style="padding: 0.4rem">
                                @php

                                    $generatorPNG = new Picqer\Barcode\BarcodeGeneratorPNG();

                                @endphp

                                <div>
                                    <div style="   display:flex;
                                                                            flex-direction: row;
                                                                            margin-top: 0.5rem;
                                                                            margin-right: 0.2rem;
                                                                            margin-left: 0.2rem;
                                                                            margin-bottom: 0.5rem;
                                                                            font-size: calc(0.1px + 1vw)">
                                    شماره بارنامه <span>:</span>
                                    </div>
                                    @if($item->partDitail->serviceNumberParcel)
                                        <img style="margin-top: 0.5rem"
                                             src="data:image/png;base64,{{ base64_encode($generatorPNG->getBarcode($item->partDitail->serviceNumberParcel, $generatorPNG::TYPE_CODE_128)) }}">
                                    @else
                                        <img style="margin-top: 0.5rem"
                                             src="data:image/png;base64,{{ base64_encode($generatorPNG->getBarcode("1", $generatorPNG::TYPE_CODE_128)) }}">
                                    @endif
                                    <p style="text-align: center;margin-top: 0.5rem">{{$item->partDitail->serviceNumberParcel}}</p>
                                </div>
                                <br>
                                <div>
                                    شماره پارت <span>:</span>

                                    @if($item->partDitail->serviceNumberParcel)
                                        <img style="margin-top: 0.5rem"
                                             src="data:image/png;base64,{{ base64_encode($generatorPNG->getBarcode($item->partDitail->servicePartnumber, $generatorPNG::TYPE_CODE_128)) }}">
                                    @else
                                        <img style="margin-top: 0.5rem"
                                             src="data:image/png;base64,{{ base64_encode($generatorPNG->getBarcode("1", $generatorPNG::TYPE_CODE_128)) }}">
                                    @endif


                                    <p style="text-align: center;margin-top: 0.5rem">{{$item->partDitail->servicePartnumber}}</p>
                                </div>
                            </div>
                            {{--    <Barcode
                                        value={data.serviceNumberParcel} height="35"
                                        displayValue="true"
                                        font="iran"
                                        style={{ padding: '0.4rem' }} />
                                <p style={{ margin: 0 }}>شماره بارنامه</p>

                                <Barcode
                                        value={data.servicePartnumber} height="35"
                                        displayValue="true" /><p>شماره پارت</p>--}}
                        </div>
                    </div>

                </div>
                {{--  {/*
                  <div style="     display: flex;
                                                                  flex-direction: row;
                                                                  margin-top: 0.4rem;
                                                                  margin-right: 2rem;
                                                                  margin-left: 2rem">
                      <div style="display: flex; flex-direction:column; flex: 1"></div>
                      <div style="display: flex; flex-direction:column; flex: 1">

                      </div>
                  </div>
                  */}--}}

            </div>
        </div>
        @if($loop->index == count($data))
            <pagebreak></pagebreak>
        @endif
    @endforeach

</div>


</body>
</html>
