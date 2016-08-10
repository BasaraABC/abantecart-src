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

ALTER TABLE `ac_customers` ADD COLUMN `salt` varchar(8) COLLATE utf8_general_ci NOT NULL DEFAULT '';

ALTER TABLE `ac_users` ADD COLUMN `salt` varchar(8) COLLATE utf8_general_ci NOT NULL DEFAULT '';
ALTER TABLE `ac_users` MODIFY `password` varchar(40);


#global search speedup
ALTER TABLE `ac_settings` DROP INDEX `ac_settings_idx` ;
ALTER TABLE `ac_settings` DROP PRIMARY KEY, ADD PRIMARY KEY (`setting_id`, `store_id`, `group`, `key`);
ALTER TABLE `ac_settings` ADD FULLTEXT INDEX `ac_settings_idx` (`value` ASC);
ALTER TABLE `ac_language_definitions` DROP INDEX `ac_lang_definition_idx` ;
ALTER TABLE `ac_language_definitions` DROP PRIMARY KEY, ADD PRIMARY KEY (`language_definition_id`, `language_id`, `section`, `block`, `language_key`);
ALTER TABLE `ac_language_definitions` ADD FULLTEXT INDEX `ac_lang_definition_idx` (`language_value` ASC);


#enable neowize
REPLACE INTO `ac_extensions`
(`type`, `key`, `category`, `status`, `priority`, `version`, `license_key`, `date_installed`, `date_modified`, `date_added`)
VALUES
('extensions', 'neowize_insights', 'extensions', 1, 1, '1.0.2', null, NOW(), NOW(), NOW() );

REPLACE INTO `ac_settings` (`group`, `key`, `value`) VALUES
('neowize_insights','neowize_insights_priority',10),
('neowize_insights','neowize_insights_date_installed', NOW()),
('neowize_insights','store_id',0),
('neowize_insights','neowize_insights_status',1);