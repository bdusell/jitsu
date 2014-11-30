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
		Pages::redirect('videos/' . rawurlencode($id));
	}
}

?>
