<div class="modal fade bd-example-modal-lg" id="editEventModal" tabindex="-1" role="dialog" aria-labelledby="editEventModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="editEventModalLabel"><i class="icon-cog"></i> Edit WOD</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="{{ route('edit.wod') }}" method="POST" id="edit-wod-form">
				@csrf
				<div class="modal-body">
					<table class="table">
						<tr>
							<td style="width: 50%;" >
								<div class="form-group">
									<label for="wod_edit_name" class="col-form-label">WOD Name:</label>
									<input type="text" class="form-control" id="wod_edit_name" value="" placeholder="Wod Name" />
								</div>
								<div class="form-group">
									<label for="wod_edit_type" class="col-form-label">WOD Type:</label>
									<select class="form-control" id="wod_edit_type">
										<option value="">-----</option>
									</select>
								</div>
							</td>
							<td style="width: 50%;">
								<div class="form-group">
									<label for="wod_edit_desc" class="col-form-label">WOD Desc:</label>
									<textarea class="form-control z-depth-1" id="wod_edit_desc" rows="5" placeholder="Please provide the WOD..."></textarea>
								</div>
							</td>
							<input type="hidden" id="event_id" value="" />
							<input type="hidden" id="wod_id" value="" />
						</tr>
					</table>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary updateWod">Add</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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