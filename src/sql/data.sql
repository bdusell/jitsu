insert into "videos"("name", "url")
values (
	'Video 1',
	'http://www.example.com/video1'
);
insert into "tags"("video_id", "value")
values (
	last_insert_rowid(),
	'tag1'
);
insert into "videos"("name", "url")
values (
	'Video 2',
	'http://www.example.com/video2'
);
insert into "videos"("name", "url")
values (
	'Video 3',
	'http://www.example.com/video3'
);
insert into "videos"("name", "url")
values (
	'Video 4',
	'http://www.example.com/video4'
);
insert into "videos"("name", "url")
values (
	'Video 5',
	'http://www.example.com/video5'
);
