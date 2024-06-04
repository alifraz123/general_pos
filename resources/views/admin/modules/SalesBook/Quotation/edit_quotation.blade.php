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
        <h1>Edit Quotation
        </h1>

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
                                <input type="hidden" id="VAT_Calculation" value="{{Auth::user()->VAT_Calculation}}">
                                <div class="col-xs-6 col-sm-3">
                                    <label>Date</label>
                                    <input id="Date" style="padding: 0;width: 100%;height: 30px;font-size: 14px;"
                                        value="{{$salebook[0]->Date}}" name="date" type="date">
                                </div>
                                <div id="CustomerName_Field" class="col-xs-6 col-sm-3">
                                    <input type="hidden" id="invoice_edit" name="invoice_edit"
                                        value="{{$salebook[0]->Invoice}}">

                                    <label>Customer Name</label>

                                    <input type="hidden" id="CustomerIndustry"
                                        value="{{Auth::user()->CustomerIndustry}}">
                                    @if(Auth::user()->CustomerIndustry == 'Regular')
                                    <select onchange="getCustomerData(this.value)" name="CustomerName" id="CustomerName"
                                        class="control-form select2" style="width: 100%;height:30px">
                                        <option value="" selected disabled>Choose Customer...</option>
                                        @foreach($Customers as $Customer)
                                        <option {{$salebook[0]->CustomerName == $Customer->CustomerName ? 'selected' :
                                            ''}}>{{$Customer->CustomerName}}</option>
                                        @endforeach
                                    </select>
                                    @else

                                    <input name="CustomerName" value="{{$salebook[0]->CustomerName}}" id="CustomerName"
                                        style="width: 100%;padding:0;height:30px">
                                    @endif


                                </div>
                                <input type="hidden" value="{{$salebook[0]->PaymentType}}" id="hidden_paymenttype">

                                <div id="VATNO_Field" class="col-xs-6 col-sm-3">

                                    <label>Customer VAT NO.</label>
                                    <input name="VATNO" value="{{$salebook[0]->VATNO}}" id="VATNO"
                                        style="width: 100%;padding:0;height:30px">

                                </div>
                                <div id="PT" class="col-xs-6 col-sm-3">
                                    <label>Payment Type</label>
                                    <select name="PaymentType" id="PaymentType" style="width: 100%;" required
                                        class="select2">

                                    </select>
                                </div>
                                <div class="col-xs-6 col-sm-3">
                                    <label>Validation Date</label>
                                    <input id="dueDate" style="padding: 0;width: 100%;height: 30px;font-size: 14px;"
                                        value="{{$salebook[0]->dueDate}}" name="duedate" type="date">
                                </div>

                                <div id="Cell_Field" class="col-xs-6 col-sm-3">
                                    <label>Cell</label>

                                    <input name="Cell" id="Cell" value="{{$salebook[0]->Cell}}"
                                        style="width: 100%;padding:0;height:30px">
                                </div>
                                @if(Auth::user()->ShowEmailOnInvoice == 'Yes')
                                <div id="Email_Field" class="col-xs-6 col-sm-3">
                                    <label>Email</label>
                                    <input name="Email" value="{{$salebook[0]->Email}}" id="Email"
                                        style="width: 100%;padding:0;height:30px">

                                </div>
                                @endif

                                <div id="Address_Field" class="col-xs-12 col-sm-6">
                                    <label>Address</label>
                                    <input name="Address" id="Address" style="width: 100%;padding:0;height:30px"
                                        value="{{$salebook[0]->Addres}}" required>

                                </div>

                            </div>
                            <div style="display:flex" class="">
                                <div style="width: 45%;">
                                    <label>Category</label>
                                    <select name="Category" onchange="getItemsOfSelectedCategory(this.value)"
                                        id="Category" style="width: 100%;" required class="select2">


                                    </select>
                                </div>
                                <div class="col-xs-6 col-sm-3">
                                    <label>Quotation No</label>
                                    <span class="pull-right"><label style="cursor:pointer;"
                                            onclick="openNotesModal()">Notes</label></span>
                                    <input required value="{{$salebook[0]->Invoice}}"
                                        style="padding: 0;width: 100%;height: 30px;font-size: 14px;" id="Invoice"
                                        name="Invoice" type="text">
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
                                                <th>Item Code</th>
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
                                                        id="ItemName_other" class="select2"
                                                        style="width: 100%;height:30px" required>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input readonly name="ItemCode[]" size="50" id="ItemCode"
                                                        style="width: 100%;height:30px" required>
                                                </td>

                                                <td> <input size="5" type="number"
                                                        oninput="changeInvoice_oninput_other()" name="Qty_other[]"
                                                        id="Qty_other" style="width:100%;height:30px;"></td>
                                                <td> <input size="5" name="Price_other[]" id="Price_other"
                                                        oninput="changeInvoice_oninput_other()"
                                                        style="width: 100%;height:30px;" type="number" required></td>
                                                <td> <input size="5" name="Total_other[]" readonly id="Total_other"
                                                        style="width: 100%;height:30px;" type="number" required></td>
                                                <td><button onclick="addRow_other()" id="addRow"
                                                        style="height: 30px;background:green;color:white;border:none"
                                                        class="addRow">+</button></td>
                                            </tr>
                                            @foreach($salebook_detail as $sbd)
                                            <tr>

                                                <td> <input style="width:100%;padding:0;height:30px"
                                                        value="{{$sbd->ItemName}}" readonly type='text'
                                                        name='ItemName_other[]'></td>
                                                <td> <input style="width:100%;padding:0;height:30px"
                                                        value="{{$sbd->ItemCode}}" readonly type='text'
                                                        name='ItemCode[]'></td>
                                                <td> <input style="width:100%;padding:0;height:30px"
                                                        oninput="changeInvoice_oninput_other()" value="{{$sbd->Qty}}"
                                                        type='text' name='Qty_other[]'></td>
                                                <td><input style="width:100%;padding:0;height:30px"
                                                        oninput="changeInvoice_oninput_other()" readonly
                                                        value="{{$sbd->Rate}}" type='text' name='Price_other[]'></td>
                                                <td> <input style="width:100%;padding:0;height:30px"
                                                        value="{{$sbd->Total}}" type='text' name='Total_other[]'
                                                        required></td>
                                                <td><button onclick="deleteRow(this)"
                                                        style="line-height:0; height: 30px;background:red;color:white;border:none"
                                                        class='deleteRow'>&times;</button></td>
                                            </tr>
                                            @endforeach
                                        </tbody>



                                    </table>
                                </div>
                            </div>




                        </div>
                        <div class="col-md-2">
                            <div class="card card-default">


                                <div style="" class="card-body">
                                    <div class="row">

                                        <div style="width: 100%;">
                                            <div class="">
                                                <label>Total</label>
                                                <input id="SaleTotal" value="{{$salebook[0]->Total}}" readonly
                                                    placeholder="Total" name="SaleTotal" style="width: 100%;"
                                                    type="text" required>
                                            </div>
                                        </div>

                                        <div style="width: 100%;" class="">
                                            <label>VAT %</label>

                                            <input type="text" oninput="changeInvoice_oninput_choose()"
                                                value="{{$salebook[0]->VATPercentage}}" placeholder="TO Discount"
                                                name="VATPercentage" id="VATPercentage" class="control-form"
                                                style="width: 100%;" required>

                                        </div>


                                        <div class="row">

                                            <div class="col-md-12">
                                                <div class="">
                                                    <label>VAT Amount</label>
                                                    <input type="text" value="{{$salebook[0]->VATPercentageValue}}"
                                                        readonly id="VATPercentageAmount" name="VATPercentageAmount"
                                                        style="width: 100%;" required placeholder="Scheme Disc.">
                                                </div>

                                            </div>

                                            <div class="col-md-12">
                                                <div class="">
                                                    <label>Discount</label>
                                                    <input type="number" value="{{$salebook[0]->Discount}}"
                                                        oninput="changeInvoice_oninput_choose()" id="Discount"
                                                        name="Discount" style="width: 100%;" required class=""
                                                        placeholder="Extra Discount">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="">
                                                    <label>Final Total</label>
                                                    <input type="text" value="{{$salebook[0]->FinalTotal}}" readonly
                                                        id="FinalTotal" name="FinalTotal" style="width: 100%;" required
                                                        class="" placeholder="Final Total">
                                                </div>

                                            </div>
                                            <div class="col-md-12">
                                                <div class="">
                                                    <label>Cash</label>
                                                    <input type="text" value="{{$salebook[0]->Cash}}" readonly id="Cash"
                                                        name="Cash" style="width: 100%;" required placeholder="Debit">
                                                </div>

                                            </div>

                                            <div style="margin-top:10px" class="col-md-12">
                                                <button style="padding: 7px;width:35%;"
                                                    onclick="update_dispatch('update')"
                                                    class="btn btn-primary">Update</button>
                                                <button style="padding: 7px;width:30%;"
                                                    onclick="update_dispatch('print')"
                                                    class="btn btn-primary">Print</button>
                                                <button style="padding: 7px;width:30%;" onclick="update_dispatch('pdf')"
                                                    class="btn btn-primary"><i class="fa fa-file-pdf-o"
                                                        aria-hidden="true"></i></button>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>

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
                        <textarea style="width: 100%;padding:0;" name="Warrenty" id="QuotationWarrenty"
                            placeholder="Notes">{{  $salebook[0]->Warrenty  }}
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
    function openNotesModal() {

        $('#NotesModal').modal('show');
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
<input style="width:100%;height:30px;" readonly type='text' name='ItemName_other[]' value='${ItemName}'>
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

    var domainName = document.getElementById('domainName').value;

    function changeInvoice_oninput_choose(ref) {

        changeInvoice_oninput_other()
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

            });
        }
    })
    $(".loader").hide()



    var PaymentTypeValue = document.getElementById('hidden_paymenttype').value;
    $.ajax({
        url: '/getPaymentType_ForSaleInvoice',
        type: 'get',
        success: function(data) {

            let PaymentType = `
                        <option value="${PaymentTypeValue}">${PaymentTypeValue}</option>
                        `;
            data.forEach(el => {
                PaymentType += `
                        <option value="${el.PaymentType}">${el.PaymentType}</option>
                        `;
                document.getElementById('PaymentType').innerHTML = PaymentType;
                // document.getElementById('modal_Category').innerHTML = Categories;
            });
        }
    })

    function addRow_other() {

        var ItemName = document.getElementById('ItemName_other').value;
        var ItemCode = document.getElementById('ItemCode').value;
        var qty = document.getElementById('Qty_other').value;
        var price = document.getElementById('Price_other').value;
        var total = document.getElementById('Total_other').value;

        var tr =
            `<tr >
<td >
<input style="width:100%;height:30px;" readonly type='text' name='ItemName_other[]' value='${ItemName}'>
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
        var qty = document.getElementById('Qty_other').value;
        if (qty != '') {
            document.getElementById('whereProductsShow_other').insertAdjacentHTML("afterend", tr);
            document.getElementById('Qty_other').value = '';
            document.getElementById('Price_other').value = '';
            document.getElementById('Total_other').value = '';
            document.getElementById('ItemCode').value = '';
            document.getElementById('ItemName_other').focus()
            $("#ItemName_other").val('').trigger('change');


        }

    };


    function deleteRow(e) {

        e.parentNode.parentNode.remove();

        changeInvoice_oninput_other();

    };


    function getItemsOfSelectedCategory() {
        $.ajax({
            url: '../getItemsOfSelectedCategory',
            type: 'get',
            data: {
                Category: document.getElementById('Category').value
            },
            beforeSend: function() {
                $(".loader").show();
            },
            success: function(data) {
                console.log(data)


                let Categories = '<option disabled selected value="">Choose ItemName...</option>';
                data.forEach(el => {
                    Categories += `
                        <option value="${el.ItemName}">${el.ItemName}</option>
                        `;

                    document.getElementById('ItemName_other').innerHTML = Categories;
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
        changeInvoice_oninput_other()

        var ItemNameValue = '';

        ItemNameValue = document.getElementById('ItemName_other').value;

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
    }




    // todatDate();

    function update_dispatch(parameter) {

        var ItemNameValue = '';
        var DescriptionValue = '';
        var qtyValue = '';
        var priceValue = '';
        var totalValue = '';

        var ItemName = document.getElementsByName('ItemName_other[]');
        var ItemCode = document.getElementsByName('ItemCode[]');
        var Description = '';
        var qty = document.getElementsByName('Qty_other[]');
        var price = document.getElementsByName('Price_other[]');
        var total = document.getElementsByName('Total_other[]');
        var obj = [];

        for (var i = 1; i < ItemName.length; i++) {
            // alert(ItemName[i].value)
            console.log(ItemCode[i].value)
            var obje;
            obje = {
                ItemName: ItemName[i].value,
                ItemCode: ItemCode[i].value,
                Qty: qty[i].value,
                Price: price[i].value,
                Total: total[i].value,
            };
            obj.push(obje);
        }


        console.log(obj);

        var Date = document.getElementById('Date').value;
        var dueDate = document.getElementById('dueDate').value;
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
        var Warrenty = document.getElementById('QuotationWarrenty').value;
        var PaymentType = document.getElementById('PaymentType').value;


        if (Date != '') {

            var token = '{{csrf_token()}}';
            $.ajax({
                type: "post",
                url: "../update_quotation",
                data: {
                    obj: obj,
                    Date: Date,
                    dueDate:dueDate,
                    CustomerName: CustomerName,
                    VATNO: VATNO,
                    Cell: Cell,
                    Address: Address,
                    Email: Email,
                    Warrenty: Warrenty,
                    SaleTotal: SaleTotal,
                    VATPercentage: VATPercentage,
                    VATPercentageAmount: VATPercentageAmount,
                    Discount: Discount,
                    FinalTotal: FinalTotal,
                    Cash: Cash,
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
                        window.open("/printQuotation?Invoice=" + Invoice, '_blank');
                        window.close()
                    } else if (parameter=="pdf") {
                        var Invoice = JSON.parse(data).Invoice;
                        window.open("/printQuotation?Invoice=" + Invoice+"&Pdf=Y", '_blank');
                        window.close()
                    } 
                    else {

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

    var qty = document.getElementById("Quantity");
    qty.addEventListener("keydown", function(e) {
        if (e.key === "Enter") {
            addRow();
        }
    });
    var Qty_other = document.getElementById("Quantity_other");
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
            update_dispatch()
        }
        if (event.keyCode == 64) {
            var a = "bill";
            Save(a);
        }
    });
</script>



@endsection