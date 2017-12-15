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
		

// AnsRealistic - radio
if(isset($_POST['AnsRealistic']) && $_POST['AnsRealistic'] != '') {
	$AnsRealistic = isset($_POST['AnsRealistic']) ? $_POST['AnsRealistic'] : '';
	$_SESSION['AnsRealistic'] = $AnsRealistic;
} else {
	if(1 == 1){ // Is Field Validation Method Set to: Field Only Validates When Visible?
		$_SESSION['AnsRealistic'] = null;
	} else {
		$error = '1'; $valError .= 'AnsRealistic is required.<br/>';
	}
}
if(isset($_SESSION['AnsRealistic_is'])) { $_SESSION['AnsRealistic_is'] = 0; }

	if(isset($_SESSION['AnsRealistic_processed'])) { $_SESSION['AnsRealistic_processed'] = true; }$AnsRealistic = isset($_SESSION['AnsRealistic']) ? $_SESSION['AnsRealistic'] : '';
$_SESSION['qs']["{$_SESSION['entry_key_practiceexam-survey']}"]['AnsRealistic'] = $AnsRealistic;
$_SESSION['qs-entities']["{$_SESSION['entry_key_practiceexam-survey']}"]['AnsRealistic'] = is_array($AnsRealistic) ? htmlentities(implode(':', $AnsRealistic)) : htmlentities($AnsRealistic);		
		
// Label Processing
		
$label = "AnsRealistic";
		
if(isset($_POST['AnsRealistic_dyn_label']) && $_POST['AnsRealistic_dyn_label'] != ""){
	$label = filter_input(INPUT_POST, 'AnsRealistic_dyn_label', FILTER_SANITIZE_STRING);
}
		
$label = addslashes($label);
		
$_SESSION['qs-label']["{$_SESSION['entry_key_practiceexam-survey']}"]["{$label}"] = $AnsRealistic;
$_SESSION['qs-label']["{$_SESSION['entry_key_practiceexam-survey']}"]['AnsRealistic'] = $label;
$_SESSION['qs-label']["{$_SESSION['entry_key_practiceexam-survey']}"]['AnsRealistic_pageTitle'] = "Practice Exam Survey";

// AnsFair - radio
if(isset($_POST['AnsFair']) && $_POST['AnsFair'] != '') {
	$AnsFair = isset($_POST['AnsFair']) ? $_POST['AnsFair'] : '';
	$_SESSION['AnsFair'] = $AnsFair;
} else {
	if(1 == 1){ // Is Field Validation Method Set to: Field Only Validates When Visible?
		$_SESSION['AnsFair'] = null;
	} else {
		$error = '1'; $valError .= 'AnsFair is required.<br/>';
	}
}
if(isset($_SESSION['AnsFair_is'])) { $_SESSION['AnsFair_is'] = 0; }

	if(isset($_SESSION['AnsFair_processed'])) { $_SESSION['AnsFair_processed'] = true; }$AnsFair = isset($_SESSION['AnsFair']) ? $_SESSION['AnsFair'] : '';
$_SESSION['qs']["{$_SESSION['entry_key_practiceexam-survey']}"]['AnsFair'] = $AnsFair;
$_SESSION['qs-entities']["{$_SESSION['entry_key_practiceexam-survey']}"]['AnsFair'] = is_array($AnsFair) ? htmlentities(implode(':', $AnsFair)) : htmlentities($AnsFair);		
		
// Label Processing
		
$label = "AnsFair";
		
if(isset($_POST['AnsFair_dyn_label']) && $_POST['AnsFair_dyn_label'] != ""){
	$label = filter_input(INPUT_POST, 'AnsFair_dyn_label', FILTER_SANITIZE_STRING);
}
		
$label = addslashes($label);
		
$_SESSION['qs-label']["{$_SESSION['entry_key_practiceexam-survey']}"]["{$label}"] = $AnsFair;
$_SESSION['qs-label']["{$_SESSION['entry_key_practiceexam-survey']}"]['AnsFair'] = $label;
$_SESSION['qs-label']["{$_SESSION['entry_key_practiceexam-survey']}"]['AnsFair_pageTitle'] = "Practice Exam Survey";

// AnsBreaks - radio
if(isset($_POST['AnsBreaks']) && $_POST['AnsBreaks'] != '') {
	$AnsBreaks = isset($_POST['AnsBreaks']) ? $_POST['AnsBreaks'] : '';
	$_SESSION['AnsBreaks'] = $AnsBreaks;
} else {
	if(1 == 1){ // Is Field Validation Method Set to: Field Only Validates When Visible?
		$_SESSION['AnsBreaks'] = null;
	} else {
		$error = '1'; $valError .= 'AnsBreaks is required.<br/>';
	}
}
if(isset($_SESSION['AnsBreaks_is'])) { $_SESSION['AnsBreaks_is'] = 0; }

	if(isset($_SESSION['AnsBreaks_processed'])) { $_SESSION['AnsBreaks_processed'] = true; }$AnsBreaks = isset($_SESSION['AnsBreaks']) ? $_SESSION['AnsBreaks'] : '';
