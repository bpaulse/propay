$(document).ready(function(){

	console.log('settings.js');
	$(document).on('click', '.loadsettingspage', loadSettingsPage);
	$("#user_dateofbirth").datepicker({dateFormat: "yy-mm-dd"});

	// get details data
	getUserDetailsInfo();

	// 
	$('#user_logo').change(function(evt) {
		// var fileName = $(this).val().split('\\').pop(); // Extract the file name from the input
		// $('#custom-file-input').val(fileName);

		console.log('change image');

		let file = evt.target.files[0];

		console.log('file');
		console.log(file);

		if ( file ) {

			console.log('getting file');

			var reader = new FileReader();

			reader.onload = function(e) {
				console.log(e.target.result);
				let previewImage = $('<img>').attr('src', e.target.result);
				// console.log(previewImage);
				let styles = { 'width': '150px' };
				previewImage.css(styles);		
				$('#file-preview').empty().append(previewImage);
				console.log('render file');
			};

			reader.readAsDataURL(file);
		} else {
			$('#file-preview').empty();
		}

	});

	$('#userForm').submit(function(evt) {
		console.log('submit form');
		
		evt.preventDefault();
		let formData = new FormData(this);
		// var formData = new FormData($('#userForm'));
		console.log(formData);

		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

		let ajaxData = {
			_token: '{{ csrf_token() }}',
			'user_firstname': $('#user_firstname').val(),
			'user_surname': $('#user_surname').val(),
			'user_mobile': $('#user_mobile').val(),
			'user_email': $('#user_email').val(),
			'user_idnumber': $('#user_idnumber').val(),
			'user_dateofbirth': $('#user_dateofbirth').val(),
			'user_vat': $('#user_vat').val()
		};

		$.ajax({
			url: '/saveUser',
			method: 'POST',
			data: ajaxData,
			success: function(response) {
				alert('Form data saved successfully.');
				// Perform any additional actions or redirect as needed
			},
			error: function(xhr, status, error) {
				console.log(status);
				console.log(error);
				alert('An error occurred while saving the form data.');
				// Handle error scenario
			}
		});

	});

	// save user details
	// $(document).on('click', '.saveUserDetails', function(evt) {

	// 	evt.preventDefault();
	// 	console.log('saveUserDetails');

	// 	let formData = new FormData(this);
	// 	// var formData = new FormData($('#userForm'));

	// 	console.log(formData);

	// 	$.ajax({
	// 		url: '/saveUser',
	// 		method: 'POST',
	// 		data: formData,
	// 		contentType: false,
	// 		processData: false,
	// 		success: function(response) {
	// 			alert('Form data saved successfully.');
	// 			// Perform any additional actions or redirect as needed
	// 		},	
	// 		error: function(xhr, status, error) {
	// 			console.log(status);
	// 			console.log(error);
	// 			alert('An error occurred while saving the form data.');
	// 			// Handle error scenario
	// 		}
	// 	});
	// });

});

function getUserDetailsInfo() {
	
	console.log('getUserDetailsInfo');
	
	$.ajax({
		url: "/getUserDetailsInfo",
		data: {},
		cache: false,
		type: "GET",
		success: function(response) {
			populateFields(response);
		},
		error: function(xhr) {
			console.log(xhr);
		}
	});
}

loadSettingsPage = () => {

	console.log('loadSettingsPage');
	return 'loadSettingsPage';

}

function populateFields(data) {

	console.log(data);

	$('#user_firstname').val(data.detail.Name);
	$('#user_surname').val(data.detail.Surname);
	$('#user_mobile').val(data.detail.Mobile);
	$('#user_email').val(data.detail.Email);
	$('#user_idnumber').val(data.detail.Idnumber);
	$('#user_dateofbirth').val(data.detail.DateOfBirth);
	$('#user_vat').val(data.detail.vat);
	$('#user_companyname').val(data.detail.companyname);
	$('#user_companyreg').val(data.detail.companyreg);
	$('#user_address').val(data.detail.address);
	$('#user_banking').val(data.detail.BankingDetails);

	
	let attributes  = { 'src': 'clientLogos/'+data.detail.logo }
	let styles = { 'width': '150px' }
	let previewImage = $('<img>').attr(attributes);
	previewImage.css(styles);
	$('#file-preview').empty().append(previewImage);

	// disable submit button 
	// $('.saveClient').attr('disabled', true);

}

