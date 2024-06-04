@extends('admin/layouts/reportlayout')
@section('content')
<script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>

<div class="container-fluid">
    <!-- Main content -->
    <section id="tbl_exporttable_to_xls">

        <h2 style="text-align:center">
            <span> {{Auth::user()->CompanyName}} </span>
            <span> {{Auth::user()->CompanyNameArabic}} </span>
            <br> VAT N0.
            <span> {{Auth::user()->VATNO}} </span>
            <span> {{Auth::user()->VATNO_Arabic}} </span>


        </h2> <br>



        <h2 class="page-header" style="text-align:center">
            VAT Report
            <span class="pull-left"> <button type="button" class="btn btn-primary" onclick="ExportToExcel('xlsx')">Export Excel</button> </span>
            <span class="pull-right"> <button type="button" class="btn btn-primary" onclick="window.print();">Print</button></span>
            <small>Date : From: ( {{$startDate}} ) To: ({{$endDate}} )</small>
        </h2>
        <div class="row">
            <div class="col-xs-12 table-responsive">
                <table class="table table-condensed table-bordered">
                    <thead>
                        <tr>
                            <th colspan="7">Sale</th>
                        </tr>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Invoice</th>
                            <th>VAT No.</th>
                            <th>Total</th>
                            <th>VAT %</th>
                            <th>VAT Amount</th>
                            <th>Discount</th>
                            <th>Final Total</th>
                            <th>Cash</th>

                        </tr>
                    </thead>
                    <tbody>
                        @php $Total = 0; $VATPercentage = 0; $VATPercentageValue = 0; $Discount = 0; $FinalTotal = 0; $Cash = 0;
                        @endphp
                        @for($a=0; $a < count($salebook);$a++) @php $Total=$Total + $salebook[$a]->Total;
                            $VATPercentage = $VATPercentage + $salebook[$a]->VATPercentage;
                            $VATPercentageValue = $VATPercentageValue + $salebook[$a]->VATPercentageValue;
                            $Discount = $Discount + $salebook[$a]->Discount;
                            $FinalTotal = $FinalTotal + $salebook[$a]->FinalTotal;
                            $Cash = $Cash + $salebook[$a]->Cash;

                            @endphp
                            <tr>
                                <td>{{$a+1}}</td>
                                <td>{{$salebook[$a]->Date}}</td>
                                <td>{{$salebook[$a]->Invoice}}</td>
                                <td>{{Auth::user()->VATNO}}</td>
                                <td>{{$salebook[$a]->Total}}</td>
                                <td>{{$salebook[$a]->VATPercentage}}</td>
                                <td>{{$salebook[$a]->VATPercentageValue}}</td>
                                <td>{{$salebook[$a]->Discount}}</td>
                                <td>{{$salebook[$a]->FinalTotal}}</td>
                                <td>{{$salebook[$a]->Cash}}</td>

                            </tr>
                            @endfor
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>{{$Total}}</td>
                                <td></td>
                                <td>{{$VATPercentageValue}}</td>
                                <th>{{$Discount}}</th>
                                <th>{{$FinalTotal}}</th>
                                <th>{{$Cash}}</th>

                            </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 table-responsive">
                <table class="table table-condensed table-bordered">
                    <thead>
                        <tr>
                            <th colspan="7">Purchase</th>
                        </tr>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Purchase Invoice</th>
                            <th>Purchaser Name</th>
                            <th>Purchase Address</th>
                            <th>VAT No.</th>
                            <th>VAT %</th>
                            <th>VAT Amount</th>
                            <th>Discount</th>
                            <th>Final Total</th>


                        </tr>
                    </thead>
                    <tbody>
                        @php $VATPercentage2 = 0; $VATPercentageValue2 = 0; $Discount2 = 0; $FinalTotal2 = 0;
                        @endphp
                        @for($b=0; $b < count($purchasebook);$b++) @php $VATPercentage2=$VATPercentage2 + $purchasebook[$b]->VATPercentage;
                            $VATPercentageValue2 = $VATPercentageValue2 + $purchasebook[$b]->VATPercentageAmount;
                            $Discount2 = $Discount2 + $purchasebook[$b]->Discount;
                            $FinalTotal2 = $FinalTotal2 + $purchasebook[$b]->FinalTotal;

                            @endphp
                            <tr>
                                <td>{{$b+1}}</td>
                                <td>{{$purchasebook[$b]->Date}}</td>
                                <td>{{$purchasebook[$b]->PurchaseInvoice}}</td>
                                <td>{{$purchasebook[$b]->PurchaserName}}</td>
                                <td>{{$purchasebook[$b]->PurchaserAddress}}</td>
                                <td>{{$purchasebook[$b]->VATNumber}}</td>
                                <td>{{$purchasebook[$b]->VATPercentage}}</td>
                                <td>{{$purchasebook[$b]->VATPercentageAmount}}</td>
                                <td>{{$purchasebook[$b]->Discount}}</td>
                                <td>{{$purchasebook[$b]->FinalTotal}}</td>
                            </tr>
                            @endfor
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <th></th>
                                <th>{{$VATPercentageValue2}}</th>
                                <th>{{$Discount2}}</th>
                                <th>{{$FinalTotal2}}</th>
                            </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<script>
    function ExportToExcel(type, fn, dl) {
        var elt = document.getElementById('tbl_exporttable_to_xls');
        var wb = XLSX.utils.table_to_book(elt, {
            sheet: "sheet1"
        });
        return dl ?
            XLSX.write(wb, {
                bookType: type,
                bookSST: true,
                type: 'base64'
            }) :
            XLSX.writeFile(wb, fn || ('MySheetName.' + (type || 'xlsx')));
    }
    //     window.onload = function()
    // {
    // 	var tt = window.print();
    // 	setTimeout(window.close, 0);

    // }
</script>