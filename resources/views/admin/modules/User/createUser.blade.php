@extends('admin/layouts/mainlayout')
@section('content')

<style>
    .container {
        display: flex;
        align-items: flex-start;
        justify-content: flex-start;
        width: 100%;
    }

    #upload {
        position: absolute;
        z-index: -1;
        top: 10px;
        left: 8px;
        font-size: 17px;
        color: #b8b8b8;
    }

    .button-wrap {
        position: relative;
    }

    .button {
        display: inline-block;
        padding: 12px 18px;
        cursor: pointer;
        border-radius: 5px;
        background-color: #8ebf42;
        font-size: 16px;
        font-weight: bold;
        color: #fff;
    }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <h1>Create User</h1>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div id="roles" class="row">

                @if (session('status'))
                <h6 class="alert alert-success">{{ session('status') }}</h6>
                @endif

                <div class="box box-default">

                    <div class=" box-body">
                        @if(Auth::user()->UserType == "Admin")
                        <form onsubmit="event.preventDefault(); check();" method="post" id="formId" enctype="multipart/form-data" action="insertCreatedUser">
                            @csrf
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Name</label>
                                                <input type="text" required onfocusout="chechNameDuplication(this.value)" id="name" name="name" class="form-control" placeholder="Name">
                                                <label id="nameError" for=""></label>
                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Email</label>
                                                <input type="email" required onfocusout="chechEmailDuplication(this.value)" id="email" name="email" class="form-control" placeholder="Email">
                                                <label id="emailError" for=""></label>
                                            </div>

                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Password</label>
                                                <input required type="text" name="password" class="form-control" placeholder="Password">

                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <label>CR No.</label>
                                                    <input required name="CRNO" class="form-control" style="width: 100%;">

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Company Name</label>
                                                <input required class="form-control" name="CompanyName" style="width: 100%;">

                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Company Name Arabic</label>
                                                <input required class="form-control" name="CompanyNameArabic" style="width: 100%;">

                                            </div>

                                        </div>


                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>VAT NO.</label>
                                                <input required class="form-control" name="VATNO" style="width: 100%;">

                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Cell</label>
                                                <input required class="form-control" name="Cell" style="width: 100%;">

                                            </div>

                                        </div>


                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Address</label>
                                                <textarea required type="text" name="Address" class="form-control" placeholder="Cell">
                                                </textarea>

                                            </div>

                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Article No.</label>
                                                <input required type="text" maxlength="3" name="ArticleNo" class="form-control" placeholder="">

                                            </div>

                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Customer Industry</label>
                                                <select required id="CustomerIndustry" name="CustomerIndustry" class="form-control select2" placeholder="CustomerIndustry">
                                                    <option value="" selected disabled>Choose Customer Industry...</option>
                                                    <option>Regular</option>
                                                    <option>Walk In</option>
                                                </select>
                                                <label id="nameError" for=""></label>
                                            </div>

                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Sale Type</label>
                                                <select required id="SaleType" name="SaleType" class="form-control select2" placeholder="SaleType">
                                                    <option value="" disabled selected>Choose Sale Type...</option>
                                                    <option>BarCode</option>
                                                    <option>Manual</option>
                                                </select>
                                                <label id="nameError" for=""></label>
                                            </div>

                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Show email on invoice</label>
                                                <select required id="ShowEmailOnInvoice" name="ShowEmailOnInvoice" class="form-control select2" placeholder="SaleType">
                                                    <option value="" disabled selected>Choose status...</option>
                                                    <option>Yes</option>
                                                    <option>No</option>
                                                </select>
                                                <label id="nameError" for=""></label>
                                            </div>

                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Apply Discount Type</label>
                                                <select id="VAT_Calculation" name="VAT_Calculation" class="form-control select2">
                                                    <option value="" disabled selected>Apply Discount Type</option>
                                                    <option>Before</option>
                                                    <option>After</option>
                                                </select>
                                                <label id="nameError" for=""></label>
                                            </div>

                                        </div>
                                    </div>


                                </div>
                                <div class="col-md-4">

                                    <div style="display: flex; justify-content:center;margin-bottom:20px" class="row">
                                        <img id="image" src="adminassets/dist/img/avatar5.png" style="border-radius:50%;width: 150px;height:150px" alt="">

                                    </div>


                                    <div style="display: flex; justify-content:center" class="row">
                                        <div class="form-group">
                                            <label class="button" for="upload">
                                                Choose File
                                                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-card-image" viewBox="0 0 16 16">
                                                    <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z" />
                                                    <path d="M1.5 2A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13zm13 1a.5.5 0 0 1 .5.5v6l-3.775-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12v.54A.505.505 0 0 1 1 12.5v-9a.5.5 0 0 1 .5-.5h13z" />
                                                </svg>

                                            </label>
                                            <input id="upload" name="image" type="file">

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <div class="col-md-2">
                                <input type="submit" style="margin-bottom: 30px;" class="btn btn-success" value="Save">

                            </div>
                    </div>

                </div>
                </form>
                @endif
            </div>
        </div>

        <div class="box box-default">
            <div class="box-body">



                <table id="example1" class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>

                            <th>CR No.</th>
                            <th>Company Name</th>
                            <th>Company Name Arabic</th>
                            <th>VAT NO.</th>
                            <th>Article No.</th>
                            <th>Show Email On Invoice</th>
                            <th>Action</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>{{$user->name}}</td>
                            <td>{{$user->email}}</td>
                            <td>{{$user->CRNO}}</td>
                            <td>{{$user->CompanyName}}</td>
                            <td>{{$user->CompanyNameArabic}}</td>
                            <td>{{$user->VATNO}}</td>
                            <td>{{$user->ArticleNo}}</td>
                            <td>{{$user->ShowEmailOnInvoice}}</td>
                            <td>

                                <a style="color: white;width:100%;position:absolute">
                                    <img src="images/tripledot.png" onclick="showHide('showHideDiv{{$user->id}}')" style="width: 20px; height:20px;cursor:pointer;" alt="">

                                    <div style="width:10%; cursor:pointer;position: relative;top:0px;left:0px;display:none;z-index:10;padding:10px;background-color:white;color:black" class="showHideDiv{{$user->id}}">
                                        <p style="display:block" onclick="getValueForEdit('{{$user->id}}','showHideDiv{{$user->id}}')">
                                            Edit
                                        </p>

                                        <p onclick="confirmToDelete('createdUserDelete/{{$user->id}}')">
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
                    <div class="modal-dialog mw-100 w-50 " style="width:90%;" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Edit User</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">

                                <form method="post" onsubmit="event.preventDefault(); check2();" id="updateformId" enctype="multipart/form-data" action="updateCreatedUser">
                                    @csrf
                                    <input type="hidden" name="modal_id" id="modal_id">
                                    <input type="hidden" name="modal_idname" id="modal_idname">
                                    <input type="hidden" name="modal_idemail" id="modal_idemail">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">

                                                        <label for="">Name</label>
                                                        <input type="text" readonly required onfocusout="updateNameCheck(this.value)" name="modal_name" id="modal_name" class="form-control" placeholder="Name">
                                                        <label id="update_nameError"></label>
                                                    </div>

                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="">Email</label>
                                                        <input type="email" required onfocusout="updateEmailCheck(this.value)" name="modal_email" id="modal_email" class="form-control" placeholder="Email">
                                                        <label id="update_emailError"></label>
                                                    </div>

                                                </div>

                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <input type="hidden" id="hidden_UserPassword" name="hidden_UserPassword">
                                                        <label for="">Password</label>
                                                        <input type="text" id="modal_password" name="modal_password" class="form-control" placeholder="Password">

                                                    </div>

                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="form-group">
                                                            <label>CR No.</label>
                                                            <input required name="modal_CRNO" id="modal_CRNO" class="form-control" style="width: 100%;">
                                                        </div>

                                                    </div>

                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Company Name</label>
                                                        <input required type="text" id="modal_CompanyName" name="modal_CompanyName" class="form-control" placeholder="Cell">

                                                    </div>

                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Company Name Arabic</label>
                                                        <input required class="form-control" id="modal_CompanyNameArabic" name="modal_CompanyNameArabic" multiple="multiple" data-placeholder="Select a Role" style="width: 100%;">

                                                    </div>

                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">

                                                    <label>VAT No. Arabic</label>
                                                    <input type="text" id="VATNO_Arabic" name="VATNO_Arabic" class="form-control">

                                                </div>


                                            </div>

                                            <div class="row">

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>VAT NO.</label>
                                                        <input required class="form-control" id="modal_VATNO" name="modal_VATNO" multiple="multiple" data-placeholder="Select a Role" style="width: 100%;">

                                                    </div>

                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Cell</label>
                                                        <input required class="form-control" id="modal_Cell" name="modal_Cell" multiple="multiple" data-placeholder="Select a Role" style="width: 100%;">

                                                    </div>

                                                </div>
                                            </div>

                                            <div id="userInvoice_customerIndustry_saletype_div" class="row">

                                                <div id="CustomerIndustry" class="col-md-4">
                                                    <label>Customer Industry</label>
                                                    <select class="form-control select2" type="text" id="modal_CustomerIndustry" name="CustomerIndustry" style="width:100%">

                                                    </select>

                                                </div>

                                                <div id="SaleType" class="col-md-4">
                                                    <label>Sale Type</label>
                                                    <select class="form-control select2" type="text" id="modal_SaleType" name="SaleType" style="width:100%">

                                                    </select>

                                                </div>
                                            </div>

                                            <div class="row">

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Address</label>
                                                        <input required class="form-control" id="modal_Address" name="modal_Address" multiple="multiple" data-placeholder="Select a Role" style="width: 100%;">

                                                    </div>

                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Article No.</label>
                                                        <input required readonly class="form-control" maxlength="3" id="modal_ArticleNo" name="modal_ArticleNo" style="width: 100%;">

                                                    </div>

                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>English Description</label>
                                                    <input type="text" placeholder="English Description" id="modal_English_Description" name="English_Description" class="form-control">

                                                </div>
                                                <div class="col-md-6">
                                                    <label>Arabic Description</label>
                                                    <input type="text" placeholder="Arabic Description" id="modal_Arabic_Description" name="Arabic_Description" class="form-control">

                                                </div>

                                            </div>

                                            <div id="Invoice_Side_Content" class="row">
                                                <div class="col-md-6">
                                                    <label>Invoice Side Arabic</label>
                                                    <input type="text" placeholder="Invoice Side Arabic" id="modal_Side_Arabic_Description" name="modal_Side_Arabic_Description" class="form-control">

                                                </div>
                                                <div class="col-md-6">
                                                    <label>Invoice Side English</label>
                                                    <input type="text" placeholder="Invoice Side English" id="modal_Side_English_Description" name="modal_Side_English_Description" class="form-control">

                                                </div>

                                            </div>


                                            <div class="row">
                                                <input type="hidden" id="hidden_InvoicePic" name="hidden_InvoicePic">
                                                <div class="col-md-6">
                                                    <label>Invoice Pic</label>
                                                    <input type="file" name="InvoicePic" class="form-control" id="">

                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Business Type English</label>
                                                    <input type="text" id="BusinessTypeEnglish" name="BusinessTypeEnglish" class="form-control">

                                                </div>
                                                <div class="col-md-6">
                                                    <label>Business Type Arabic</label>
                                                    <input type="text" id="BusinessTypeArabic" name="BusinessTypeArabic" class="form-control">
                                                </div>

                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Business Description English</label>
                                                    <input type="text" id="BusinessDescriptionEnglish" name="BusinessDescriptionEnglish" class="form-control">

                                                </div>
                                                <div class="col-md-6">
                                                    <label>Business Description Arabic</label>
                                                    <input type="text" id="BusinessDescriptionArabic" name="BusinessDescriptionArabic" class="form-control">
                                                </div>

                                            </div>


                                            <div class="row">

                                                <div id="Admin_Section_language" class="col-md-6">
                                                    <input type="hidden" id="hidden_language" name="hidden_language">
                                                    <label>Language</label>
                                                    <select type="text" id="modal_language" name="language" style="width: 100%;" class="select2">

                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>VAT %</label>
                                                    <input type="text" name="VATPercentage" id="modal_VATPercentage" class="form-control">
                                                </div>



                                            </div>

                                            <div class="row">
                                                <div id="Admin_Section_ShowEmailOnInvoice" class="col-md-6">
                                                    <label>Show Email On Invoice</label>
                                                    <select id="modal_ShowEmailOnInvoice" name="ShowEmailOnInvoice" style="width: 100%;" class="select2">
                                                    </select>
                                                </div>

                                                <div id="Admin_Section_VAT_Calculation" class="col-md-6">
                                                    <label>Apply Discount Type</label>
                                                    <select id="modal_VAT_Calculation" name="VAT_Calculation" style="width: 100%;" class="select2">
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row">



                                                <div class="col-md-2">
                                                    <input type="submit" style="margin-top: 26px;" class="btn btn-success" value="Update">

                                                </div>

                                            </div>

                                        </div>
                                        <div class="col-md-4">

                                            <div style="display: flex; justify-content:center;margin-bottom:20px" class="row">
                                                <img id="modal_image" style="border-radius:50%;width: 150px;height:150px" alt="">

                                            </div>


                                            <div style="display: flex; justify-content:center" class="row">
                                                <div class="form-group">
                                                    <label class="button" for="modal_upload">
                                                        Choose File
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-card-image" viewBox="0 0 16 16">
                                                            <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z" />
                                                            <path d="M1.5 2A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13zm13 1a.5.5 0 0 1 .5.5v6l-3.775-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12v.54A.505.505 0 0 1 1 12.5v-9a.5.5 0 0 1 .5-.5h13z" />
                                                        </svg>

                                                    </label>
                                                    <input type="hidden" id="hidden_UserPic" name="hidden_UserPic">
                                                    <input id="modal_upload" style="display: none;" name="modal_image" type="file">

                                                </div>

                                            </div>

                                        </div>



                                    </div>

                            </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
