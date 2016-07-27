ALTER TABLE `ac_task_details`
CHANGE COLUMN `settings` `settings` LONGTEXT NULL DEFAULT NULL ;

ALTER TABLE `ac_task_steps`
CHANGE COLUMN `settings` `settings` LONGTEXT NULL DEFAULT NULL ;

ALTER TABLE `ac_customers`
CHANGE COLUMN `cart` `cart` LONGTEXT NULL DEFAULT NULL ;

ALTER TABLE `ac_order_downloads`
CHANGE COLUMN `attributes_data` `attributes_data` LONGTEXT NULL DEFAULT NULL ;

ALTER TABLE `ac_order_options`
CHANGE COLUMN `settings` `settings` LONGTEXT NULL DEFAULT NULL ;

ALTER TABLE `ac_product_descriptions`
CHANGE COLUMN `description` `description` LONGTEXT NULL DEFAULT NULL ;

ALTER TABLE `ac_reviews`
CHANGE COLUMN `text` `text` LONGTEXT NULL DEFAULT NULL ;

ALTER TABLE `ac_store_descriptions`
CHANGE COLUMN `description` `description` LONGTEXT NULL DEFAULT NULL ;

ALTER TABLE `ac_user_groups`
CHANGE COLUMN `permission` `permission` LONGTEXT NULL DEFAULT NULL ;

ALTER TABLE `ac_content_descriptions`
CHANGE COLUMN `content` `content` LONGTEXT NULL DEFAULT NULL ;

ALTER TABLE `ac_block_descriptions`
CHANGE COLUMN `content` `content` LONGTEXT NULL DEFAULT NULL ;

ALTER TABLE `ac_ant_messages`
CHANGE COLUMN `html` `html` LONGTEXT NULL DEFAULT NULL ;

ALTER TABLE `ac_customers`
ADD COLUMN `data` text DEFAULT null;

ALTER TABLE `ac_customers`
ADD COLUMN `salt` varchar(32) COLLATE utf8_general_ci NOT NULL DEFAULT '';



#global search speedup
ALTER TABLE `ac_settings` DROP INDEX `ac_settings_idx` ;
ALTER TABLE `ac_settings` DROP PRIMARY KEY, ADD PRIMARY KEY (`setting_id`, `store_id`, `group`, `key`);
ALTER TABLE `ac_settings` ADD FULLTEXT INDEX `ac_settings_idx` (`value` ASC);
ALTER TABLE `ac_language_definitions` DROP INDEX `ac_lang_definition_idx` ;
ALTER TABLE `ac_language_definitions` DROP PRIMARY KEY, ADD PRIMARY KEY (`language_definition_id`, `language_id`, `section`, `block`, `language_key`);
ALTER TABLE `ac_language_definitions` ADD FULLTEXT INDEX `ac_lang_definition_idx` (`language_value` ASC);
