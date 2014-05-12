<?php
/**
* @var $this APackageManager
*/

//add item to admin menu
$m = new AMenu('admin');
$m->insertMenuItem(
		array(
			'item_id' => 'languages',
			'item_text' => 'text_language',
			"item_url" => 'extension/extensions/language',
			"parent_id" => 'extension',
			"sort_order" => 5,
			"item_type" => 'core'));

//update extensions store menu items
$item = $m->deleteMenuItem('extensions_store');

$m->insertMenuItem(
		array(
			'item_id' => 'extensions_stores',
			'item_text' => 'text_extensions_store',
			"item_url" => 'extension/extensions_store',
			"parent_id" => 'extension',
			"sort_order" => 8,
			"item_type" => 'core'));

$m->insertMenuItem(
		array(
			'item_id' => 'extensions_store',
			'item_text' => 'text_extensions_store_new',
			"item_url" => 'extension/extensions_store',
			"parent_id" => 'extensions_stores',
			"sort_order" => 1,
			"item_type" => 'core'));

$m->insertMenuItem(
		array(
			'item_id' => 'extensions_store_prev',
			'item_text' => 'text_extensions_store_prev',
			"item_url" => 'extension/extensions_store_prev',
			"parent_id" => 'extensions_stores',
			"sort_order" => 2,
			"item_type" => 'core'));

$dataset = new ADataset ('menu', 'storefront');

$columns[] = array( 'name' => 'item_icon_rl_id',
					'type' => 'integer'	);
$dataset->defineColumns($columns);

$all_columns = $dataset->getColumnDefinitions();
foreach($all_columns as $c){
	if($c['dataset_column_name']=='item_icon_rl_id'){
		$dataset_column_id = $c['dataset_column_id'];
		break;
	}
}

//after insert of column need to insert empty values for data consistency (for 1.1.8 only)

$sql_query = "SELECT DISTINCT dv.row_id
			  FROM ". $this->db->table('dataset_values')." dv
			  INNER JOIN ". $this->db->table('dataset_definition')." dd ON dd.dataset_column_id = dv.dataset_column_id
			  WHERE dd.dataset_id = '1' AND dv.row_id>0";
$res = $this->db->query($sql_query);
if($res->num_rows){
	foreach($res->rows as $r){
		$this->db->query( "INSERT INTO ". $this->db->table('dataset_values')." (dataset_column_id, row_id)
							VALUES ('".$dataset_column_id."','".$r['row_id']."')");
	}
}




//insert download attribute types
$this->db->query("INSERT INTO `".DB_PREFIX."global_attributes_types` (`type_key`, `controller`, `sort_order`, `status`) VALUES
							('download_attribute', 'responses/catalog/attribute/getDownloadAttributeSubform', 2, 1);");
$attr_id = $this->db->getLastId();

$this->db->query("INSERT INTO `".DB_PREFIX."global_attributes_type_descriptions` (`attribute_type_id`,`language_id`, `type_name`, `create_date`)
				VALUES ('".$attr_id."', 1, 'Download Attribute', NOW()),
       					('".$attr_id."', 9, 'Descargar Atributo', NOW());");
       					
//clear cache after upgrade       					
$this->cache->delete('*');
