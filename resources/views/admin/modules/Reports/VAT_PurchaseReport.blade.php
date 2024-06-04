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
            VAT Purchase Report
            <span class="pull-left"> <button type="button" class="btn btn-primary" onclick="ExportToExcel('xlsx')">Export Excel</button> </span>
            <span class="pull-right"> <button type="button" class="btn btn-primary" onclick="window.print();">Print</button></span>
            <small>Date : From: ( {{$startDate}} ) To: ({{$endDate}} )</small>
        </h2>

        <div class="row">
            <div class="col-xs-12 table-responsive">
                <table class="table table-condensed table-bordered">
                    <thead>
                        <tr>
                            <th colspan="7">Purchase</th>
                        </tr>
                        <tr>
                            <th># </th>
                            <th>Date </th>
                            <th>Purchase Invoice </th>
                            <th>Purchaser Name </th>
                            <th>Purchase Address </th>
                            <th>VAT Amount</th>
                            <th>Discount </th>
                            <th>Final Total</th>


                        </tr>
                    </thead>
                    <tbody>
                        @php $VATPercentageValue2 = 0; $Discount2 = 0; $FinalTotal2 = 0;
                        @endphp
                        @for($b=0; $b < count($purchasebook);$b++) @php $VATPercentageValue2=$VATPercentageValue2 + $purchasebook[$b]->VATPercentageAmount;
                            $Discount2 = $Discount2 + $purchasebook[$b]->Discount;
                            $FinalTotal2 = $FinalTotal2 + $purchasebook[$b]->FinalTotal;

                            @endphp
                            <tr>
                                <td>{{$b+1}}</td>
                                <td>{{$purchasebook[$b]->Date}}</td>
                                <td>{{$purchasebook[$b]->PurchaseInvoice}}</td>
                                <td>{{$purchasebook[$b]->PurchaserName}}</td>
                                <td>{{$purchasebook[$b]->PurchaserAddress}}</td>
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
</script>