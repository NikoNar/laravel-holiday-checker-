<?php 
namespace App\Services;

class CheckForHolidays{
	protected $date;
	public function __construct($date){
		$this->date = $date;
		$this->holidays = config('holidays');
		date_default_timezone_set($this->holidays['timezone']);
	}

	public function searchInArray($date = null){
		$date = !$date ? $this->date : $date;
		$holidays   = $this->holidays['dates']; //array of all holidays;
		foreach($holidays as $key=>$holiday){
			switch(count($holiday)){
				case count($holiday)==2 :	
					if(date('d-m',strtotime($date)) == $holiday[0]){
						if($this->checkingForDayOff($date)){
							$response = json_encode(['celebrity'=>$holiday[1],'add_day_offs'=>$this->checkingForDayOff($date)]);
							return $response;
						}
						return json_encode(['celebrity'=>$holiday[1]]);
						break;
					}

				case count($holiday)==3 :
					if(ctype_digit($holiday[0][0])){
						$from_date = $holiday[0]."-".explode('-', $date)[2];
						$to_date   = $holiday[1]."-".explode('-', $date)[2];
						if(strtotime($date) >= strtotime($from_date) && strtotime($date) <= strtotime($to_date)){
							if($this->checkingForDayOff($date) && strtotime($date) == strtotime($to_date)){
								$response = json_encode(['celebrity'=>$holiday[2],'add_day_offs'=>$this->checkingForDayOff($date)]);
								return $response;
							}
							return json_encode(['celebrity'=>$holiday[2]]);
							break;
						}
					}

				case count($holiday)==4 :
					if(ctype_alpha($holiday[0][0])){
						$week_day = $this->weekNumber($holiday[0]); //week day of expired
						$nth_week = $holiday[1]; //nth-week of expired 
						$month    = $this->monthNumber($holiday[2]); //month of expired

						//finding month-nthweek
						$inputed_year = explode('-', $date)[2];
						$first_day_of_month = "01-$month-$inputed_year";
						if($nth_week == "first"){
							$nth_week = 1;
						}

						if($nth_week == 'last'){
							$nth_week = $this->fintLastMonday($first_day_of_month);
						}

						$nth_week == 1 ? $month_nth_week = $first_day_of_month : $month_nth_week = date('d-m-Y',strtotime($first_day_of_month)+(($nth_week-1)*7*86400));

						if($nth_week==1){ 
							if(date('w',strtotime($date))<$week_day && ctype_digit(date('w',strtotime($month_nth_week)))!=0){ //checking if this date's week_day later than inputed or not
								$week_day == 7 ? $difference = date('w',strtotime($month_nth_week)) : $difference = date('w',strtotime($month_nth_week)) - $week_day;
								
								$month_nth_week = date('d-m-Y',strtotime($month_nth_week)+((7-$difference)*86400)); //expired date;
																
							}
							else{
								$difference = date('w',strtotime($month_nth_week)) - $week_day;
								$month_nth_week = date('d-m-Y',strtotime($month_nth_week)-$difference*86400); // expired date;
							}
						}
						else{
							if(date('w',strtotime($month_nth_week))>1){
								$temp = date('w',strtotime($month_nth_week));
								while($temp>1){
									$month_nth_week = date('d-m-Y',strtotime($month_nth_week)-86400);
									$temp--;
								}
							}



							if(date('w',strtotime($month_nth_week))<$week_day && date('w',strtotime($month_nth_week))!= 0){
								if(ctype_digit(date('m',strtotime($month_nth_week)+($week_day*86400)))>ctype_digit(date('m',strtotime($month_nth_week)))){
									$month_nth_week = date('d-m-Y',strtotime($month_nth_week)-(8-$week_day)*86400);			
								}
								else{
									$month_nth_week = date('d-m-Y',strtotime($month_nth_week)+($week_day-1)*86400);
								}
							}
							elseif (date('w',strtotime($month_nth_week))==0){
								$month_nth_week = date('d-m-Y',strtotime($month_nth_week)-(6*86400)+($week_day-1)*86400);
							}
						}
						if($date == $month_nth_week){
							if($this->checkingForDayOff($this->date)){
								$response = json_encode(['celebrity'=>$holiday[3],'add_day_offs'=>$this->checkingForDayOff($this->date)]);
								return $response;
							}
							return json_encode(['celebrity'=>$holiday[3]]);
						}
					}
					if(date('l',strtotime($date))=='Monday'){
						if($this->checkingMondayDayOff($date)){
							$celebrity = json_decode($this->checkingMondayDayOff($date));
							return json_encode(['day_off'=>true,'previous_celebrity'=>$celebrity->celebrity]);
						}
					}
			}
		}
	}

	// accepts string parameter (Month name) and returns it's number in year 
	private function monthNumber($mon){
		$months = ['01' => 'January','02'=>'February','03'=>'March','04'=>'April','05'=>'May','06'=>'June','07'=>'July','08'=>'August','09'=>'September','10'=>'October','11'=>'November','12'=>'December'];
    
		foreach($months as $key=>$month){
			if($month == $mon){
				return $key;
			}
		}
		return false;
	}

	// accepts string parameter (Week name) and returns it's number in week 
	private function weekNumber($week){
		$week_days = [1=>"Monday",2=>"Tuesday",3=>'Wednesday',4=>'Thursday',5=>'Friday',6=>'Saturday',7=>"Sunday"];

		foreach($week_days as $key=>$week_day){
			if($week == $week_day){
				return $key;
			}
		}
		return false;
	}

	// function cheking if a day or two days later have been celebration or not,accepts one parameter of type string(date) and return boolean value
	private function checkingForDayOff($date){
		$week_day = date('w',strtotime($date));
		if($week_day == 6 || $week_day == 7){
			switch($week_day){
				case $week_day == 6:
					$day_offs = [date('d-m-Y',strtotime($date)+86400+86400)];
					break;
				case $week_day == 7:
				    $day_offs = [date('d-m-Y',strtotime($date)+86400)];
					break;
			}
			return $day_offs;
		}
		return false;
	}

	//function finds the last week of the month,accepts string type parameter(date) and returns value of integer type
	private function fintLastMonday($date){
		$nth_weekday        = 8-date('w',strtotime($date)); //reversed nth_weekday
		$last_day_of_month  = date('t',strtotime($date)); //nth month
		$count = 0;
		if(($last_day_of_month-$nth_weekday)%7>0){
			$count = floor(($last_day_of_month-$nth_weekday)/7+2);
		}
		else{
			$count = floor(($last_day_of_month-$nth_weekday)/7+1);
		}

		return $count;
	}

	// function cheking if a day or two days later Monday have been celebration or not,accepts one parameter of type string(date) and return boolean value
	private function checkingMondayDayOff($date){
		if(date('l',strtotime($date))=="Monday"){
			$previous_day   = date('d-m-Y',strtotime($date)-86400);
			$two_days_later = date('d-m-Y',strtotime($date)-86400-86400);
			if($this->searchInArray($previous_day) || $this->searchInArray($two_days_later)){
				switch (true) {
					case $this->searchInArray($previous_day):
						return $this->searchInArray($previous_day);
						break;

					case $this->searchInArray($two_days_later):
						return $this->searchInArray($two_days_later);
						break;
					
				}
			}
		}
		return false;
	}
}
?>
