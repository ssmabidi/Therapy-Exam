<?php
//--
// PHP Form Process Script - Generated: January 6, 2017
//--


// Start our main session

if(!session_id()) { session_start(); }

$error = 0; $valError = "";

// Form Page Submit Security
$domain_list = explode(',',"");
$ip_limit = 0;
$ip_limit_message = <<<EOT
Sorry, form entry limit reached.
EOT;
$delta = 3155692600;
$db_key = 61;
$job_id = 61;
$active = 1;
$active_message = <<<EOT
Sorry, this form is currently disabled.
EOT;

include_once 'security/secure_submit.php';
include_once 'lib/utility.php';

$_SESSION['entry_key_practiceexam-tutorial'] = isset($_SESSION['entry_key_practiceexam-tutorial']) ? $_SESSION['entry_key_practiceexam-tutorial'] : md5(time() + rand(10000, 1000000));

$form_persistance = 0;
	
// Collect Optional GeoData
$items = array("geo_lat", "geo_long", "geo_accuracy", "geo_altitude", "geo_heading", "geo_speed", "geo_timestamp");

if(is_array($items) && count($items) != 0){

	foreach($items as $item){
	
		// always reset our tmp value
		unset($tmp);
	
		// check for and create dynamic element
		if(isset($_POST["{$item}"]) && $_POST["{$item}"] != '') {
			$tmp = isset($_POST["{$item}"]) ? $_POST["{$item}"] : 0;
			$_SESSION["{$item}"] = $tmp;
			if(isset($_SESSION["{$item}_is"])) { $_SESSION["{$item}_is"] = 0; }
			if(isset($_SESSION["{$item}_processed"])) { $_SESSION["{$item}_processed"] = true; }
		} else {
			$_SESSION["{$item}"] = null;
		}
		$tmp = isset($_SESSION["{$item}"]) ? $_SESSION["{$item}"] : '';
		$_SESSION['qs']["{$_SESSION['entry_key_practiceexam-tutorial']}"]["{$item}"] = $tmp;
		$_SESSION['qs-entities']["{$_SESSION['entry_key_practiceexam-tutorial']}"]["{$item}"] = htmlentities($tmp);
		$_SESSION['qs-label']["{$_SESSION['entry_key_practiceexam-tutorial']}"]["Dynamic - {$item}"] = $tmp;
		$_SESSION['qs-label']["{$_SESSION['entry_key_practiceexam-tutorial']}"]["{$item}"] = $item;
		$_SESSION['qs-label']["{$_SESSION['entry_key_practiceexam-tutorial']}"]["{$item}_pageTitle"] = "Practice Exam Tutorial";
	
	}
	
}
		

// Answer - radio
if(isset($_POST['Answer']) && $_POST['Answer'] != '') {
	$Answer = isset($_POST['Answer']) ? $_POST['Answer'] : 0;
	$_SESSION['Answer'] = $Answer;
	if(isset($_SESSION['Answer_is'])) { $_SESSION['Answer_is'] = 0; }
	if(isset($_SESSION['Answer_processed'])) { $_SESSION['Answer_processed'] = true; }
} else {
	$_SESSION['Answer'] = null;
}
$Answer = isset($_SESSION['Answer']) ? $_SESSION['Answer'] : '';
$_SESSION['qs']["{$_SESSION['entry_key_practiceexam-tutorial']}"]['Answer'] = $Answer;
$_SESSION['qs-entities']["{$_SESSION['entry_key_practiceexam-tutorial']}"]['Answer'] = is_array($Answer) ? htmlentities(implode(':', $Answer)) : htmlentities($Answer);

// Label Processing

$label = "Answer";

if(isset($_POST['Answer_dyn_label']) && $_POST['Answer_dyn_label'] != ""){
	$label = filter_input(INPUT_POST, 'Answer_dyn_label', FILTER_SANITIZE_STRING);
}
			
$label = addslashes($label);

