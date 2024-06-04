@extends('admin/layouts/mainlayout')
@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <h1>Purchaser</h1>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div id="zones" class="row">

                @if (session('status'))
                <h6 class="alert alert-success">{{ session('status') }}</h6>
                @endif

                <div class="box box-default">

                    <div class="box-body">
                        <form method="post" action="insertPurchaser">
                            @csrf
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label>Purchaser Name</label>
                                        <input required id="PurchaserName" type="text" name="PurchaserName" class="form-control" placeholder="PurchaserName">
                                    </div>

                                </div>

                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label>Contact</label>
                                        <input type="text" required class="form-control" name="Contact" id="Contact">
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label>Address</label>
                                        <textarea rows="3" cols="30" required class="form-control" name="Address" id="Address">
                                        </textarea>
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
                                    <th scope="col">Purchaser Name</th>
                                    <th scope="col">Contact</th>
                                    <th scope="col">Address</th>
                                    <th scope="col">Action</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach($purchasers as $Purchaser)
                                <tr>
                                    <td>{{$Purchaser->PurchaserName}}</td>
                                    <td>{{$Purchaser->Contact}}</td>
                                    <td>{{$Purchaser->Address}}</td>

                                    <td>
                                        <a style="color: white;width:100%;position:absolute">
                                            <img onclick="showHide('showHideDiv{{$Purchaser->id}}')" src="images/tripledot.png" style="width: 20px; height:20px;cursor:pointer;" alt="">
                                            <div style="width:10%; cursor:pointer;position: relative;top:0px;left:0px;display:none;z-index:10;padding:10px;background-color:white;color:black" class="showHideDiv{{$Purchaser->id}}">
                                                <p class="dropdown-item" onclick="getValueForEdit('{{$Purchaser->id}}','showHideDiv{{$Purchaser->id}}')">
                                                    Edit
                                                </p>

                                                <p class="dropdown-item " onclick="confirmToDelete('PurchaserDelete/{{$Purchaser->id}}')">
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
                                        <h5 class="modal-title" id="exampleModalLabel">Edit Purchaser</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="post" action="updatePurchaser">
                                            @csrf
                                            <input type="hidden" value="" id="id" name="id">
                                            <div class="box-body">
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                            <label>Purchaser Name</label>
                                                            <input style="width: 100%;" required type="text" id="modal_PurchaserName" name="PurchaserName" class="form-control" placeholder="Item Name">
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
            url: 'PurchaserEdit',
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
                    document.getElementById('modal_PurchaserName').value = data.PurchaserName;
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