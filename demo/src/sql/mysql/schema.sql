drop database if exists phrame;
create database phrame;
use phrame;

create table `videos` (
	`id` integer not null primary key auto_increment,
	`name` varchar(64) not null,
	`url` varchar(512) not null
);

create table `tags` (
	`video_id` integer not null,
	`value` varchar(16) not null,
	primary key (`video_id`, `value`),
	foreign key (`video_id`) references `videos`(`id`)
);
