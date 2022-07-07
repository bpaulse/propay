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
				<table class="table">
					<tr>
						<form id="basic-form">
							<td style="width: 100%;">
								<div class="container">
									<div class="row">
										<div class="form-group col-md-6">
											<label for="Name" class="col-form-label">Name:</label>
											<input type="text" class="form-control" id="Name" data-name="Name" value="" minlength="3" required>
											<div class="error error-Name"></div>
										</div>

										<div class="form-group col-md-6">
											<label for="Surname" class="col-form-label">Surname:</label>
											<input type="text" class="form-control" id="Surname" data-name="Surname" value="">
											<div class="error error-Surname"></div>
										</div>

										<div class="form-group col-md-6">
											<label for="Email" class="col-form-label">Email: </label>
											<input type="text" class="form-control" id="Email" data-name="Email" value="">
											<div class="error error-Email"></div>
										</div>

										<div class="form-group col-md-6">
											<label for="Mobile" class="col-form-label">Mobile: </label>
											<input type="text" class="form-control" id="Mobile" data-name="Mobile" value="">
											<div class="error error-Mobile"></div>
										</div>

										<div class="form-group col-md-6">
											<label for="Idnumber" class="col-form-label">ID Number: </label>
											<input type="text" class="form-control" id="Idnumber" data-name="Idnumber" value="">
											<div class="error error-Idnumber"></div>
										</div>

										<div class="form-group col-md-6">
											<label for="DateOfBirth" class="col-form-label">Date Of Birth: </label>
											<input type="text" class="form-control" id="DateOfBirth" data-name="DateOfBirth" value="">
											<div class="error error-DateOfBirth"></div>
										</div>

										<div class="form-group col-md-6">
											<label for="Language" class="col-form-label">Language: </label><br />
											<select class="form-control" id="Language">
												<option>Please select...</option>
											</select>
										</div>

										<!-- <div class="form-group col-md-6" id='placeInterestDropdown'> -->
										<div class="form-group col-md-6">
											<label class="col-2 col-form-label" for="animals">Interests</label>
											<select multiple name="animals" id="animals" class="filter-multi-select">
												<!-- <option value="1">Bear</option>
												<option value="2">Ant</option>
												<option value="3">Salamander</option>
												<option value="4">Owl</option>
												<option value="5">Frog</option>
												<option value="6">Shark</option> -->
											</select>
										</div>

									</div>
								</div>
							</td>
							<input type="hidden" class="form-control" id="person_id" value="">
						</form>
					</tr>
				</table>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary updatePerson">Update Person Details</button>
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



<div class="modal fade" id="editPersonInterestModal" tabindex="-1" role="dialog" aria-labelledby="editPersonInterestModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="editPersonInterestModalLabel">Delete Person</h5>
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
				<button type="button" class="btn btn-primary editPersonInterestButton">Delete Person</button>
				<button type="button" class="btn btn-secondary closeEditPersonInterestModal" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>



<div class="modal fade" id="editPersonLangModal" tabindex="-1" role="dialog" aria-labelledby="editPersonLangModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="editPersonLangModalLabel">Edit Person Language</h5>
				<button type="button" class="close closeeditPersonLangModal" data-dismiss="modal" aria-label="Close">
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
				<button type="button" class="btn btn-primary updatePersonLangButton">Update Language</button>
				<button type="button" class="btn btn-secondary editPersonLangModal" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>