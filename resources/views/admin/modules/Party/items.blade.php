@extends('admin/layouts/mainlayout')
@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <h1>Items</h1>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div id="zones" class="row">

                @if (session('status'))
                <h6 class="alert alert-success">{{ session('status') }}</h6>
                @endif

                <div class="box box-default">

                    <div class="box-body">
                        <form  id="formId" method="post" action="insertItems">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Item Name</label>
                                        <input type="text" required id="ItemName" name="ItemName" class="form-control" placeholder="Item Name">
                                    </div>

                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Item Code</label>
                                        <input type="text" class="form-control" name="ItemCode" id="ItemCode" placeholder="Item Code">
                                    </div>
                                </div>

                            </div>
                            <div class="row">

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Category</label>
                                        <select type="text" required  class="form-control select2" name="Category" id="Category">
                                            <option value="">Choose Category...</option>
                                        </select>
                                    </div>
                                </div>
                                @if(Auth::user()->SaleType == 'BarCode')
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Bar Code</label>
                                        <input type="BarCode"  class="form-control" name="BarCode" id="BarCode" placeholder="BarCode">
                                    </div>
                                </div>
                                @endif
                              
                                
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Sale Rate</label>
                                        <input type="number" step="any"  class="form-control" name="Rate" id="Rate" placeholder="Item Sale Rate">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Purchase Rate</label>
                                        <input type="number" step="any"  class="form-control" name="purchase_rate" id="PurchaseRate" placeholder="Item Purchase Rate">
                                    </div>
                                </div>
                                

                            </div>
                            
                            <div class="row">
                                <div class="col-md-2">
                                    <input type="submit" style="margin-bottom: 25px;" class="btn btn-success" value="Save">

                                </div>

                            </div>
                        </form>
                    </div>
                </div>

                <div class="box box-default">
                    <div class="box-body">

                        <table id="example1" class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Category</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Code</th>
                                    <th scope="col">Sale Rate</th>
                                    <th scope="col">Purchase Rate</th>
                                    <th scope="col">Quantity</th>
                                    @if(Auth::user()->SaleType == 'BarCode')
                                    <th>
                                        BarCode
                                    </th>
                                    @endif
                                    <th scope="col">Action</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                <tr>
                                    <td>{{$item->Category}}</td>
                                    <td>{{$item->ItemName}}</td>
                                    <td>{{$item->ItemCode}}</td>
                                    <td>{{$item->Rate}}</td>
                                    <td>{{$item->PurchaseRate}}</td>
                                    <td>{{$item->Qty}}</td>
                                    @if(Auth::user()->SaleType == 'BarCode')
                                    <td>
                                        {{$item->BarCode}}
                                    </td>
                                    @endif

                                    <td>
                                        <a style="color: white;width:100%;position:absolute">
                                            <img onclick="showHide('showHideDiv{{$item->id}}')" src="images/tripledot.png" style="width: 20px; height:20px;cursor:pointer;" alt="">
                                            <div style="width:10%; cursor:pointer;position: relative;top:0px;left:0px;display:none;z-index:10;padding:10px;background-color:white;color:black" class="showHideDiv{{$item->id}}">
                                                <p class="dropdown-item" onclick="getValueForEdit('{{$item->id}}','showHideDiv{{$item->id}}')">
                                                    Edit
                                                </p>

                                                <p class="dropdown-item " onclick="confirmToDelete('itemsDelete/{{$item->id}}')">
                                                    Delete
                                                </p>


                                            </div>
                                        </a>

                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog mw-100 w-50" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Edit ItemNames</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div> 
                                    <div class="modal-body">
                                        <form   id="updateformId" method="post" action="updateItems">
                                            @csrf
                                            <input type="hidden" value="" id="id" name="id">
                                            <input type="hidden" id="hidden_ItemName" name="hidden_ItemName">
                                            <div class="box-body">
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                            <label>Item Name</label>
                                                            <input style="width: 100%;" required type="text" id="modal_ItemName" name="ItemName" class="form-control" placeholder="Item Name">
                                                        </div>

                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                            <label>sale Rate</label>
                                                            <input type="number" step="any" style="width: 100%;"  class="form-control" name="Rate" id="modal_Rate" placeholder="Item Rate">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                            <label>Purchase Rate</label>
                                                            <input type="number" step="any" style="width: 100%;"  class="form-control" name="PurchaseRate" id="modal_PurchaseRate" placeholder="Item Rate">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                            <label>Item Code</label>
                                                            <input type="text" class="form-control" name="ItemCode" id="modal_ItemCode">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                            <label>Category</label>
                                                            <select class="form-control select2" name="Category" id="modal_Category">
                                                                <option value="">Choose Category...</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    
                                                    @if(Auth::user()->SaleType == 'BarCode')
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Bar Code</label>
                                                            <input type="text" class="form-control" name="BarCode" id="modal_BarCode">
                                                        </div>
                                                    </div>
                                                    @endif

                                                </div>
 
                                                <div class="row">
                                                 

                                                    <div class="col-md-2">
                                                        <input style=" margin-top: 25px;" value="Update" type="submit" class="btn btn-primary">

                                                    </div>
                                                </div>

                                            </div>
                                            <div class="box-footer">
                                            </div>
                                        </form>
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
</section>

</div>


<script>
    $(".loader").hide()

    function showHide(value) {
        var element = document.getElementsByClassName(value)[0]

        if (element.style.display == "none") {
            element.style.display = "block"

        } else {
            element.style.display = "none"
        }
    }

    function confirmToDelete(value) {
        var status = confirm('Want to delete ?');
        if (status) {
            location.href = value;
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


    function getValueForEdit(id, editId) {
        document.getElementsByClassName(editId)[0].style.display = 'none';

        $.ajax({
            url: 'itemsEdit',
            type: 'get',
            data: {
                id: id
            },
            beforeSend: function() {
                $(".loader").show();
            },

            success: function(data) {
                console.log(data)
                if (data != '') {
                    document.getElementById('id').value = data.item_data.id;
                    document.getElementById('hidden_ItemName').value = data.item_data.ItemName;
                    document.getElementById('modal_ItemName').value = data.item_data.ItemName;
                    document.getElementById('modal_Rate').value = data.item_data.Rate;
                    document.getElementById('modal_PurchaseRate').value = data.item_data.PurchaseRate;
                    document.getElementById('modal_ItemCode').value = data.item_data.ItemCode;
                    let categories = '';
                    data.categories.forEach(el => {
                        categories += `
                        <option value="${el.Category}" ${el.Category == data.item_data.Category ? 'selected' : ''}  >${el.Category}</option>
                        `;
                    
                    })
                    document.getElementById('modal_Category').innerHTML = categories;

                    @if(Auth::user()->SaleType == 'BarCode')
                    document.getElementById('modal_BarCode').value = data.item_data.BarCode ?? '';
                    @endif
                    $(".loader").hide();
                } else {
                    $(".loader").show();
                }

            },
            error: function(req, status, error) {
                console.log(error)

            }
        })
        $('#exampleModal').modal('show');
    }
</script>
@endsection