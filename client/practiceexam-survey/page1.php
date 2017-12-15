<?php
session_start();
$examID = $_SESSION['qs']["{$_SESSION['entry_key_practiceexam-survey']}"]['examID'];

require_once '/home/afonseca/public_html/amember/library/Am/Lite.php';

$username = Am_Lite::getInstance()->getUsername();
$email = Am_Lite::getInstance()->getEmail();

?>
<?php 
//--
// RackForms v1.0 PHP Form Submit Script - Generated: July 17, 2017
//--
if(function_exists('ini_set')){
	ini_set('display_errors', 0); // Change to 1 to display all error messages.
	ini_set('error_reporting', E_ALL);
}
	
// Start our main session

if(!session_id()) { session_start(); }

$error = 0; $valError = "";

// Form Page Submit Security
$domain_list = explode(',',"");

// Form Page Security
$ip_limit = 0;
$job_id = '60';
$ip_limit_message = <<<EOT
Sorry, form entry limit reached.
EOT;
$ip_limit_duration = 0;
$delta = 3155692600;

$active = 1;
$active_message = <<<EOT
Sorry, this form is currently disabled.
EOT;

// Global Timestamps
$timestamp = time();
$datetime = date('Y-m-d  H:i:s', time());

// Visitor IP
$remote_ip = $_SERVER['REMOTE_ADDR'];

if(!isset($_SESSION['MAX_PATH']))
	$_SESSION['MAX_PATH'] = "";

include_once "{$_SESSION['MAX_PATH']}security/secure_submit.php";
include_once "{$_SESSION['MAX_PATH']}lib/utility.php";

// Build 693 - We now include database code by default.
if(file_exists("{$_SESSION['MAX_PATH']}Database.php")){
	@include_once "{$_SESSION['MAX_PATH']}Database.php";
}

// Required Pages Logic.
$required_pages = array('page0.php');

// check pages
foreach($required_pages as $rp){
	if($_SESSION['pages-passed']["{$_SESSION['entry_key_practiceexam-survey']}"]["{$rp}"] != 'pass'){
	
$message = <<<EOD
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Submission Error Page</title>
<style type="text/css">
a { 
text-decoration:none;	
}
</style>			
</head>
<body>
<link href='//fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
				
<div style="padding:15px; background-color:#f8f8f8; border-radius:10px; border:solid 1px #e2e2e2; font-family: 'Open Sans', sans-serif;">
	
	<p style="font-size:14pt;">Submission Notice: All required pages must be completed before we can submit this form.</p>

	<span style="font-size:9pt;"><strong>Form users:</strong> Please <a href="{$rp}">click here</a> to try the form submission again.</span>
				
	<br/><br/>
				
	<span style="font-size:9pt;"><strong>Form creators:</strong> Please be sure to check the: "Page Not Required / Allow Direct Access?" box under: "Form Properties" for EVERY page that can be skipped or is not required for the ENTIRE job.</span>
	<p style="font-size:9pt;"><span>For this job, that means starting with the page: <strong>{$rp}</strong></span></p>
	<span style="font-size:9pt;">Please see <a href="https://www.rackforms.com/documentation/rackforms/page-elements/sortable.php#FormPropertiesDirectAccess" target="_blank">this link</a> for more details.</span>
</div>
</body>
</html>
EOD;

		echo $message; die();
	}
}
		

