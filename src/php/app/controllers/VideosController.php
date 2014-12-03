<?php

class VideosController {

	public static function index() {
		$videos = Database::query('select "id" from "videos"');
		Pages::page('videos/index', array(
			'title' => 'Videos',
			'videos' => $videos
		));
	}

	public static function show($id) {
		$video = Database::row('select "id" from "videos" where "id" = ?', $id);
		if($video) {
			$tags = Database::query('select "value" from "tags" where "video_id" = ?', $id);
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
		$id = Request::form('id');
		Database::execute('insert into "videos"("id") values (?)', $id);
		Pages::redirect('videos/' . StringUtil::encode_url($id));
	}

	public static function search() {
		$query = Request::form('query');
		$tags = StringUtil::split($query);
		if($tags) {
			$placeholders = StringUtil::join(', ', ArrayUtil::fill('?', ArrayUtil::length($tags)));
			$videos = Database::query(
				'select "id" from "videos" ' .
				'join "tags" on "videos"."id" = "tags"."video_id" ' .
				'where "value" in (' . $placeholders . ')',
				$tags
			);
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
