<?php

namespace DemoApp;

use jitsu\StringUtil;
use jitsu\ArrayUtil;
use jitsu\sql\Ast as sql;

class Videos {

	public static function read_many($data) {
		Util::wrap($data, function($data) {
			$db = $data->database;
			$videos = $db->query(Util::sql($data,
				sql::select(sql::star())
				->from(sql::table('videos')->as_self())
				->order_by(sql::col('id')->asc())
			));
			Util::page($data, 'videos/index', array(
				'title' => 'Videos',
				'videos' => $videos
			));
		});
	}

	public static function read($data) {
		Util::wrap($data, function($data) {
			list($id) = $data->parameters;
			$db = $data->database;
			$video = $db->row(Util::sql($data,
				sql::select(sql::star())
				->from(sql::table('videos')->as_self())
				->where(sql::col('id')->eq(sql::value()))
				->limit(sql::value(1))
			), $id);
			if($video) {
				$tags = $db->query(Util::sql($data,
					sql::select(sql::col('value')->as_self())
					->from(sql::table('tags')->as_self())
					->where(sql::col('video_id')->eq(sql::value()))
				), $id);
				Util::page($data, 'videos/show', array(
					'title' => 'Video ' . $id,
					'video' => $video,
					'tags' => $tags
				));
			} else {
				Util::error($data, 404);
			}
		});
	}

	public static function new_form($data) {
		Util::wrap($data, function($data) {
			Util::page($data, 'videos/new', array(
				'title' => 'New Video'
			));
		});
	}

	public static function create($data) {
		Util::wrap($data, function($data) {
			$request = $data->request;
			$name = $request->form('name');
			$href = $request->form('url');
			$db = $data->database;
			$db->execute(Util::sql($data,
				sql::insert(sql::table('videos')->cols('name', 'url'))->values(2)
			), $name, $href);
			Util::redirect($data, 'videos/' . StringUtil::encode_url($db->last_insert_id()));
		});
	}

	public static function search($data) {
		Util::wrap($data, function($data) {
			$request = $data->request;
			$query = new \jitsu\XString($request->form('query'));
			$tags = $query->split();
			if(!$tags->is_empty()) {
				$db = $data->database;
				$videos = $db->query(Util::sql($data,
					sql::select(
						sql::col('id')->as_self(),
						sql::col('name')->as_self()
					)
					->from(
						sql::table('videos')->as_self()
						->join(sql::table('tags')->as_self())->on(
							sql::table('videos')->col('id')
							->eq(sql::table('tags')->col('video_id'))
						)
					)->where(sql::col('value')->in(
						ArrayUtil::fill(sql::value(), $tags->length())
					))
				), $tags->value)->to_array();
			} else {
				$videos = array();
			}
			Util::page($data, 'videos/search', array(
				'title' => 'Search Results',
				'videos' => $videos,
				'tags' => $tags
			));
		});
	}
}

?>
