<div class="modal fade bd-example-modal-lg" id="addClientModal" tabindex="-1" role="dialog" aria-labelledby="addClientModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addClientModalLabel"><i class="icon-cog"></i> Add Client</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<form action="{{ route('add.client') }}" method="POST" id="add-client-form">
				<div class="modal-body">
					<table class="table">
						@csrf
						<tr>
							<td>
								<div class="form-group">
									<label for="name" class="col-form-label">Name:</label>
									<input type="text" class="form-control" id="add_name" value="">
								</div>
							</td>
							<td>
								<div class="form-group">
									<label for="surname" class="col-form-label">Surname:</label>
									<input type="text" class="form-control" id="add_surname" value="">
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="form-group">
									<label for="mobile" class="col-form-label">Mobile:</label>
									<input type="text" class="form-control" id="add_mobile" maxlength="10" value="">
								</div>
							</td>
							<td>
								<div class="form-group">
									<label for="email" class="col-form-label">Email:</label>
									<input type="email" class="form-control" id="add_email" value="">
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="form-group">
									<label for="website" class="col-form-label">Website:</label>
									<input type="text" class="form-control" id="add_website" value="">
								</div>
							</td>
							<td>
								<div class="form-group">
									<label for="landline" class="col-form-label">Land Line:</label>
									<input type="text" class="form-control" id="add_landline" maxlength="10" value="">
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="form-group">
									<label for="vat" class="col-form-label">VAT Number:</label>
									<input type="text" class="form-control" id="add_vat" value="">
								</div>
							</td>
							<td>
								<div class="form-group">
									<label for="companyreg" class="col-form-label">Company Registration Number:</label>
									<input type="text" class="form-control" id="add_companyreg" value="">
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="form-group">
									<label for="companyname" class="col-form-label">Company Name:</label>
									<input type="text" class="form-control" id="add_companyname" value="">
								</div>
							</td>
							<td>
								<div class="form-group">
									<label for="address" class="col-form-label">Address:</label>
									<textarea class="form-control" name="address" id="add_address" rows="2"></textarea>
								</div>
							</td>
						</tr>
					</table>

				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary saveClient">Submit</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</form>
		</div>
	</div>
</div>


<div class="modal fade" id="deleteClientModal" tabindex="-1" role="dialog" aria-labelledby="deleteClientModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="deleteClientModalLabel">Delete Client</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<input type="hidden" id="modal_clientid" value="" />
			<div class="modal-body">Are you sure you want to delete the Client?</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary deleteClientRow">Delete Client</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>