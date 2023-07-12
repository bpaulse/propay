$(document).ready(function() {

	$("#filterBtn"). attr("disabled", true);

	$('.selectpicker').selectpicker();
	$('#selectpicker').on('change', selectStatusChange);

	$('.preselectPeriod').selectpicker();
	$('#preselectPeriod').on('change', preselectPeriodChange);

	var from = $('#fromDate').datepicker({
		dateFormat: 'yy-mm-dd',
		changeMonth: true
	}).on('change', function() {
		to.datepicker('option', 'minDate', getDate(this));
	}),
	to = $('#toDate').datepicker({
		dateFormat: 'yy-mm-dd',
		changeMonth: true
	}).on('change', function() {
		from.datepicker('option', 'maxDate', getDate(this));
	});
	
	toastr.options.preventDuplicates = false;

	$("#clientinfo").select2();

	$('#loading-div').hide();

	$(document).on('click', '.closeEditInvoiceModal', closeEditInvoiceModal);
	$(document).on('click', '.closeDeleteInvoiceModal', closeDeleteInvoiceModal);

	$(document).on('click', '.closeProductModal', closeProductModal);

	$(document).on('click', '.addClient', openClientPage);

	$(document).on('click', '.statement', openStatementPage);

	$(document).on('click', '.backToInvoiceList', backToInvoiceList);

	$(document).on('click', '#filterBtn', filterInvoices);

	$(document).on('click', '#printPDFStatement', printPDFStatement);

});


function printPDFStatement () {
	console.log('printPDFStatement');

	var selectedText = $('#selectpicker option:selected').text();
	var selectID = $('#selectpicker option:selected').val();

	console.log('selectedText: ' + selectedText);
	console.log('selectedID: ' + selectID);

	const fromDateStr = ( isDate($('#fromDate').val()) ? $('#fromDate').val() : 0);
	const toDateStr = ( isDate($('#toDate').val()) ? $('#toDate').val() : 0);

	console.log('Dates');
	console.log(fromDateStr, toDateStr);


}

function isDate(value) {
	// Create a new Date object from the value
	const date = new Date(value);
  
	// Check if the date is valid
	// The getTime() method returns NaN for an invalid date
	return !isNaN(date.getTime());
}

function filterInvoices (e) {

	e.preventDefault();
	console.log('filterInvoices');

	var selectedText = $('#selectpicker option:selected').text();
	var selectID = $('#selectpicker option:selected').val();

	console.log('selectedText: ' + selectedText);
	console.log('selectedID: ' + selectID);

	const fromDateStr = ( isDate($('#fromDate').val()) ? $('#fromDate').val() : 0);
	const toDateStr = ( isDate($('#toDate').val()) ? $('#toDate').val() : 0);

	console.log('Dates');
	console.log(fromDateStr, toDateStr);

	if ( fromDateStr == 0 || toDateStr == 0 ) {
		console.log('dates will be excluded...');
	} else {
		console.log('Dates are added...');
	}


	if ( selectID == null ) {
		toastr.error('Please select a status');
	} else {

		$.ajax({
			method: 'get',
			url: '/filterInvoices',
			data: { status: selectID, fromDate: fromDateStr, toDate: toDateStr },
			dataType: 'json',
			contentType: false,
			beforeSend: function() {
				$('#loading-div').show();
			},
			success: function(res) {

				console.log(res);

				$('#tableData').html('');

				var output = '';
				let fullAmount = 0.00;
			
				$.each(res.details, function(data1, data2) {
					
					const lineAmount = data2.invoiceline.reduce((acc, item) => acc + parseFloat(item.linetotal), 0);
					fullAmount += lineAmount;
					var row = invoiceRow(data2.id, data2.invoice_name, data2.invoice_desc, data2.invoiceStatus, lineAmount);
					output += row;
			
				});
			
				$('#tableData').html(output);
				$('#total').html('R' + ' ' + addCommas(fullAmount.toFixed(2)));
				$('#total').css('font-weight', 'bold');
			
				$.each(res.invoicelines, function(index, item){
					$('#first_item_' + item.id).text(item.id + "(" + item.count + ")");
				});
			


				$('#loading-div').hide();
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(xhr.status);
				alert(thrownError);
			}
		});

	}

}


