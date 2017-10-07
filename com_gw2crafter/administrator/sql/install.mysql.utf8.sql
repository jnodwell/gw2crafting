CREATE TABLE IF NOT EXISTS `#__gw2crafter_api_item` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL ,
`created_by` INT(11)  NOT NULL ,
`modified_by` INT(11)  NOT NULL ,
`gw2_item_id` INT NOT NULL ,
`gw2_item_name` VARCHAR(255) NOT NULL ,
`gw2_item_type` VARCHAR(64) NOT NULL,
    `gw2_item_icon_url` VARCHAR(255) NOT NULL,
    `gw2_item_rarity` VARCHAR(64) NOT NULL,
    `gw2_item_vendor_value` INT NOT NULL ,
    `gw2_item_required_level` INT NOT NULL ,
    CONSTRAINT id_gw2_item_id UNIQUE (id,gw2_item_id),
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8mb4_unicode_ci;

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
`gw2_recipe_disciplines` VARCHAR(255) NOT NULL,
`gw2_recipe_min_rating` INT NOT NULL ,
    CONSTRAINT id_gw2_recipe_id UNIQUE (id,gw2_recipe_id),
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8mb4_unicode_ci;

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
) DEFAULT COLLATE=utf8mb4_unicode_ci;

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
) DEFAULT COLLATE=utf8mb4_unicode_ci;
