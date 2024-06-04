@extends('admin/layouts/mainlayout')
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Edit Purchase Invoice</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div style="padding:20px" class="box box-default">
                    <!-- <form method="get" id="formId"  target="_blank"> -->

                    <div class="row">

                        <div class="col-md-3">

                            <div class="form-group">
                                <label>Date From</label>
                                <input type="date" name="startDate" id="startDate" required class="form-control">
                            </div>

                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Date To</label>
                                <input type="date" name="endDate" id="endDate" required class="form-control">
                            </div>
                        </div>


                    </div>

                    <div class="row">
                        <div class="col-md-2">
                            <div style="margin-top: 0px;" class="form-group">
                                <button onclick="getInvoices('Invoices')" class="btn btn-primary">
                                Submit
                                </button>
                                <!-- <button onclick="getInvoicesForEdit()" class="btn btn-info">Open Invoices</button> -->
                            </div>

                        </div>

                    </div>
                    <!-- </form> -->
                </div>
            </div>
        </div>
    </section>
</div>
<script>
  
    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear() + "-" + (month) + "-" + (day);
    document.getElementById('startDate').value = today;
    document.getElementById('endDate').value = today;
    document.getElementById('AccountHead').value = document.getElementById('AccountHeadValue').innerText;
    document.getElementById('CompanyName').value = document.getElementById('AccountHeadCompanyName').innerText;

    function getInvoices(parameter) {
        var startDate = document.getElementById('startDate').value;
        var endDate = document.getElementById('endDate').value;
       
        var QueryString = "?startDate=" + startDate + "&endDate=" + endDate;
       
        window.open('getPurchaseInvoicesForEdit' + QueryString + "&status=" + parameter, '_blank')
    }
</script>

@endsection