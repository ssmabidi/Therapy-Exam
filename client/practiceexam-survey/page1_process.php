<?php
//--
// PHP Form Process Script - Generated: July 17, 2017
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
$db_key = 60;
$job_id = 60;
$active = 1;
$active_message = <<<EOT
Sorry, this form is currently disabled.
EOT;

include_once 'security/secure_submit.php';
include_once 'lib/utility.php';

$_SESSION['entry_key_practiceexam-survey'] = isset($_SESSION['entry_key_practiceexam-survey']) ? $_SESSION['entry_key_practiceexam-survey'] : md5(time() + rand(10000, 1000000));

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
		$_SESSION['qs']["{$_SESSION['entry_key_practiceexam-survey']}"]["{$item}"] = $tmp;
		$_SESSION['qs-entities']["{$_SESSION['entry_key_practiceexam-survey']}"]["{$item}"] = htmlentities($tmp);
		$_SESSION['qs-label']["{$_SESSION['entry_key_practiceexam-survey']}"]["Dynamic - {$item}"] = $tmp;
		$_SESSION['qs-label']["{$_SESSION['entry_key_practiceexam-survey']}"]["{$item}"] = $item;
		$_SESSION['qs-label']["{$_SESSION['entry_key_practiceexam-survey']}"]["{$item}_pageTitle"] = "Practice Exam Survey";
	
	}
	
}
		

// Dynamic Element Processing - Check for existance of our process session element
if(isset($_SESSION['fb_dynamic_elements'])){

	// Create Master Array, Used For Easy Insert Actions With SQL+
	if(!isset($_SESSION['qs']["{$_SESSION['entry_key_practiceexam-survey']}"]["fb_dynamic_elements"]))
		$_SESSION['qs']["{$_SESSION['entry_key_practiceexam-survey']}"]["fb_dynamic_elements"] = array();
		
	// Merge Arrays
	$_SESSION['qs']["{$_SESSION['entry_key_practiceexam-survey']}"]["fb_dynamic_elements"] = array_merge($_SESSION['qs']["{$_SESSION['entry_key_practiceexam-survey']}"]["fb_dynamic_elements"], $_SESSION['fb_dynamic_elements']);

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
			$_SESSION['qs']["{$_SESSION['entry_key_practiceexam-survey']}"]["{$item}"] = $tmp;
			$_SESSION['qs-entities']["{$_SESSION['entry_key_practiceexam-survey']}"]["{$item}"] = htmlentities($tmp);
			$_SESSION['qs-label']["{$_SESSION['entry_key_practiceexam-survey']}"]["Dynamic - {$item}"] = $tmp;
			$_SESSION['qs-label']["{$_SESSION['entry_key_practiceexam-survey']}"]["{$item}"] = $item;
			$_SESSION['qs-label']["{$_SESSION['entry_key_practiceexam-survey']}"]["{$item}_pageTitle"] = "Practice Exam Survey";
		
		}
		
	}

}

// unset the session element
unset($_SESSION['fb_dynamic_elements']);


// Form Persistance - Mode|Database Key|Resume Mode|Success Page
savePersistantValues(0, $db_key, 0, '');
		


$sid_url = "";

if(defined('SID'))
	$sid_url = (strlen(SID) ? ('?' . htmlspecialchars(SID)) : '');

if($error){
	
	// remove pages from valid list. 
	if(isset($_SESSION['pages'][''])) { 
		unset($_SESSION['pages']['']);
		unset($_SESSION['pages-passed']["{$_SESSION['entry_key_practiceexam-survey']}"]['page1.php']);
	} 
	
	$_SESSION["e_message"] = $valError;
	header("Location: {$_SESSION['MAX_PATH_PROC']}{$sid_url}");
	return;
} else {
	$_SESSION['pages'][''] = 'pass';
	
	$_SESSION['pages-passed']["{$_SESSION['entry_key_practiceexam-survey']}"]['page1.php'] = 'pass';
	
	// custom route code
	
	
	// conditional route code
	
	
	// default action
	header("Location: {$_SESSION['MAX_PATH_PROC']}{$sid_url}");
	return;
}

?>