<?php
/*------------------------------------------------------------------------------
  $Id$

  AbanteCart, Ideal OpenSource Ecommerce Solution
  http://www.AbanteCart.com

  Copyright © 2011-2013 Belavier Commerce LLC

  This source file is subject to Open Software License (OSL 3.0)
  License details is bundled with this package in the file LICENSE.txt.
  It is also available at this URL:
  <http://www.opensource.org/licenses/OSL-3.0>

 UPGRADE NOTE:
   Do not edit or add to this file if you wish to upgrade AbanteCart to newer
   versions in the future. If you wish to customize AbanteCart for your
   needs please refer to http://www.AbanteCart.com for more information.
------------------------------------------------------------------------------*/
if (! defined ( 'DIR_CORE' ) || !IS_ADMIN) {
	header ( 'Location: static_pages/' );
}
set_time_limit(0);
ini_set("memory_limit", "64M");
clearstatcache();

class ModelToolMigration extends Model {

	const CLASS_LOCATION = 'admin/model/tool/migration/';
	const CLASS_PREFIX = 'Migration_';
	/**
	 * @var $cart (oscmax|osc)
	 */
	protected $cartObj = null;
	protected $log = '';
	private $is_error = null;

	private $language_id;

	public function isCartSupported($cart) {
		$file = self::CLASS_LOCATION . $cart . '.php';
		if (!file_exists($file))
			return false;

		/** @noinspection PhpIncludeInspection */
		require_once($file);
		$name = self::CLASS_PREFIX . ucfirst($cart);
		if (!class_exists($name))
			return false;
		else
			return true;
	}

	public function saveStepData($vars) {
		foreach ($vars as $var) {
			$separator = "";
			if ($var == 'cart_url' && (substr($this->request->post[ $var ], -1) != "/")) {
				$separator = "/";
			}
			$this->session->data[ 'migration' ][ $var ] = !empty($this->request->post[ $var ])
					? $this->request->post[ $var ] . $separator : '';
		}
	}

	public function clearStepData() {
		$this->session->data[ 'migration' ] = array();
	}

	public function isStepData() {
		return !empty($this->session->data[ 'migration' ]);
	}

	/*
		* check cart URL, cart db info, writable directories
		* maybe check db or cart folder structure to confirm cart type
		* also  do  some php lib ( like curl, gd )
		*/
	protected function preCheck() {
		return '';
	}

	protected function addLog($msg,$type='error') {
		$class = $type=='error' ? 'warning' : 'success';
		$class = $type=='attention' ? 'attention' : $class;
		$this->log .= '<p class="'.$class.'">' . $msg . '</p>';
	}

	public function run() {
		$check = $this->preCheck();
		if (!empty($check)) return $check;


		if ($this->session->data[ 'migration' ][ 'erase_existing_data' ]) $this->clearData();

		$cart = $this->session->data[ 'migration' ][ 'cart_type' ];
		require_once self::CLASS_LOCATION . $cart . '.php';
		$name = self::CLASS_PREFIX . ucfirst($cart);
		$this->cartObj = new $name($this->session->data[ 'migration' ], $this->config);


		if ($this->session->data[ 'migration' ][ 'migrate_customers' ]) {
			if (!$this->migrateCustomers()) {
				return $this->log;
			}
		}

		if ($this->session->data[ 'migration' ][ 'migrate_products' ]) {
			if (!$this->migrateProducts()) {
				return $this->log;
			}
		}

		if ($this->session->data[ 'migration' ][ 'migrate_orders' ]) {
			if (!$this->migrateOrders()) {
				return $this->log;
			}
		}

		$this->clearStepData();
		return $this->log;
	}

	protected function import($sql) {
		foreach (explode(";\n", $sql) as $sql) {
			$sql = trim($sql);
			if ($sql) {
				$result = $this->db->query($sql,true);
				if($result===false){
					$this->addLog($this->db->error);
					return false;
				}
			}
		}
		return true;
	}

