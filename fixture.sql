DROP TABLE IF EXISTS `page`;
CREATE TABLE `page` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `parent_id` int(10) unsigned DEFAULT NULL,
  `content` longtext,
  `keywords` text,
  `is_active` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `position` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `page_parent_ref` (`parent_id`),
  CONSTRAINT `page_parent_ref` FOREIGN KEY (`parent_id`) REFERENCES `page` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;


LOCK TABLES `page` WRITE;
INSERT INTO `page` VALUES
(1,'some','soem',NULL,NULL,NULL,1,3),
(2,'child','child',1,NULL,NULL,1,0),
(3,'asd','asd',NULL,NULL,NULL,1,0),
(4,'asd','asdh',3,NULL,NULL,1,0);
UNLOCK TABLES;


DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(32) NOT NULL,
  `is_active` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `game` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `price` int(20) NOT NULL,
  `special_offer` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `required_age` int(10) DEFAULT NULL,
  `is_active` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `game_category` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` longtext,
  `is_active` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `game_genre` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` longtext,
  `is_active` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `game_genres` (
  `game_id` int(20) NOT NULL,
  `genre_id` int(20) NOT NULL,
  PRIMARY KEY (`game_id`,`genre_id`),
  KEY `game_genres_ref` (`genre_id`),
  CONSTRAINT `game_genres_game_ref` FOREIGN KEY (`game_id`) REFERENCES `game` (`id`),
  CONSTRAINT `game_genres_genre_ref` FOREIGN KEY (`genre_id`) REFERENCES `game_genre` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `game_categories` (
  `game_id` int(20) NOT NULL,
  `category_id` int(20) NOT NULL,
  PRIMARY KEY (`game_id`,`category_id`),
  KEY `game_categories_ref` (`category_id`),
  CONSTRAINT `game_categories_category_ref` FOREIGN KEY (`category_id`) REFERENCES `game_category` (`id`),
  CONSTRAINT `game_categories_game_ref` FOREIGN KEY (`game_id`) REFERENCES `game` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `user` WRITE;
INSERT INTO `user` VALUES (1,'admin','admin@game-shop.local','123',1);
UNLOCK TABLES;


