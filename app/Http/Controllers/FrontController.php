<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class FrontController extends Controller
{

		/**
	  * Create a new controller instance.
	  *
	  * @return void
	  */
	  public function __construct()
	  {
	    //$this->middleware('auth');
	  }

    public function homepage(){
			return view('front.home', [
				'active_page' => 'inicio',
				'title_page' => 'Inicio',
			]);
		}
		
}