$_SESSION['qs']["{$_SESSION['entry_key_practiceexam-survey']}"]['AnsBreaks'] = $AnsBreaks;
$_SESSION['qs-entities']["{$_SESSION['entry_key_practiceexam-survey']}"]['AnsBreaks'] = is_array($AnsBreaks) ? htmlentities(implode(':', $AnsBreaks)) : htmlentities($AnsBreaks);		
		
// Label Processing
		
$label = "AnsBreaks";
		
if(isset($_POST['AnsBreaks_dyn_label']) && $_POST['AnsBreaks_dyn_label'] != ""){
	$label = filter_input(INPUT_POST, 'AnsBreaks_dyn_label', FILTER_SANITIZE_STRING);
}
		
$label = addslashes($label);
		
$_SESSION['qs-label']["{$_SESSION['entry_key_practiceexam-survey']}"]["{$label}"] = $AnsBreaks;
$_SESSION['qs-label']["{$_SESSION['entry_key_practiceexam-survey']}"]['AnsBreaks'] = $label;
$_SESSION['qs-label']["{$_SESSION['entry_key_practiceexam-survey']}"]['AnsBreaks_pageTitle'] = "Practice Exam Survey";

// AnsMindInto - radio
if(isset($_POST['AnsMindInto']) && $_POST['AnsMindInto'] != '') {
	$AnsMindInto = isset($_POST['AnsMindInto']) ? $_POST['AnsMindInto'] : '';
	$_SESSION['AnsMindInto'] = $AnsMindInto;
} else {
	if(1 == 1){ // Is Field Validation Method Set to: Field Only Validates When Visible?
		$_SESSION['AnsMindInto'] = null;
	} else {
		$error = '1'; $valError .= 'AnsMindInto is required.<br/>';
	}
}
if(isset($_SESSION['AnsMindInto_is'])) { $_SESSION['AnsMindInto_is'] = 0; }

	if(isset($_SESSION['AnsMindInto_processed'])) { $_SESSION['AnsMindInto_processed'] = true; }$AnsMindInto = isset($_SESSION['AnsMindInto']) ? $_SESSION['AnsMindInto'] : '';
$_SESSION['qs']["{$_SESSION['entry_key_practiceexam-survey']}"]['AnsMindInto'] = $AnsMindInto;
$_SESSION['qs-entities']["{$_SESSION['entry_key_practiceexam-survey']}"]['AnsMindInto'] = is_array($AnsMindInto) ? htmlentities(implode(':', $AnsMindInto)) : htmlentities($AnsMindInto);		
		
// Label Processing
		
$label = "AnsMindInto";
		
if(isset($_POST['AnsMindInto_dyn_label']) && $_POST['AnsMindInto_dyn_label'] != ""){
	$label = filter_input(INPUT_POST, 'AnsMindInto_dyn_label', FILTER_SANITIZE_STRING);
}
		
$label = addslashes($label);
		
$_SESSION['qs-label']["{$_SESSION['entry_key_practiceexam-survey']}"]["{$label}"] = $AnsMindInto;
$_SESSION['qs-label']["{$_SESSION['entry_key_practiceexam-survey']}"]['AnsMindInto'] = $label;
$_SESSION['qs-label']["{$_SESSION['entry_key_practiceexam-survey']}"]['AnsMindInto_pageTitle'] = "Practice Exam Survey";

// AnsMindDuring - radio
if(isset($_POST['AnsMindDuring']) && $_POST['AnsMindDuring'] != '') {
	$AnsMindDuring = isset($_POST['AnsMindDuring']) ? $_POST['AnsMindDuring'] : '';
	$_SESSION['AnsMindDuring'] = $AnsMindDuring;
} else {
	if(1 == 1){ // Is Field Validation Method Set to: Field Only Validates When Visible?
		$_SESSION['AnsMindDuring'] = null;
	} else {
		$error = '1'; $valError .= 'AnsMindDuring is required.<br/>';
	}
}
if(isset($_SESSION['AnsMindDuring_is'])) { $_SESSION['AnsMindDuring_is'] = 0; }

	if(isset($_SESSION['AnsMindDuring_processed'])) { $_SESSION['AnsMindDuring_processed'] = true; }$AnsMindDuring = isset($_SESSION['AnsMindDuring']) ? $_SESSION['AnsMindDuring'] : '';
