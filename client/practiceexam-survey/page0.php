<?php
require_once '/home/afonseca/public_html/amember/library/Am/Lite.php';

$username = Am_Lite::getInstance()->getUsername();
$email = Am_Lite::getInstance()->getEmail();
?>
<?php
//--
// PHP Page Script - Generated: July 17, 2017
//--


if(function_exists('ini_set')){
	ini_set('display_errors', 0); // Change to 1 to display all error messages.
	ini_set('error_reporting', E_ALL);
}

// SID Support - Redirect and append SID if needed. 
// Allows SESSION Vars to be saved on first page.
// Server MUST have session.use_trans_sid enabled.

$sid_url = "";

if(defined('SID'))
	$sid_url = (strlen(SID) ? ('?' . htmlspecialchars(SID)) : '');


// Start our main session

if(!session_id()) { session_start(); }



// Path info for PHP Include
$_SESSION['MAX_PATH'] = '';
$ct_tmp = '';
$ct = substr_count('', "/");
$_SESSION['MAX_PATH_PROC'] = './';
// Build 632 - Refine this check
if($ct != 0){ // if a PHP Export Path is set, we need to create a path *back* to the include calling file
	while($ct != 0){
		$ct_tmp .= '../';
		$ct--;
	}
	$_SESSION['MAX_PATH_PROC'] = $ct_tmp;
}
// echo $_SESSION['MAX_PATH_PROC']; // Uncomment to see which path RackForms is using to process pages

// IE P3P Policy Header - must send to allow 3rd party cookies (when form page used as iFrame include)
header('P3P: CP="IDC DSP COR CURa ADMa OUR IND PHY ONL COM STA"');

// Build 695 - We now create this key here as well as on process pages.
$_SESSION['entry_key_practiceexam-survey'] = isset($_SESSION['entry_key_practiceexam-survey']) ? $_SESSION['entry_key_practiceexam-survey'] : md5(time() + rand(10000, 1000000));

// Form Page Security
$domain_list = explode(',',"");
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

// Login Logic
$fb_login = new stdClass();
$fb_login->login = 0;
$fb_login->persistent_login = 0;
$fb_login->persistent_login_job_id = 60;
$fb_login->username = "";
$fb_login->password = "";
$fb_login->login_attempts = 2;
$fb_login->login_message = <<<EOT
Please login to continue.
EOT;
$fb_login->login_error_message = <<<EOT
Invalid login. Please try again.
EOT;
$fb_login->login_attempts_error_message = <<<EOT
Maximum login attempts exceeded.
EOT;
$fb_login->redirect = "page0.php";

$_SESSION['qs']["{$_SESSION['entry_key_practiceexam-survey']}"]['fb_login'] = $fb_login;


// Global Timestamps, Etc.
$timestamp = time();
$datetime = date('Y-m-d  H:i:s', time());

// Visitor IP
$remote_ip = $_SERVER['REMOTE_ADDR'];

include_once "{$_SESSION['MAX_PATH']}security/secure_page.php";
include_once "{$_SESSION['MAX_PATH']}lib/utility.php";

// Build 693 - We now include database code by default.
if(file_exists("{$_SESSION['MAX_PATH']}Database.php")){
	@include_once "{$_SESSION['MAX_PATH']}Database.php";
}

// Build 836
if(0 == 3){
	if(!isset($_SESSION['fb_entry_id_auto'])){
	
		$_SESSION ['fb_entry_id_auto'] = isset($_GET['RID']) ? filter_input(INPUT_GET, 'RID', FILTER_SANITIZE_STRING) : "";
		
		if($_SESSION['fb_entry_id_auto'] == ""){
			$_SESSION['fb_entry_id_auto'] = randomPassword();
		}
	}
}

// Build 689
loadPersistantValues(0, $job_id, "page0.php");

// Build 805
if(1 == 0){
    init_stats($job_id, "page0.php", 'form');
}

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
<script type="text/javascript" src="<?php echo $_SESSION['MAX_PATH']; ?>js/jquery/jquery-full.js"></script>


<!-- tinymce -->

<!-- val script -->
<script type="text/javascript">
	var phppath = '<?php echo $_SESSION['MAX_PATH']; ?>';
	var pageName = 'page0.xml';
	// error logic
	var showMessage = 0;
	var showAlert = 1;
	var showDefault = 1;
	var errorStyle = 0;
	var errorColor = "#EB0000";
	var jspopup_errormessage = "You have not completed this form correctly.\nPlease go back and review your answers.";
	var errorBorderStyles = [];
	var layout = 0;
	var tablemode = 0;
	
	
</script>

<script type="text/javascript" src="<?php echo $_SESSION['MAX_PATH']; ?>xmlform.js"></script>
<script type="text/javascript" src="<?php echo $_SESSION['MAX_PATH']; ?>conditional.js"></script>
<script type="text/javascript" src="<?php echo $_SESSION['MAX_PATH']; ?>lib/utility.js"></script>









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
<script src='https://therapyexamprep.com/products/practice-exam/vendor/modernizr-custom.js'></script>
<script src="https://therapyexamprep.com/products/practice-exam/vendor/flat-ui/flat-ui.min.js"></script>

<script src="https://therapyexamprep.com/products/practice-exam/resources/js/flat-ui-app.js"></script>
<script src="https://therapyexamprep.com/products/practice-exam/resources/js/survey.min.js"></script>



<!-- Stylesheets -->
<link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="https://therapyexamprep.com/products/practice-exam/vendor/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="https://therapyexamprep.com/products/practice-exam/vendor/flat-ui/flat-ui.min.css">

<link rel="stylesheet" type="text/css" href="https://therapyexamprep.com/products/practice-exam/resources/css/practice-exam.css">



<style type="text/css">
body { margin:5px;  font-family: 'Helvetica'; }
html { margin:5px; }
</style>

<!-- custom style -->
<style type="text/css">

