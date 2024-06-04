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
            VAT Sale Report
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
                            <th># </th>
                            <th>Date </th>
                            <th>Invoice Number </th>
                            <th>Total </th>
                            <th>VAT Amount </th>
                            <th>Discount </th>
                            <th>Final Total</th>
                            <th>Cash </th>

                        </tr>
                    </thead>
                    <tbody>
                        @php $Total = 0; $VATPercentageValue = 0; $Discount = 0; $FinalTotal = 0;
                        $Cash = 0;
                        @endphp
                        @for($a=0; $a < count($salebook);$a++) @php $Total=$Total + $salebook[$a]->Total;

                            $VATPercentageValue = $VATPercentageValue + $salebook[$a]->VATPercentageValue;
                            $Discount = $Discount + $salebook[$a]->Discount;
                            $FinalTotal = $FinalTotal + $salebook[$a]->FinalTotal;
                            $Cash = $Cash + $salebook[$a]->Cash;

                            @endphp
                            <tr>
                                <td>{{$a+1}}</td>
                                <td>{{$salebook[$a]->Date}}</td>
                                <td>{{$salebook[$a]->Invoice}}</td>
                                <td>{{$salebook[$a]->Total}}</td>
                                <td>{{$salebook[$a]->VATPercentageValue}}</td>
                                <td>{{$salebook[$a]->Discount}}</td>
                                <td>{{$salebook[$a]->FinalTotal}}</td>
                                <td>{{$salebook[$a]->Cash}}</td>
                                <td></td>
                            </tr>
                            @endfor
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <th>{{$Total}}</th>
                                <th>{{$VATPercentageValue}}</th>
                                <th>{{$Discount}}</th>
                                <th>{{$FinalTotal}}</th>
                                <th>{{$Cash}}</th>

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