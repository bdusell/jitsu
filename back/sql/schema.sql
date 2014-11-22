create table "cards" (
	"id" integer not null primary key autoincrement,
	"foreign" text not null,
	"english" text not null
);

create table "lists" (
	"id" integer not null primary key autoincrement,
	"name" text not null
);

create table "tags" (
	"id" integer not null primary key autoincrement,
	"name" text not null
);

create table "trials" (
	"id" integer not null primary key autoincrement,
	"level" integer not null,
	"list_id" integer not null,
	foreign key ("list_id") references "lists"("id") on delete cascade
);
