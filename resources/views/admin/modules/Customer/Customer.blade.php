@extends('admin/layouts/mainlayout')
@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <h1>Customer</h1>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div id="zones" class="row">

                @if (session('status'))
                <h6 class="alert alert-success">{{ session('status') }}</h6>
                @endif

                <div class="box box-default">

                    <div class="box-body">
                        <form method="post" action="insertCustomer">
                            @csrf
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label>Customer Name</label>
                                        <input required id="CustomerName" type="text" name="CustomerName" class="form-control" placeholder="CustomerName">
                                        <label id="itemError"></label>
                                    </div>

                                </div>

                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label>Contact</label>
                                        <input type="text" required class="form-control" name="Contact" id="Contact">
                                        <label id="itemError"></label>
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label>Address</label>
                                        <textarea rows="3" cols="30" required class="form-control" name="Address" id="Address">
                                        </textarea>
                                        <label id="itemError"></label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <input type="submit" style="margin-top: 25px;" class="btn btn-success" value="Save">

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
                                    <th scope="col">Customer Name</th>
                                    <th scope="col">Contact</th>
                                    <th scope="col">Address</th>
                                    <th scope="col">Action</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach($Customers as $Customer)
                                <tr>
                                    <td>{{$Customer->CustomerName}}</td>
                                    <td>{{$Customer->Contact}}</td>
                                    <td>{{$Customer->Address}}</td>

                                    <td>
                                        <a style="color: white;width:100%;position:absolute">
                                            <img onclick="showHide('showHideDiv{{$Customer->id}}')" src="images/tripledot.png" style="width: 20px; height:20px;cursor:pointer;" alt="">
                                            <div style="width:10%; cursor:pointer;position: relative;top:0px;left:0px;display:none;z-index:10;padding:10px;background-color:white;color:black" class="showHideDiv{{$Customer->id}}">
                                                <p class="dropdown-item" onclick="getValueForEdit('{{$Customer->id}}','showHideDiv{{$Customer->id}}')">
                                                    Edit
                                                </p>

                                                <p class="dropdown-item " onclick="confirmToDelete('CustomerDelete/{{$Customer->id}}')">
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
                                        <h5 class="modal-title" id="exampleModalLabel">Edit Customer</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="post" action="updateCustomer">
                                            @csrf
                                            <input type="hidden" value="" id="id" name="id">
                                            <div class="box-body">
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                            <label>Customer Name</label>
                                                            <input style="width: 100%;" required type="text" id="modal_CustomerName" name="CustomerName" class="form-control" placeholder="Item Name">
                                                            <label id="updateItemError"></label>
                                                        </div>

                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                            <label>Customer VATNo</label>
                                                            <input type="text" style="width: 100%;" required class="form-control" name="CustomerVATNo" id="modal_CustomerVATNo" placeholder="Item Rate">
                                                        </div>
                                                    </div>


                                                </div>
                                                <div class="row">

                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                            <label>Contact</label>
                                                            <input type="text" style="width: 100%;" class="form-control" name="Contact" id="modal_Contact">

                                                        </div>
                                                    </div>

                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                            <label>Address</label>
                                                            <input type="text" class="form-control" name="Address" id="modal_Address">
                                                        </div>
                                                    </div>

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


    function getValueForEdit(id, editId) {
        document.getElementsByClassName(editId)[0].style.display = 'none';

        $.ajax({
            url: 'CustomerEdit',
            type: 'get',
            data: {
                id: id
            },
            beforeSend: function() {
                $(".loader").show();
            },

            success: function(data) {
                // console.log(data)
                if (data != '') {
                    document.getElementById('id').value = data.id;
                    document.getElementById('modal_CustomerName').value = data.CustomerName;
                    document.getElementById('modal_CustomerVATNo').value = data.CustomerVATNo;
                    document.getElementById('modal_Contact').value = data.Contact;
                    document.getElementById('modal_Address').value = data.Address;
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