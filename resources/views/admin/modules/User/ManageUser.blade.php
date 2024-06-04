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
        border-radius: 8px;
        background-color: #8ebf42;
        font-size: 16px;
        font-weight: bold;
        color: #fff;
    }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <h1>Users Management</h1>
    </section>
    <section class="content">

        <div class="box box-default">
            <div class="box-body">



                <table id="example1" class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>

                            <th>CR No.</th>
                            <th>Company Name</th>
                            <th>Article No.</th>
                            <th>Creation Date</th>
                            <th>status</th>

                            <th>Action</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr style="{{$user->UserType == 'Admin' ? 'color:red' : 'color:black'}}">
                            <td>{{$user->name}}</td>
                            <td>{{$user->email}}</td>
                            <td>{{$user->CRNO}}</td>
                            <td>{{$user->CompanyName}}</td>
                            <td>{{$user->ArticleNo}}</td>
                            <td>{{$user->created_at}}</td>
                            <td>{{$user->UserStatus}}</td>

                            <td>

                                <a style="color: white;width:100%;position:absolute">
                                    <img src="images/tripledot.png" onclick="showHide('showHideDiv{{$user->id}}')" style="width: 20px; height:20px;cursor:pointer;" alt="">

                                    <div style="width:10%; cursor:pointer;position: relative;top:0px;left:0px;display:none;z-index:10;padding:10px;background-color:white;color:black" class="showHideDiv{{$user->id}}">
                                        @if(Auth::user()->UserType == 'Admin')
                                        @if($user->UserStatus == 'Active')
                                        <p style="display:block" onclick="usermanagement('usermanagement/{{$user->id}}/suspend','suspend')">
                                            Suspend
                                        </p>

                                        @else
                                        <p style="display:block" onclick="usermanagement('usermanagement/{{$user->id}}/activate','activate')">
                                            Activate
                                        </p>

                                        @endif
                                        <p style="display:block" onclick="usermanagement('usermanagement/{{$user->id}}/FreeTrial','FreeTrial')">
                                            Free Trial
                                        </p>
                                        <p style="display:block" onclick="getValueForMessage('{{$user->id}}')">
                                            Message
                                        </p>

                                        <p onclick="confirmToDelete('createdUserDelete/{{$user->id}}')">
                                            Delete
                                        </p>
                                        @endif
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
                                <h5 class="modal-title" id="exampleModalLabel">Subscription Message</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">

                                <form method="post" id="updateformId" enctype="multipart/form-data" action="updateMessage">
                                    @csrf
                                    <input type="hidden" name="id" id="id">
                                    <input type="hidden" name="idname" id="idname">
                                    <div class="row">
                                        <div class="col-md-8">
                                            @if(Auth::user()->UserType == 'Admin')
                                            <div id="subscription_div" class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Subscription Message</label>
                                                        <textarea name="modal_SubscriptionMessage" id="modal_SubscriptionMessage" cols="80" rows="5"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            <div class="col-md-2">
                                                <input type="submit" style="margin-top: 26px;" class="btn btn-success" value="Update">

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


    function usermanagement(value, UserStatus) {
        if (UserStatus == 'suspend') {
            var status = confirm('Want to suspend user ?');
            if (status) {
                location.href = value;

            }
        } else if (UserStatus == 'activate') {
            var status = confirm('Want to activate user ?');
            if (status) {
                location.href = value;

            }

        } else if (UserStatus == 'FreeTrial') {
            var status = confirm('Want to assign free Trial ?');
            if (status) {
                location.href = value;

            }
        }
    }






    function getValueForMessage(id, editId) {
        $.ajax({
            url: 'SubscriptionMessage',
            type: 'get',
            data: {
                id: id
            },
            beforeSend: function() {
                $(".loader").show();
            },

            success: function(data) {
                if (data != '') {
                    console.log(data)
                    document.getElementById('id').value = data.id;
                    document.getElementById('idname').value = data.name;
                    document.getElementById('modal_SubscriptionMessage').value = data.SubscriptionMessage;

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