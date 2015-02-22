ALTER TABLE `ac_customers`
  ADD `wishlist` text COLLATE utf8_general_ci;

ALTER TABLE `ac_banner_stat`
ADD COLUMN `rowid` INT(11) NOT NULL AUTO_INCREMENT FIRST,
ADD PRIMARY KEY (`rowid`);

ALTER TABLE `ac_custom_lists`
ADD COLUMN `rowid` INT(11) NOT NULL AUTO_INCREMENT FIRST,
ADD PRIMARY KEY (`rowid`);

ALTER TABLE `ac_dataset_column_properties`
ADD COLUMN `rowid` INT(11) NOT NULL AUTO_INCREMENT FIRST,
ADD PRIMARY KEY (`rowid`);

ALTER TABLE `ac_dataset_properties`
ADD COLUMN `rowid` INT(11) NOT NULL AUTO_INCREMENT FIRST,
ADD PRIMARY KEY (`rowid`);

ALTER TABLE `ac_products_featured` ADD PRIMARY KEY (`product_id`);

ALTER TABLE `ac_resource_map`
DROP INDEX `group_id` ,
DROP INDEX ac_resource_map_idx,
ADD PRIMARY KEY (`resource_id`, `object_name`, `object_id`);

UPDATE `ac_settings`
SET `group` = 'details'
WHERE `group` = 'system' AND `key` = 'config_ssl';