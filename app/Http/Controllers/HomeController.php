<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Person;
use App\Models\AppSetting;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct() {
		$this->middleware('auth');
	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Contracts\Support\Renderable
	 */
	public function index(){
		return view('home');
	}

	public function person() {
		return view('person');
	}

	public function getPeopleList (Request $request) {

		$people = DB::select('SELECT 
				peo.id, 
				peo.user_id, 
				det.Name, 
				det.Surname, 
				det.Mobile, 
				det.Email, 
				det.DateOfBirth, 
				det.Idnumber,
				det.id as detailId 
			FROM people as peo 
			INNER JOIN details AS det on peo.id = det.person_id 
			WHERE 
				peo.user_id = ? AND peo.state = ?', 
			[Auth::user()->id, 1]
		);

		$output = [];

		foreach ($people as $person) {
			// var_dump($person);
			$output[] = [
				'person'	=> $person,
				'interests'	=> $this->getPersonInterests($person->detailId),
				'language'	=> $this->getPersonLanguage($person->detailId)
			];
		}

		return response()->json($output);

	}

	public function getPersonLanguage($detailId) {
		$lang = DB::select('SELECT aps.id, aps.Name
		FROM details as det
		INNER JOIN languages as lng ON lng.detail_id = det.id
		INNER JOIN app_settings as aps ON lng.app_setting_id = aps.id
		WHERE det.id = ?', [$detailId]);
		// var_dump($lang);
		return $lang;
	}

	public function getPersonInterests($detailId) {
		$interests = DB::select('SELECT aps.id, aps.Name 
		FROM details as det
		INNER JOIN interests as intr ON intr.detail_id = det.id
		INNER JOIN app_settings as aps ON aps.id = intr.app_setting_id
		WHERE det.id = ?', [$detailId]);
		return $interests;
	}

	public function deletePersonLine(Request $request) {
		
		$person_id = $request->input('id');
		$update = Person::where('id', $person_id)->update(array('state' => 0));

		if ( $update ) {
			$return = ['code' => 1, 'msg' => 'Person deleted successfully!'];
		} else {
			$return = ['code' => 0, 'msg' => 'Error deleting Client'];
		}

		return response()->json($return);
	}

	public function getAppSettings(Request $request) {

		$id = $request->input('person_id');
		$thisPerson = Person::find($id);

		$personInfo = ['person' => $this->getPerson($request->input('person_id')), 'interests' => $this->getPersonInterests($thisPerson->detail->id)];


		$lang = AppSetting::where('description', 'Language')->get();
		$interest = AppSetting::where('description', 'Interests')->get();
		return response()->json(['lang' => $lang, 'interest' => $interest, 'personInfo' => $personInfo]);

	}

	public function getPerson ($person_id) {

		$person = DB::select('SELECT 
				peo.id, 
				peo.user_id, 
				det.Name, 
				det.Surname, 
				det.Mobile, 
				det.Email, 
				det.DateOfBirth, 
				det.Idnumber,
				det.id as `detailId`,
				aps.Name as `languageName`,
				lng.app_setting_id as `languageId`
			FROM people as peo 
			INNER JOIN details AS det ON peo.id = det.person_id
			LEFT JOIN languages AS lng ON lng.detail_id = det.id
			LEFT JOIN app_settings AS aps ON aps.id = lng.app_setting_id
			WHERE 
				peo.id = ?', 
			[$person_id]
		);

		return $person;

	}

	public function buildMultiselect () {
		$interest = AppSetting::where('description', 'Interests')->get();
		return response()->json($interest);
	}

	public function events () {
		return view('events');
	}

	public function loadSettingsPage () {
		return view('settings');
	}

	public function home () {
		return view('home');
	}

	public function sumOfHourGlass() {
		
	}

	public function sendMailForUser () {

		$details = [
			'title' => 'Mail from ItSolutionStuff.com',
			'body' => 'This is for testing email using smtp'
		];
	
		\Mail::to('bevanpaulse@gmail.com')->send(new \App\Mail\MyTestMail($details));
		dd("Email is Sent.");
	
	}

}
