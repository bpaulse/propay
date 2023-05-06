@extends('layouts.app')

@section('content')

	@guest
		<script type="text/javascript">
			window.location.href = '/';//here double curly bracket
		</script>
	@else

		<div class="container">
			<div class="row" style="margin-top: 45px">
				<div class="col-md-8">
					<div class="card">
						<div class="card-header">
							<div style="float: left; width: 50%; border: 0px solid black;">
								<i class="icon-list"></i> Invoices <span id="tableHeader"></span>
							</div>
							<div style="float: right; width: 50%; border: 0px solid black; text-align: right;">
								<span class="addClient"><img src="{{ asset('images/client.png') }}" style="width: 25px;"></span>
								<span class="addProduct"><img src="{{ asset('images/add-product.png') }}" style="width: 25px;"></span>
								<span class="statement"><img src="{{ asset('images/statement.png') }}" style="width: 25px;"></span>
							</div>
						</div>
						<div class="card-body">
							<div id="loading-div"><img width="70px" src="{{ asset('images/loader.gif') }}" /></div>
							<div class="table-responsive">
								<table class="table table-hover table-condensed" id="invoices-table">
									<thead>
										<th>#</th>
										<th>--</th>
										<th>Name</th>
										<th>Description</th>
										<th>Action</th>
									</thead>
									<tbody id='tableData'></tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				@include('partial/add-new-invoice-form');
			</div>
		</div>

		@include('modal/edit-invoice-modal');
		@include('modal/product-modal');

		@push('index_script')
			<script src="{{ asset('js/index.js') }}"></script>
		@endpush

	@endguest

@endsection