if(isset($_SESSION['qs']) && isset($_SESSION['entry_key_practiceexam-survey'])) {

	// Build 689 - Always Called.
	clearPersistantValues(0, $job_id);
    
    // Build 805
    if(1 == 0){
        init_stats($job_id, "page1.php", 'confirm');
    }

	// Utility Function Clean up this session's inline data -- remove all field keys, then the qs and qs-label, followed by entry_key and page data
	function clear_fb_session(){
	
		// Build 866 - Remove Any ACI Transaction Data
		if(isset($_SESSION['aci-level1']))
			unset($_SESSION['aci-level1']); 
				
		// Build 770 - Remove Any Stripe Transaction Data
		if(isset($_SESSION['stripe']))
			unset($_SESSION['stripe']); 

		// Remove all singleton session data fields (selected items etc)
		$named_sesison_vars = array_keys($_SESSION['qs']["{$_SESSION['entry_key_practiceexam-survey']}"]);
		foreach($named_sesison_vars as $var){
			// fields
			unset($_SESSION["{$var}"]);
			// isset
			unset($_SESSION["{$var}_is"]);
			// _processed - Build 712
			unset($_SESSION["{$var}_processed"]);
		}
	
		if(isset($_SESSION['qs']["{$_SESSION['entry_key_practiceexam-survey']}"])){
			foreach($_SESSION['qs']["{$_SESSION['entry_key_practiceexam-survey']}"] as $key=>$value){
				unset($_SESSION[$key]);
			}
		}
		
		// Build 764
		if(isset($_SESSION['fb_ecomm_practiceexam-survey'])){
			unset($_SESSION['fb_ecomm_practiceexam-survey']);
		}
		
		if(isset($_SESSION['qs']["{$_SESSION['entry_key_practiceexam-survey']}"]['signatures'])){
			unset($_SESSION['qs']["{$_SESSION['entry_key_practiceexam-survey']}"]['signatures']);
		}
		
		unset($_SESSION['pages']);
		unset($_SESSION['pages-passed']["{$_SESSION['entry_key_practiceexam-survey']}"]);
		
		clean_output_location('tmp');
		clean_output_location('lib/jquery-upload/server/php/files/' . $_SESSION['entry_key_practiceexam-survey']); // Buld 860
		
		if(isset($_SESSION['qs']["{$_SESSION['entry_key_practiceexam-survey']}"])) { unset($_SESSION['qs']["{$_SESSION['entry_key_practiceexam-survey']}"]); }
		if(isset($_SESSION['qs-entity']["{$_SESSION['entry_key_practiceexam-survey']}"])) { unset($_SESSION['qs-entity']["{$_SESSION['entry_key_practiceexam-survey']}"]); }
		if(isset($_SESSION['qs-label']["{$_SESSION['entry_key_practiceexam-survey']}"])) { unset($_SESSION['qs-label']["{$_SESSION['entry_key_practiceexam-survey']}"]); }
		if(isset($_SESSION['entry_key_practiceexam-survey'])) {unset($_SESSION['entry_key_practiceexam-survey']); }

		if(isset($_SESSION['fielded_data_array'])) { unset($_SESSION['fielded_data_array']); }
		
		// Build 757 - unset main indentifiers, which is trnasformed at build time to specific element for this form.
		unset($_SESSION['entry_key_practiceexam-survey']);
				
		// Build 836
		if(isset($_SESSION['fb_entry_id_auto']))
			unset($_SESSION['fb_entry_id_auto']);
		
	}
	// call session clear code



/**
 * RackForms SQL+ Process - Uses custom SQL to write to a database of your choosing.
 **/

$db_type = 'mysql';
$db_host = 'localhost';
$mysql_socket = '';
$mysql_port = '';
$dbdsn = '';
$db_user = 'afonseca_TEP';
$db_pass = 'pl_aX[FWXIK}';
$db_catalog = 'afonseca_TEP';

include 'Database.php';
$debug = 0; // Change to 1 or 2 to see debug info if you run into problems executing your query (may need to look at html page source to see error).

// Optional DB Connector File - if not specified RackForms will use config.php via Database.php include
if(file_exists('')){
	@include '';
}

// query
$sql_practiceexam_survey = "practiceexam_survey";
$dbh = new Database();

$jobname = 'practiceexam_survey';
$session_id = session_id();
$timestamp = time();
$datetime = date('Y-m-d  H:i:s', time());

// Used For Security Processing
$remote_ip = $_SERVER['REMOTE_ADDR'];

if(isset($_SESSION['qs']["{$_SESSION['entry_key_practiceexam-survey']}"]) && $_SESSION['qs']["{$_SESSION['entry_key_practiceexam-survey']}"] != ''){

// check for and replace array based variables
$vars = array('AnsRealistic','AnsFair','AnsBreaks','AnsMindInto','AnsMindDuring','AnsMindAfter','AnsFocus');
foreach($vars as $var){
	if(isset($_SESSION['qs']["{$_SESSION['entry_key_practiceexam-survey']}"][$var])){
		if(is_array($_SESSION['qs']["{$_SESSION['entry_key_practiceexam-survey']}"][$var])){
			$field_items = '';
			foreach($_SESSION['qs']["{$_SESSION['entry_key_practiceexam-survey']}"][$var] as $key=>$v){
				if($key != 0){ $field_items .= '|'; }
				$field_items .= $v;
			}
			$_SESSION['qs']["{$_SESSION['entry_key_practiceexam-survey']}"][$var] = $field_items;
		}
	}
}


$sql_practiceexam_survey = "INSERT INTO Exam_Survey (realistic, fair, tookBreaks, mindsetStart, mindsetDuring, mindsetAfter, lostFocus) VALUES (?,?,?,?,?,?,?)";

$params = array(isset($_SESSION['qs']["{$_SESSION['entry_key_practiceexam-survey']}"]['AnsRealistic']) ? $_SESSION['qs']["{$_SESSION['entry_key_practiceexam-survey']}"]['AnsRealistic'] : '',
				isset($_SESSION['qs']["{$_SESSION['entry_key_practiceexam-survey']}"]['AnsFair']) ? $_SESSION['qs']["{$_SESSION['entry_key_practiceexam-survey']}"]['AnsFair'] : '',
				isset($_SESSION['qs']["{$_SESSION['entry_key_practiceexam-survey']}"]['AnsBreaks']) ? $_SESSION['qs']["{$_SESSION['entry_key_practiceexam-survey']}"]['AnsBreaks'] : '',
				isset($_SESSION['qs']["{$_SESSION['entry_key_practiceexam-survey']}"]['AnsMindInto']) ? $_SESSION['qs']["{$_SESSION['entry_key_practiceexam-survey']}"]['AnsMindInto'] : '',
				isset($_SESSION['qs']["{$_SESSION['entry_key_practiceexam-survey']}"]['AnsMindDuring']) ? $_SESSION['qs']["{$_SESSION['entry_key_practiceexam-survey']}"]['AnsMindDuring'] : '',
				isset($_SESSION['qs']["{$_SESSION['entry_key_practiceexam-survey']}"]['AnsMindAfter']) ? $_SESSION['qs']["{$_SESSION['entry_key_practiceexam-survey']}"]['AnsMindAfter'] : '',
				isset($_SESSION['qs']["{$_SESSION['entry_key_practiceexam-survey']}"]['AnsFocus']) ? $_SESSION['qs']["{$_SESSION['entry_key_practiceexam-survey']}"]['AnsFocus'] : '');



// Default SQL Call - If The Last Argument is 1 Then this call will attempt to return the MySQL lastInsertId()
$ret_val = $dbh->pdo_procedure_params($debug, $sql_practiceexam_survey, $db_host, $db_type, $mysql_socket, $mysql_port, $dbdsn, $db_user, $db_pass, $db_catalog, $params, 1, 1);
$fb_result_ret_val = $ret_val;
$_SESSION['sqlplus_retval'] = $ret_val;



if($debug) { echo '<pre>$ret_val = ' . $ret_val . '</pre>'; }

}

// Custom Code After Query
require_once '/home/afonseca/public_html/products/practice-exam/survey.php';

$isSurveySaved = recordSurvey($examID, $ret_val);

if ($isSurveySaved)
{
  echo '<script>localStorage.removeItem("ExamState");</script>';
}


} else { die(); }
?>


