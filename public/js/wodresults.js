$(document).ready(function(){

	toastr.options.preventDuplicates = false;

	var chunks = window.location.href.split('/');
	var eventid = chunks[chunks.length-2];
	var wodid = chunks[chunks.length-1];

	// add li tabs
	populateUL(eventid, wodid);

	$(document).on('click', '.backWodList', function(evt){
		window.location.href = '/displayEventDetails/' + eventid;
	});

});

function populateUL(eventid, wodid) {
	getDivision(eventid, wodid);
}

function BuildInitialTabs (data) {

	var counter = 0;
	$.each(data, function(data1,gender){

		for (let i = 0; i < gender.length; i++) {
			console.log(gender[i]);
			if ( counter == 0 ){
				$(".nav-tabs").append(tabHTML('active', gender[i]));
				$(".tab-content").append(contentHTML('active', gender[i]));
			} else {
				$(".nav-tabs").append(tabHTML('', gender[i]));
				$(".tab-content").append(contentHTML('', gender[i]));
			}
			counter++;
		}
	});

}

function getDivision(eventid, wodid) {

	$.ajax({
		type: 'GET',
		url: '/getAllDivisions',
		data: {eventid: eventid, wodid: wodid},
		dataType: 'json',
		contentType: false,
		success: function (response) {

			console.log(response);

			let eventData = response.allDivisions.eventData;

			BuildInitialTabs(eventData.data);
			buildLeaderBoards(response);

		},
		error: function(e) {
			console.log(e);
		}

	});

}

function buildLeaderBoards(response) {

	var data = response.allDivisions.leaderboard;


	var wods = data[response.allDivisions.eventData.static.eventid];
	var overallData = response.overallLeaderBoard;
	// var divId;

	console.log(response.allDivisions.wodnames[69]);

	$.each(wods, function(wodid,wod){
		// console.log(wodid);
		$.each(wod, function(gender,athletetypeArray){
			$.each(athletetypeArray, function(athletetype, participants){

				let row = '';
				for ( var j = 0; j < participants.length; j++ ) {
					row += singleRow(Number(j) + 1, participants[j]);
				}

				var workoutname = response.allDivisions.wodnames[wodid];

				$('#' + athletetype + '-' + gender + '-content').append(createTable(row, '', workoutname));

			});
		});
	});

	// console.log(overallData);

	$.each(overallData, function(gender, genderData){
		$.each(genderData, function(athletetype, athletetypeData){
			addOverallLeaderBoard( response.allDivisions.eventData.static.eventid, gender, athletetype, athletetypeData );
		});
	});

}

function addOverallLeaderBoard (eventid, gender, athletetype, divId) {

	var ajaxData = {
		eventid: eventid,
		gender: gender, 
		athletetype: athletetype, 
		divId: divId
	};

	$.ajax({
		type: 'GET',
		url: '/getOverallStandings',
		data: ajaxData,
		dataType: 'json',
		success: function (response) {

			// console.log(response);

			var rows = '';

			$.each(response, function(index,data){
				rows += singleRowOverall(index, data);
			});

			var workoutname = 'Standings';

			$('#' + athletetype + '-' + gender + '-content').append(createTable(rows, 'final', workoutname));

		},
		error: function(e) {
			console.log(e);
		}
	});

}

function singleRow(index, board) {
	return '<tr><td>'+index+'</td><td>'+board.AthleteName+'</td><td style="text-align: right;">'+board.score+'</td></tr>';
}

function singleRowOverall(index, board) {
	var num = Number(index)+1;
	return '<tr><td>'+num+'</td><td>'+board.athletename+'</td><td style="text-align: right;">'+board.totalscore+'</td></tr>';
}

function tabHTML(classStr, data){

	var ariaControl = 'false';
	if ( classStr == 'active' ) {
		ariaControl = 'true';
	}

	let html = '<li class="nav-item">';
	html += '<a class="nav-link '+classStr+'" id="'+data.id+'-tab" data-toggle="tab" href="#'+data.id+'" role="tab" aria-controls="'+data.id+'" aria-selected="'+ariaControl+'">'
	html += data.name;
	html += '</a>'
	html += '</li>';

	return html;
}

function contentHTML(classStr, data){

	var html = '<div class="tab-pane fade show '+classStr+'" id="'+data.id+'" role="tabpanel" aria-labelledby="'+data.id+'-tab">';
	html += '<h2>'+data.name+'</h2>';
	html += '<div id="'+data.dvId+'-'+data.gender+'-content">';
	html += '</div>';
	html += '</div>';
	return html;
}

function createTable (tablecontent, type, workoutname) {
	let overall = '';
	let borderline = '1px';
	if ( type == 'final') {
		overall = 'background-color: grey; color: white;';
		borderline = '0px';
	}
	return '<div style="float: left; padding: 10px; border-right: '+borderline+' solid black;"><h5 style="font-size: 14px;">' + workoutname + '</h5><table border="1" style="width: 140px; font-size: 8pt; ' + overall +'">'+TableHeader()+tablecontent+'</table></div>';
}

function TableHeader () {
	return '<tr><td style="width: 8%;">#</td>	<td style="width: 80%;">Name</td><td style="width: 12%;">Score</td></tr>';
}