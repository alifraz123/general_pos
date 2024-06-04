@extends('admin/layouts/mainlayout')
@section('content')

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Edit Sale Return</h1>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div style="padding:20px" class="box box-default">
          <table id="example1" class="table">
            <thead>
              <tr>
                <th scope="col">Invoice</th>
                <th scope="col">Date</th>
                <th scope="col">Customer Name</th>
                <th scope="col">VAT Amount</th>
                <th scope="col">Total</th>
                <th scope="col">Operator</th>
                <th scope="col">Action</th>

              </tr>
            </thead>
            <tbody>
              @foreach($saleInvoices as $saleInvoice)
              <tr>
                <td>{{$saleInvoice->Invoice}}</td>
                <td>{{$saleInvoice->Date}}</td>
                <td>{{$saleInvoice->CustomerName}}</td>
                <td>{{$saleInvoice->VATPercentageValue}}</td>
                <td>{{$saleInvoice->FinalTotal}}</td>
                <td>{{$saleInvoice->user}}</td>

                <td>
                  <a style="color: white;width:100%;position:absolute">
                    <img onclick="showHide('showHideDiv{{$saleInvoice->Invoice}}')" src="images/tripledot.png" style="width: 20px; height:20px;cursor:pointer" alt="">

                    <div style="cursor:pointer;position: relative;top:0px;left:0px;display:none;z-index:1000;padding:10px;background-color:white;color:black" class="showHideDiv{{$saleInvoice->Invoice}}">
                      <p onclick="editFunction('edit_invoice?id={{$saleInvoice->Invoice}}','showHideDiv{{$saleInvoice->Invoice}}')">
                        Edit
                      </p>

                      <p class="dropdown-item " onclick="confirmToDelete('delete_dispatch?startDate={{$saleInvoice->Date}}&endDate={{$saleInvoice->Date}}&Invoice={{$saleInvoice->Invoice}}')">
                        Delete
                      </p>



                    </div>
                  </a>

                </td>
              </tr>
              @endforeach

            </tbody>
          </table>


        </div>
      </div>
    </div>
  </section>

</div>
<script>
  function showHide(value) {
    var element = document.getElementsByClassName(value)[0]

    if (element.style.display == "none") {
      element.style.display = "block"

    } else {
      element.style.display = "none"
    }
  }

  function editFunction(value, editId) {
    document.getElementsByClassName(editId)[0].style.display = 'none';
    window.open(value + '&Ref=SR', '_blank');
  }

  function confirmToDelete(value) {
    var status = confirm('Want to delete ?');
    if (status) {
      location.href = value;
    }

  }
  if (sessionStorage.getItem('update') == 'yes') {
    location.reload()
    sessionStorage.removeItem('update')
  }
</script>
@endsection