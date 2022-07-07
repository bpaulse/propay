<div class="modal fade bd-example-modal-lg" id="wodScoreModal" tabindex="-1" role="dialog" aria-labelledby="wodScoreModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="wodScoreModalLabel"><i class="icon-cog"></i> Enter WOD Score for <i id="athleteName"></i></h5>
				<button type="button" class="close closeWODScoreModal" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="add-wod-score">
				@csrf
				<div class="modal-body">
					<table class="table">
						<tr>
							<td style="width: 100%;">
								<div class="form-group">
									<label for="wod_desc" class="col-form-label">WOD Desc:</label>
									<div id="woddescinfo"></div>
								</div>
							</td>
							<input type="hidden" id="wod_id" value="">
							<input type="hidden" id="athlete_id" value="">
						</tr>
						<tr>
							<td style="width: 100%; padding-top: 19px;">
								<span id="scoringtype"></span>
							</td>
							<td style="width: 50%; border: 0px solid black;"></td>
						</tr>

					</table>
				</div>
				<div class="modal-footer">
					<button class="btn btn-primary" id="addscoreform">Add</button>
					<button class="btn btn-warning" id="clearscore">Clear</button>
					<button type="button" class="btn btn-secondary closeWODScoreModal" data-dismiss="modal">Close</button>
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