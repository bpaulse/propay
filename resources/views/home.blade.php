@extends('layouts.app')

@section('content')

	@guest
		<script type="text/javascript">
			window.location.href = '/';//here double curly bracket
		</script>
	@else
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-12">
					<div class="card">
						<div class="card-header">
							<div style="float: left; width: 50%; border: 0px solid black;">
								<i class="icon-list"></i> {{ __('Management') }} - {{ Auth::user()->name }} ({{ Auth::user()->email }}) <span id="tableHeader"></span>
							</div>
							<div style="float: right; width: 50%; border: 0px solid black; text-align: right;">
								<span class="addClient"><img src="{{ asset('images/add-icon.jpg') }}" style="width: 5%;"></span>
							</div>
						</div>

						<div class="card-body">

							@if (session('status'))
								<div class="alert alert-success" role="alert">
									{{ session('status') }}
								</div>
							@endif

							<ul>
								<!-- <li><a href="/events">Scoring</a></li> -->
								<li><a href="/invoice-list">Invoicing</a></li>
								<!-- <li><a href="/person">Athletes</a></li> -->
							</ul>

						</div>
					</div>
				</div>
			</div>
		</div>
		@include('modal/edit-person-modal');

		@push('people_script')
			<script src="{{ asset('js/people.js') }}"></script>
		@endpush
	@endguest
@endsection
