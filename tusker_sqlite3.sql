-- SQLiteDatabaseBrowser 3.9.1 SQL Dump
-- it is for reference only
-- you can directly use sqlite database located in ./assets/tusker.db
-- by enabling the corresponding config option in ./application/config/database.php

BEGIN TRANSACTION;

CREATE TABLE `website` (
  `title` varchar(255) NOT NULL
,  `page_title` varchar(255) NOT NULL
,  `status` integer NOT NULL DEFAULT '0'
,  `admin_email` varchar(200) NOT NULL
,  `contact_email` varchar(200) NOT NULL
,  `modified_by` varchar(200) NOT NULL
);
INSERT INTO `website` VALUES ('Tusker', 'Tusker', 1, 'avenir.ro@gmail.com', 'avenir.ro@gmail.com', '');

CREATE TABLE "users_groups" (
	`id`	integer NOT NULL,
	`user_id`	integer NOT NULL,
	`group_id`	integer NOT NULL,
	PRIMARY KEY(id)
);
INSERT INTO `users_groups` VALUES (1, 1, 1);
INSERT INTO `users_groups` VALUES (2, 1, 2);

CREATE TABLE "users" (
	`id`	integer NOT NULL,
	`ip_address`	varchar(15) NOT NULL,
	`username`	varchar(100) NOT NULL,
	`password`	varchar(255) NOT NULL,
	`salt`	varchar(255) DEFAULT NULL,
	`email`	varchar(100) NOT NULL,
	`activation_code`	varchar(40) DEFAULT NULL,
	`forgotten_password_code`	varchar(40) DEFAULT NULL,
	`forgotten_password_time`	integer DEFAULT NULL,
	`remember_code`	varchar(40) DEFAULT NULL,
	`created_on`	integer NOT NULL,
	`last_login`	integer DEFAULT NULL,
	`active`	integer DEFAULT NULL,
	`first_name`	varchar(50) DEFAULT NULL,
	`last_name`	varchar(50) DEFAULT NULL,
	`company`	varchar(200) NOT NULL,
	`phone`	varchar(20) NOT NULL,
	PRIMARY KEY(id)
);
INSERT INTO `users` VALUES (1,'127.0.0.1','administrator','$2a$07$SeBknntpZror9uyftVopmu61qg0ms8Qv1yV6FG.kQOSM.9QhmTo36','','admin@admin.com','',NULL,NULL,'gtbuLmffMbIAevpNfUYwfe',1268889823,1495133418,1,'Admin','istrator','','');

CREATE TABLE "tasks" (
	`id`	integer NOT NULL,
	`project_id`	integer NOT NULL,
	`user_id`	integer NOT NULL,
	`title`	varchar(255) NOT NULL,
	`details`	longtext,
	`ect`	integer NOT NULL,
	`status`	integer NOT NULL DEFAULT '0',
	`due`	date DEFAULT NULL,
	`closed`	integer NOT NULL DEFAULT '0',
	`time_spent`	integer NOT NULL DEFAULT 0,
	`created_at`	datetime DEFAULT NULL,
	`created_by`	integer DEFAULT NULL,
	`updated_at`	datetime DEFAULT NULL,
	`updated_by`	integer DEFAULT NULL,
	`deleted_at`	datetime DEFAULT NULL,
	`deleted_by`	integer DEFAULT NULL,
	PRIMARY KEY(id)
);

CREATE TABLE "task_histories" (
	`id`	integer NOT NULL,
	`task_id`	integer NOT NULL,
	`user_id`	integer NOT NULL,
	`comment`	longtext,
	`created_at`	datetime DEFAULT NULL,
	`created_by`	integer DEFAULT NULL,
	`updated_at`	datetime DEFAULT NULL,
	`updated_by`	integer DEFAULT NULL,
	`deleted_at`	datetime DEFAULT NULL,
	`deleted_by`	integer DEFAULT NULL,
	`status`	integer DEFAULT NULL,
	`time_spent`	integer DEFAULT NULL,
	PRIMARY KEY(id)
);

CREATE TABLE "rat" (
	`id`	integer NOT NULL,
	`user_id`	integer DEFAULT NULL,
	`date_time`	datetime DEFAULT NULL,
	`code`	integer DEFAULT NULL,
	`message`	varchar(255) DEFAULT NULL,
	PRIMARY KEY(id)
);

CREATE TABLE "projects_users" (
	`id`	integer NOT NULL,
	`user_id`	integer NOT NULL,
	`project_id`	integer NOT NULL,
	`role`	varchar(20) DEFAULT NULL,
	`status`	integer NOT NULL DEFAULT '0',
	`created_at`	datetime DEFAULT NULL,
	`created_by`	integer DEFAULT NULL,
	`updated_at`	datetime DEFAULT NULL,
	`updated_by`	integer DEFAULT NULL,
	`deleted_at`	datetime DEFAULT NULL,
	`deleted_by`	integer DEFAULT NULL,
	PRIMARY KEY(id)
);

CREATE TABLE "projects" (
	`id`	integer NOT NULL,
	`user_id`	integer NOT NULL,
	`title`	varchar(255) NOT NULL,
	`due`	date DEFAULT NULL,
	`closed`	integer NOT NULL DEFAULT '0',
	`created_at`	datetime DEFAULT NULL,
	`created_by`	integer DEFAULT NULL,
	`updated_at`	datetime DEFAULT NULL,
	`updated_by`	integer DEFAULT NULL,
	`deleted_at`	datetime DEFAULT NULL,
	`deleted_by`	integer DEFAULT NULL,
	PRIMARY KEY(id)
);

CREATE TABLE "login_attempts" (
	`id`	integer NOT NULL,
	`ip_address`	varchar(15) NOT NULL,
	`login`	varchar(100) NOT NULL,
	`time`	integer DEFAULT NULL,
	PRIMARY KEY(id)
);

CREATE TABLE "groups" (
	`id`	integer NOT NULL,
	`name`	varchar(20) NOT NULL,
	`description`	varchar(100) NOT NULL,
	PRIMARY KEY(id)
);
INSERT INTO `groups` VALUES (1,'admin','Administrator');
INSERT INTO `groups` VALUES (2,'members','General User');

CREATE TABLE "ci_sessions" (
	`id`	varchar(40) NOT NULL,
	`ip_address`	varchar(45) NOT NULL,
	`timestamp`	integer NOT NULL DEFAULT '0' UNIQUE,
	`data`	blob NOT NULL
);

CREATE TABLE "banned" (
	`id`	INTEGER NOT NULL,
	`ip`	varchar(15) NOT NULL,
	`created_at`	datetime NOT NULL,
	`updated_at`	datetime NOT NULL,
	`deleted_at`	datetime NOT NULL,
	`created_by`	integer NOT NULL,
	`updated_by`	integer NOT NULL,
	`deleted_by`	integer NOT NULL,
	PRIMARY KEY(id)
);
CREATE INDEX `fk_users_groups_users1_idx`  ON `users_groups`(`user_id`);
CREATE INDEX `fk_users_groups_groups1_idx` ON `users_groups`(`group_id`);

COMMIT;