	protected function clearData() {
		// find the default language id
		$languageId = $this->getDefaultLanguageId();

		// start transaction, remove products
		$sql = "START TRANSACTION;\n";

		if ($this->session->data[ 'migration' ][ 'migrate_products' ]) {
			//categories
			$sql .= "DELETE FROM `" . DB_PREFIX . "categories`;\n";
			$sql .= "DELETE FROM `" . DB_PREFIX . "category_descriptions` WHERE language_id='".$languageId."';\n";
			$sql .= "DELETE FROM `" . DB_PREFIX . "categories_to_stores`;\n";
			$sql .= "DELETE FROM `" . DB_PREFIX . "url_aliases` WHERE query LIKE 'category_id=%';\n";
			$sql .= "DELETE FROM `" . DB_PREFIX . "resource_map` WHERE object_name = 'categories';\n";
			//products
			$sql .= "DELETE FROM `" . DB_PREFIX . "products`;\n";
			$sql .= "DELETE FROM `" . DB_PREFIX . "product_descriptions` WHERE language_id='".$languageId."';\n";
			$sql .= "DELETE FROM `" . DB_PREFIX . "products_to_categories`;\n";
			$sql .= "DELETE FROM `" . DB_PREFIX . "products_to_downloads`;\n";
			$sql .= "DELETE FROM `" . DB_PREFIX . "products_to_stores`;\n";
			$sql .= "DELETE FROM `" . DB_PREFIX . "product_options`;\n";
			$sql .= "DELETE FROM `" . DB_PREFIX . "product_option_descriptions`;\n";
			$sql .= "DELETE FROM `" . DB_PREFIX . "product_option_values`;\n";
			$sql .= "DELETE FROM `" . DB_PREFIX . "product_option_value_descriptions`;\n";
			$sql .= "DELETE FROM `" . DB_PREFIX . "product_specials`;\n";
			$sql .= "DELETE FROM `" . DB_PREFIX . "products_featured`;\n";
			$sql .= "DELETE FROM `" . DB_PREFIX . "products_related`;\n";
			$sql .= "DELETE FROM `" . DB_PREFIX . "product_tags`;\n";

			$sql .= "DELETE FROM `" . DB_PREFIX . "reviews`;\n";
			$sql .= "DELETE FROM `" . DB_PREFIX . "manufacturers`;\n";
			$sql .= "DELETE FROM `" . DB_PREFIX . "manufacturers_to_stores`;\n";
			$sql .= "DELETE FROM `" . DB_PREFIX . "url_aliases` WHERE `query` LIKE 'product_id=%';\n";
			$sql .= "DELETE FROM `" . DB_PREFIX . "products_related`;\n";
			$sql .= "DELETE FROM `" . DB_PREFIX . "resource_map` WHERE object_name = 'products';\n";
		}

		if ($this->session->data[ 'migration' ][ 'migrate_customers' ]) {
			$sql .= "DELETE FROM `" . DB_PREFIX . "customers`;\n";
			$sql .= "DELETE FROM `" . DB_PREFIX . "addresses`;\n";
		}

		if ($this->session->data[ 'migration' ][ 'migrate_orders' ]) {
			$sql .= "DELETE FROM `" . DB_PREFIX . "orders`;\n";
			$sql .= "DELETE FROM `" . DB_PREFIX . "order_downloads`;\n";
			$sql .= "DELETE FROM `" . DB_PREFIX . "order_history`;\n";
			$sql .= "DELETE FROM `" . DB_PREFIX . "order_options`;\n";
			$sql .= "DELETE FROM `" . DB_PREFIX . "order_products`;\n";
			$sql .= "DELETE FROM `" . DB_PREFIX . "order_statuses`;\n";
			$sql .= "DELETE FROM `" . DB_PREFIX . "order_totals`;\n";
		}

		$this->import($sql);
		// final commit
		$this->db->query("COMMIT;");
		$this->clearCache();
		$this->addLog('Existing data erased','success');
		return TRUE;
	}

	function clearCache() {
		$this->cache->delete('category');
		$this->cache->delete('category_description');
		$this->cache->delete('manufacturer');
		$this->cache->delete('product');
		$this->cache->delete('product_image');
		$this->cache->delete('product_option');
		$this->cache->delete('product_option_description');
		$this->cache->delete('product_option_value');
		$this->cache->delete('product_option_value_description');
		$this->cache->delete('product_to_category');
		$this->cache->delete('url_alias');
		$this->cache->delete('product_special');
		$this->cache->delete('product_discount');
	}

