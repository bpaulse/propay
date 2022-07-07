$(document).ready(function(){

	console.log('woddetails');	

	toastr.options.preventDuplicates = false;

	var chunks = window.location.href.split('/');
	var wodid = chunks[chunks.length-1];
	var eventid = chunks[chunks.length-2];

	loadAthletes(eventid, wodid);

	$(document).on('click', '.backEventDetails', function(evt){
		window.location.href = '/displayEventDetails/' + eventid;
	});

	$(document).on('click', '.insertScores', function(evt){

		let athlete_id = $(this).closest('tr').attr('data-id');
		$('#wodScoreModal').modal('show');

		// populate data on modal
		populateScoringInputForm(athlete_id, wodid, eventid);

	});

	$(document).on('click', '#clearscore', clearAthleteScore);

	$(document).on('click', '#addscoreform', function(evt){

		evt.preventDefault();

		let athlete_id = $('#athlete_id').val();
		let wod_id = $('#wod_id').val();

		console.log('addscoreform');

		if ( $('#submittype').val() === 'roundsfortime') {

			let secondes = $('#secondes').val();
			let minutes = $('#minutes').val();
			let update = true;
			let ajaxData = {};

			if( $('#secondes').val().trim() != '' && $('#minutes').val().trim() != '' ) {
				

				let stringToSave = minutes + ':' + secondes;

				ajaxData = {
					athlete_id: athlete_id,
					wod_id: wod_id,
					score: stringToSave
				};

			} else if( $('#secondes').val().trim() == '' && $('#minutes').val().trim() == '' ) {

				ajaxData = {
					athlete_id: athlete_id,
					wod_id: wod_id,
					score: null
				};

				toastr.warning('Athlete\'s score has been deleted...');

			} else {

				update = false;
				toastr.warning('Please ensure to complete all the fields...');

			}

			if ( update ) {

				$.ajax({
					type: 'GET',
					url: '/saveInputScoring',
					data: ajaxData,
					dataType: 'json',
					contentType: false,
					success: function(response) {

						$('#wodScoreModal').modal('hide');

						console.log(response.data.score);
						console.log(response.data.athlete_id);

						$("#" + response.data.athlete_id + " th:nth-child(5)").text(response.data.score);
						var img = '<img src="/images/entered.png" style="width: 30px;" />';
						$("#" + response.data.athlete_id + " th:nth-child(4)").html(img);

					}

				});
			}
		} else {
			
			console.log('submit');
			console.log($('#submittype').val());
			console.log($('#wodvalue').val());

			var ajaxData = {
				wodvalue: $('#wodvalue').val(),
				submittype: $('#submittype').val(),
				athleteid: athlete_id,
				wodid: wod_id 
			};

			console.log(ajaxData);

			$.ajax({
				type: 'GET',
				url: '/saveValueScoring',
				data: ajaxData,
				dataType: 'json',
				contentType: false,
				success: function(response) {

					console.log('saveValueScoring');
					console.log(response);

					console.log(response.data.score);

					$('#wodScoreModal').modal('hide');
					$("#" + response.data.athlete_id + " th:nth-child(5)").text(response.data.score);
					var img = '<img src="/images/entered.png" style="width: 30px;" />';
					$("#" + response.data.athlete_id + " th:nth-child(4)").html(img);

				}
			});

		}

	});

	$(document).on('keyup', '#secondes', function(evt){

		$('#addscoreform').prop('disabled', false);

		if ( $(this).val().length != 0 ) { 

			$(this).val($(this).val().replace(/[^\d]/,''));

			let inputStr = $(this).val()

			if ( $(this).val().length != 0 ) {

				if ( $(this).val().length <= 2 ) {
					// $(this).val($(this).val().replace(/[^\d]/,''));
					if ( $(this).val() > 60 ) {
						$(this).val($(this).val().slice(0, -1));
					}
				} else {
					$(this).val($(this).val().slice(0, -1));
					// $(this).val($(this).val().replace(/[^\d]/,''));
				}

				// check if minutes are valid
				if ( $('#minutes').val() > 0 ) {
					console.log('minutes are valid');
					$('#addscoreform').prop('disabled', false);
				} else {
					console.log('minutes are not valid');
					$('#addscoreform').prop('disabled', true);
				}

			}

		} else {
			$('#addscoreform').prop('disabled', false);
		}

	});

	$("#athlete_search").keyup(function() {

		var wod_id = wodid;
		var event_id = eventid;
		var dInput = $(this).val();
		var ajaxData = { eventid: event_id, wodid: wodid, searchterm: dInput };

		$.ajax({
			type: 'GET',
			url: '/searchAthlete',
			data: ajaxData,
			dataType: 'json',
			contentType: false,
			success: function(response) {

				$('#athleteData').empty();

				// populate list
				var output = '';
				$.each(response.data, function(data1,data2){

					var row = athleteRow(
						{
							'id': data2.athlete_id, 
							'name': data2.Name + ' ' + data2.Surname, 
							'category': data2.athleteDivision, 
							'gender': data2.gender,
							'score': scoreColumnData(data2.score)
						}
					);
					output += row;
				});

				$('#athleteData').html(output);

			}
		})

	});

	// wodScoreModal

	$(document).on('click', '.closeWODScoreModal', function(evt){
		$(this).find('form').trigger('reset');
		$('#wodScoreModal').modal('hide');
	});

	$('#wodScoreModal').on('hidden.bs.modal', function () {
		console.log('hard reset');
		// $(this).find('form').trigger('reset');
	});

});

