$(document).ready(function() {

	console.log("ClientList");

	toastr.options.preventDuplicates = false;

	$("#clientinfo").select2();

	$("#inputQuantity").focus(function() { $(this).select(); });

	$('#add-invoice-form').on('submit', addInvoiceForm);

	getClientList();

	$(document).on('change', '#clientinfo', function(event) {
		var client_id = event.target.value;
		var invoice_id = $('#inv_id').val();
		saveClientToInvoice(client_id, invoice_id);
	});

	$('#add-invoice-line').hover(function() {
		$(this).css('cursor', 'pointer');
	});

	$('.close-line-item').hover(function() {
		$(this).css('cursor', 'pointer');
	});

	$(document).on('hover', '.delete-invoiceline', function() {
		$(this).css('cursor', 'pointer');
	});

	$(document).on('click', '.delete-invoiceline', function() {
		var trid = $(this).closest('tr').attr('id');
		deleteInvLine(trid);
	});

	$(document).on('click', '.edit-invoiceline', function() {
		var trid = $(this).closest('tr').attr('id');
		$('#lineupdateform').show();
		getInvoiceLineInfo(trid);
	});

	$(document).on('click', '.deleteInvoice', function() {
		var i = $(this).closest('tr').attr('data-id');
	});

	$(document).on('keyup', '#inputQuantity', function(event) {

		var quantityVal = $(this).val();
		var product_id = $('#inputProduct').val();

		if (!isNumberKey(event).status) {
			var currentString = $(this).val();
			// check the number of occurances of the dots
			newString = currentString.slice(0, -1);
			$(this).val(newString);
			quantityVal = newString;
		}

		$('#quantityDesc').text(quantityVal);
		retrieveProduct(product_id)

	});

	$(document).on('click', '.addProduct', function() {
		$('#productModal').modal('show');
	});

	$(document).on('click', '.backToInvoiceList', backToInvoiceList);

	$(document).on('click', '.deleteClient', deleteClientModal);

	$(document).on('click', '.deleteClientRow', deleteClientRow);

	$(document).on('click', '.editClient', openClientEditModal);

	// this is the id of the form
	$("#edit-client-form").submit(submitClientForm);
	$("#add-client-form").submit(submitClientAddForm);

	// $(document).on('click', '.addClient', openClientAddModal);

	$(document).on('click', '.addClientForm', openClientAddModal);

	$(document).on('click', '.closeEditInvoiceModal', closeEditInvoiceModal);
	$(document).on('click', '.closeAddClientModal', closeAddClientModal);

	$('#add_name').keyup(populateCompanyNameField);
	$('#add_surname').keyup(populateCompanyNameField);

});

function closeEditInvoiceModal () {
	console.log('closeEditInvoiceModal');
	$('#editClientModal').modal('hide');
}


function closeAddClientModal () {
	console.log('closeAddClientModal');
	$('#addClientModal').modal('hide');
}

function addInvoiceForm(e) {

	e.preventDefault();

	var doAjax = true;
	if (doAjax) {

		var form = this;

		$.ajax({
			type: $(form).attr('method'),
			url: $(form).attr('action'),
			headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
			data: new FormData(form),
			processData: false,
			dataType: "json",
			contentType: false,
			beforeSend: function() {
				$(form).find('span.error-text').text('');
			},
			success: function(response) {
					if (response.code === 0) {
					$.each(response.error, function(prefix, val) {
						$(form).find('span.' + prefix + '_error').text(val[0]);
					});
				} else {
					$(form)[0].reset();
					toastr.success(response.msg);
					var invoice = response.data;
					var row = invoiceRow(response.data.id, response.data.name, response.data.desc, response.data.status);
					$('#tableData').append(row);
				}
			}
		});

	}

}


function apply_pagination() {
	$pagination.twbsPagination({
		totalPages: totalPages,
		visiblePages: 6,
		onPageClick: function(event, page) {
			displayRecordsIndex = Math.max(page - 1, 0) * recPerPage;
			endRec = (displayRecordsIndex) + recPerPage;

			displayRecords = records.slice(displayRecordsIndex, endRec);
			generate_table();
		}
	});
}

function populateCompanyNameField () {

	$('#add_companyname').val($('#add_name').val().trim() + ' ' + $('#add_surname').val().trim());

}

