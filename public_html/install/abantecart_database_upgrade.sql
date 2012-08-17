-- move settings around for better location

update ac_settings set `group` = 'details' where `key` like 'config_description_%';
update ac_settings set `group` = 'details' where `key` in ('store_name', 'config_url', 'config_title', 'config_meta_description', 'config_owner', 'config_address', 'store_main_email', 'config_telephone', 'config_fax','config_country_id', 'config_zone_id', 'config_storefront_language', 'admin_language', 'config_currency', 'config_currency_auto', 'config_length_class', 'config_weight_class');

update ac_settings set `group` = 'general' where `key` in ('config_catalog_limit', 'config_admin_limit', 'config_bestseller_limit', 'config_featured_limit', 'config_latest_limit', 'config_special_limit', 'config_stock_display', 'enable_reviews', 'config_download', 'config_download_status', 'config_help_links');

update ac_settings set `group` = 'checkout' where `key` in ('config_tax', 'config_tax_store', 'config_tax_customer', 'starting_invoice_id', 'invoice_prefix', 'config_customer_group_id', 'config_customer_price', 'config_customer_approval', 'config_guest_checkout', 'config_account_id', 'config_checkout_id', 'config_stock_checkout', 'config_order_status_id', 'config_stock_status_id', 'config_cart_weight', 'config_shipping_session', 'cart_ajax');

update ac_settings set `group` = 'appearance' where `key` in ('config_storefront_template', 'storefront_width', 'admin_width', 'config_logo', 'config_icon', 'config_image_thumb_width', 'config_image_thumb_height', 'config_image_category_width', 'config_image_category_height', 'config_image_product_width', 'config_image_product_height', 'config_image_additional_width', 'config_image_additional_height', 'config_image_related_width', 'config_image_related_height', 'config_image_cart_width', 'config_image_cart_height', 'config_image_grid_width', 'config_image_grid_height', 'config_image_popup_width', 'config_image_popup_height');

update ac_settings set `group` = 'api' where `key` in ('config_storefront_api_status', 'config_storefront_api_key', 'config_storefront_api_stock_check');

update ac_settings set `group` = 'system' where `key` in ('config_ssl', 'config_session_ttl', 'config_maintenance', 'encryption_key', 'enable_seo_url', 'config_compression', 'config_cache_enable', 'config_upload_max_size', 'config_error_display', 'config_error_log', 'config_debug', 'config_debug_level', 'storefront_template_debug', 'config_error_filename');

insert into ac_settings values ('', 0, 'api', 'config_storefront_api_stock_check', '0');
INSERT INTO ac_settings values ('', 0, 'appearance', 'config_grid_rows_num', '10');
INSERT INTO ac_settings values ('', 0, 'appearance', 'config_cart_ajax', '1');

alter table ac_stores add column
	`alias` varchar(15) COLLATE utf8_bin NOT NULL; 
alter table ac_stores add column	
	`status` int(1) NOT NULL;

ALTER TABLE `ac_stores` DROP COLUMN	`url`;
ALTER TABLE `ac_stores` DROP COLUMN	`ssl`;

-- populate aliases
update ac_stores set alias = LOWER(SUBSTRING(name,0,15));