<?php

namespace DemoApp;

use jitsu\JSONUtil;
use jitsu\sql\Ast as sql;

class Tags {

	public static function create($data) {
		Util::wrap($data, function($data) {
			list($video_id, $value) = $data->parameters;
			$db = $data->database;
			$db->execute(Util::sql($data,
				sql::insert_or_ignore(
					sql::table('tags')->cols('video_id', 'value')
				)->values(2)
			), $video_id, $value);
			$config = $data->config;
			echo JSONUtil::encode((object) array('value' => $value), !$config->minify_json);
		});
	}

	public static function delete($data) {
		Util::wrap($data, function($data) {
			list($video_id, $value) = $data->parameters;
			$db = $data->database;
			$db->execute(Util::sql($data,
				sql::delete(sql::table('tags'))->where(
					sql::col('video_id')->eq(sql::value())->and_(
						sql::col('value')->eq(sql::value())
					)
				)
			), $video_id, $value);
		});
	}
}

?>
