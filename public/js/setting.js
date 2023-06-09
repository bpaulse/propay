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
				console.log(previewImage);
				$('#file-preview').empty().append(previewImage);
				console.log('render file');
			};

			reader.readAsDataURL(file);
		} else {
			$('#file-preview').empty();
		}

	});

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

	// $("#file-preview").attr("src", 'clientLogos/'+data.detail.logo);

	
	let attributes  = {
		'src': 'clientLogos/'+data.detail.logo,
		''
	}
	
	let previewImage = $('<img>').attr();
	$('#file-preview').empty().append(previewImage);
	
	// $("input:file").val('clientLogos/'+data.detail.logo);

}

