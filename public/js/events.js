$(document).ready(function(){

	console.log('events');

	toastr.options.preventDuplicates = false;

	loadEvents();

	$(document).on('click', '.backToHome', function(){
		window.location.href = '/home';
	});

	$(document).on('click', '.eventDetail', myFunc);

	$('#add-event-form').on('submit', function(e){

		e.preventDefault();

		var doAjax = true;
		if ( doAjax ) {

			var form = this;

			$.ajax({
				type: $(form).attr('method'),
				url: $(form).attr('action'),
				headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
				data: new FormData(form),
				processData: false,
				dataType: "json",
				contentType: false,
				beforeSend: function() {
						$(form).find('span.error-text').text('');
				},
				success: function (response) {

					if ( response.code === 0 ) {
						// $.each(response.error, function(prefix, val){
						// 	$(form).find('span.'+prefix+'_error').text(val[0]);
						// });
					} else {

						console.log('success');
						$(form)[0].reset();
						toastr.success(response.msg);

						console.log(response.data);
						var event = response.data;
						console.log(event);

						var row = eventRow({'id': event.id, 'event_name': event.name, 'event_desc': event.desc, 'event_loc': event.loc});
						$('#eventsData').append(row);
					}
				}
			});

		}

	});

	$(document).on('click', '#addEventForm', addEventFrom);

	$(document).on('click', '.addEventButton', submitEvent);

	$(document).on('click', '.deleteEvent', function(evt){
		let eventid = $(this).closest('tr').attr('data-id');
		$('#deleteEventModal').modal('show');
		$('#event_id').val(eventid);
	});

	$(document).on('click', '.deleteEventButton', function(evt){

		let event_id = $('#event_id').val();
		changeEventStatus(event_id);
	});

	$(document).on('click', '#editEvent', function(evt){

		$('#editEventModal').modal("show");

		let eventid = $(this).closest('tr').attr('data-id');
		populateEventEditForm(eventid);

	});

	$(document).on('click', '.updateEvent', function(evt) {

		let ajaxData = {
			event_id: $('#event_id').val(),
			event_name: $('#event_name').val(),
			event_desc: $('#event_desc').val(),
			event_loc: $('#event_loc').val(),
			event_mod_date: $('#event_mod_date').val()
		}

		// console.log(ajaxData);

		$.ajax({
			type: 'GET',
			url: '/updateEventData',
			data: ajaxData,
			dataType: 'json',
			success: function (data) {

				// console.log(data.update);

				if ( data.update ) {
					toastr.success('Event Updated successfully...');
					$('#editEventModal').modal("hide");

					// update rows
					// let eventid = $(this).closest('tr').attr('data-id');
					console.log('event_id');
					console.log(data.event_id);
					$('tr[data-id="' + data.event_id + '"] th:nth-child(1)').text(data.eventData.event_name);
					$('tr[data-id="' + data.event_id + '"] th:nth-child(2)').text(data.eventData.event_desc);
					$('tr[data-id="' + data.event_id + '"] th:nth-child(3)').text(data.eventData.event_location);

				} else {
					toastr.warning('Unable to update Event with ID ' + data.event_id);
				}

			},
			error: function(e) {
				console.log(e);
			}
		});


	});

	$('#editEventModal').on('hidden.bs.modal', function () {
		console.log('modal closing');
		emptyEventDataForm();
	});

	$('#addEventForm').css('cursor', 'pointer');

	$('#event_add_date').click(function(){
		$('#event_add_date').datepicker({
			dateFormat:'yy-mm-dd',
			changeMonth:true,
			changeYear:true,
			showOn: "focus"
		});
		$('#event_add_date').datepicker('show');
	});

	$(document).on('keyup change', '#event_add_name', function(){
		$('.event_add_name_error').text('');
		if ( $.trim($('#event_add_name').val()) == '' ){
			$('.event_add_name_error').text('Please add the Name of the Event');
		}
	});

	$(document).on('keyup change', '#event_add_desc', function(){
		$('.event_add_desc_error').text('');
		if ( $.trim($('#event_add_desc').val()) == '' ){
			$('.event_add_desc_error').text('Please add the Description of the Event');
		}
	});

	$(document).on('keyup change', '#event_add_location', function(){
		$('.event_add_location_error').text('');
		if ( $.trim($('#event_add_location').val()) == '' ){
			$('.event_add_location_error').text('Please add the Location of the Event');
		}
	});

	$(document).on('keyup change', '#event_add_date', function(){
		$('.event_add_date_error').text('');
		if ( $.trim($('#event_add_date').val()) == '' ){
			$('.event_add_date_error').text('Please add the date of the Event');
		}
	});

});

