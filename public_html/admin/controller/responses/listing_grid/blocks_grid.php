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
class ControllerResponsesListingGridBlocksGrid extends AController {
	public $data;

	public function main() {

		//init controller data
		$this->extensions->hk_InitData($this, __FUNCTION__);

		$this->loadLanguage('design/blocks');

		$page = $this->request->post[ 'page' ]; // get the requested page
		if ((int)$page < 0) $page = 0;
		$limit = $this->request->post[ 'rows' ]; // get how many rows we want to have into the grid


		//process custom search form
		$grid_filter_params = array( 'block_txt_id', 'name' );
		$filter_grid = new AFilter(array( 'method' => 'post', 'grid_filter_params' => $grid_filter_params ));

		$layout = new ALayoutManager();
		$total = $layout->getBlocksList($filter_grid->getFilterData(), 'total_only');
		$blocks = $layout->getBlocksList($filter_grid->getFilterData());


		// prepare block list (delete template duplicates)
		foreach ($blocks as $block) {
			// skip base custom blocks
			if (!$block[ 'custom_block_id' ] && in_array($block[ 'block_txt_id' ], array( 'html_block', 'listing_block' ))) {
				continue;
			}
			$tmp[ $block[ 'block_id' ] . '_' . $block[ 'custom_block_id' ] ] = $block;
		}
		$blocks = $tmp;


		if ($total > 0) {
			$total_pages = ceil($total / $limit);
		} else {
			$total_pages = 0;
		}

		$response = new stdClass();
		$response->page = $page;
		$response->total = $total_pages;
		$response->records = $total;


		$i = 0;
		foreach ($blocks as $result) {
			if ($result[ 'custom_block_id' ]) {
				$action = '<a id="action_edit_' . $result[ 'block_id' ] . '_' . $result[ 'custom_block_id' ] . '" class="btn_action" href="' . $this->html->getSecureURL('design/blocks/edit', '&custom_block_id=' . $result[ 'custom_block_id' ]) . '"
						title="' . $this->language->get('text_edit') . '">' .
						'<img src="' . RDIR_TEMPLATE . 'image/icons/icon_grid_edit.png" alt="' . $this->language->get('text_edit') . '" />' .
						'</a>
				<a class="btn_action" href="' . $this->html->getSecureURL('design/blocks/delete', '&custom_block_id=' . $result[ 'custom_block_id' ]) . '"
			 	onclick="return confirm(\'' . $this->language->get('text_delete_confirm') . '\')" title="' . $this->language->get('text_delete') . '">' .
						'<img src="' . RDIR_TEMPLATE . 'image/icons/icon_grid_delete.png" alt="' . $this->language->get('text_delete') . '" />' .
						'</a>';

			} else {
				$action = '<a id="' . $result[ 'block_id' ] . '" class="view btn_action"
						title="' . $this->language->get('text_view') . '">' .
						'<img src="' . RDIR_TEMPLATE . 'image/icons/icon_grid_view.png" alt="' . $this->language->get('text_view') . '" />' .
						'</a>';
			}
			$response->rows[ $i ][ 'id' ] = $result[ 'custom_block_id' ] ? $result[ 'block_id' ] . '_' . $result[ 'custom_block_id' ] : $result[ 'block_id' ];
			$response->rows[ $i ][ 'cell' ] = array(
				$result[ 'custom_block_id' ] ? $result[ 'block_id' ] . '_' . $result[ 'custom_block_id' ] : $result[ 'block_id' ],
				$result[ 'block_txt_id' ],
				$result[ 'block_name' ],
				(isset($result[ 'status' ]) ?
						$this->html->buildCheckbox(array(
							'name' => 'status[' . $result[ 'product_id' ] . ']',
							'value' => $result[ 'status' ],
							'style' => 'btn_switch',
							'attr' => 'readonly="true"'
						)) : ''),
				$result[ 'block_date_added' ],
				$action
			);
			$i++;
		}
		$this->data = $response;
		//update controller data
		$this->extensions->hk_UpdateData($this, __FUNCTION__);

		$this->load->library('json');
		$this->response->setOutput(AJson::encode($this->data));
	}

