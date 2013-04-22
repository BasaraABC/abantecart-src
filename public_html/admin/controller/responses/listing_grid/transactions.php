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
if (!defined('DIR_CORE') || !IS_ADMIN) {
	header('Location: static_pages/');
}
class ControllerResponsesListingGridTransactions extends AController {
	private $error = array();

	public function main() {

		//init controller data
		$this->extensions->hk_InitData($this, __FUNCTION__);

		$this->loadLanguage('sale/customer');
		$this->loadModel('sale/customer');
		$this->load->library('json');


		$page = $this->request->post[ 'page' ]; // get the requested page
		$limit = $this->request->post[ 'rows' ]; // get how many rows we want to have into the grid
		$sidx = $this->request->post[ 'sidx' ]; // get index row - i.e. user click to sort
		$sord = $this->request->post[ 'sord' ]; // get the direction

		$data = array(
			'sort' => $sidx,
			'order' => $sord,
			'start' => ($page - 1) * $limit,
			'limit' => $limit,
			'customer_id' => (int)$this->request->get['customer_id']
		);

		if ( has_value($this->request->get[ 'user' ]) )
			$data['filter']['user'] = $this->request->get[ 'user' ];
		if ( has_value($this->request->get['credit']) )
			$data['filter']['credit'] = $this->request->get[ 'credit' ];
		if ( has_value($this->request->get['debit']) )
			$data['filter']['debit'] = $this->request->get[ 'debit' ];
		if ( has_value($this->request->get['type']) )
			$data['filter']['type'] = $this->request->get[ 'type' ];
		if ( has_value($this->request->get['date_start']) )
			$data['filter']['date_start'] = dateDisplay2ISO($this->request->get[ 'date_start' ]);
		if ( has_value($this->request->get['date_end']) )
			$data['filter']['date_end'] = dateDisplay2ISO($this->request->get[ 'date_end' ]);

		$allowedFields = array( 'user', 'credit', 'debit', 'type', 'date_start', 'date_end' );
		if ( isset($this->request->post[ '_search' ]) && $this->request->post[ '_search' ] == 'true') {
			$searchData = AJson::decode(htmlspecialchars_decode($this->request->post[ 'filters' ]), true);

			foreach ($searchData[ 'rules' ] as $rule) {
				if (!in_array($rule[ 'field' ], $allowedFields)) continue;
				$data['filter'][ $rule[ 'field' ] ] = $rule[ 'data' ];
			}
		}

		$total = $this->model_sale_customer->getTotalTransactions($data);

		if ($total > 0) {
			$total_pages = ceil($total / $limit);
		} else {
			$total_pages = 0;
		}

		$response = new stdClass();
		$response->page = $page;
		$response->total = $total_pages;
		$response->records = $total;

		$results = $this->model_sale_customer->getTransactions($data);
		$i = 0;
		foreach ($results as $result) {
			$response->rows[ $i ][ 'id' ] = $result[ 'transaction_id' ];
			$response->rows[ $i ][ 'cell' ] = array(
				$result[ 'create_date' ],
				$result[ 'user' ],
				$result[ 'credit' ],
				$result[ 'debit' ],
				$result[ 'type' ],
			);
			$i++;
		}

		//update controller data
		$this->extensions->hk_UpdateData($this, __FUNCTION__);


		$this->response->setOutput(AJson::encode($response));
	}


	private function _validateForm($data=array()) {

		$output['credit'] = (float)$data['credit'];
		$output['debit'] = (float)$data['debit'];

		if($data['type'][1]){
			$output['type'] = trim($data['type'][1]);
			$this->cache->delete('transaction_types');
		}else{
			$output['type'] = trim($data['type'][0]);
		}

		if(!$output['type']){
			$this->error[] = $this->language->get('error_transaction_type');
		}
		$output['type'] = htmlentities($data['type'],ENT_QUOTES,'UTF-8');
		$output['comment'] = htmlentities($data['comment'],ENT_QUOTES,'UTF-8');
		$output['description'] = htmlentities($data['description'],ENT_QUOTES,'UTF-8');

		return $output;
	}


	public function saveTransaction(){
		//init controller data
		$this->extensions->hk_InitData($this, __FUNCTION__);

		if (!$this->user->canModify('listing_grid/transactions') || $this->request->server['REQUEST_METHOD']!='POST') {
					$error = new AError('');
					return $error->toJSONResponse('NO_PERMISSIONS_402',
						array( 'error_text' => sprintf($this->language->get('error_permission_modify'), 'listing_grid/transactions'),
							'reset_value' => true
						));
		}

		$this->loadLanguage('sale/customer');
		$this->loadModel('sale/customer');
		$this->load->library('json');

		//check is data valid
		$valid_data = $this->_validateForm();
		$response = $this->get_Transaction_Info($this->request->post);
		if(!$this->errors){
			$valid_data['customer_id'] = $this->request->get['customer_id'];
			$response['transaction_id'] = $this->model_sale_customer->addTransaction($valid_data);
			$response['success'] = $this->language->get('text_transaction_success');
		}else{
			$response['error'] = implode('<br>',$this->errors);
		}

		//update controller data
		$this->extensions->hk_UpdateData($this, __FUNCTION__);

		$this->response->setOutput(AJson::encode($response));
	}