function changeEventStatus(event_id) {

	var ajaxData = {event_id: event_id};
	var proceed = true;

	if ( proceed ) {
		$.ajax({
			type: 'GET',
			url: '/changeEventStatus',
			data: ajaxData,
			dataType: 'json',
			success: function(response){

				$('#deleteEventModal').modal('hide');
				toastr.success('Event Deleted...');
				$('tr[data-id="'+response.event_id+'"]').remove();

			},
			error: function(e){
				console.log(e);
			}
		});
	}

}

function submitEvent() {

	console.log('submitEvent');

	var ajaxData = {
		event_name: $('#event_add_name').val(),
		event_desc: $('#event_add_desc').val(),
		event_location: $('#event_add_location').val(),
		event_date: $('#event_add_date').val()
	};

	var proceed = true;

	if ( $.trim($('#event_add_name').val()) == '' ) {
		$('.event_add_name_error').text("Please add the Name of the Event");
		proceed = false;
	}

	if ( $.trim($('#event_add_desc').val()) == '' ) {
		$('.event_add_desc_error').text("Please add the Name of the Event");
		proceed = false;
	}

	if ( $.trim($('#event_add_location').val()) == '' ) {
		$('.event_add_location_error').text("Please add the Location of the Event");
		proceed = false;
	}

	if ( $.trim($('#event_add_date').val()) == '' ) {
		$('.event_add_date_error').text("Please add the Date of the Event");
		proceed = false;
	}

	if ( proceed ) {
		$.ajax({
			type: 'GET',
			url: '/addEvent',
			data: ajaxData,
			dataType: 'json',
			success: function(response){

				$('event_add_name').val('')
				$('event_add_desc').val('')
				$('event_add_location').val('')
				$('event_add_date').val('')

				var row = eventRow({'id': response.data.id, 'event_name': response.data.name, 'event_desc': response.data.desc, 'event_loc': response.data.loc});

				$('#addEventModal').modal('hide');
				$('#eventsData').append(row);

			},
			error: function(e){
				console.log(e);
			}
		});
	}

}

function addEventFrom() {
	$('#addEventModal').modal('show');
}

function loadEvents() {
	$.ajax({
		type: 'GET',
		url: '/getEventsList',
		data: '',
		processData: false,
		dataType: 'json',
		contentType: false,
		success: function(response){
			var output = '';
			$.each(response.details, function(data1,data2){
				var row = eventRow({'id': data2.id, 'event_name': data2.event_name, 'event_desc': data2.event_desc, 'event_loc': data2.event_location, 'event_date': data2.event_date});
				output += row;
			});
			$('#eventsData').html(output);
		},
		error: function(e){
			console.log(e);
		}
	});
}

function emptyEventDataForm() {
	$('#event_name').val('');
	$('#event_desc').val('');
	$('#event_loc').val('');
	$('#event_mod_date').val('');
}

function populateEventEditForm(eventid){
	console.log(eventid);

	$.ajax({
		type: 'GET',
		url: '/getEventDetails',
		data: {eventid: eventid},
		dataType: 'json',
		success: function (event) {

			// console.log(event);

			$('#event_name').val(event.event_name);
			$('#event_id').val(eventid);
			$('#event_desc').val(event.event_desc);
			$('#event_loc').val(event.event_location);
			$('#event_mod_date').val(event.event_date);

		},
		error: function(e) {
			console.log(e);
		}
	});

}

function eventRow (event) {
	return '<tr data-id="'+ event.id +'"><th>' + event.event_name + '</th><th>' + event.event_desc + '</th><th>' + event.event_loc  + '</th><th>' + editAndSaveButtons(event.event_date) + '</th></tr>';
}

function editAndSaveButtons(event_date) {

	const eventDateObj = new Date(event_date);
	const currentDateObj = new Date();

	var output = '';

	if ( eventDateObj < currentDateObj ) {
		output = '<button class="btn btn-success eventDetail">' + '<i class="icon-chevron-right"></i>' + '</button>';
	} else {
		output = '<button class="btn btn-info" id="editEvent"><i class="icon-pencil"></i></button>' + '&nbsp;' + '<button class="btn btn-danger deleteEvent">' + '<i class="icon-trash"></i>' + '</button>' + '&nbsp;' + '<button class="btn btn-success eventDetail">' + '<i class="icon-chevron-right"></i>' + '</button>';
	}

	return output;

}

function myFunc(e){
	var event_id = $(this).closest('tr').attr("data-id");
	window.location.href = '/displayEventDetails/' + event_id;
}