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

class ModelToolBackup extends Model {
	public function restore($sql) {
		foreach (explode(";\n", $sql) as $sql) {
    		$sql = trim($sql);
    		
			if ($sql) {
      			$this->db->query($sql);
    		}
  		}
	}
	public function load($xml) {
		$xml_obj = simplexml_load_string ( $xml );
		if($xml_obj){
			$xmlname = $xml_obj->getName();
			if($xmlname=='template_layouts'){
				$load = new ALayoutManager();
				$load->loadXML(array('xml'=>$xml));
			}elseif ( $xmlname=='datasets' ){
				$load = new ADataset();
				$load->loadXML(array('xml'=>$xml));
			}elseif ( $xmlname=='forms' ){
				$load = new AFormManager();
				$load->loadXML(array('xml'=>$xml));
			}else{
				return false;
			}
		} else {
			return false;
		}
	return true;
	}
	
	public function getTables() {
		$table_data = array();
		
		$query = $this->db->query("SHOW TABLES FROM `" . DB_DATABASE . "`");
		
		foreach ($query->rows as $result) {
			$table_data[] = $result['Tables_in_' . DB_DATABASE];
		}
		
		return $table_data;
	}
	
	public function backup($tables,$rl=true, $config=false) {
		$sql_dump = '';
		// make dump
		foreach ($tables as $table) {
			if (DB_PREFIX) {
				if (strpos($table, DB_PREFIX) === FALSE) {
					$status = FALSE;
				} else {
					$status = TRUE;
				}
			} else {
				$status = TRUE;
			}
			
			if ($status) {
				$sql_dump .= 'TRUNCATE TABLE `' . $table . '`;' . "\n\n";
			
				$query = $this->db->query("SELECT * FROM `" . $table . "`");
				
				foreach ($query->rows as $result) {
					$fields = '';
					foreach (array_keys($result) as $value) {
						$fields .= '`' . $value . '`, ';
					}
					$values = '';
					
					foreach (array_values($result) as $value) {
						$value = str_replace(array("\x00", "\x0a", "\x0d", "\x1a"), array('\0', '\n', '\r', '\Z'), $value);
						$value = str_replace(array("\n", "\r", "\t"), array('\n', '\r', '\t'), $value);
						$value = str_replace('\\', '\\\\',	$value);
						$value = str_replace('\'', '\\\'',	$value);
						$value = str_replace('\\\n', '\n',	$value);
						$value = str_replace('\\\r', '\r',	$value);
						$value = str_replace('\\\t', '\t',	$value);			
						
						$values .= '\'' . $value . '\', ';
					}
					$sql_dump .= 'INSERT INTO `' . $table . '` (' . preg_replace('/, $/', '', $fields) . ') VALUES (' . preg_replace('/, $/', '', $values) . ');' . "\n";
				}
				
				$sql_dump .= "\n\n";
			}
		}

		$bkp = new ABackup('manual_backup');
		if(!$bkp->error){
			$backup_dir = DIR_BACKUP.$bkp->getBackupName().'/';

			$fp = fopen($backup_dir.'data/dump.sql', 'w');
			fwrite($fp, $sql_dump);
			fclose($fp);

			if($rl){
				$bkp->backupDirectory(DIR_RESOURCE,false);
			}
			if($config){
				$bkp->backupFile(DIR_ROOT . '/system/config.php', false);
			}
			$bkp->archive(DIR_BACKUP.$bkp->getBackupName().'.tar.gz', DIR_BACKUP, $bkp->getBackupName() );
		}
		return $bkp;
	}
}
?>