<?php

// PDF Rendering Flags.
$PAGE_IS_PDF = false;
$PDF_LIBRARY = "";

?>
		<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" content="" />
<meta name="keywords" content="" />
<title>Practice Exam Survey</title>
<link rel="stylesheet" type="text/css" href="<?php echo $_SESSION['MAX_PATH']; ?>formpage.css" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo $_SESSION['MAX_PATH']; ?>print.css" media="print" />

<script type="text/javascript" src="<?php echo $_SESSION['MAX_PATH']; ?>lib/utility.js"></script>

<script type="text/javascript" src="<?php echo $_SESSION['MAX_PATH']; ?>js/jquery/jquery-full.js"></script>
<noscript>
  <meta http-equiv="refresh" content="0;url=http://www.enable-javascript.com/">
  <style>
    #rackforms-output-div-exam-form { display:none; }
  </style>
</noscript>

<script>
  var amUser = "<?php echo $username ?>";
  var amEmail = "<?php echo $email ?>";
</script>

<!-- Javascript -->
<script src="https://therapyexamprep.com/products/practice-exam/vendor/flat-ui/flat-ui.min.js"></script>

<script src="https://therapyexamprep.com/products/practice-exam/resources/js/flat-ui-app.js"></script>
<script src="https://therapyexamprep.com/products/practice-exam/resources/js/survey.min.js"></script>
<script src="https://therapyexamprep.com/products/practice-exam/resources/js/practice-exam-ui.js"></script>

