<div class="modal fade bd-example-modal-lg" id="addEventModal" tabindex="-1" role="dialog" aria-labelledby="addEventModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addEventModalLabel"><i class="icon-plus"></i> Add Event</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="add-event-form">
				<div class="modal-body">
					<div class="col-md-12">
						<div class="card">
							<div class="card-body">
								<div class="form-group">
									<label for="">Event Name</label>
									<input type="text" class="form-control" id="event_add_name" name="event_add_name" placeholder="Please Enter an Event Name..." />
									<span class="text-danger error-text event_add_name_error"></span>
								</div>
								<div class="form-group">
									<label for="">Event Desciption</label>	
									<input type="text" class="form-control" id="event_add_desc" name="event_add_desc" placeholder="Please enter an Event Description..." />
									<span class="text-danger error-text event_add_desc_error"></span>
								</div>
								<div class="form-group">
									<label for="">Event Location</label>	
									<input type="text" class="form-control" id="event_add_location" name="event_add_location" placeholder="Please enter an Event Location..." />
									<span class="text-danger error-text event_add_location_error"></span>
								</div>
								<div class="form-group">
									<label for="">Event Date</label>	
									<input type="text" class="form-control" id="event_add_date" name="event_add_date" placeholder="Please enter an Event Date..." />
									<span class="text-danger error-text event_add_date_error"></span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary addEventButton">Add</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>

			</form>


		</div>
	</div>
</div>


<div class="modal fade" id="deleteEventModal" tabindex="-1" role="dialog" aria-labelledby="deleteEventModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="deleteEventModalLabel">Delete Event</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<span>
					Are you sure you want to delete the Eventqq?
				</span>
				<input type="hidden" id="event_id" value="" />
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary deleteEventButton">Delete Event</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>