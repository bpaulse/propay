<div class="modal fade bd-example-modal-lg" id="editEventModal" tabindex="-1" role="dialog" aria-labelledby="editEventModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="editEventModalLabel"><i class="icon-cog"></i> Edit Invoice</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
										<div class="form-group col-md-12">
											<label for="event_name" class="col-form-label">Event Name:</label>
											<input type="text" class="form-control" id="event_name" data-name="name" value="">
										</div>

										<div class="form-group col-md-12">
											<label for="event_desc" class="col-form-label">Event Desc:</label>
											<input type="text" class="form-control" id="event_desc" data-name="desc" value="">
										</div>

										<div class="form-group col-md-12">
											<label for="event_loc" class="col-form-label">Event Location:</label>
											<input type="text" class="form-control" id="event_loc" data-name="loc" value="">
										</div>

										<div class="form-group col-md-12">
											<label for="event_mod_date" class="col-form-label">Event Date:</label>
											<input type="text" class="form-control" id="event_mod_date" data-name="loc" value="">
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
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>