	public function update_field() {

		//init controller data
		$this->extensions->hk_InitData($this, __FUNCTION__);

		if (!$this->user->canModify('listing_grid/blocks_grid')) {
			$error = new AError('');
			return $error->toJSONResponse('NO_PERMISSIONS_402',
				array( 'error_text' => sprintf($this->language->get('error_permission_modify'), 'listing_grid/blocks_grid'),
					'reset_value' => true
				));
		}

		$this->loadLanguage('design/blocks');

		$custom_block_id = (int)$this->request->get[ 'custom_block_id' ];
		$layout = new ALayoutManager();
		if (($this->request->server [ 'REQUEST_METHOD' ] == 'POST')) {

			$tmp = array();
			if (isset($this->request->post [ 'block_status' ])) {
				$tmp[ 'status' ] = (int)$this->request->post [ 'block_status' ];
			}
			if (isset($this->request->post [ 'block_name' ])) {
				$tmp[ 'name' ] = $this->request->post [ 'block_name' ];
			}
			if (isset($this->request->post [ 'block_title' ])) {
				$tmp[ 'title' ] = $this->request->post [ 'block_title' ];
			}
			if (isset($this->request->post [ 'block_description' ])) {
				$tmp[ 'description' ] = $this->request->post [ 'block_description' ];
			}
			if (isset($this->request->post [ 'block_content' ])) {
				$tmp[ 'content' ] = $this->request->post [ 'block_content' ];
			}
			if (isset($this->request->post [ 'block_wrapper' ])) {
				$tmp[ 'block_wrapper' ] = $this->request->post [ 'block_wrapper' ];
			}
			if (isset($this->request->post [ 'block_framed' ])) {
				$tmp[ 'block_framed' ] = (int)$this->request->post [ 'block_framed' ];
			}

			$tmp[ 'language_id' ] = $this->session->data[ 'content_language_id' ];

			$layout->saveBlockDescription((int)$this->request->post[ 'block_id' ],
				$custom_block_id,
				$tmp);
			$info = $layout->getBlockDescriptions($custom_block_id);
			if (isset($tmp[ 'status' ])) {
				if ($info[ $tmp[ 'language_id' ] ][ 'status' ] != $tmp[ 'status' ]) {
					$error = new AError('');
					return $error->toJSONResponse('NO_PERMISSIONS_406',
						array( 'error_text' => $this->language->get('error_text_status'),
							'reset_value' => true
						));
				}
			}

		}

		//update controller data
		$this->extensions->hk_UpdateData($this, __FUNCTION__);
	}

	public function getSubForm() {
		$listing_datasource = isset($this->request->post[ 'listing_datasource' ]) ? $this->request->post[ 'listing_datasource' ] : $this->request->get[ 'listing_datasource' ];
		$response_type = isset($this->request->post[ 'response_type' ]) ? $this->request->post[ 'response_type' ] : $this->request->get[ 'response_type' ];

		$listing_manager = new AListingManager((int)$this->request->get [ 'custom_block_id' ]);
		$this->data[ 'data_sources' ] = $listing_manager->getListingDataSources();
		// if request for non-existant datasource
		if (!in_array($listing_datasource, array_keys($this->data[ 'data_sources' ]))) {
			return;
		}

		if (strpos($listing_datasource, 'custom_') !== FALSE) {
			$this->getCustomListingSubForm($listing_datasource, $response_type);
		} elseif ($listing_datasource == 'media') {
			$this->getMediaListingSubForm();
		} elseif ($listing_datasource == '') {
			return;
		} else {
			$this->getAutoListingSubForm();
		}

	}

