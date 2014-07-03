<?php 

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