$_SESSION['qs-label']["{$_SESSION['entry_key_practiceexam-tutorial']}"]["{$label}"] = $Answer;
$_SESSION['qs-label']["{$_SESSION['entry_key_practiceexam-tutorial']}"]['Answer'] = $label;
$_SESSION['qs-label']["{$_SESSION['entry_key_practiceexam-tutorial']}"]['Answer_pageTitle'] = "Practice Exam Tutorial";

// Dynamic Element Processing - Check for existance of our process session element
if(isset($_SESSION['fb_dynamic_elements'])){

	// Create Master Array, Used For Easy Insert Actions With SQL+
	if(!isset($_SESSION['qs']["{$_SESSION['entry_key_practiceexam-tutorial']}"]["fb_dynamic_elements"]))
		$_SESSION['qs']["{$_SESSION['entry_key_practiceexam-tutorial']}"]["fb_dynamic_elements"] = array();
		
	// Merge Arrays
	$_SESSION['qs']["{$_SESSION['entry_key_practiceexam-tutorial']}"]["fb_dynamic_elements"] = array_merge($_SESSION['qs']["{$_SESSION['entry_key_practiceexam-tutorial']}"]["fb_dynamic_elements"], $_SESSION['fb_dynamic_elements']);

	$items = $_SESSION['fb_dynamic_elements'];
	
	if(is_array($items) && count($items) != 0){
	
		foreach($items as $item){
		
			// always reset our tmp value
			unset($tmp);
		
			// check for and create dynamic element
			if(isset($_POST["{$item}"]) && $_POST["{$item}"] != '') {
				$tmp = isset($_POST["{$item}"]) ? $_POST["{$item}"] : 0;
				$_SESSION["{$item}"] = $tmp;
				if(isset($_SESSION["{$item}_is"])) { $_SESSION["{$item}_is"] = 0; }
				if(isset($_SESSION["{$item}_processed"])) { $_SESSION["{$item}_processed"] = true; }
			} else {
				$_SESSION["{$item}"] = null;
			}
			$tmp = isset($_SESSION["{$item}"]) ? $_SESSION["{$item}"] : '';
			$_SESSION['qs']["{$_SESSION['entry_key_practiceexam-tutorial']}"]["{$item}"] = $tmp;
			$_SESSION['qs-entities']["{$_SESSION['entry_key_practiceexam-tutorial']}"]["{$item}"] = htmlentities($tmp);
			$_SESSION['qs-label']["{$_SESSION['entry_key_practiceexam-tutorial']}"]["Dynamic - {$item}"] = $tmp;
			$_SESSION['qs-label']["{$_SESSION['entry_key_practiceexam-tutorial']}"]["{$item}"] = $item;
			$_SESSION['qs-label']["{$_SESSION['entry_key_practiceexam-tutorial']}"]["{$item}_pageTitle"] = "Practice Exam Tutorial";
		
		}
		
	}

}

// unset the session element
unset($_SESSION['fb_dynamic_elements']);


// Form Persistance - Mode|Database Key|Resume Mode|Success Page
savePersistantValues(0, $db_key, 0, 'page4.php');
		


$sid_url = "";

if(defined('SID'))
	$sid_url = (strlen(SID) ? ('?' . htmlspecialchars(SID)) : '');

if($error){
	
	// remove pages from valid list. 
	if(isset($_SESSION['pages']['page4.php'])) { 
		unset($_SESSION['pages']['page4.php']);
		unset($_SESSION['pages-passed']["{$_SESSION['entry_key_practiceexam-tutorial']}"]['page3.php']);
	} 
	
	$_SESSION["e_message"] = $valError;
	header("Location: {$_SESSION['MAX_PATH_PROC']}page3.php{$sid_url}");
	return;
} else {
	$_SESSION['pages']['page4.php'] = 'pass';
	
	$_SESSION['pages-passed']["{$_SESSION['entry_key_practiceexam-tutorial']}"]['page3.php'] = 'pass';
	
	// custom route code
	
	
	// conditional route code
	
	
	// default action
	header("Location: {$_SESSION['MAX_PATH_PROC']}page4.php{$sid_url}");
	return;
}

?>