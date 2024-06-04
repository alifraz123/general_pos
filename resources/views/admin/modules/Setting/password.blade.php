@extends('admin/layouts/mainlayout')
@section('content')

<div class="content-wrapper">


    <section class="content">
        <div id="show_insert_status">

        </div>
        <div  class="container-fluid">

            <div class="row">
                <div class="col-md-10">

                    <div  class="card card-default">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-6">
                                    <h3 class="card-title">Password Change</h3>
                                </div>

                            </div>

                        </div>

                        <div style="display:flex" class="">


                            <div style="width: 30%;margin-right:3px">
                                <label>Type New Password</label>
                                <input class="form-control" style="padding: 0;width: 100%;height: 30px;font-size: 14px;" id="password" name="password" type="password">
                            </div>
                            <div style="width: 30%;margin-right:3px">
                                <label>Reenter New Password</label>
                                <input class="form-control" style="padding: 0;width: 100%;height: 30px;font-size: 14px;" id="new_password" type="password">
                            </div>
                            <div style="margin-top:26px" style="width: 15%;margin-right:3px">

                                <button class="btn btn-success" onclick="save_password()">
                                    Save Password
                                </button>
                            </div>

                        </div>

                    </div>


                </div>


            </div>

        </div>


</div>

<script>
    $(".loader").hide()

    function save_password() {
        var passwrod = document.getElementById('password').value;
        var new_password = document.getElementById('new_password').value;
        var token = '{{csrf_token()}}';
        if (passwrod == new_password) {
            $.ajax({
                url: '/save_password',
                type: "post",
                data: {
                    password: document.getElementById('password').value,
                    _token:token
                },
                beforeSend: function() {
                    $(".loader").show()
                },
                complete: function() {
                    $(".loader").hide()
                },
                success: function(data) {
                    if (data == "changed") {
                        var output = `
                                <div class="alert alert-success">
            <button class="close" style="font-size: 30px;" data-dismiss="alert">&times</button>
            Password Successfuly Changed
        </div>    
                                `;
                        document.getElementById('show_insert_status').innerHTML = output;
                    } else {
                        var output = `
                                <div class="alert alert-success">
            <button class="close" style="font-size: 30px;" data-dismiss="alert">&times</button>
            Something went wrong
        </div>    
                                `;
                        document.getElementById('show_insert_status').innerHTML = output;
                    }

                }
            })
        } else {
            alert("Your password is not matching plesae Reenter your password!");
            document.getElementById('password').value = '';
            document.getElementById('new_password').value = '';
            document.getElementById('password').focus();
        }


    }
</script>



@endsection