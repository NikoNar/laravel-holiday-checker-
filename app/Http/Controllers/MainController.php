<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\DateRequest;
use App\Services\CheckForHolidays;
use DateTime;

class MainController extends Controller
{
	public function index(){
		$current_datetime = date("d-m-Y H:i:s l");
		return view('index',compact('current_datetime'));
	}

	public function compare(DateRequest $data){
		$input_date = date('d-m-Y',strtotime($data->date));
		$checking   = new CheckForHolidays($input_date);
		return $checking->search_in_array();
	}
}
