<?php
/*------------------------------------------------------------------------------
   $Id$

   AbanteCart, Ideal OpenSource Ecommerce Solution
   http://www.AbanteCart.com

   Copyright © 2011-2015 Belavier Commerce LLC

   This source file is subject to Open Software License (OSL 3.0)
   Lincence details is bundled with this package in the file LICENSE.txt.
   It is also available at this URL:
   <http://www.opensource.org/licenses/OSL-3.0>

  UPGRADE NOTE:
	Do not edit or add to this file if you wish to upgrade AbanteCart to newer
	versions in the future. If you wish to customize AbanteCart for your
	needs please refer to http://www.AbanteCart.com for more information.
 ------------------------------------------------------------------------------*/
if ( !IS_ADMIN || !defined ( 'DIR_CORE' )) {
	header ( 'Location: static_pages/' );
}

class ControllerResponsesExtensionDefaultTextMarketer extends AController {

	public $data = array();

	public function test() {

		$this->loadLanguage('default_textmarketer/default_textmarketer');
		$error_message = '';
		$to = $this->request->get['to'];
		if(!$to){
			$error_message = $this->language->get('error_empty_test_phone_number');
		}
		$to = '+'.ltrim($to,'+');

		if(!$error_message){

			include_once(DIR_EXT . 'default_textmarketer/core/lib/textmarketer.php');
			$result = null;
			try{
				include_once('textmarketer.php');
				$sender = new TextMarketer($this->config->get('default_textmarketer_username'),
						$this->config->get('default_textmarketer_password'),
						$this->config->get('default_textmarketer_test'));

				$originator = $this->config->get('default_textmarketer_originator');
				$originator = preg_replace('/[^a-zA-z]/', '', $originator);
				$result = $sender->send('test message', $to, $originator);

			} catch(AException $e){	}


			if (!$result){
				$error_message = $this->language->get('text_see_log');
			}


		}


		$json = array();

		if(!$error_message){
			$json['message'] = $this->language->get('text_connection_success');
			$json['error'] = false;
		}else{
			$json['message'] = "Connection to TextMarketer server can not be established.<br>" . $error_message .".<br>Check your server configuration or contact your hosting provider.";
			$json['error'] = true;
		}

		$this->load->library('json');
		$this->response->setOutput(AJson::encode($json));

	}


}