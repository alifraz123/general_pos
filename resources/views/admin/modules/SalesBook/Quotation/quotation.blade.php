@extends('admin/layouts/mainlayout')
@section('content')
<style>
    input:disabled {
        background: field;
        border: 1px solid #ccc;
    }

    textarea:disabled {
        background: field;
        border: 1px solid #ccc;
    }
</style>
<div class="content-wrapper">
    <section class="content-header">
        <h1>Quotation
        </h1>
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
                                <input type="hidden" id="VAT_Calculation" value="{{Auth::user()->VAT_Calculation}}">

                                <div class="row">
                                    <div class="col-xs-6 col-sm-3  col-sm-3">
                                        <input type="hidden" value="{{Auth::user()->ArticleNo}}" id="ArticleNo">
                                        <label>Date</label>
                                        <input class="form-control"
                                            style="padding: 0;width: 100%;height: 30px;font-size: 14px;" id="Date"
                                            name="date" type="date">
                                    </div>

                                    <div id="CustomerName_Field" class="col-xs-6 col-sm-3">

                                        <input type="hidden" id="CustomerIndustry"
                                            value="{{Auth::user()->CustomerIndustry}}">
                                        @if(Auth::user()->CustomerIndustry == 'Regular')
                                        <label>Customer Name</label> <label style="cursor:pointer;"
                                            onclick="openCustomerModal()">&#43</label>
                                        <select onchange="getCustomerData(this.value)" name="CustomerName"
                                            id="CustomerName" class="control-form select2"
                                            style="width: 100%;height:30px">
                                            <option value="" selected disabled>Choose Customer...</option>

                                            @foreach($Customers as $Customer)
                                            <option>{{$Customer->CustomerName}}</option>
                                            @endforeach
                                        </select>
                                        @else
                                        <label>Customer Name
                                        </label>
                                        <input name="CustomerName" id="CustomerName" class="control-form"
                                            style="width: 100%;height:30px">
                                        @endif

                                    </div>
                                    <div id="VATNO_Field" class="col-xs-6 col-sm-3">
                                        <label>Customer VAT NO.</label>
                                        <input name="VATNO" id="VATNO" class="control-form "
                                            style="width: 100%;height:30px" required>

                                    </div>

                                    <div id="PT" class="col-xs-6 col-sm-3">
                                        <label>Payment Type</label>
                                        <select name="PaymentType" id="PaymentType" style="width: 100%;" required
                                            class="select2">
                                            <option value="" selected disabled>Choose Payment Type...</option>


                                        </select>
                                    </div>

                                    <div class="col-xs-6 col-sm-3  col-sm-3">
                                        <label>Validation Date</label>
                                        <input class="form-control"
                                            style="padding: 0;width: 100%;height: 30px;font-size: 14px;" id="dueDate"
                                            name="duedate" type="date">
                                    </div>



                                    <div id="Cell_Field" class="col-xs-6 col-sm-3">
                                        <label>Cell</label>
                                        <input name="Cell" id="Cell" style="width: 100%;padding:0;height:30px">

                                    </div>
                                    @if(Auth::user()->ShowEmailOnInvoice == 'Yes')
                                    <div id="Email_Field" class="col-xs-6 col-sm-3">
                                        <label>Email</label>
                                        <input name="Email" id="Email" style="width: 100%;padding:0;height:30px">

                                    </div>
                                    @endif


                                    <div id="Address_Field" class="col-xs-12 col-sm-6">
                                        <label>Address</label>
                                        <input name="Address" id="Address" style="width: 100%;padding:0;height:30px"
                                            required>

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
                                    <div class="col-xs-6 col-sm-3">
                                        <label>Quotation No</label>
                                        <span class="pull-right"><label style="cursor:pointer;"
                                                onclick="openNotesModal()">Notes</label></span>

                                        <input required style="padding: 0;width: 100%;height: 30px;font-size: 14px;"
                                            id="Invoice" name="Invoice" type="text">
                                    </div>
                                    @if(Auth::user()->SaleType == 'BarCode')
                                    <div class="col-xs-6 col-sm-3">
                                        <label>BarCode</label>
                                        <input type="text" style="width: 100%;padding:0;height:30px;"
                                            onchange="AppenedItem(this.value)" id="BarCode">

                                    </div>
                                    @endif
                                </div>

                                <!-- Add Product Detail -->
                                <div id="other" class="row">
                                    <div style="overflow-x:auto" class="col-xs-12 col-md-12">
                                        <table id="tablerows_other">
                                            <thead>
                                                <tr>
                                                    <th style="width: 50%;">Item Name</th>
                                                    @if(Auth::user()->ArticleNo == 'FM')
                                                    <th>رقم الفاتورة</th>
                                                    @else
                                                    <th>Item Code</th>
                                                    @endif
                                                    <th>Qty</th>
                                                    <th>Price</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr id="whereProductsShow_other">
                                                    <td>
                                                        <select name="ItemName_other[]"
                                                            onchange="getPriceOfSelectedItemName(this.value)" size="50"
                                                            id="ItemName" class="select2"
                                                            style="width: 100%;height:30px" required>
                                                        </select>
                                                    </td>
                                                    @if(Auth::user()->ArticleNo == 'FM')
                                                    <td>
                                                        <input name="ItemCode[]" size="50" id="ItemCode"
                                                            style="width: 100%;height:30px" required>

                                                    </td>
                                                    @else
                                                    <td>
                                                        <input name="ItemCode[]" size="50" readonly id="ItemCode"
                                                            style="width: 100%;height:30px" required>

                                                    </td>
                                                    @endif

                                                    <td> <input size="5" type="number"
                                                            oninput="changeInvoice_oninput_other()" name="Qty_other[]"
                                                            id="Qty_other" style="width:100%;height:30px;"></td>
                                                    <td> <input size="5" name="Price_other[]" id="Price_other"
                                                            oninput="changeInvoice_oninput_other()"
                                                            style="width: 100%;height:30px;" type="number" required>
                                                    </td>
                                                    <td> <input size="5" name="Total_other[]" readonly id="Total_other"
                                                            style="width: 100%;height:30px;" type="number" required>
                                                    </td>
                                                    <td><button onclick="addRow_other()" id="addRow"
                                                            style="height: 30px;background:green;color:white;border:none"
                                                            class="addRow">+</button></td>
                                                </tr>
                                            </tbody>



                                        </table>
                                    </div>
                                </div>




                            </div>


                            <div class="col-md-2">

                                <div class="row">
                                    <div class="col-xs-6 col-sm-12">

                                        <label>Total</label>
                                        <input id="SaleTotal" readonly placeholder="Total" name="Total"
                                            style="width: 100%;" type="number" required>

                                    </div>

                                    <div class="col-xs-6 col-sm-12">
                                        <label>VAT %</label>
                                        <input type="number" oninput="changeInvoice_oninput_choose()"
                                            value="{{Auth::user()->VATPercentage}}" id="VATPercentage"
                                            name="VATPercentage" style="width: 100%;" required
                                            placeholder="Scheme Disc.">
                                    </div>
                                    <div class="col-xs-6 col-sm-12">
                                        <label>VAT Amount</label>

                                        <input type="number" placeholder="VAT Amount" name="VATPercentageAmount"
                                            id="VATPercentageAmount" class="control-form" style="width: 100%;" required>

                                    </div>


                                    <div class="col-xs-6 col-sm-12">
                                        <label>Discount</label>
                                        <input type="number" oninput="changeInvoice_oninput_choose()" id="Discount"
                                            name="Discount" style="width: 100%;" required class=""
                                            placeholder="% Discount">
                                    </div>

                                    <div class="col-xs-6 col-sm-12">
                                        <label>Final Total</label>
                                        <input type="number" id="FinalTotal" readonly name="FinalTotal"
                                            style="width: 100%;" required placeholder="Final Total">
                                    </div>

                                    <div class="col-xs-6 col-sm-12">
                                        <label>Cash</label>
                                        <input type="number" id="Cash" name="Cash" style="width: 100%;" required
                                            placeholder="Cash">
                                    </div>

                                    <div style="width: 100%;margin-top:10px" class="col-xs-6 col-sm-12">

                                        <div class="btn-group">
                                            <button type="button" onclick="dispatch('save')"
                                                class="btn btn-primary">Save</button>
                                            <button type="button" class="btn btn-primary dropdown-toggle"
                                                data-toggle="dropdown">
                                                <span class="caret"></span>
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <ul class="dropdown-menu" role="menu">
                                                <li><a onclick="dispatch('pdf')">Save AS Pdf</a></li>
                                            </ul>
                                        </div>
                                        <button style="padding: 7px;width:40%;" onclick="dispatch('print')"
                                            class="btn btn-primary">Print</button>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </div>
        </div>
