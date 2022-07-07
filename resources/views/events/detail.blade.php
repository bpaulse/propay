@extends('layouts.app')
@section('title', 'Results')
@section('content')	
@guest
	<script type="text/javascript">
		window.location.href = '/';//here double curly bracket
	</script>
@else
	<div class="container" data-event_id="<?php echo $eventid; ?>">
		<div class="row" style="margin-top: 10px">
			<div class="col-md-12">
				<div class="w-100 text-right p-1">
					<button class="btn btn-info text-right backEvent">Back</button>
				</div>
				<div class="card">
					<div class="card-header">
						<div style="float: left; width: 50%; border: 0px solid black;">
							<i class="icon-list"></i> <span id="tableHeader"></span>
						</div>
						<div style="float: right; width: 50%; border: 0px solid black; text-align: right;">
							<span class="addAthlete"><img src="{{ asset('images/athlete.png') }}" style="width: 5%;"></span>
							<span class="addWod"><img src="{{ asset('images/workout.png') }}" style="width: 5%;"></span>
						</div>
					</div>
					<div class="card-body">
						<table class="table table-hover table-condensed" id="wodlist-table">
							<thead>
								<th style="width: 20%">WOD Name</th>
								<th style="width: 40%">Description</th>
								<th style="width: 20%">WOD Type</th>
								<th style="width: 20%">Action</th>
							</thead>
							<tbody id='wodData'></tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

	@include('modal/add-athlete-modal')
	@include('modal/add-wod-modal')
	@include('modal/edit-wod-modal')

	@push('eventDetail_script')
		<script src="{{ asset('js/eventDetail.js') }}"></script>
	@endpush
@endguest
@endsection