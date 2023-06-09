$(document).ready(function(){

	console.log('appload.js');

	$(document).on('click', '.loadsettingspage', loadSettingsPage);
	$(document).on('click', '.loadHomepage', loadHomepage);
	$(document).on('click', '.loadInvoicePage', loadInvoicePage);

});

loadSettingsPage = () => {

	console.log('loadSettingsPage');
	document.location.href = 'loadSettingsPage';

}

loadHomepage = () => {

	console.log('loadHomepage');
	document.location.href = 'home';

}

loadInvoicePage = () => {

	console.log('loadInvoicePage');
	document.location.href = '/invoice-list';

}