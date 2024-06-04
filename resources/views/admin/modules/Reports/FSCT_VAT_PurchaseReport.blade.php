@extends('admin/layouts/reportlayout')
@section('content')
<style>
    @media print {
        #Excelbtn {
            display: none;
        }

        #Printbtn {
            display: none;
        }
    }
</style>
<script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>

<div class="container-fluid">
    <!-- Main content -->
    <section id="tbl_exporttable_to_xls">
        <h2 style="text-align:center">
            <span> {{Auth::user()->CompanyName}} </span>
            <br> VAT N0.
            <span> {{Auth::user()->VATNO}} </span>


        </h2> <br>
        <h2 class="page-header" style="text-align:center">
            VAT Purchase Report
            <span class="pull-left"> <button type="button" id="Excelbtn" class="btn btn-primary"
                    onclick="ExportToExcel('xlsx')">Export Excel</button> </span>
            <span class="pull-right"> <button type="button" id="Printbtn" class="btn btn-primary"
                    onclick="window.print();">Print</button></span>
            <br>
            <small>Date : From: (<span id="startDate"></span>) To: (<span id="endDate"></span>) </small>
        </h2>

        <div class="row">
            <div class="col-xs-12 table-responsive">
                <table class="table table-condensed table-bordered">
                    <thead>
                        <tr>
                            <th colspan="7">Purchase</th>
                        </tr>
                        <tr>
                            <th># No. </th>
                            <th>Invoice Number  </th>
                            <th>Date </th>
                            <th>Amount </th>
                            <th>VAT Amount </th>
                            <th>Gross Total </th>
                            <th>Purchaser Name </th>
                            <th>Purchaser VAT No. </th>
                            <th>Total Qty </th>

                        </tr>
                    </thead>
                    <tbody>
                        @php $VATPercentageValue2 = 0; $Discount2 = 0; $FinalTotal2 = 0;$Total = 0;
                        @endphp
                        @for($b=0; $b < count($purchasebook);$b++) @php $VATPercentageValue2=$VATPercentageValue2 +
                            $purchasebook[$b]->VATPercentageAmount;
                            $Discount2 = $Discount2 + $purchasebook[$b]->Discount;
                            $FinalTotal2 = $FinalTotal2 + $purchasebook[$b]->FinalTotal;
                            $Total = $Total + $purchasebook[$b]->Total;

                            @endphp
                            <tr>
                                <td>{{$b+1}}</td>
                                <td>{{$purchasebook[$b]->PurchaseInvoice}}</td>
                                <td>{{$purchasebook[$b]->Date}}</td>
                                <td>{{$purchasebook[$b]->Total}}</td>
                                <td>{{$purchasebook[$b]->VATPercentageAmount}}</td>
                                <td>{{$purchasebook[$b]->FinalTotal}}</td>
                                <td>{{$purchasebook[$b]->PurchaserName}}</td>
                                <td>{{$TotalQtyArray[$b]}}</td>
                            </tr>
                            @endfor
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>

                                <td>{{$Total}}</td>
                                <th>{{$VATPercentageValue2}}</th>
                                <th>{{$FinalTotal2}}</th>
                                <td></td>

                                <th></th>
                            </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<script>
    let startDate = @json($startDate);
    let endDate = @json($endDate);
    document.getElementById('startDate').innerText=formatDate(startDate);
    document.getElementById('endDate').innerText=formatDate(endDate);
    function formatDate(dateString) {
        const [year, month, day] = dateString.split("-");
        const date = new Date(year, month - 1, day);
        const formattedDate = new Intl.DateTimeFormat("en-US", {
            year: "numeric",
            month: "2-digit",
            day: "2-digit"
        }).format(date);
        return formattedDate;
    }
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