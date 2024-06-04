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
            VAT Sale Report
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
                            <th colspan="7">Sale</th>
                        </tr>
                        <tr>
                            <th># No.</th>
                            <th>Invoice Number </th>
                            <th>Discount </th>
                            <th>Date </th>
                            <th>Amount </th>
                            <th>VAT Amount </th>
                            <th>Gross Total </th>
                            <th>Customer Name </th>
                            <th>Customer VAT No. </th>
                            <th>Payment Type </th>
                            <th>Total Qty </th>

                        </tr>
                    </thead>
                    <tbody>
                        @php $Total = 0; $VATPercentageValue = 0; $Discount = 0; $FinalTotal = 0;
                        @endphp
                        @for($a=0; $a < count($salebook);$a++) @php $Total=$Total + $salebook[$a]->Total;

                            $VATPercentageValue = $VATPercentageValue + $salebook[$a]->VATPercentageValue;
                            $Discount = $Discount + $salebook[$a]->Discount;
                            $FinalTotal = $FinalTotal + $salebook[$a]->FinalTotal;

                            @endphp
                            <tr>
                                <td>{{$a+1}}</td>
                                <td>{{$salebook[$a]->Invoice}}</td>
                                <td>{{$salebook[$a]->Discount}}</td>
                                <td>{{$salebook[$a]->Date}}</td>
                                <td>{{$salebook[$a]->Total}}</td>
                                <td>{{$salebook[$a]->VATPercentageValue}}</td>
                                <td>{{$salebook[$a]->FinalTotal}}</td>
                                <td>{{$salebook[$a]->CustomerName}}</td>
                                <td>{{$salebook[$a]->VATNO}}</td>
                                <td>{{$salebook[$a]->PaymentType}}</td>
                                <td>{{$TotalQtyArray[$a]}}</td>
                            </tr>
                            @endfor
                            <tr>
                                <td></td>
                                <td></td>
                                <th>{{$Discount}}</th>
                                <td></td>
                                <th>{{$Total}}</th>
                                <th>{{$VATPercentageValue}}</th>
                                <th>{{$FinalTotal}}</th>
                                <th></th>
                                <th></th>
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