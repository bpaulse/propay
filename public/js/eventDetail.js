// tinymce.init({selector:'textarea', inline: true});

$(document).ready(function(){

	toastr.options.preventDuplicates = false;

	// get name of the event
	var eventid = window.location.href.split('/').pop();

	setTableHeader (eventid);
	loadWods(eventid);

	$(document).on('click', '.scoreboard', function(evt){
		let wodid = $(this).closest('tr').attr('data-wod_id');
		window.location.href = '/wodResults/' + eventid + '/' + wodid;
	});

	$(document).on('click', '.backEvent', backToEvents);

	$(document).on('click', '.addAthlete', function () {

		$('#event_id').val(eventid);
		$('#addAthleteModal').modal('show');

		$('input[name=sample-radio][value=NewUser]').prop('checked', true);
	
		$('#add-athlete-form').css('display', 'block');
		$('#assoc-athlete-form').css('display', 'none');

		getGender();
		getCategory();

	});

	$(document).on('click', '.closeAddWODModal', function () {
		console.log('close x');
		$('#addWODModal').modal('hide');
	});

	$(document).on('click', '.addWod', function () {
		console.log('addWod');
		console.log(eventid);
		$('#addWODModal').modal('show');
		// hide time element

		$('#event_id').val(eventid);
		$('#fortime').hide();
	});

	$(document).on('change', '#wod_type', function () {

		if ( $('#wod_type option:selected').text() === 'For Time' ) {
			$('#fortime').show();
		} else {
			$('#fortime').hide();
		}

	});

	$(document).on('submit', '#add-wod-form', function(evt){

		evt.preventDefault();
		var doAjax = true;

		if ( doAjax ) {

			var form = this;

			let ajaxData = {
				wod_name: $('#wod_name').val(),
				wod_type: $('#wod_type').val(),
				wod_desc: $('#wod_desc').val(),
				event_id: $('#event_id').val(),
				wod_id: $('#wod_id').val(),
			};

			console.log(ajaxData);

			$.ajax({
				type: $(form).attr('method'),
				url: $(form).attr('action'),
				headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
				data: ajaxData,

				success: function (response) {

					var type = '';

					if ( response.code === 0 ) {
						// $.each(response.error, function(prefix, val){
						// 	$(form).find('span.'+prefix+'_error').text(val[0]);
						// });
					} else {

						$(form)[0].reset();

						$('#addWODModal').modal('hide');

						toastr.success(response.msg);
						var event = response.data;

						var row = wodRow(
							{
								'id': event.wod.id, 
								'wodname': event.wod.wodname, 
								'woddesc': event.wod.woddesc, 
								'wodtype': event.wod.wodtype_id, 
								'settingdesc': event.wod.wodtype
							},
							type
						);

						$('#nodata').remove();
						$('#wodData').append(row);
					}

				},
				error: function(xhr, ajaxOptions, thrownError) { console.log(xhr.responseText); }
			});

		}

	});

	$(document).on('submit', '#add-athlete-form', function(evt){

		evt.preventDefault();
		var doAjax = true;

		//get value from dropdown
		var athlete_type = $('#athlete_type option:selected').val();
		var gender_type = $('#gender_type option:selected').val();

		// console.log('gender_type');
		// console.log(gender_type);

		// console.log('athlete_type');
		// console.log(athlete_type);

		if ( doAjax ) {

			var form = this;

			let ajaxData = {
				athlete_name:		$('#athlete_name').val(),
				athlete_surname:	$('#athlete_surname').val(),
				athlete_mobile:		$('#athlete_mobile').val(),
				athlete_email:		$('#athlete_email').val(),
				athlete_type:		athlete_type,
				gender_type:		gender_type,
				event_id:			$('#event_id').val(),
			};

			// console.log(ajaxData);

			$.ajax({
				type: $(form).attr('method'),
				url: $(form).attr('action'),
				headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
				data: ajaxData,
				// beforeSend: function() {
						// $(form).find('span.error-text').text('');
				// },
				success: function (response) {

					// console.log(response);

					if ( response.code === 0 ) {
						toastr.warning(response.msg);
					} else {

						$('#addAthleteModal').modal('hide');

						$(form)[0].reset();
						toastr.success(response.msg);

					}

				},
				error: function(xhr, ajaxOptions, thrownError) { console.log(xhr.responseText); }
			});


		} else {
			console.log('Skip Ajax Request');
		}

	});

	$(document).on('click', '.wodDetails', function(evt) {

		console.log('wodDetails');
		var wodid = $(this).closest('tr').attr("data-wod_id");
		window.location.href = '/wodDetails/' + eventid + '/' + wodid;

	});

	$(document).on('click', '.editWod', function(evt){

		$('#editEventModal').modal('show');
		let wodid = $(this).closest('tr').attr('data-wod_id');

		$('#wod_id').val(wodid);

		let wodData = {
			wodid: wodid
		};

		getWODDetails(wodData);

	});

	$(document).on('click', '.updateWod', updateEventWod);

	$(document).on('change', 'input[name=sample-radio]', function(evt){

		// console.log($(this).val());
		if ( $(this).val() == 'ExistingUser' ) {
			// console.log('Existing');
			$('#add-athlete-form').css('display', 'none');
			$('#assoc-athlete-form').css('display', 'block');
			// console.log('hide form');
		} else {
			// console.log('New');
			$('#add-athlete-form').css('display', 'block');
			$('#assoc-athlete-form').css('display', 'none');
			// console.log('show form');
		}

	});

	$('#addWODModal').on('hidden.bs.modal', function () {
		console.log('addWODModal');
		// $(this).find('form').trigger('reset');
	});


	$('.postName').select2({
		placeholder: 'Select an item',
		disabled: false,
		dropdownParent: $('#addAthleteModal'),
		ajax: {
			url: '/getAthletes',
			dataType: 'json',
			delay: 250,
			data: function (data) {
				console.log(data);
				return {
					searchTerm: data.term // search term
				};
			},
			processResults: function (response) {
				return {
					results:response
				};
			},
			cache: true
		}
	});

});

