@extends('admin/layouts/reportlayout')
@section('content')
<!-- onload="window.print()" -->

<div style="text-align:center;" class="row">
    <div class="col-xs-4">
        <h4>{{ Auth::user()->CompanyName }}</h2>
            <h4>{{ Auth::user()->BusinessTypeEnglish }}</h4>
            <b>{{ Auth::user()->BusinessDescriptionEnglish }}</b>
            <div>CR No: {{ Auth::user()->CRNO }}</div>
            <div>VAT No: {{ Auth::user()->VATNO }}</div>
            <div>Mobile No. : {{Auth::user()->cell}}</div>
    </div>
    <div class="col-xs-4">
        @if ( Auth::user()->Invoice_pic == "" or Auth::user()->Invoice_pic == "Invoice_pic")

        @else
        <img src="{{ Auth::user()->Invoice_pic }}" alt="" height="130px" width="150px">
        @endif
    </div>
    <div class="col-xs-4">
        <h4>{{ Auth::user()->CompanyNameArabic }}</h4>
        <h4>{{ Auth::user()->BusinessTypeArabic }}</h4>
        <b>{{ Auth::user()->BusinessDescriptionArabic }}</b>
        <div>التسجيل التجاري: {{ $cr_number_arabic }}</div>
        <div>رقم تسجيل ضريبة القيمة المضافة: {{ Auth::user()->VATNO_Arabic }}</div>
    </div>

</div>
<div style="border: 1px solid black;border-radius:5px;margin-bottom:10px" class="text-center">
    <b>Quotation / عرض السعر</b>
</div>

<div style="border: 1px solid black;border-radius:5px;padding:10px;display:flex;justify-content:space-between">


    <div style="width: 60%;">
        <div> Customer Detail: تفاصيل العميل</div>
        <div> {{$salebook->CustomerName}}</div>
        @if($salebook->Cell != '')
        <div>
            <b>Mobile: {{$salebook->Cell}}</b>
        </div>
        @endif
        @if($salebook->Email != '')
        <div>Email: {{$salebook->Email}}</div>
        @endif
        <div>VAT No: {{$salebook->VATNO}}</div>
        <div>Address: {{$salebook->Addres}}</div>

    </div>
    <div style="width: 40%;">

        <div style="display:flex;justify-content:space-between">
            <span>Quotation No. &nbsp; </span> <span>{{$salebook->Invoice}}&nbsp;&nbsp;&nbsp;</span> <span><b> :رقم
                    الفاتورة </b></span>
        </div>
        <div style="display:flex;justify-content:space-between">
            <span>
                Date:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </span>
            <span>{{explode('-',$salebook->Date)[2].'-'.explode('-',$salebook->Date)[1].'-'.explode('-',$salebook->Date)[0]}}&nbsp;&nbsp;&nbsp;</span>
            <span><b> :تاريخ الفاتورة </b></span>
        </div>
        <div style="display:flex;justify-content:space-between">
            <span> Validation Date:&nbsp;&nbsp;&nbsp; </span>
            <span>{{explode('-',$salebook->dueDate)[2].'-'.explode('-',$salebook->dueDate)[1].'-'.explode('-',$salebook->dueDate)[0]}}&nbsp;&nbsp;&nbsp;</span>
            <span><b> :تاريخ الفاتورة </b></span>
        </div>
        <div style="display:flex;justify-content:space-between">
            <span> Payment Term: &nbsp;&nbsp;&nbsp;</span> <span>{{$salebook->PaymentType}}&nbsp;&nbsp;&nbsp;</span>
            <span> <b> :شروط الدفع </span></b>
        </div>


    </div>

</div>
<div style="margin-top: 10px;  {{count($salebook_detail) <=5 ? 'height:400px' : 'height:auto'}}" class="">
    <table style="border:1px solid black" class="table table-responsive ">
        <thead>
            <tr>
                <th style="border:1px solid black;text-align:center">SN</th>
                @if(Auth::user()->ArticleNo == 'FM' or Auth::user()->ArticleNo == 'AT' or Auth::user()->ArticleNo ==
                'jj')
                <th style="width: 10%;border:1px solid black;text-align:center">
                    رقم الفاتورة
                </th>
                @else
                <th style="width: 15%;border:1px solid black;text-align:center">
                    Item Code <br> رقم الصنف
                </th>
                @endif
                @if(Auth::user()->ArticleNo == 'AT' or Auth::user()->ArticleNo == 'jj')
                <th style="width: 40%;border:1px solid black;text-align:left">
                    Description <br> وصف
                </th>
                @else
                <th style="width: 35%;border:1px solid black;text-align:left">

                    Description <br> وصف
                </th>
                @endif
                <th style="width: 10%;border:1px solid black;text-align:center">
                    Qty <br> الكمية
                </th>

                <th style="width: 13%;border:1px solid black;text-align:center">
                    Unit Price <br> سعر الوحدة
                </th>
                <th style="width: 20%;border:1px solid black;text-align:center">
                    Total Price <br> السعر الإجمالي
                </th>
            </tr>
        </thead>
        <tbody>
            @for($a=0; $a < count($salebook_detail); $a++) <tr>
                <td style="border:1px solid black;text-align:center">{{$a+1}}</td>
                <td style="border:1px solid black;text-align:center">{{$salebook_detail[$a]->ItemCode}}</td>
                <td style="border:1px solid black;text-align:left">{{$salebook_detail[$a]->ItemName}}</td>
                <td style="border:1px solid black;text-align:center">{{$salebook_detail[$a]->Qty}}</td>
                <td style="border:1px solid black;text-align:center">{{$salebook_detail[$a]->Rate}}</td>
                <td style="border:1px solid black;text-align:center">{{number_format($salebook_detail[$a]->Total,2)}}
                </td>
                </tr>
                @endfor
        </tbody>
    </table>
