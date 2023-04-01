$(document).ready(function(){

	console.log('People');

	buildMultiselect();

	$(document).on('click', '#popme', function(){
		console.log('beeebe');

		$('#email').val('bevanpaulse@gmail.com');
		$('#password').val('12345678');
	});

	toastr.options.preventDuplicates = false;

	loadPeople();

	$(document).on('click', '.addPersonModal', addPersonModal);

	$(document).on('click', '.editPerson', editPerson);

	$(document).on('click', '.deletePerson', deletePerson);

	$(document).on('click', '.closeEditModal', closeEditModal);
	$(document).on('click', '.closeDeleteModal', closeDeleteModal);

	$(document).on('click', '.deletePersonButton', deletePersonFunc);

	$(document).on('click', '#DateOfBirth', populateDOB);

	$(document).on('click', '.updatePerson', updatePerson);

	$(document).on('keyup', 'input', function(evt){

		if ( evt.target.value == '' ){
			$('.error-' + $(this).attr('id')).text('Please enter your ' + $(this).attr('id') + '...');
		} else {

			// console.log('target not empty');

			if ( $(this).attr('id') == 'Email' ) {

				// console.log('target is email');

				if( !IsEmail(evt.target.value) ){
					// console.log('not a valid email');
					// console.log($(this).attr('id') + ' invalid...');
					var descriptorId = 'error-' + $(this).attr('id');
					// console.log(descriptorId);
					$('.' + descriptorId).text('Your Email Address format is Incorrect...');
				}

			} else {
				$('.error-' + $(this).attr('id')).text('');
			}


		}

	});

	$('#editPersonModal').on('hidden.bs.modal', function () {
		console.log('hard reset');
		$(this).find('form').trigger('reset');
	});

	$(document).on('click', '#backtoHome', function(){
		window.location.href = '/home';
	});

});

function buildMultiselect() {

	console.log('buildMultiselect');

	var ajaxData = {};

	$.ajax({

		method: 'get',
		url: '/buildMultiselect',
		data: ajaxData,
		dataType: 'json',
		success: function(response) {

			var options = '';

			$.each(response, function(index,data2){
				options += '<option value="' + data2.id + '">' + data2.Name + '</option>';
			});

			// var interestSelect = '<label class="col-2 col-form-label" for="animals">Interests</label>';
			// interestSelect += '<select multiple name="animals" id="animals" class="filter-multi-select">';
			// interestSelect += options;

			// interestSelect += '<option value="1">Bear</option>';
			// interestSelect += '<option value="2">Ant</option>';
			// interestSelect += '<option value="3">Salamander</option>';
			// interestSelect += '<option value="4">Owl</option>';
			// interestSelect += '<option value="5">Frog</option>';
			// interestSelect += '<option value="6">Shark</option>';

			// interestSelect += '</select>';
			$('#animals').append(options);

		}

	});

}

$(function () {
	// Apply the plugin 
	var notifications = $('#notifications');
	$('#animals').on("optionselected", function(e) {
		createNotification("selected", e.detail.label);
	});
	$('#animals').on("optiondeselected", function(e) {
		createNotification("deselected", e.detail.label);
	});
	function createNotification(event,label) {
	  var n = $(document.createElement('span'))
		.text(event + ' ' + label + "  ")
		.addClass('notification')
		.appendTo(notifications)
		.fadeOut(3000, function() {
		  n.remove();
		});
	}
});

function addPersonModal() {
	console.log('addPersonModal');
}

function updatePerson () {

	var proceed = false;

	if ( $('#Name').val() == '' ) {
		$('.error-Name').text('Please enter your Name...');
		proceed = false;
	}
	
	if ( $('#Name').val() == '' ) {
		$('.error-Name').text('Please enter your Name...');
		proceed = false;
	}
	if ( $('#Surname').val() == '' ) {
		$('.error-Surname').text('Please enter your Surname...');
		proceed = false;
	}
	if ( $('#Email').val() == '' ) {
		$('.error-Email').text('Please enter your Email...');
		proceed = false;
	} else {
		console.log('email not empty');
		if( !IsEmail($('#Email').val()) ){
			console.log('email not valid');
			$('.error-Email').text('Your Email Address format is Incorrect...');
			proceed = false;
		}
	}


	if ( $('#Mobile').val() == '' ) {
		$('.error-Mobile').text('Please enter your Mobile...');
		proceed = false;
	}
	if ( $('#Idnumber').val() == '' ) {
		$('.error-Idnumber').text('Please enter your Idnumber...');
		proceed = false;
	}
	if ( $('#DateOfBirth').val() == '' ) {
		$('.error-DateOfBirth').text('Please enter your DateOfBirth...');
		proceed = false;
	}


	var ajaxData = {
		Name: $('#Name').val(),
		Surname: $('#Surname').val(),
		Email: $('#Email').val(),
		Mobile: $('#Mobile').val(),
		Idnumber: $('#Idnumber').val(),
		DateOfBirth: $('#DateOfBirth').val(),
		Language: $('#Language').val(),
		Interest: $('#Interest').val()
	}
	console.log(ajaxData);

	if ( proceed ) {
		$.ajax({

			method: 'get',
			url: '/updatePersonRow',
			data: ajaxData,
			dataType: 'json',
			success: function(response) {
				console.log(response);
			},
			error: function(e) {
				console.log(e);
			}
		});
	}

}