function getAthletes() {

	$.ajax({
		type: 'GET',
		url: '/getAthletes',
		data: {},
		dataType: 'json',
		contentType: false,
		success: function (athletes) {

			var output = [];

			$.each(athletes, function (id, athlete) {
				output.push(athlete.Name + ' ' + athlete.Surname);
			});

			autocomplete(document.getElementById("myInput"), output);

		},
		error: function(e) {
			console.log(e);
		}
	});

}

function updateEventWod(evt){

	evt.preventDefault();
	// wod_edit_name
	var ajaxData = {
		wod_id: $("#wod_id").val(),
		wod_name: $("#wod_edit_name").val(),
		wod_desc: $.trim($("#wod_edit_desc").val()),
		wod_type: $("#wod_edit_type option:selected").val(),
		wod_type_name: $("#wod_edit_type option:selected").text()
	};

	updateAjaxWod(ajaxData);

}

function updateAjaxWod(data) {

	$.ajax({
		type: 'GET',
		url: '/updateWod',
		data: data,
		dataType: 'json',
		contentType: false,
		success: function (response) {

			if ( response.update == 1 ) {

				toastr.success('WOD updated successfully...');
				$('#editEventModal').modal('hide');

				$('tr[data-wod_id="' + response.wod_id + '"] th:nth-child(1)').html(response.wodData.wodname);
				$('tr[data-wod_id="' + response.wod_id + '"] th:nth-child(2)').html(response.wodData.woddesc);
				$('tr[data-wod_id="' + response.wod_id + '"] th:nth-child(3)').text(response.wodData.wodtypename);


			} else {
				toastr.warning('ERROR updating the WOD. Please try again...');
			}

		},
		error: function(e) {
			console.log(e);
		}
	});

}

function getWODDetails (wodData) {

	$.ajax({
		type: 'GET',
		url: '/getWODDescAndSettings',
		data: wodData,
		dataType: 'json',
		contentType: false,
		success: function (response) {

			let wod = response.wod;

			$('#wod_edit_name').val(wod.wodname);
			$('#wod_edit_desc').val(wod.woddesc);

			let settings = response.settings;

			var ddl = $("#wod_edit_type");
			ddl.empty();
			ddl.append($("<option></option>").val("").html("--Select--"));

			$.each(settings, function (index, item) {

				if ( wod.wodtype == item.id ) {
					ddl.append($("<option selected></option>").val(item.id).html(item.settingdesc));
				} else {
					ddl.append($("<option></option>").val(item.id).html(item.settingdesc));
				}

			});

		},
		error: function(e) {
			console.log(e);
		}
	});

}

function backToEvents () {
	window.location.href = '/events';
}

function addAthlete () {
	$('#event_id').val(eventid);
	$('#addAthleteModal').modal('show');
}

function getGender() {

	$.ajax({
		type: 'GET',
		url: '/getGender',
		data: {},
		dataType: 'json',
		contentType: false,
		success: function (response) {

			var items = "";
			$.each(response, function(index,data2){
				items += "<option id='"+data2.id+"'>" + data2.settingdesc + "</option>";
			});
			$("#gender_type").append(items);

		},
		error: function(e) {
			console.log(e);
		}
	});

}

function getCategory() {

	$.ajax({
		type: 'GET',
		url: '/getCategory',
		data: {},
		dataType: 'json',
		contentType: false,
		success: function (response) {

			var items = "";
			$.each(response, function(index,data2){
				items += "<option id='"+data2.id+"'>" + data2.settingdesc + "</option>";
			});
			$("#athlete_type").append(items);

		},
		error: function(e) {
			console.log(e);
		}
	});

}

function loadWods(eventid) {

	$.ajax({
		type: 'GET',
		url: '/getWodsForEvent',
		data: {eventid: eventid},
		dataType: 'json',
		contentType: false,
		success: function (response) {

			var output = '';

			if ( response.count === 0 ) {
				output = wodRowNoData();
			} else {
				$.each(response.data, function(data1,data2){
					var row = wodRow({'id': data2.id, 'wodname': data2.wodname, 'woddesc': data2.woddesc, 'wodtype': data2.wodtype, 'settingdesc': data2.settingdesc, 'event_date': data2.event_date });
					output += row;
				});
			}

			$('#wodData').html(output);
		},
		error: function(e) {
			console.log(e);
		}
	});

}

