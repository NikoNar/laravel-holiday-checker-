<?php 
	return [
		'dates' =>[
			[
				date('01-01'),  //constant date holiday each year
				"It's New Year on that date" //validation message
			],
			[
				date('07-01'), //constant  date holiday each year
				"It's Christmas on that date" //
			],
			[
				date('01-05'),
				date('07-05'),
				"It's May month holidays on that date",
			],
			[
				date('03-03'),
				date('10-03'),
				"It's March month holidays on that date",
			],
			[
				'Monday',
				"last",
				'March',
				'March Monday holiday'
			], //nth week of month
			[
				'Saturday',
				'first',
				'June',
				'Jun Saturday holiday'
			], //nth week of month
			[
				'Thursday',
				4,
				'November',
				'November Thursday holiday'
				], //nth week of month
			[
				'Wednesday',
				2,
				'June',
				'June Wednesday holiday'
			], //nth week of month
			['Tuesday',2,'August','August Tuesday holiday'], //nth week of month
			['Monday',2,'July','July Monday holiday'], //nth week of month
		],
		'timezone' => 'Asia/Yerevan',
	];
 ?>