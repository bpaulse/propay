<div class="modal fade bd-example-modal-lg" id="productModal" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="productModalLabel"><i class="icon-cog"></i> Products</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<table class="table">
					<tr>
						<td>
							<div class="form-row" id="productform">
								<div class="form-group col-md-12" style="text-align: right;">
									<span class="close-product-div">&times;</span>
								</div>
								<div class="form-group col-md-4">
									<label for="product_name">Product Name</label>
									<input type="text" class="form-control" id="product_name" value="">
								</div>
								<div class="form-group col-md-4" style="border: blue 0px solid;">
									<label for="unitprice">Unit Price</label>
									<input type="text" class="form-control" id="unitprice" value="">
									<input type="hidden" id="product_id" value="">
								</div>
								<div class="form-group col-md-4">
									<div style="float: right; width: 50%; height: 100%; border: 0px solid black; padding-top: 12%;">
										<button class="btn btn-primary w-100 submit-product"></button>
									</div>
								</div>
							</div>
						</td>
					</tr>
				</table>

				<div class="card">
					<div class="card-header">
						<div style="float: left; width: 49%; border: 0px solid black;">
							<i class="icon-list"></i> Product/Services
						</div>
						<div style="float: right; width: 49%; border: 0px solid black; text-align: right;">
							<i class="icon-plus" id="add-product-line" style=""></i>
						</div>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-hover table-condensed" id="product-table">
								<thead>
									<tr>
										<th>Name</th>
										<th>Unit Price</th>
										<th style="text-align: center;">Action</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>