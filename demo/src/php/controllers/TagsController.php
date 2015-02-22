<?php

use phrame\JSONUtil;
use phrame\sql\Ast as sql;

class TagsController {

	public static function update($video_id, $value) {
		Database::execute(Database::interpret(
			sql::insert_or_ignore(
				sql::table('tags')->cols('video_id', 'value')
			)->values(2)
		), $video_id, $value);
		echo JSONUtil::encode((object) array('value' => $value), !AppConfig::get('minify_json'));
	}

	public static function delete($video_id, $value) {
		Database::execute(Database::interpret(
			sql::delete(sql::table('tags'))->where(
				sql::col('video_id')->eq(sql::value())->and_(
					sql::col('value')->eq(sql::value())
				)
			)
		), $video_id, $value);
	}
}

?>
