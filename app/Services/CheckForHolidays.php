<?php 
namespace App\Services;

class CheckForHolidays{
	public function search_in_array($value,$array){
		foreach($array as $key=>$holiday){
			switch(count($holiday)){
				case count($holiday)==2 :	
					if(date('d-m',strtotime($value)) == $holiday[0]){
						if($this->checking_for_dayoff($value)){
							$response = json_encode(['celebrity'=>$holiday[1],'add_day_offs'=>$this->checking_for_dayoff($value)]);
							return $response;
						}
						return json_encode(['celebrity'=>$holiday[1]]);
						break;
					}

				case count($holiday)==3 :
					if(ctype_digit($holiday[0][0])){
						$from_date = $holiday[0]."-".explode('-', $value)[2];
						$to_date   = $holiday[1]."-".explode('-', $value)[2];
						if(strtotime($value) >= strtotime($from_date) && strtotime($value) <= strtotime($to_date)){
							if($this->checking_for_dayoff($value) && strtotime($value) == strtotime($to_date)){
								$response = json_encode(['celebrity'=>$holiday[2],'add_day_offs'=>$this->checking_for_dayoff($value)]);
								return $response;
							}
							return json_encode(['celebrity'=>$holiday[2]]);
							break;
						}
					}

				case count($holiday)==4 :
					if(ctype_alpha($holiday[0][0])){
						$week_day = $this->week_number($holiday[0]); //week day of expired
						$nth_week = $holiday[1]; //nth-week of expired 
						$month    = $this->month_number($holiday[2]); //month of expired

						//finding month-nthweek
						$inputed_year = explode('-', $value)[2];
						$first_day_of_month = "01-$month-$inputed_year";
						if($nth_week == "first"){
							$nth_week = 1;
						}

						if($nth_week == 'last'){
							$nth_week = $this->find_last_week($first_day_of_month);
						}

						$nth_week == 1 ? $month_nth_week = $first_day_of_month : $month_nth_week = date('d-m-Y',strtotime($first_day_of_month)+(($nth_week-1)*7*86400));

						if($nth_week==1){ 
							if(date('w',strtotime($value))<$week_day && ctype_digit(date('w',strtotime($month_nth_week)))!=0){ //checking if this date's week_day later than inputed or not

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


						if($value == $month_nth_week){
							if($this->checking_for_dayoff($value)){
								$response = json_encode(['celebrity'=>$holiday[3],'add_day_offs'=>$this->checking_for_dayoff($value)]);
								return $response;
							}
							return json_encode(['celebrity'=>$holiday[3]]);
						}
					}
			}
		}
	}

	private function month_number($mon){
		$months = ['01' => 'January','02'=>'February','03'=>'March','04'=>'April','05'=>'May','06'=>'June','07'=>'July','08'=>'August','09'=>'September','10'=>'October','11'=>'November','12'=>'December'];
    
		foreach($months as $key=>$month){
			if($month == $mon){
				return $key;
			}
		}
		return false;
	}

	private function week_number($week){
		$week_days = [1=>"Monday",2=>"Tuesday",3=>'Wednesday',4=>'Thursday',5=>'Friday',6=>'Saturday',7=>"Sunday"];

		foreach($week_days as $key=>$week_day){
			if($week == $week_day){
				return $key;
			}
		}
		return false;
	}

	private function checking_for_dayoff($date){
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

	private function checking_for_weekday($date,$nth_week,$holiday_weekday){  
		if($nth_week==1){
			$expired_date_weekday = date('w',strtotime($date));	
			if($expired_date_weekday<=$holiday_weekday){
				return "passed";
			}
			else{
				for($i = $expired_date_weekday;$i<7+$holiday_weekday;$i++){
					$date = date('d-m-Y',strtotime($date)+86400);					
				}
				return $date;
			}
		}
	}

	private function find_last_week($date){
		$nth_weekday        = 8-date('w',strtotime($date)); //reversed nth_weekday
		$last_day_of_month  = date('t',strtotime($date)); //nth month
		$count = 0;
		if(($last_day_of_month-$nth_weekday)%7>0){
			$count = floor(($last_day_of_month-$nth_weekday)/7+2);
		}
		else{
			$count = floor(($last_day_of_month-$nth_weekday)/7)+1;
		}

		return $count;
	}
}
?>