function isBlank( data ) {
	return ( $.trim(data).length == 0 );
}

function submitClientAddForm(e) {

	e.preventDefault();
	
	const formData = new FormData();


	// var files = $('#file')[0].files;

	// console.log(file);

	var form = $(this)[0];

	// var formData = new FormData();

	// let formData = new FormData(this);

	// if(files.length > 0){
	// 	var fd = new FormData();
	// 	$.ajax({
    //         type:'POST',
    //         url: $(fd).attr('action'),
    //         data: fd,
    //         contentType: false,
    //         processData: false,
	// 		headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
    //         success: (response) => {
    //             if (response) {
    //                 this.reset();
    //                 alert('File has been uploaded successfully');
    //             }
    //         },
    //         error: function(response){
    //             $('#file-input-error').text(response.responseJSON.message);
    //         }
    //    });
	// }

	// ajaxData = {
	// 	id: $('#add_client_id').val(),
	// 	name: $('#add_name').val(),
	// 	surname: $('#add_surname').val(),
	// 	mobile: $('#add_mobile').val(),
	// 	email: $('#add_email').val(),
	// 	website: $('#add_website').val(),
	// 	landline: $('#add_landline').val(),
	// 	vat: $('#add_vat').val(),
	// 	companyreg: $('#add_companyreg').val(),
	// 	companyname: $('#add_companyname').val(),
	// 	address: $('#add_address').val(),
	// 	file: files,
	// 	user_id: 1
	// }

	formData.append( 'file', $('#logo')[0].files[0] );

	formData.append('id', $('#add_client_id').val());
	formData.append('name', $('#add_name').val());
	formData.append('surname', $('#add_surname').val());
	formData.append('mobile', $('#add_mobile').val());
	formData.append('email', $('#add_email').val());
	formData.append('website', $('#add_website').val());
	formData.append('landline', $('#add_landline').val());
	formData.append('vat', $('#add_vat').val());
	formData.append('companyreg', $('#add_companyreg').val());
	formData.append('companyname', $('#add_companyname').val());
	formData.append('address', $('#add_address').val());


	$.ajax({
		method: $(form).attr('method'),
		url: $(form).attr('action'),
		data: formData,
		headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false,
		cache: false,
		success: function(response) {

			if (response.code === 1) {

				var row = clientRow(response.data);
				$('#clientTableData').append(row);

				resetClientAddForm();
				$('#addClientModal').modal('hide');

				toastr.success(response.msg);

			} else {
				toastr.warning(response.msg);
			}

		},
		error: function(e) { console.log(e) }

	});

}

function openClientAddModal() {
	$('#addClientModal').modal('show');
	resetClientAddForm();

	$("#add_name").val("asdlkfjasd");
	$("#add_surname").val("asldkfjsd");
	$("#add_mobile").val("0796968869");
	$("#add_email").val("bevan@gmail.com");
	$("#add_website").val("www.datanav.co.za");
	$("#add_landline").val("0219064392");
	$("#add_vat").val("14523654785");
	$("#add_companyreg").val("2021/2323/asdfasd");
	$("#add_companyname").val("asdfa sf asdf ");
}

function resetClientAddForm() {
	$('#add_name').val('');
	$('#add_surname').val('');
	$('#add_client_id').val('');
	$('#add_mobile').val('');
	$('#add_email').val('');
	$('#add_website').val('');
	$('#add_landline').val('');
	$('#add_vat').val('');
	$('#add_companyreg').val('');
	$('#add_companyname').val('');
	$('#add_address').val('');
}

