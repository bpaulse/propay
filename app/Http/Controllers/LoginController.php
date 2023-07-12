<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Person;
use App\Models\AppSetting;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{

	use AuthenticatesUsers;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */

	public function logout(Request $request) {
		// var_dump('bevan');
		// $this->guard()->logout();
		// $request->session()->invalidate();
		// return redirect('/index');
		Session::flush();
		Auth::logout();
		return redirect('login');
	}
}