function clearAthleteScore(evt){
	evt.preventDefault();
	console.log('clearscore');
	$('#modvalue').val('00');
	$('#minutes').val('00');
	$('#secondes').val('00');
}

function populateScoringInputForm (athlete_id, wod_id, event_id) {

	var ajaxData = { athlete_id: athlete_id, wod_id: wod_id, event_id: event_id };

	$.ajax({
		type: 'GET',
		url: '/populateScoringInputForm',
		data: ajaxData,
		dataType: 'json',
		success: function(result) {

			let athleteInfo = result[0];

			$('#addscoreform').prop('disabled', false);

			$('#athleteName').text('');
			$('#athleteName').text(athleteInfo.Name + ' ' + athleteInfo.Surname);
			$('#woddescinfo').html('');
			$('#woddescinfo').html(athleteInfo.woddesc);

			let scoreString = athleteInfo.score;

			let wod_id = athleteInfo.wod_id;
			let athlete_id = athleteInfo.athlete_id;

			$('#wod_id').val(wod_id);
			$('#athlete_id').val(athlete_id);

			if ( athleteInfo.wodtype == 1 ) {

				console.log('X');
				$('#scoringtype').html( Reps() );
				$('#wodvalue').val(scoreString);

			} else if ( athleteInfo.wodtype == 2 ) {

				$('#scoringtype').html( roundsfortime() );
				// console.log(athleteInfo.score);

				athleteScore = athleteInfo.score;

				try {

					console.log(athleteScore.search(':'));

					const myArray = scoreString.split(":");

					if ( ( typeof(myArray) === "undefined" ) || ( myArray === null ) ) {
						console.log('not defined');
					} else {
						$('#minutes').val(myArray[0]);
						$('#secondes').val(myArray[1]);
					}

				} catch (e) {
					// console.log(e);
					console.log('handle the exception');

					//disable add button
					$('#addscoreform').prop('disabled', true);

				}

			} else if ( athleteInfo.wodtype == 3 ) {
				console.log('Z');
				$('#scoringtype').html( onerm() );

				$('#wodvalue').val(scoreString);

			}

		}
	})

}

function roundsfortime () {

	let output = '';

	output = output + '<div class="form-row">';
	output = output + '<div class="form-group col-md-2">';
	output = output + '<label for="minutes">Min</label>';
	output = output + '<input type="text" class="form-control" id="minutes">';
	// output = output + '<span class="minutes-error" style="color: red; font-size: 8pt; font-family: Verdana, Geneva, Tahoma, sans-serif;">Error Message</span>';
	output = output + '</div>';
	output = output + '<div class="form-group col-md-2">';
	output = output + '<label for="secondes">Sec</label>';
	output = output + '<input type="text" class="form-control" id="secondes">';
	output = output + '<input type="hidden" class="form-control" id="submittype" value="roundsfortime">';
	output = output + '</div>';
	output = output + '</div>';

	return output;

}

