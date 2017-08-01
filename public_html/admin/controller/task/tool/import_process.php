<?php
/*------------------------------------------------------------------------------
  $Id$

  AbanteCart, Ideal OpenSource Ecommerce Solution
  http://www.AbanteCart.com

  Copyright © 2011-2017 Belavier Commerce LLC

  This source file is subject to Open Software License (OSL 3.0)
  License details is bundled with this package in the file LICENSE.txt.
  It is also available at this URL:
  <http://www.opensource.org/licenses/OSL-3.0>

 UPGRADE NOTE:
   Do not edit or add to this file if you wish to upgrade AbanteCart to newer
   versions in the future. If you wish to customize AbanteCart for your
   needs please refer to http://www.AbanteCart.com for more information.
------------------------------------------------------------------------------*/
if (!defined('DIR_CORE') || !IS_ADMIN){
	header('Location: static_pages/');
}

class ControllerTaskToolImportProcess extends AController{
	public $data = array();
	private $processed_count = 0;

	public function processRows(){
		list($task_id, $step_id, $details) = func_get_args();
		$this->load->library('json');
		//for aborting process
		ignore_user_abort(false);
		session_write_close();

		//init controller data
		$this->extensions->hk_InitData($this, __FUNCTION__);

		$this->processed_count = 0;
		$result = $this->_process($task_id, $step_id);
		if(!$this->processed_count){
			$result = false;
		}
		//update controller data
		$this->extensions->hk_UpdateData($this, __FUNCTION__);

		$output = array('result'  => $result);
		if($result){
			$output['message'] = $this->processed_count . ' rows processed.';
		}else{
			$output['error_text'] = $this->processed_count . ' rows processed with error.';
		}

		$this->response->setOutput(AJson::encode( $output ));
	}

	private function _process($task_id, $step_id){

		if (!$task_id || !$step_id){
			$error_text = 'Cannot run task step. Task_id (or step_id) has not been set.';
			$this->_return_error($error_text);
		}

		$tm = new ATaskManager();
		$task_info = $tm->getTaskById($task_id);
        //get setting with import details
        $import_details = $task_info['settings']['import_data'];
        $processed = (int)$task_info['settings']['processed'];
        $step_info = $tm->getTaskStep($task_id, $step_id);
        if(!$step_info['settings'] ){
            $error_text = "Cannot run task #{$task_id} step #{$step_id}. Can not locate settings for the step.";
            $this->_return_error($error_text);
        }
        //record the start
        $tm->updateStep($step_id, array('last_time_run' => date('Y-m-d H:i:s')));

        $return = array();
        $start = $step_info['start'];
        $stop = $step_info['stop'];
        $filename = $import_details['file'];
        $type = $import_details['table'];
        $delimeter = $import_details['delimiter'];

        $step_result = false;
        //read records from source file
        $records = $this->readFileSeek($filename, $start, ($stop-$start));
        if(count($records)) {
            //process column names
            $columns = str_getcsv($records[0], $delimeter, '"');
            //skip header and process each record
            array_shift($records);
            $this->loadModel('tool/import_process');
            foreach ($records as $index => $row) {
                $vals = array();
                $rowData = str_getcsv($row, $delimeter, '"');
                //check if we match row data count to header
                if (count($rowData) != count($columns)) {
                    //incomplete row. Exit
                    $return[] = "Error: incomplete data in row number: {$index} with: {$rowData[0]}";
                    continue;
                }
                for ($i = 0; $i < count($columns); $i++) {
                    $vals[$columns[$i]] = $rowData[$i];
                }
                //main driver to process data and import
                $method = "process_{$type}_record";
                $result = $this->model_tool_import_process->$method($task_id, $vals, $import_details);
                if ($result) {
                    $this->processed_count++;
                }
            }
            $tm->updateTaskDetails($task_id,
                array(
                    'settings'   => array(
                        'step_id'           => $step_id,
                        'total_rows_count'  => $task_info['settings']['total_rows_count'],
                        'processed'         => $this->processed_count
                    )
                )
            );
            $step_result = true;
            $tm->updateStep($step_id, array('last_result' => $step_result));
            //all done, clear cache
            $this->cache->remove('*');
        }

        return $step_result;
	}

    protected function readFileSeek($source, $linenum = 1, $range = 1){
        $buffer = array();
        $fh = fopen($source, 'r');
        $lineNo = 0;
        $startLine = $linenum;
        $endLine = $linenum + $range;
        while ($line = fgets($fh)) {
            //always return first line with header
            if($lineNo++ == 0) {
                $buffer[] = $line;
                continue;
            }

            if ($lineNo >= $startLine) {
                $buffer[] = $line;
            }
            if ($lineNo == $endLine) {
                break;
            }
        }
        fclose($fh);
        return $buffer;
    }

	private function _return_error($error_text){
		$error = new AError($error_text);
		$error->toLog()->toDebug();
		return $error->toJSONResponse('APP_ERROR_402',
				array ('error_text'  => $error_text,
				       'reset_value' => true
				));
	}

}
