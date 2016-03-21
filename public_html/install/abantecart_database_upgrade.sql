INSERT INTO `ac_block_templates` (`block_id`, `parent_block_id`, `template`, `date_added`) VALUES
(29, 3, 'blocks/account.tpl', now() ),
(29, 6, 'blocks/account.tpl', now() );

UPDATE `ac_block_templates`
SET `parent_block_id` = 1
WHERE `template` = 'blocks/customer.tpl' AND `block_id`=31 AND `parent_block_id` = 0;

UPDATE `ac_block_templates`
SET `parent_block_id` = 8
WHERE `template` = 'blocks/donate.tpl' AND `block_id`=21 AND `parent_block_id` = 0;

UPDATE `ac_block_templates`
SET `parent_block_id` = 2
WHERE `template` = 'blocks/breadcrumbs.tpl' AND `block_id`=28 AND `parent_block_id` = 0;