	public function get_Transaction_Info($default_data_set=array()){
		if(!is_array($default_data_set)){
			$default_data_set = array();
		}
		//init controller data
		$this->extensions->hk_InitData($this, __FUNCTION__);
		$this->load->library('json');
		$this->loadLanguage('sale/customer');
		$edit = 1;
		if (!$this->user->canAccess('listing_grid/transactions')) {
					$error = new AError('');
					return $error->toJSONResponse('NO_PERMISSIONS_402',
						array( 'error_text' => sprintf($this->language->get('error_permission_access'), 'listing_grid/transactions'),
							'reset_value' => true
						));
		}


		if((int)$this->request->get['transaction_id'] || isset($default_data_set['transaction_id'])){
			$edit = 0;
			if (!$this->user->canModify('listing_grid/transactions')) {
						$error = new AError('');
						return $error->toJSONResponse('NO_PERMISSIONS_402',
							array( 'error_text' => sprintf($this->language->get('error_permission_modify'), 'listing_grid/transactions'),
								'reset_value' => true
							));
			}
		}


		$this->loadModel('sale/customer');

		$info = !$default_data_set ? $this->model_sale_customer->getTransaction($this->request->get['transaction_id']) : $default_data_set;

		if($edit){
			$form = new AForm();
			$form->setForm(array(
				'form_name' => 'transaction_form',
			));

			$response['fields'][] = array('text' => $this->language->get('text_option_credit'),
										'field' => (string)$form->getFieldHtml(array(
																			'type' => 'input',
																			'name' => 'credit',
																			'value' => $info['credit'],
																			'style' => 'large-field'
																		)));
			$response['fields'][] = array('text' => $this->language->get('text_option_debit'),
										'field' => (string)$form->getFieldHtml(array(
																			'type' => 'input',
																			'name' => 'debit',
																			'value' => $info['debit'],
																			'style' => 'large-field'
																		)));
			$types = $this->model_sale_customer->getTransactionTypes();
			$response['fields'][] = array('text' => $this->language->get('text_transaction_type'),
										'field' => (string)$form->getFieldHtml(array(
																			'type' => 'selectbox',
																			'name' => 'type[0]',
																			'options' => $types,
																			'value' => $info['type'],
																			'style' => 'medium-field'
																		)));
			$response['fields'][] = array('text' => $this->language->get('text_other_type'),
										'field' => (string)(string)$form->getFieldHtml(array(
																			'type' => 'input',
																			'name' => 'type[1]',
																			'value' => (!in_array($info['type'],$types)? $info['type'] :''),
																			'style' => 'large-field'
																		))
			);

			$response['fields'][] = array('text' => $this->language->get('text_transaction_comment'),
												'field' => (string)$form->getFieldHtml(array(
																					'type' => 'textarea',
																					'name' => 'comment',
																					'value' => $info['comment'],
																					'style' => 'large-field'
																				)));

			$response['fields'][] = array('text' => $this->language->get('text_transaction_description'),
												'field' => (string)$form->getFieldHtml(array(
																					'type' => 'textarea',
																					'name' => 'description',
																					'value' => $info['description'],
																					'style' => 'large-field'
																				)));


		}else{

			$response['fields'][] = array('text' => $this->language->get('column_create_date').':',
										'field' =>  dateISO2Display($info['create_date'], $this->language->get('date_format_short').' '.$this->language->get('time_format')));

			$response['fields'][] = array('text' => $this->language->get('column_created_by').":",
										'field' =>  $info['user']);

			$response['fields'][] = array('text' => $this->language->get('text_option_credit').':',
										'field' =>  $info['credit']);
			$response['fields'][] = array('text' => $this->language->get('text_option_debit').':',
										'field' =>  $info['debit']);
			$response['fields'][] = array('text' => $this->language->get('text_transaction_type'),
										'field' => (string)$info['type']);

			$response['fields'][] = array('text' => $this->language->get('text_transaction_comment'),
										'field' => htmlentities($info['comment'],ENT_QUOTES,'UTF-8'));

			$response['fields'][] = array('text' => $this->language->get('text_transaction_description'),
										'field' => htmlentities($info['description'],ENT_QUOTES,'UTF-8'));
			$response['fields'][] = array('text' => $this->language->get('text_update_date'),
													'field' =>  dateISO2Display($info['update_date'],$this->language->get('date_format_short').' '.$this->language->get('time_format')));

		}

		//update controller data
		$this->extensions->hk_UpdateData($this, __FUNCTION__);
		if(!$default_data_set){
			$this->response->setOutput(AJson::encode($response));
		}else{
			return $response;
		}
	}

}