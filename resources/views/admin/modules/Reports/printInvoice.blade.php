@extends('admin/layouts/reportlayout')
@section('content')
<!-- onload="window.print()" -->
<body onload="window.print()">
	
			<div class="row">
				<div class="col-xs-12">
					<h2 class="" style="text-align:center">
						<span class="pull-left"><img src="/storage{{ substr(Auth::user()->image,6) }}" width="128" height="128"/>
						</span>
						<span class="pull-right" id="qrc"></span>
						فاتورة ضريبية
						<br>Tax Invoice
						<br>
						<small>
							<div class="row">
								<div class="col-sm-4">
									<table class="table table-condensed table-bordered">
										<thead>
											<tr>
												<th>Invoice #</th>
												<th>
													<strong>{{$salebook->Invoice}}</strong>
												</th>
												<th>
													<span class="pull-right">فاتورة</span>
												</th>
											</tr>
											<tr>
												<th>Date / Time </th>
												<th>
													<strong>{{$salebook->invoiceDateTime}}</strong>
												</th>
												<th>
													<span class="pull-right">التاريخ/الوقت</span>
												</th>
											</tr>
										</thead>
									</table>
								</div>
							</div>
						</small>
					</h2>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-6 table-responsive ">
					<div class="table table-condensed table-bordered">
						<table class="table table-condensed table-bordered">
							<thead>
								<tr>
									<th colspan="3">Seller:
										<span class="pull-right">المورد</span>
									</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>Name</td>
									<td style="text-align:center">
										<span >{{Auth::user()->CompanyNameArabic}}</span><br>
										<span >{{Auth::user()->CompanyName}}</span>
									</td>
									<td>
										<span class="pull-right">الإسم</span>
									</td>
								</tr>
								<tr>
									<td>VatNo</td>
									<td style="text-align:center">
										<span >{{Auth::user()->VATNO}}</span>
									</td>
									<td>
										<span class="pull-right">رقم ضريبة
										</span>
									</td>
								</tr>
								<tr>
									<td>Cell</td>
									<td style="text-align:center">{{Auth::user()->cell}}</td>
									<td>
										<span class="pull-right">جوال رقم</span>
									</td>
								</tr>
								<tr>
									<td>Address</td>
									<td style="text-align:center">
										<span >{{Auth::user()->Addres}}</span>
									</td>
									<td>
										<span class="pull-right">العنوان</span>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="col-xs-6 table-responsive ">
					<div class="table table-condensed table-bordered">
						<table class="table table-condensed table-bordered">
							<thead>
								<tr>
									<th colspan="3">Cutomer:
										<span class="pull-right">العميل</span>
									</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>Name</td>
									<td style="text-align:center">{{$salebook->CustomerName}}</td>
									<td>
										<span class="pull-right">الإسم</span>
									</td>
								</tr>
								<tr>
									<td>VatNo</td>
									<td style="text-align:center">{{$salebook->VATNO}}</td>
									<td>
										<span class="pull-right">رقم ضريبة
										</span>
									</td>
								</tr>
								<tr>
									<td>Cell</td>
									<td style="text-align:center">{{$salebook->Cell}}</td>
									<td>
										<span class="pull-right">جوال رقم</span>
									</td>
								</tr>
								<tr>
									<td>Address</td>
									<td style="text-align:center">{{$salebook->Addres}}</td>
									<td>
										<span class="pull-right">العنوان</span>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12 table-responsive ">
					<div class="table table-condensed table-bordered">
						<table style="border:1px solid black" class="table table-condensed table-bordered">
							<thead >
								<tr>
									<th>#</th>
									<th style="width: 50%;">Description<span class="pull-right">منتجات</span>
									</th>
									<th>Quantity<span class="pull-right">الكمية</span>
									</th>
									<th>Unit Price<span class="pull-right">سعر الوحدة</span>
									</th>
									<th>Total<span class="pull-right">الإجمالي</span>
									</th>
								</tr>
							</thead>
							<tbody>
                                @for($a=0; $a < count($salebook_detail); $a++)
								<tr>
                                    <td>{{$a+1}}</td>
                                    <td>{{$salebook_detail[$a]->ItemName}}</td>
                                    <td>{{$salebook_detail[$a]->Qty}}</td>
                                    <td>{{$salebook_detail[$a]->Rate}}</td>
                                    <td>{{$salebook_detail[$a]->Total}}</td>
                                </tr>
                            @endfor
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-4">
			<b>warranty</b>	: {{$salebook->Remarks}}
				</div>
				<div class="col-xs-4"></div>
				<div class="col-xs-4">
					<div class="table-responsive">
						<table class="table table-condensed table-bordered">
							<tr>
								<th>Total:</th>
								<td>
									<b>{{$salebook->Total}}<b></td>
										<th>
											<span class="pull-right">الإجمالي</span>
										</th>
									</tr>
								</td>
							</tr>
                            <tr>
								<th>Discount:</th>
								<td>
									<b>{{$salebook->Discount}}<b></td>
										<th>
											<span class="pull-right">خصم</span>
										</th>
									</tr>
								</td>
							</tr>
                            <tr>
								<th>Total After Discount:</th>
								<td>
									<b>{{$salebook->Total - $salebook->Discount}}<b></td>
										<th>
											<span class="pull-right">المجموع بعد الخصم</span>
										</th>
									</tr>
								</td>
							</tr>
							<tr>
								<th>Vat {{$salebook->VATPercentage}} %</th>
								<td>
									<b>{{$salebook->VATPercentageValue}} <b></td>
										<th>
											<span class="pull-right">ضريبة</span>
										</th>
									</tr>
								</td>
							</tr>
							
							<tr>
								<th>Payable after VAT in SAR</th>
								<td>
									<b>{{$salebook->FinalTotal}}<b></td>
										<th>
											<span class="pull-right">إجمالي المبلغ</span>
										</th>
									</tr>
								</td>
							</tr>
                           
								</td>
							</tr>
                           
								</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
			<br><br>
			<div class="row" style="text-align:center">
				<div class="col-xs-4"> <strong>Salesman Signature<strong><br> <br>------------------------------
				</div>
				<div class="col-xs-4" style="text-align:center">				
				</div>
				<div class="col-xs-4"><strong>Recevier Signature<strong><br><br>------------------------------	
				</div>
			</div>
		
</body>
<input type="hidden" value="{{ $strr   }}" id="strr">
<script src="https://cdn.jsdelivr.net/gh/davidshimjs/qrcodejs@gh-pages/qrcode.min.js"></script>
<script type="text/javascript">

var strr = document.getElementById('strr').value;
var qrcode = new QRCode(document.getElementById("qrc"), {
text: strr,
width: 128,
height: 128,
correctLevel: QRCode.CorrectLevel.H
});
window.onload = function()
{
	var tt = window.print();
	setTimeout(window.close, 0);
       
}
</script>
@endsection