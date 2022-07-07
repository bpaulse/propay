@extends('layouts.app')

@section('title', 'Wod Details')

@section('content')

	@guest
		<script type="text/javascript">
			window.location.href = '/';//here double curly bracket
		</script>
	@else
		<div class="container">
			<div class="row" style="margin-top: 10px">
				<div class="w-25 text-left p-3">
					<div class="form-group">
						<input type="text" class="form-control" id="athlete_search" value="" placeholder="Search">
					</div>
				</div>
				<div class="w-25 text-left p-3">
				</div>
				<div class="w-50 text-right p-3">
					<button class="btn btn-info text-right backEventDetails">Back</button>
				</div>
				<div class="col-md-12">
					<div class="card">
						<div class="card-header"><i class="icon-list"></i> WOD Detail(s) </div>
						<div class="card-body">
							<table class="table table-hover table-condensed" id="athletes-table">
								<thead>
									<th style="width: 30%">Name</th>
									<th style="width: 30%">Category</th>
									<th style="width: 25%">Gender</th>
									<th style="width: 5%">Score</th>
									<th style="width: 5%">Score Status</th>
									<th style="width: 10%">Action</th>
								</thead>
								<tbody id='athleteData'></tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		@include('modal/add-woddetail-modal');

		@push('woddetails_script')
			<script src="{{ asset('js/woddetails.js') }}"></script>
		@endpush

	@endguest

@endsection