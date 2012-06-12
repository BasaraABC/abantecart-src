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

class ControllerCommonListingGrid extends AController {
	public $data; // array for template

	public function main() {

		//Load input argumets for gid settings
		$this->data = func_get_arg(0);
		if(!is_array($this->data)){
			throw new AException ( AC_ERR_LOAD, 'Error: Could create grid. Grid definition is not array.' );
		}
		//use to init controller data
        $this->extensions->hk_InitData($this,__FUNCTION__);
		//Do not load scripts multiple times 
        if ( !$this->registry->has('jqgrid_script') ) {
	        $locale = $this->session->data['language'];
	        if(!file_exists(DIR_ROOT.'/'.RDIR_TEMPLATE.'javascript/jqgrid/js/i18n/grid.locale-'.$locale.'.js')){
		        $locale = 'en';
	        }
            $this->document->addScript(RDIR_TEMPLATE.'javascript/jqgrid/js/i18n/grid.locale-'.$locale.'.js');

            $this->document->addScript(RDIR_TEMPLATE.'javascript/jqgrid/js/jquery.jqGrid.min.js');
	        $this->document->addScript(RDIR_TEMPLATE.'javascript/jqgrid/plugins/jquery.grid.fluid.js');
            $this->document->addScript(RDIR_TEMPLATE.'javascript/jqgrid/js/jquery.ba-bbq.min.js');
            $this->document->addScript(RDIR_TEMPLATE.'javascript/jqgrid/js/grid.history.js');

            $this->document->addStyle(array(
				'href' => RDIR_TEMPLATE.'javascript/jqgrid/css/abantecart.ui.jqgrid.css',
	            'rel' => 'stylesheet',
	            'media' => 'screen',
			));

            //set flag to not include scripts/css twice
            $this->registry->set('jqgrid_script', true);
        } 
        $this->data['update_field'] = empty($this->data['update_field']) ? '' : $this->data['update_field'];
        $this->data['editurl'] = empty($this->data['editurl']) ? '' : $this->data['editurl'];
        $this->data['rowNum'] = empty($this->data['rowNum']) ? 10 : $this->data['rowNum'];
        $this->data['rowList'] = empty($this->data['rowList']) ? array(10, 20, 30) : $this->data['rowList'];
		$this->data['multiselect'] = empty($this->data['multiselect']) ? "true" : $this->data['multiselect'];
		$this->data['multiaction'] = empty($this->data['multiaction']) ? "true" : $this->data['multiaction'];
		$this->data['hoverrows'] = empty($this->data['hoverrows']) ? "true" : $this->data['hoverrows'];
		$this->data['altRows'] = empty($this->data['altRows']) ? "true" : $this->data['altRows'];
		$this->data["sortorder"] = empty($this->data["sortorder"]) ? "desc" : $this->data["sortorder"];
		$this->data["columns_search"] = !isset($this->data["columns_search"]) ? true : $this->data["columns_search"];
		$this->data["search_form"] = !isset($this->data["search_form"]) ? false : $this->data["search_form"];
		// add custom buttons to jqgrid "pager" area
		if($this->data['custom_buttons']){
			$i=0;
			foreach( $this->data['custom_buttons'] as $button){
				if(!$button['caption']){
					continue;
				}
				$button['buttonicon'] = empty($button['buttonicon']) ? 'ui-icon-newwin' : $button['buttonicon'];
				$button['onClickButton'] = empty($button['onClickButton']) ? 'null' : $button['onClickButton'];
				$button['position'] = empty($button['position']) ? 'last' : $button['position'];
				$button['cursor'] = empty($button['cursor']) ? 'pointer' : $button['cursor'];
				$custom_buttons[$i] = $button;
			$i++;
			}
			$this->view->assign('custom_buttons', $custom_buttons );
		}
        // add action columns in case actions are defined
        if ( !empty($this->data['actions']) ) {
            $this->data['colNames'][] = $this->language->get('column_action');
		    $this->data['colModel'][] = array(
				'name' => 'action',
				'index' => 'action',
                'align' => 'center',
				'sortable' => false,
				'search' => false,
			);
        }

        $this->view->assign('data', $this->data );

		$this->view->assign('text_choose_action', $this->language->get('text_choose_action') );
		if(!$this->data['multiaction_options']){
			$multiaction_options['delete'] = $this->language->get('text_delete_selected') ;
			$multiaction_options['save'] = $this->language->get('text_save_selected') ;
		}else{
			$multiaction_options = $this->data['multiaction_options'];
		}

		$btn_go = $this->html->buildButton(array('text'=> !$this->data['button_go'] ? $this->language->get('button_go') : $this->data['button_go'],
		                                         'id'=>'btn_go',
		                                         'style'=>'button6',
		                                         'attr'=>(sizeof($multiaction_options)==1 ? 'onclick = "var grid = $(\'#'.$this->data['table_id'].'\'); grid.jqGrid(\'resetSelection\');	var ids = grid.getDataIDs(); for (var i=0, il=ids.length; i < il; i++) { grid.jqGrid(\'setSelection\',ids[i], true);  }" ' :''),
		                                         'form'=>'form'));
		$this->view->assign('btn_go', $btn_go );

		$this->view->assign('multiaction_options', $multiaction_options );

		$this->view->assign('text_save_all', $this->language->get('text_save_all') );
		$this->view->assign('text_select_items', $this->language->get('text_select_items') );
		$this->view->assign('text_no_results', $this->language->get('text_no_results') );
		$this->view->assign('text_all', $this->language->get('text_all') );

	    $this->processTemplate('common/listing_grid.tpl');

        //update controller data
        $this->extensions->hk_UpdateData($this,__FUNCTION__);

	}
}