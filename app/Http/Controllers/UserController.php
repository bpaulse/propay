<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Wod;
use App\Models\User;
use App\Models\Detail;
use App\Models\AthleteEvent;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\EventDetailController;

use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

	public function saveUser(Request $request) {

		var_dump('Save User Details');

		$person_id = Auth::user()->id;

		var_dump($person_id);
		// var_dump($request->user_firstname);
		// var_dump($request->user_surname);

		// exit();

		// $detail->mobile = $request->user_mobile;
		// $detail->email = $request->user_email;

		// // var_dump($detail->person_id);
		// var_dump($detail->firstname);
		// var_dump($detail->surname);
		// var_dump($detail->mobile);
		

		// $validatedData = $request->validate([
		// 	'user_firstname' => 'required',
		// 	'user_surname' => 'required',
		// 	'user_mobile' => 'required',
		// 	'user_email' => 'required|email',

		// 	// 'user_email' => 'required|email',
		// 	'user_dateofbirth' => 'required',
		// 	'user_idnumber' => 'required',
		// 	'user_vat' => 'required',
		// 	'user_companyreg' => 'required',

		// 	'user_address' => 'required',
		// 	'user_companyname' => 'required',
		// 	'user_banking' => 'required',

		// 	'user_logo' => 'required|file'
		// ]);

		$action = DB::table('details')->where('person_id', $person_id)->update(
			[
				'Name' => $request->user_firstname, 
				'Surname' => $request->user_surname,
				'Mobile' => $request->user_mobile,
				'Email' => $request->user_email,
				// 'DateOfBirth' => $request->user_dateofbirth,
				// 'IdNumber' => $request->user_idnumber,
				// 'Vat' => $request->user_vat,
				// 'CompanyReg' => $request->user_companyreg,
				// 'Address' => $request->user_address,
				// 'CompanyName' => $request->user_companyname,
				// 'Banking' => $request->user_banking
			]
		);

		var_dump('action: ' . $action);



		// $user_id = Auth::user()->id;
		// $detail = Detail::where('person_id', '=', $user_id)->first();

		// var_dump($detail);

		// $detail->firstname = $request->user_firstname;
		// $detail->surname = $request->user_surname;
		// $detail->mobile = $request->user_mobile;
		// $detail->email = $request->user_email;
		// $detail->dateofbirth = $request->user_dateofbirth;
		// $detail->idnumber = $request->user_idnumber;
		// $detail->vat = $request->user_vat;
		// $detail->companyreg = $request->user_companyreg;
		// $detail->address = $request->user_address;
		// $detail->companyname = $request->user_companyname;
		// $detail->banking = $request->user_banking;
		// $detail->file = $request->user_logo;

		// $detailSave = [
		// 	'firstname' => $request->user_firstname,
		// 	'surname' => $request->user_surname,
		// 	'mobile' => $request->user_mobile,
		// 	'email' => $request->user_email,
		// 	'dateofbirth' => $request->user_dateofbirth,
		// 	'idnumber' => $request->user_idnumber,
		// 	'vat' => $request->user_vat,
		// 	'companyreg' => $request->user_companyreg,
		// 	'address' => $request->user_address,
		// 	'companyname' => $request->user_companyname,
		// 	'banking' => $request->user_banking
		// ];

		// $update = Detail::where('person_id', $user_id)->update($detailSave);


		// return response()->json(['detail' => $detail]);

		// $file = $request->file('user_logo');
		// $fileName = $file->getClientOriginalName();
		// $file->storeAs('uploads', $fileName);

		// $save = $detail->save();

		// if ( $save ) {
		// 	return response()->json([
		// 		'code' => 1,
		// 		'msg' => 'User Details has been successfully saved!',
		// 		'data' => $formData
		// 	]);
		// } else {
		// 	return response()->json([
		// 		'code' => 0,
		// 		'msg' => 'Something went wrong.',
		// 		'data' => null
		// 	]);
		// }


		/*

		if ( !$validator->fails() ) {

			$event = new Event();

			$event->event_name		= $event_name;
			$event->event_desc		= $event_desc;
			$event->event_location	= $event_location;
			$event->event_date		= $event_date;
			$event->published		= $this->active;
			$event->user_id			= 1;

			$save = $event->save();

			if ( $save ) {

				$eventData = [
					'id' => $event->id,
					'name' => $event->event_name,
					'desc' => $event->event_desc,
					'loc' => $event->event_location,
					'date' => $event->event_date,
					'published' => $event->published,
					'user_id' => $event->user_id
				];

				return response()->json([
					'code' => 1,
					'msg' => 'New Event has been successfully created!',
					'data' => $eventData
				]);

			} else {

				return response()->json([
					'code' => 0,
					'msg' => 'Something went wrong.',
					'data' => null
				]);

			}

		} else {
			var_dump('failed');
			var_dump($validator->errors()->toArray());
		}

		*/

	}

	public function getEventsList(){
		$events = Event::where('published', '=', $this->active)->get();
		return response()->json(['details' => $events]);
	}

	public function getUserDetailsInfo(){

		$user_id = Auth::user()->id;
		$userDetail = Detail::where('person_id', '=', $user_id)->first();
		return response()->json(['detail' => $userDetail]);

	}

	public function changeEventStatus(Request $request) {

		$event_id = $request->input('event_id');
		$eventData = [ 'published' => 0 ];

		$update = Event::where('id', '=', $event_id)->update($eventData);
		$data = ['event_id' => $event_id, 'update' => $update];

		return response()->json($data);

	}

	public function getEventDetails(Request $request) {
		$eventid = $request->eventid;
		$event = Event::select('*')->where('id', $eventid)->firstOrFail();
		return response()->json($event);
	}

	public function updateUserData(Request $request) {

		$event_id		 = $request->event_id;
		$event_name		 = $request->event_name;
		$event_desc		 = $request->event_desc;
		$event_loc		 = $request->event_loc;
		$event_mod_date	 = $request->event_mod_date;

		$eventData = [
			'event_name' => $event_name,
			'event_desc' => $event_desc,
			'event_location' => $event_loc,
			'event_date' => $event_mod_date
		];

		$update = Event::where('id', '=', $event_id)->update($eventData);
		$data = ['event_id' => $event_id, 'eventData' => $eventData, 'update' => $update];

		return response()->json($data);

	}

}
