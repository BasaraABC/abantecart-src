<?php 
/*------------------------------------------------------------------------------
  $Id$

  AbanteCart, Ideal OpenSource Ecommerce Solution
  http://www.AbanteCart.com

  Copyright © 2011-2014 Belavier Commerce LLC

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
class ControllerResponsesCommonRunTask extends AController {
	private $error = array(); 
	    
  	public function main() {

        //init controller data
        $this->extensions->hk_InitData($this,__FUNCTION__);

		$task_obj = new ATaskManager();
		if(has_value($this->request->get['task_key'])){
			$output = $task_obj->runTask($this->request->get['task_key']);
		}

        //init controller data
        $this->extensions->hk_UpdateData($this,__FUNCTION__);
		$this->load->library('json');
		$output = $output ? AJson::encode($output) : null;
		$this->response->setOutput( $output );
	}

	public function getState() {

	        //init controller data
	        $this->extensions->hk_InitData($this,__FUNCTION__);

			$this->load->library('task_manager');
			$task_obj = new TaskManager();
			if(has_value($this->request->get['task_key'])){
				$task_obj->runTask($this->request->get['task_key']);
			}

	        //init controller data
	        $this->extensions->hk_UpdateData($this,__FUNCTION__);
			$this->load->library('json');
			$this->response->setOutput( AJson::encode($output) );
	}

}