function preselectPeriodChange() {
	console.log('preselectPeriodChange');
	var period = $(this).val();
	console.log('period: ' + period);

	if ( period == 0 ) {
		console.log('Yesterday');
		setYesterdayDates();
	} else if ( period == 1 ) {
		console.log('this week');
		setThisWeekDates();
	} else if ( period == 2 ) {
		console.log('last week');
		setPreviousWeekDates();
	} else if ( period == 3 ) {
		console.log('this month');
		setThisMonthDates();
		// console.log("Start date:", lastMonthDates.start);
		// console.log("End date:", lastMonthDates.end);
	} else if ( period == 4 ) {
		console.log('last month');
		setLastMonthDates();
	}

}

function setYesterdayDates() {
	const currentDate = new Date();

	// Calculate yesterday's date
	const yesterday = new Date(currentDate);
	yesterday.setDate(currentDate.getDate() - 1);

	// Format the date as a string (YYYY-MM-DD)
	const formattedYesterday = yesterday.toISOString().slice(0, 10);

	console.log(formattedYesterday);

	$('#fromDate').val(formattedYesterday);
	$('#toDate').val(formattedYesterday);
}

function setThisMonthDates() {

	var currentDate = new Date(); // Get the current date
	var currentMonth = currentDate.getMonth(); // Get the current month (0-11)
	var currentYear = currentDate.getFullYear(); // Get the current year


	// If the previous month is December, subtract 1 from the current year
	var previousYear = (currentMonth === 0) ? currentYear - 1 : currentYear;
	// Create a new Date object for the first day of the previous month
	var firstDayOfCurrentMonth = new Date(previousYear, currentMonth, 2);


	// Format the dates as strings in the format "YYYY-MM-DD"
	var startDate = firstDayOfCurrentMonth.toISOString().split('T')[0];
	console.log(startDate);

	const endDate = currentDate.toISOString().slice(0, 10);

	$('#fromDate').val(startDate);
	$('#toDate').val(endDate);

	// return {
	// 	start: startDate,
	// 	end: endDate
	// };

}


function setLastMonthDates() {

	var currentDate = new Date(); // Get the current date
	var currentMonth = currentDate.getMonth(); // Get the current month (0-11)
	var currentYear = currentDate.getFullYear(); // Get the current year

	// Subtract 1 from the current month to get the previous month
	var previousMonth = (currentMonth === 0) ? 11 : currentMonth - 1;

	// If the previous month is December, subtract 1 from the current year
	var previousYear = (currentMonth === 0) ? currentYear - 1 : currentYear;
	// Create a new Date object for the first day of the previous month
	var firstDayOfPreviousMonth = new Date(previousYear, previousMonth, 2);

	// Create a new Date object for the last day of the previous month
	var lastDayOfPreviousMonth = new Date(previousYear, previousMonth + 1, 0);

	// Format the dates as strings in the format "YYYY-MM-DD"
	var startDate = firstDayOfPreviousMonth.toISOString().split('T')[0];

	var endDate = lastDayOfPreviousMonth.toISOString().split('T')[0];

	$('#fromDate').val(startDate);
	$('#toDate').val(endDate);

	// return {
	// 	start: startDate,
	// 	end: endDate
	// };

}

function setThisWeekDates() {
	const currentDate = new Date();

	// Calculate the start date of the previous week

	const startOfCurrentWeek = new Date(currentDate);
	startOfCurrentWeek.setDate(currentDate.getDate() - currentDate.getDay() + 1); // Set to the current Sunday

	// Format the start date as a string (YYYY-MM-DD)
	const formattedStartDate = startOfCurrentWeek.toISOString().slice(0, 10);

	const formatcurrentDate = currentDate.toISOString().slice(0, 10);

	$('#fromDate').val(formattedStartDate);
	$('#toDate').val(formatcurrentDate);
}

function setPreviousWeekDates() {
	const currentDate = new Date();

	// Calculate the start date of the previous week
	const startOfPreviousWeek = new Date(currentDate);
	startOfPreviousWeek.setDate(currentDate.getDate() - currentDate.getDay() - 6); // Set to the previous Sunday

	// Calculate the end date of the previous week
	const endOfPreviousWeek = new Date(startOfPreviousWeek);
	endOfPreviousWeek.setDate(startOfPreviousWeek.getDate() + 6); // Set to the following Saturday

	// Format the dates as strings (YYYY-MM-DD)
	const formattedStartDate = startOfPreviousWeek.toISOString().slice(0, 10);
	const formattedEndDate = endOfPreviousWeek.toISOString().slice(0, 10);

	// Output the start and end dates
	// console.log("Start Date of Previous Week:", formattedStartDate);
	// console.log("End Date of Previous Week:", formattedEndDate);

	$('#fromDate').val(formattedStartDate);
	$('#toDate').val(formattedEndDate);
}

