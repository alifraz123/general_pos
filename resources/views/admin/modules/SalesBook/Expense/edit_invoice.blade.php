@extends('admin/layouts/mainlayout')
@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <h1>Edit Expense Invoice</h1>

    </section>
    <section class="content">
        <div id="show_insert_status">

        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="box box-default">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-10">
                                <div class="row">
                                    <input type="hidden" value="{{$expensebook->Invoice}}" id="invoice_edit">
                                    <div class="col-md-3">
                                        <label>Date</label>
                                        <input class="form-control" value="{{$expensebook->Date}}" style="padding: 0;width: 100%;height: 30px;font-size: 14px;" id="Date" name="date" type="date">
                                    </div>
                                    <div class="col-md-3">
                                        <label>Invoice</label>
                                        <input readonly required value="{{$expensebook->Invoice}}" style="padding: 0;width: 100%;height: 30px;font-size: 14px;" id="Invoice" name="Invoice" type="text">
                                    </div>

                                    <div class="col-md-3">

                                        <label>Expense From Account</label>
                                        <select name="ExpenseFromAccount" id="ExpenseFromAccount" class="control-form select2" style="width: 100%;height:30px">
                                            <option selected disabled value="">Choose Account</option> 
                                            @foreach($expense_from_account as $item)
                                            <option value="{{$item->id}}" {{$expensebook->ExpenseFromAccount == $item->id ? 'selected' : ''}}>{{$item->name}}</option>
                                            @endforeach  
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label>Expense Of</label>
                                        <select  name="ExpenseOf" id="ExpenseOf" class="control-form select2" style="width: 100%;height:30px" >
                                            <option selected disabled value="">Expense Of</option>
                                           @foreach ($chart_of_accounts as $item)
                                               <option value="{{$item->id}}" {{$expensebook->chart_of_account_id == $item->id ? 'selected' : ''}}>{{$item->name}}</option>
                                           @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label>Amount</label>
                                        <input type="number" name="Amount" id="Amount" value="{{$expensebook->Amount}}" class="control-form " style="width: 100%;height:30px" required>

                                    </div>
                                    <div class="col-md-3">
                                        <label>Description</label>
                                        <input name="Description" id="Description" value="{{$expensebook->Description}}" class="control-form " style="width: 100%;height:30px" required>

                                    </div>
                                    <div class="col-md-3">
                                        <button style="margin-top: 22px;" class="btn btn-primary" onclick="update_dispatch()">Submit</button>
                                    </div>


                                </div>

                                <!-- Add Product Detail -->

                            </div>

                        </div>

                    </div>

                </div>
            </div>
        </div>

</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<script>
    $(".loader").hide()

    function update_dispatch(parameter) {

        var Date = document.getElementById('Date').value;
        var ExpenseFromAccount = document.getElementById('ExpenseFromAccount').value;
        var Amount = document.getElementById('Amount').value;
        var Description = document.getElementById('Description').value;
        let ExpenseOf = document.getElementById('ExpenseOf').value;


        if (Date != '' && Amount != '') {

            var token = '{{csrf_token()}}';
            $.ajax({
                type: "post",
                url: "../update_dispatch_expensebook",
                data: {

                    Date: Date,
                    ExpenseFromAccount: ExpenseFromAccount,
                    ExpenseOf: ExpenseOf,
                    Amount: Amount,
                    Description: Description,
                    invoice_edit: document.getElementById('invoice_edit').value,
                    _token: token
                },
                dataType: "text",
                beforeSend: function() {
                    $(".loader").show();
                },

                success: function(data) {
                    // console.log("returned data is :" + data);
                    if (parameter == "print") {
                        var Invoice = JSON.parse(data).Invoice;
                        window.open("/printInvoice?Invoice=" + Invoice, '_blank');
                        window.close()
                    } else {

                        if (JSON.parse(data).status == "inserted") {
                            sessionStorage.setItem('update', 'yes')
                            window.history.back()
                            var output = `
                        <div class="alert alert-success">
                        <button class="close" style="font-size: 30px;" data-dismiss="alert">&times</button>
                        Updated Successfuly
                        </div>    
                        `;
                            document.getElementById('show_insert_status').innerHTML = output;

                            $(".loader").hide();
                            window.close()
                        } else {
                            $(".loader").show();
                            var output = `
                        <div class="alert alert-danger">
            <button class="close" style="font-size: 30px;" data-dismiss="alert">&times</button>
            Not Updated
        </div>   
                                `;
                            document.getElementById('show_insert_status').innerHTML = output;
                        }

                    }





                },
                error: function(req, status, error) {
                    console.log(error);
                    var output = `
                                <div class="alert alert-danger">
            <button class="close" style="font-size: 30px;" data-dismiss="alert">&times</button>
            Something Went Wrong !
        </div>
                                `;
                    document.getElementById('show_insert_status').innerHTML = output;
                }
            });

        }

    }


</script>



@endsection