function IsEmail(email) {
	var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if (!regex.test(email)) {
		return false;
	} else {
		return true;
	}
}

function populateDOB(){
	$('#DateOfBirth').datepicker({
		dateFormat:'yy-mm-dd',
		changeMonth:true,
		changeYear:true,
		showOn: "focus",
		minDate: new Date(1999, 10 - 1, 25)
	});
	$('#DateOfBirth').datepicker('show');
}

function closeEditModal () {
	$("#editPersonModal").modal("hide"); 
}

function closeDeleteModal () {
	$("#deletePersonModal").modal("hide");
	// closeDeleteModal
}

function editPerson(evt){

	// console.log('edit person');
	$('#editPersonModal').modal("show");
	let person_id = $(this).closest('tr').attr('data-id');
	// console.log(person_id);
	populatePersonEditForm(person_id);

}

function populatePersonEditForm(person_id) {
	populateDropDowns(person_id);
}

function populateDropDowns(person_id) {

	var ajaxData = {person_id: person_id};

	$.ajax({

		method: 'get',
		url: '/getAppSettings',
		data: ajaxData,
		dataType: 'json',
		success: function(response) {

			console.log(response.personInfo.person);
			populateFormData(response.personInfo.person[0]);
			$('#placeInterestDropdown').html('');
			var lang = $("#Language");
			var selectedText = '';
			var selectId = 0;

			$.each(response.lang, function(index,data2){
				lang.append(new Option(data2.Name, data2.id));
			});

			$("#Language option[value='"+response.personInfo.person[0].languageId+"']").attr("selected", "selected");

			var options = '';

		},
		error: function(e) {
			console.log(e);
		}
	});
}

function populateFormData(personData) {

	$('#Name').val(personData.Name);
	$('#Surname').val(personData.Surname);
	$('#Email').val(personData.Email);
	$('#Mobile').val(personData.Mobile);
	$('#Idnumber').val(personData.Idnumber);
	$('#DateOfBirth').val(personData.DateOfBirth);

}

function deletePerson(evt){

	$('#deletePersonModal').modal("show");
	let person_id = $(this).closest('tr').attr('data-id');
	$('#person_id').val(person_id);

}

function deletePersonFunc () {

	console.log($('#person_id').val());
	deletePersonRow($('#person_id').val());

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

			console.log(response);

			if (response.code === 1) {
				$('#deletePersonModal').modal("hide");
				toastr.success(response.msg);
				$('#' + invLineId).remove();
			} else {
				toastr.warning(response.msg);
			}

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
				var row = peopleRow(
					{
						'id': data2.person.id, 
						'DateOfBirth': data2.person.DateOfBirth, 
						'Email': data2.person.Email, 
						'Idnumber': data2.person.Idnumber, 
						'Mobile': data2.person.Mobile,
						'Name': data2.person.Name,
						'Surname': data2.person.Surname,
						'user_id': data2.person.user_id,
						'lang': (data2.language.length >= 1) ? data2.language[0].Name : "-----",
						'interests': stringifyInterests(data2.interests)
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

function stringifyInterests(interests) {
	var output = '';
	for (var j = 0; j < interests.length; j++ ) {
		if ( j == interests.length - 1 ){
			output += interests[j].Name;
		} else {
			output += interests[j].Name + '<br />';
		}
	}
	return '<span style="font-size: 12px;">' + output + '</span>';
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
	output += '<th>' + person.lang  + '</th>';
	output += '<th>' + person.interests  + '</th>';
	output += '<th>' + editAndSaveButtons() + '</th>';
	output += '</tr>';
	return output;
}

function editAndSaveButtons() {

	// const eventDateObj = new Date(event_date);
	// const currentDateObj = new Date();
	var output = '<button class="btn btn-info editPerson">Edit</button>' + '&nbsp;'
	output += '<button class="btn btn-danger deletePerson">' + 'Del' + '</button>';
	return output;

}

function myFunc(e){
	var event_id = $(this).closest('tr').attr("data-id");
	window.location.href = '/displayEventDetails/' + event_id;
}