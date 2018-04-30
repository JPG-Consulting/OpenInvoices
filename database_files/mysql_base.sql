CREATE TABLE `users` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` tinytext NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_by` varchar(100) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `roles_un_username` (`username`),
  UNIQUE KEY `roles_un_email` (`email`),
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `parent_id` int(10) unsigned int NULL,
  `created_by` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_by` varchar(100) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_un` (`name`),
  KEY `roles_name_idx` (`name`) USING BTREE,
  CONSTRAINT `roles_ibfk_hierarchy` FOREIGN KEY (`parent_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `user_roles` (
  `user_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  `created_by` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_by` varchar(100) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `user_roles_roles_fk` (`role_id`),
  CONSTRAINT `user_roles_roles_fk` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `user_roles_users_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO users
(username, password, email, active,  created_by, created_at, modified_by, modified_at)
VALUES('admin', '$2y$10$c1akAPemjP/v64AjfJs0n.MK359D86zZuAzJbNf0lunz51mDksAne', 'root@localhost', 1, 'System', '2018-04-29 21:48:00.000', NULL, NULL);

INSERT INTO roles
(name, parent_id, created_by, created_at, modified_by, modified_at)
VALUES('Administrators', NULL, 'System', '2018-04-29 21:48:00.000', NULL, NULL);

INSERT INTO openinvoices.user_roles
(user_id, role_id, created_by, created_at, modified_by, modified_at)
VALUES(1, 1, 'System', '2018-04-29 21:48:00.000', NULL, NULL);