</div>



<div>


    <div
        style="border: 1px solid black;border-radius:10px;height:auto;padding:10px;display:inline-block;vertical-align: top;width:100%">
        @if(Auth::user()->VAT_Calculation == 'Before')
        <div style="display:flex;justify-content: space-between">
            <span style="text-align: center"> Total (Excluding Vat) </span> <span style="text-align: right" dir="rtl"
                lang="ar"> الإجمالي غير (شاملة ضريبة القيمة المضافة) </span>
            <span><b>{{number_format($salebook->Total,2)}}</b></span>
        </div>

        <div style="display:flex;justify-content: space-between">
            <span>Discount</span> <span>مجموع الخصومات</span>
            <span><b>{{number_format($salebook->Discount,2)}}</b></span>
        </div>

        <div style="display:flex;justify-content: space-between">
            <span>Total (Taxable Amount Excluding VAT)</span> <span dir="rtl" lang="ar">الإجمالي الخاضع للضريبة (شاملة
                ضريبة القيمة المضافة)</span> <span><b>{{number_format($salebook->Total -
                    $salebook->Discount,2)}}</b></span>
        </div>

        <div style="display:flex;justify-content: space-between">
            <span>Total Vat</span> <span>مجموع ضريبة القيمة المضافة</span> <span><b> {{(number_format(($salebook->Total
                    - $salebook->Discount) * ($salebook->VATPercentage)/100,2))}}</b></span>
        </div>

        <div style="display:flex;justify-content: space-between">
            <span>Total Amount Due</span> <span>إجمالي المبلغ المستحق</span> <span><b id="finaltotal">
                    {{number_format((($salebook->Total - $salebook->Discount) + ((($salebook->Total -
                    $salebook->Discount) *
                    $salebook->VATPercentage)/100)),2)}} </b></span>
        </div>
        @elseif(Auth::user()->VAT_Calculation == 'After')

        <div style="display:flex;justify-content: space-between">
            <span style="text-align: center"> Total (Excluding Vat) </span> <span style="text-align: right" dir="rtl"
                lang="ar"> الإجمالي غير (شاملة ضريبة القيمة المضافة) </span>
            <span><b>{{number_format($salebook->Total,2)}}</b></span>
        </div>

        <div style="display:flex;justify-content: space-between">
            <span>Total (Taxable Amount Excluding VAT)</span> <span dir="rtl" lang="ar">الإجمالي الخاضع للضريبة (شاملة
                ضريبة القيمة المضافة)</span> <span><b>{{number_format($salebook->Total,2)}}</b></span>
        </div>

        <div style="display:flex;justify-content: space-between">
            <span>Total Vat</span> <span>مجموع ضريبة القيمة المضافة</span> <span><b> {{number_format(($salebook->Total *
                    $salebook->VATPercentage)/100,2)}}</b></span>
        </div>

        <div style="display:flex;justify-content: space-between">
            <span>Discount</span> <span>مجموع الخصومات</span>
            <span><b>{{number_format($salebook->Discount,2)}}</b></span>
        </div>

        <div style="display:flex;justify-content: space-between">
            <span>Total Amount Due</span> <span>إجمالي المبلغ المستحق</span> <span><b id="finaltotal">
                    {{number_format((($salebook->Total) + (($salebook->Total * $salebook->VATPercentage)/100) -
                    $salebook->Discount),2)}}
                </b></span>
        </div>

        @endif

    </div>
</div>


<div class="" style="margin-top: 1%">
    <div style="display: inline-block; width:100%;">
        <div style="border: 1px solid black;border-radius:10px;padding:10px;text-align:center;height:35px;}}">
            <div>
                <b>SAR:</b> {{$AmountInWords}} only
            </div>
            @if(Auth::user()->ArticleNo == 'RB' or Auth::user()->ArticleNo == 'KA' or Auth::user()->ArticleNo == 'WB')
            <div>
                <b dir="rtl" lang="ar"> :ريال سعودي &nbsp </b>
                <b><span dir="rtl" lang="ar" id="totalarabic"></span></b>

            </div>
            @endif

        </div>
    </div>
</div>
{!! $salebook->Warrenty !!}

<hr>
<div class="" style=" text-align:center">
    <div>
        <b>
            {{Auth::user()->Detail_English}}
        </b>
    </div>
    <div>
        <b>
            {{Auth::user()->Detail_Arabic}}

        </b>

    </div>
</div>



<input type="hidden" value="{{ $isPdf   }}" id="isPdf">
<input type="hidden" value="{{$salebook->Invoice}}" id="Invoice">
<script src="https://cdn.jsdelivr.net/gh/davidshimjs/qrcodejs@gh-pages/qrcode.min.js"></script>
<script type="text/javascript">
    var FileName=document.getElementById('Invoice').value;
    var isPdf = document.getElementById('isPdf').value;
    if (isPdf=='Y') {
        var opt = {
                    margin:       1,
                    filename:     FileName,
                    };
        html2pdf(document.body,opt);
        setTimeout(window.close, 2000);
    } else {
        window.onload = function() {
        var tt = window.print();
        setTimeout(window.close, 0);

        }
        
    }

</script>
<script src="https://cdn.jsdelivr.net/npm/number-to-arabic-words@latest/dist/index.js"></script>
<script type="text/javascript">
    var number = document.getElementById('finaltotal').innerText
    arabicWord.setConfig({
        delimiter: 'ريال ',
    })
    if (String(number).slice(-2) != '00') {
        document.getElementById('totalarabic').innerText = toArabicWord(number) + ' هللة'
    } else {
        document.getElementById('totalarabic').innerText = toArabicWord(number)
    }
</script>
@endsection