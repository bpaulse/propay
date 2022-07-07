<div class="modal fade bd-example-modal-lg" id="addWODModal" tabindex="-1" role="dialog" aria-labelledby="addWODModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addWODModalLabel"><i class="icon-cog"></i> Add WOD</h5>
				<button type="button" class="close closeAddWODModal" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="{{ route('add.wod') }}" method="POST" id="add-wod-form">
				<div class="modal-body">
					<table class="table">
						<tr>
							<td style="width: 50%;" >
								<div class="form-group">
									<label for="wod_name" class="col-form-label">WOD Name:</label>
									<input type="text" class="form-control" id="wod_name" value="">
								</div>
								<div class="form-group">
									<label for="wod_type" class="col-form-label">WOD Type:</label>
									<select class="form-control" id="wod_type">
										<option value="">-----</option>
										<option value="1">AMRAP</option>
										<option value="2">For Time</option>
										<option value="3">1 RM</option>
									</select>
								</div>
							</td>
							<td style="width: 50%;">
								<div class="form-group">
									<label for="wod_desc" class="col-form-label">WOD Desc:</label>
									<textarea class="form-control z-depth-1" id="wod_desc" rows="5" placeholder="Write something here..."></textarea>
								</div>
							</td>
							<input type="hidden" id="event_id" value="">
						</tr>
						<tr>
							<td style="width: 50%; border: 0px solid black; padding-top: 19px;" id="fortime">
								<div class="form-row">
									<div class="form-group col-md-2">
										<label for="minutes">Min</label>
										<input type="text" class="form-control" id="minutes">
									</div>
									<div class="form-group col-md-2">
										<label for="secondes">Sec</label>
										<input type="text" class="form-control" id="secondes">
									</div>
								</div>
							</td>
							<td style="width: 50%; border: 0px solid black;">
							</td>
						</tr>
					</table>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary saveWod">Add</button>
					<button type="button" class="btn btn-secondary closeAddWODModal" data-dismiss="modal">Close</button>
				</div>
			</form>
		</div>
	</div>
</div>


<div class="modal fade" id="deleteInvoiceModal" tabindex="-1" role="dialog" aria-labelledby="deleteInvoiceModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="deleteInvoiceModalLabel">Edit Invoice</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">Are you sure you want to delete the Invoice?</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary saveInvoice">Update Invoice</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>