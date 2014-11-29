<?php

class VideosController {

	public static function index() {
		$videos = Database::query('select "id" from "videos"');
		AppHelper::page('videos/index', array(
			'title' => 'Videos',
			'videos' => $videos
		));
	}

	public static function show($id) {
		$video = Database::row('select "id" from "videos" where "id" = ?', $id);
		if($video) {
			$tags = Database::query('select "value" from "tags" where "video_id" = ?', $id);
			AppHelper::page('videos/show', array(
				'title' => 'Video ' . $id,
				'video' => $video,
				'tags' => $tags
			));
		} else {
			AppHelper::error(404);
		}
	}

	public static function new_() {
		AppHelper::page('videos/new', array(
			'title' => 'New Video'
		));
	}

	public static function create() {
		$id = Request::form('id');
		Database::execute('insert into "videos"("id") values (?)', $id);
		AppHelper::redirect('videos/' . rawurlencode($id));
	}
}

?>