	public function getAutoListingSubForm() {
		//init controller data
		$this->extensions->hk_InitData($this, __FUNCTION__);
		$this->loadLanguage('design/blocks');

		$lm = new ALayoutManager();
		if (!isset ($this->request->get [ 'custom_block_id' ])) {
			$form = new AForm ('ST');
		} else {
			$form = new AForm ('HS');
			$content = $lm->getBlockDescriptions((int)$this->request->get [ 'custom_block_id' ]);
			$content = $content[ $this->session->data[ 'content_language_id' ] ][ 'content' ];
			$content = unserialize($content);
		}
		$form->setForm(array( 'form_name' => 'BlockFrm' ));
		$this->data[ 'response' ] = '<table class="form">
										<tr><td>' . $this->language->get('entry_limit') . '</td>
											<td class="ml_field">' . $form->getFieldHtml(array( 'type' => 'input',
			'name' => 'limit',
			'value' => $content[ 'limit' ],
			'help_url' => $this->gen_help_url('block_limit') )) . '</td></tr>
																								</table>';


		//update controller data
		$this->extensions->hk_UpdateData($this, __FUNCTION__);
		$this->response->setOutput($this->data[ 'response' ]);
	}


	public function getMediaListingSubForm() {
		//init controller data
		$this->extensions->hk_InitData($this, __FUNCTION__);
		$this->loadLanguage('design/blocks');

		$lm = new ALayoutManager();
		if (!isset ($this->request->get [ 'custom_block_id' ])) {
			$form = new AForm ('ST');
		} else {
			$form = new AForm ('HS');
			$content = $lm->getBlockDescriptions((int)$this->request->get [ 'custom_block_id' ]);
			$content = $content[ $this->session->data[ 'content_language_id' ] ][ 'content' ];
			$content = unserialize($content);
		}
		$form->setForm(array( 'form_name' => 'BlockFrm' ));

		$rl = new AResourceManager();
		$types = $rl->getResourceTypes();
		$resource_types[ '' ] = $this->language->get('text_select');
		foreach ($types as $type) {
			$resource_types[ $type[ 'type_name' ] ] = $type[ 'type_name' ];
		}
		$view = new AView($this->registry, 0);
		$view->batchAssign(array(   'entry_media_resource_type' => $this->language->get('entry_resource_type'),
									'media_resource_type' => $form->getFieldHtml(
																	 array( 'type' => 'selectbox',
																			'name' => 'resource_type',
																			'value' => (string)$content[ 'resource_type' ],
																			'options' => $resource_types,
																			'style' => 'no-save',
																			'help_url' => $this->gen_help_url('block_resource_type')
																		)),
									'entry_media_resource_limit' => $this->language->get('entry_limit'),
									'media_resource_limit' => $form->getFieldHtml(
																	array( 'type' => 'input',
																			'name' => 'limit',
																			'value' => $content[ 'limit' ],
																			'style' => 'no-save',
																			'help_url' => $this->gen_help_url('block_limit') ))

		));
		$this->data[ 'response' ] = $view->fetch('responses/design/block_media_listing_subform.tpl');

		//update controller data
		$this->extensions->hk_UpdateData($this, __FUNCTION__);
		$this->response->setOutput($this->data[ 'response' ]);
	}


	/*
	 * response method, if response type is html - it send jqgrid, otherwise - json response for grid
	 * */
	public function getCustomListingSubForm($data_source, $response_type = 'html') {
		//init controller data
		$this->extensions->hk_InitData($this, __FUNCTION__);
		$this->loadLanguage('design/blocks');
		$this->load->library('json');
		$response_type = !$response_type ? 'html' : $response_type;
		$listing_datasource = isset($this->request->post[ 'listing_datasource' ]) ? $this->request->post[ 'listing_datasource' ] : $this->request->get[ 'listing_datasource' ];
		/*
		 * send table wrapper for jqgrid as html
		 * */
		$form_name = $this->request->get[ 'form_name' ] ? $this->request->get[ 'form_name' ] : 'BlockFrm';
		if ($response_type == 'html') {

			if ($this->request->get[ 'popup' ] != 1) {


				// need to get data of custom listing
				$listing_data = array();
				if ($this->request->get [ 'custom_block_id' ]) {

					$lm = new ALayoutManager();
					$content = $lm->getBlockDescriptions((int)$this->request->get [ 'custom_block_id' ]);
					$content = $content[ $this->session->data[ 'content_language_id' ] ][ 'content' ];
					$content = unserialize($content);

					if ($content[ 'listing_datasource' ] == $listing_datasource) {
						$lm = new AListingManager($this->request->get [ 'custom_block_id' ]);
						$list = $lm->getCustomList();
						if ($list) {
							foreach ($list as $row) {
								$listing_data[ $row[ 'id' ] ] = array( 'status' => true,
									'sort_order' => $row[ 'sort_order' ] );
							}
						}
					}
				}


				$form = new AForm ('ST');
				$form->setForm(array( 'form_name' => $form_name ));
				$view = new AView($this->registry, 0);
				$view->batchAssign(array(   'form_name' => $form_name,
											'multivalue_html' => $form->getFieldHtml(
																		array( 'id' => 'popup',
																			'type' => 'multivalue',
																			'name' => 'popup',
																			'title' => $this->language->get('text_select_from_list'),
																			'selected' => ($listing_data ? AJson::encode($listing_data) : "{}"),
																			'content_url' => $this->html->getSecureUrl('listing_grid/blocks_grid/getsubform',
																					'&popup=1&custom_block_id=' . (int)$this->request->get [ 'custom_block_id' ]),
																			'postvars' => array( 'listing_datasource' => $listing_datasource ),
																			'return_to' => '', // placeholder's id of listing items count.
																			'no_save' => ((int)$this->request->get [ 'custom_block_id' ] ? false : true),
																			'text' => array(
																				'selected' => $this->language->get('text_selected'),
																				'edit' => $this->language->get('text_save_edit'),
																				'apply' => $this->language->get('text_apply'),
																				'save' => $this->language->get('button_save'),
																				'reset' => $this->language->get('button_reset') ),
																		))

				));
				$this->data[ 'response' ] = $view->fetch('responses/design/block_custom_listing_subform.tpl');

			} else {
				$this->loadLanguage($this->data[ 'data_sources' ][ $data_source ][ 'language' ]);
				//remember selected rows for response
				if (isset($this->request->post[ 'selected' ])) {
					$this->session->data[ 'listing_selected' ] = AJson::decode(html_entity_decode($this->request->post[ 'selected' ]), true);
				}
				$grid_settings = array(
					'table_id' => 'product_grid',
					'url' => $this->html->getSecureURL('listing_grid/blocks_grid/getSubForm',
							'&custom_block_id=' . (int)$this->request->get[ 'custom_block_id' ] . '&listing_datasource=' . $data_source . '&response_type=json'),
					'editurl' => '',
					'update_field' => $this->html->getSecureURL('listing_grid/blocks_grid/rememberchoice',
							"&custom_block_id=" . (int)$this->request->get[ 'custom_block_id' ]),
					'sortname' => 'name',
					'sortorder' => 'asc',
					'actions' => array(),
					'multiselect_noselectbox' => true,
				);

				$grid_settings[ 'colNames' ] = array(
					$this->language->get('column_image'),
					$this->language->get('column_name') );
				if (strpos($listing_datasource, 'product') !== FALSE) {
					$grid_settings[ 'colNames' ][ ] = $this->language->get('column_model');
				}
				$grid_settings[ 'colNames' ][ ] = $this->language->get('column_sort_order');
				$grid_settings[ 'colNames' ][ ] = $this->language->get('column_action');

				$grid_settings[ 'colModel' ] = array(
					array(
						'name' => 'image',
						'index' => 'image',
						'align' => 'center',
						'width' => 50,
						'sortable' => false,
						'search' => false ),
					array(
						'name' => 'name',
						'index' => 'name',
						'align' => 'left',
						'width' => 200 ) );
				if (strpos($listing_datasource, 'product') !== FALSE) {
					$grid_settings[ 'colModel' ][ ] = array(
						'name' => 'model',
						'index' => 'model',
						'align' => 'center',
						'width' => 60,
						'sortable' => true );
				}
				$grid_settings[ 'colModel' ][ ] = array(
					'name' => 'sort_order',
					'index' => 'sort_order',
					'align' => 'center',
					'width' => 120,
					'sortable' => false,
					'search' => false );

				$grid_settings[ 'colModel' ][ ] = array(
					'name' => 'action',
					'index' => 'action',
					'align' => 'center',
					'width' => 30,
					'sortable' => false,
					'search' => false );

				$grid_settings[ 'search_form' ] = true;

				$grid = $this->dispatch('common/listing_grid', array( $grid_settings ));
				$this->data[ 'response' ] = $grid->dispatchGetOutput();

				$view = new AView($this->registry, 0);
				$view->batchAssign(array(   'form_name' => $form_name,
											'table_id' => $grid_settings[ 'table_id' ]
				));
				$this->data[ 'response' ] .= $view->fetch('responses/design/block_custom_listing_js.tpl');
			}
		} else {
			// json-response for jqgrid

			// if datasource was switched
			$layout_manager = new ALayoutManager();
			$info = $layout_manager->getBlockDescriptions((int)$this->request->get [ 'custom_block_id' ]);
			$info = is_array($info) ? current($info) : '';
			$info = unserialize($info[ 'content' ]);
			$custom_list = array();
			if ($info[ 'listing_datasource' ] == $listing_datasource) {
				$lm = new AListingManager($this->request->get [ 'custom_block_id' ]);
				$list = $lm->getCustomList();
				if ($list) {
					foreach ($list as $row) {
						$custom_list[ $row[ 'id' ] ] = $row[ 'sort_order' ];
					}
				}
			}
			//load data source language and model
			$this->loadLanguage($this->data[ 'data_sources' ][ $data_source ][ 'language' ]);
			$this->loadModel($this->data[ 'data_sources' ][ $data_source ][ 'model' ]);
			$this->loadModel('tool/image');

			//Prepare filter config
			$grid_filter_params = array( 'name', 'sort_order', 'model' );
			$filter = new AFilter(array( 'method' => 'post', 'grid_filter_params' => $grid_filter_params ));
			$filter_data = $filter->getFilterData();

			//call data source model total method to get total based on set filter
			$total = call_user_func_array(array( $this->{'model_' . str_replace('/', '_', $this->data[ 'data_sources' ][ $data_source ][ 'model' ])},
					$this->data[ 'data_sources' ][ $data_source ][ 'total_method' ] ),
				array( $filter_data ));
			$response = new stdClass();
			$response->page = $filter->getParam('page');
			$response->total = $filter->calcTotalPages($total);
			$response->records = $total;
			$response->userdata = (object)array( '' );
			//call data source model method to get all records based on set filter
			$results = call_user_func_array(array( $this->{'model_' . str_replace('/', '_', $this->data[ 'data_sources' ][ $data_source ][ 'model' ])},
					$this->data[ 'data_sources' ][ $data_source ][ 'method' ] ),
				array( $filter_data ));
			$i = 0;
			$resource = new AResource('image');
			$response->userdata = (object)array( 'page' => '', 'selId' => Array() );
			$data_type = $this->data[ 'data_sources' ][ $data_source ][ 'data_type' ];
			$id_list = $custom_list ? array_keys($custom_list) : array();

			if ($results) {
				foreach ($results as $result) {

					if (in_array($result[ $data_type ], $id_list) || in_array($result[ $data_type ], array_keys($this->session->data[ 'listing_selected' ]))) {
						$response->userdata->selId[ ] = $result[ $data_type ];
					}
					$thumbnail = $resource->getMainThumb($this->data[ 'data_sources' ][ $data_source ][ 'rl_object_name' ],
						$result[ $data_type ],
						36,
						36, true);

					$response->rows[ $i ][ 'id' ] = $result[ $data_type ];
					$response->rows[ $i ][ 'cell' ] = array(
						$thumbnail[ 'thumb_html' ],
						$result[ 'name' ] );

					if (strpos($listing_datasource, 'product') !== FALSE) {
						$response->rows[ $i ][ 'cell' ][ ] = $result[ 'model' ];
					}

					$response->rows[ $i ][ 'cell' ][ ] = $this->html->buildInput(
						array(
							'name' => 'sort_order[' . $result[ $data_type ] . ']',
							'value' => ($custom_list[ $result[ $data_type ] ] ? $custom_list[ $result[ $data_type ] ] : $this->session->data[ 'listing_selected' ][ $result[ $data_type ] ][ 'sort_order' ]),
							'style' => 'no-save',
							'attr' => 'onblur="write_sorting(' . $result[ $data_type ] . ');" ' . (!in_array($result[ $data_type ], $id_list) && !in_array($result[ $data_type ], array_keys($this->session->data[ 'listing_selected' ])) ? ' disabled="disabled" ' : ''),
						)
					);

					$response->rows[ $i ][ 'cell' ][ ] = '<a class="btn_action" href="JavaScript:void(0);" onclick="showPopup(\'' . $this->html->getSecureURL($this->data[ 'data_sources' ][ $data_source ][ 'view_path' ], '&' . $data_type . '=' . $result[ $data_type ]) . '\')" title="' . $this->language->get('text_view') . '">' .
							'<img src="' . RDIR_TEMPLATE . 'image/icons/icon_grid_view.png" alt="' . $this->language->get('text_edit') . '" /></a>';
					$i++;
				}
			}

			$this->data[ 'response' ] = $response;
		}


		//update controller data
		$this->extensions->hk_UpdateData($this, __FUNCTION__);

		if ($response_type == 'json') {
			$this->load->library('json');
			$this->data[ 'response' ] = AJson::encode($this->data[ 'response' ]);
		}

		$this->response->setOutput($this->data[ 'response' ]);

	}

	public function info() {

		//init controller data
		$this->extensions->hk_InitData($this, __FUNCTION__);

		$this->loadLanguage('design/blocks');
		$lm = new ALayoutManager();
		$info = $lm->getBlockInfo((int)$this->request->get[ 'block_id' ]);
		$layouts = array();
		if ($info) {
			//$this->loadModel('setting/store');
			$tmp = '';
			foreach ($info as $row) {
				if ((int)$row[ 'layout_id' ]) {
					$layouts[ ] = '<a target="_blank" href="' . $this->html->getSecureURL('design/layout', '&tmpl_id=' . $row[ 'template_id' ] . '&page_id=' . $row[ 'page_id' ] . '&layout_id=' . $row[ 'layout_id' ]) . '">' . $row[ 'layout_name' ] . '</a>';
				}
				if ($tmp == $row[ 'template_id' ] . '-' . $row[ 'page_id' ] . '-' . $row[ 'layout_id' ]) {
					continue;
				} else {
					$tmp = $row[ 'template_id' ] . '-' . $row[ 'page_id' ] . '-' . $row[ 'layout_id' ];
				}
				$row[ 'templates' ] = explode(',', $row[ 'templates' ]);
				unset($row[ 'layout_id' ], $row[ 'layout_name' ], $row[ 'page_id' ], $row[ 'template_id' ], $row[ 'store_id' ]);
				$block_info = $row;
			}
			if (!$layouts) {
				$layouts = array( $this->language->get('text_none') );
			}
			$block_info[ 'layouts' ] = $layouts;

			$response = '<tr>';
			foreach ($block_info as $key => $item) {
				if (!is_array($item)) {
					$response .= '<tr><td>' . $this->language->get('text_' . $key) . '</td><td>' . $item . '</td></tr>';
				} else {
					if ($item) {
						$response .= '<tr><td>' . $this->language->get('text_' . $key) . ':</td><td></td></tr>';
						foreach ($item as $info_name => $info_value) {
							if (!is_array($info_value)) {
								$response .= '<tr><td></td><td>' . $info_value . '</td></tr>';
							} else {
								foreach ($info_value as $v) {
									$response .= '<tr><td></td><td>' . $v . '</td></tr>';
								}

							}
						}
					}
				}
			}
			$response .= '</tr>';
		}

		$this->data = $response;

		//update controller data
		$this->extensions->hk_UpdateData($this, __FUNCTION__);

		$this->response->setOutput($this->data);
	}

	public function block_info() {
		$data = array();
		//init controller data
		$this->extensions->hk_InitData($this, __FUNCTION__);

		$this->loadLanguage('design/blocks');
		
		//load specific template/page/layout
		$template = $this->request->get['template'];
		$page_id = $this->request->get['page_id'];
		$layout_id = $this->request->get['layout_id'];		
		$lm = new ALayoutManager($template, $page_id, $layout_id);

		//acccept 2 type of ids. Number based and custom [block]_[custom_block] 
		$custom_block_id = $this->request->get['block_id'];
		if( preg_match("/(\d+)_(\d+)/",$custom_block_id, $match) ) { 
			//take last postion of id for custom block
			$block_id = $match[1];
			$custom_block_id = $match[2];
		} else {
			//error
			$this->load->library('json');
			$this->response->setOutput(AJson::encode( array( 'error' => 'Incorrect Block ID' ) ));
			return null;			
		}

		$info = $lm->getBlockInfo((int)$block_id);
		//expect only 1 block details per layout
		$data = $info[0];
		//get specific description 
		if( $custom_block_id > 0){
			$descr = $lm->getBlockDescriptions((int)$custom_block_id);
			$language_id = $this->session->data['content_language_id'];
			$data['title'] = $descr[$language_id]['title'];
			$data['description'] = $descr[$language_id]['description'];
			
			//detect edit URL and build button
			if ($data['block_txt_id'] == 'html_block' || $data['block_txt_id'] == 'listing_block' ) {
				$edit_url = $this->html->getSecureURL('design/blocks/edit', '&custom_block_id='.$custom_block_id);
			} else if ($data['block_txt_id'] == 'banner_block') {
				$edit_url = $this->html->getSecureURL('extension/banner_manager/edit_block', '&custom_block_id='.$custom_block_id);
			} else {
				//just list all 
				$edit_url = $this->html->getSecureURL('design/blocks');				
			}
						
			$data['block_edit_brn'] =  $this->html->buildButton(array(	'type' => 'button',
			    											'name' => 'btn_edit',
			    											'id' => 'btn_edit',
			    											'text' => $this->language->get('text_edit'),
			    											'href' => $edit_url,
			    											'target' => '_new',
			    											'style'=>'button1'));			
			$data['allow_edit'] = 'true';
			
		} else {
			//get details from language for static blocks from storefront
			$alang = new ALanguage($this->registry, $language_id, 0);
			$alang->load($data['controller'], 'silent');
			$data['title'] = $alang->get('heading_title');
			$data['description'] = $this->language->get('text_controller') . ": " . $data['controller'];
			$data['description'] .= "<br/>" . $this->language->get('text_templates') . ": ";
			$data['description'] .= "<br/>" . implode("<br/>", split(',', $data['templates']));	
		}
		
		//update controller data
		$this->extensions->hk_UpdateData($this, __FUNCTION__);

		$this->load->library('json');
		$this->response->setOutput(AJson::encode($data));
	}
}
