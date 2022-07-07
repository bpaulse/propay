<?php

namespace App\Http\Traits;
use App\Models\InvoiceLine;

trait CalcTrait {
	public function index() {
		// Fetch all the students from the 'student' table.
		$student = InvoiceLine::all();
		return view('welcome')->with(compact('student'));
	}
}