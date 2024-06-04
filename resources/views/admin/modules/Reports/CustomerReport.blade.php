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
        {{-- <h3>
            <span class="pull-left"> <button type="button" class="btn btn-primary" onclick="ExportToExcel('xlsx')">Export Excel</button> </span>
            <span class="pull-right"> <button type="button" class="btn btn-primary" onclick="window.print();">Print</button></span>
        </h3> --}}
        <h2 style="text-align:center"> <span>Customer Report</span>  </h2>
        <h3 style="text-align:center"><span>Name:{{$CustomerName}}</span> </h3>
        <br>
        <div class="row">
            <div class="col-xs-12 table-responsive">
                <table class="table table-condensed table-bordered">
                    <thead>
                        <tr>
                            <th colspan="1" style="text-align:center"><button type="button" id="Excelbtn" class="btn btn-primary" onclick="ExportToExcel('xlsx')">Export Excel</button></th>
                            <th colspan="7" style="text-align:center"><span>Dated: ( {{$startDate}} )--({{$endDate}} )</span></th>
                            <th colspan="1" style="text-align:center"><button type="button" id="Printbtn" class="btn btn-primary" onclick="window.print();">Print</button></th>
                        </tr>
                        <tr>
                            <th> <span># No.  </th>
                            <th> <span>Date </th>
                            <th> <span>Invoice Number </th>
                            <th> <span>Discount </th>
                            <th> <span>Amount  </th>
                            <th> <span>VAT Amount  </th>
                            <th> <span>Gross Total  </th>
                            <th> <span>Customer Name  </th>
                            <th><span>Payment Type </th>
                            

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
                                <td>{{$salebook[$a]->Date}}</td>
                                <td>{{$salebook[$a]->Invoice}}</td>
                                <td>{{$salebook[$a]->Discount}}</td>
                                <td>{{$salebook[$a]->Total}}</td>
                                <td>{{$salebook[$a]->VATPercentageValue}}</td>
                                <td>{{$salebook[$a]->FinalTotal}}</td>
                                <td>{{$salebook[$a]->CustomerName}}</td>
                                <td>{{$salebook[$a]->PaymentType}}</td>
                                
                            </tr>
                            @endfor
                            <tr>
                                <td></td>
                                <td></td>
                                <th></th>
                                <td>{{ $Discount }}</td>
                                <th>{{$Total}}</th>
                                <th>{{$VATPercentageValue}}</th>
                                <th>{{$FinalTotal}}</th>
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