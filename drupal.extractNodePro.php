<?php
/* =========================================================
* ExtractNodePro.php 1.0
* - Extract node fields to a clean array
*
* Author: Alexander Bech / www.alexanderbech.com
* http://github.com/AlexanderBech/ExtractNodePro.php
* ========================================================== */

/*
*	Remove empty HTML tags from string (USED IN extractNodePro / PLACE INSIDE template.php)
*
*	Author: Alexander Bech / www.alexanderbech.com
*	http://github.com/AlexanderBech/
*/
function removeEmptyHtmlTags($string){
	$cleaned = preg_replace("/<p[^>]*>[\s|&nbsp;]*<\/p>/", '', $string);
	return $cleaned;
}

/*
*	Extract node fields to a clean array (PLACE INSIDE template.php)
*
*	Author: Alexander Bech / www.alexanderbech.com
*	http://github.com/AlexanderBech/ExtractNodePro.php
*/
function extractNodePro($node, $fields=array(), $values=array()){

	// Setup
	$data = array();
	$fields = empty($fields) ? array('title', 'body', 'path') : $fields;
	$values = empty($values) ? array('value', 'rgb', 'uri', 'alt', 'width', 'height') : $values;

	// Loop
	foreach($fields as $field){
		// Rewrite field name if 'field_' is not given
		if(strpos($field, 'field_') === FALSE && $field != "title" && $field != "body" && $field != "nid"){
			$field_name = "field_".$field;
		} else {
			$field_name = $field;
		}
		// Check if field exist
		if(!isset($node->$field_name)){
			$data[$field] = ""; // Field does not exist
			continue;
		}
		// Get field
		$node_field = $node->$field_name;
		// Check if empty
		if(!empty($node_field)){
			// Check if string
			if(!is_array($node_field)){
				$data[$field] = $node_field;
				continue;
			}
			if(!empty($node_field['und'])){

				// If 'nid' exist we assume it's a node reference
				if(!empty($node_field['und'][0]['nid'])){
					$data[$field] = $node_field['und'];
					continue;
				}

				if(count($node_field['und']) > 1){
					// MORE THAN ONE
					foreach($node_field['und'] as $val_num => $val_item){

						// Find values to save
						$values_to_return = array();
						foreach($values as $value){
							if(!empty($val_item[$value])){
								$values_to_return[] = $value;
							}
						}
						// Save values
						if(!empty($values_to_return)){
							if(count($values_to_return) > 1){
								foreach($values_to_return as $vr){
		 							$data[$field][$val_num][$vr] = $val_item[$vr];
								}
								continue;
							} else {
								// One value
								$data[$field][$val_num] = removeEmptyHtmlTags($val_item[$values_to_return[0]]);
								continue;
							}
						}

					}
					continue;
				} else {
					// Find values to save
					$values_to_return = array();
					foreach($values as $value){
						if(!empty($node_field['und'][0][$value])){
							$values_to_return[] = $value;
						}
					}
					// Save values
					if(!empty($values_to_return)){
						if(count($values_to_return) > 1){
							foreach($values_to_return as $vr){
	 							$data[$field][$vr] = $node_field['und'][0][$vr];
							}
							continue;
						} else {
							// One value
							$data[$field] = removeEmptyHtmlTags($node_field['und'][0][$values_to_return[0]]);
							continue;
						}
					}
				}
			} else {
				$data[$field] = ""; // Value is empty
			}
			$data[$field] = ""; // Value could not be recognized
		} else {
			$data[$field] = ""; // Value is empty
		}
	}

	// Return data array
	return $data;
}

?>