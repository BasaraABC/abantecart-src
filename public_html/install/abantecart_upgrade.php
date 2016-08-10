<?php
//alter tables

//check if exist
$sql = "SELECT * FROM ".$this->db->table('banner_descriptions');
$result = $this->db->query($sql,true);
if($result){
	$this->db->query(
			"ALTER TABLE ".$this->db->table('banner_descriptions')."
			CHANGE COLUMN `description` `description` LONGTEXT NULL DEFAULT NULL"
			, true);
}

//improve global search
$sql = "ALTER TABLE ".$this->db->table('settings')." DROP INDEX `ac_settings_idx`;";
$this->db->query($sql, true);

$sql = "ALTER TABLE ".$this->db->table('settings')." DROP INDEX `".DB_PREFIX."settings_idx`;";
$this->db->query($sql, true);

$sql ="ALTER TABLE ".$this->db->table('settings')." DROP PRIMARY KEY, ADD PRIMARY KEY (`setting_id`, `store_id`, `group`, `key`);";
$this->db->query($sql, true);

$sql = "ALTER TABLE ".$this->db->table('settings')." ADD FULLTEXT INDEX `".DB_PREFIX."settings_idx` (`value` ASC);";
$this->db->query($sql, true);

$sql = "ALTER TABLE ".$this->db->table('language_definitions')." DROP INDEX `ac_lang_definition_idx`;";
$this->db->query($sql, true);

$sql = "ALTER TABLE ".$this->db->table('language_definitions')." DROP INDEX `".DB_PREFIX."lang_definition_idx`;";
$this->db->query($sql, true);

$sql ="ALTER TABLE ".$this->db->table('language_definitions')." DROP PRIMARY KEY, ADD PRIMARY KEY (`language_definition_id`, `language_id`, `section`, `block`, `language_key`);";
$this->db->query($sql, true);

$sql ="ALTER TABLE ".$this->db->table('language_definitions')." ADD FULLTEXT INDEX `ac_lang_definition_idx` (`language_value` ASC);";
$this->db->query($sql, true);



//create reports->analytics menu item
$menu = new AMenu ( "admin" );
$menu->insertMenuItem ( array (
						 "item_id" => "report_analytics",
						 "parent_id" => 'reports',
						 "item_icon_rl_id" => 234,
						 "item_text" => 'text_analytics',
						 "item_url" => "",
						 "item_type"=>"core",
						 "sort_order"=>"4"
						)
					);
// create NeoWize admin menu button
$menu->insertMenuItem ( array (
						 "item_id" => "neowize_insights",
						 "parent_id" => 'report_analytics',
						 "item_icon_rl_id" => 234,
						 "item_text" => "NeoWize Insights",
						 "item_url" => "neowize/dashboard",
						 "item_type"=>"extension",
						 "sort_order"=>"0"
						)
					);

//move all resources with type archives to resources/archive directory!

/**
 * @var AController $this
 */

$result = $this->db->query("SELECT * FROM ". $this->db->table('resource_types')." WHERE type_name= 'archive'");
$resource_type_id = (int)$result->row['type_id'];

$result = true;
if(!is_dir(DIR_RESOURCE.'archive/')){
	$result = mkdir(DIR_RESOURCE.'archive/',0755);
	if(!$result){
		$error = new AError('Cannot to move resources with type "archive" into '.DIR_RESOURCE.' directory during upgrade process. Please check permissions.');
		$error->toLog()->toMessages();
	}
}else if(!is_writeable(DIR_RESOURCE.'archive/')){
	$error = new AError('Cannot to move resources with type "archive" into '.DIR_RESOURCE.'archive/ directory during upgrade process. Please check permissions.');
	$error->toLog()->toMessages();
	$result = false;
}

if($resource_type_id && $result){
	$resources = $this->db->query(
			"SELECT DISTINCT r.resource_id, rd.resource_path
			 FROM ". $this->db->table('resource_library')." r
			 LEFT JOIN  ". $this->db->table('resource_descriptions')." rd
			    ON (rd.resource_id = r.resource_id)
			 WHERE r.type_id= '".$resource_type_id."'");
	$error_text = '';
	foreach($resources->rows as $row){
		if(!$row['resource_path']){ continue;}
		$file = DIR_RESOURCE.'download/'.$row['resource_path'];
		if(!is_file($file)){ continue;}

		$new_file_path = DIR_RESOURCE.'archive/'.$row['resource_path'];

		if(is_file($new_file_path)){ continue; }

		//check is directory exists
		$dest_dir = dirname($new_file_path);
		if(!is_dir($dest_dir)){
			mkdir($dest_dir,755,true);
		}

		$res = copy($file, $new_file_path);
		if($res){
			unlink($file);
		}else{
			$error_text .= $file."\n";
		}
	}

	if($error_text){
		$error = new AError('Upgrade Error: Cannot to move resource files into ' . DIR_RESOURCE . 'archive/ directory during upgrade process.'."\n".$error_text);
		$error->toLog()->toMessages();
	}

	// update resource type directory
	$sql = "UPDATE " . $this->db->table('resource_types') . "
		SET default_directory = '" . $this->db->escape('archive/') . "'
		WHERE type_name= 'archive'";
	$result = $this->db->query($sql);
}





