@extends('layouts.app')

@section('content')

	@guest
		<script type="text/javascript">
			window.location.href = '/';//here double curly bracket
		</script>
	@else
		<!-- Wod Details -->
		<div class="container">
			<div class="row" style="margin-top: 45px">
				<div class="col-md-12">
					<div style="text-align: right; padding-bottom: 10px;">
						<button class="btn btn-warning backToInvoiceList">Back</button>
					</div>
					<div class="card">
						<div class="card-header">
							<div style="float: left; width: 50%; border: 0px solid black;">
								<i class="icon-list"></i> Clients <span id="tableHeader"></span>
							</div>
							<div style="float: right; width: 50%; border: 0px solid black; text-align: right;">
								<span class="addClientForm"><img src="{{ asset('images/add-icon.jpg') }}" style="width: 5%;"></span>
							</div>
						</div>
						<div class="card-body">
							<div class="table-responsive">
								<table class="table table-hover table-condensed" id="people-table">
									<thead>
										<th>Company Name</th>
										<th>Person Name & Surname</th>
										<th>Mobile</th>
										<th>Email</th>
										<th>Action</th>
									</thead>
									<tbody id='clientTableData'></tbody>
								</table>
								<div id="pager">
									<ul id="pagination" class="pagination-sm"></ul>
							</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		@include('modal/add-client-modal');
		@include('modal/edit-client-modal');

		@push('clientlist_script')
			<script src="{{ asset('js/clientlist.js') }}"></script>
		@endpush

	@endguest

@endsection
