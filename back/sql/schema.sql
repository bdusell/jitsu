create table "videos" (
	"id" integer not null primary key autoincrement
);

create table "tags" (
	"video_id" integer not null,
	"value" text not null,
	foreign key ("video_id") references "videos"("id")
);
