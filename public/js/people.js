$(document).ready(function(){

	console.log('People');

	toastr.options.preventDuplicates = false;

	loadPeople();

	$(document).on('click', '.editPerson', editPerson);
	$(document).on('click', '.deletePerson', deletePerson);

	$(document).on('click', '.closeEditModal', closeEditModal);
	$(document).on('click', '.closeDeleteModal', closeDeleteModal);


});

function closeEditModal () {
	$("#editPersonModal").modal("hide"); 
}

function closeDeleteModal () {
	$("#deletePersonModal").modal("hide");
	// closeDeleteModal
}

function editPerson(evt){

	$('#editPersonModal').modal("show");
	let person_id = $(this).closest('tr').attr('data-id');
	// $('#person_id').val(person_id);
	console.log(person_id);
	// populateEventEditForm(event_id);

}

function deletePerson(evt){

	console.log('deleteperson');
	$('#deletePersonModal').modal("show");
	let person_id = $(this).closest('tr').attr('data-id');
	console.log(person_id);
	$('#person_id').val(person_id);
	// deletePersonRow(person_id);

}

function deletePersonRow(personid) {

	var ajaxData = { id: personid };
	console.log(ajaxData);
	$.ajax({

		method: 'get',
		url: '/deletePersonLine',
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

				var row = peopleRow({'id': response.data.id, 'event_name': response.data.name, 'event_desc': response.data.desc, 'event_loc': response.data.loc});

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

function loadPeople() {
	$.ajax({
		type: 'GET',
		url: '/getPeopleList',
		data: '',
		processData: false,
		dataType: 'json',
		contentType: false,
		success: function(response){

			var output = '';
			$.each(response, function(data1,data2){
				// person_id <= id
				console.log(data2);
				var row = peopleRow(
					{
						'id': data2.id, 
						'DateOfBirth': data2.DateOfBirth, 
						'Email': data2.Email, 
						'Idnumber': data2.Idnumber, 
						'Mobile': data2.Mobile,
						'Name': data2.Name,
						'Surname': data2.Surname,
						'user_id': data2.user_id
					}
				);
				output += row;

			});

			$('#personTableData').html(output);
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

function peopleRow (person) {
	var output = '<tr data-id="'+ person.id +'">';
	output += '<th>' + person.Name + ' ' + person.Surname + '</th>';
	output += '<th>' + person.Mobile + '</th>';
	output += '<th>' + person.Email  + '</th>';
	output += '<th>' + person.Idnumber  + '</th>';
	output += '<th>' + person.DateOfBirth  + '</th>';
	output += '<th>' + person.user_id  + '</th>';
	output += '<th>' + person.user_id  + '</th>';
	output += '<th>' + editAndSaveButtons() + '</th>';
	output += '</tr>';
	return output;
}

function editAndSaveButtons() {

	// const eventDateObj = new Date(event_date);
	// const currentDateObj = new Date();
	var output = '<button class="btn btn-info editPerson">Edit</button>' + '&nbsp;'
	output += '<button class="btn btn-danger deletePerson">' + 'Delete' + '</button>';
	return output;

}

function myFunc(e){
	var event_id = $(this).closest('tr').attr("data-id");
	window.location.href = '/displayEventDetails/' + event_id;
}