function submitClientForm(e) {

	e.preventDefault(); // avoid to execute the actual submit of the form.

	console.log('submitClientForm');

	var form = $(this)[0];

	// var formData = new FormData(form);
	// var url = $(this).attr('action');

	ajaxData = {
		id: $('#client_id').val(),
		name: $('#name').val(),
		surname: $('#surname').val(),
		mobile: $('#mobile').val(),
		email: $('#email').val(),
		website: $('#website').val(),
		landline: $('#landline').val(),
		vat: $('#vat').val(),
		companyreg: $('#companyreg').val(),
		companyname: $('#companyname').val(),
		address: $('#address').val()
	}

	console.log(ajaxData);

	console.log($(form).attr('action'));

	$.ajax({
		type: $(form).attr('method'),
		url: $(form).attr('action'),
		headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
		data: ajaxData,
		success: function(response) {

			if (response.code === 1) {
				form.reset();
				$('#editClientModal').modal('hide');
				toastr.success(response.msg);

				// Update values in the list

				$('tr[id="' + $('#client_id').val() + '"] th:nth-child(1)').text(response.data.companyname);
				$('tr[id="' + $('#client_id').val() + '"] th:nth-child(2)').text(response.data.name + ' ' + response.data.surname);
				$('tr[id="' + $('#client_id').val() + '"] th:nth-child(3)').text(response.data.mobile);
				$('tr[id="' + $('#client_id').val() + '"] th:nth-child(4)').text(response.data.email);


			} else {
				form.reset();
				$('#editClientModal').modal('hide');
				toastr.success(response.msg);
			}

		}
	});

}

function openClientEditModal() {

	var clientid = null;

	$('#editClientModal').modal('show');

	clientid = $(this).closest("tr").attr("id");
	if (clientid === null || clientid === undefined) {
		console.log('klient is nil');
	} else {

		$('#client_id').val(clientid);

		// get client info
		getClient(clientid);

	}

}

function deleteClientRow(e) {

	var ajaxData = { clientid: $('#modal_clientid').val() };

	$.ajax({

		method: 'get',
		url: '/deleteClientLine',
		data: ajaxData,
		dataType: 'json',
		success: function(response) {

			if (response.code === 1) {
				toastr.success(response.msg + invLineId);
				$('#' + invLineId).remove();
			} else {
				toastr.warning(response.msg + invLineId);
			}

			updateInvoiceTotal($('#inv_id').val());

		},
		error: function(e) {
			console.log(e);
		}
	});

}

function deleteClientModal() {
	$('#deleteClientModal').modal('show');
	var clientid = $(this).closest("tr").attr("id");
	$('#modal_clientid').val(clientid);
}

function backToInvoiceList(e) {
	window.location.href = '/invoice-list';
}

async function deleteInvLine(invLineId) {
	var ajaxData = { ivlId: invLineId };
	await deleteInvoiceLineData(ajaxData, invLineId);
}

function getClientList() {

	var user_id = 1;

	var ajaxData = { user_id: user_id };

	$.ajax({
		type: 'GET',
		url: '/getClientLineInfo',
		data: ajaxData,
		dataType: 'json',
		success: function(response) {
			generate_table(response);
		},
		error: function(e) {
			console.log(e);
		}
	});
}

function generate_table(data) {
	var output = '';
	$.each(data, function(index, data2) {
		var row = clientRow(data2);
		output += row;
	});
	$('#clientTableData').html(output);
}


function deleteInvoiceLineData(ajaxData, invLineId) {

	$.ajax({

		method: 'get',
		url: '/deleteInvoiceLineData',
		data: ajaxData,
		dataType: 'json',
		success: function(response) {

			if (response.code === 1) {

				toastr.success(response.msg + invLineId);
				$('#' + invLineId).remove();

			} else {

				alert(response.msg + invLineId);

			}

			// update invoice total
			var invoiceId = $('#inv_id').val();
			updateInvoiceTotal(invoiceId);

		},
		error: function(e) {
			console.log(e);
		}
	});

}

function setClientDropdown(inv_id) {
	var client_id = 1;
	$('#clientinfo').val(1);
	$("#clientinfo option[value=2]").attr('selected', 'selected');
}

function populateClientDropdown(user_id, currentClientId) {
	getClientLineInfo(user_id, currentClientId);
}

function getClient(client_id) {

	$.ajax({
		method: 'get',
		url: '/getClient',
		data: { clientid: client_id },
		dataType: 'json',
		contentType: false,
		success: function(data) {

			document.forms['edit-client-form']['name'].value = data.name;
			document.forms['edit-client-form']['surname'].value = data.surname;

			document.forms['edit-client-form']['mobile'].value = data.mobile;
			document.forms['edit-client-form']['email'].value = data.email;

			document.forms['edit-client-form']['website'].value = data.website;
			document.forms['edit-client-form']['landline'].value = data.landline;

			document.forms['edit-client-form']['vat'].value = data.vat;
			document.forms['edit-client-form']['companyreg'].value = data.companyreg;

			document.forms['edit-client-form']['companyname'].value = data.companyname;
			document.forms['edit-client-form']['address'].value = data.address;

			document.forms['edit-client-form']['client_id'].value = data.id;

		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(xhr.status);
			alert(thrownError);
			console.log(ajaxOptions);
		}
	});

}

