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

	public function addUser(Request $request) {

		$event_name				= $request->input('event_name');
		$event_desc				= $request->input('event_desc');
		$event_location			= $request->input('event_location');
		$event_date				= $request->input('event_date');

		$validator = Validator::make($request->all(), [
			'event_name'		=> 'required|max:255',
			'event_desc'		=> 'required|max:255',
			'event_location'	=> 'required',
			'event_date'		=> 'required'
		]);

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
