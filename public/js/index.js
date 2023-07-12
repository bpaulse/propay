$(document).ready(function() {
	
	toastr.options.preventDuplicates = false;

	$("#clientinfo").select2();

	$('#loading-div').hide();

	$("#inputQuantity").focus(function() { $(this).select(); });

	$('#add-invoice-form').on('submit', function(e) {

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

	});

	$(document).on('click', '#add-invoice-line', function(e) {

		$('#lineupdateform').show();
		$('.update-productline').html('Add');

		//populate amount
		$.ajax({
			method: 'get',
			url: '/getProductInfo',
			data: {},
			dataType: 'json',
			contentType: false,
			success: function(data) {
				populateProductSelect(data.products);
				// reset values
				$('#quantityDesc').text(1);
				$('#inputQuantity').val(1)
				$('#unitpriceDesc').text('');
				$('#TotalDesc').text('');
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(xhr.status);
				alert(thrownError);
			}
		});

	});

	$(document).on('change', '#clientinfo', function(event) {

		var client_id = event.target.value;
		var invoice_id = $('#inv_id').val();
		saveClientToInvoice(client_id, invoice_id);

	});

	$(document).on('change', '#inputProduct', function(event) {

		var product_id = event.target.value;

		if (product_id === undefined || product_id == null || product_id === 0 || product_id === '') {

			$('#unitpriceDesc').text('');
			$('#TotalDesc').text('');

		} else {

			retrieveProduct(product_id);

		}

	});

	$(document).on('click', '.close-line-item', function(e) {
		$('#lineupdateform').hide();
	});

	$('.close-product-div').hover(function() {
		$(this).css('cursor', 'pointer');
	});

	$(document).on('click', '.close-product-div', function(e) {
		$('#unitprice').val('');
		$('#product_name').val('');
		$('#product_id').val(0);
		$('#productform').hide();
	});

	$(document).on('click', '.submit-product', function(e) {

		let type = $(this).text();
		var product_name = $('#product_name').val();
		var unitprice = $('#unitprice').val();
		var product_id = $('#product_id').val();


		if (type === 'Add') {
			product_id = 0;
		}

		var ajaxData = {
			product_name: product_name,
			unitprice: unitprice,
			product_id: product_id,
			user_id: 1
		};

		$.ajax({
			method: 'post',
			url: '/updateProductLine',
			data: ajaxData,
			headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
			success: function(response) {

				if (response.code === 1) {

					// update values of the product on the list of products
					var product = response.data.product;

					var row = '<tr id="' + product.id + '">' + TableCell(product.product_name) + TableCell(parseFloat(product.unitprice).toFixed(2)) + actionProduct() + '</tr>';
					$('#product-table>tbody').append(row);

					// hide add form
					$('#productform').hide();

					$('#product_name').val('');
					$('#unitprice').val('');

					toastr.success(response.msg);

				} else if (response.code === 3) {
					var product = response.data.product;

					// update row - get rowid
					$("#" + product.id + " td:nth-child(1)").text(product.product_name);
					$("#" + product.id + " td:nth-child(2)").text(product.unitprice);

					// hide add form
					$('#productform').hide();

					$('#product_name').val('');
					$('#unitprice').val('');
					toastr.success(response.msg);

				} else {
					toastr.warning(response.msg);
				}

			},
			error: function(e) { console.log(e) }

		});



	});

	$(document).on('click', '.update-productline', function(e) {

		let type = $(this).text();

		var quantity = $('#inputQuantity').val();
		var product_id = $('#inputProduct').val();
		var inv_line_id = $('#inv_line_id').val();

		var inv_id = $('#inv_id').val();

		if (type === 'Add') {
			inv_line_id = 0;
		}

		var ajaxData = {
			invoice_id: inv_id,
			invoice_line_id: inv_line_id,
			quantity: parseFloat(quantity).toFixed(2),
			product_id: product_id
		};

		$('#lineupdateform').hide();

		$.ajax({
			method: 'post',
			url: '/updateInvoiceLine',
			data: ajaxData,
			headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
			success: function(response) {

				if (response.code === 1 || response.code === 3) {

					$('#lineupdateform').hide();
					toastr.success(response.msg);
					buildInvoiceLines(inv_id);

				} else {
					toastr.warning(response.msg);
				}

			},
			error: function(e) { console.log(e) }

		});

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

	$(document).on('hover', '.delete-Product', function() {
		$(this).css('cursor', 'pointer');
	});

	$(document).on('hover', '#add-product-line', function() {
		$(this).css('cursor', 'pointer');
	});

	$(document).on('click', '.delete-invoiceline', function() {
		var trid = $(this).closest('tr').attr('id');
		deleteInvLine(trid);
	});

	$(document).on('click', '.add-invoiceline', function() {
		console.log('add-invoiceline');
	});

	$(document).on('click', '.edit-invoiceline', function() {
		var trid = $(this).closest('tr').attr('id');
		$('#lineupdateform').show();
		getInvoiceLineInfo(trid);
	});

	$(document).on('click', '.delete-Product', deleteLineProduct);

	$(document).on('click', '#add-product-line', displayProductAddForm);

	$(document).on('click', '.edit-Product', displayProductEditForm);

	$(document).on('click', '.sendInvoice', sendInvoice);

	$(document).on('click', '.addProduct', function() {

		// $('#event_id').val(eventid);
		$('#productModal').modal('show');
		$('#productform').hide();

		$.ajax({
			type: 'GET',
			url: '/getProductServicesList',
			data: '',
			processData: false,
			dataType: 'json',
			contentType: false,
			success: function(response) {

				var output = '';

				$.each(response.products, function(data1, data2) {

					var row = '<tr id="' + data2.id + '">' + TableCell(data2.product_name) + TableCell(data2.unitprice) + actionProduct() + '</tr>';
					output += row;
				});
				$('#product-table>tbody').html(output);

			},
			error: function(e) {
				console.log(e);
			}
		});
	});

	$(document).on('click', '.deleteInvoiceButton', function() {

		var invoiceID = $("#invoiceID").data('invoice_id');
		$('#deleteInvoiceModal').modal('show');

		const ajaxData = {inv_id: invoiceID};

		$.ajax({
			method: 'GET',
			url: '/deleteInvoice',
			data: ajaxData,
			dataType: 'json',
			contentType: false,
			success: function(data) {
	
				if (data.code) {

					toastr.success(data.msg);

					$('.invoiceline[data-id="'+data.invoice_id+'"]').remove();
					$('#deleteInvoiceModal').modal('hide');

				} else {
					toastr.warning(data.msg);
				}
	
			},
			error: function(e) {
				console.log(e);
			}
		});

	});

	$(document).on('click', '.deleteInvoice', function() {

		var invoice_id = $(this).closest('tr').attr('data-id');
		$('#deleteInvoiceModal').modal('show');
		const modalQuestion = 'Are you sure you want to delete the Invoice (Inv # ' + invoice_id + ') ?';
		$('#invoiceID').html( modalQuestion );
		$('#invoiceID').attr("data-invoice_id", invoice_id);
	});

	$(document).on('keyup', '#unitprice', function(event) {

		var unitpriceVal = $(this).val();

		if (!isNumberKey(event).status) {
			var currentString = $(this).val();
			// check the number of occurances of the dots
			newString = currentString.slice(0, -1);
			$(this).val(newString);
			unitpriceVal = newString;
		}

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

	$(document).on('focusout', '#invoice_name', saveInvoiceField);
	$(document).on('focusout', '#invoice_desc', saveInvoiceField);

	$("#editInvoiceModal").on("hidden.bs.modal", function() {
		// put your default event here

		let invoice_id = $('#inv_id').val();

		let invoice_name = $('#invoice_name').val();
		let invoice_desc = $('#invoice_desc').val();

		let element = $("tr").data("id");

		$('tr[data-id="' + invoice_id + '"] th:nth-child(3)').text(invoice_name);
		$('tr[data-id="' + invoice_id + '"] th:nth-child(4)').text(invoice_desc);


		$.ajax({
			method: 'get',
			url: '/getInvoiceLinesCount',
			data: { inv_id: invoice_id },
			dataType: 'json',
			contentType: false,
			success: function(data) {
				$('#first_item_' + invoice_id).text(invoice_id + "(" + data.details.count + ")");
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(xhr.status);
				alert(thrownError);
			}
		});


	});

	$(document).on('click', '.closeEditInvoiceModal', closeEditInvoiceModal);
	$(document).on('click', '.closeDeleteInvoiceModal', closeDeleteInvoiceModal);

	$(document).on('click', '.closeProductModal', closeProductModal);

	$(document).on('click', '.editInvoice', function() {

		$('#lineupdateform').hide();
		$('#clientinfodisplay').empty();

		let _token = $('meta[name="csrf-token"]').attr('content');
		var inv_id = $(this).closest('tr').attr('data-id');
		let ajaxData = { invoice_id: inv_id, _token: _token };

		$.ajax({
			method: 'get',
			url: '/getInvoiceDetails',
			data: ajaxData,
			dataType: 'json',
			contentType: false,
			success: function(data) {

				var currentClientId;

				if (data.invoiceClient !== null) {
					currentClientId = data.invoiceClient[0].client_id;
					// if (typeof myVar === 'undefined') {
				} else {
					currentClientId = 0;
				}

				const invoicedate = new Date(data.details.updated_at);
				var displayYearDate = invoicedate.getFullYear();
				var displayMonthDate = invoicedate.getMonth() + 1;
				var displayDayDate = invoicedate.getDate();

				$('#editInvoiceModal').modal('show');
				$('#invoice_id').text(padInvoiceNumber(data.details.id));
				$('#inv_id').val(inv_id);
				$('#invoice_name').val(data.details.invoice_name);
				$('#invoice_desc').val(data.details.invoice_desc);
				$('#created_date').text(displayYearDate + '-' + displayMonthDate + '-' + displayDayDate);

				// PLEASE CHANGE
				var user_id = 1;

				populateClientDropdown(user_id, currentClientId);
				buildInvoiceLines(inv_id);

			},
			error: function(e) {
				console.log(e);
			}
		});

	});

	$(document).on('click', '.addClient', openClientPage);

	$(document).on('click', '.statement', openStatementPage);

	$(document).on('click', '.backToInvoiceList', backToInvoiceList);

});

function backToInvoiceList(e) {
	window.location.href = '/invoice-list';
}

function openStatementPage() {
	window.location.href = '/statement-list';
}

function closeProductModal () {
	$('#productModal').modal('hide');
}

function closeEditInvoiceModal () {
	$('#editInvoiceModal').modal('hide');
}

function closeDeleteInvoiceModal () {
	$('#deleteInvoiceModal').modal('hide');
}

function saveInvoiceField() {

	let type = $(this).data('name');

	let ajaxData = {
		invoice_id: $('#inv_id').val(),
		fieldValue: $(this).val(),
		type: type
	}

	$.ajax({
		method: 'get',
		url: '/updateSingleInvoiceField',
		data: ajaxData,
		dataType: 'json',
		success: function(response) {
			if (response.code === 1) {
				toastr.success(response.msg);
			} else {
				toastr.warning(response.msg);
			}
		},
		error: function(e) {
			console.log(e);
		}
	});
}

function sendInvoice(e) {

	e.preventDefault();
	var id = $(this).closest('tr').attr('data-id');
	buildAndSendInvoice(id);

}

function buildAndSendInvoice(id) {

	var ajaxData = {
		invoiceid: id
	};

	var proceed = true;

	if ( proceed ) {

		$.ajax({

			method: 'get',
			url: '/buildAndSendInvoice',
			data: ajaxData,
			dataType: 'json',
			contentType: false,
			beforeSend: function() {
				$('#loading-div').show();
			},
			success: function(response) {

				if ( response.code == 1) {

					toastr.success(response.msg);
					$('#loading-div').hide();

					$('#status_img_' + id).attr('src', 'images/email.jpg');
				}

				// var data = JSON.parse(response);
				// console.log(data);
				// console.log(data.code);

				// if ( data.code == 1 ) {

				// 	toastr.success(response.msg);

				// // 	// set image to email jpg - get parent tr and then find the image
				// // 	$(this).closest('tr').find('th').eq(0).find('img').attr('src', 'images/email.jpg');

				// // 	// $(this).parent('td').parent('tr').
				// // 	// $('#emailImage').attr('src', '/images/email.jpg');

				// 	$('#loading-div').hide();

				// } else {
				// 	toastr.warning(response.msg);
				// }

			},
			error: function(jqXHR, textStatus, errorThrown) {
				$('#loading-div').hide();
				toastr.warning("Unable to send email...");

				console.log('Request failed: ' + jqXHR.status + ' - ' + jqXHR.responseText);
				console.log('Request failed: ' + textStatus + ' - ' + errorThrown);
			}

		});

	}


}

function deleteLineProduct() {

	var productid = $(this).closest('tr').attr('id');

	let ajaxData = { product_id: productid };

	$.ajax({
		method: 'get',
		url: '/deleteProduct',
		data: ajaxData,
		dataType: 'json',
		contentType: false,
		success: function(data) {

			if (data.code) {
				$('#' + data.product).remove();
				toastr.success(data.msg);
			} else {
				toastr.warning(data.msg);
			}

		},
		error: function(e) {
			console.log(e);
		}
	});

}

function openClientPage() {
	window.location.href = '/client-list';
};

function displayProductEditForm() {

	$('#productform').show();
	$('.submit-product').text('Update');

	// populate field values
	var productid = $(this).closest('tr').attr('id');

	var unitprice = $("#" + productid + " td:nth-child(2)").text();
	var product_name = $("#" + productid + " td:nth-child(1)").text();


	$('#unitprice').val(unitprice);
	$('#product_name').val(product_name);
	$('#product_id').val(productid);

}

function displayProductAddForm() {
	$('#productform').show();
	$('.submit-product').text('Add');

	$('#unitprice').val('');
	$('#product_name').val('');
	$('#product_id').val(0);

}

// callback function
function tryMe(param1, param2) {
	alert(param1 + " and " + param2);
}

// callback executer
function callbackTester(callback) {
	callback();
}

async function deleteInvLine(invLineId) {

	var ajaxData = { ivlId: invLineId };
	await deleteInvoiceLineData(ajaxData, invLineId);

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

				toastr.warning(response.msg + invLineId);

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

function saveClientToInvoice(client_id, invoice_id) {

	var ajaxData = {
		clientid: client_id,
		invoiceid: invoice_id
	};

	$.ajax({
		method: 'get',
		url: '/saveClientToInvoice',
		data: ajaxData,
		dataType: 'json',
		success: function(response) {

			$('#clientinfodisplay').empty();

			var item = response.data.clientDetails;

			var clientinfodisplay = "<div style='float: left; border: 0px solid black;'>" + item.companyname + "<br />" + item.name + ' ' + item.surname + "<br />" + item.landline + "<br />" + item.mobile + "<br />" + item.companyreg + "</div>" + "<div style='float: right;'>" + item.address + "<br />" + item.email + "<br />" + item.website + "<br />" + item.vat + "</div>";
			$('#clientinfodisplay').html(clientinfodisplay);

			toastr.success('Client changed for this Invoice...');

			$('.invoice-update-status').show();
			$('.invoice-update-status').text('You have changed the client for this Invoice to ' + item.companyname + ' ...');
			$('.invoice-update-status').css('background-color', 'blue');
			$('.invoice-update-status').css('color', 'white');
			setTimeout(function() {
				$('.invoice-update-status').hide();
				$('.invoice-update-status').text('');
			}, 5000);

		},
		error: function(e) {
			console.log(e);
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

function getInvoiceLineInfo(invoicelineid) {

	$.ajax({
		method: 'get',
		url: '/getInvoiceLineInfo',
		data: { inv_line_id: invoicelineid },
		dataType: 'json',
		contentType: false,
		success: function(data) {

			populateProductSelect(data.products);

			$('#inputQuantity').val(parseFloat(data.invoicelineInfo.quantity).toFixed(2));
			$("#inputProduct").val(data.invoicelineInfo.product_id);
			$("#inv_line_id").val(invoicelineid);

			var linetotal = data.invoicelineInfo.quantity * data.unitprice;

			$('#quantityDesc').text(parseFloat(data.invoicelineInfo.quantity).toFixed(2));
			$('#unitpriceDesc').text('R ' + data.unitprice);
			$('#TotalDesc').text('R ' + linetotal);

			$('.update-productline').text('Update');

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

function invoiceLineStatusString(quantity, unitprice) {
	var tt = quantity * unitprice;

	output = '<table style="border: 1px solid black;">';
	output = output + '<tr><td>UnitPrice</td><td>' + unitprice + '</td><td>Total</td></tr>'
	output = output + '<tr><td>Quantity</td><td>' + quantity + '</td><td>' + tt + '</td></tr>';
	output = output + '</table>';

	return output;
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

function populateClientSelect(data, currentclientid) {
	var $select = $('#clientinfo');
	$select.empty().append('<option value="">Choose a Client</option>');
	$.each(data, function(key, item) {

		if (item.id === currentclientid) {

			$select.append('<option value="' + item.id + '" selected="true">' + item.companyname + '</option>');
			$('#clientinfodisplay').empty();
			var clientinfodisplay = "<div style='float: left; border: 0px solid black;'>" + item.companyname + "<br />" + item.name + ' ' + item.surname + "<br />" + item.landline + "<br />" + item.mobile + "<br />" + item.companyreg + "</div>" + "<div style='float: right; border: 0px solid black;'>" + item.address + "<br />" + item.email + "<br />" + item.website + "<br />" + item.vat + "</div>";
			$('#clientinfodisplay').html(clientinfodisplay);

		} else {
			$select.append('<option value="' + item.id + '">' + item.companyname + '</option>');
		}

	});

}

function padInvoiceNumber(id) {
	const prefix = 'INV_';
	let zeros;
	if (id >= 0 && id < 10) {
		zeros = '00000';
	} else if (id >= 10 && id < 100) {
		zeros = '0000';
	} else if (id >= 100 && id < 1000) {
		zeros = '000';
	} else if (id >= 1000 && id < 10000) {
		zeros = '00';
	} else if (id >= 10000 && id < 10000) {
		zeros = '0';
	} else {
		zeros = '';
	}
	return prefix + zeros + id.toString();;
}

async function genericGet(data) {

	let result;

	try {

		result = await $.ajax({
			type: data.type,
			url: data.url,
			data: data.data,
			processData: false,
			dataType: data.dataType,
			contentType: false,
		});

		return result;

	} catch (error) {
		console.error(error);
	}

}

var goog = getInvoiceList().then(function(res) {
	
	var output = '';

	$.each(res.details, function(data1, data2) {
		var row = invoiceRow(data2.id, data2.invoice_name, data2.invoice_desc, data2.invoiceStatus);
		output += row;
	});
	$('#tableData').html(output);

	$.each(res.invoicelines, function(index, item){
		$('#first_item_' + item.id).text(item.id + "(" + item.count + ")");
	});

});

function getInvoiceList() {

	let data = {
		type: 'GET',
		url: '/getInvoicesList',
		data: '',
		processData: false,
		dataType: 'json',
		contentType: false,
	};

	return genericGet(data).then((returnValue) => returnValue );

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

function invoiceRow(invoiceId, invoiceName, invoiceDesc, invoiceStatus) {
	return '<tr class="invoiceline" data-id="' + invoiceId + '">' + 
	TableHCellFirst(invoiceId) + 
	TableHCell(lineStatus(invoiceStatus, invoiceId)) + 
	TableHCell(invoiceName) + 
	TableHCell(invoiceDesc) + 
	TableHCell(editAndSaveButtons()) + '</tr>';
}

function productRow(item) {
	return '<tr data-id="' + invoiceId + '"><th>' + editAndSaveButtons() + '</th></tr>';
}

function lineStatus(invoiceStatus, id) {
	output = '';
	if (invoiceStatus == 0) {
		output = 'new_invoice.jfif';
	} else if (invoiceStatus == 1) {
		output = 'email.jpg';
	} else {
		output = 'paid.jpg'
	}
	return '<img  id="status_img_'+id+'" src="images/' + output + '" style="width: 35px;" />';
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
			$('#invoice-line-table>tbody').empty();
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

function TableCell(val) {
	return '<td>' + val + '</td>';
}

function TableHCellFirst(val) {
	return '<th id="first_item_'+val+'">' + val + '</th>';
}

function TableHCell(val) {
	return '<th>' + val + '</th>';
}

function actionInvoiceLine() {
	return '<td style="text-align: center;">' + editInvoiceLine() + '&nbsp;' + deleteInvoiceLine() + '</i></td>';
}

function editInvoiceLine() {
	return '<i class="icon-pencil edit-invoiceline"></i>';
}

function deleteInvoiceLine() {
	return '<i class="icon-trash delete-invoiceline"></i>';
}

function actionProduct() {
	return '<td style="text-align: center;">' + editProduct() + '&nbsp;' + deleteProduct() + '</i></td>';
}

function editProduct() {
	return '<i class="icon-pencil edit-Product"></i>';
}

function deleteProduct() {
	// return '<i class="icon-trash delete-Product"></i>';
	return '';
}

function editAndSaveButtons() {
	return '<button class="btn btn-info editInvoice"><i class="icon-pencil"></i></button>' + '&nbsp;' + '<button class="btn btn-danger deleteInvoice">' + '<i class="icon-trash"></i>' + '</button>' + '&nbsp;' + '<button class="btn btn-success sendInvoice">' + '<i class="icon-envelope"></i>' + '</button>';
}