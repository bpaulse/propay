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
								<i class="icon-list"></i> {{ __('Settings') }} - {{ Auth::user()->name }} <span id="tableHeader"></span>
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
							@else
								<div>
									Settings sdsd
								</div>
							@endif


						</div>
					</div>
				</div>
			</div>
		</div>

		@push('setting_script')
			<script src="{{ asset('js/setting.js') }}"></script>
		@endpush
	@endguest
@endsection
