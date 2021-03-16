<?php 
	return [
		'dates' =>[
			'New_year'         => [date('01-01'),"It's New Year on that date"],
			'Christmas'        => [date('07-01'),"It's Christmas on that date"],
			'May_holidays'     => [date('01-05'),date('07-05'),"It's May month holidays on that date"],
			'March_holidays'   => [date('03-03'),date('10-03'),"It's March month holidays on that date"],
			'March-Monday'     => ['Monday',"last",'March','March Monday holiday'], //nth week of month
			'Jun-Saturday'     => ['Saturday','first','June','Jun Saturday holiday'], //nth week of month
			'November-Thursday'=> ['Thursday',4,'November','November Thursday holiday'], //nth week of month
			'June-Wednesday'   => ['Wednesday',2,'June','June Wednesday holiday'], //nth week of month
			'August-Tuesday'   => ['Tuesday',2,'August','August Tuesday holiday'], //nth week of month
			'July-Monday'      => ['Monday',2,'July','July Monday holiday'], //nth week of month
		],
		'timezone' => 'Asia/Yerevan',
	];
 ?>