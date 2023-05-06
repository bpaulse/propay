<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class StatementController extends Controller
{
	public function index() {
		return view('statement-list');
	}
}