function Reps() {
	let output = '';

	output = output + '<div class="form-row">';
	output = output + '<div class="form-group col-md-4">';
	output = output + '<label for="wodvalue">Reps</label>';
	output = output + '<input type="text" class="form-control" id="wodvalue">';
	output = output + '<input type="hidden" class="form-control" id="submittype" value="reps">';
	output = output + '</div>';
	output = output + '</div>';
	return output;
}

function onerm() {
	let output = '';

	output += '<div class="form-row">';
	output += '<div class="form-group col-md-4">';
	output += '<label for="wodvalue">1RM</label>';
	output += '<input type="text" class="form-control" id="wodvalue">';
	output += '<input type="hidden" class="form-control" id="submittype" value="onerm">';
	output += '</div>';
	output += '</div>';
	return output;
}

function getWODDesc(wodid) {

	$.ajax({
		type: 'GET',
		url: '/getWODDesc',
		data: {wodid: wodid},
		dataType: 'json',
		success: function(wod) {

			$('#woddescinfo').html(wod.woddesc);

			// 1 - AMRAP 
			// 2 - For Time 
			// 3 - 1 RM

			// get the name of the athlete

			console.log('type');
			console.log(wod.wodtype);

			if ( wod.wodtype == 1 ) {
				$('#scoringtype').html( roundsfortime() );
			} else if ( wod.wodtype == 2 ) {
				$('#scoringtype').html( Reps() );
			} else if ( wod.wodtype == 3 ) {
				$('#scoringtype').html( onerm() );
			}

		}
	})

}

function loadAthletes(eventid, wodid) {

	$.ajax({
		type: 'GET',
		url: '/getAthletesForEvent',
		data: {eventid: eventid, wodid: wodid},
		dataType: 'json',
		contentType: false,
		success: function (response) {

			var output = '';
			$.each(response, function(data1,data2){

				var row = athleteRow({
					'id': data2.athlete_id, 
					'name': data2.Name + ' ' + data2.Surname, 
					'category': data2.athleteDivision, 
					'gender': data2.gender,
					'score': scoreColumnData(data2.score)
				});
				
				output += row;
			});

			$('#athleteData').html(output);
		},
		error: function(e) {
			console.log(e);
		}
	});
}

function scoreColumnData (score) {

	let scoreStatus = '';
	// let score;

	if ( typeof(score) === "undefined" ) {
		scoreStatus = 'score.png';
		score = '------'
	} else if( score === null ) {
		scoreStatus = 'score.png';
		score = '------'
	} else {
		scoreStatus = 'entered.png';
		score = score;
	}

	return {score: score, image: scoreStatus};
}

function athleteRow (athlete) {
	return '<tr data-id="'+ athlete.id +'" id="'+ athlete.id +'"><th>' + athlete.name + '</th><th>' + athlete.category + '</th><th>' + athlete.gender  + '</th><th>' + scoreStatus(athlete.score) + '</th><th>' + athlete.score.score + '</th><th>' + editAndSaveButtons(athlete.score.score) + '</th></tr>';
}

function scoreStatus(output){
	return '<img  src="/images/'+output.image+'" style="width: 35px;" />';
}

function athleteRowNoData() {
	return '<tr id="nodata"><th colspan="4" style="text-align: center; color: blue;">No ATHLETES loaded...</th></tr>';
}

function editAndSaveButtons(score) {
	return editButton (score);
}

function clickThroughButton () {
	return '<button class="btn btn-success wodDetails">' + '<i class="icon-chevron-right"></i>' + '</button>';
}

function deleteButton() {
	return '<button class="btn btn-danger deleteInvoice">' + '<i class="icon-trash"></i>' + '</button>';
}

function editButton (score) {
	return '<button class="btn btn-info insertScores" data-scores="'+score+'"><i class="icon-pencil"></i></button>';
}

function scoreBoardButton() {
	return '<button class="btn btn-info scoreboard">' + '<i class="icon-trash"></i>' + '</button>';
}