function getClientLineInfo(user_id, currentClientId) {

	$.ajax({
		method: 'get',
		url: '/getClientLineInfo',
		data: { user_id: user_id },
		dataType: 'json',
		contentType: false,
		success: function(data) {

			populateClientSelect(data, currentClientId);

		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(xhr.status);
			alert(thrownError);
		}
	});
}

function isNumberKey(evt) {
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	if (charCode != 46 && charCode != 190 && charCode > 31 && (charCode < 48 || charCode > 57) && (charCode < 96 || charCode > 105)) {
		return { 'status': false };
	}
	return { 'status': true };
}

function getProductInfo(products, product_id) {

	var returnProd;

	$.each(products, function(key, product) {
		if (product.id == product_id) {
			returnProd = product;
		}
	});

	return returnProd;
}

function addCommas(nStr) {
	nStr += '';
	var x = nStr.split('.');
	var x1 = x[0];
	var x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
	return x1 + x2;
}

function populateProductSelect(products) {
	var $select = $('#inputProduct');
	$select.empty().append('<option value="">Choose a product</option>');
	$.each(products, function(key, product) {
		$select.append('<option value="' + product.id + '" data-unitprice="' + product.unitprice + '">' + product.product_name + '</option>');
	});
}

function retrieveProduct(productid) {
	$.ajax({
		type: 'GET',
		url: '/retrieveProduct',
		data: { id: productid },
		success: function(response) {
			let product = response[0];
			$('#unitpriceDesc').text('R ' + product.unitprice);
			var quantity = $('#inputQuantity').val();
			let total = product.unitprice * quantity;
			$('#TotalDesc').text('R ' + total);
		},
		error: function(e) {
			console.log(e);
		}
	});
}

function lineStatus(invoiceStatus) {
	output = '';
	if (invoiceStatus == 0) {
		output = 'new_invoice.jfif';
	} else if (invoiceStatus == 1) {
		output = 'overdue.png';
	} else {
		output = 'paid.jpg'
	}
	return '<img src="images/' + output + '" style="width: 35px;" />';
}

function buildInvoiceLines(invoiceid) {

	$.ajax({
		url: '/getInvoiceLineDetails',
		type: 'get',
		data: { inv_id: invoiceid },
		success: function(response) {
			var allrows = '';
			$.each(response.invoicelinesData, function(data1, data2) {
				var row = '<tr id="' + data2.invoice_line_id + '">' + TableCell(data2.product_name) + TableCell(data2.quantity) + TableCell(data2.unitprice) + TableCell(data2.linetotal) + actionInvoiceLine() + '</tr>';
				allrows = allrows + row;
			});
			$('#invoice-line-table>tbody').html(allrows);
			$('.invoiceTotal').text(response.invoiceTotal);
		}
	});

}

function updateInvoiceTotal(invoiceid) {

	$.ajax({
		url: '/getInvoiceLineDetails',
		type: 'get',
		data: { inv_id: invoiceid },
		success: function(response) {
			$('.invoiceTotal').text(response.invoiceTotal);
		}
	});

}

function clientRow(item) {
	return '<tr id="' + item.id + '">' +
		tablecell(item.companyname) +
		tablecell(item.name + ' ' + item.surname) +
		tablecell(item.mobile) +
		tablecell(item.email) +
		tablecell(editAndSaveButtons()) +
		'</tr>';
}

function tablecell(val) {
	return '<th>' + val + '</th>';
}

function editItem() {
	return '<button class="btn btn-info editClient"><i class="icon-pencil"></i></button>';
}

function deleteItem() {
	return '<button class="btn btn-danger deleteClient"><i class="icon-trash"></i></button>';
}

function editAndSaveButtons() {
	// return editItem() + '&nbsp;' + deleteItem();
	return editItem() + '&nbsp;';
}