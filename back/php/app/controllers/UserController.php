<?php

class UserController {

	public static function index() {
		AppHelper::page('users/index', array('title' => 'Users'));
	}

	public static function show($id) {
		$row = Database::row(
<<<SQL
select "id", "name"
from "users"
where "id" = ?
limit 1
SQL
		, $id);
		if($row) {
			AppHelper::page('users/show', array(
				'title' => 'User',
				'id' => $row->id,
				'name' => $row->name
			));
		} else {
			AppHelper::error(404);
		}
	}

	public static function create() {
		//Response::redirect();
	}
}

?>
