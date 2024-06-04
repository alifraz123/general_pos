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
        <h2 style="text-align:center">
            VAT Report 
            <span class="pull-left"> <button type="button" id="Printbtn" class="btn btn-primary"
                    onclick="ExportToExcel('xlsx')">Export Excel</button> </span>
            <span class="pull-right"> <button type="button" id="Printbtn" class="btn btn-primary"
                    onclick="window.print();">Print</button></span>
            <br>
            <small>Date : From: (<span id="startDate"></span>) To: (<span id="endDate"></span>) </small>
        </h2> <br>
        <div class="row">
            <div class="col-xs-12 table-responsive">
                <table class="table table-condensed table-bordered">
                    <thead>
                        <tr>
                            <th colspan="7">Total Report</th>
                        </tr>
                        <tr>

                            <th>Description</th>
                            <th>Amount </th>
                            <th>VAT Amount </th>
                            <th>Total  </th>
                        </tr>
                    </thead>
                    <tbody>

                        <tr>

                            <td>Sales<span style="float: right;font-weight:bold">مبيعات</span>
                            </td>

                            <td>{{$sale_amount}}</td>
                            <td>{{$sale_vat}}</td>
                            <td>{{$sale_total}}</td>


                        </tr>
                        <tr>

                            <td>Purchases<span style="float: right;font-weight:bold"></span></td>
                            <td>{{$purchase_amount}}</td>
                            <td>{{$purchase_vat}}</td>
                            <td>{{$purchase_total}}</td>


                        </tr>

                        <tr>

                            <td>Total Payable VAT<span style="float: right;font-weight:bold">                                    المستحقة</span></td>
                            <th>SAR {{$sale_amount - $purchase_amount}}  </th>
                            <th>SAR {{$sale_vat - $purchase_vat}} </th>
                            <th>SAR {{$sale_total - $purchase_total}} </th>


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