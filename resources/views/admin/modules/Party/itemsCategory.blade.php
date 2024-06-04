@extends('admin/layouts/mainlayout')
@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Items Category
        </h1>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div id="roles" class="row">

                @if (session('status'))
                <h6 class="alert alert-success">{{ session('status') }}</h6>
                @endif

                <div class="box box-default">

                    <div class="box-body">
                        <form onsubmit="event.preventDefault(); check()" enctype="multipart/form-data" id="formId" method="post" action="insertItemsCategory">
                            @csrf
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label>Category Name</label>
                                        <input required id="CategoryName" type="text" name="CategoryName" class="form-control" placeholder="Company Name">
                                        <label id="categoryError"></label>
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
                                    <th scope="col">Category Name</th>
                                    <th scope="col">Action</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $category)
                                <tr>
                                    <td>{{$category->Category}}</td>

                                    <td>
                                        <a style="color: white;width:100%;position:absolute">
                                            <img onclick="showHide('showHideDiv{{$category->id}}')" src="images/tripledot.png" style="width: 20px; height:20px;cursor:pointer;" alt="">
                                            <div style="width:10%; cursor:pointer;position: relative;top:0px;left:0px;display:none;z-index:1;padding:10px;background-color:white;color:black" class="showHideDiv{{$category->id}}">
                                                <p class="dropdown-item" onclick="getValueForEdit('{{$category->id}}','showHideDiv{{$category->id}}')">
                                                    Edit
                                                </p>

                                                <p class="dropdown-item " onclick="confirmToDelete('itemsCategoryDelete/{{$category->id}}')">
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
                                        <h5 class="modal-title" id="exampleModalLabel">Edit Items Category</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form onsubmit="event.preventDefault(); updatecheck()" enctype="multipart/form-data" id="updateformId" method="post" action="updateItemsCategory">
                                            @csrf
                                            <input type="hidden" value="" id="modal_id" name="id">
                                            <input type="hidden" value="" id="modal_Category_id"  name="modal_Category_id">
                                            <div class="box-body">
                                                <div class="row">
                                                    <div class="col-sm-5">
                                                        <!-- text input -->
                                                        <div class="form-group">
                                                            <label>Category Name</label>
                                                            <input style="width: 100%;" required type="text" name="CategoryName" id="modal_CategoryName" required class="form-control">
                                                            <label id="updateCategoryError"></label>
                                                        </div>
                                                    </div>

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

    document.getElementById('categoryError').style.display = 'none';

    function check() {
        var RoleValue = document.getElementById('CategoryName').value;
        if (RoleValue != '') {
            // console.log("first")
            $.ajax({
                url: 'checkDuplication',
                type: 'get',
                data: {
                    value: RoleValue,
                    type: 'categoryCheck'
                },
                beforeSend: function() {
                    $(".loader").show();
                },

                success: function(data) {
                    // console.log(data)
                    if (data != '') {
                        if (data == "Duplicate") {
                            document.getElementById('categoryError').style.display = 'block';
                            document.getElementById('categoryError').style.color = 'red';
                            document.getElementById('categoryError').innerText = RoleValue + " is already added please change value";
                        } else {


                            document.getElementById('formId').submit()
                        }
                        $(".loader").hide();
                    } else {
                        $(".loader").show();
                    }
                },
                error: function(req, status, error) {
                    console.log(error)
                }
            })
        }

    }


    document.getElementById('updateCategoryError').style.display = 'none';

    function updatecheck() {
        var RoleValue = document.getElementById('modal_CategoryName').value;
        var modal_id = document.getElementById('modal_Category_id').value;

        if (modal_id != RoleValue) {
            if (RoleValue != '') {
                // console.log("first")
                $.ajax({
                    url: 'checkDuplication',
                    type: 'get',
                    data: {
                        value: RoleValue,
                        type: 'categoryCheck'
                    },
                    beforeSend: function() {
                        $(".loader").show();
                    },

                    success: function(data) {
                        // console.log(data)
                        if (data != '') {
                            if (data == "Duplicate") {
                                document.getElementById('updateCategoryError').style.display = 'block';
                                document.getElementById('updateCategoryError').style.color = 'red';
                                document.getElementById('updateCategoryError').innerText = RoleValue + " is already added please change value";
                            } else {

                                document.getElementById('updateformId').submit()
                            }
                            $(".loader").hide();
                        } else {
                            $(".loader").show();
                        }
                    },
                    error: function(req, status, error) {
                        console.log(error)
                    }
                })
            }
        } else {
            document.getElementById('updateformId').submit()
        }

    }


    function getValueForEdit(CategoryName, editId) {
        document.getElementsByClassName(editId)[0].style.display = 'none';
        $.ajax({
            url: 'itemsCategoryEdit',
            type: 'get',
            data: {
                CategoryName: CategoryName
            },
            beforeSend: function() {
                $(".loader").show();
            },

            success: function(data) {
                // console.log(data)
                if (data != '') {
                    document.getElementById('modal_id').value = data.id;
                    document.getElementById('modal_Category_id').value = data.Category;
                    document.getElementById('modal_CategoryName').value = data.Category;
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