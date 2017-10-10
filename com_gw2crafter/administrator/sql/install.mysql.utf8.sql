CREATE TABLE IF NOT EXISTS  `#__gw2crafter_api_item` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ordering` int(11) NOT NULL,
  `state` tinyint(1) NOT NULL,
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_by` int(11) NOT NULL,
  `gw2_item_id` int(11) NOT NULL,
  `gw2_item_name` varchar(255)  NOT NULL,
  `gw2_item_type` varchar(64)  NOT NULL,
  `gw2_item_icon_url` varchar(255)  NOT NULL,
  `gw2_item_rarity` varchar(64)  NOT NULL,
  `gw2_item_vendor_value` int(11) NOT NULL,
  `gw2_item_required_level` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `gw2_item_id` (`gw2_item_id`)
) ENGINE=InnoDB ;

CREATE TABLE IF NOT EXISTS `#__gw2crafter_api_recipe` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL ,
`created_by` INT(11)  NOT NULL ,
`modified_by` INT(11)  NOT NULL ,
`gw2_recipe_id` INT NOT NULL ,
`gw2_recipe_type` VARCHAR(64) NOT NULL ,
`gw2_recipe_name` VARCHAR(255) NOT NULL,
`gw2_created_item_id` INT NOT NULL,
`gw2_output_item_count` INT NOT NULL,
`gw2_recipe_min_rating` INT NOT NULL ,
    CONSTRAINT id_gw2_recipe_id UNIQUE (id,gw2_recipe_id),
PRIMARY KEY (`id`)
)ENGINE=InnoDB  ;

CREATE TABLE IF NOT EXISTS `#__gw2crafter_recipe_items` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL ,
`created_by` INT(11)  NOT NULL ,
`modified_by` INT(11)  NOT NULL ,
`gw2crafter_api_recipe_id` INT NOT NULL ,
    `gw2crafter_api_item_id` INT NOT NULL,
    `qty` INT NOT NULL,

PRIMARY KEY (`id`)
) ENGINE=InnoDB ;

CREATE TABLE `#__gw2crafter_recipe_disciplines` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ordering` int(11) NOT NULL,
  `state` tinyint(1) NOT NULL,
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_by` int(11) NOT NULL,
  `gw2crafter_api_recipe_id` int(11) NOT NULL,
  `gw2crafter_api_discipline` varchar(64)  NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `gw2crafter_api_recipe_id` (`gw2crafter_api_recipe_id`,`gw2crafter_api_discipline`)
) ENGINE=InnoDB ;

CREATE TABLE IF NOT EXISTS `#__gw2crafter_api_item_prices` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL ,
`created_by` INT(11)  NOT NULL ,
`modified_by` INT(11)  NOT NULL ,
`modified` CURRENT_TIMESTAMP,
`gw2crafter_item_id` INT NOT NULL ,
    `gw2crafter_api_highest_buy` INT NOT NULL,
    `gw2crafter_api_lowest_sell` INT NOT NULL,
PRIMARY KEY (`id`)
)ENGINE=InnoDB  ;

CREATE TABLE IF NOT EXISTS `#__gw2crafter_crafter_favorites` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL ,
`created_by` INT(11)  NOT NULL ,
`modified_by` INT(11)  NOT NULL ,
`gw2_item_id` INT(11)  NOT NULL ,
`gw2_name` VARCHAR(255) NOT NULL ,
`joomla_user_id` INT NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8mb4_unicode_ci;

source api_items.sql;
