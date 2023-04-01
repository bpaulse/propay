@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-12">
				<div style="text-align: right; padding-bottom: 5px;"><button id="backtoHome" class="btn btn-secondary">Back</button></div>
				<div class="card">
					<div class="card-header">
						<div style="float: left; width: 50%; border: 0px solid black;">
							<i class="icon-list"></i> {{ __('People') }} <span id="tableHeader"></span>
						</div>
						<div style="float: right; width: 50%; border: 0px solid black; text-align: right;">
							<span class="addPersonModal" style="cursor: pointer"><img src="{{ asset('images/add-icon.jpg') }}" style="width: 5%;"></span>
						</div>
					</div>

					<div class="card-body">
						@if (session('status'))
							<div class="alert alert-success" role="alert">
								{{ session('status') }}
							</div>
						@endif
						<div class="table-responsive">
							<table class="table table-hover table-condensed" id="client-table">
								<thead>
									<th>Person</th>
									<th>Mobile</th>
									<th>Email</th>
									<th>ID Number</th>
									<th>DOB </th>
									<th>Language</th>
									<th>Interest</th>
									<th>Action</th>
								</thead>
								<tbody id='personTableData'></tbody>
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
	@include('modal/edit-person-modal');

	@push('events_script')
		<script src="{{ asset('js/people.js') }}"></script>
	@endpush

@endsection
