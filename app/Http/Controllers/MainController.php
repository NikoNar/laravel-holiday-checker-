<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CheckForHolidays;
use DateTime;

class MainController extends Controller
{
	public function __construct(){
		$this->holidays = config('holidays');
		date_default_timezone_set($this->holidays['timezone']);
	}

	public function index(){
		$current_datetime = date("d-m-Y H:i:s l");
		return view('index',compact('current_datetime'));
	}

	public function compare(Request $data){
		$input_date = date('d-m-Y',strtotime($data->date));
		$holidays   = $this->holidays['dates']; //array of all holidays;
		$checking = new CheckForHolidays();
		if($checking->search_in_array($input_date,$holidays)){
			print_r($checking->search_in_array($input_date,$holidays));
		}
	}
}