$_SESSION['qs']["{$_SESSION['entry_key_practiceexam-survey']}"]['AnsMindDuring'] = $AnsMindDuring;
$_SESSION['qs-entities']["{$_SESSION['entry_key_practiceexam-survey']}"]['AnsMindDuring'] = is_array($AnsMindDuring) ? htmlentities(implode(':', $AnsMindDuring)) : htmlentities($AnsMindDuring);		
		
// Label Processing
		
$label = "AnsMindDuring";
		
if(isset($_POST['AnsMindDuring_dyn_label']) && $_POST['AnsMindDuring_dyn_label'] != ""){
	$label = filter_input(INPUT_POST, 'AnsMindDuring_dyn_label', FILTER_SANITIZE_STRING);
}
		
$label = addslashes($label);
		
$_SESSION['qs-label']["{$_SESSION['entry_key_practiceexam-survey']}"]["{$label}"] = $AnsMindDuring;
$_SESSION['qs-label']["{$_SESSION['entry_key_practiceexam-survey']}"]['AnsMindDuring'] = $label;
$_SESSION['qs-label']["{$_SESSION['entry_key_practiceexam-survey']}"]['AnsMindDuring_pageTitle'] = "Practice Exam Survey";

// AnsMindAfter - radio
if(isset($_POST['AnsMindAfter']) && $_POST['AnsMindAfter'] != '') {
	$AnsMindAfter = isset($_POST['AnsMindAfter']) ? $_POST['AnsMindAfter'] : '';
	$_SESSION['AnsMindAfter'] = $AnsMindAfter;
} else {
	if(1 == 1){ // Is Field Validation Method Set to: Field Only Validates When Visible?
		$_SESSION['AnsMindAfter'] = null;
	} else {
		$error = '1'; $valError .= 'AnsMindAfter is required.<br/>';
	}
}
if(isset($_SESSION['AnsMindAfter_is'])) { $_SESSION['AnsMindAfter_is'] = 0; }

	if(isset($_SESSION['AnsMindAfter_processed'])) { $_SESSION['AnsMindAfter_processed'] = true; }$AnsMindAfter = isset($_SESSION['AnsMindAfter']) ? $_SESSION['AnsMindAfter'] : '';
$_SESSION['qs']["{$_SESSION['entry_key_practiceexam-survey']}"]['AnsMindAfter'] = $AnsMindAfter;
$_SESSION['qs-entities']["{$_SESSION['entry_key_practiceexam-survey']}"]['AnsMindAfter'] = is_array($AnsMindAfter) ? htmlentities(implode(':', $AnsMindAfter)) : htmlentities($AnsMindAfter);		
		
// Label Processing
		
$label = "AnsMindAfter";
		
if(isset($_POST['AnsMindAfter_dyn_label']) && $_POST['AnsMindAfter_dyn_label'] != ""){
	$label = filter_input(INPUT_POST, 'AnsMindAfter_dyn_label', FILTER_SANITIZE_STRING);
}
		
$label = addslashes($label);
		
$_SESSION['qs-label']["{$_SESSION['entry_key_practiceexam-survey']}"]["{$label}"] = $AnsMindAfter;
$_SESSION['qs-label']["{$_SESSION['entry_key_practiceexam-survey']}"]['AnsMindAfter'] = $label;
$_SESSION['qs-label']["{$_SESSION['entry_key_practiceexam-survey']}"]['AnsMindAfter_pageTitle'] = "Practice Exam Survey";

// AnsFocus - radio
if(isset($_POST['AnsFocus']) && $_POST['AnsFocus'] != '') {
	$AnsFocus = isset($_POST['AnsFocus']) ? $_POST['AnsFocus'] : '';
	$_SESSION['AnsFocus'] = $AnsFocus;
} else {
	if(1 == 1){ // Is Field Validation Method Set to: Field Only Validates When Visible?
		$_SESSION['AnsFocus'] = null;
	} else {
		$error = '1'; $valError .= 'AnsFocus is required.<br/>';
	}
}
if(isset($_SESSION['AnsFocus_is'])) { $_SESSION['AnsFocus_is'] = 0; }

	if(isset($_SESSION['AnsFocus_processed'])) { $_SESSION['AnsFocus_processed'] = true; }$AnsFocus = isset($_SESSION['AnsFocus']) ? $_SESSION['AnsFocus'] : '';
$_SESSION['qs']["{$_SESSION['entry_key_practiceexam-survey']}"]['AnsFocus'] = $AnsFocus;
$_SESSION['qs-entities']["{$_SESSION['entry_key_practiceexam-survey']}"]['AnsFocus'] = is_array($AnsFocus) ? htmlentities(implode(':', $AnsFocus)) : htmlentities($AnsFocus);		
		
// Label Processing
		
$label = "AnsFocus";
		
if(isset($_POST['AnsFocus_dyn_label']) && $_POST['AnsFocus_dyn_label'] != ""){
	$label = filter_input(INPUT_POST, 'AnsFocus_dyn_label', FILTER_SANITIZE_STRING);
}
		
