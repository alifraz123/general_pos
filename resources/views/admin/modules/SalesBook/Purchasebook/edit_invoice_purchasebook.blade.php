@extends('admin/layouts/mainlayout')
@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <h1>Edit Purchase Invoice</h1>

    </section>
    <section class="content">
        <div id="show_insert_status">

        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="box box-default">
                    <div class="box-body">
                        <div class="col-md-10">
                            <div class="row">
                                <input type="hidden" id="invoice_edit" name="invoice_edit" value="{{$purchasebook->Invoice}}">
                                <div class="col-md-3">
                                    <label>Date</label>
                                    <input id="Date" style="padding: 0;width: 100%;height: 30px;font-size: 14px;" value="{{$purchasebook->Date}}" name="date" type="date">
                                </div>

                                <div class="col-md-3">
                                    <label>Invoice</label>
                                    <input name="Invoice" value="{{$purchasebook->Invoice}}" readonly id="Invoice" style="width: 100%;padding:0;height:30px">

                                </div>
                                <div class="col-md-3">
                                    <label>Purchase Invoice</label>
                                    <input name="PurchaseInvoice" value="{{$purchasebook->PurchaseInvoice}}" id="PurchaseInvoice" style="width: 100%;padding:0;height:30px">

                                </div>
                                

                                <div class="col-md-3">
                                    <label>Purchaser Name</label>
                                    <select onchange="getPurchaserData(this.value)" name="PurchaserName"
                                    id="PurchaserName" class="control-form select2"
                                    style="width: 100%;height:30px">
                                        <option value="" selected disabled>Choose Purchaser... </option>

                                        @foreach($purchasers as $Purchaser)
                                        <option value="{{$Purchaser->id}}" {{$Purchaser->id == $purchasebook->PurchaserName ? 'selected' : ''}}>{{$Purchaser->PurchaserName}}</option>
                                        @endforeach
                                </select>
                                </div>



                                <div class="col-md-3">
                                    <label>Purchaser Address</label>
                                    <input type="text" value="{{$purchasebook->PurchaserAddress}}" name="PurchaserAddress" id="PurchaserAddress" style="width: 100%;padding:0;height:30px" required>
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
                                    <table>
                                        <tr>
                                            <th style="width: 50%">Item Name</th>
                                            <th>Qty</th>
                                            <th>Price</th>
                                            <th>Total</th>
                                        </tr>

                                        <tbody id="whereProductsShow">
                                            <tr>
                                                <td> 
                                                    <select name="ItemName[]" size="50"
                                                    id="ItemName" class="select2"
                                                    style="width: 100%;height:30px" required>
                                                </select>  
                                                <input style="display:none" type='text' id="ItemName_other_hidden" name='ItemName_other_hidden[]'>  
                                                </td>
                                                <td> <input size="5" type="number" oninput="changeInvoice_oninput()" name="Qty[]" id="Qty" style="width:100%;height:30px;"></td>
                                                <td> <input size="5" name="Price[]" oninput="changeInvoice_oninput()" id="Price" style="width: 100%;height:30px;" type="number" required></td>
                                                <td> <input size="5" name="Total[]" readonly id="Total" style="width: 100%;height:30px;" type="number" required></td>
                                                <td><button onclick="addRow()" id="addRow" style="height: 30px;background:green;color:white;border:none" class="addRow">+</button></td>
                                            </tr>
                                            @foreach($purchasebook_detail as $sbd)
                                            <tr>
                                                <td> 
                                                    <input size="50" style="width:100%;padding:0;height:30px" value="{{$sbd->items_ItemName}}" readonly type='text' name='ItemName[]'>
                                                    <input style="display:none" value="{{$sbd->ItemName}}" type='text' name='ItemName_other_hidden[]'>
                                                </td>
                                                <td> <input size="5" style="width:100%;padding:0;height:30px" oninput="changeInvoice_oninput()" value="{{$sbd->Qty}}" type='text' name='Qty[]'></td>
                                                <td> <input size="5" style="width:100%;padding:0;height:30px" readonly value="{{$sbd->Price}}" type='text' name='Price[]'></td>
                                                <td> <input size="5" style="width:100%;padding:0;height:30px" value="{{$sbd->Total}}" type='text' name='Total[]' required></td>
                                                <td><button onclick="deleteRow(this)" style="line-height:0;height: 30px;background:red;color:white;border:none" class='deleteRow'>&times;</button></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>


                            </div>


                        </div>
                        <div class="col-md-2">

                            <div class="row">

                                <div class="col-xs-6 col-md-12">
                                    <div class="">
                                        <label>Total</label>
                                        <input id="SaleTotal" value="{{$purchasebook->Total}}" readonly placeholder="Total" name="SaleTotal" style="width: 100%;" type="text" required>
                                    </div>
                                </div>

                                <div class="col-xs-6 col-md-12">
                                    <label>Discount</label>
                                    <input type="number" value="{{$purchasebook->Discount}}" oninput="changeInvoice_oninput()" id="Discount" name="Discount" style="width: 100%;" required class="" placeholder="Extra Discount">
                                </div>

                                <div class="col-xs-6 col-md-12">
                                    <label>VAT %</label>

                                    <input type="text" value="{{$purchasebook->VATPercentage}}" readonly placeholder="TO Discount" name="VATPercentage" id="VATPercentage" class="control-form" style="width: 100%;" required>

                                </div>

                                <div class="col-xs-6 col-md-12">
                                    <div class="">
                                        <label>VAT Amount</label>
                                        <input type="text" value="{{$purchasebook->VATPercentageAmount}}" readonly id="VATPercentageAmount" name="VATPercentageAmount" style="width: 100%;" required placeholder="Scheme Disc.">
                                    </div>

                                </div>

                                <div class="col-xs-6 col-md-12">
                                    <div class="">
                                        <label>Final Total</label>
                                        <input type="text" value="{{$purchasebook->FinalTotal}}" readonly id="FinalTotal" name="FinalTotal" style="width: 100%;" required class="" placeholder="Final Total">
                                    </div>

                                </div>

                                <div id="PT" class="col-xs-6 col-sm-12">
                                    <label>Payment in Cash</label>
                                    <input type="checkbox" {{$purchasebook->PaymentType == 1 ? 'checked' : ''}}  name="PaymentType" id="PaymentType">
                                </div>


                                <div style="margin-top:10px" class="col-xs-6 col-md-12">
                                    <button style="padding: 7px;width:47%;" onclick="update_dispatch('update')" class="btn btn-primary">Update</button>


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
    $(".loader").hide()

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

    function addRow() {

        var selected_element = document.getElementById('ItemName');
        var ItemName = selected_element.options[selected_element.selectedIndex].text;
        var ItemId =  selected_element.value;
        var qty = document.getElementById('Qty').value;
        var price = document.getElementById('Price').value;
        var total = document.getElementById('Total').value;

        var tr =
            `<tr >
    <td >
    <input size="50" style="width:100%;height:30px" readonly type='text' name='ItemName[]' value='${ItemName}'>
    <input type='hidden' name='ItemName_other_hidden[]' value='${ItemId}'>
    </td>
    <td >
    <input size="5" style="width:100%;height:30px" oninput='changeInvoice_oninput()'  type='number' name='Qty[]' value='${qty}'>
    </td>
    <td >
    <input size="5" style="width:100%;height:30px" readonly type='number' name='Price[]' value='${price}'>
    </td>
    
    <td >
    <input size="5" style="width:100%;height:30px" readonly type='number' name='Total[]' value='${total}'>
    </td>
    
    <td>
    <button onclick="deleteRow(this)" style="height: 30px;background:red;color:white;border:none" class='deleteRow'>&times</button> 
    </td>
    </tr>
    `;

        if (ItemName != "" && qty != "" && qty != 0 && price != "" && total != "") {

            document.getElementById('whereProductsShow').insertAdjacentHTML("afterend", tr);
            // $('#whereProductsShow').append(tr);
            changeInvoice_oninput();
            document.getElementById('ItemName').value = '';
            document.getElementById('Qty').value = 0;
            document.getElementById('Price').value = 0;
            document.getElementById('Total').value = 0;
            document.getElementById('ItemName').focus()

        }

    };

    function deleteRow(e) {

        e.parentNode.parentNode.remove();
        changeInvoice_oninput();

    };



    function getPriceOfSelectedItemName(ItemName) {

        if (ItemName != '') {
            var ItemName = document.getElementById('ItemName').value;

            $.ajax({
                url: '../getPriceFromRateTable_sale',
                type: 'get',
                data: {
                    ItemName: ItemName,
                },
                beforeSend: function() {
                    $(".loader").show();
                },

                success: function(data) {
                    console.log(data)
                    if (data != '') {

                        document.getElementById('Price').value = data.Rate;
                        $(".loader").hide();
                    } else {

                        $(".loader").show();

                    }
                }
            })



        }
    }

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
        if (qty.length > 0) {
            for (var i = 1; i < qty.length; i++) {
                total1 = total1 + parseFloat(total[i].value);
            }
            console.log(total1)
            SaleTotal.value = parseFloat(total1).toFixed(2);
            VATPercentageAmount.value = parseFloat(SaleTotal.value*VATPercentage.value)/100;
            FinalTotal.value = (SaleTotal.value) - Discount.value - VATPercentageAmount.value;
        } 
    }
    // todatDate();

    function update_dispatch(parameter) {

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
        var PurchaseInvoice = document.getElementById('PurchaseInvoice').value;
        var PurchaserName = document.getElementById('PurchaserName').value;
        var PurchaserAddress = document.getElementById('PurchaserAddress').value;
        var SaleTotal = document.getElementById('SaleTotal').value;
        var Discount = document.getElementById('Discount').value;
        var VATPercentage = document.getElementById('VATPercentage').value;
        var VATPercentageAmount = document.getElementById('VATPercentageAmount').value;
        var FinalTotal = document.getElementById('FinalTotal').value;
        var PaymentType = document.getElementById('PaymentType').checked ? 1 : 0;// 1 for cash 0 for credit

        if (Date != '') {

            var token = '{{csrf_token()}}';
            $.ajax({
                type: "post",
                url: "../update_dispatch_purchasebook",
                data: {
                    obj: obj,
                    Date: Date,
                    PurchaseInvoice: PurchaseInvoice,
                    PurchaserName: PurchaserName,
                    PurchaserAddress: PurchaserAddress,
                    SaleTotal: SaleTotal,
                    Discount: Discount,
                    VATPercentage: VATPercentage,
                    VATPercentageAmount: VATPercentageAmount,
                    FinalTotal: FinalTotal,
                    PaymentType: PaymentType,
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

    var Quantity = document.getElementById("Price");
    Quantity.addEventListener("keydown", function(e) {
        if (e.key === "Enter") {
            addRow();
        }
    });

    var Quantity = document.getElementById("Qty");
    Quantity.addEventListener("keydown", function(e) {
        if (e.key === "Enter") {
            addRow();
        }
    });
    var TO = document.getElementById("Total");
    TO.addEventListener("keydown", function(e) {
        if (e.key === "Enter") {
            addRow();
        }
    });

    $(document).keypress(function(event) {
        if (event.keyCode == 33) {
            var a = "save";
            update_dispatch()
        }
        if (event.keyCode == 64) {
            var a = "bill";
            Save(a);
        }
    });
</script>



@endsection