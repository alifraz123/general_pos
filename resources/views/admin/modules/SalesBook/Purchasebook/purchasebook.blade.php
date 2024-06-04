@extends('admin/layouts/mainlayout')
@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <h1>Purchase Book</h1>
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

                                        <label>Purchase Invoice</label>
                                        <input name="PurchaseInvoice" id="PurchaseInvoice" class="control-form" style="width: 100%;height:30px">

                                    </div>
                                   
                                    <div class="col-md-3">
                                        <label>Purchaser</label>
                                        <select onchange="getPurchaserData(this.value)" name="PurchaserName"
                                        id="PurchaserName" class="control-form select2"
                                        style="width: 100%;height:30px">
                                            <option value="" selected disabled>Choose Purchaser...</option>

                                            @foreach($purchasers as $Purchaser)
                                            <option value="{{$Purchaser->id}}">{{$Purchaser->PurchaserName}}</option>
                                            @endforeach
                                    </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Purchaser Address</label>
                                        <input name="PurchaserAddress" id="PurchaserAddress" readonly class="control-form " style="width: 100%;height:30px" required>

                                    </div>


                                </div>
                                <div style="display:flex" class="">
                                    <div style="width: 45%;">
                                        <label>Category</label>
                                        <br>
                                        <select name="Category" onchange="getItemsOfSelectedCategory(this.value)"
                                            id="Category" style="width: 100%;" required class="select2">
                                        </select>
                                    </div>
                                  
                                </div>

                                <!-- Add Product Detail -->

                                <div class="row">

                                    <div style="overflow-x:auto" class="col-xs-12 col-md-8">
                                        <table id="tablerows">
                                            <thead>
                                                <tr>
                                                    <th style="width: 50%;">Item Name</th>
                                                    <th>Qty</th>
                                                    <th>Price</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr id="whereProductsShow">
                                                    <td> 
                                                        <select name="ItemName[]" size="50"
                                                            id="ItemName" class="select2"
                                                            style="width: 100%;height:30px" required>
                                                        </select>
                                                        <input style="display:none" type='text' id="ItemName_other_hidden" name='ItemName_other_hidden[]'>
                                                    <td> <input size="5" type="number" oninput="changeInvoice_oninput()" name="Qty[]" id="Qty" style="width:100%;height:30px;"></td>
                                                    <td> <input size="5" name="Price[]" id="Price" oninput="changeInvoice_oninput()" style="width: 100%;height:30px;" type="number" required></td>
                                                    <td> <input size="5" name="Total[]" readonly id="Total" style="width: 100%;height:30px;" type="number" required></td>
                                                    <td><button onclick="addRow()" id="addRow" style="height: 30px;background:green;color:white;border:none" class="addRow">+</button></td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>

                                </div>

                            </div>


                            <div class="col-md-2">

                                <div class="row">
                                    <div class="col-xs-6 col-md-12">

                                        <label>Total</label>
                                        <input id="SaleTotal" readonly placeholder="Total" name="Total" style="width: 100%;" type="number" required>

                                    </div>
                                    <div class="col-xs-6 col-md-12">
                                        <label>Discount</label>
                                        <input type="number" oninput="changeInvoice_oninput(this.value)" id="Discount" name="Discount" style="width: 100%;" required class="" placeholder="% Discount">
                                    </div>

                                    <div class="col-xs-6 col-md-12">
                                        <label>VAT %</label>
                                        <input type="number" value="{{Auth::user()->VATPercentage}}" id="VATPercentage" name="VATPercentage" style="width: 100%;" required placeholder="Scheme Disc.">
                                    </div>
                                    <div class="col-xs-6 col-md-12">
                                        <label>VAT Amount</label>

                                        <input type="number" placeholder="VAT Amount" name="VATPercentageAmount" id="VATPercentageAmount" class="control-form" style="width: 100%;" required>

                                    </div>

                                    <div class="col-xs-6 col-md-12">
                                        <label>Final Total</label>
                                        <input type="number" id="FinalTotal" readonly name="FinalTotal" style="width: 100%;" required placeholder="Final Total">
                                    </div>

                                    <div id="PT" class="col-xs-6 col-sm-12">
                                        <label>Payment in Cash</label>
                                        <input type="checkbox" name="PaymentType" id="PaymentType">
                                    </div>

                                    <div style="width: 100%;margin-top:10px" class="col-xs-6 col-md-12">
                                        <button style="padding: 7px;width:47%;" onclick="dispatch('save')" class="btn btn-primary">Save</button>

                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<script>
      $.ajax({
        url: '/getCategoriesOfLoggenInUser',
        type: 'get',
        success: function(data) {
            let Categories = '<option disabled selected value="">Choose Category...</option>';
            data.forEach(el => {
                Categories += `<option value="${el.id}">${el.Category}</option>`;
                document.getElementById('Category').innerHTML = Categories;
                // document.getElementById('modal_Category').innerHTML = Categories;
            });
        }
    })

    function getPurchaserData(id) {
        if (id != '') {
            $.ajax({
                url: '/getPurchaserData',
                type: 'get',
                data: {
                    id: id
                },
                success: function(data) {
                    console.log(data)
                    document.getElementById('PurchaserAddress').value = data.Address;
                }
            })
        }
    }

    function getItemsOfSelectedCategory(category) {

    $.ajax({
        url: '/getItemsOfSelectedCategory',
        type: 'get',
        data: {
            Category: category
        },
        success: function(data) {
            console.log(data)
            let ItemNames = '<option disabled selected value="">Choose ItemName...</option>';
            data.forEach(el => {
                ItemNames += `<option value="${el.id}">${el.ItemName}</option>`;
                document.getElementById('ItemName').innerHTML = ItemNames;
                // document.getElementById('modal_Category').innerHTML = ItemNames;
            });

        }
    })
    }
    function addRow() {

        var selected_element = document.getElementById('ItemName');
        var ItemName = selected_element.options[selected_element.selectedIndex].text;
        var ItemId =  selected_element.value;
        console.log(ItemName)
        console.log(ItemId)
        var qty = document.getElementById('Qty').value;
        var price = document.getElementById('Price').value;

        var total = document.getElementById('Total').value;

        var tr =
            `<tr >
    <td >
    <input size="50" style="width:100%;height:30px;" readonly type='text' name='ItemName[]' value='${ItemName}'>
    <input type='hidden' name='ItemName_other_hidden[]' value='${ItemId}'>
    </td>
    <td >
    <input size="5" style="width:100%;height:30px;" oninput='changeInvoice_oninput()'  type='number' name='Qty[]' value='${qty}'>
    </td>
    <td >
    <input size="5" style="width:100%;height:30px;" oninput='changeInvoice_oninput()' type='number' name='Price[]' value='${price}'>
    </td>
  
    <td >
    <input size="5" style="width:100%;height:30px;" readonly type='number' name='Total[]' value='${total}'>
    </td>
   
    <td>
    <button onclick="deleteRow(this)" style="height: 30px;background:red;color:white;border:none" class='deleteRow'>&times</button> 
    </td>
    </tr>
    `;
        var ItemName = document.getElementById('ItemName').value;
        var qty = document.getElementById('Qty').value;
        if (ItemName != '' && qty != '') {
            document.getElementById('whereProductsShow').insertAdjacentHTML("afterend", tr);
            document.getElementById('Qty').value = '';
            document.getElementById('Price').value = '';
            document.getElementById('Total').value = '';

            changeInvoice_oninput()

            $("#ItemName").val('').trigger('change');
            document.getElementById('ItemName').focus()

        }

    };

    function deleteRow(e) {

        e.parentNode.parentNode.remove();
        changeInvoice_oninput();

    };

    function changeInvoice_oninput() {
        var total1 = 0;
        var qty = document.getElementsByName('Qty[]');
        var price = document.getElementsByName('Price[]');
        var total = document.getElementsByName('Total[]');
        var Discount = document.getElementById('Discount');
        var VATPercentage = document.getElementById('VATPercentage');
        var SaleTotal = document.getElementById('SaleTotal');
        var VATPercentageAmount = document.getElementById('VATPercentageAmount');
        var FinalTotal = document.getElementById('FinalTotal');
        for (var i = 0; i < qty.length; i++) {
            total[i].value = (qty[i].value * price[i].value).toFixed(2)
        }
        console.log("abc")
        if (qty.length > 0) {
            console.log("abc 2")
            for (var i = 1; i < qty.length; i++) {
                total1 = total1 + parseFloat(total[i].value);
            }
            console.log(total1)
            SaleTotal.value = total1.toFixed(2);
            VATPercentageAmount.value = parseFloat(SaleTotal.value*VATPercentage.value)/100;
            FinalTotal.value = (SaleTotal.value) - Discount.value - VATPercentageAmount.value;
        } 
    }


    // function for making invoice
    makeInvoice();

    function makeInvoice() {
        $.ajax({
            url: '/getInvoice_purchase',
            type: "get",
            success: function(data) {
                console.log(data)
                document.getElementById('Invoice').value = data;
            }
        })
    }

    todatDate();

    function dispatch(parameter) {

        var ItemName = document.getElementsByName('ItemName_other_hidden[]');
        var qty = document.getElementsByName('Qty[]');
        var price = document.getElementsByName('Price[]');

        var total = document.getElementsByName('Total[]');

        var obj = [];
        for (var i = 1; i < ItemName.length; i++) {
            var ItemName1 = ItemName[i].value;
            var qty1 = qty[i].value;
            var price1 = price[i].value;

            var total1 = total[i].value;


            var obje;
            obje = {
                ItemName: ItemName1,
                Qty: qty1,
                Price: price1,
                Total: total1,

            };
            obj.push(obje);
        }
        // console.log(obj);
        var Date = document.getElementById('Date').value;
        var Invoice = document.getElementById('Invoice').value;
        var PurchaseInvoice = document.getElementById('PurchaseInvoice').value;
        var PurchaserName = document.getElementById('PurchaserName').value;
        var PurchaserAddress = document.getElementById('PurchaserAddress').value;
        var SaleTotal = document.getElementById('SaleTotal').value;
        var Discount = document.getElementById('Discount').value;
        var VATPercentage = document.getElementById('VATPercentage').value;
        var VATPercentageAmount = document.getElementById('VATPercentageAmount').value;
        var FinalTotal = document.getElementById('FinalTotal').value;
        var PaymentType = document.getElementById('PaymentType').checked ? 1 : 0;// 1 for cash means debit 0 for credit


        if (Date != '') {
            var token = '{{csrf_token()}}';
            console.log(obj)
            $.ajax({
                type: "post",
                url: "/dispatch_purchasebook",
                data: {
                    obj: obj,
                    Date: Date,
                    Invoice: Invoice,
                    PurchaseInvoice: PurchaseInvoice,
                    PurchaserName: PurchaserName,
                    PurchaserAddress: PurchaserAddress,
                    SaleTotal: SaleTotal,
                    Discount: Discount,
                    VATPercentage: VATPercentage,
                    VATPercentageAmount: VATPercentageAmount,
                    FinalTotal: FinalTotal,
                    PaymentType: PaymentType,
                    _token: token
                },

                success: function(data) {
                    console.log(data)
                    document.getElementById('Invoice').value = data.NewInvoice;
                    // console.log(document.getElementById('Invoice').value);

                    if (parameter == "print") {

                        window.open("/printInvoice?Invoice=" + data.Invoice, '_blank');

                        document.getElementById('PurchaseInvoice').value = '';
                        document.getElementById('PurchaserName').value = '';
                        document.getElementById('PurchaserAddress').value = '';
                        document.getElementById('SaleTotal').value = '';
                        document.getElementById('Discount').value = '';
                        document.getElementById('VATPercentageAmount').value = '';
                        document.getElementById('FinalTotal').value = '';
                        var tablerows = document.getElementById("tablerows")
                        var count = tablerows.rows.length;

                        for (var a = count - 1; a > 1; a--) {

                            tablerows.rows[a].remove()

                        }
                        document.getElementById('ItemName').focus()

                    } else {
                        if (data.status == "inserted") {


                            var output = `
                        <div class="alert alert-success">
                        <button class="close" style="font-size: 30px;" data-dismiss="alert">&times</button>
                        Saved Successfuly
                        </div>    
                                `;
                            document.getElementById('show_insert_status').innerHTML = output;

                            document.getElementById('PurchaseInvoice').value = '';
                            document.getElementById('PurchaserName').value = '';
                            document.getElementById('PurchaserAddress').value = '';
                            document.getElementById('SaleTotal').value = '';
                            document.getElementById('Discount').value = '';
                            document.getElementById('VATPercentageAmount').value = '';
                            document.getElementById('FinalTotal').value = '';
                            var tablerows = document.getElementById("tablerows")
                            var count = tablerows.rows.length;

                            for (var a = count - 1; a > 1; a--) {

                                tablerows.rows[a].remove()

                            }
                            document.getElementById('ItemName').focus()

                        } else {

                            var output = `
                        <div class="alert alert-danger">
                        <button class="close" style="font-size: 30px;" data-dismiss="alert">&times</button>
                         Not Saved
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