$label = addslashes($label);
		
$_SESSION['qs-label']["{$_SESSION['entry_key_practiceexam-survey']}"]["{$label}"] = $AnsFocus;
$_SESSION['qs-label']["{$_SESSION['entry_key_practiceexam-survey']}"]['AnsFocus'] = $label;
$_SESSION['qs-label']["{$_SESSION['entry_key_practiceexam-survey']}"]['AnsFocus_pageTitle'] = "Practice Exam Survey";

// agree - checkbox
if(isset($_POST['agree']) && $_POST['agree'] != '') {
	$agree = isset($_POST['agree']) ? $_POST['agree'] : '';
	$_SESSION['agree'] = $agree;
} else {
	if(1 == 1){ // Is Field Validation Method Set to: Field Only Validates When Visible?
		$_SESSION['agree'] = null;
	} else {
		$error = '1'; $valError .= 'agree is required.<br/>';
	}
}
if(isset($_SESSION['agree_is'])) { $_SESSION['agree_is'] = 0; }

	if(isset($_SESSION['agree_processed'])) { $_SESSION['agree_processed'] = true; }$agree = isset($_SESSION['agree']) ? $_SESSION['agree'] : '';
$_SESSION['qs']["{$_SESSION['entry_key_practiceexam-survey']}"]['agree'] = $agree;
$_SESSION['qs-entities']["{$_SESSION['entry_key_practiceexam-survey']}"]['agree'] = is_array($agree) ? htmlentities(implode(':', $agree)) : htmlentities($agree);		
		
// Label Processing
		
$label = "agree";
		
if(isset($_POST['agree_dyn_label']) && $_POST['agree_dyn_label'] != ""){
	$label = filter_input(INPUT_POST, 'agree_dyn_label', FILTER_SANITIZE_STRING);
}
		
$label = addslashes($label);
		
$_SESSION['qs-label']["{$_SESSION['entry_key_practiceexam-survey']}"]["{$label}"] = $agree;
$_SESSION['qs-label']["{$_SESSION['entry_key_practiceexam-survey']}"]['agree'] = $label;
$_SESSION['qs-label']["{$_SESSION['entry_key_practiceexam-survey']}"]['agree_pageTitle'] = "Practice Exam Survey";

// examID - hidden
if(isset($_POST['examID']) && $_POST['examID'] != '') {
	$examID = isset($_POST['examID']) ? $_POST['examID'] : 0;
	$_SESSION['examID'] = $examID;
	if(isset($_SESSION['examID_is'])) { $_SESSION['examID_is'] = 0; }
	if(isset($_SESSION['examID_processed'])) { $_SESSION['examID_processed'] = true; }
} else {
	$_SESSION['examID'] = null;
}
$examID = isset($_SESSION['examID']) ? $_SESSION['examID'] : '';
$_SESSION['qs']["{$_SESSION['entry_key_practiceexam-survey']}"]['examID'] = $examID;
$_SESSION['qs-entities']["{$_SESSION['entry_key_practiceexam-survey']}"]['examID'] = is_array($examID) ? htmlentities(implode(':', $examID)) : htmlentities($examID);

// Label Processing

$label = "Hidden Field";

if(isset($_POST['examID_dyn_label']) && $_POST['examID_dyn_label'] != ""){
	$label = filter_input(INPUT_POST, 'examID_dyn_label', FILTER_SANITIZE_STRING);
}
			
$label = addslashes($label);

$_SESSION['qs-label']["{$_SESSION['entry_key_practiceexam-survey']}"]['examID'] = $label;
$_SESSION['qs-label']["{$_SESSION['entry_key_practiceexam-survey']}"]['examID_pageTitle'] = "Practice Exam Survey";

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
savePersistantValues(0, $db_key, 0, 'page1.php');
		


$sid_url = "";

if(defined('SID'))
	$sid_url = (strlen(SID) ? ('?' . htmlspecialchars(SID)) : '');

if($error){
	
	// remove pages from valid list. 
	if(isset($_SESSION['pages']['page1.php'])) { 
		unset($_SESSION['pages']['page1.php']);
		unset($_SESSION['pages-passed']["{$_SESSION['entry_key_practiceexam-survey']}"]['page0.php']);
	} 
	
	$_SESSION["e_message"] = $valError;
	header("Location: {$_SESSION['MAX_PATH_PROC']}page0.php{$sid_url}");
	return;
} else {
	$_SESSION['pages']['page1.php'] = 'pass';
	
	$_SESSION['pages-passed']["{$_SESSION['entry_key_practiceexam-survey']}"]['page0.php'] = 'pass';
	
	// custom route code
	
	
	// conditional route code
	
	
	// default action
	header("Location: {$_SESSION['MAX_PATH_PROC']}page1.php{$sid_url}");
	return;
}

?>