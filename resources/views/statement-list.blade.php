@extends('layouts.app')

@section('content')

	@guest

		<script type="text/javascript">
			window.location.href = '/';
		</script>

	@else

		<div class="container">
			<div class="row" style="margin-top: 45px">
				<div style="border: 0px solid black; width: 100%; padding-bottom: 5px;">
					<div style="border: 0px solid black; float: left">

						<!-- <label for="from">From</label>
						<input type="text" id="from" name="from">
						<label for="to">to</label>
						<input type="text" id="to" name="to"> -->

					</div>
					<div style="border: 0px solid black; float: right"><button class="btn btn-warning backToInvoiceList">Back</button></div>
				</div>

				<div class="col-md-12">

					<!-- <div style="border: 1px solid black;">
						<div style="text-align: left; padding-bottom: 10px; border: 1px solid black; width: 75%; float: left;">
							Bevan
							<div class="form-group">
								<input id="dp1" type="text" class="form-control clickable input-md" id="DtChkIn" placeholder="&#xf133;  Check-In" />
							</div>
							<div class="form-group">
								<input id="dp2" type="text" class="form-control clickable input-md" id="DtChkOut" placeholder="&#xf133;  Check-Out" />
							</div>

						</div>
						<div style="text-align: right; padding-bottom: 10px; border: 1px solid black; width: 25%; float: right;">
							<button class="btn btn-warning backToInvoiceList">Back</button>
						</div>
					</div> -->

					<div class="card">
						<div class="card-header">
							<div style="float: left; width: 100%; border: 0px solid black;">
								<i class="icon-list"></i> Statements <span id="tableHeader"></span>
							</div>
						</div>
						<div class="card-body">
							<div id="loading-div">
								<img width="70px" src="{{ asset('images/loader.gif') }}" />
							</div>
							<div class="table-responsive">
								<table class="table table-hover table-condensed" id="invoices-table">
									<thead>
										<th>#</th>
										<th>Name</th>
										<th>Description</th>
										<th style="text-align: right;">Amount</th>
									</thead>
									<tbody id='tableData'></tbody>
								</table>
							</div>
						</div>
						<div style="border: 0px solid black; margin-bottom: 10px; text-align: right; margin-right: 30px;">
							Total: <span id="total"></span>
						</div>
					</div>
				</div>
			</div>
		</div>

		@include('modal/edit-invoice-modal');
		@include('modal/product-modal');

		@push('index_script')
			<script src="{{ asset('js/statement.js') }}"></script>
		@endpush

	@endguest

@endsection