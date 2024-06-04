@extends('admin/layouts/mainlayout')
@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <h1>
        Chart Of Accounts
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
                        <form id="formId" method="post" action="ChartOfAccounts">
                            @csrf
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input required id="Name" type="text" name="name" class="form-control" placeholder="Chart Of Account">
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label>Type</label>
                                        <select id="Type" type="text" name="type" class="form-control select2">
                                            <option value="" selected disabled>Type...</option>
                                            <option value="E">Expense</option>
                                            <option value="L">Liability</option>
                                            <option value="A">Asset</option>
                                            <option value="I">Income</option>
                                            <option value="O">Equity</option>
                                        </select>
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
                                    <th scope="col">Name</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Action</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach($chartofaccounts as $chartofaccount)
                                <tr>
                                    <td>{{$chartofaccount->name}}</td>
                                    <td>{{$chartofaccount->type}}</td>

                                    <td>
                                        <a style="color: white;width:100%;position:absolute">
                                            <img onclick="showHide('showHideDiv{{$chartofaccount->id}}')" src="images/tripledot.png" style="width: 20px; height:20px;cursor:pointer;" alt="">
                                            <div style="width:10%; cursor:pointer;position: relative;top:0px;left:0px;display:none;z-index:1;padding:10px;background-color:white;color:black" class="showHideDiv{{$chartofaccount->id}}">
                                                <p class="dropdown-item" onclick="getValueForEdit('{{$chartofaccount->id}}','showHideDiv{{$chartofaccount->id}}')">
                                                Edit
                                                </p>

                                                <p class="dropdown-item " onclick="confirmToDelete('ChartOfAccountDelete/{{$chartofaccount->id}}')">
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
                                        <h5 class="modal-title" id="exampleModalLabel">Edit Chart Of Accounts</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="updateformId" method="post" action="updateChartOfAccount">
                                            @csrf
                                            <input type="hidden" value="" id="modal_id" name="id">
                                            <div class="box-body">
                                                <div class="row">
                                                    <div class="col-sm-5">
                                                        <!-- text input -->
                                                        <div class="form-group">
                                                            <label>Name</label>
                                                            <input style="width: 100%;" required type="text" name="name" id="modal_name" required class="form-control">
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-5">
                                                        <!-- text input -->
                                                        <div class="form-group">
                                                            <label>Type</label>
                                                            <select style="width: 100%;" name="type" id="modal_type" required class="form-control select2">
                                                                    <option value="" selected disabled>Type...</option>
                                                            </select>
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


    function getValueForEdit(id, editId) {
        document.getElementsByClassName(editId)[0].style.display = 'none';
        $.ajax({
            url: 'ChartOfAccountEdit',
            type: 'get',
            data: {
                id: id
            },
            beforeSend: function() {
                $(".loader").show();
            },

            success: function(data) {
                console.log(data.chart_of_accounts_data.id)
                if (data.chart_of_account_data != '') {
                    document.getElementById('modal_id').value = data.chart_of_accounts_data.id;
                    document.getElementById('modal_name').value = data.chart_of_accounts_data.name;
                    let chart_of_accounts_types = '';
                    //here make code of if options of L,A,I and if data.type == any option theen select that
                    chart_of_accounts_types = 
                        '<option value="E" ' + (data.chart_of_accounts_data.type == 'E' ? 'selected' : '') + '>Expense</option>' +
                        '<option value="L" ' + (data.chart_of_accounts_data.type == 'L' ? 'selected' : '') + '>Liability</option>' +
                        '<option value="A" ' + (data.chart_of_accounts_data.type == 'A' ? 'selected' : '') + '>Asset</option>' +
                        '<option value="I" ' + (data.chart_of_accounts_data.type == 'I' ? 'selected' : '') + '>Income</option>' +
                        '<option value="O" ' + (data.chart_of_accounts_data.type == 'O' ? 'selected' : '') + '>Equity</option>';

                    document.getElementById('modal_type').innerHTML = chart_of_accounts_types;


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