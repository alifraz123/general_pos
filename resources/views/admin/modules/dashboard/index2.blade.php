@extends('admin/layouts/mainlayout')
@section('content')

<div class="content-wrapper">

  <section class="content">
    @if ($UserStatus == 'FreeTrial')

    <div class="alert alert-danger alert-dismissible">
      <button class="close" style="font-size: 30px;" data-dismiss="alert">&times</button>
      <div id="show_subscription_status" style="font-size:large;"></div>

    </div>

    @endif


    <!-- Small boxes (Stat box) -->
    <div class="row">
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
          <div class="inner">
            <h3>0</h3>

            <p>Sale</p>
          </div>
          <div class="icon">
            <i class="ion ion-bag"></i>
          </div>
          <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
          <div class="inner">
            <h3>0<sup style="font-size: 20px">%</sup></h3>

            <p>NetSale</p>
          </div>
          <div class="icon">
            <i class="ion ion-stats-bars"></i>
          </div>
          <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
          <div class="inner">
            <h3>0</h3>

            <p>Cash</p>
          </div>
          <div class="icon">
            <i class="ion ion-person-add"></i>
          </div>
          <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
          <div class="inner">
            <h3>0</h3>

            <p>Balance</p>
          </div>
          <div class="icon">
            <i class="ion ion-pie-graph"></i>
          </div>
          <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>
     
    </div>


    <!-- /.row -->
    <!-- Main row -->

    <!-- /.row (main row) -->

  </section>


  <input type="text" hidden value="{{$SubscriptionMessage}}" id="SubscriptionMessage" name="SubscriptionMessage">

</div>
<script>
  $.ajax({
    url: '/getAccountHeadFromUserAccountTable',
    type: 'get',
    beforeSend: function() {


    },
    success: function(data) {
      // console.log(data)
      if (data != '') {

        var roles = '';
        roles += '<option disabled selected>Choose accounthead...</option>';
        data.forEach(el => {
          roles += `
                  <option value="${el.AccountHead}">${el.AccountHead}</option>
                  `;
          document.getElementById('AccountHead').innerHTML = roles;
        });
      } else {

      }
    },
    error: function(req, status, error) {
      console.log(error)

    }
  })
</script>
<script>
  var output = document.getElementById('SubscriptionMessage').value;
  document.getElementById('show_subscription_status').innerHTML += output;
</script>
@endsection