<div class="modal fade bd-example-modal-lg" id="editInvoiceModal" tabindex="-1" role="dialog" aria-labelledby="editInvoiceModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="editInvoiceModalLabel"><i class="icon-cog"></i> Edit Invoice</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="invoice-update-status"></div>
				<table class="table">
					<tr>
						<td>
							<table style="width: 100%;">
								<tr>
									<td style="width: 20%; border: 0px solid black;">
										<div class="form-group">
											<label class="col-form-label" id="invoice_id"></label>
										</div>
									</td>
									<td style="width: 60%; border: 0px solid black;">
										<span style="font-size: 12px;" id="clientinfodisplay"></span>
									</td>
									<td style="text-align: right; width: 20%; border: 0px solid black;">
										<div class="form-group">
											<label class="col-form-label" id="created_date"></label>
										</div>
									</td>
								</tr>
							</table>
						</td>
					</tr> 
					<tr>
						<form>
							<td style="text-align: center; width: 100%;">
								<div class="container">
									<div class="row">
										<div class="form-group col-md-4">
											<label for="invoice_name" class="col-form-label">Invoice Name:</label>
											<input type="text" class="form-control" id="invoice_name" data-name="name" value="">
										</div>

										<div class="form-group col-md-4">
											<label for="invoice_desc" class="col-form-label">Invoice Desc:</label>
											<input type="text" class="form-control" id="invoice_desc" data-name="desc" value="">
										</div>

										<div class="form-group col-md-4">
											<label for="clientinfo" class="col-form-label">Client Info:</label>
											<select id="clientinfo" class="form-control" style="width: 100%;">
												<option selected>Choose...</option>
											</select>
										</div>
									</div>
								</div>
							</td>
							<input type="hidden" class="form-control" id="inv_id" value="">
						</form>
					</tr>
				</table>
				<table class="table">
					<tr>
						<td>
							<div class="form-row" id="lineupdateform">
								<div class="form-group col-md-12" style="text-align: right;">
									<span class="close-line-item">X</span>
								</div>
								<div class="form-group col-md-4">
									<label for="inputProduct">Product</label>
									<select id="inputProduct" class="form-control">
										<option selected>Choose...</option>
									</select>
								</div>
								<div class="form-group col-md-4" style="border: blue 0px solid;">
									<label for="inputQuantity">Quantity</label>
									<input type="text" class="form-control" id="inputQuantity" value="">
									<input type="hidden" id="inv_line_id" value="">
								</div>
								<div class="form-group col-md-4">
									<div style="float: left; width: 50%; height: 100%; font-size: 10px; border: 0px solid black;; padding-top: 12%;">
										UnitPrice - <span id="unitpriceDesc"></span><br />
										Quantity - <span id="quantityDesc"></span><br />
										Total - <span id="TotalDesc"></span>
									</div>
									<div style="float: right; width: 50%; height: 100%; border: 0px solid black; padding-top: 12%;">
										<button class="btn btn-primary w-100 update-productline"></button>
									</div>
								</div>
							</div>
						</td>
					</tr>
				</table>

				<div class="card">
					<div class="card-header">
						<div style="float: left; width: 49%; border: 0px solid black;">
							<i class="icon-list"></i> Invoice Lines
						</div>
						<div style="float: right; width: 49%; border: 0px solid black; text-align: right;">
							<i class="icon-plus" id="add-invoice-line" style=""></i>
						</div>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-hover table-condensed" id="invoice-line-table">
								<thead>
									<tr>
										<th>Product</th>
										<th>Qty</th>
										<th>Unit Price</th>
										<th>Price</th>
										<th style="text-align: center;">Action</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
				</div>

				<div class="card">
					<div class="card-header">
						<div class="table-responsive">
							<table class="table table-hover table-condensed">
								<thead>
									<tr>
										<th class="w-75 bg-success text-right">Total: </th>
										<th class="w-25 bg-success invoiceTotal"></th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>



			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary saveInvoice">Update Invoice</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
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