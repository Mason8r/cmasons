<?php 

//When editing or creating content, there are drop down 
//boxes for the menus, areas and categories. This function
//creates and returns the arrays for the drop downs.
function retrieveContentArray() {
	
	$data = array();

	$db['menus'] = Menu::all();
	$db['areas'] = Area::all();
	$db['categories'] = ContentCategory::all();

	//extract all the data needed from the mother fucking DB
	foreach ($db as $key => $objects ) {
		foreach ($objects as $value ) {
			$data[$key][$value->id] = $value->name;
		}
	}

	return $data;

}

//To get the age in months created_at of user
function retireveAgeInMonths($user = null) {

	if( ! $user ) {

		$user = Sentry::getUser();

	}

	$timestamp = strtotime($user->created_at);

	$date = new \DateTime();

	$date->setTimestamp($timestamp);

	$interval = $date->diff(new \DateTime('now'));

	return (($interval->format('%y') * 12) + $interval->format('%m'));

}