function wodRow (wod, type) {
	return '<tr data-wod_id="'+ wod.id +'"><th>' + wod.wodname + '</th><th>' + wod.woddesc + '</th><th>' + wod.settingdesc  + '</th><th>' + editAndSaveButtons(wod.event_date) + '</th></tr>';
}

function wodRowNoData() {
	return '<tr id="nodata"><th colspan="4" style="text-align: center; color: blue;">No WODS loaded...</th></tr>';
}

function editAndSaveButtons(event_date) {

	const eventDateObj = new Date(event_date);
	const currentDateObj = new Date();

	if ( eventDateObj < currentDateObj ) {
		return scoreBoardButton();
	} else {
		return editButton () + '&nbsp;' + '&nbsp;' + scoreBoardButton() + '&nbsp;' + '&nbsp;' + clickThroughButton();
	}

}

function clickThroughButton () {
	return '<button class="btn btn-success wodDetails">' + '<i class="icon-chevron-right"></i>' + '</button>';
}

function deleteButton() {
	return '<button class="btn btn-danger deleteInvoice">' + '<i class="icon-trash"></i>' + '</button>';
}

function editButton () {
	return '<button class="btn btn-info editWod"><i class="icon-pencil"></i></button>';
}

function scoreBoardButton() {
	return '<button class="btn btn-warning scoreboard">' + '<i class="icon-list-ol"></i>' + '</button>';
}

function setTableHeader (event_id) {

	var ajaxData = {eventid: event_id};
	// console.log(ajaxData);
	$.ajax({
		type: 'GET',
		url: '/getEventName',
		data: ajaxData,
		dataType: 'json',
		success: function (response) {
			let tableheader = 'Event' + ' (' + response.name + ')';
			$('#tableHeader').text(tableheader);
		},
		error: function(xhr) {
			console.log(xhr);
		}
	});

}

function delete_search_history(id) {

	fetch("process_data.php", {

		method: "POST",
		body: JSON.stringify({
			action:'delete',
			id:id
		}),
		headers:{
			'Content-type' : 'application/json; charset=UTF-8'
		}

	}).then(function(response){

		return response.json();

	}).then(function(responseData){
		load_search_history();
	});
}

function load_search_history(){
	var search_query = document.getElementsByName('search_box')[0].value;

	if(search_query == '')
	{

		fetch("process_data.php", {

			method: "POST",

			body: JSON.stringify({
				action:'fetch'
			}),

			headers:{
				'Content-type' : 'application/json; charset=UTF-8'
			}

		}).then(function(response){

			return response.json();

		}).then(function(responseData){

			if(responseData.length > 0)
			{

				var html = '<ul class="list-group">';

				html += '<li class="list-group-item d-flex justify-content-between align-items-center"><b class="text-primary"><i>Your Recent Searches</i></b></li>';

				for(var count = 0; count < responseData.length; count++)
				{

					html += '<li class="list-group-item text-muted" style="cursor:pointer"><i class="fas fa-history mr-3"></i><span onclick="get_text(this)">'+responseData[count].search_query+'</span> <i class="far fa-trash-alt float-right mt-1" onclick="delete_search_history('+responseData[count].id+')"></i></li>';

				}

				html += '</ul>';

				document.getElementById('search_result').innerHTML = html;

			}

		});

	}
}

function get_text(event) {
	var string = event.textContent;

	//fetch api

	fetch("process_data.php", {

		method:"POST",

		body: JSON.stringify({
			search_query : string
		}),

		headers : {
			"Content-type" : "application/json; charset=UTF-8"
		}
	}).then(function(response){

		return response.json();

	}).then(function(responseData){

		document.getElementsByName('search_box')[0].value = string;
	
		document.getElementById('search_result').innerHTML = '';

	});

	

}

function load_data(query){
	if(query.length > 2)
	{
		var form_data = new FormData();
		form_data.append('query', query);
		var ajax_request = new XMLHttpRequest();
		ajax_request.open('POST', 'process_data.php');
		ajax_request.send(form_data);
		ajax_request.onreadystatechange = function()
		{
			if(ajax_request.readyState == 4 && ajax_request.status == 200)
			{
				var response = JSON.parse(ajax_request.responseText);

				var html = '<div class="list-group">';

				if(response.length > 0)
				{
					for(var count = 0; count < response.length; count++)
					{
						html += '<a href="#" class="list-group-item list-group-item-action" onclick="get_text(this)">'+response[count].post_title+'</a>';
					}
				}
				else
				{
					html += '<a href="#" class="list-group-item list-group-item-action disabled">No Data Found</a>';
				}

				html += '</div>';

				document.getElementById('search_result').innerHTML = html;
			}
		}
	}
	else
	{
		document.getElementById('search_result').innerHTML = '';
	}
}
