<?php
/**
 * RackForms Form Library Files - get_single_image_token.php
 * @version 1.2
 * @author RackForms
 * @category Database
 * @copyright 2009-2014 nicSoft
 * @name get_single_image_token.php
 * 
 * ------------------
 * get_single_image_token.php
 * ------------------
 * 
 * -----------------
 * How to use:
 * -----------------
 * This file is used internally by RackForms and should not be called directly from user scripts.
 * 
 * -------------------
 * Rationale
 * -------------------
 * This file allows RackForms to implement the *J_ token.
 */
ini_set('display_errors', 0); // Change to 1 to display all error messages.
ini_set('error_reporting', E_ALL);

$debug = 0; // set to 1 to see debug messages.

if(!session_id())
	session_start();
	
// get main id for this item
$getid = isset($_GET['id']) ? $_GET['id'] : -1;

// if no id, stop execution
if($getid == -1)
	die;

// token
$token = isset($_GET['token']) ? $_GET['token'] : -1;

// if no token, stop execution
if($token == "")
	die;

// capture variables from session element 'packets' created in the template page
$column_name = isset($_SESSION['image_packet']["{$getid}"]["{$token}"]['J_token_column_name']) ? $_SESSION['image_packet']["{$getid}"]["{$token}"]['J_token_column_name'] : -1;

// additional field vars
$table_name = isset($_SESSION['image_packet']["{$getid}"]["{$token}"]['J_token_table_name']) ? $_SESSION['image_packet']["{$getid}"]["{$token}"]['J_token_table_name'] : -1;
$id_column_name = isset($_SESSION['image_packet']["{$getid}"]["{$token}"]['J_token_id_column_name']) ? $_SESSION['image_packet']["{$getid}"]["{$token}"]['J_token_id_column_name'] : -1;
$id_column_value = isset($_SESSION['image_packet']["{$getid}"]["{$token}"]['J_token_id_column_value']) ? $_SESSION['image_packet']["{$getid}"]["{$token}"]['J_token_id_column_value'] : -1;

$thumbnail = isset($_SESSION['image_packet']["{$getid}"]["{$token}"]['J_token_use_thumbnail']) ? $_SESSION['image_packet']["{$getid}"]["{$token}"]['J_token_use_thumbnail'] : -1;
$maxwidth = isset($_SESSION['image_packet']["{$getid}"]["{$token}"]['J_token_maxwidth']) ? $_SESSION['image_packet']["{$getid}"]["{$token}"]['J_token_maxwidth'] : -1;
$maxheight = isset($_SESSION['image_packet']["{$getid}"]["{$token}"]['J_token_maxheight']) ? $_SESSION['image_packet']["{$getid}"]["{$token}"]['J_token_maxheight'] : -1;


// Use Inline Connection Details By Default
// These values come from the jobs Repeater element.
$connector = isset($_GET['connector']) ? $_GET['connector'] : 'inline';

if($id_column_value != -1){
	
	// get connection data
	if($connector == 'inline'){
		$db_host = '#HOST';
		$db_type = '#TYPE';
		$db_user = '#USER';
		$db_pass = '#PASS';
		$db_catalog = '#CATALOG';
		$mysql_socket = '#SOCKET';
		$mysql_port = '#PORT';
		$dbdsn = '#DBDSN';
		$dbconnector = '#DBCONNECTOR';
		
		if(file_exists($dbconnector)){
			include $dbconnector;
		}
	}
	
	include '../Database.php';
	$dbh_img = new Database();

	if($table_name != -1 && $id_column_value != -1){
		$pass = 1;
	} else {
		$pass = 0;
	}

	$get = $column_name;
	$sql_img = "SELECT {$column_name} FROM {$table_name} WHERE {$id_column_name} = ?";
	
	
	//error_log(print_r($sql_img, TRUE));
	

	if($pass){
		$params = array($id_column_value);
		$result_img = $dbh_img->pdo_procedure_params($debug, $sql_img, $db_host, $db_type, $mysql_socket, $mysql_port, $dbdsn, $db_user, $db_pass, $db_catalog, $params, $return_true = 0);
	
	}
	
	// set default mime
	$mime = "image/jpeg";
	
	// get file info
	if(version_compare ( PHP_VERSION, "5.3.0", ">=" )){
		
		// Build 868 - Extension not always present, so check for funciton.
		if(function_exists('finfo')) {
			
			$finfo = new finfo(FILEINFO_MIME);
			$file_info = $finfo->buffer($result_img[0][$get]);
			
			$mime = explode(';', $file_info);
			$mime = $mime[0];
			
		}

	}
	
	
	// create thumbnail?
	if ($result_img[0][$get] != '' && version_compare ( PHP_VERSION, "5.0.0", ">=" ) && $thumbnail == 'true') {
		
		$valid_image = false;
		
		$image = imagecreatefromstring($result_img[0][$get]);                        
		if($image != false) { $valid_image = true; }    
		
		if($valid_image){
			
			$original_width = imagesx($image);
			$original_height = imagesy($image);
			
			if($maxwidth != '' && $original_height > $maxwidth){ // resize image only if start dim is larger than max
				$new_width = $maxwidth;
				$new_height = $original_height * ($new_width / $original_width);
			
				$image_resized = imagecreatetruecolor($new_width, $new_height); 
				imagecopyresampled($image_resized, $image, 0, 0, 0, 0, $new_width, $new_height, $original_width, $original_height);
			} elseif ($maxheight != '' && $original_height > $maxheight){	
				$new_height = $maxheight;
				$new_width = $original_width * ($new_height / $original_height);
			
				$image_resized = imagecreatetruecolor($new_width, $new_height); 
				imagecopyresampled($image_resized, $image, 0, 0, 0, 0, $new_width, $new_height, $original_width, $original_height);
			} else { // no resize
				$image_resized = $image;
			}
			
			header("Content-type: {$mime}");
			imagejpeg($image_resized);
			
		}
		
	} else {

		header("Content-type: {$mime}");
		echo $result_img[0][$get];
	
	} // thumbnail


}
?>