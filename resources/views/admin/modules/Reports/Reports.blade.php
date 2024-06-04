@extends('admin/layouts/mainlayout')
@section('content')
<style>
    html {
        scroll-behavior: smooth;
    }
</style>
<div class="content-wrapper">
    <section class="content-header">
        <h1>Reports</h1>

    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="box box-default">
                    <div class="box-body">
                        <div class="col-md-2">

                            <label>Date From :</label>
                            <input type="date" name="startDate" id="startDate" class="form-control">

                        </div>
                        <div class="col-md-2">

                            <label>Date To :</label>
                            <input type="date" name="endDate" id="endDate" required class="form-control">

                        </div>

                        <div class="col-md-2">
                            <div class="row">
                                <div class="col-xs-4 col-md-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="exampleRadios" id="yearly" value="yearly">
                                        <label class="form-check-label" for="yearly">
                                            Yearly
                                        </label>
                                    </div>
                                </div>

                                <div class="col-xs-4 col-md-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="exampleRadios" id="monthly" value="monthly">
                                        <label class="form-check-label" for="monthly">
                                        Monthly
                                        </label>
                                    </div>
                                </div>

                                <div class="col-xs-4 col-md-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" onclick="focusOnCustomeDate()" name="exampleRadios" id="custome" value="custome">
                                        <label class="form-check-label" for="custome">
                                        Custom
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-4">

                            <label>Customer Name:</label>
                            <select  name="CustomerName" id="CustomerName" class="form-control select2">
                                <option value="">Choose CustomerName...</option>
                                @foreach($Customers as $Customer)
                                <option>{{$Customer->CustomerName}}</option>
                                @endforeach
                            </select>

                        </div>

                        

                    </div>
                </div>

                <div class="box box-default">
                    <div style="text-align:center;" class="box-header with-border">

                        <h3 class="box-title"><strong>Reports</strong> </h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <form id="formId" method="post" target="_blank">
                            @csrf

                            <input type="hidden" name="startDate">
                            <input type="hidden" name="endDate">
                            <input type="hidden" name="CustomerName">

                        </form>
                        <div class="row">

                            <div class="form-group col-md-3">

                                <button style="width:100%" class="btn btn-primary" onclick="getStartAndEndingDates('VAT_Report','onlyDate')">
                                VAT Report</button>
                            </div>
                            <div class="form-group col-md-3">

                                <button style="width:100%" class="btn btn-primary" onclick="getStartAndEndingDates('VAT_SaleReport','onlyDate')">
                                VAT Sale Report</button>
                            </div>
                            <div class="form-group col-md-3">

                                <button style="width:100%" class="btn btn-primary" onclick="getStartAndEndingDates('VAT_PurchaseReport','onlyDate')">
                                VAT Purchase Report</button>
                            </div>
                            <div class="form-group col-md-3">

                                <button style="width:100%" class="btn btn-primary" onclick="getStartAndEndingDates('TotalReport','onlyDate')">
                                Total Report</button>
                            </div>

                            <div class="form-group col-md-3">

                                <button style="width:100%" class="btn btn-primary" onclick="getStartAndEndingDates('ExpenseReport','onlyDate')">
                                Expense Report</button>

                            </div>
                            <div class="form-group col-md-3">

                                <button style="width:100%" class="btn btn-primary" onclick="getStartAndEndingDates('CustomerReport','onlyDate')">
                                Customer Report</button>

                            </div>

                          

                        </div>


                    </div>

                </div>

            </div>

        </div>

</div>

</section>



</div>


<script>
    var now = new Date();
    now.toLocaleString('en-US', {
                timeZone: 'Asia/Riyadh'
            });
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear() + "-" + (month) + "-" + (day);
    $('#startDate').val(today);
    $('#endDate').val(today);

    // this is code for dispatch report selection end

    function getStartAndEndingDates(id, value) {

        var startDate = '';
        var endDate = '';
        if (document.getElementById('yearly').checked) {
            var now = new Date();
            var year = now.getFullYear();
            startDate = year + '-01-01';
            endDate = year + '-12-31';
        } else if (document.getElementById('monthly').checked) {
            var day = 0;
            var now = new Date();
            var year = now.getFullYear();
            var month = ("0" + (now.getMonth() + 1)).slice(-2);
            if (month == 01 || month == 03 || month == 05 || month == 07 || month == 08 || month == 10 || month == 12) {
                day = 31;
            } else if (month == 04 || month == 06 || month == 09 || month == 11) {
                day = 30;
            } else if (month == 02) {
                var now = new Date();
                var year = now.getFullYear();
                var isLeap = new Date(year, 1, 29).getMonth() == 1;
                if (isLeap) {
                    day = 29;
                } else {
                    day = 28;
                }
            }
            startDate = year + "-" + month + '-01';
            endDate = year + "-" + month + "-" + day;
        } else if (document.getElementById('custome').checked) {
            document.getElementById('startDate').focus();
            startDate = document.getElementById('startDate').value;
            endDate = document.getElementById('endDate').value;
        } else {
            if (value == 'withTime') {
                startDate = document.getElementById('startDate_withTime').value;
                endDate = document.getElementById('endDate_withTime').value;
            } else if (value == 'onlyDate') {
                startDate = document.getElementById('startDate').value;
                endDate = document.getElementById('endDate').value;
            }

        }


        document.getElementById('formId').action = '/' + id;
        const x = document.forms["formId"];
        var CustomerName = document.getElementById('CustomerName').value;

        x.elements[1].value = startDate;
        x.elements[2].value = endDate;
        x.elements[3].value = CustomerName;



        var AC = x.elements[1].value;
        var CN = x.elements[2].value;

        if (AC && CN) {
            if (id == 'CustomerReport') {
                if (CustomerName == '') {
                    alert("please select Customer Name");
                    return false;
                }
            }
            document.getElementById('formId').submit()
        }

    }

    function getHiddenValues(id) {
        getStartAndEndingDates(id);
    }
</script>

@endsection