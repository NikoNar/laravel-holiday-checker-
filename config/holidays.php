<?php 
	return [
		'dates' =>[
			[
				date('01-01'),  //constant date holiday each year
				"It's New Year on that date" //validation message
			],
			[
				date('07-01'), //constant  date holiday each year
				"It's Christmas on that date" // validation message
			],
			[	// holiday in range
				date('01-05'), //starting date
				date('07-05'), //ending date
				"It's May month holidays on that date", //validation message
			],
			[
				date('03-03'), //starting date
				date('10-03'), //ending date
				"It's March month holidays on that date", //validation message
			],
			[
				'Monday', //Day of week
				"last",	//nth week of month
				'March', // month
				'March Monday holiday' //validation message
			], //nth week of month
			[
				'Saturday', //Day of week
				'first', //nth week of month
				'June',	//month
				'Jun Saturday holiday' //validation message
			], //nth week of month
			[
				'Thursday', //Day of week
				4, // nth week of month
				'November', //month
				'November Thursday holiday' //validation message
				], //nth week of month
			[
				'Wednesday', //day of week
				2, // nth week of month
				'June', //month
				'June Wednesday holiday' //validation message
			], //nth week of month
			[
				'Tuesday', //day of week
				2,	//nth week of month
				'August',	//month
				'August Tuesday holiday'	//validation message
			], //nth week of month
			[
				'Monday',	//day of week
				2,	//nth week of month
				'July',	//month
				'July Monday holiday'	//validation message
			], //nth week of month
		],
		'timezone' => 'Asia/Yerevan',	//timezone
	];
 ?>