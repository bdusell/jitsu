<?php

use phrame\sql\Ast as sql;

class VideosController {

	public static function index() {
		$videos = Database::query(Database::interpret(
			sql::select(sql::star())
			->from(sql::table('videos')->as_self())
			->order_by(sql::col('id')->asc())
		));
		Pages::page('videos/index', array(
			'title' => 'Videos',
			'videos' => $videos
		));
	}

	public static function show($id) {
		$video = Database::row(Database::interpret(
			sql::select(sql::star())
			->from(sql::table('videos')->as_self())
			->where(sql::col('id')->eq(sql::value()))
			->limit(sql::value(1))
		), $id);
		if($video) {
			$tags = Database::query(Database::interpret(
				sql::select(sql::col('value')->as_self())
				->from(sql::table('tags')->as_self())
				->where(sql::col('video_id')->eq(sql::value()))
			), $id);
			Pages::page('videos/show', array(
				'title' => 'Video ' . $id,
				'video' => $video,
				'tags' => $tags
			));
		} else {
			Pages::error(404);
		}
	}

	public static function new_() {
		Pages::page('videos/new', array(
			'title' => 'New Video'
		));
	}

	public static function create() {
		$name = Request::form('name');
		$href = Request::form('url');
		Database::execute(Database::interpret(
			sql::insert(sql::table('videos')->cols('name', 'url'))->values(2)
		), $name, $href);
		Pages::redirect('videos/' . StringUtil::encode_url(Database::last_insert_id()));
	}

	public static function search() {
		$query = xstring(Request::form('query'));
		$tags = $query->split();
		if(!$tags->is_empty()) {
			$videos = Database::query(Database::interpret(
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
			), $tags)->to_array();
		} else {
			$videos = array();
		}
		Pages::page('videos/search', array(
			'title' => 'Search Results',
			'videos' => $videos,
			'tags' => $tags
		));
	}
}

?>
