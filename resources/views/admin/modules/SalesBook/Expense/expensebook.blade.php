@extends('admin/layouts/mainlayout')
@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <h1>Expense Book</h1>
    </section>

    <section class="content ">
        <div id="show_insert_status">

        </div>
        <div class="container-fluid">
            <div class="row ">
                <!-- left column -->
                <div class="box box-default">
                    <div class="box-body">

                        <div class="row">
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label>Date</label>
                                        <input class="form-control" style="padding: 0;width: 100%;height: 30px;font-size: 14px;" id="Date" name="date" type="date">
                                    </div>
                                    <div class="col-md-3">
                                        <label>Invoice</label>
                                        <input readonly required style="padding: 0;width: 100%;height: 30px;font-size: 14px;" id="Invoice" name="Invoice" type="text">
                                    </div>

                                    <div class="col-md-3">

                                        <label>Expense From Account</label>
                                        <select name="ExpenseFromAccount" id="ExpenseFromAccount" class="control-form select2" style="width: 100%;height:30px">
                                            <option selected disabled value="">Choose Account</option> 
                                            @foreach($expense_from_account as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach  
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label>Expense Of</label>
                                        <select  name="ExpenseOf" id="ExpenseOf" class="control-form select2" style="width: 100%;height:30px" >
                                            <option selected disabled value="">Expense Of</option>
                                           @foreach ($chart_of_accounts as $item)
                                               <option value="{{$item->id}}">{{$item->name}}</option>
                                           @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label>Amount</label>
                                        <input type="number" name="Amount" id="Amount" class="control-form " style="width: 100%;height:30px" required>

                                    </div>
                                    <div class="col-md-3">
                                        <label>Description</label>
                                        <input name="Description" id="Description" class="control-form " style="width: 100%;height:30px" required>

                                    </div>
                                    <div class="col-md-3">
                                        <button style="margin-top: 22px;" class="btn btn-primary" onclick="dispatch()">Submit</button>
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
  
    // function for making invoice
    makeInvoice();

    function makeInvoice() {
        $.ajax({
            url: '/getInvoice_expense',
            type: "get",
            success: function(data) {
                document.getElementById('Invoice').value = data;
            }
        })
    }


   

    todatDate();

    function dispatch(parameter) {
        var Date = document.getElementById('Date').value;
        var Invoice = document.getElementById('Invoice').value;
        var ExpenseFromAccount = document.getElementById('ExpenseFromAccount').value;
        var ExpenseOf = document.getElementById('ExpenseOf').value;
        var Amount = document.getElementById('Amount').value;
        var Description = document.getElementById('Description').value;
       
        if (Invoice != '' && Amount != '') {
            var token = '{{csrf_token()}}';
            // console.log(obj)
            $.ajax({
                type: "post",
                url: "/dispatch_expensebook",
                data: {
                    Date: Date,
                    Invoice: Invoice,
                    ExpenseFromAccount: ExpenseFromAccount,
                    ExpenseOf:ExpenseOf,
                    Amount: Amount,
                    Description: Description,
                    _token: token
                },

                success: function(data) {
                    document.getElementById('Invoice').value = data.NewInvoice;
                    // console.log(document.getElementById('Invoice').value);

                        if (data.status == "inserted") {


                            var output = `
                        <div class="alert alert-success">
                        <button class="close" style="font-size: 30px;" data-dismiss="alert">&times</button>
                        Saved Successfuly
                        </div>    
                                `;
                        document.getElementById('show_insert_status').innerHTML = output;

                        document.getElementById('Amount').value = '';
                        document.getElementById('Description').value = '';

                        } else {

                            var output = `
                        <div class="alert alert-danger">
                        <button class="close" style="font-size: 30px;" data-dismiss="alert">&times</button>
                         Not Saved
                         </div>   
                            `;
                            document.getElementById('show_insert_status').innerHTML = output;
                        }


                },
                error: function(req, status, error) {
                    console.log(error);
                    var output = `
                    <div class="alert alert-danger">
                    <button class="close" style="font-size: 30px;" data-dismiss="alert">&times</button>
                     Not Inserted
                     </div>
                                `;
                    document.getElementById('show_insert_status').innerHTML = output;
                }



            });



            todatDate();

        }



    }

    function todatDate() {
        var now = new Date();
        var day = ("0" + now.getDate()).slice(-2);
        var month = ("0" + (now.getMonth() + 1)).slice(-2);
        var today = now.getFullYear() + "-" + (month) + "-" + (day);
        $('#Date').val(today);
    }

    var Percent = document.getElementById("Price");
    Percent.addEventListener("keydown", function(e) {
        if (e.key === "Enter") {
            addRow();
        }
    });

    var qty = document.getElementById("Qty");
    qty.addEventListener("keydown", function(e) {
        if (e.key === "Enter") {
            addRow();
        }
    });
    var qty = document.getElementById("Total");
    qty.addEventListener("keydown", function(e) {
        if (e.key === "Enter") {
            addRow();
        }
    });


    $(document).keypress(function(event) {
        if (event.keyCode == 33) {
            var a = "save";
            dispatch('save')
        }
        if (event.keyCode == 64) {
            var a = "bill";
            Save(a);
        }
    });
</script>



@endsection