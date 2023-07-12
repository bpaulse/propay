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

						<select class="preselectPeriod" id="preselectPeriod" data-live-search="true">
							<option value="">Select Range...</option>
							<option value="0">Yesterday</option>
							<option value="1">This Week</option>
							<option value="2">Last Week</option>
							<option value="3">This Month</option>
							<option value="4">Last Month</option>
							<option value="5">Last 3 Month</option>
							<option value="6">Last 6 Month</option>
							<option value="7">Last 12 Month</option>
						</select>

						<label for="fromDate">From</label><input type="text" id="fromDate" name="fromDate">
						<label for="toDate">to</label><input type="text" id="toDate" name="toDate">
						
						
						<label for="selectpicker">Status</label>
						<select class="selectpicker" id="selectpicker" data-live-search="true">
							<option value="">Select state...</option>
							<option value="0">Created</option>
							<option value="1">Emailed</option>
							<option value="2">Paid</option>
						</select>

						<button class="btn btn-primary" id="filterBtn">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filter" viewBox="0 0 16 16">
								<path d="M6 10.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5zm-2-3a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm-2-3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5z"/>
							</svg>
						</button>

					</div>
					<div style="border: 0px solid black; float: right"><button class="btn btn-warning backToInvoiceList">Back</button></div>
				</div>

				<div class="col-md-12">

					<div class="card">
						<div class="card-header">
							<div style="float: left; width: 49%; border: 0px solid black;">
								<i class="icon-list"></i> Statements <span id="tableHeader"></span>
							</div>
							<div id="printPDFStatement" style="float: right; width: 49%; border: 0px solid black; text-align: right; cursor: pointer;">
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-pdf-fill" viewBox="0 0 16 16">
									<path d="M5.523 10.424c.14-.082.293-.162.459-.238a7.878 7.878 0 0 1-.45.606c-.28.337-.498.516-.635.572a.266.266 0 0 1-.035.012.282.282 0 0 1-.026-.044c-.056-.11-.054-.216.04-.36.106-.165.319-.354.647-.548zm2.455-1.647c-.119.025-.237.05-.356.078a21.035 21.035 0 0 0 .5-1.05 11.96 11.96 0 0 0 .51.858c-.217.032-.436.07-.654.114zm2.525.939a3.888 3.888 0 0 1-.435-.41c.228.005.434.022.612.054.317.057.466.147.518.209a.095.095 0 0 1 .026.064.436.436 0 0 1-.06.2.307.307 0 0 1-.094.124.107.107 0 0 1-.069.015c-.09-.003-.258-.066-.498-.256zM8.278 4.97c-.04.244-.108.524-.2.829a4.86 4.86 0 0 1-.089-.346c-.076-.353-.087-.63-.046-.822.038-.177.11-.248.196-.283a.517.517 0 0 1 .145-.04c.013.03.028.092.032.198.005.122-.007.277-.038.465z"/>
									<path fill-rule="evenodd" d="M4 0h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2zm.165 11.668c.09.18.23.343.438.419.207.075.412.04.58-.03.318-.13.635-.436.926-.786.333-.401.683-.927 1.021-1.51a11.64 11.64 0 0 1 1.997-.406c.3.383.61.713.91.95.28.22.603.403.934.417a.856.856 0 0 0 .51-.138c.155-.101.27-.247.354-.416.09-.181.145-.37.138-.563a.844.844 0 0 0-.2-.518c-.226-.27-.596-.4-.96-.465a5.76 5.76 0 0 0-1.335-.05 10.954 10.954 0 0 1-.98-1.686c.25-.66.437-1.284.52-1.794.036-.218.055-.426.048-.614a1.238 1.238 0 0 0-.127-.538.7.7 0 0 0-.477-.365c-.202-.043-.41 0-.601.077-.377.15-.576.47-.651.823-.073.34-.04.736.046 1.136.088.406.238.848.43 1.295a19.707 19.707 0 0 1-1.062 2.227 7.662 7.662 0 0 0-1.482.645c-.37.22-.699.48-.897.787-.21.326-.275.714-.08 1.103z"/>
								</svg> 
								Print (PDF) 
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