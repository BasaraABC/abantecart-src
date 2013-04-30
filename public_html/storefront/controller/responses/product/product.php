<?php
/*------------------------------------------------------------------------------
  $Id$

  AbanteCart, Ideal OpenSource Ecommerce Solution
  http://www.AbanteCart.com

  Copyright 2011 Belavier Commerce LLC

  This source file is subject to Open Software License (OSL 3.0)
  License details is bundled with this package in the file LICENSE.txt.
  It is also available at this URL:
  <http://www.opensource.org/licenses/OSL-3.0>

 UPGRADE NOTE:
   Do not edit or add to this file if you wish to upgrade AbanteCart to newer
   versions in the future. If you wish to customize AbanteCart for your
   needs please refer to http://www.AbanteCart.com for more information.
------------------------------------------------------------------------------*/
if (!defined('DIR_CORE')) {
	header('Location: static_pages/');
}
/** @noinspection PhpUndefinedClassInspection */
class ControllerResponsesProductProduct extends AController {

	public $data = array();

	public function main() {
	}

	public function is_group_option() {
		//init controller data
		$this->extensions->hk_InitData($this, __FUNCTION__);

		$this->loadModel('catalog/product');
		$group_options = $this->model_catalog_product->getProductGroupOptions(
			$this->request->get['product_id'],
			$this->request->get['option_id'],
			$this->request->get['option_value_id']
		);

		//$product_price = $this->request->get['product_price'];
		//$tax_class_id = $this->request->get['tax_class_id'];

		foreach ($group_options as $option_id => $option_values) {
			foreach ($option_values as $option_value_id => $option_value) {
				$name = $option_value['name'];
				/*if ( (float)$option_value['price'] ) {
					if ( $option_value['prefix'] == '%' ) {
						$name .= ' '. $this->currency->format(
							$this->tax->calculate(
								($product_price * $option_value['price'] / 100),
								$tax_class_id,
								$this->config->get('config_tax')
							)
						);
					} else {
						$name .= ' '. $this->currency->format(
							$this->tax->calculate(
								$option_value['price'],
								$tax_class_id,
								$this->config->get('config_tax')
							)
						);
					}
				}*/
				$this->data['group_option'][$option_id][$option_value_id] = $name;
			}
		}

		//init controller data
		$this->extensions->hk_UpdateData($this, __FUNCTION__);

		$this->load->library('json');
		$this->response->setOutput(AJson::encode($this->data['group_option']));
	}

	/*
	 * Load images for product option
	 * */
	public function get_option_resources() {
		//init controller data
		$this->extensions->hk_InitData($this, __FUNCTION__);
		$attribute_value_id = (int)$this->request->get['attribute_value_id'];
		$output = array();
		if ($attribute_value_id) {
			$resource = new AResource('image');

			// main product image
			$sizes = array('main' => array('width' => $this->config->get('config_image_popup_width'),
				'height' => $this->config->get('config_image_popup_height')),
				'thumb' => array('width' => $this->config->get('config_image_thumb_width'),
					'height' => $this->config->get('config_image_thumb_height')));

			$output['main'] = $resource->getResourceAllObjects('product_option_value', $attribute_value_id, $sizes, 1, false);

			if (!$output['main']) {
				unset($output['main']);
			}

			// additional images
			$sizes = array('main' => array('width' => $this->config->get('config_image_popup_width'),
				'height' => $this->config->get('config_image_popup_height')),
				'thumb' => array('width' => $this->config->get('config_image_additional_width'),
					'height' => $this->config->get('config_image_additional_height')));

			$output['images'] = $resource->getResourceAllObjects('product_option_value', $attribute_value_id, $sizes, 0, false);
			if (!$output['images']) {
				unset($output['images']);
			}

		}


		//init controller data
		$this->extensions->hk_UpdateData($this, __FUNCTION__);

		$this->load->library('json');
		$this->response->setOutput(AJson::encode($output));
	}

	public function addToCart() {
		//init controller data
		$this->extensions->hk_InitData($this, __FUNCTION__);

		$this->cart->add($this->request->get['product_id'], 1);


		$display_totals = $this->cart->buildTotalDisplay();

		$dispatch = $this->dispatch('responses/product/product/get_cart_details',array($display_totals));

		$output['cart_details'] = $dispatch->dispatchGetOutput();
		$output['item_count'] = $this->cart->countProducts();

		$output['total'] = $this->currency->format($display_totals['total']);
		//init controller data
		$this->extensions->hk_UpdateData($this, __FUNCTION__);

		$this->load->library('json');
		$this->response->setOutput(AJson::encode($output));
	}

	public function get_cart_details($totals){

		if(!$this->view->isTemplateExists('responses/checkout/cart_details.tpl')){
			return '';
		}
		$resource = new AResource('image');
		foreach ($this->cart->getProducts() as $result) {
			$option_data = array();

			$thumbnail = $resource->getMainThumb('products',
				$result['product_id'],
				$this->config->get('config_image_product_width'),
				$this->config->get('config_image_product_height'), true);

			foreach ($result['option'] as $option) {
				$option_data[] = array(
					'name' => $option['name'],
					'value' => $option['value']
				);
			}

			$qty += $result['quantity'];

			$this->data['products'][] = array(
				'key' => $result['key'],
				'name' => $result['name'],
				'option' => $option_data,
				'quantity' => $result['quantity'],
				'stock' => $result['stock'],
				'price' => $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'))),
				'href' => $this->html->getSEOURL('product/product', '&product_id=' . $result['product_id']),
				'thumb' => $thumbnail,
			);
		}


		$this->data['totals'] = $totals['total_data'];
		$this->data['subtotal'] = $this->currency->format($this->tax->calculate($totals['total'], $result['tax_class_id'], $this->config->get('config_tax')));
		$this->data['taxes'] = $totals['taxes'];

		$this->view->batchAssign($this->data);
		$this->processTemplate('responses/checkout/cart_details.tpl');
	}


	/*
	 * Calculate product total based on options selected
	 * */
	public function calculateTotal() {
		//init controller data
		$this->extensions->hk_InitData($this, __FUNCTION__);

		$output = array();
		//can not show price
		if (!$this->config->get('config_customer_price') && !$this->customer->isLogged()) {
			return $output;
		}

		if (has_value($this->request->post['product_id']) && is_numeric($this->request->post['product_id'])) {
			$product_id = $this->request->post['product_id'];
			if (isset($this->request->post['option'])) {
				$option = $this->request->post['option'];
			} else {
				$option = array();
			}

			if (isset($this->request->post['quantity'])) {
				$quantity = $this->request->post['quantity'];
			} else {
				$quantity = 1;
			}
			$result = $this->cart->buildProductDetails($product_id, $quantity, $option);
			$output['total'] = $this->tax->calculate(
				$result['total'],
				$result['tax_class_id'],
				(int)$this->config->get('config_tax')
			);
			$output['price'] = $this->tax->calculate(
				$result['price'],
				$result['tax_class_id'],
				(int)$this->config->get('config_tax')
			);
			$output['total'] = $this->currency->format($output['total']);
			$output['price'] = $this->currency->format($output['price']);
		}

		//init controller data
		$this->extensions->hk_UpdateData($this, __FUNCTION__);

		$this->load->library('json');
		$this->response->setOutput(AJson::encode($output));
	}
}