<?php
//--
// PHP Form Process Script - Generated: January 11, 2017
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
$db_key = 48;
$job_id = 48;
$active = 1;
$active_message = <<<EOT
Sorry, this form is currently disabled.
EOT;

include_once 'security/secure_submit.php';
include_once 'lib/utility.php';

$_SESSION['entry_key_practiceexam'] = isset($_SESSION['entry_key_practiceexam']) ? $_SESSION['entry_key_practiceexam'] : md5(time() + rand(10000, 1000000));

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
		$_SESSION['qs']["{$_SESSION['entry_key_practiceexam']}"]["{$item}"] = $tmp;
		$_SESSION['qs-entities']["{$_SESSION['entry_key_practiceexam']}"]["{$item}"] = htmlentities($tmp);
		$_SESSION['qs-label']["{$_SESSION['entry_key_practiceexam']}"]["Dynamic - {$item}"] = $tmp;
		$_SESSION['qs-label']["{$_SESSION['entry_key_practiceexam']}"]["{$item}"] = $item;
		$_SESSION['qs-label']["{$_SESSION['entry_key_practiceexam']}"]["{$item}_pageTitle"] = "Practice Exam";
	
	}
	
}
		

// DifficultyRadio - radio
if(isset($_POST['DifficultyRadio']) && $_POST['DifficultyRadio'] != '') {
	$DifficultyRadio = isset($_POST['DifficultyRadio']) ? $_POST['DifficultyRadio'] : 0;
	$_SESSION['DifficultyRadio'] = $DifficultyRadio;
	if(isset($_SESSION['DifficultyRadio_is'])) { $_SESSION['DifficultyRadio_is'] = 0; }
	if(isset($_SESSION['DifficultyRadio_processed'])) { $_SESSION['DifficultyRadio_processed'] = true; }
} else {
	$_SESSION['DifficultyRadio'] = null;
}
$DifficultyRadio = isset($_SESSION['DifficultyRadio']) ? $_SESSION['DifficultyRadio'] : '';
$_SESSION['qs']["{$_SESSION['entry_key_practiceexam']}"]['DifficultyRadio'] = $DifficultyRadio;
$_SESSION['qs-entities']["{$_SESSION['entry_key_practiceexam']}"]['DifficultyRadio'] = is_array($DifficultyRadio) ? htmlentities(implode(':', $DifficultyRadio)) : htmlentities($DifficultyRadio);

// Label Processing

$label = "DifficultyRadio";

if(isset($_POST['DifficultyRadio_dyn_label']) && $_POST['DifficultyRadio_dyn_label'] != ""){
	$label = filter_input(INPUT_POST, 'DifficultyRadio_dyn_label', FILTER_SANITIZE_STRING);
}
			
$label = addslashes($label);

$_SESSION['qs-label']["{$_SESSION['entry_key_practiceexam']}"]["{$label}"] = $DifficultyRadio;
$_SESSION['qs-label']["{$_SESSION['entry_key_practiceexam']}"]['DifficultyRadio'] = $label;
$_SESSION['qs-label']["{$_SESSION['entry_key_practiceexam']}"]['DifficultyRadio_pageTitle'] = "Practice Exam";

// GuessedCheck - checkbox
if(isset($_POST['GuessedCheck']) && $_POST['GuessedCheck'] != '') {
	$GuessedCheck = isset($_POST['GuessedCheck']) ? $_POST['GuessedCheck'] : 0;
	$_SESSION['GuessedCheck'] = $GuessedCheck;
	if(isset($_SESSION['GuessedCheck_is'])) { $_SESSION['GuessedCheck_is'] = 0; }
	if(isset($_SESSION['GuessedCheck_processed'])) { $_SESSION['GuessedCheck_processed'] = true; }
} else {
	$_SESSION['GuessedCheck'] = null;
}
$GuessedCheck = isset($_SESSION['GuessedCheck']) ? $_SESSION['GuessedCheck'] : '';
$_SESSION['qs']["{$_SESSION['entry_key_practiceexam']}"]['GuessedCheck'] = $GuessedCheck;
$_SESSION['qs-entities']["{$_SESSION['entry_key_practiceexam']}"]['GuessedCheck'] = is_array($GuessedCheck) ? htmlentities(implode(':', $GuessedCheck)) : htmlentities($GuessedCheck);

// Label Processing

$label = "GuessedCheck";

