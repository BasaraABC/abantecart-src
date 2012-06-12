<?php
/*------------------------------------------------------------------------------
  $Id$

  AbanteCart, Ideal OpenSource Ecommerce Solution
  http://www.AbanteCart.com

  Copyright © 2011 Belavier Commerce LLC

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
class ModelSaleCoupon extends Model {
	public function addCoupon($data) {
      	$this->db->query(  "INSERT INTO " . DB_PREFIX . "coupons
							SET code = '" . $this->db->escape($data['code']) . "',
								discount = '" . (float)$data['discount'] . "',
								type = '" . $this->db->escape($data['type']) . "',
								total = '" . (float)$data['total'] . "',
								logged = '" . (int)$data['logged'] . "',
								shipping = '" . (int)$data['shipping'] . "',
								date_start = '" . $this->db->escape($data['date_start']) . "',
								date_end = '" . $this->db->escape($data['date_end']) . "',
								uses_total = '" . (int)$data['uses_total'] . "',
								uses_customer = '" . (int)$data['uses_customer'] . "',
								status = '" . (int)$data['status'] . "',
								date_added = NOW()");

      	$coupon_id = $this->db->getLastId();

      	foreach ($data['coupon_description'] as $language_id => $value) {
        	$this->db->query("INSERT INTO " . DB_PREFIX . "coupon_descriptions
        	                  SET coupon_id = '" . (int)$coupon_id . "',
        	                        language_id = '" . (int)$language_id . "',
        	                        name = '" . $this->db->escape($value['name']) . "',
        	                        description = '" . $this->db->escape($value['description']) . "'");
      	}
		if (isset($data['coupon_product'])) {
      		foreach ($data['coupon_product'] as $product_id) {
        		$this->db->query(  "INSERT INTO " . DB_PREFIX . "coupons_products
        		                    SET coupon_id = '" . (int)$coupon_id . "', product_id = '" . (int)$product_id . "'");
      		}			
		}
		return $coupon_id;
	}
	
	public function editCoupon($coupon_id, $data) {

		$coupon_table_fields = array('code',
		                             'discount',
		                             'type',
		                             'total',
		                             'logged',
		                             'shipping',
		                             'date_start',
		                             'date_end',
		                             'uses_total',
		                             'uses_customer',
		                             'status');
		$update = array();
		foreach ( $coupon_table_fields as $f ) {
			if ( isset($data[$f]) )
				$update[] = "$f = '".$this->db->escape($data[$f])."'";
		}
		if ( !empty($update) ) $this->db->query("UPDATE " . DB_PREFIX . "coupons
												SET ". implode(',', $update) ."
												WHERE coupon_id = '" . (int)$coupon_id . "'");

		if ( !empty($data['coupon_description']) ) {
			foreach ($data['coupon_description'] as $language_id => $value) {
				$update = array();
				if ( isset($value['name']) ) $update[] = "name = '" . $this->db->escape($value['name']) ."'";
				if ( isset($value['description']) ) $update[] = "description = '" . $this->db->escape($value['description']) ."'";
				if ( !empty($update) ){
					$exist = $this->db->query( "SELECT *
												FROM " . DB_PREFIX . "coupon_descriptions
										        WHERE coupon_id = '" . (int)$coupon_id . "' AND language_id = '" . (int)$language_id . "' ");
					if($exist->num_rows){
						$this->db->query("UPDATE " . DB_PREFIX . "coupon_descriptions
											SET ". implode(',', $update) ."
											WHERE coupon_id = '" . (int)$coupon_id . "' AND language_id = '" . (int)$language_id . "' ");
					}else{
						$this->db->query("INSERT INTO " . DB_PREFIX . "coupon_descriptions
											SET ". implode(',', $update) .",
											 coupon_id = '" . (int)$coupon_id . "',
											 language_id = '" . (int)$language_id . "' ");
					}
				}
			}
		}

	}

	public function editCouponProducts($coupon_id, $data) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "coupons_products
						  WHERE coupon_id = '" . (int)$coupon_id . "'");

		if (isset($data['coupon_product'])) {
      		foreach ($data['coupon_product'] as $product_id) {
				$this->db->query(  "INSERT INTO " . DB_PREFIX . "coupons_products
									SET coupon_id = '" . (int)$coupon_id . "',
										product_id = '" . (int)$product_id . "'");
      		}
		}
	}
	
	public function deleteCoupon($coupon_id) {
      	$this->db->query("DELETE FROM " . DB_PREFIX . "coupons WHERE coupon_id = '" . (int)$coupon_id . "'");
      	$this->db->query("DELETE FROM " . DB_PREFIX . "coupon_descriptions WHERE coupon_id = '" . (int)$coupon_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "coupons_products WHERE coupon_id = '" . (int)$coupon_id . "'");
	}
	
	public function getCouponByID($coupon_id) {
      	$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "coupons WHERE coupon_id = '" . (int)$coupon_id . "'");
		
		return $query->row;
	}
	
	public function getCoupons($data = array()) {

		if ( !empty($data['content_language_id']) ) {
			$language_id = ( int )$data['content_language_id'];
		} else {
			$language_id = (int)$this->config->get('storefront_language_id');
		}

		$sql = "SELECT c.coupon_id, cd.name, c.code, c.discount, c.date_start, c.date_end, c.status
			FROM " . DB_PREFIX . "coupons c
				LEFT JOIN " . DB_PREFIX . "coupon_descriptions cd ON (c.coupon_id = cd.coupon_id)
			WHERE cd.language_id = '" . $language_id . "'";

		if ( isset($data['status']) && in_array($data['status'], array(0,1)) ) {
			$sql .= " AND c.status = ".$data['status'];
		}

		$sort_data = array(
			'cd.name',
			'c.code',
			'c.discount',
			'c.date_start',
			'c.date_end',
			'c.status'
		);	
			
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY cd.name";	
		}
			
		if (isset($data['order']) && (strtoupper($data['order']) == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}			

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}	
			
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}		
		
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
	
	public function getCouponDescriptions($coupon_id) {
		$coupon_description_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "coupon_descriptions WHERE coupon_id = '" . (int)$coupon_id . "'");
		
		foreach ($query->rows as $result) {
			$coupon_description_data[$result['language_id']] = array(
				'name'        => $result['name'],
				'description' => $result['description']
			);
		}
		
		return $coupon_description_data;
	}

	public function getCouponProducts($coupon_id) {
		$coupon_product_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "coupons_products WHERE coupon_id = '" . (int)$coupon_id . "'");
		
		foreach ($query->rows as $result) {
			$coupon_product_data[] = $result['product_id'];
		}
		
		return $coupon_product_data;
	}
	
	public function getTotalCoupons( $data) {
      	$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "coupons";
		if ( isset($data['status']) && in_array($data['status'], array(0,1)) ) {
			$sql .= " WHERE status = ".$data['status'];
		}
		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}		
}
?>