</div>

<input type="hidden" value="{{Auth::user()->UserType}}" id="UserType">

<script>
    $(".loader").hide()
    var domainName = document.getElementById('domainName').value;
    if (domainName == 'ewct') {
        document.getElementById('Invoice_Side_Content').style.display = 'none';
    }



    var usertype = document.getElementById('UserType').value;
    if (usertype == "Admin") {
        document.getElementById('modal_name').readOnly = false;
        document.getElementById('modal_ArticleNo').readOnly = false;

    }
    if (usertype != 'Admin') {
        document.getElementById('Admin_Section_DomainName').style.display = 'none';
        document.getElementById('Admin_Section_UserInvoice').style.display = 'none';
        document.getElementById('Admin_Section_language').style.display = 'none';
        document.getElementById('Admin_Section_ShowEmailOnInvoice').style.display = 'none';
        document.getElementById('Admin_Section_VAT_Calculation').style.display = 'none';
    }


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
    document.getElementById('nameError').style.display = 'none';

    function chechNameDuplication(name) {
        $.ajax({
            url: 'checkDuplication',
            type: 'get',
            data: {
                value: name,
                type: 'createUserNameCheck'
            },
            beforeSend: function() {
                $(".loader").show();
            },

            success: function(data) {
                // console.log(data)
                if (data != '') {
                    if (data == "Duplicate") {
                        document.getElementById('nameError').style.display = 'block';
                        document.getElementById('nameError').style.color = 'red';
                        document.getElementById('nameError').innerText = name + " is already added please change value";
                        document.getElementById('name').value = '';
                        document.getElementById('name').focus();
                    } else {
                        document.getElementById('nameError').style.display = 'none';

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


    document.getElementById('update_nameError').style.display = 'none';

    function updateNameCheck(name) {
        var modal_id = document.getElementById('modal_idname').value;
        if (modal_id != name) {
            $.ajax({
                url: 'checkDuplication',
                type: 'get',
                data: {
                    value: name,
                    type: 'createUserNameCheck'
                },
                beforeSend: function() {
                    $(".loader").show();
                },

                success: function(data) {
                    // console.log(data)
                    if (data != '') {
                        if (data == "Duplicate") {
                            document.getElementById('update_nameError').style.display = 'block';
                            document.getElementById('update_nameError').style.color = 'red';
                            document.getElementById('update_nameError').innerText = name + " is already added please change value";
                            document.getElementById('modal_name').value = '';
                            document.getElementById('modal_name').focus();
                        } else {
                            document.getElementById('update_nameError').style.display = 'none';

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

    function check() {
        var nameValue = '';
        var emailValue = '';
        var name = document.getElementById('name').value;
        $.ajax({
            url: 'checkDuplication',
            type: 'get',
            data: {
                value: name,
                type: 'createUserNameCheck'
            },
            beforeSend: function() {
                $(".loader").show();
            },

            success: function(data) {
                // console.log(data)
                if (data != '') {
                    if (data == "Duplicate") {

                        document.getElementById('nameError').style.display = 'block';
                        document.getElementById('nameError').style.color = 'red';
                        document.getElementById('nameError').innerText = name + " is already added please change value";
                        document.getElementById('name').value = '';
                        document.getElementById('name').focus();
                    } else {
                        nameValue = 'fill';
                        bb(nameValue);
                        document.getElementById('nameError').style.display = 'none';

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

        var email = document.getElementById('email').value;
        $.ajax({
            url: 'checkDuplication',
            type: 'get',
            data: {
                value: email,
                type: 'createUserEmailCheck'
            },
            beforeSend: function() {
                $(".loader").show();
            },

            success: function(data) {
                // console.log(data)
                if (data != '') {
                    if (data == "Duplicate") {

                        document.getElementById('emailError').style.display = 'block';
                        document.getElementById('emailError').style.color = 'red';
                        document.getElementById('emailError').innerText = email + " is already added please change value";
                        document.getElementById('email').value = '';
                        document.getElementById('email').focus();
                    } else {
                        emailValue = 'fill';
                        bb(emailValue);
                        document.getElementById('emailError').style.display = 'none';

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
        var count = 0;

        function bb(value) {
            if (value != '') {
                count++;
            }
            if (count == 2) {
                bbmethod()
            }
        }

        function bbmethod() {
            document.getElementById('formId').submit()
        }


    }

    document.getElementById('emailError').style.display = 'none';

    function chechEmailDuplication(email) {
        $.ajax({
            url: 'checkDuplication',
            type: 'get',
            data: {
                value: email,
                type: 'createUserEmailCheck'
            },
            beforeSend: function() {
                $(".loader").show();
            },

            success: function(data) {
                // console.log(data)
                if (data != '') {
                    if (data == "Duplicate") {
                        document.getElementById('emailError').style.display = 'block';
                        document.getElementById('emailError').style.color = 'red';
                        document.getElementById('emailError').innerText = email + " is already added please change value";
                        document.getElementById('email').value = '';
                        document.getElementById('email').focus();
                    } else {
                        document.getElementById('emailError').style.display = 'none';

                    }
                    $(".loader").hide();
                } else {
                    $(".loader").show();
                }
            },
            error: function(req, status, error) {
                console.log(error);
            }
        })
    }

    document.getElementById('update_emailError').style.display = 'none';

    function updateEmailCheck(email) {
        var modal_id = document.getElementById('modal_idemail').value;
        if (modal_id != email) {
            $.ajax({
                url: 'checkDuplication',
                type: 'get',
                data: {
                    value: email,
                    type: 'createUserEmailCheck'
                },
                beforeSend: function() {
                    $(".loader").show();
                },

                success: function(data) {
                    // console.log(data)
                    if (data != '') {
                        if (data == "Duplicate") {
                            document.getElementById('update_emailError').style.display = 'block';
                            document.getElementById('update_emailError').style.color = 'red';
                            document.getElementById('update_emailError').innerText = email + " is already added please change value";
                            document.getElementById('modal_email').value = '';
                            document.getElementById('modal_email').focus();
                        } else {
                            document.getElementById('update_emailError').style.display = 'none';

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




    function check2() {
        var updateNameValue = '';
        var updateEmailValue = '';
        var count = 0;
        var name = document.getElementById('modal_name').value;
        var modal_id = document.getElementById('modal_idname').value;
        if (modal_id != name) {
            updateNameValue = '';
            $.ajax({
                url: 'checkDuplication',
                type: 'get',
                data: {
                    value: name,
                    type: 'createUserNameCheck'
                },
                beforeSend: function() {
                    $(".loader").show();
                },

                success: function(data) {
                    // console.log(data)
                    if (data != '') {
                        if (data == "Duplicate") {

                            document.getElementById('update_nameError').style.display = 'block';
                            document.getElementById('update_nameError').style.color = 'red';
                            document.getElementById('update_nameError').innerText = name + " is already added please change value";
                            document.getElementById('modal_name').value = '';
                            document.getElementById('modal_name').focus();
                        } else {
                            updateNameValue = 'fill';
                            bb(updateNameValue)
                            document.getElementById('update_nameError').style.display = 'none';

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
        } else {
            updateNameValue = 'fill';

            bb(updateNameValue)
        }

        var email = document.getElementById('modal_email').value;
        var modal_id = document.getElementById('modal_idemail').value;
        if (modal_id != email) {
            updateEmailValue = '';
            $.ajax({
                url: 'checkDuplication',
                type: 'get',
                data: {
                    value: email,
                    type: 'createUserEmailCheck'
                },
                beforeSend: function() {
                    $(".loader").show();
                },

                success: function(data) {
                    // console.log(data)
                    if (data != '') {
                        if (data == "Duplicate") {
                            document.getElementById('update_emailError').style.display = 'block';
                            document.getElementById('update_emailError').style.color = 'red';
                            document.getElementById('update_emailError').innerText = email + " is already added please change value";
                            document.getElementById('modal_email').value = '';
                            document.getElementById('modal_email').focus();
                        } else {

                            updateEmailValue = 'fill';
                            bb(updateEmailValue)
                            document.getElementById('update_emailError').style.display = 'none';

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
        } else {
            updateEmailValue = 'fill';

            bb(updateEmailValue)
        }


        function bb(value) {

            if (value != '') {
                count++;
            }

            if (count == 2) {
                bbmethod()
            }
        }

        function bbmethod() {

            document.getElementById('updateformId').submit()
        }
    }

    let input = document.getElementById('upload');
    let img = document.getElementById('image');
    var _URL = window.URL || window.webkitURL;
    input.addEventListener('change', () => {
        let file = input.files[0];
        var ImageName = file.name;

        var ImageExtension = ImageName.split('.');

        if (ImageExtension[1] == 'jpg' || ImageExtension[1] == 'png') {
            if (file) {
                let reader = new FileReader();
                reader.addEventListener('load', () => img.src = reader.result);
                reader.readAsDataURL(file);
            } else {
                img.src = "images/blank.png";

            }
        } else {
            alert("Please select image of jpg or png type")
        }


    });


    let input2 = document.getElementById('modal_upload');
    let img2 = document.getElementById('modal_image');
    input2.addEventListener('change', () => {
        let file = input2.files[0];


        if (file) {
            let reader = new FileReader();
            reader.addEventListener('load', () => img2.src = reader.result);
            reader.readAsDataURL(file);
        } else {
            img2.src = "images/blank.png";

        }
    });



    function getValueForEdit(id, editId) {
        document.getElementsByClassName(editId)[0].style.display = 'none';

        $.ajax({
            url: 'createdUserEdit',
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
                    document.getElementById('modal_id').value = data.id;
                    document.getElementById('modal_idname').value = data.name;
                    document.getElementById('modal_idemail').value = data.email;
                    document.getElementById('modal_name').value = data.name;
                    document.getElementById('modal_email').value = data.email;
                    document.getElementById('modal_CRNO').value = data.CRNO;
                    document.getElementById('modal_CompanyName').value = data.CompanyName;
                    document.getElementById('modal_CompanyNameArabic').value = data.CompanyNameArabic;
                    document.getElementById('modal_VATNO').value = data.VATNO;
                    document.getElementById('modal_Cell').value = data.cell;
                    document.getElementById('modal_Address').value = data.Addres;
                    document.getElementById('modal_ArticleNo').value = data.ArticleNo;
                    document.getElementById('modal_English_Description').value = data.Detail_English;
                    document.getElementById('modal_Arabic_Description').value = data.Detail_Arabic;
                    document.getElementById('modal_Side_Arabic_Description').value = data.Side_Detail_Arabic;
                    document.getElementById('modal_Side_English_Description').value = data.Side_Detail_English;
                    document.getElementById('hidden_InvoicePic').value = data.Invoice_pic;
                    document.getElementById('hidden_UserPic').value = data.image;
                    document.getElementById('hidden_UserPassword').value = data.password;
                    document.getElementById('BusinessTypeEnglish').value = data.BusinessTypeEnglish;
                    document.getElementById('BusinessTypeArabic').value = data.BusinessTypeArabic;
                    document.getElementById('BusinessDescriptionEnglish').value = data.BusinessDescriptionEnglish;
                    document.getElementById('BusinessDescriptionArabic').value = data.BusinessDescriptionArabic;
                    document.getElementById('VATNO_Arabic').value = data.VATNO_Arabic;


                    let CustomerIndustry = '<option disabled selected value="">Customer Industry...</option>';
                    CustomerIndustry += `
                   <option>Regular</option>
                   <option>Walk In</option>
                   `;
                    document.getElementById('modal_CustomerIndustry').innerHTML = CustomerIndustry;


                    if (data.CustomerIndustry == 'Regular') {
                        document.getElementById("modal_CustomerIndustry")[1].selected = true
                    } else if (data.CustomerIndustry == 'Walk In') {
                        document.getElementById("modal_CustomerIndustry")[2].selected = true
                    }

                    let ShowEmailOnInvoice = '<option disabled selected value="">Customer Industry...</option>';
                    ShowEmailOnInvoice += `
                   <option>Yes</option>
                   <option>No</option>
                   `;
                    document.getElementById('modal_ShowEmailOnInvoice').innerHTML = ShowEmailOnInvoice;


                    if (data.ShowEmailOnInvoice == 'Yes') {
                        document.getElementById("modal_ShowEmailOnInvoice")[1].selected = true
                    } else if (data.ShowEmailOnInvoice == 'No') {
                        document.getElementById("modal_ShowEmailOnInvoice")[2].selected = true
                    }

                    let VAT_Calculation = '<option disabled selected value="">Apply Discount Type...</option>';
                    VAT_Calculation += `
                   <option>Before</option>
                   <option>After</option>
                   `;
                    document.getElementById('modal_VAT_Calculation').innerHTML = VAT_Calculation;


                    if (data.VAT_Calculation == 'Before') {
                        document.getElementById("modal_VAT_Calculation")[1].selected = true
                    } else if (data.VAT_Calculation == 'After') {
                        document.getElementById("modal_VAT_Calculation")[2].selected = true
                    }

                    let SaleType = '<option disabled selected value="">Sale Type...</option>';
                    SaleType += `
                   <option>BarCode</option>
                   <option>Manual</option>
                   `;
                    document.getElementById('modal_SaleType').innerHTML = SaleType;


                    if (data.SaleType == 'BarCode') {
                        document.getElementById("modal_SaleType")[1].selected = true
                    } else if (data.SaleType == 'Manual') {
                        document.getElementById("modal_SaleType")[2].selected = true
                    }


                    document.getElementById('hidden_language').value = data.language;
                    let language = '<option disabled selected value="">Choose language...</option>';
                    language += `
                   <option >english</option>
                   <option >arabic</option>
                   `;
                    document.getElementById('modal_language').innerHTML = language;

                    if (data.language == 'english') {
                        document.getElementById("modal_language")[1].selected = true
                    } else if (data.language == 'arabic') {
                        document.getElementById("modal_language")[2].selected = true
                    }

                    document.getElementById('modal_VATPercentage').value = data.VATPercentage;

                    document.getElementById('hidden_DomainName').value = data.domainName;
                    document.getElementById('hidden_UserInvoice').value = data.user_template;

                    let UserInvoice = '<option disabled selected value="">Choose Item Code...</option>';
                    UserInvoice += `
                   <option >ewct</option>
                   <option >fsct</option>
                   <option >iwct</option>
                   <option >osct</option>
                   <option >cwct</option>
                   <option >tsct</option>
                   <option >wsct</option>
                   `;
                    document.getElementById('modal_UserInvoice').innerHTML = UserInvoice;

                    if (data.user_template == 'ewct') {
                        document.getElementById("modal_UserInvoice")[1].selected = true
                    } else if (data.user_template == 'fsct') {
                        document.getElementById("modal_UserInvoice")[2].selected = true
                    } else if (data.user_template == 'iwct') {
                        document.getElementById("modal_UserInvoice")[3].selected = true
                    } else if (data.user_template == 'osct') {
                        document.getElementById("modal_UserInvoice")[4].selected = true
                    } else if (data.user_template == 'cwct') {
                        document.getElementById("modal_UserInvoice")[5].selected = true
                    } else if (data.user_template == 'tsct') {
                        document.getElementById("modal_UserInvoice")[6].selected = true
                    } else if (data.user_template == 'wsct') {
                        document.getElementById("modal_UserInvoice")[7].selected = true
                    }

                    let DomainName = '<option disabled selected value="">Choose Domain Name...</option>';
                    DomainName += `
                   <option>ewct</option>
                   <option>fsct</option>
                   <option>iwct</option>
                   <option>osct</option>
                   <option>cwct</option>
                   <option>tsct</option>
                   <option>wsct</option>
                   `;
                    document.getElementById('modal_DomainName').innerHTML = DomainName;


                    if (data.domainName == 'ewct') {
                        document.getElementById("modal_DomainName")[1].selected = true
                    } else if (data.domainName == 'fsct') {
                        document.getElementById("modal_DomainName")[2].selected = true
                    } else if (data.domainName == 'iwct') {
                        document.getElementById("modal_DomainName")[3].selected = true
                    } else if (data.domainName == 'osct') {

                        document.getElementById("modal_DomainName")[4].selected = true
                    } else if (data.domainName == 'cwct') {

                        document.getElementById("modal_DomainName")[5].selected = true
                    } else if (data.domainName == 'tsct') {
                        document.getElementById("modal_DomainName")[6].selected = true
                    } else if (data.domainName == 'wsct') {
                        document.getElementById("modal_DomainName")[7].selected = true
                    }




                    if (data.image != null) {

                        document.getElementById('modal_image').src = data.image;

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
        $('#exampleModal').modal('show');
    }
</script>
@endsection