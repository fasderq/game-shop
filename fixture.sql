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
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `genre` varchar(255) NOT NULL,
  `price` int(20) NOT NULL,
  `special_offer` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `required_age` int(10) DEFAULT NULL,
  `is_active` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `user` WRITE;
INSERT INTO `user` VALUES (1,'admin','admin@game-shop.local','123',1);
UNLOCK TABLES;
