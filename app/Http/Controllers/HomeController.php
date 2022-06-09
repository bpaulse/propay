<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Person;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Contracts\Support\Renderable
	 */
	public function index()
	{
		return view('home');
	}

	public function getPeopleList (Request $request) {

		$people = DB::select('SELECT peo.id, peo.user_id, det.Name, det.Surname, det.Mobile, det.Email, det.DateOfBirth, det.Idnumber 
			FROM people as peo 
			INNER JOIN details AS det on peo.id = det.person_id 
			WHERE peo.user_id = ? AND peo.state = 1', 
			[Auth::user()->id, 1]
		);

		return response()->json($people);

	}

	public function deletePersonLine(Request $request) {
		$person_id = $request->input('id');
		var_dump($person_id);
	}

}
