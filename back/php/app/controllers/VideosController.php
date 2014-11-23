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
			AppHelper::page('videos/show', array(
				'title' => 'Video ' . $id,
				'video' => $video
			));
		} else {
			AppHelper::error(404);
		}
	}
}

?>