function selectStatusChange() {

	console.log('selectStatusChange');
	
	var status = $(this).val();
	var selectedText = $('#selectpicker option:selected').text();

	// console.log('status: ' + status);
	// console.log('selectedText: ' + selectedText);

	if ( status ) {
		console.log('true');
		$("#filterBtn"). attr("disabled", false);
	} else {
		console.log('false');
		$("#filterBtn"). attr("disabled", true);
	}

	// console.log('id: ' + id);
	// console.log('status: ' + status);
	// updateInvoiceStatus(id, status);

}

function getDate( element ) {

	var date;
	var dateFormat = "yy-mm-dd";
	try {
		date = $.datepicker.parseDate( dateFormat, element.value );
	} catch( error ) {
		date = null;
	}
	return date;

}

function backToInvoiceList(e) {
	window.location.href = '/invoice-list';
}

function openStatementPage() {
	console.log('openStatementPage');
	window.location.href = '/statement-list';
}

function closeProductModal () {
	console.log('closeProductModal');
	$('#productModal').modal('hide');
}

function closeEditInvoiceModal () {
	console.log('closeEditInvoiceModal');
	$('#editInvoiceModal').modal('hide');
}

function closeDeleteInvoiceModal () {
	$('#deleteInvoiceModal').modal('hide');
}

function sendInvoice(e) {

	e.preventDefault();
	var id = $(this).closest('tr').attr('data-id');

	console.log('SendInvoice');
	console.log('id: ' + id);

	buildAndSendInvoice(id);
}

function openClientPage() {
	window.location.href = '/client-list';
};



function setClientDropdown(inv_id) {
	var client_id = 1;
	$('#clientinfo').val(1);
	$("#clientinfo option[value=2]").attr('selected', 'selected');
}

function populateClientDropdown(user_id, currentClientId) {
	getClientLineInfo(user_id, currentClientId);
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
		zeros = '0000';
	} else if (id >= 10 && id < 100) {
		zeros = '000';
	} else if (id >= 100 && id < 1000) {
		zeros = '00';
	} else if (id >= 1000 && id < 10000) {
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
	let fullAmount = 0.00;

	$.each(res.details, function(data1, data2) {
		
		const lineAmount = data2.invoiceline.reduce((acc, item) => acc + parseFloat(item.linetotal), 0);
		fullAmount += lineAmount;
		var row = invoiceRow(data2.id, data2.invoice_name, data2.invoice_desc, data2.invoiceStatus, lineAmount);
		output += row;

	});

	$('#tableData').html(output);
	$('#total').html('R' + ' ' + addCommas(fullAmount.toFixed(2)));
	$('#total').css('font-weight', 'bold');

	$.each(res.invoicelines, function(index, item){
		$('#first_item_' + item.id).text(item.id + "(" + item.count + ")");
	});

});
//physifpq
//c!D25u5tdk6uu8

function getInvoiceList() {

	let data = {
		type: 'GET',
		url: '/getInvoicesList',
		data: '',
		processData: false,
		dataType: 'json',
		contentType: false,
	};

	return genericGet(data).then((returnValue) => returnValue);

}

function invoiceRow(invoiceId, invoiceName, invoiceDesc, lineStatus, lineAmount) {
	return '<tr class="invoiceline" data-id="' + invoiceId + '">' + 
	TableHCellFirst(invoiceId) + 
	TableHCell(invoiceName, 'left') + 
	TableHCell(invoiceDesc, 'left') + 
	TableHCell('R' + ' ' + (Math.round(lineAmount * 100) / 100).toFixed(2), 'right') + '</tr>';
}

function productRow(item) {
	return '<tr data-id="' + invoiceId + '"><th>' + editAndSaveButtons() + '</th></tr>';
}

function lineStatus(invoiceStatus) {
	// console.log(invoiceStatus)
	output = '';
	if (invoiceStatus == 0) {
		output = 'new_invoice.jfif';
		// output = '';
	} else if (invoiceStatus == 1) {
		output = 'email.png';
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

function TableCell(val, style) {
	return '<td style="text-align: ' + style + ';">' + val + '</td>';
}

function TableHCellFirst(val) {
	return '<th id="first_item_'+val+'">' + val + '</th>';
}

function TableHCell(val, style) {
	return '<th style="text-align: ' + style + ';">' + val + '</th>';
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

$(function() {

    var start = moment().subtract(29, 'days');
    var end = moment();

    function cb(start, end) {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    }

    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);

    cb(start, end);

});