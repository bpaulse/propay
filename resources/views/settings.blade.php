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
								<!-- <span class="addClient"><img src="{{ asset('images/add-icon.jpg') }}" style="width: 5%;"></span> -->
							</div>
						</div>

						<div class="card-body">
							@if (session('status'))
								<div class="alert alert-success" role="alert">
									{{ session('status') }}
								</div>
							@else
								<div>
									<form id="userForm" enctype="multipart/form-data" method="POST">
										<div class="modal-body">
											<table class="table">
												@csrf
												<tr>
													<td>
														<div class="form-group">
															<label for="user_firstname" class="col-form-label">Name:</label>
															<input type="text" class="form-control" id="user_firstname" value="">
														</div>
													</td>
													<td>
														<div class="form-group">
															<label for="user_surname" class="col-form-label">Surname:</label>
															<input type="text" class="form-control" id="user_surname" value="">
														</div>
													</td>
												</tr>
												<tr>
													<td>
														<div class="form-group">
															<label for="user_mobile" class="col-form-label">Mobile:</label>
															<input type="text" class="form-control" id="user_mobile" maxlength="10" value="">
														</div>
													</td>
													<td>
														<div class="form-group">
															<label for="user_email" class="col-form-label">Email:</label>
															<input type="email" class="form-control" id="user_email" value="">
														</div>
													</td>
												</tr>
												<tr>
													<td>
														<div class="form-group">
															<label for="user_dateofbirth" class="col-form-label">Date Of Birth:</label>
															<input type="text" class="form-control" id="user_dateofbirth" value="">
														</div>
													</td>
													<td>
														<div class="form-group">
															<label for="user_idnumber" class="col-form-label">ID Number:</label>
															<input type="text" class="form-control" id="user_idnumber" maxlength="10" value="">
														</div>
													</td>
												</tr>
												<tr>
													<td>
														<div class="form-group">
															<label for="user_vat" class="col-form-label">VAT Number:</label>
															<input type="text" class="form-control" id="user_vat" value="">
														</div>
													</td>
													<td>
														<div class="form-group">
															<label for="user_companyreg" class="col-form-label">Company Registration Number:</label>
															<input type="text" class="form-control" id="user_companyreg" value="">
														</div>
													</td>
												</tr>
												<tr>
													<td>
														<div class="form-group">
															<label for="user_companyname" class="col-form-label">Company Name:</label>
															<input type="text" class="form-control" id="user_companyname" value="">
														</div>
														<div class="form-group">
															<label for="user_logo" class="col-form-label">Logo:</label>
															<input type="file" id="user_logo" name="user_logo" class="form-control" value="">
															<div style="width: 200px; height: 100px; border: 0px solid black; margin-top: 10px;">
																<span>Preview</span>
																<div id="file-preview"></div>
																<!-- <img id="user_logo_preview" src="" style="width: 200px;' alt="Preview Pane" />  -->
															</div>
														</div>
													</td>
													<td>
														<div class="form-group">
															<label for="user_address" class="col-form-label">Address:</label>
															<textarea class="form-control" name="user_address" id="user_address" rows="2"></textarea>
														</div>
														<div class="form-group">
															<label for="user_banking" class="col-form-label">Banking Details:</label>
															<textarea class="form-control" name="user_banking" id="user_banking" rows="3"></textarea>
														</div>
													</td>
												</tr>
											</table>

										</div>
										<div class="modal-footer">
											<button type="submit" class="btn btn-primary saveUserDetails">Submit</button>
											<button type="button" class="btn btn-secondary closeAddClientModal" data-dismiss="modal">Close</button>
										</div>
									</form>
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
