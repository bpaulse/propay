<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Person;

class PersonController extends Controller
{
	public function getPersonInfo( $person_id ) {

		return DB::table('details')->where('person_id', $person_id)->first();

	}
}
