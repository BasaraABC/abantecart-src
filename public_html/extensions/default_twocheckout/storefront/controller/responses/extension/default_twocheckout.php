<?php
/*------------------------------------------------------------------------------
  $Id$

  AbanteCart, Ideal OpenSource Ecommerce Solution
  http://www.AbanteCart.com

  Copyright © 2011 Belavier Commerce LLC

  This source file is subject to Open Software License (OSL 3.0)
  Lincence details is bundled with this package in the file LICENSE.txt.
  It is also available at this URL:
  <http://www.opensource.org/licenses/OSL-3.0>

 UPGRADE NOTE:
   Do not edit or add to this file if you wish to upgrade AbanteCart to newer
   versions in the future. If you wish to customize AbanteCart for your
   needs please refer to http://www.AbanteCart.com for more information.
------------------------------------------------------------------------------*/
if ( !defined ( 'DIR_CORE' )) {
	header ( 'Location: static_pages/' );
}

class ControllerResponsesExtensionDefaultTwoCheckout extends AController {
	public function main() {
		$this->loadLanguage('default_twocheckout/default_twocheckout');
    	$template_data['button_confirm'] = $this->language->get('button_confirm');
		$template_data['button_back'] = $this->language->get('button_back');

		$this->load->model('checkout/order');
		
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		
		$template_data['action'] = 'https://www.2checkout.com/2co/buyer/purchase';

		$template_data['sid'] = $this->config->get('default_twocheckout_account');
		$template_data['total'] = $this->currency->format($order_info['total'], $order_info['currency'], $order_info['value'], FALSE);
		$template_data['cart_order_id'] = $this->session->data['order_id'];
		$template_data['card_holder_name'] = $order_info['payment_firstname'] . ' ' . $order_info['payment_lastname'];
		$template_data['street_address'] = $order_info['payment_address_1'];
		$template_data['city'] = $order_info['payment_city'];
		$template_data['state'] = $order_info['payment_zone'];
		$template_data['zip'] = $order_info['payment_postcode'];
		$template_data['country'] = $order_info['payment_country'];
		$template_data['email'] = $order_info['email'];
		$template_data['phone'] = $order_info['telephone'];
		
		if ($this->cart->hasShipping()) {
			$template_data['ship_street_address'] = $order_info['shipping_address_1'];
			$template_data['ship_city'] = $order_info['shipping_city'];
			$template_data['ship_state'] = $order_info['shipping_zone'];
			$template_data['ship_zip'] = $order_info['shipping_postcode'];
			$template_data['ship_country'] = $order_info['shipping_country'];
		} else {
			$template_data['ship_street_address'] = $order_info['payment_address_1'];
			$template_data['ship_city'] = $order_info['payment_city'];
			$template_data['ship_statey'] = $order_info['payment_zone'];
			$template_data['ship_zip'] = $order_info['payment_postcode'];
			$template_data['ship_country'] = $order_info['payment_country'];			
		}
		
		$template_data['products'] = array();
		
		$products = $this->cart->getProducts();

		foreach ($products as $product) {
			$template_data['products'][] = array(
				'product_id'  => $product['product_id'],
				'name'        => $product['name'],
				'description' => $product['name'],
				'quantity'    => $product['quantity'],
				'price'		  => $this->currency->format($product['price'], $order_info['currency'], $order_info['value'], FALSE)
			);
		}

		if ($this->config->get('default_twocheckout_test')) {
			$template_data['demo'] = 'Y';
		}	
		
		$template_data['lang'] = $this->session->data['language'];

		if ($this->request->get['rt'] != 'checkout/guest_step_3') {
			$template_data['return_url'] = $this->html->getSecureURL('checkout/confirm');
		} else {
			$template_data['return_url'] = $this->html->getSecureURL('checkout/guest_step_3');
		}
		
		if ($this->request->get['rt'] != 'checkout/guest_step_3') {
			$template_data['back'] = $this->html->getSecureURL('checkout/payment');
		} else {
			$template_data['back'] = $this->html->getSecureURL('checkout/guest_step_2');
		}

		$this->view->batchAssign( $template_data );
		$this->processTemplate('responses/default_twocheckout.tpl' );
	}
	
	public function callback() {
		$this->load->model('checkout/order');
		
		$order_info = $this->model_checkout_order->getOrder($this->request->post['order_number']);
		
		if (md5($this->config->get('default_twocheckout_secret') . $this->config->get('default_twocheckout_account') . $this->request->post['order_number'] . $this->request->post['total']) == $this->request->post['key']) {
			$this->model_checkout_order->confirm($this->request->post['order_number'], $this->config->get('default_twocheckout_order_status_id'));
	
			$this->redirect($this->html->getURL('checkout/success'));	
		}
	}
}