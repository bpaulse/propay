<div class="modal fade bd-example-modal-lg" id="editPersonModal" tabindex="-1" role="dialog" aria-labelledby="editPersonModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="editPersonModalLabel"><i class="icon-cog"></i> Edit Person</h5>
				<button type="button" class="close closeEditModal" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="invoice-update-status"></div>
				<table class="table">
					<tr>
						<form>
							<td style="text-align: center; width: 100%;">
								<div class="container">
									<div class="row">
										<div class="form-group col-md-6">
											<label for="Name" class="col-form-label">Name:</label>
											<input type="text" class="form-control" id="Name" data-name="Name" value="">
										</div>

										<div class="form-group col-md-6">
											<label for="Surname" class="col-form-label">Surname:</label>
											<input type="text" class="form-control" id="Surname" data-name="Surname" value="">
										</div>

										<div class="form-group col-md-6">
											<label for="Email" class="col-form-label">Email: </label>
											<input type="text" class="form-control" id="Email" data-name="Email" value="">
										</div>

										<div class="form-group col-md-6">
											<label for="Mobile" class="col-form-label">Mobile: </label>
											<input type="text" class="form-control" id="Mobile" data-name="Mobile" value="">
										</div>

										<div class="form-group col-md-6">
											<label for="Idnumber" class="col-form-label">ID Number: </label>
											<input type="text" class="form-control" id="Idnumber" data-name="Idnumber" value="">
										</div>

										<div class="form-group col-md-6">
											<label for="DateOfBirth" class="col-form-label">Date Of Birth: </label>
											<input type="text" class="form-control" id="DateOfBirth" data-name="DateOfBirth" value="">
										</div>

										<div class="form-group col-md-6">
											<label for="Language" class="col-form-label">Language: </label>
											<input type="text" class="form-control" id="Language" data-name="Language" value="">
										</div>

										<div class="form-group col-md-6">
											<label for="Interest" class="col-form-label">Interest: </label><br />
											<select class="form-control" id="Interest">
												<option>Please select...</option>
												<option value="1">asdfasd fasdfasdfas dfasdfasd</option>
												<option value="2">asdfasd fasdfasdfas</option>
												<option value="3">aklskd jlksd jlkas</option>
												<option value="4">askldfj asldkj lasd</option>
											</select>
											<!-- <label for="Interest" class="col-form-label">Interest: </label>
											<input type="text" class="form-control" id="Interest" data-name="Interest" value=""> -->
										</div>

									</div>
								</div>
							</td>
							<input type="hidden" class="form-control" id="event_id" value="">
						</form>
					</tr>
				</table>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary updateEvent">Update Event Details</button>
				<button type="button" class="btn btn-secondary closeEditModal" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="deletePersonModal" tabindex="-1" role="dialog" aria-labelledby="deletePersonModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="deletePersonModalLabel">Delete Person</h5>
				<button type="button" class="close closeDeleteModal" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<span>
					Are you sure you want to delete the Person?
				</span>
				<input type="hidden" id="person_id" value="" />
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary deletePersonButton">Delete Person</button>
				<button type="button" class="btn btn-secondary closeDeleteModal" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>