if(isset($_POST['GuessedCheck_dyn_label']) && $_POST['GuessedCheck_dyn_label'] != ""){
	$label = filter_input(INPUT_POST, 'GuessedCheck_dyn_label', FILTER_SANITIZE_STRING);
}
			
$label = addslashes($label);

$_SESSION['qs-label']["{$_SESSION['entry_key_practiceexam']}"]["{$label}"] = $GuessedCheck;
$_SESSION['qs-label']["{$_SESSION['entry_key_practiceexam']}"]['GuessedCheck'] = $label;
$_SESSION['qs-label']["{$_SESSION['entry_key_practiceexam']}"]['GuessedCheck_pageTitle'] = "Practice Exam";

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
$_SESSION['qs']["{$_SESSION['entry_key_practiceexam']}"]['Answer'] = $Answer;
$_SESSION['qs-entities']["{$_SESSION['entry_key_practiceexam']}"]['Answer'] = is_array($Answer) ? htmlentities(implode(':', $Answer)) : htmlentities($Answer);

// Label Processing

$label = "Answer";

if(isset($_POST['Answer_dyn_label']) && $_POST['Answer_dyn_label'] != ""){
	$label = filter_input(INPUT_POST, 'Answer_dyn_label', FILTER_SANITIZE_STRING);
}
			
$label = addslashes($label);

$_SESSION['qs-label']["{$_SESSION['entry_key_practiceexam']}"]["{$label}"] = $Answer;
$_SESSION['qs-label']["{$_SESSION['entry_key_practiceexam']}"]['Answer'] = $label;
$_SESSION['qs-label']["{$_SESSION['entry_key_practiceexam']}"]['Answer_pageTitle'] = "Practice Exam";

// Dynamic Element Processing - Check for existance of our process session element
if(isset($_SESSION['fb_dynamic_elements'])){

	// Create Master Array, Used For Easy Insert Actions With SQL+
	if(!isset($_SESSION['qs']["{$_SESSION['entry_key_practiceexam']}"]["fb_dynamic_elements"]))
		$_SESSION['qs']["{$_SESSION['entry_key_practiceexam']}"]["fb_dynamic_elements"] = array();
		
	// Merge Arrays
	$_SESSION['qs']["{$_SESSION['entry_key_practiceexam']}"]["fb_dynamic_elements"] = array_merge($_SESSION['qs']["{$_SESSION['entry_key_practiceexam']}"]["fb_dynamic_elements"], $_SESSION['fb_dynamic_elements']);

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
			$_SESSION['qs']["{$_SESSION['entry_key_practiceexam']}"]["{$item}"] = $tmp;
			$_SESSION['qs-entities']["{$_SESSION['entry_key_practiceexam']}"]["{$item}"] = htmlentities($tmp);
			$_SESSION['qs-label']["{$_SESSION['entry_key_practiceexam']}"]["Dynamic - {$item}"] = $tmp;
			$_SESSION['qs-label']["{$_SESSION['entry_key_practiceexam']}"]["{$item}"] = $item;
			$_SESSION['qs-label']["{$_SESSION['entry_key_practiceexam']}"]["{$item}_pageTitle"] = "Practice Exam";
		
		}
		
	}

}

// unset the session element
unset($_SESSION['fb_dynamic_elements']);


// Form Persistance - Mode|Database Key|Resume Mode|Success Page
savePersistantValues(0, $db_key, 0, 'page1.php');
		


$sid_url = "";

if(defined('SID'))
	$sid_url = (strlen(SID) ? ('?' . htmlspecialchars(SID)) : '');

if($error){
	
	// remove pages from valid list. 
	if(isset($_SESSION['pages']['page1.php'])) { 
		unset($_SESSION['pages']['page1.php']);
		unset($_SESSION['pages-passed']["{$_SESSION['entry_key_practiceexam']}"]['page0.php']);
	} 
	
	$_SESSION["e_message"] = $valError;
	header("Location: {$_SESSION['MAX_PATH_PROC']}page0.php{$sid_url}");
	return;
} else {
	$_SESSION['pages']['page1.php'] = 'pass';
	
	$_SESSION['pages-passed']["{$_SESSION['entry_key_practiceexam']}"]['page0.php'] = 'pass';
	
	// custom route code
	
	
	// conditional route code
	
	
	// default action
	header("Location: {$_SESSION['MAX_PATH_PROC']}page1.php{$sid_url}");
	return;
}

?>