<!-- Stylesheets -->
<link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="https://therapyexamprep.com/products/practice-exam/vendor/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="https://therapyexamprep.com/products/practice-exam/vendor/flat-ui/flat-ui.min.css">

<link rel="stylesheet" type="text/css" href="https://therapyexamprep.com/products/practice-exam/resources/css/practice-exam.css">
<style type="text/css">
body { margin:5px;  font-family: 'Helvetica'; }
html { margin:5px; }
</style>

<style type="text/css">		
/* link colors */
.rackforms-output-div a:link { color:#0D00AE; text-decoration:none; }
.rackforms-output-div a:visited { color:#0D00AE; text-decoration:none; }
.rackforms-output-div a:hover { color:#0D00AE; text-decoration:underline; }
.rackforms-output-div a:active { color:#0D00AE; text-decoration:none; }
</style>		


</head>
<body onload="">
<a name="top" id="top"></a>

<div id="rackforms-output-div" class="rackforms-output-div" style=" font-family: DejaVu Sans, Arial, Helvetica, sans-serif;  background-color: rgba(49,110,180,1);     width:500px;   ">
<?php
$visible = '';
$enabled = '';
?>

<!-- Form Field Start -->
<div id="fb_fld-confirmationHeader" rf-field="true" class="confirmationHeaderWrapper" style="float:left; width:500px; margin-bottom:9px; <?php echo $visible; ?>" >

<?php
// Process Array Variables.
if(!isset($array_vars_processed)){ $vars = array(); process_array_variables($vars, ', '); $array_vars_processed = true; }
?>
		<div class="section-head " style="color:#222222; font-size:15pt; font-weight:normal; width:100%;    border-radius:0px;  text-align:left;   padding-left:0px;  padding-right:0px;  padding-top:0px;  padding-bottom:0px;  "><div id="TopHeader" class="bucket" style="position:relative; height:69px; width:100%; ">

<!-- Bucket Item - Responsive Form Field Start -->
<div id="fb_fld-ExamName2" class="" style=" float:left; width:33%; z-index:1;">



<div class="section-head " style="color:#d3d3d3; font-size:16px; font-weight:normal; width:95%;    border-radius:0px;  text-align:left;   padding-left:0px;  padding-right:0px;  padding-top:0px;  padding-bottom:0px;  ">
Practice Exam
</div>

</div>
<!-- Form Field End -->


<!-- Bucket Item - Responsive Form Field Start -->
<div id="fb_fld-Logo2" class="" style=" float:left; width:33%; z-index:1;">



<div class="section-head " style="color:#d3d3d3; font-size:16px; font-weight:normal; width:100%;    border-radius:0px;  text-align:center;   padding-left:0px;  padding-right:0px;  padding-top:0px;  padding-bottom:0px;  ">
<span id="logo"></span>
</div>

</div>
<!-- Form Field End -->


<!-- Bucket Item - Responsive Form Field Start -->
<div id="fb_fld-user2" class="" style=" float:left; width:33%; z-index:1;">



<div class="section-head " style="color:#d3d3d3; font-size:16px; font-weight:normal; width:100%;    border-radius:0px;  text-align:right;   padding-left:0px;  padding-right:0px;  padding-top:0px;  padding-bottom:0px;  ">
<p><span id="username">John Doe</span><br><span id="email">jdoe@home.net</span></p>
</div>

</div>
<!-- Form Field End -->


</div></div>
</div>
<!-- Form Field End -->

<?php
$visible = '';
$enabled = '';
?>

<!-- Form Field Start -->
<div id="fb_fld-surveyComplete" rf-field="true" class="" style="float:left; width:500px; margin-bottom:9px; <?php echo $visible; ?>" >

<?php
// Process Array Variables.
if(!isset($array_vars_processed)){ $vars = array(); process_array_variables($vars, ', '); $array_vars_processed = true; }
?>
		<div class="section-head " style="color:#d3d3d3; font-size:16px; font-weight:normal; width:100%;    border-radius:0px;  text-align:left;   padding-left:0px;  padding-right:0px;  padding-top:0px;  padding-bottom:0px;  "><h1>Survey Complete</h1>
</div>
</div>
<!-- Form Field End -->

<?php
$visible = '';
$enabled = '';
?>

<!-- Form Field Start -->
<div id="fb_fld-p_survey_responses_have" rf-field="true" class="" style="float:left; width:500px; margin-bottom:9px; <?php echo $visible; ?>" >

<?php
// Process Array Variables.
if(!isset($array_vars_processed)){ $vars = array(); process_array_variables($vars, ', '); $array_vars_processed = true; }
?>
		<div class="body-copy" style="color:#d3d3d3; font-size:16px; font-weight:normal; width:100%;    border-radius:0px;  text-align:left;   padding-left:0px;  padding-right:0px;  padding-top:0px;  padding-bottom:0px;   "><p>Your survey responses have been saved and this concludes your practice exam submission. Exam results will be available in your Practice Exams page. You may now close this window.</p></div>
</div>
<!-- Form Field End -->


	<div style="clear:both;"></div>
</div>


</body>
</html>



<?php

if(!isset($has_timed_redirect)){

	// clean up this session's data -- remove all field keys, then the qs and qs-label, followed by entry_key and page data
	
	// Build 866 - Remove Any ACI Transaction Data
	if(isset($_SESSION['aci-level1']))
		unset($_SESSION['aci-level1']); 
		
	// Build 770 - Remove Any Stripe Transaction Data
	if(isset($_SESSION['stripe']))
		unset($_SESSION['stripe']); 
	
	// Remove all singleton session data fields (selected items etc)
	$named_sesison_vars = array_keys($_SESSION['qs']["{$_SESSION['entry_key_practiceexam-survey']}"]);
	
	foreach($named_sesison_vars as $var){
		// fields
		unset($_SESSION["{$var}"]);
		// isset
		unset($_SESSION["{$var}_is"]);
		// _processed - Build 712
		unset($_SESSION["{$var}_processed"]);
	}
	
	if(isset($_SESSION['qs']["{$_SESSION['entry_key_practiceexam-survey']}"])){
		foreach($_SESSION['qs']["{$_SESSION['entry_key_practiceexam-survey']}"] as $key=>$value){
			unset($_SESSION[$key]);
		}
	}
	
	// Build 764
	if(isset($_SESSION['fb_ecomm_practiceexam-survey'])){
		unset($_SESSION['fb_ecomm_practiceexam-survey']);
	}
	
	if(isset($_SESSION['qs']["{$_SESSION['entry_key_practiceexam-survey']}"]['signatures'])){
		unset($_SESSION['qs']["{$_SESSION['entry_key_practiceexam-survey']}"]['signatures']);
	}
	
	unset($_SESSION['pages']);
	unset($_SESSION['pages-passed']["{$_SESSION['entry_key_practiceexam-survey']}"]);
	
	clean_output_location('tmp');
	clean_output_location('lib/jquery-upload/server/php/files/' . $_SESSION['entry_key_practiceexam-survey']); // Buld 860
	
	if(isset($_SESSION['qs']["{$_SESSION['entry_key_practiceexam-survey']}"])) { unset($_SESSION['qs']["{$_SESSION['entry_key_practiceexam-survey']}"]); }
	if(isset($_SESSION['qs-entity']["{$_SESSION['entry_key_practiceexam-survey']}"])) { unset($_SESSION['qs-entity']["{$_SESSION['entry_key_practiceexam-survey']}"]); }
	if(isset($_SESSION['qs-label']["{$_SESSION['entry_key_practiceexam-survey']}"])) { unset($_SESSION['qs-label']["{$_SESSION['entry_key_practiceexam-survey']}"]); }
	if(isset($_SESSION['entry_key_practiceexam-survey'])) {unset($_SESSION['entry_key_practiceexam-survey']); }
	
	if(isset($_SESSION['fielded_data_array'])) { unset($_SESSION['fielded_data_array']); }
	
	// Build 757 - unset main indentifiers, which is trnasformed at build time to specific element for this form.
	unset($_SESSION['entry_key_practiceexam-survey']);
		
	// Build 836
	if(isset($_SESSION['fb_entry_id_auto']))
		unset($_SESSION['fb_entry_id_auto']);

} // $has_timed_redirect

?>