span.error { background: #EB0000; } div.error { background-color:#EB0000; }

.req-star { color: #cc0000  }

div.validation-style-3-line { border-top: 1px solid #EB0000; padding-top: 4px; height:1px; width:100%; position:absolute; top:0px; }
span.validation-style-3-line { border-top: 1px solid #EB0000; padding-top: 4px; height:1px; width:100%; position:absolute; top:0px; }

.validation-style-3-icon { background: url(icons/validation/style-3/warning-icon-simple-16.png) no-repeat scroll right center; position:absolute; top:-17px; right:2px; height:16px; width:16px; }
.validation-style-3-message { position:absolute; top:-15px; right:26px; height:16px; font-size:10px; font-weight:bold; color:#A6A6A6 }

</style>
<!-- end custom style -->

<style type="text/css">

/* link colors */
.rackforms-output-div a:link { color:#0D00AE; text-decoration:none; }
.rackforms-output-div a:visited { color:#0D00AE; text-decoration:none; }
.rackforms-output-div a:hover { color:#0D00AE; text-decoration:underline; }
.rackforms-output-div a:active { color:#0D00AE; text-decoration:none; }

span.errormsg { color:#A6A6A6; }
</style>

<!--[if lte IE 8]>
<style>
.btn { 	margin: 0; padding: 0 .70em; width: auto; overflow: visible; }
</style>
<![endif]-->


</head>
<body onload="importXML(getXmlUrl(), parseFormXml ,checkCookie()) ; ">
<a id="top"></a>
<!-- OUTPUT_START -->
<div id="rackforms-output-div-exam-form" class="rackforms-output-div col-lg-9 ui form" style=" font-family: DejaVu Sans, Arial, Helvetica, sans-serif;       width:94%;   ">
<form class="rackforms-output-sortable" action="<?php echo $_SESSION['MAX_PATH']; ?>page0_process.php<?php echo $sid_url; ?>" method="post" enctype="application/x-www-form-urlencoded" name="page0" id="exam-form" target="_self" >
<?php
$visible = '';
$enabled = '';
?>

<!-- Form Field Start -->
<div id="fb_fld-TopHeader" rf-field="true" class="" style="float:left; width:100%; margin-bottom:9px; <?php echo $visible; ?>" >


<!-- bucket start -->
<div id="TopHeader" class="bucket" style="position:relative; height:69px; width:; " >
<?php
$visible = '';
$enabled= '';
?>

<!-- Bucket Item - Responsive Form Field Start -->
<div id="fb_fld-ExamName" rf-field="true" class="" style="<?php echo $visible; ?> float:left; width:33%; z-index:1; margin-bottom:15px">
				
				
<?php
// Process Array Variables.
if(!isset($array_vars_processed)){ $vars = array(); process_array_variables($vars, ', '); $array_vars_processed = true; }
?>
		
<div class="section-head " style="color:#d3d3d3; font-size:16px; font-weight:normal; width:95%;    border-radius:0px;  text-align:left;   padding-left:0px;  padding-right:0px;  padding-top:0px;  padding-bottom:0px;  ">
Practice Exam
</div>

</div>
<!-- Form Field End -->

<?php
$visible = '';
$enabled= '';
?>

<!-- Bucket Item - Responsive Form Field Start -->
<div id="fb_fld-Logo" rf-field="true" class="" style="<?php echo $visible; ?> float:left; width:33%; z-index:1; margin-bottom:15px">
				
				
<?php
// Process Array Variables.
if(!isset($array_vars_processed)){ $vars = array(); process_array_variables($vars, ', '); $array_vars_processed = true; }
?>
		
<div class="section-head " style="color:#d3d3d3; font-size:16px; font-weight:normal; width:100%;    border-radius:0px;  text-align:center;   padding-left:0px;  padding-right:0px;  padding-top:0px;  padding-bottom:0px;  ">
<span id="logo"></span>
</div>

</div>
<!-- Form Field End -->

<?php
$visible = '';
$enabled= '';
?>

<!-- Bucket Item - Responsive Form Field Start -->
<div id="fb_fld-user" rf-field="true" class="" style="<?php echo $visible; ?> float:left; width:33%; z-index:1; margin-bottom:15px">
				
				
<?php
// Process Array Variables.
if(!isset($array_vars_processed)){ $vars = array(); process_array_variables($vars, ', '); $array_vars_processed = true; }
?>
		
<div class="section-head " style="color:#d3d3d3; font-size:16px; font-weight:normal; width:100%;    border-radius:0px;  text-align:right;   padding-left:0px;  padding-right:0px;  padding-top:0px;  padding-bottom:0px;  ">
<p><span id="username">John Doe</span><br/><span id="email">jdoe@home.net</span></p>
</div>

</div>
<!-- Form Field End -->


</div><!-- bucket end -->


</div>
<!-- Form Field End -->


<div style="clear:both;"></div><!-- Clear -->

<?php
$visible = '';
$enabled = '';
?>

<!-- Form Field Start -->
<div id="fb_fld-HeaderView" rf-field="true" class="" style="float:left; width:100%; margin-bottom:9px; <?php echo $visible; ?>" >


<!-- bucket start -->
<div id="HeaderView" class="bucket" style="position:relative; height:69px; width:; " >
<?php
$visible = '';
$enabled= '';
?>

<!-- Bucket Item - Responsive Form Field Start -->
<div id="fb_fld-questionNumber" rf-field="true" class="" style="<?php echo $visible; ?> float:left; width:100%; z-index:1; margin-bottom:15px">
				
				
<?php
// Process Array Variables.
if(!isset($array_vars_processed)){ $vars = array(); process_array_variables($vars, ', '); $array_vars_processed = true; }
?>
		
<div class="section-head " style="color:#d3d3d3; font-size:16px; font-weight:normal; width:100%;    border-radius:0px;  text-align:center;   padding-left:0px;  padding-right:0px;  padding-top:0px;  padding-bottom:0px;  ">
<span id="questionDisplay">Exit Survey</span>
</div>

</div>
<!-- Form Field End -->


</div><!-- bucket end -->


</div>
<!-- Form Field End -->


<div style="clear:both;"></div><!-- Clear -->

<?php
$visible = '';
$enabled = '';
?>

<!-- Form Field Start -->
<div id="fb_fld-SummaryView" rf-field="true" class="" style="float:left; width:; margin-bottom:9px; <?php echo $visible; ?>" >

<!-- Block Code End -->


</div>
<!-- Form Field End -->


<div style="clear:both;"></div><!-- Clear -->

<?php
$visible = '';
$enabled = '';
?>

<!-- Form Field Start -->
<div id="fb_fld-bodycopy47" rf-field="true" class="Question" style="float:left; width:100%; margin-bottom:9px; <?php echo $visible; ?>" >

<?php
// Process Array Variables.
if(!isset($array_vars_processed)){ $vars = array(); process_array_variables($vars, ', '); $array_vars_processed = true; }
?>
		
<div class="body-copy " style="color:#d3d3d3; font-size:16px; font-weight:normal; width:100%;    border-radius:0px;  text-align:left;   padding-left:0px;  padding-right:0px;  padding-top:0px;  padding-bottom:0px;  ">
Please take a few moments to answer the following questions:
</div>

</div>
<!-- Form Field End -->


<div style="clear:both;"></div><!-- Clear -->

<?php
$visible = '';
$enabled = '';
?>

<!-- Form Field Start -->
<div id="fb_fld-headerExam" rf-field="true" class="Question" style="float:left; width:100%; margin-bottom:9px; <?php echo $visible; ?>" >

<?php
// Process Array Variables.
if(!isset($array_vars_processed)){ $vars = array(); process_array_variables($vars, ', '); $array_vars_processed = true; }
?>
		
<div class="body-copy " style="color:#d3d3d3; font-size:16px; font-weight:normal; width:100%;    border-radius:0px;  text-align:left;   padding-left:0px;  padding-right:0px;  padding-top:0px;  padding-bottom:0px;  ">
<h1>Exam</h1>
</div>

</div>
<!-- Form Field End -->


<div style="clear:both;"></div><!-- Clear -->

<?php
$visible = '';
$enabled = '';
?>

<!-- Form Field Start -->
<div id="fb_fld-bodycopy49" rf-field="true" class="Question" style="float:left; width:100%; margin-bottom:9px; <?php echo $visible; ?>" >

<?php
// Process Array Variables.
if(!isset($array_vars_processed)){ $vars = array(); process_array_variables($vars, ', '); $array_vars_processed = true; }
?>
		
<div class="body-copy " style="color:#d3d3d3; font-size:16px; font-weight:normal; width:100%;    border-radius:0px;  text-align:left;   padding-left:0px;  padding-right:0px;  padding-top:0px;  padding-bottom:0px;  ">
Did you feel this exam gave you a realistic simulation?
</div>

</div>
<!-- Form Field End -->


<div style="clear:both;"></div><!-- Clear -->

<?php
$visible = '';
$enabled = '';
?>

<!-- Form Field Start -->
<div id="fb_fld-AnsRealistic" rf-field="true" class="" style="float:left; width:100%; margin-bottom:9px; <?php echo $visible; ?>" >
<div class="fb-checkbox-wrapper" style="width:98%px">

	<!-- Form Element Start --><fieldset data-role="controlgroup" style="border:0; padding:0px; margin:0px;">
	<?php $_SESSION['AnsRealistic'] = isset($_SESSION['AnsRealistic']) ? $_SESSION['AnsRealistic'] : array(""); ?>

	<?php if(!isset($_SESSION['AnsRealistic_is'])) { $_SESSION['AnsRealistic_is'] = 1; } ?>
	<?php if(!isset($_SESSION['AnsRealistic_processed'])) { $_SESSION['AnsRealistic_processed'] = false; } ?>

<div class="heading-main" style="  color:#d3d3d3; font-size:16px;  float:left; width:9px;  font-weight:normal; "><span class="req-star">&#42;</span>&nbsp;
	<div style="position:relative">
		<div id="AnsRealistic-validation-style-3-line" class="validation-style-3-line" style="display:none;">&nbsp;</div>
		<div id="AnsRealistic-validation-style-3-icon" class="validation-style-3-icon" style="display:none;">&nbsp;</div>
		<div id="AnsRealistic-validation-style-3-message" class="validation-style-3-message errormsg" style="display:none;">&nbsp;</div>
	</div>
</div>
<div class="fbtooltip-AnsRealistic fbtooltip" style="display:none;"></div>



<?php $rowcount_AnsRealistic = 10; $count_row_AnsRealistic = 0; ?>
<?php
!isset($count_AnsRealistic) ? $count_AnsRealistic = 1 : $count_AnsRealistic++;
$count_row_AnsRealistic++;
$count_AnsRealistic == 1 || $count_row_AnsRealistic == 1 ? print '<div class="fb-multiselect-column grouped fields radio-column" style="width:; ; float:left;">' : print '';
?>

<?php if($count_AnsRealistic % 2 == 0 && false) { $alternate_row = true; } else { $alternate_row = false; } ?>
<div class="multiselect-item field" style="   margin: 1px;  align:left;  <?php if(false && $alternate_row) { ?> <?php } else if(!false) { echo ''; } ?> clear:both; ">
<div class="ui radio checkbox">
<label for="AnsRealistic0" class="radio-inline ui radio checkbox <?php $value = 'Yes'; ?> "  style=" color:#d3d3d3;  "  >
<input type="radio" name="AnsRealistic" id="AnsRealistic0"  value="Yes"  style=" color:#d3d3d3;  "  class="required "
									
<?php if($_SESSION['AnsRealistic'] == html_entity_decode('Yes', ENT_QUOTES) || 
	( ('Yes' == htmlentities('', ENT_QUOTES) || 'Yes' == '') && $_SESSION['AnsRealistic_processed'] == false)) { 
	echo "checked=\"checked\""; }
?>
  />
<span class="rf-multiselect-item-label">Yes</span>
</label> <br/>
</div><!-- Close Non-Selected DIV -->
</div><!-- Close Non-Multi-Select DIV -->
<?php if($count_row_AnsRealistic == $rowcount_AnsRealistic) { echo '</div><!-- Close Row DIV -->'; $count_row_AnsRealistic = 0; } ?>

<?php
!isset($count_AnsRealistic) ? $count_AnsRealistic = 1 : $count_AnsRealistic++;
$count_row_AnsRealistic++;
$count_AnsRealistic == 1 || $count_row_AnsRealistic == 1 ? print '<div class="fb-multiselect-column grouped fields radio-column" style="width:; ; float:left;">' : print '';
?>

<?php if($count_AnsRealistic % 2 == 0 && false) { $alternate_row = true; } else { $alternate_row = false; } ?>
<div class="multiselect-item field" style="   margin: 1px;  align:left;  <?php if(false && $alternate_row) { ?> <?php } else if(!false) { echo ''; } ?> clear:both; ">
<div class="ui radio checkbox">
<label for="AnsRealistic1" class="radio-inline ui radio checkbox <?php $value = 'No'; ?> "  style=" color:#d3d3d3;  "  >
<input type="radio" name="AnsRealistic" id="AnsRealistic1"  value="No"  style=" color:#d3d3d3;  "  class="required "
									
<?php if($_SESSION['AnsRealistic'] == html_entity_decode('No', ENT_QUOTES) || 
	( ('No' == htmlentities('', ENT_QUOTES) || 'No' == '') && $_SESSION['AnsRealistic_processed'] == false)) { 
	echo "checked=\"checked\""; }
?>
  />
<span class="rf-multiselect-item-label">No</span>
</label> <br/>
</div><!-- Close Non-Selected DIV -->
</div><!-- Close Non-Multi-Select DIV -->
<?php if($count_row_AnsRealistic == $rowcount_AnsRealistic) { echo '</div><!-- Close Row DIV -->'; $count_row_AnsRealistic = 0; } ?>

<?php if($count_row_AnsRealistic != 0) { echo '</div>'; } ?><?php
// Even if we have no results we still create a column for AJAX calls. 
if($count_row_AnsRealistic == 0){
	echo "<div class=\"fb-multiselect-column radio-column\" style=\"width:; ; float:left;\" ></div>";
}
?>

<div class="fb-checkbox-clear" style="clear:both;"></div>
</fieldset></div>
</div>
<!-- Form Field End -->


<div style="clear:both;"></div><!-- Clear -->

<?php
$visible = '';
$enabled = '';
?>

<!-- Form Field Start -->
<div id="fb_fld-bodycopy51" rf-field="true" class="Question" style="float:left; width:100%; margin-bottom:9px; <?php echo $visible; ?>" >

<?php
// Process Array Variables.
if(!isset($array_vars_processed)){ $vars = array(); process_array_variables($vars, ', '); $array_vars_processed = true; }
?>
		
<div class="body-copy " style="color:#d3d3d3; font-size:16px; font-weight:normal; width:100%;    border-radius:0px;  text-align:left;   padding-left:0px;  padding-right:0px;  padding-top:0px;  padding-bottom:0px;  ">
Did you feel the exam questions were fair?
</div>

</div>
<!-- Form Field End -->


<div style="clear:both;"></div><!-- Clear -->

<?php
$visible = '';
$enabled = '';
?>

<!-- Form Field Start -->
<div id="fb_fld-AnsFair" rf-field="true" class="" style="float:left; width:100%; margin-bottom:9px; <?php echo $visible; ?>" >
<div class="fb-checkbox-wrapper" style="width:98%px">

	<!-- Form Element Start --><fieldset data-role="controlgroup" style="border:0; padding:0px; margin:0px;">
	<?php $_SESSION['AnsFair'] = isset($_SESSION['AnsFair']) ? $_SESSION['AnsFair'] : array(""); ?>

	<?php if(!isset($_SESSION['AnsFair_is'])) { $_SESSION['AnsFair_is'] = 1; } ?>
	<?php if(!isset($_SESSION['AnsFair_processed'])) { $_SESSION['AnsFair_processed'] = false; } ?>

<div class="heading-main" style="  color:#d3d3d3; font-size:16px;  float:left; width:9px;  font-weight:normal; "><span class="req-star">&#42;</span>&nbsp;
	<div style="position:relative">
		<div id="AnsFair-validation-style-3-line" class="validation-style-3-line" style="display:none;">&nbsp;</div>
		<div id="AnsFair-validation-style-3-icon" class="validation-style-3-icon" style="display:none;">&nbsp;</div>
		<div id="AnsFair-validation-style-3-message" class="validation-style-3-message errormsg" style="display:none;">&nbsp;</div>
	</div>
</div>
<div class="fbtooltip-AnsFair fbtooltip" style="display:none;"></div>



<?php $rowcount_AnsFair = 10; $count_row_AnsFair = 0; ?>
<?php
!isset($count_AnsFair) ? $count_AnsFair = 1 : $count_AnsFair++;
$count_row_AnsFair++;
$count_AnsFair == 1 || $count_row_AnsFair == 1 ? print '<div class="fb-multiselect-column grouped fields radio-column" style="width:; ; float:left;">' : print '';
?>

<?php if($count_AnsFair % 2 == 0 && false) { $alternate_row = true; } else { $alternate_row = false; } ?>
<div class="multiselect-item field" style="   margin: 1px;  align:left;  <?php if(false && $alternate_row) { ?> <?php } else if(!false) { echo ''; } ?> clear:both; ">
<div class="ui radio checkbox">
<label for="AnsFair0" class="radio-inline ui radio checkbox <?php $value = 'Yes'; ?> "  style=" color:#d3d3d3;  "  >
<input type="radio" name="AnsFair" id="AnsFair0"  value="Yes"  style=" color:#d3d3d3;  "  class="required "
									
<?php if($_SESSION['AnsFair'] == html_entity_decode('Yes', ENT_QUOTES) || 
	( ('Yes' == htmlentities('', ENT_QUOTES) || 'Yes' == '') && $_SESSION['AnsFair_processed'] == false)) { 
	echo "checked=\"checked\""; }
?>
  />
<span class="rf-multiselect-item-label">Yes</span>
</label> <br/>
</div><!-- Close Non-Selected DIV -->
</div><!-- Close Non-Multi-Select DIV -->
<?php if($count_row_AnsFair == $rowcount_AnsFair) { echo '</div><!-- Close Row DIV -->'; $count_row_AnsFair = 0; } ?>

<?php
!isset($count_AnsFair) ? $count_AnsFair = 1 : $count_AnsFair++;
$count_row_AnsFair++;
$count_AnsFair == 1 || $count_row_AnsFair == 1 ? print '<div class="fb-multiselect-column grouped fields radio-column" style="width:; ; float:left;">' : print '';
?>

<?php if($count_AnsFair % 2 == 0 && false) { $alternate_row = true; } else { $alternate_row = false; } ?>
<div class="multiselect-item field" style="   margin: 1px;  align:left;  <?php if(false && $alternate_row) { ?> <?php } else if(!false) { echo ''; } ?> clear:both; ">
<div class="ui radio checkbox">
<label for="AnsFair1" class="radio-inline ui radio checkbox <?php $value = 'No'; ?> "  style=" color:#d3d3d3;  "  >
<input type="radio" name="AnsFair" id="AnsFair1"  value="No"  style=" color:#d3d3d3;  "  class="required "
									
<?php if($_SESSION['AnsFair'] == html_entity_decode('No', ENT_QUOTES) || 
	( ('No' == htmlentities('', ENT_QUOTES) || 'No' == '') && $_SESSION['AnsFair_processed'] == false)) { 
	echo "checked=\"checked\""; }
?>
  />
<span class="rf-multiselect-item-label">No</span>
</label> <br/>
</div><!-- Close Non-Selected DIV -->
</div><!-- Close Non-Multi-Select DIV -->
<?php if($count_row_AnsFair == $rowcount_AnsFair) { echo '</div><!-- Close Row DIV -->'; $count_row_AnsFair = 0; } ?>

<?php if($count_row_AnsFair != 0) { echo '</div>'; } ?><?php
// Even if we have no results we still create a column for AJAX calls. 
if($count_row_AnsFair == 0){
	echo "<div class=\"fb-multiselect-column radio-column\" style=\"width:; ; float:left;\" ></div>";
}
?>

<div class="fb-checkbox-clear" style="clear:both;"></div>
</fieldset></div>
</div>
<!-- Form Field End -->


<div style="clear:both;"></div><!-- Clear -->

<?php
$visible = '';
$enabled = '';
?>

<!-- Form Field Start -->
<div id="fb_fld-bodycopy54" rf-field="true" class="Question" style="float:left; width:100%; margin-bottom:9px; <?php echo $visible; ?>" >

<?php
// Process Array Variables.
if(!isset($array_vars_processed)){ $vars = array(); process_array_variables($vars, ', '); $array_vars_processed = true; }
?>
		
<div class="body-copy " style="color:#d3d3d3; font-size:16px; font-weight:normal; width:100%;    border-radius:0px;  text-align:left;   padding-left:0px;  padding-right:0px;  padding-top:0px;  padding-bottom:0px;  ">
Did you take unscheduled breaks?
</div>

</div>
<!-- Form Field End -->


<div style="clear:both;"></div><!-- Clear -->

<?php
$visible = '';
$enabled = '';
?>

<!-- Form Field Start -->
<div id="fb_fld-AnsBreaks" rf-field="true" class="" style="float:left; width:100%; margin-bottom:9px; <?php echo $visible; ?>" >
<div class="fb-checkbox-wrapper" style="width:98%px">

	<!-- Form Element Start --><fieldset data-role="controlgroup" style="border:0; padding:0px; margin:0px;">
	<?php $_SESSION['AnsBreaks'] = isset($_SESSION['AnsBreaks']) ? $_SESSION['AnsBreaks'] : array(""); ?>

	<?php if(!isset($_SESSION['AnsBreaks_is'])) { $_SESSION['AnsBreaks_is'] = 1; } ?>
	<?php if(!isset($_SESSION['AnsBreaks_processed'])) { $_SESSION['AnsBreaks_processed'] = false; } ?>

<div class="heading-main" style="  color:#d3d3d3; font-size:16px;  float:left; width:9px;  font-weight:normal; "><span class="req-star">&#42;</span>&nbsp;
	<div style="position:relative">
		<div id="AnsBreaks-validation-style-3-line" class="validation-style-3-line" style="display:none;">&nbsp;</div>
		<div id="AnsBreaks-validation-style-3-icon" class="validation-style-3-icon" style="display:none;">&nbsp;</div>
		<div id="AnsBreaks-validation-style-3-message" class="validation-style-3-message errormsg" style="display:none;">&nbsp;</div>
	</div>
</div>
<div class="fbtooltip-AnsBreaks fbtooltip" style="display:none;"></div>



<?php $rowcount_AnsBreaks = 10; $count_row_AnsBreaks = 0; ?>
<?php
!isset($count_AnsBreaks) ? $count_AnsBreaks = 1 : $count_AnsBreaks++;
$count_row_AnsBreaks++;
$count_AnsBreaks == 1 || $count_row_AnsBreaks == 1 ? print '<div class="fb-multiselect-column grouped fields radio-column" style="width:; ; float:left;">' : print '';
?>

<?php if($count_AnsBreaks % 2 == 0 && false) { $alternate_row = true; } else { $alternate_row = false; } ?>
<div class="multiselect-item field" style="   margin: 1px;  align:left;  <?php if(false && $alternate_row) { ?> <?php } else if(!false) { echo ''; } ?> clear:both; ">
<div class="ui radio checkbox">
<label for="AnsBreaks0" class="radio-inline ui radio checkbox <?php $value = 'Yes'; ?> "  style=" color:#d3d3d3;  "  >
<input type="radio" name="AnsBreaks" id="AnsBreaks0"  value="Yes"  style=" color:#d3d3d3;  "  class="required "
									
<?php if($_SESSION['AnsBreaks'] == html_entity_decode('Yes', ENT_QUOTES) || 
	( ('Yes' == htmlentities('', ENT_QUOTES) || 'Yes' == '') && $_SESSION['AnsBreaks_processed'] == false)) { 
	echo "checked=\"checked\""; }
?>
  />
<span class="rf-multiselect-item-label">Yes</span>
</label> <br/>
</div><!-- Close Non-Selected DIV -->
</div><!-- Close Non-Multi-Select DIV -->
<?php if($count_row_AnsBreaks == $rowcount_AnsBreaks) { echo '</div><!-- Close Row DIV -->'; $count_row_AnsBreaks = 0; } ?>

<?php
!isset($count_AnsBreaks) ? $count_AnsBreaks = 1 : $count_AnsBreaks++;
$count_row_AnsBreaks++;
$count_AnsBreaks == 1 || $count_row_AnsBreaks == 1 ? print '<div class="fb-multiselect-column grouped fields radio-column" style="width:; ; float:left;">' : print '';
?>

<?php if($count_AnsBreaks % 2 == 0 && false) { $alternate_row = true; } else { $alternate_row = false; } ?>
<div class="multiselect-item field" style="   margin: 1px;  align:left;  <?php if(false && $alternate_row) { ?> <?php } else if(!false) { echo ''; } ?> clear:both; ">
<div class="ui radio checkbox">
<label for="AnsBreaks1" class="radio-inline ui radio checkbox <?php $value = 'No'; ?> "  style=" color:#d3d3d3;  "  >
<input type="radio" name="AnsBreaks" id="AnsBreaks1"  value="No"  style=" color:#d3d3d3;  "  class="required "
									
<?php if($_SESSION['AnsBreaks'] == html_entity_decode('No', ENT_QUOTES) || 
	( ('No' == htmlentities('', ENT_QUOTES) || 'No' == '') && $_SESSION['AnsBreaks_processed'] == false)) { 
	echo "checked=\"checked\""; }
?>
  />
<span class="rf-multiselect-item-label">No</span>
</label> <br/>
</div><!-- Close Non-Selected DIV -->
</div><!-- Close Non-Multi-Select DIV -->
<?php if($count_row_AnsBreaks == $rowcount_AnsBreaks) { echo '</div><!-- Close Row DIV -->'; $count_row_AnsBreaks = 0; } ?>

<?php if($count_row_AnsBreaks != 0) { echo '</div>'; } ?><?php
// Even if we have no results we still create a column for AJAX calls. 
if($count_row_AnsBreaks == 0){
	echo "<div class=\"fb-multiselect-column radio-column\" style=\"width:; ; float:left;\" ></div>";
}
?>

<div class="fb-checkbox-clear" style="clear:both;"></div>
</fieldset></div>
</div>
<!-- Form Field End -->


<div style="clear:both;"></div><!-- Clear -->

<?php
$visible = '';
$enabled = '';
?>

<!-- Form Field Start -->
<div id="fb_fld-bodycopy60" rf-field="true" class="Question" style="float:left; width:100%; margin-bottom:9px; <?php echo $visible; ?>" >

<?php
// Process Array Variables.
if(!isset($array_vars_processed)){ $vars = array(); process_array_variables($vars, ', '); $array_vars_processed = true; }
?>
		
<div class="body-copy " style="color:#d3d3d3; font-size:16px; font-weight:normal; width:100%;    border-radius:0px;  text-align:left;   padding-left:0px;  padding-right:0px;  padding-top:0px;  padding-bottom:0px;  ">
<h1>Mindset</h1>
</div>

</div>
<!-- Form Field End -->


<div style="clear:both;"></div><!-- Clear -->

<?php
$visible = '';
$enabled = '';
?>

<!-- Form Field Start -->
<div id="fb_fld-bodycopy56" rf-field="true" class="Question" style="float:left; width:100%; margin-bottom:9px; <?php echo $visible; ?>" >

<?php
// Process Array Variables.
if(!isset($array_vars_processed)){ $vars = array(); process_array_variables($vars, ', '); $array_vars_processed = true; }
?>
		
<div class="body-copy " style="color:#d3d3d3; font-size:16px; font-weight:normal; width:100%;    border-radius:0px;  text-align:left;   padding-left:0px;  padding-right:0px;  padding-top:0px;  padding-bottom:0px;  ">
What was your mindset going into the practice exam?
</div>

</div>
<!-- Form Field End -->


<div style="clear:both;"></div><!-- Clear -->

<?php
$visible = '';
$enabled = '';
?>

<!-- Form Field Start -->
<div id="fb_fld-AnsMindInto" rf-field="true" class="" style="float:left; width:100%; margin-bottom:9px; <?php echo $visible; ?>" >
<div class="fb-checkbox-wrapper" style="width:98%px">

	<!-- Form Element Start --><fieldset data-role="controlgroup" style="border:0; padding:0px; margin:0px;">
	<?php $_SESSION['AnsMindInto'] = isset($_SESSION['AnsMindInto']) ? $_SESSION['AnsMindInto'] : array(""); ?>

	<?php if(!isset($_SESSION['AnsMindInto_is'])) { $_SESSION['AnsMindInto_is'] = 1; } ?>
	<?php if(!isset($_SESSION['AnsMindInto_processed'])) { $_SESSION['AnsMindInto_processed'] = false; } ?>

<div class="heading-main" style="  color:#d3d3d3; font-size:16px;  float:left; width:9px;  font-weight:normal; "><span class="req-star">&#42;</span>&nbsp;
	<div style="position:relative">
		<div id="AnsMindInto-validation-style-3-line" class="validation-style-3-line" style="display:none;">&nbsp;</div>
		<div id="AnsMindInto-validation-style-3-icon" class="validation-style-3-icon" style="display:none;">&nbsp;</div>
		<div id="AnsMindInto-validation-style-3-message" class="validation-style-3-message errormsg" style="display:none;">&nbsp;</div>
	</div>
</div>
<div class="fbtooltip-AnsMindInto fbtooltip" style="display:none;"></div>



<?php $rowcount_AnsMindInto = 10; $count_row_AnsMindInto = 0; ?>
<?php
!isset($count_AnsMindInto) ? $count_AnsMindInto = 1 : $count_AnsMindInto++;
$count_row_AnsMindInto++;
$count_AnsMindInto == 1 || $count_row_AnsMindInto == 1 ? print '<div class="fb-multiselect-column grouped fields radio-column" style="width:; ; float:left;">' : print '';
?>

<?php if($count_AnsMindInto % 2 == 0 && false) { $alternate_row = true; } else { $alternate_row = false; } ?>
<div class="multiselect-item field" style="   margin: 1px;  align:left;  <?php if(false && $alternate_row) { ?> <?php } else if(!false) { echo ''; } ?> clear:both; ">
<div class="ui radio checkbox">
<label for="AnsMindInto0" class="radio-inline ui radio checkbox <?php $value = 'Good'; ?> "  style=" color:#d3d3d3;  "  >
<input type="radio" name="AnsMindInto" id="AnsMindInto0"  value="Good"  style=" color:#d3d3d3;  "  class="required "
									
<?php if($_SESSION['AnsMindInto'] == html_entity_decode('Good', ENT_QUOTES) || 
	( ('Good' == htmlentities('', ENT_QUOTES) || 'Good' == '') && $_SESSION['AnsMindInto_processed'] == false)) { 
	echo "checked=\"checked\""; }
?>
  />
<span class="rf-multiselect-item-label">Good</span>
</label> <br/>
</div><!-- Close Non-Selected DIV -->
</div><!-- Close Non-Multi-Select DIV -->
<?php if($count_row_AnsMindInto == $rowcount_AnsMindInto) { echo '</div><!-- Close Row DIV -->'; $count_row_AnsMindInto = 0; } ?>

<?php
!isset($count_AnsMindInto) ? $count_AnsMindInto = 1 : $count_AnsMindInto++;
$count_row_AnsMindInto++;
$count_AnsMindInto == 1 || $count_row_AnsMindInto == 1 ? print '<div class="fb-multiselect-column grouped fields radio-column" style="width:; ; float:left;">' : print '';
?>

<?php if($count_AnsMindInto % 2 == 0 && false) { $alternate_row = true; } else { $alternate_row = false; } ?>
<div class="multiselect-item field" style="   margin: 1px;  align:left;  <?php if(false && $alternate_row) { ?> <?php } else if(!false) { echo ''; } ?> clear:both; ">
<div class="ui radio checkbox">
<label for="AnsMindInto1" class="radio-inline ui radio checkbox <?php $value = 'Not Good'; ?> "  style=" color:#d3d3d3;  "  >
<input type="radio" name="AnsMindInto" id="AnsMindInto1"  value="Not Good"  style=" color:#d3d3d3;  "  class="required "
									
<?php if($_SESSION['AnsMindInto'] == html_entity_decode('Not Good', ENT_QUOTES) || 
	( ('Not Good' == htmlentities('', ENT_QUOTES) || 'Not Good' == '') && $_SESSION['AnsMindInto_processed'] == false)) { 
	echo "checked=\"checked\""; }
?>
  />
<span class="rf-multiselect-item-label">Not Good</span>
</label> <br/>
</div><!-- Close Non-Selected DIV -->
</div><!-- Close Non-Multi-Select DIV -->
<?php if($count_row_AnsMindInto == $rowcount_AnsMindInto) { echo '</div><!-- Close Row DIV -->'; $count_row_AnsMindInto = 0; } ?>

<?php if($count_row_AnsMindInto != 0) { echo '</div>'; } ?><?php
// Even if we have no results we still create a column for AJAX calls. 
if($count_row_AnsMindInto == 0){
	echo "<div class=\"fb-multiselect-column radio-column\" style=\"width:; ; float:left;\" ></div>";
}
?>

<div class="fb-checkbox-clear" style="clear:both;"></div>
</fieldset></div>
</div>
<!-- Form Field End -->


<div style="clear:both;"></div><!-- Clear -->

<?php
$visible = '';
$enabled = '';
?>

<!-- Form Field Start -->
<div id="fb_fld-bodycopy57" rf-field="true" class="Question" style="float:left; width:100%; margin-bottom:9px; <?php echo $visible; ?>" >

<?php
// Process Array Variables.
if(!isset($array_vars_processed)){ $vars = array(); process_array_variables($vars, ', '); $array_vars_processed = true; }
?>
		
<div class="body-copy " style="color:#d3d3d3; font-size:16px; font-weight:normal; width:100%;    border-radius:0px;  text-align:left;   padding-left:0px;  padding-right:0px;  padding-top:0px;  padding-bottom:0px;  ">
What was your mindset throughout the practice exam?
</div>

</div>
<!-- Form Field End -->


<div style="clear:both;"></div><!-- Clear -->

<?php
$visible = '';
$enabled = '';
?>

<!-- Form Field Start -->
<div id="fb_fld-AnsMindDuring" rf-field="true" class="" style="float:left; width:100%; margin-bottom:9px; <?php echo $visible; ?>" >
<div class="fb-checkbox-wrapper" style="width:98%px">

	<!-- Form Element Start --><fieldset data-role="controlgroup" style="border:0; padding:0px; margin:0px;">
	<?php $_SESSION['AnsMindDuring'] = isset($_SESSION['AnsMindDuring']) ? $_SESSION['AnsMindDuring'] : array(""); ?>

	<?php if(!isset($_SESSION['AnsMindDuring_is'])) { $_SESSION['AnsMindDuring_is'] = 1; } ?>
	<?php if(!isset($_SESSION['AnsMindDuring_processed'])) { $_SESSION['AnsMindDuring_processed'] = false; } ?>

<div class="heading-main" style="  color:#d3d3d3; font-size:16px;  float:left; width:9px;  font-weight:normal; "><span class="req-star">&#42;</span>&nbsp;
	<div style="position:relative">
		<div id="AnsMindDuring-validation-style-3-line" class="validation-style-3-line" style="display:none;">&nbsp;</div>
		<div id="AnsMindDuring-validation-style-3-icon" class="validation-style-3-icon" style="display:none;">&nbsp;</div>
		<div id="AnsMindDuring-validation-style-3-message" class="validation-style-3-message errormsg" style="display:none;">&nbsp;</div>
	</div>
</div>
<div class="fbtooltip-AnsMindDuring fbtooltip" style="display:none;"></div>



<?php $rowcount_AnsMindDuring = 10; $count_row_AnsMindDuring = 0; ?>
<?php
!isset($count_AnsMindDuring) ? $count_AnsMindDuring = 1 : $count_AnsMindDuring++;
$count_row_AnsMindDuring++;
$count_AnsMindDuring == 1 || $count_row_AnsMindDuring == 1 ? print '<div class="fb-multiselect-column grouped fields radio-column" style="width:; ; float:left;">' : print '';
?>

<?php if($count_AnsMindDuring % 2 == 0 && false) { $alternate_row = true; } else { $alternate_row = false; } ?>
<div class="multiselect-item field" style="   margin: 1px;  align:left;  <?php if(false && $alternate_row) { ?> <?php } else if(!false) { echo ''; } ?> clear:both; ">
<div class="ui radio checkbox">
<label for="AnsMindDuring0" class="radio-inline ui radio checkbox <?php $value = 'Good'; ?> "  style=" color:#d3d3d3;  "  >
<input type="radio" name="AnsMindDuring" id="AnsMindDuring0"  value="Good"  style=" color:#d3d3d3;  "  class="required "
									
<?php if($_SESSION['AnsMindDuring'] == html_entity_decode('Good', ENT_QUOTES) || 
	( ('Good' == htmlentities('', ENT_QUOTES) || 'Good' == '') && $_SESSION['AnsMindDuring_processed'] == false)) { 
	echo "checked=\"checked\""; }
?>
  />
<span class="rf-multiselect-item-label">Good</span>
</label> <br/>
</div><!-- Close Non-Selected DIV -->
</div><!-- Close Non-Multi-Select DIV -->
<?php if($count_row_AnsMindDuring == $rowcount_AnsMindDuring) { echo '</div><!-- Close Row DIV -->'; $count_row_AnsMindDuring = 0; } ?>

<?php
!isset($count_AnsMindDuring) ? $count_AnsMindDuring = 1 : $count_AnsMindDuring++;
$count_row_AnsMindDuring++;
$count_AnsMindDuring == 1 || $count_row_AnsMindDuring == 1 ? print '<div class="fb-multiselect-column grouped fields radio-column" style="width:; ; float:left;">' : print '';
?>

<?php if($count_AnsMindDuring % 2 == 0 && false) { $alternate_row = true; } else { $alternate_row = false; } ?>
<div class="multiselect-item field" style="   margin: 1px;  align:left;  <?php if(false && $alternate_row) { ?> <?php } else if(!false) { echo ''; } ?> clear:both; ">
<div class="ui radio checkbox">
<label for="AnsMindDuring1" class="radio-inline ui radio checkbox <?php $value = 'Not Good'; ?> "  style=" color:#d3d3d3;  "  >
<input type="radio" name="AnsMindDuring" id="AnsMindDuring1"  value="Not Good"  style=" color:#d3d3d3;  "  class="required "
									
<?php if($_SESSION['AnsMindDuring'] == html_entity_decode('Not Good', ENT_QUOTES) || 
	( ('Not Good' == htmlentities('', ENT_QUOTES) || 'Not Good' == '') && $_SESSION['AnsMindDuring_processed'] == false)) { 
	echo "checked=\"checked\""; }
?>
  />
<span class="rf-multiselect-item-label">Not Good</span>
</label> <br/>
</div><!-- Close Non-Selected DIV -->
</div><!-- Close Non-Multi-Select DIV -->
<?php if($count_row_AnsMindDuring == $rowcount_AnsMindDuring) { echo '</div><!-- Close Row DIV -->'; $count_row_AnsMindDuring = 0; } ?>

<?php if($count_row_AnsMindDuring != 0) { echo '</div>'; } ?><?php
// Even if we have no results we still create a column for AJAX calls. 
if($count_row_AnsMindDuring == 0){
	echo "<div class=\"fb-multiselect-column radio-column\" style=\"width:; ; float:left;\" ></div>";
}
?>

<div class="fb-checkbox-clear" style="clear:both;"></div>
</fieldset></div>
</div>
<!-- Form Field End -->


<div style="clear:both;"></div><!-- Clear -->

<?php
$visible = '';
$enabled = '';
?>

<!-- Form Field Start -->
<div id="fb_fld-bodycopy61" rf-field="true" class="Question" style="float:left; width:100%; margin-bottom:9px; <?php echo $visible; ?>" >

<?php
// Process Array Variables.
if(!isset($array_vars_processed)){ $vars = array(); process_array_variables($vars, ', '); $array_vars_processed = true; }
?>
		
<div class="body-copy " style="color:#d3d3d3; font-size:16px; font-weight:normal; width:100%;    border-radius:0px;  text-align:left;   padding-left:0px;  padding-right:0px;  padding-top:0px;  padding-bottom:0px;  ">
What was your mindset after taking the practice exam?
</div>

</div>
<!-- Form Field End -->


<div style="clear:both;"></div><!-- Clear -->

<?php
$visible = '';
$enabled = '';
?>

<!-- Form Field Start -->
<div id="fb_fld-AnsMindAfter" rf-field="true" class="" style="float:left; width:100%; margin-bottom:9px; <?php echo $visible; ?>" >
<div class="fb-checkbox-wrapper" style="width:98%px">

	<!-- Form Element Start --><fieldset data-role="controlgroup" style="border:0; padding:0px; margin:0px;">
	<?php $_SESSION['AnsMindAfter'] = isset($_SESSION['AnsMindAfter']) ? $_SESSION['AnsMindAfter'] : array(""); ?>

	<?php if(!isset($_SESSION['AnsMindAfter_is'])) { $_SESSION['AnsMindAfter_is'] = 1; } ?>
	<?php if(!isset($_SESSION['AnsMindAfter_processed'])) { $_SESSION['AnsMindAfter_processed'] = false; } ?>

<div class="heading-main" style="  color:#d3d3d3; font-size:16px;  float:left; width:9px;  font-weight:normal; "><span class="req-star">&#42;</span>&nbsp;
	<div style="position:relative">
		<div id="AnsMindAfter-validation-style-3-line" class="validation-style-3-line" style="display:none;">&nbsp;</div>
		<div id="AnsMindAfter-validation-style-3-icon" class="validation-style-3-icon" style="display:none;">&nbsp;</div>
		<div id="AnsMindAfter-validation-style-3-message" class="validation-style-3-message errormsg" style="display:none;">&nbsp;</div>
	</div>
</div>
<div class="fbtooltip-AnsMindAfter fbtooltip" style="display:none;"></div>



<?php $rowcount_AnsMindAfter = 10; $count_row_AnsMindAfter = 0; ?>
<?php
!isset($count_AnsMindAfter) ? $count_AnsMindAfter = 1 : $count_AnsMindAfter++;
$count_row_AnsMindAfter++;
$count_AnsMindAfter == 1 || $count_row_AnsMindAfter == 1 ? print '<div class="fb-multiselect-column grouped fields radio-column" style="width:; ; float:left;">' : print '';
?>

<?php if($count_AnsMindAfter % 2 == 0 && false) { $alternate_row = true; } else { $alternate_row = false; } ?>
<div class="multiselect-item field" style="   margin: 1px;  align:left;  <?php if(false && $alternate_row) { ?> <?php } else if(!false) { echo ''; } ?> clear:both; ">
<div class="ui radio checkbox">
<label for="AnsMindAfter0" class="radio-inline ui radio checkbox <?php $value = 'I passed'; ?> "  style=" color:#d3d3d3;  "  >
<input type="radio" name="AnsMindAfter" id="AnsMindAfter0"  value="I passed"  style=" color:#d3d3d3;  "  class="required "
									
<?php if($_SESSION['AnsMindAfter'] == html_entity_decode('I passed', ENT_QUOTES) || 
	( ('I passed' == htmlentities('', ENT_QUOTES) || 'I passed' == '') && $_SESSION['AnsMindAfter_processed'] == false)) { 
	echo "checked=\"checked\""; }
?>
  />
<span class="rf-multiselect-item-label">I passed</span>
</label> <br/>
</div><!-- Close Non-Selected DIV -->
</div><!-- Close Non-Multi-Select DIV -->
<?php if($count_row_AnsMindAfter == $rowcount_AnsMindAfter) { echo '</div><!-- Close Row DIV -->'; $count_row_AnsMindAfter = 0; } ?>

<?php
!isset($count_AnsMindAfter) ? $count_AnsMindAfter = 1 : $count_AnsMindAfter++;
$count_row_AnsMindAfter++;
$count_AnsMindAfter == 1 || $count_row_AnsMindAfter == 1 ? print '<div class="fb-multiselect-column grouped fields radio-column" style="width:; ; float:left;">' : print '';
?>

<?php if($count_AnsMindAfter % 2 == 0 && false) { $alternate_row = true; } else { $alternate_row = false; } ?>
<div class="multiselect-item field" style="   margin: 1px;  align:left;  <?php if(false && $alternate_row) { ?> <?php } else if(!false) { echo ''; } ?> clear:both; ">
<div class="ui radio checkbox">
<label for="AnsMindAfter1" class="radio-inline ui radio checkbox <?php $value = 'I failed'; ?> "  style=" color:#d3d3d3;  "  >
<input type="radio" name="AnsMindAfter" id="AnsMindAfter1"  value="I failed"  style=" color:#d3d3d3;  "  class="required "
									
<?php if($_SESSION['AnsMindAfter'] == html_entity_decode('I failed', ENT_QUOTES) || 
	( ('I failed' == htmlentities('', ENT_QUOTES) || 'I failed' == '') && $_SESSION['AnsMindAfter_processed'] == false)) { 
	echo "checked=\"checked\""; }
?>
  />
<span class="rf-multiselect-item-label">I failed</span>
</label> <br/>
</div><!-- Close Non-Selected DIV -->
</div><!-- Close Non-Multi-Select DIV -->
<?php if($count_row_AnsMindAfter == $rowcount_AnsMindAfter) { echo '</div><!-- Close Row DIV -->'; $count_row_AnsMindAfter = 0; } ?>

<?php if($count_row_AnsMindAfter != 0) { echo '</div>'; } ?><?php
// Even if we have no results we still create a column for AJAX calls. 
if($count_row_AnsMindAfter == 0){
	echo "<div class=\"fb-multiselect-column radio-column\" style=\"width:; ; float:left;\" ></div>";
}
?>

<div class="fb-checkbox-clear" style="clear:both;"></div>
</fieldset></div>
</div>
<!-- Form Field End -->


<div style="clear:both;"></div><!-- Clear -->

<?php
$visible = '';
$enabled = '';
?>

<!-- Form Field Start -->
<div id="fb_fld-bodycopy53" rf-field="true" class="Question" style="float:left; width:100%; margin-bottom:9px; <?php echo $visible; ?>" >

<?php
// Process Array Variables.
if(!isset($array_vars_processed)){ $vars = array(); process_array_variables($vars, ', '); $array_vars_processed = true; }
?>
		
<div class="body-copy " style="color:#d3d3d3; font-size:16px; font-weight:normal; width:100%;    border-radius:0px;  text-align:left;   padding-left:0px;  padding-right:0px;  padding-top:0px;  padding-bottom:0px;  ">
<h1>Focus</h1>
</div>

</div>
<!-- Form Field End -->


<div style="clear:both;"></div><!-- Clear -->

<?php
$visible = '';
$enabled = '';
?>

<!-- Form Field Start -->
<div id="fb_fld-bodycopy63" rf-field="true" class="Question" style="float:left; width:100%; margin-bottom:9px; <?php echo $visible; ?>" >

<?php
// Process Array Variables.
if(!isset($array_vars_processed)){ $vars = array(); process_array_variables($vars, ', '); $array_vars_processed = true; }
?>
		
<div class="body-copy " style="color:#d3d3d3; font-size:16px; font-weight:normal; width:100%;    border-radius:0px;  text-align:left;   padding-left:0px;  padding-right:0px;  padding-top:0px;  padding-bottom:0px;  ">
Did you find your mind losing focus during the exam?
</div>

</div>
<!-- Form Field End -->


<div style="clear:both;"></div><!-- Clear -->

<?php
$visible = '';
$enabled = '';
?>

<!-- Form Field Start -->
<div id="fb_fld-AnsFocus" rf-field="true" class="" style="float:left; width:100%; margin-bottom:9px; <?php echo $visible; ?>" >
<div class="fb-checkbox-wrapper" style="width:98%px">

	<!-- Form Element Start --><fieldset data-role="controlgroup" style="border:0; padding:0px; margin:0px;">
	<?php $_SESSION['AnsFocus'] = isset($_SESSION['AnsFocus']) ? $_SESSION['AnsFocus'] : array(""); ?>

	<?php if(!isset($_SESSION['AnsFocus_is'])) { $_SESSION['AnsFocus_is'] = 1; } ?>
	<?php if(!isset($_SESSION['AnsFocus_processed'])) { $_SESSION['AnsFocus_processed'] = false; } ?>

<div class="heading-main" style="  color:#d3d3d3; font-size:16px;  float:left; width:9px;  font-weight:normal; "><span class="req-star">&#42;</span>&nbsp;
	<div style="position:relative">
		<div id="AnsFocus-validation-style-3-line" class="validation-style-3-line" style="display:none;">&nbsp;</div>
		<div id="AnsFocus-validation-style-3-icon" class="validation-style-3-icon" style="display:none;">&nbsp;</div>
		<div id="AnsFocus-validation-style-3-message" class="validation-style-3-message errormsg" style="display:none;">&nbsp;</div>
	</div>
</div>
<div class="fbtooltip-AnsFocus fbtooltip" style="display:none;"></div>



<?php $rowcount_AnsFocus = 10; $count_row_AnsFocus = 0; ?>
<?php
!isset($count_AnsFocus) ? $count_AnsFocus = 1 : $count_AnsFocus++;
$count_row_AnsFocus++;
$count_AnsFocus == 1 || $count_row_AnsFocus == 1 ? print '<div class="fb-multiselect-column grouped fields radio-column" style="width:; ; float:left;">' : print '';
?>

<?php if($count_AnsFocus % 2 == 0 && false) { $alternate_row = true; } else { $alternate_row = false; } ?>
<div class="multiselect-item field" style="   margin: 1px;  align:left;  <?php if(false && $alternate_row) { ?> <?php } else if(!false) { echo ''; } ?> clear:both; ">
<div class="ui radio checkbox">
<label for="AnsFocus0" class="radio-inline ui radio checkbox <?php $value = 'Yes'; ?> "  style=" color:#d3d3d3;  "  >
<input type="radio" name="AnsFocus" id="AnsFocus0"  value="Yes"  style=" color:#d3d3d3;  "  class="required "
									
<?php if($_SESSION['AnsFocus'] == html_entity_decode('Yes', ENT_QUOTES) || 
	( ('Yes' == htmlentities('', ENT_QUOTES) || 'Yes' == '') && $_SESSION['AnsFocus_processed'] == false)) { 
	echo "checked=\"checked\""; }
?>
  />
<span class="rf-multiselect-item-label">Yes</span>
</label> <br/>
</div><!-- Close Non-Selected DIV -->
</div><!-- Close Non-Multi-Select DIV -->
<?php if($count_row_AnsFocus == $rowcount_AnsFocus) { echo '</div><!-- Close Row DIV -->'; $count_row_AnsFocus = 0; } ?>

<?php
!isset($count_AnsFocus) ? $count_AnsFocus = 1 : $count_AnsFocus++;
$count_row_AnsFocus++;
$count_AnsFocus == 1 || $count_row_AnsFocus == 1 ? print '<div class="fb-multiselect-column grouped fields radio-column" style="width:; ; float:left;">' : print '';
?>

<?php if($count_AnsFocus % 2 == 0 && false) { $alternate_row = true; } else { $alternate_row = false; } ?>
<div class="multiselect-item field" style="   margin: 1px;  align:left;  <?php if(false && $alternate_row) { ?> <?php } else if(!false) { echo ''; } ?> clear:both; ">
<div class="ui radio checkbox">
<label for="AnsFocus1" class="radio-inline ui radio checkbox <?php $value = 'No'; ?> "  style=" color:#d3d3d3;  "  >
<input type="radio" name="AnsFocus" id="AnsFocus1"  value="No"  style=" color:#d3d3d3;  "  class="required "
									
<?php if($_SESSION['AnsFocus'] == html_entity_decode('No', ENT_QUOTES) || 
	( ('No' == htmlentities('', ENT_QUOTES) || 'No' == '') && $_SESSION['AnsFocus_processed'] == false)) { 
	echo "checked=\"checked\""; }
?>
  />
<span class="rf-multiselect-item-label">No</span>
</label> <br/>
</div><!-- Close Non-Selected DIV -->
</div><!-- Close Non-Multi-Select DIV -->
<?php if($count_row_AnsFocus == $rowcount_AnsFocus) { echo '</div><!-- Close Row DIV -->'; $count_row_AnsFocus = 0; } ?>

<?php if($count_row_AnsFocus != 0) { echo '</div>'; } ?><?php
// Even if we have no results we still create a column for AJAX calls. 
if($count_row_AnsFocus == 0){
	echo "<div class=\"fb-multiselect-column radio-column\" style=\"width:; ; float:left;\" ></div>";
}
?>

<div class="fb-checkbox-clear" style="clear:both;"></div>
</fieldset></div>
</div>
<!-- Form Field End -->


<div style="clear:both;"></div><!-- Clear -->

<?php
$visible = '';
$enabled = '';
?>

<!-- Form Field Start -->
<div id="fb_fld-844480" rf-field="true" class="" style="float:left; width:100%; margin-bottom:9px; <?php echo $visible; ?>" >
<div class="spacer" style="height:29px;  width:1px;">&nbsp;</div>
</div>
<!-- Form Field End -->


<div style="clear:both;"></div><!-- Clear -->

<?php
$visible = '';
$enabled = '';
?>

<!-- Form Field Start -->
<div id="fb_fld-bodycopy46" rf-field="true" class="Question" style="float:left; width:100%; margin-bottom:9px; <?php echo $visible; ?>" >

<?php
// Process Array Variables.
if(!isset($array_vars_processed)){ $vars = array(); process_array_variables($vars, ', '); $array_vars_processed = true; }
?>
		
<div class="body-copy " style="color:#d3d3d3; font-size:16px; font-weight:normal; width:100%;    border-radius:0px;  text-align:left;   padding-left:0px;  padding-right:0px;  padding-top:0px;  padding-bottom:0px;  ">
As a reminder, no recalling, redistributing, copying or republishing any Therapy Exam Prep exam questions or associated content in any print or digital format.
</div>

</div>
<!-- Form Field End -->


<div style="clear:both;"></div><!-- Clear -->

<?php
$visible = '';
$enabled = '';
?>

<!-- Form Field Start -->
<div id="fb_fld-agree" rf-field="true" class="" style="float:left; width:100%; margin-bottom:9px; <?php echo $visible; ?>" >
<div class="fb-checkbox-wrapper" style="width:98%px">

	<!-- Form Element Start --><fieldset data-role="controlgroup" style="border:0; padding:0px; margin:0px;">
	<?php $_SESSION['agree'] = isset($_SESSION['agree']) ? $_SESSION['agree'] : array(""); ?>

	<?php if(!isset($_SESSION['agree_is'])) { $_SESSION['agree_is'] = 1; } ?>
	<?php if(!isset($_SESSION['agree_processed'])) { $_SESSION['agree_processed'] = false; } ?>

<div class="heading-main" style="  color:#d3d3d3; font-size:10pt;  float:left; width:9px;  font-weight:normal; "><span class="req-star">&#42;</span>&nbsp;
	<div style="position:relative">
		<div id="agree-validation-style-3-line" class="validation-style-3-line" style="display:none;">&nbsp;</div>
		<div id="agree-validation-style-3-icon" class="validation-style-3-icon" style="display:none;">&nbsp;</div>
		<div id="agree-validation-style-3-message" class="validation-style-3-message errormsg" style="display:none;">&nbsp;</div>
	</div>
</div>
<div class="fbtooltip-agree fbtooltip" style="display:none;"></div>



<?php $rowcount_agree = 10; $count_row_agree = 0; ?>
<?php
!isset($count_agree) ? $count_agree = 1 : $count_agree++;
$count_row_agree++;
$count_agree == 1 || $count_row_agree == 1 ? print '<div class="fb-multiselect-column grouped fields checkbox-column" style="width:; ; float:left;">' : print '';
?>

<?php if($count_agree % 2 == 0 && false) { $alternate_row = true; } else { $alternate_row = false; } ?>
<div class="multiselect-item field" style="   margin: 1px;  align:left;  <?php if(false && $alternate_row) { ?> <?php } else if(!false) { echo ''; } ?> clear:both; ">
<div class="ui checkbox">
<label for="agree0" class="checkbox-inline ui checkbox <?php $value = 'I have read and agree.'; ?> "  style=" color:#d3d3d3;  "  >
<input type="checkbox" name="agree[]" id="agree0"  value="I have read and agree."  style=" color:#d3d3d3;  "  class="required "
						
<?php 
// explode default token/value if possible for multiple default values.
$dynamic_array = explode('|', '');
$dynamic_array_list = explode('|', '');

// set default values
if(is_array($_SESSION['agree']) && in_array(html_entity_decode("I have read and agree.", ENT_QUOTES), $_SESSION['agree']) ||
	('I have read and agree.' == htmlentities('', ENT_QUOTES) || 'I have read and agree.' == '' ||
	(is_array($dynamic_array) && in_array("I have read and agree.", $dynamic_array) || is_array($dynamic_array_list) && in_array("I have read and agree.", $dynamic_array_list) ) && 
	$_SESSION['agree_processed'] == false)) { 
	echo 'checked="checked"'; 
}
?>	
  />
<span class="rf-multiselect-item-label">I have read and agree.</span>
</label> <br/>
</div><!-- Close Non-Selected DIV -->
</div><!-- Close Non-Multi-Select DIV -->
<?php if($count_row_agree == $rowcount_agree) { echo '</div><!-- Close Row DIV -->'; $count_row_agree = 0; } ?>

<?php if($count_row_agree != 0) { echo '</div>'; } ?><?php
// Even if we have no results we still create a column for AJAX calls. 
if($count_row_agree == 0){
	echo "<div class=\"fb-multiselect-column checkbox-column\" style=\"width:; ; float:left;\" ></div>";
}
?>

<div class="fb-checkbox-clear" style="clear:both;"></div>
</fieldset></div>
</div>
<!-- Form Field End -->


<div style="clear:both;"></div><!-- Clear -->

<div style="clear:both;"></div><?php
$visible = '';
$enabled = '';
?>

<!-- Form Field Start -->
<div id="fb_fld-NavIntermission" rf-field="true" class="" style="float:left; width:100%; margin-bottom:9px; <?php echo $visible; ?>" >


<!-- bucket start -->
<div id="NavIntermission" class="bucket" style="position:relative; height:187px; width:; " >
<?php
$visible = '';
$enabled= '';
?>

<!-- Bucket Item - Responsive Form Field Start -->
<div id="fb_fld-Submit" rf-field="true" class="" style="<?php echo $visible; ?> float:left; width:100%; z-index:1; margin-bottom:15px">
				
				
<style type="text/css" scoped>

#Submit{

}

#Submit:hover{

}

#Submit:focus{

}

</style>
		
<input class="nolabel btn btn-primary  fld-full" type="submit" name="Submit" id="Submit"  value="Submit"  onclick="fb.disable_submit(event);" />

</div>
<!-- Form Field End -->


</div><!-- bucket end -->


</div>
<!-- Form Field End -->


<div style="clear:both;"></div><!-- Clear -->


<?php
// Process Array Variables.
if(!isset($array_vars_processed)){ $vars = array(); process_array_variables($vars, ', '); $array_vars_processed = true; }
?>
		<input type="hidden" name="examID" id="examID" value="0" />
</form>
<?php if(isset($_SESSION["e_message"])){  ?>
<div class="err-msg"><?php echo html_entity_decode($_SESSION["e_message"], ENT_QUOTES); ?></div>
<?php unset($_SESSION["e_message"]); } ?>

<div style="clear:both;"></div>
</div>

</body>
</html>