	protected function getDefaultLanguageId() {
		if (isset($this->language_id)) return $this->language_id;

		$code = $this->config->get('admin_language');
		$sql = "SELECT language_id
				FROM `" . DB_PREFIX . "languages`
				WHERE code = '".$code."'";
		$result = $this->db->query($sql,true);
		$languageId = 1;
		if ($result->rows) {
			foreach ($result->rows as $row) {
				$languageId = $row[ 'language_id' ];
				break;
			}
		}
		$this->language_id = $languageId;
		return $this->language_id;
	}


	protected function migrateCustomers() {
		$customers = $this->cartObj->getCustomers();

		if (!$customers) {
			$errors = $this->cartObj->getErrors();
			$class = '';
			if(!$errors){
				$errors =  $this->language->get('text_no_customers');
				$class = 'attention';
			}
			$this->addLog($errors,$class);
			return true;
		}

		foreach ($customers as $data) {
			if(!trim($data['email'])){ continue; }

			$store_id = has_value($data[ 'store_id' ]) ? (int)$data[ 'store_id' ] : (int)$this->config->get('config_store_id');
			$date_added = has_value($data[ 'date_added' ]) ? "'".$this->db->escape($data[ 'date_added' ])."'" : 'NOW()';
			$status = has_value($data[ 'status' ]) ? $data[ 'status' ] : 1;
			$approved = has_value($data[ 'approved' ]) ? $data[ 'approved' ] : 1;

			$result = $this->db->query ( "INSERT INTO " . DB_PREFIX . "customers
										SET store_id = '" . $store_id . "',
											firstname = '" . $this->db->escape($data[ 'firstname' ]) . "',
											lastname = '" . $this->db->escape($data[ 'lastname' ]) . "',
											email = '" . $this->db->escape($data[ 'email' ]) . "',
											loginname = '" . $this->db->escape($data[ 'email' ]) . "',
											telephone = '" . $this->db->escape($data[ 'telephone' ]) . "',
											fax = '" . $this->db->escape($data[ 'fax' ]) . "',
											password = '" . $this->db->escape(AEncryption::getHash($data[ 'password' ])) . "',
											newsletter = '" . $this->db->escape($data[ 'newsletter' ]) . "',
											ip = '" . $this->db->escape($data[ 'ip' ]) . "',
											customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "',
											status = '" . $status . "',
											approved = '" . $approved . "',
											date_added = " . $date_added . "",
									   true);
			if($result === false){
				$this->addLog($this->db->error);
				return null;
			}

			$customer_id = $this->db->getLastId();
			$customer_id_map[$data['customer_id']] = $customer_id;
			$data[ 'address' ] = (array)$data[ 'address' ];
			foreach ($data[ 'address' ] as $address) {
				$result = $this->db->query ( "INSERT INTO " . DB_PREFIX . "addresses
											  SET customer_id = '" . (int)$customer_id . "',
											   	  firstname = '" . $this->db->escape($address[ 'firstname' ]) . "',
													lastname = '" . $this->db->escape($address[ 'lastname' ]) . "',
													company = '" . $this->db->escape($address[ 'company' ]) . "',
													address_1 = '" . $this->db->escape($address[ 'address_1' ]) . "',
													city = '" . $this->db->escape($address[ 'city' ]) . "',
													postcode = '" . $this->db->escape($address[ 'postcode' ]) . "',
													country_id = '" . (int)$address[ 'country_id' ] . "',
													zone_id = '" . (int)$address[ 'zone_id' ] . "'", true);
				if($result === false){
					$this->addLog($this->db->error);
					return null;
				}
				$address_id = $this->db->getLastId();
			}

			$result = $this->db->query("UPDATE " . DB_PREFIX . "customers
									    SET address_id = '" . (int)$address_id . "'
								        WHERE customer_id = '" . (int)$customer_id . "'",
			                           true);
			if($result === false){
				$this->addLog($this->db->error);
				return null;
			}

		}
		$this->addLog(count($customers) . ' customers imported','success');
		return true;
	}


	protected function migrateProducts() {
		$this->load->model('tool/image');
		$rm = new AResourceManager();
		$rm->setType('image');

		$language_id = $this->getDefaultLanguageId();
		$store_id = $this->config->get('config_store_id');
		$category_id_map = array();
		$manufacturer_id_map = array();

		$language_list = $this->language->getAvailableLanguages();

		$products = $this->cartObj->getProducts();

		if (!$products) {
			$errors = $this->cartObj->getErrors();
			$class = 'error';
			if(!$errors){
				$errors =  $this->language->get('text_no_products');
				$class = 'attention';
			}
			$this->addLog($errors,$class);
			return true;
		}
		$categories = $this->cartObj->getCategories();
		if (!$categories) {
			$errors = $this->cartObj->getErrors();
			$class = 'error';
			if(!$errors){
				$errors =  $this->language->get('text_no_categories');
				$class = 'attention';
			}
			$this->addLog($errors,$class);
			return true;
		}
		$manufacturers = $this->cartObj->getManufacturers();
		if (!$manufacturers) {
			$errors = $this->cartObj->getErrors();
			$class = 'error';
			if(!$errors){
				$errors =  $this->language->get('text_no_brands');
				$class = 'attention';
			}
			$this->addLog($errors,$class);
			return true;
		}

		// import categories
		$categories = $this->cartObj->getCategories();
		$pics = 0;
		foreach ($categories as $data) {
			$data[ 'name' ] = strip_tags($data[ 'name' ]);
			$result = $this->db->query("INSERT INTO " . DB_PREFIX . "categories
                                        SET parent_id = '" . (int)$data[ 'parent_id' ] . "',
                                            sort_order = '" . (int)$data[ 'sort_order' ] . "',
                                            status = '1',
                                            date_modified = NOW(),
                                            date_added = NOW()",
			                           true);
			if($result===false){
				$this->addLog($this->db->error);
				return null;
			}
			$category_id = $this->db->getLastId();
			$category_id_map[ $data[ 'category_id' ] ] = $category_id;

			$data[ 'image' ] = trim($data[ 'image' ]);
			if (!empty($data[ 'image' ])) {
				$source = $this->session->data[ 'migration' ][ 'cart_url' ] . 'images/images_big/' . str_replace('categories/','',$data[ 'image' ]);
				$source = str_replace(' ','%20',$source);
				$src_exists = @getimagesize($source);
				if(!$src_exists){
					$source = $this->session->data[ 'migration' ][ 'cart_url' ] . 'images/' . $data[ 'image' ];
					$source = str_replace(' ','%20',$source);
				    $src_exists = @getimagesize($source);
				}
				if ( $src_exists ) {
					$data['image'] = 'data/'. pathinfo($data['image'], PATHINFO_BASENAME);
					$target = DIR_RESOURCE.'image/'. pathinfo($data['image'], PATHINFO_BASENAME);
					if (($file = $this->downloadFile($source)) === false) {
						$this->is_error = true;
						$this->addLog(" Category {$data['name']} File  " . $source . " couldn't be uploaded.");
					}

					if (!$this->is_error){
						if(!file_exists(DIR_RESOURCE.'image/')){
							mkdir(DIR_RESOURCE.'image/',0777);
						}
						if (!$this->writeToFile($file, $target)) {
							$this->is_error = true;
							$this->addLog("Cannot create Category {$data['name']} ({$source})  file " . $target . " in resource/image folder ");
						}else{
							// increase picture counter
							$pics++;
						}
						$resource = array( 'language_id' => $this->config->get('storefront_language_id'),
										   'name' => array(),
										   'title' => '',
										   'description' => '',
										   'resource_path' => pathinfo($data['image'], PATHINFO_BASENAME),
										   'resource_code' => '');
						$filename = pathinfo($data['image'], PATHINFO_BASENAME);
						foreach($language_list as $lang){
							$resource['name'][$lang['language_id']] = $filename;
						}
						$resource_id = $rm->addResource($resource);
						if ( $resource_id ) {
							$rm->mapResource('categories', $category_id, $resource_id);
						} else {
							$this->addLog($this->db->error);
							return null;
						}
					}
				}
			}
			$result = $this->db->query( "INSERT INTO " . DB_PREFIX . "category_descriptions
					 				     SET category_id = '" . (int)$category_id . "',
										     language_id = '" . (int)$language_id . "',
										     name = '" . $this->db->escape($data[ 'name' ]) . "',
										     description = '" . $this->db->escape($data[ 'description' ]) . "'",
			                            true);
			if($result===false){
				$this->addLog($this->db->error);
				return null;
			}

			$result = $this->db->query( "INSERT INTO " . DB_PREFIX . "categories_to_stores
										   (category_id,store_id)
										 VALUES ('" . (int)$category_id . "','" . (int)$store_id . "')", true);
			if($result === false){
				$this->addLog($this->db->error);
				return null;
			}
		}

		//update parent id according to new map
		$query = $this->db->query("SELECT category_id, parent_id FROM " . DB_PREFIX . "categories ");
		foreach ($query->rows as $result) {
			if (empty($category_id_map[ $result[ 'parent_id' ] ])) continue;

				$result = $this->db->query("UPDATE " . DB_PREFIX . "categories
										    SET parent_id = '" . $category_id_map[ $result[ 'parent_id' ] ] . "'
										    WHERE category_id = '" . (int)$result[ 'category_id' ] . "'", true);
				if($result === false){
					$this->addLog($this->db->error);
					return null;
				}
		}
		$this->addLog(count($categories) . ' categories imported ('. $pics . ' pictures)','success');


		// import manufacturers
		$pics = 0;
		foreach ($manufacturers as $data) {

			$result = $this->db->query("INSERT INTO " . DB_PREFIX . "manufacturers
                                        SET name = '" . $this->db->escape($data[ 'name' ]) . "'", true);
			if($result === false){
				$this->addLog($this->db->error);
				return null;
			}

			$manufacturer_id = $this->db->getLastId();
			$manufacturer_id_map[ $data[ 'manufacturer_id' ] ] = $manufacturer_id;
			$data[ 'image' ] = trim($data[ 'image' ]);
			if (!empty($data[ 'image' ])) {
				$source = $this->session->data[ 'migration' ][ 'cart_url' ] . 'images/images_big/' . str_replace('manufacturers/','',$data[ 'image' ]);
				$source = str_replace(' ','%20',$source);
				$src_exists = @getimagesize($source);
				if(!$src_exists){
					$source = $this->session->data[ 'migration' ][ 'cart_url' ] . 'images/' . $data[ 'image' ];
					$source = str_replace(' ','%20',$source);
				    $src_exists = @getimagesize($source);
				}
				if ( $src_exists ) {
					$data['image'] = 'data/'. pathinfo($data['image'], PATHINFO_BASENAME);
					$target = DIR_RESOURCE.'image/'. pathinfo($data['image'], PATHINFO_BASENAME);
					if (($file = $this->downloadFile($source)) === false) {
						$this->is_error = true;
						$this->addLog(" Brand {$data['name']} File " . $source . " couldn't be uploaded.");
					}

					if (!$this->is_error){
						if(!file_exists(DIR_RESOURCE.'image/')){
							mkdir(DIR_RESOURCE.'image/',0777);
						}

						if (!$this->writeToFile($file, $target)) {
							$this->is_error = true;
							$this->addLog("Couldn't create Manufacturer {$data['name']} ({$source}) file " . $target . " in image folder ");
						}else{
							$pics++;
						}
					}

					if (!$this->is_error){
						$resource = array( 'language_id' => $this->config->get('storefront_language_id'),
										   'name' => array(),
										   'title' => '',
										   'description' => '',
										   'resource_path' => pathinfo($data['image'], PATHINFO_BASENAME),
										   'resource_code' => '');
						$filename = pathinfo($data['image'], PATHINFO_BASENAME);
						foreach($language_list as $lang){
							$resource['name'][$lang['language_id']] = $filename;
						}
						$resource_id = $rm->addResource($resource);
						if ( $resource_id ) {
							$rm->mapResource('manufacturers', $manufacturer_id, $resource_id);
						} else {
							$this->addLog($this->db->error);
							return null;
						}
					}
				}
			}
			$result = $this->db->query("INSERT INTO " . DB_PREFIX . "manufacturers_to_stores
                                        SET manufacturer_id = '" . (int)$manufacturer_id . "', store_id = '" . (int)$store_id . "'", true);
			if($result === false){
				$this->addLog($this->db->error);
				return null;
			}
		}

		$this->addLog(count($manufacturers) . ' brands imported ('. $pics . ' pictures)','success');

		// import products

		$pics = 0;
		$i=1;
		foreach ($products as $data) {

			$data[ 'manufacturer_id' ] = empty($manufacturer_id_map[ $data[ 'manufacturer_id' ] ]) ? '' : $manufacturer_id_map[ $data[ 'manufacturer_id' ] ];
			$date_added = has_value($data[ 'date_added' ]) ? "'".$this->db->escape($data[ 'date_added' ])."'" : 'NOW()';
			$date_modified = has_value($data[ 'date_modified' ]) ? "'".$this->db->escape($data[ 'date_modified' ])."'" : 'NOW()';

			$result = $this->db->query("INSERT INTO " . DB_PREFIX . "products
										SET model = '" . $this->db->escape($data[ 'model' ]) . "',
											sku	= '" . $this->db->escape($data[ 'sku' ]) . "',
											location = '" . $this->db->escape($data[ 'location' ]) . "',
											quantity = '" . (int)$data[ 'quantity' ] . "',
											stock_status_id = '" . (int)$data[ 'stock_status_id' ] . "',
											date_available = '" . $this->db->escape($data[ 'date_available' ]) . "',
											manufacturer_id = '" . (int)$data[ 'manufacturer_id' ] . "',
											shipping = '" . (int)$data[ 'shipping' ] . "',
											price = '" . (float)$data[ 'price' ] . "',
											weight = '" . (float)$data[ 'weight' ] . "',
											weight_class_id = '" . (int)$data[ 'weight_class_id' ] . "',
											length = '" . (float)$data[ 'length' ] . "',
											length_class_id = '" . (int)$data[ 'length_class_id' ] . "',
											height = '" . (float)$data[ 'height' ] . "',
											status = '" . (int)$data[ 'status' ] . "',
											viewed = '" . (int)$data[ 'viewed' ] . "',
											minimum = '" . (int)$data[ 'minimum' ] . "',
											subtract = '" . (int)$data[ 'subtract' ] . "',
											tax_class_id = '" . (int)$data[ 'tax_class_id' ] . "',
											sort_order = '" . (int)$data[ 'sort_order' ] . "',
											date_added = " . $date_added . ",
											date_modified = " . $date_modified . "",
			                           true);

			if($result === false){
				$this->addLog($this->db->error);
				return null;
			}

			$product_id = $this->db->getLastId();
			$product_id_map[ $data[ 'product_id' ] ] = $product_id;
			$product_prices_map[ $data[ 'product_id' ] ] = (float)$data[ 'price' ];

			$data[ 'image' ] = trim($data[ 'image' ]);
			if (!empty($data[ 'image' ])) {


				$images = array();
				$image_filename = $basename = pathinfo($data[ 'image' ],PATHINFO_FILENAME);
				$ext = pathinfo($data[ 'image' ],PATHINFO_EXTENSION);
				if(in_array(substr($basename,-2),array('_1','_2','_3','_4','_5',))){
					$basename = substr($basename,-2);
					$images[] = $image_filename.'.'.$ext;
				}else{
					$images[] = $basename.'.'.$ext;
				}
				$i=1;
				while($i<6){
					if(!in_array($basename.'_'.$i,$images)){
						$images[] = $basename.'_'.$i.'.'.$ext;
					}
					$i++;
				}

				foreach($images as $image_name){
					$source = $this->session->data[ 'migration' ][ 'cart_url' ] . 'images/images_big/' . $image_name;
					$source = str_replace(' ','%20',$source);
					$src_exists = @getimagesize($source);
					if ( $src_exists ) {
						$data['image'] = 'data/'. pathinfo($image_name, PATHINFO_BASENAME);
						$target = DIR_RESOURCE.'image/'. pathinfo($image_name, PATHINFO_BASENAME);
						if (($file = $this->downloadFile($source)) === false) {
							$this->is_error = true;
							$this->addLog(" Product {$data['name']} File " . $source . " couldn't be uploaded.");
						}

						if (!$this->is_error){
							if (!$this->writeToFile($file, $target)) {
								$this->is_error = true;
								$this->addLog("Couldn't create product {$data['name']} ({$source}) file " . $target . " in image folder ");
							}else{
								$pics++;
							}
						}
						if (!$this->is_error){
							$resource = array( 'language_id' => $this->config->get('storefront_language_id'),
											   'name' => array(),
											   'title' => '',
											   'description' => '',
											   'resource_path' => pathinfo($image_name, PATHINFO_BASENAME),
											   'resource_code' => '');

							$filename = pathinfo($data['image'], PATHINFO_BASENAME);
							foreach($language_list as $lang){
								$resource['name'][$lang['language_id']] = $filename;
							}

							$resource_id = $rm->addResource($resource);

							if ( $resource_id ) {

								$rm->mapResource('products', $product_id, $resource_id);
							} else {
								$this->addLog($this->db->error);
								return null;
							}
						}

					} else {
						$this->addLog(" Product {$image_name} File " . $source . " couldn't be accessed.");
					}
				$i++;
				}
			}

			$result = $this->db->query ( "INSERT INTO " . DB_PREFIX . "product_descriptions
										  SET product_id = '" . (int)$product_id . "',
										  	  language_id = '" . (int)$language_id . "',
											  name = '" . $this->db->escape($data[ 'name' ]) . "',
											  meta_keywords = '" . $this->db->escape($data[ 'meta_keyword' ]) . "',
											  meta_description = '" . $this->db->escape($data[ 'meta_description' ]) . "',
											  description = '" . $this->db->escape($data[ 'description' ]) . "'", true);
			if($result === false){
				$this->addLog($this->db->error);
				return null;
			}

			$result = $this->db->query ( "INSERT INTO " . DB_PREFIX . "products_to_stores
			                              SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "'", true);
			if($result === false){
				$this->addLog($this->db->error);
				return null;
			}

			if (isset($data[ 'product_category' ])) {
				foreach ($data[ 'product_category' ] as $category_id) {
					if(!(int)$category_id_map[ $category_id ]) continue;

					$result = $this->db->query("INSERT INTO " . DB_PREFIX . "products_to_categories
                                                    (product_id,category_id)
                                                VALUES ('" . (int)$product_id . "', '" . (int)$category_id_map[ $category_id ] . "')", true);
					if($result === false){
						$this->addLog($this->db->error);
						return null;
					}
				}
			}

			// products review
			if($data['reviews']){

				foreach($data['reviews'] as $review){

					if(!(int)$product_id_map[ (int)$review['product_id'] ]){
						continue;
					}

					$sql = "INSERT INTO " . DB_PREFIX . "reviews
	                                             (`product_id`, `customer_id`, `author`,
	                                             `text`,`rating`,`status`,`date_added`,`date_modified`)
	                                            VALUES ('" . (int)$product_id_map[ $review['product_id'] ] . "',
	                                            		'" . (int)$customer_id_map[ $data['review_customer_id'] ] . "',
	                                            		'" .  $this->db->escape($review['review_author']) . "',
	                                            		'" .  $this->db->escape($review['review_text']) . "',
	                                            		'" .  (int)$review['review_rating'] . "',
	                                            		'" .  (int)$review['review_status'] . "',
														'" .  $this->db->escape($review['review_date_added']) . "',
														'" .  $this->db->escape($review['review_date_modified']) . "'
	                                            		);";

					$result = $this->db->query($sql, true);
					if($result === false){
						$this->addLog($this->db->error);
					}
				}
			}

		}//end of product foreach

		if ( method_exists($this->cartObj,'getProductOptions') ) {
			$options = $this->cartObj->getProductOptions();
		}

		foreach($options['product_options'] as $product_option){
			//options
			$sql = "INSERT INTO ".DB_PREFIX."product_options (product_id, sort_order,status,element_type)
					VALUES('".$product_id_map[$product_option['product_id']]."','".$product_option['sort_order']."','1','".$this->db->escape($product_option['element_type'])."');";
			$result = $this->db->query($sql);
			if($result === false){
				$this->addLog($this->db->error);
				return null;
			}
			$product_option_id = $this->db->getLastId();
			$key = $product_option['product_id'].'_'.$product_option[ 'product_option_id' ];
			$key = empty($product_option[ 'product_option_id' ]) ? $product_option['product_id'].'_new_'.$product_option[ 'products_text_attributes_id'] : $key;

			$product_option_id_map[ $key ] = $product_option_id;

			//option description
			$sql = "INSERT INTO ".DB_PREFIX."product_option_descriptions (product_option_id, language_id, product_id, name)
					VALUES('".$product_option_id."', 1, '".$product_id_map[$product_option['product_id']]."','".$this->db->escape($product_option['product_option_name'])."');";
			$result = $this->db->query($sql);
			if($result === false){
				$this->addLog($this->db->error);
				return null;
			}

		}

		//option value
		foreach($options['product_option_values'] as $product_option_value){
			$opt_price = 0;
			if($product_option_value['price_prefix']=='+'){
				$opt_price = $product_option_value['price'];
			}else if($product_option_value['price_prefix']=='-'){
				$opt_price = '-'.$product_option_value['price'];
			}
			$key = $product_option_value['product_id'].'_'.$product_option_value[ 'product_option_id' ];
			if($product_option_value[ 'products_text_attributes_id']){
				$key = $product_option_value['product_id'].'_new_'.$product_option_value[ 'products_text_attributes_id'];
			}
			$sql = "INSERT INTO ".DB_PREFIX."product_option_values (product_id,
																product_option_id,
																price,
																prefix)
					VALUES('".$product_id_map[$product_option_value['product_id']]."',
					'".$product_option_id_map[ $key ]."',
					'".$opt_price."',
					'€');";
			$result = $this->db->query($sql);
			if($result === false){
				$this->addLog($this->db->error);
				return null;
			}
			$product_option_value_id = $this->db->getLastId();
			$product_option_value_id_map[ $product_option_value[ 'product_option_value_id' ] ] = $product_option_value_id;

			$sql = "INSERT INTO ".DB_PREFIX."product_option_value_descriptions (
																product_option_value_id,
																language_id,
																product_id,
																name)
					VALUES(
					'".$product_option_value_id."',
					'1',
					'".$product_id_map[$product_option_value['product_id']]."',
					'".$this->db->escape($product_option_value['product_option_value_name'])."');";
			$this->db->query($sql);
			if($result === false){
				$this->addLog($this->db->error);
				return null;
			}


		}

		$this->addLog(count($products) . ' products imported ('. $pics . ' pictures)','success');
		return true;

	}

	protected function migrateOrders() {
		$orders = $this->cartObj->getOrders();
		if (!$orders) {
			$errors = $this->cartObj->getErrors();
			$class = '';
			if(!$errors){
				$errors =  $this->language->get('text_no_orders');
				$class = 'attention';
			}
			$this->addLog($errors,$class);
			$this->addLog($errors);
			return true;
		}
		return true;
	}

	private function _get($uri) {
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $uri);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$response = new stdClass();

		$response->body = curl_exec($ch);
		$response->http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$response->content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
		$response->content_length = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);

		curl_close($ch);

		return $response;
	}

	private function downloadFile($path) {
		$file = $this->_get($path);
		if ($file->http_code == 200) {
			return $file;
		}
		return false;

	}

	function writeToFile($data, $file) {
		if (is_dir($file)) return null;
		if (function_exists("file_put_contents")) {
			$bytes = @file_put_contents($file, $data->body);
			return $bytes == $data->content_length;
		}

		$handle = @fopen($file, 'w+');
		$bytes = fwrite($handle, $data->body);
		@fclose($handle);

		return $bytes == $data->content_length;
	}

	public function getCartList(){
		$result = array();
		$files = glob(DIR_ROOT.'/admin/model/tool/migration/*',GLOB_NOSORT);
		if($files){
			foreach($files as $file){
				$cartname = pathinfo($file, PATHINFO_FILENAME);
				if(strtolower($cartname) == 'interface_migration') continue;

				require_once($file);
				$name = self::CLASS_PREFIX . ucfirst($cartname);
				$cart = new $name('', '');
				$result[$cartname] = $cart->getName().$cart->getVersion();
			}
		}

		asort(&$result);
		return $result;
	}
}