</div>
<div class="modal fade" id="CustomerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog mw-100 w-50 " style="width:40%;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Customer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="insertCustomer">
                    @csrf
                    <input type="hidden" name="requestFrom" value="salebook">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Customer Name</label>
                                <input required id="CustomerName" type="text" name="CustomerName" class="form-control"
                                    placeholder="Item Name">
                                <label id="itemError"></label>
                            </div>

                        </div>

                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Customer VATNo</label>
                                <input type="text" required class="form-control" name="CustomerVATNo" id="CustomerVATNo"
                                    placeholder="Item CustomerVATNo">
                            </div>
                        </div>



                    </div>
                    <div class="row">

                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Contact</label>
                                <input type="text" required class="form-control" name="Contact" id="Contact">

                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Address</label>
                                <input type="text" required class="form-control" name="Address" id="Address">
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-2">
                            <input type="submit" style="margin-bottom: 25px;" class="btn btn-success"
                                value="Save">

                        </div>

                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
<div class="modal fade" id="NotesModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog mw-100 w-50 " style="width:60%;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Notes</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="Warrenty_Field" class="row">
                    <div class="col-xs-12 col-md-12">
                        <label>Notes</label>
                        <textarea style="width: 100%;padding:0;" name="Warrenty" value="" id="QuotationWarrenty"
                            placeholder="Notes">
                        </textarea>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script>
    var previous_date = 'no';

    function openCustomerModal() {

        $('#CustomerModal').modal('show');
    }
    function openNotesModal() {

        $('#NotesModal').modal('show');
    }

    function changeInvoice_oninput_choose() {
        changeInvoice_oninput_other()
    }

    var productsArray_UserName_Wise = [];
    getAllItems()

    function getAllItems() {
        $.ajax({
            url: "/getAllItems",
            type: "get",
            success: function(data) {
                // console.log(data)
                if ((data.ItemsByUserName).length > 0) {
                    data.ItemsByUserName.forEach(el => {
                        productsArray_UserName_Wise.push({
                            'ItemName': `${el.ItemName}`,
                            'BarCode': `${el.BarCode}`,
                            'Qty': `${el.Qty}`,
                            'Rate': `${el.Rate}`,
                            'ItemCode': `${el.ItemCode}`
                        });

                    });
                }
                // console.log(productsArray_UserName_Wise)
            }

        })

    }

    function AppenedItem(Searched_ItemName) {
        console.log(productsArray_UserName_Wise)
        if ((productsArray_UserName_Wise.length) != 0 && productsArray_UserName_Wise.find(productsArray_UserName_Wise => productsArray_UserName_Wise.BarCode == Searched_ItemName) != undefined) {
            var ItemName = productsArray_UserName_Wise.find(productsArray_UserName_Wise => productsArray_UserName_Wise.BarCode == Searched_ItemName).ItemName;
            var Rate = productsArray_UserName_Wise.find(productsArray_UserName_Wise => productsArray_UserName_Wise.BarCode == Searched_ItemName).Rate;
            var ItemCode = productsArray_UserName_Wise.find(productsArray_UserName_Wise => productsArray_UserName_Wise.BarCode == Searched_ItemName).ItemCode;

            addRow_other_with_BarCode(ItemName, 1, Rate, Searched_ItemName)
        }

    }

    function addRow_other_with_BarCode(ItemName, qty, price, ItemCode) {

        var total = qty * price;

        var tr =
            `<tr >

            <td >
<input style="width:100%;height:30px;"  type='text' name='ItemName_other[]' value='${ItemName}'>
</td>
<td >
<input style="width:100%;height:30px;" readonly type='text' name='ItemCode[]' value='${ItemCode}'>
</td>
<td >
<input style="width:100%;height:30px;" oninput='changeInvoice_oninput_other()'  type='number' name='Qty_other[]' value='${qty}'>
</td>
<td >
<input style="width:100%;height:30px;" oninput='changeInvoice_oninput_other()' type='number' name='Price_other[]' value='${price}'>
</td>

<td >
<input style="width:100%;height:30px;" readonly type='number' name='Total_other[]' value='${total}'>
</td>

<td>
<button onclick="deleteRow(this)" style="height: 30px;background:red;color:white;border:none" class='deleteRow'>&times</button> 
</td>
</tr>
`;

        document.getElementById('whereProductsShow_other').insertAdjacentHTML("afterend", tr);
        document.getElementById('BarCode').value = '';

        changeInvoice_oninput_other();


    }

    var CustomerIndustry = document.getElementById('CustomerIndustry').value;
    if (CustomerIndustry == 'Regular') {
        document.getElementById('VATNO').disabled = true;
        document.getElementById('Cell').disabled = true;
        document.getElementById('Address').disabled = true;
        if (document.getElementById('Email')) {

            document.getElementById('Email').disabled = true;
        }
    }
    $.ajax({
        url: '/getCategoriesOfLoggenInUser',
        type: 'get',

        success: function(data) {

            let Categories = '<option disabled selected value="">Choose Category...</option>';
            data.forEach(el => {
                Categories += `
                        <option value="${el.Category}">${el.Category}</option>
                        `;

                document.getElementById('Category').innerHTML = Categories;
                // document.getElementById('modal_Category').innerHTML = Categories;
            });
        }
    })


    $.ajax({
        url: '/getPaymentType_ForSaleInvoice',
        type: 'get',
        success: function(data) {

            let PaymentType = '<option disabled selected value="">Choose Payment Type...</option>';
            data.forEach(el => {
                PaymentType += `
                        <option value="${el.PaymentType}">${el.PaymentType}</option>
                        `;
                document.getElementById('PaymentType').innerHTML = PaymentType;
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
                let tsct_row_append = '<option disabled selected value="">Choose ItemName...</option>';


                let Categories = '<option disabled selected value="">Choose ItemName...</option>';
                data.forEach(el => {
                    Categories += `
                        <option value="${el.ItemName}">${el.ItemName}</option>
                        `;

                    document.getElementById('ItemName').innerHTML = Categories;
                    // document.getElementById('modal_Category').innerHTML = Categories;
                });

                let ItemCode = '<option disabled selected value="">Choose Item Code...</option>';
                data.forEach(el => {
                    ItemCode += `
                        <option value="${el.ItemCode}">${el.ItemCode}</option>
                        `;

                    document.getElementById('ItemCode').innerHTML = ItemCode;
                    // document.getElementById('modal_Category').innerHTML = Categories;
                });
            }
        })
    }

    function getPriceOfSelectedItemName(ItemName) {
        var ItemNameValue = '';
        if (ItemName != '') {

            ItemNameValue = document.getElementById('ItemName').value;


            $.ajax({
                url: '../getPriceFromRateTable_sale',
                type: 'get',
                data: {
                    ItemName: ItemNameValue,
                },
                beforeSend: function() {
                    $(".loader").show();
                },

                success: function(data) {
                    console.log(data)
                    if (data != '') {

                        document.getElementById('Price_other').value = data.Rate;
                        document.getElementById('ItemCode').value = data.ItemCode;
                        changeInvoice_oninput_other()
                        $(".loader").hide();
                    } else {

                        $(".loader").show();

                    }
                }
            })



        }
    }


    function addRow_other() {

        var ItemName = document.getElementById('ItemName').value;
        var ItemCode = document.getElementById('ItemCode').value;
        var qty = document.getElementById('Qty_other').value;
        var price = document.getElementById('Price_other').value;
        var total = document.getElementById('Total_other').value;

        var tr =
            `<tr >
<td >
<input style="width:100%;height:30px;"  type='text' name='ItemName_other[]' value='${ItemName}'>
</td>
<td >
<input style="width:100%;height:30px;"  type='text' name='ItemCode[]' value='${ItemCode}'>
</td>
<td >
<input style="width:100%;height:30px;" oninput='changeInvoice_oninput_other()'  type='number' name='Qty_other[]' value='${qty}'>
</td>
<td >
<input style="width:100%;height:30px;" oninput='changeInvoice_oninput_other()' type='number' name='Price_other[]' value='${price}'>
</td>

<td >
<input style="width:100%;height:30px;" readonly type='number' name='Total_other[]' value='${total}'>
</td>

<td>
<button onclick="deleteRow(this)" style="height: 30px;background:red;color:white;border:none" class='deleteRow'>&times</button> 
</td>
</tr>
`;
        var qty = document.getElementById('Qty_other').value;
        $("#ItemName").val('').trigger('change');
        document.getElementById('ItemName').focus()
        if (qty != '') {
            document.getElementById('whereProductsShow_other').insertAdjacentHTML("afterend", tr);
            document.getElementById('Qty_other').value = '';
            document.getElementById('Price_other').value = '';
            document.getElementById('Total_other').value = '';
            document.getElementById('ItemCode').value = '';


        }
        changeInvoice_oninput_other();


    };




    function changeInvoice_oninput_other() {
        var vat_after_or_before = document.getElementById('VAT_Calculation').value;

        var total1 = 0;

        var qtyValue = document.getElementsByName('Qty_other[]');
        var priceValue = document.getElementsByName('Price_other[]');
        var totalValue = document.getElementsByName('Total_other[]');

        var qty = qtyValue;
        var price = priceValue;
        var total = totalValue;
        var Discount = document.getElementById('Discount');
        var VATPercentage = document.getElementById('VATPercentage');
        var SaleTotal = document.getElementById('SaleTotal');
        var VATPercentageAmount = document.getElementById('VATPercentageAmount');
        var FinalTotal = document.getElementById('FinalTotal');

        for (var i = 0; i < qty.length; i++) {
            total[i].value = (qty[i].value * price[i].value).toFixed(2)
        }
        if (qty.length > 1) {
            for (var i = 1; i < qty.length; i++) {
                total1 = total1 + parseFloat(total[i].value);
            }
            SaleTotal.value = total1.toFixed(2);
            if (vat_after_or_before == 'Before') {
                VATPercentageAmount.value = ((SaleTotal.value - Discount.value) / 100 * VATPercentage.value).toFixed(2);
                FinalTotal.value = (parseFloat(SaleTotal.value - Discount.value) + parseFloat(VATPercentageAmount.value)).toFixed(2);

            } else if (vat_after_or_before == 'After') {
                VATPercentageAmount.value = ((SaleTotal.value / 100) * VATPercentage.value).toFixed(2);
                FinalTotal.value = (parseFloat(SaleTotal.value) + parseFloat(VATPercentageAmount.value) - parseFloat(Discount.value)).toFixed(2);

            }

        } else if (qty.length == 1) {
            SaleTotal.value = 0;
            VATPercentageAmount.value = 0;
            FinalTotal.value = 0;
            Discount.value = 0;
            document.getElementById('Cash').value = 0;
        }
    }


    function deleteRow(e) {

        e.parentNode.parentNode.remove();

        changeInvoice_oninput_other();

    };


    // function for making invoice
    getInvoice()

    function getInvoice() {
        $.ajax({
            url: '/getInvoice',
            type: "get",
            data:{
                Ref:"QT"
            },
            success: function(data) {
                console.log(data)
                document.getElementById('Invoice').value = data;
            }
        })

    }

    function getCustomerData(CustomerName) {
        if (CustomerName != '') {

            $.ajax({
                url: '/getCustomerData',
                type: 'get',
                data: {
                    CustomerName: CustomerName
                },
                success: function(data) {
                    console.log(data)
                    document.getElementById('VATNO').value = data.CustomerVATNo;
                    document.getElementById('Cell').value = data.Contact;
                    document.getElementById('Address').value = data.Address;
                    document.getElementById('Email').value = data.Email;
                }

            })

        }
    }


    todatDate();

    function dispatch(parameter) {
        $.ajax({
            url: '/previous_quotation',
            type: 'get',
            data: {
                Invoice: document.getElementById('Invoice').value
            },
            success: function(data) {
                console.log(data)
                if (data == 'invoice duplication') {
                    alert(data)
                    return false;
                } else if (data == "everything is ok") {
                    ifData_isNot_previous()
                }
            }
        })

        function ifData_isNot_previous() {

            var ItemNameValue = '';
            var DescriptionValue = '';
            var qtyValue = '';
            var priceValue = '';
            var totalValue = '';
            var ItemCode = '';

            ItemNameValue = document.getElementsByName('ItemName_other[]');
            ItemCode = document.getElementsByName('ItemCode[]');
            DescriptionValue = '';
            qtyValue = document.getElementsByName('Qty_other[]');
            priceValue = document.getElementsByName('Price_other[]');
            totalValue = document.getElementsByName('Total_other[]');


            var ItemName = ItemNameValue;
            var ItemCode_fsct1 = ItemCode;
            var Description = DescriptionValue;
            var qty = qtyValue;
            var price = priceValue;
            var total = totalValue;
            var obj = [];


            for (var i = 1; i < ItemName.length; i++) {

                var obje;
                obje = {
                    ItemName: ItemName[i].value,
                    ItemCode: ItemCode[i].value,
                    Description: Description == '' ? '' : Description[i].value,
                    Qty: qty[i].value,
                    Price: price[i].value,
                    Total: total[i].value,
                };
                obj.push(obje);
            }

            // alert(obj)
            // console.log(obj);

            var Date = document.getElementById('Date').value;
            var dueDate = document.getElementById('dueDate').value;
            var Invoice = document.getElementById('Invoice').value;
            var CustomerName = document.getElementById('CustomerName').value;
            var VATNO = document.getElementById('VATNO').value;
            var Cell = document.getElementById('Cell').value;
            var Address = document.getElementById('Address').value;
            if (document.getElementById('Email')) {
                var Email = document.getElementById('Email').value;
            } else {
                Email = '';
            }
            var SaleTotal = document.getElementById('SaleTotal').value;
            var FinalTotal = document.getElementById('FinalTotal').value;

            var VATPercentage = document.getElementById('VATPercentage').value;
            var VATPercentageAmount = document.getElementById('VATPercentageAmount').value;
            var Discount = document.getElementById('Discount').value;
            var Cash = document.getElementById('Cash').value;
            var PaymentType = document.getElementById('PaymentType').value;
            var Warrenty = document.getElementById('QuotationWarrenty').value;

            if (Date != '') {


                var token = '{{csrf_token()}}';
                // console.log(obj)
                $.ajax({
                    type: "post",
                    url: "/dispatch_quotation",
                    data: {
                        obj: obj,
                        Date: Date,
                        dueDate: dueDate,
                        Invoice: Invoice,
                        previous_date: previous_date,
                        CustomerName: CustomerName,
                        VATNO: VATNO,
                        Cell: Cell,
                        Address: Address,
                        Email: Email,
                        SaleTotal: SaleTotal,
                        VATPercentage: VATPercentage,
                        VATPercentageAmount: VATPercentageAmount,
                        Discount: Discount,
                        FinalTotal: FinalTotal,
                        Cash: Cash,
                        PaymentType: PaymentType,
                        Warrenty: Warrenty,
                        _token: token
                    },

                    success: function(data) {
                        console.log(data)

                        document.getElementById('Invoice').value = data.NewInvoice;

                        if (parameter == "print") {

                            window.open("/printQuotation?Invoice=" + data.Invoice, '_blank');

                            var CustomerIndustry = document.getElementById('CustomerIndustry').value;
                            console.log(CustomerIndustry)
                            if (CustomerIndustry == 'Regular') {
                                $('#CustomerName').val('').trigger('change');

                            } else {
                                document.getElementById('CustomerName').value = '';
                            }
                            $('#ItemName').val('').trigger('change');
                            document.getElementById('VATNO').value = '';
                            document.getElementById('Cell').value = '';
                            if (document.getElementById('Email')) {

                                document.getElementById('Email').value = '';
                            }
                            document.getElementById('Address').value = '';
                            document.getElementById('SaleTotal').value = '';

                            document.getElementById('Discount').value = '';
                            document.getElementById('QuotationWarrenty').value = '';


                            document.getElementById('VATPercentageAmount').value = '';
                            document.getElementById('FinalTotal').value = '';
                            document.getElementById('Cash').value = '';


                            var tablerows = document.getElementById("tablerows_other")
                            var count = tablerows.rows.length;

                            for (var a = count - 1; a > 1; a--) {
                                tablerows.rows[a].remove()
                            }



                            // $("#ItemCode").val('').trigger('change');
                        }
                        else if(parameter == "pdf"){
                            window.open("/printQuotation?Invoice=" + data.Invoice+"&Pdf=Y", '_blank');
                            var CustomerIndustry = document.getElementById('CustomerIndustry').value;
                            console.log(CustomerIndustry)
                            if (CustomerIndustry == 'Regular') {
                                $('#CustomerName').val('').trigger('change');

                            } else {
                                document.getElementById('CustomerName').value = '';
                            }
                            $('#ItemName').val('').trigger('change');
                            document.getElementById('VATNO').value = '';
                            document.getElementById('Cell').value = '';
                            if (document.getElementById('Email')) {

                                document.getElementById('Email').value = '';
                            }
                            document.getElementById('Address').value = '';
                            document.getElementById('SaleTotal').value = '';

                            document.getElementById('Discount').value = '';
                            document.getElementById('QuotationWarrenty').value = '';


                            document.getElementById('VATPercentageAmount').value = '';
                            document.getElementById('FinalTotal').value = '';
                            document.getElementById('Cash').value = '';


                            var tablerows = document.getElementById("tablerows_other")
                            var count = tablerows.rows.length;

                            for (var a = count - 1; a > 1; a--) {
                                tablerows.rows[a].remove()
                            }
                        }
                        else {
                            if (data.status == "inserted") {


                                var output = `
                        <div class="alert alert-success">
                        <button class="close" style="font-size: 30px;" data-dismiss="alert">&times</button>
                        Saved Successfuly
                        </div>    
                                `;
                                document.getElementById('show_insert_status').innerHTML = output;

                                $('#ItemName').val('').trigger('change');


                                var CustomerIndustry = document.getElementById('CustomerIndustry').value;
                                console.log(CustomerIndustry)
                                if (CustomerIndustry == 'Regular') {
                                    $('#CustomerName').val('').trigger('change');

                                } else {
                                    document.getElementById('CustomerName').value = '';
                                }
                                document.getElementById('VATNO').value = '';
                                document.getElementById('Cell').value = '';
                                if (document.getElementById('Email')) {

                                    document.getElementById('Email').value = '';
                                }
                                document.getElementById('Address').value = '';
                                document.getElementById('SaleTotal').value = '';

                                document.getElementById('Discount').value = '';

                                document.getElementById('VATPercentageAmount').value = '';
                                document.getElementById('FinalTotal').value = '';
                                document.getElementById('Cash').value = '';

                                var tablerows = document.getElementById("tablerows_other")
                                var count = tablerows.rows.length;

                                for (var a = count - 1; a > 1; a--) {
                                    tablerows.rows[a].remove()
                                }
                                var tablerows = document.getElementById("tablerows_tsct")
                                var count = tablerows.rows.length;

                                for (var a = count - 1; a > 1; a--) {
                                    tablerows.rows[a].remove()
                                }

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

                    }
                });

                // todatDate();

            }

        }


    }

    function todatDate() {
        var now = new Date();
        var day = ("0" + now.getDate()).slice(-2);
        var month = ("0" + (now.getMonth() + 1)).slice(-2);
        var today = now.getFullYear() + "-" + (month) + "-" + (day);
        $('#Date').val(today);
        $('#dueDate').val(today);
    }

    var Price = document.getElementById("Price");
    Price.addEventListener("keydown", function(e) {
        if (e.key === "Enter") {
            addRow();
        }
    });
    var Price_other = document.getElementById("Price_other");
    Price_other.addEventListener("keydown", function(e) {
        if (e.key === "Enter") {
            addRow_other();
        }
    });

    var qty = document.getElementById("Qty");
    qty.addEventListener("keydown", function(e) {
        if (e.key === "Enter") {
            addRow();
        }
    });
    var Qty_other = document.getElementById("Qty_other");
    Qty_other.addEventListener("keydown", function(e) {
        if (e.key === "Enter") {
            addRow_other();
        }
    });
    var Total = document.getElementById("Total");
    Total.addEventListener("keydown", function(e) {
        if (e.key === "Enter") {
            addRow();
        }
    });
    var Total_other = document.getElementById("Total_other");
    Total_other.addEventListener("keydown", function(e) {
        if (e.key === "Enter") {
            addRow_other();
        }
    });


    $(document).keypress(function(event) {
        if (event.keyCode == 33) {
            var a = "save";
            dispatch('save')
        }

    });
</script>
@endsection