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
        <h1>Sale Return Invoice
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
                                <div class="row">
                                    <div class="col-xs-6 col-sm-3  col-sm-3">
                                        <label>Date</label>
                                        <input class="form-control" onchange="checkDate_isCurrent(this.value)" style="padding: 0;width: 100%;height: 30px;font-size: 14px;" id="Date" name="date" type="date">
                                    </div>

                                    <div id="CustomerName_Field" class="col-xs-6 col-sm-3">


                                        <input type="hidden" id="CustomerIndustry" value="{{Auth::user()->CustomerIndustry}}">
                                        @if(Auth::user()->CustomerIndustry == 'Regular')
                                        <label>Customer Name</label> <label style="cursor:pointer;" onclick="openCustomerModal()">&#43</label>
                                        <select onchange="getCustomerData(this.value)" name="CustomerName" id="CustomerName" class="control-form select2" style="width: 100%;height:30px">
                                            <option value="" selected disabled>Choose Customer...</option>

                                            @foreach($Customers as $Customer)
                                            <option value="{{$Customer->id}}">{{$Customer->CustomerName}}</option>
                                            @endforeach
                                        </select>
                                        @else
                                        <label>Customer Name
                                        </label>
                                        <input name="CustomerName" id="CustomerName" class="control-form" style="width: 100%;height:30px">
                                        @endif


                                    </div>
                                   
                                   
                                    <div class="col-xs-6 col-sm-3">
                                        <label>Invoice</label>
                                        <input readonly required style="padding: 0;width: 100%;height: 30px;font-size: 14px;" id="Invoice" name="Invoice" type="text">
                                    </div>


                                    <div id="Cell_Field" class="col-xs-6 col-sm-3">
                                        <label>Cell</label>
                                        <input name="Cell" id="Cell" style="width: 100%;padding:0;height:30px">

                                    </div>
                                   

                                    <div id="Address_Field" class="col-xs-6 col-sm-3">
                                        <label>Address</label>
                                        <textarea rows="3" cols="30" name="Address" id="Address" style="width: 100%;padding:0;" required>
                                        </textarea>
                                    </div>


                                    <div id="Warrenty_Field" class="col-xs-6 col-sm-3">

                                        <label>{{Auth::user()->domainName == 'fsct' ? 'Notes' : 'Warrenty'}}</label>
                                        <textarea rows="3" cols="30" style="width: 100%;padding:0;" name="Warrenty" value="" id="Warrenty" placeholder="Warrenty">

                                    </textarea>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-6">
                                        <label>Category</label>
                                        <select name="Category" onchange="getItemsOfSelectedCategory(this.value)" id="Category" style="width: 100%;" required class="select2">


                                        </select>
                                    </div>
                                   
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
                                                        <select name="ItemName_other[]" onchange="getPriceOfSelectedItemName(this.value)" size="50" id="ItemName_other" class="select2" style="width: 100%;height:30px" required>
                                                        </select>
                                                        <input style="display:none" type='text' id="ItemName_other_hidden" name='ItemName_other_hidden[]'>
                                                    </td>
                                                    <td>
                                                        <input name="ItemCode[]" size="50" id="ItemCode" style="width: 100%;height:30px" required>
                                                    </td>

                                                    <td> <input size="5" type="number" oninput="changeInvoice_oninput_other()" name="Qty_other[]" id="Qty_other" style="width:100%;height:30px;"></td>
                                                    <td> <input size="5" name="Price_other[]" id="Price_other" oninput="changeInvoice_oninput_other()" style="width: 100%;height:30px;" type="number" required></td>
                                                    <td> <input size="5" name="Total_other[]" readonly id="Total_other" style="width: 100%;height:30px;" type="number" required></td>
                                                    <td><button onclick="addRow_other()" id="addRow" style="height: 30px;background:green;color:white;border:none" class="addRow">+</button></td>
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
                                        <input id="SaleTotal" readonly placeholder="Total" name="Total" style="width: 100%;" type="number" required>

                                    </div>

                                    <div class="col-xs-6 col-sm-12">
                                        <label>VAT %</label>
                                        <input type="number" oninput="changeInvoice_oninput_choose()" value="{{Auth::user()->VATPercentage}}" id="VATPercentage" name="VATPercentage" style="width: 100%;" required placeholder="Scheme Disc.">
                                    </div>
                                    <div class="col-xs-6 col-sm-12">
                                        <label>VAT Amount</label>

                                        <input type="number" placeholder="VAT Amount" name="VATPercentageAmount" id="VATPercentageAmount" class="control-form" style="width: 100%;" required>

                                    </div>


                                    <div class="col-xs-6 col-sm-12">
                                        <label>Discount</label>
                                        <input type="number" oninput="changeInvoice_oninput_choose()" id="Discount" name="Discount" style="width: 100%;" required class="" placeholder="% Discount">
                                    </div>

                                    <div class="col-xs-6 col-sm-12">
                                        <label>Final Total</label>
                                        <input type="number" id="FinalTotal" readonly name="FinalTotal" style="width: 100%;" required placeholder="Final Total">
                                    </div>
                                    <div id="PT" class="col-xs-6 col-sm-12">
                                        <label>Payment in Cash</label>
                                        <input type="checkbox" checked name="PaymentType" id="PaymentType">
                                    </div>
                                  

                                    <div style="width: 100%;margin-top:10px" class="col-xs-6 col-sm-12">
                                        <button style="padding: 7px;width:35%;" onclick="dispatch('save')" class="btn btn-primary">Save</button>
                                        <button style="padding: 7px;width:30%;" onclick="dispatch('print')" class="btn btn-primary">Print</button>
                                        <button style="padding: 7px;width:30%;" class="btn btn-primary" onclick="dispatch('pdf')"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></button>
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

  
    function changeInvoice_oninput_choose() {

        changeInvoice_oninput_other()
    }


    $.ajax({
        url: '/getCategoriesOfLoggenInUser',
        type: 'get',

        success: function(data) {

            let Categories = '<option disabled selected value="">Choose Category...</option>';
            data.forEach(el => {
                Categories += `
                        <option value="${el.id}">${el.Category}</option>
                        `;

                document.getElementById('Category').innerHTML = Categories;
                // document.getElementById('modal_Category').innerHTML = Categories;
            });
        }
    })

    var CustomerIndustry = document.getElementById('CustomerIndustry').value;
    if (CustomerIndustry == 'Regular') {
        document.getElementById('Cell').disabled = true;
        document.getElementById('Address').disabled = true;
       
    }



    $.ajax({
        url: '/getPaymentType_ForSaleInvoice',
        type: 'get',
        success: function(data) {

            let PaymentType = '<option disabled selected value="">Choose Payment Type...</option>';
            data.forEach(el => {
                PaymentType += `
                        <option value="${el.id}">${el.PaymentType}</option>
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
                let Categories = '<option disabled selected value="">Choose ItemName...</option>';
                data.forEach(el => {
                    Categories += `
                        <option value="${el.id}">${el.ItemName}</option>
                        `;

                    document.getElementById('ItemName_other').innerHTML = Categories;
                    // document.getElementById('modal_Category').innerHTML = Categories;
                });


            }
        })
    }

    function getPriceOfSelectedItemName(ItemName) {
        var ItemNameValue = '';
        if (ItemName != '') {

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
    }



    function addRow_other() {

        var selected_element = document.getElementById('ItemName_other');
        var ItemName = selected_element.options[selected_element.selectedIndex].text;
        var ItemId =  selected_element.value;
        var ItemCode = document.getElementById('ItemCode').value;
        var qty = document.getElementById('Qty_other').value;
        var price = document.getElementById('Price_other').value;
        var total = document.getElementById('Total_other').value;

        var tr =
            `<tr >
<td >
<input style="width:100%;height:30px;" readonly type='text' name='ItemName_other[]' value='${ItemName}'>
<input type='hidden' name='ItemName_other_hidden[]' value='${ItemId}'>
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
        changeInvoice_oninput_other();


    };


    function changeInvoice_oninput_other() {
        
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
       
        let paymentType = document.getElementById('PaymentType');

        for (var i = 0; i < qty.length; i++) {
            total[i].value = (qty[i].value * price[i].value).toFixed(2)
        }
        if (qty.length > 0) {
            for (var i = 1; i < qty.length; i++) {
                total1 = total1 + parseFloat(total[i].value);
            }
            SaleTotal.value = total1.toFixed(2);
            VATPercentageAmount.value = parseFloat(SaleTotal.value*VATPercentage.value)/100;
            FinalTotal.value = (SaleTotal.value) - Discount.value - VATPercentageAmount.value;
          
        } 
        
    }



    function deleteRow(e) {

        e.parentNode.parentNode.remove();

        changeInvoice_oninput_other();

    };

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
                    document.getElementById('Cell').value = data.Contact;
                    document.getElementById('Address').value = data.Address;
                }

            })

        }
    }
    // function for making invoice
    getInvoice()

    function getInvoice() {
        $.ajax({
            url: '/getInvoice',
            type: "get",
            success: function(data) {
                document.getElementById('Invoice').value = data;
            }
        })

    }


    todatDate();

    function dispatch(parameter) {

        $.ajax({
            url: '/checkDate_isPrevious',
            type: 'get',
            data: {
                Invoice: document.getElementById('Invoice').value
            },
            success: function(data) {
                console.log(data)
               
                ifData_isNot_previous()
                
            }
        })

        function ifData_isNot_previous() {


            var ItemNameValue = document.getElementsByName('ItemName_other_hidden[]');
            var ItemCode = document.getElementsByName('ItemCode[]');
            var qtyValue = document.getElementsByName('Qty_other[]');
            var priceValue = document.getElementsByName('Price_other[]');
            var totalValue = document.getElementsByName('Total_other[]');

            var obj = [];


            for (var i = 1; i < ItemNameValue.length; i++) {
                var ItemName1 = ItemNameValue[i].value;
                var ItemCode1 = ItemCode[i].value;
                var qty1 = qtyValue[i].value;
                var price1 = priceValue[i].value;
                var total1 = totalValue[i].value;


                var obje;
                obje = {
                    ItemName: ItemName1,
                    ItemCode: ItemCode1,
                    Qty: qty1,
                    Price: price1,
                    Total: total1,
                };
                obj.push(obje);
            }


            // alert(obj)
            console.log(obj);

            var Date = document.getElementById('Date').value;
            var Invoice = document.getElementById('Invoice').value;
            var CustomerName = document.getElementById('CustomerName').value;
            var Cell = document.getElementById('Cell').value;
            var Address = document.getElementById('Address').value;
           
            var SaleTotal = document.getElementById('SaleTotal').value;
            var FinalTotal = document.getElementById('FinalTotal').value;

            var VATPercentage = document.getElementById('VATPercentage').value;
            var VATPercentageAmount = document.getElementById('VATPercentageAmount').value;
            var Discount = document.getElementById('Discount').value;
            var PaymentType = document.getElementById('PaymentType').checked ? 1 : 0;// 1 for cash 0 for credit
            var Warrenty = document.getElementById('Warrenty').value;
           

            if (Date != '') {


                var token = '{{csrf_token()}}';
                // console.log(obj)
                $.ajax({
                    type: "post",
                    url: "/dispatch",
                    data: {
                        Ref: 'SR',
                        obj: obj,
                        Date: Date,
                        Invoice: Invoice,
                        CustomerName: CustomerName,
                        Cell: Cell,
                        Address: Address,
                        SaleTotal: SaleTotal,
                        VATPercentage: VATPercentage,
                        VATPercentageAmount: VATPercentageAmount,
                        Discount: Discount,
                        FinalTotal: FinalTotal,
                        PaymentType: PaymentType,
                        Warrenty: Warrenty,
                       
                        _token: token
                    },

                    success: function(data) {
                        console.log(data)

                        document.getElementById('Invoice').value = data.NewInvoice;

                        if (parameter == "print") {

                            window.open("/printInvoice?Invoice=" + data.Invoice, '_blank');

                            var CustomerIndustry = document.getElementById('CustomerIndustry').value;
                            console.log(CustomerIndustry)
                            if (CustomerIndustry == 'Regular') {
                                $('#CustomerName').val('').trigger('change');

                            } else {
                                document.getElementById('CustomerName').value = '';
                            }
                            $('#ItemName').val('').trigger('change');
                            document.getElementById('Cell').value = '';
                            
                            document.getElementById('Address').value = '';
                            document.getElementById('SaleTotal').value = '';

                            document.getElementById('Discount').value = '';
                            document.getElementById('Warrenty').value = '';


                            document.getElementById('VATPercentageAmount').value = '';
                            document.getElementById('FinalTotal').value = '';


                            var tablerows = document.getElementById("tablerows_other")
                            var count = tablerows.rows.length;

                            for (var a = count - 1; a > 1; a--) {
                                tablerows.rows[a].remove()
                            }

                            // $("#ItemCode").val('').trigger('change');
                        } else if (parameter=="pdf") {
                            window.open("/printInvoice?Invoice=" + data.Invoice+"&Pdf=Y", '_blank');

                            var CustomerIndustry = document.getElementById('CustomerIndustry').value;
                            console.log(CustomerIndustry)
                            if (CustomerIndustry == 'Regular') {
                                $('#CustomerName').val('').trigger('change');

                            } else {
                                document.getElementById('CustomerName').value = '';
                            }
                            $('#ItemName').val('').trigger('change');
                            document.getElementById('Cell').value = '';
                            
                            document.getElementById('Address').value = '';
                            document.getElementById('SaleTotal').value = '';

                            document.getElementById('Discount').value = '';
                            document.getElementById('Warrenty').value = '';


                            document.getElementById('VATPercentageAmount').value = '';
                            document.getElementById('FinalTotal').value = '';


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

                                var CustomerIndustry = document.getElementById('CustomerIndustry').value;
                                console.log(CustomerIndustry)
                                if (CustomerIndustry == 'Regular') {
                                    $('#CustomerName').val('').trigger('change');

                                } else {
                                    document.getElementById('CustomerName').value = '';
                                }
                                $('#ItemName').val('').trigger('change');
                                document.getElementById('Cell').value = '';
                               
                                document.getElementById('Address').value = '';
                                document.getElementById('SaleTotal').value = '';

                                document.getElementById('Discount').value = '';

                                document.getElementById('VATPercentageAmount').value = '';
                                document.getElementById('FinalTotal').value = '';



                                var tablerows = document.getElementById("tablerows_other")
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