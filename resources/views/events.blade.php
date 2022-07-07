@extends('layouts.app')

@section('content')

	@guest
		<script type="text/javascript">
			window.location.href = '/';//here double curly bracket
		</script>
	@else

		<div class="container">
			<div class="row" style="margin-top: 10px">
				<div class="col-md-12">
					<div style="text-align: right; padding-bottom: 5px;"><button class="btn btn-secondary backToHome">Back</button></div>
					<div class="card">
						<div class="card-header">
							<i class="icon-list"></i> Event(s)
							<i class="icon-plus float-right" id="addEventForm"></i>
						</div>
						<div class="card-body">
							<table class="table table-hover table-condensed" id="invoices-table">
								<thead>
									<th style="width: 25%">Event</th>
									<th style="width: 25%">Description</th>
									<th style="width: 25%">Location</th>
									<th style="width: 25%">Action</th>
								</thead>
								<tbody id='eventsData'>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>

		@include('modal/edit-event-modal');
		@include('modal/add-event-modal');

		@push('events_script')
			<script src="{{ asset('js/events.js') }}"></script>
		@endpush

	@endguest


@endsection
