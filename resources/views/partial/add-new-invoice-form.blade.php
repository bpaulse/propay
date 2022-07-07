<div class="col-md-4">
	<div class="card">
		<div class="card-header">
			<i class="icon-plus-sign-alt"></i> Add New Invoice
		</div>
		<div class="card-body">
			<form action="{{ route('add.invoice') }}" method="POST" id="add-invoice-form">
				@csrf
				<div class="form-group">
					<label for="">Invoices</label>
					<input type="text" class="form-control" name="invoice_name" placeholder="Please Enter an Invoice Name..." />
					<span class="text-danger error-text invoice_name_error"></span>
				</div>
				<div class="form-group">
					<label for="">Invoice Desciption</label>
					<input type="text" class="form-control" name="invoice_desc" placeholder="Please enter an Invoice Description..." />
					<span class="text-danger error-text invoice_desc_error"></span>
				</div>
				<div class="form-group">
					<button class="btn btn-block btn-success" type="submit">SAVE</button>
				</div>
			</form>
		</div>
	</div>
</div>