$(document).ready(function(){

	console.log('appload.js');

	$(document).on('click', '.loadsettingspage', loadSettingsPage);
	$(document).on('click', '.loadHomepage', loadHomepage);

});

loadSettingsPage = () => {

	console.log('loadSettingsPage');
	document.location.href = 'loadSettingsPage';

}

loadHomepage = () => {

	console.log('loadHomepage');
	document.location.href = 'home';

}