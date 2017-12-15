<?php
// Start the session
session_start();

require_once '/home/afonseca/public_html/amember/library/Am/Lite.php';

$username = Am_Lite::getInstance()->getUsername();
$email = Am_Lite::getInstance()->getEmail();

$examNum = null;
$examAttempt = null;

if (isset($_SESSION["examNum"]))
{
  $examNum = $_SESSION["examNum"];
  unset($_SESSION["examNum"]);
}
/*else // staging
{
  $examNum = 1;
}*/

if (isset($_SESSION["examAttempt"]))
{
  $examAttempt = $_SESSION["examAttempt"];
  unset($_SESSION["examAttempt"]);
}
/*else // staging
{
  $examAttempt = 1;
}*/


$timeScale = 1.0; // default

if(isset($_SESSION["timeScale"]))
{
  $timeScale = $_SESSION["timeScale"];
  unset($_SESSION["timeScale"]);
}

?>
<?php
//--
// PHP Page Script - Generated: January 11, 2017
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
$_SESSION['entry_key_practiceexam'] = isset($_SESSION['entry_key_practiceexam']) ? $_SESSION['entry_key_practiceexam'] : md5(time() + rand(10000, 1000000));

// Form Page Security
$domain_list = explode(',',"");
$ip_limit = 0;
$job_id = '48';
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
$fb_login->persistent_login_job_id = 48;
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

$_SESSION['qs']["{$_SESSION['entry_key_practiceexam']}"]['fb_login'] = $fb_login;


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
<title>Practice Exam</title>
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
  var examNum = "<?php echo $examNum ?>";  
  var examAttempt = "<?php echo $examAttempt ?>";  
  var timeScale = "<?php echo $timeScale ?>";  
</script>


<!-- Javascript -->
<script src= "https://therapyexamprep.com/products/practice-exam/vendor/modernizr-custom.js"></script>
<script src= "https://therapyexamprep.com/products/practice-exam/vendor/EventEmitter.min.js"></script>
<script src= "https://therapyexamprep.com/products/practice-exam/vendor/easytimer.min.js"></script>
<script src= "https://therapyexamprep.com/products/practice-exam/vendor/moment.min.js"></script>
<script src= "https://therapyexamprep.com/products/practice-exam/vendor/TextHighlighter.min.js"></script>
<script src= "https://therapyexamprep.com/products/practice-exam/vendor/flat-ui/flat-ui.min.js"></script>
<script src="https://therapyexamprep.com/products/practice-exam/vendor/base64.min.js"></script>

<script src="https://therapyexamprep.com/products/practice-exam/resources/js/practice-exam.min.js?v=1.0.1"></script>
<script src="https://therapyexamprep.com/products/practice-exam/resources/js/practice-exam-ui.js"></script>
<script src="https://therapyexamprep.com/products/practice-exam/resources/js/flat-ui-app.js"></script>

<!-- Stylesheets -->
<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700" rel="stylesheet"> 
<link rel="stylesheet" type="text/css" href="https://therapyexamprep.com/products/practice-exam/vendor/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="https://therapyexamprep.com/products/practice-exam/vendor/flat-ui/flat-ui.min.css">

<link rel="stylesheet" type="text/css" href="https://therapyexamprep.com/products/practice-exam/resources/css/practice-exam.css?v=0.1">



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
<div id="fb_fld-questionNumber" rf-field="true" class="" style="<?php echo $visible; ?> float:left; width:33%; z-index:1; margin-bottom:15px">
				
				
<?php
// Process Array Variables.
if(!isset($array_vars_processed)){ $vars = array(); process_array_variables($vars, ', '); $array_vars_processed = true; }
?>
		
<div class="section-head " style="color:#d3d3d3; font-size:16px; font-weight:normal; width:95%;    border-radius:0px;  text-align:left;   padding-left:0px;  padding-right:0px;  padding-top:0px;  padding-bottom:0px;  ">
<span id="questionDisplay">Question 1 of 50</span>
</div>

</div>
<!-- Form Field End -->

<?php
$visible = '';
$enabled= '';
?>

<!-- Bucket Item - Responsive Form Field Start -->
<div id="fb_fld-sectionNumber" rf-field="true" class="" style="<?php echo $visible; ?> float:left; width:33%; z-index:1; margin-bottom:15px">
				
				
<?php
// Process Array Variables.
if(!isset($array_vars_processed)){ $vars = array(); process_array_variables($vars, ', '); $array_vars_processed = true; }
?>
		
<div class="section-head " style="color:#d3d3d3; font-size:16px; font-weight:normal; width:100%;    border-radius:0px;  text-align:center;   padding-left:0px;  padding-right:0px;  padding-top:0px;  padding-bottom:0px;  ">
<span id="sectionDisplay">Section 1 of 5</span>
</div>

</div>
<!-- Form Field End -->

<?php
$visible = '';
$enabled= '';
?>

<!-- Bucket Item - Responsive Form Field Start -->
<div id="fb_fld-timer" rf-field="true" class="" style="<?php echo $visible; ?> float:left; width:33%; z-index:1; margin-bottom:15px">
				
				
<?php
// Process Array Variables.
if(!isset($array_vars_processed)){ $vars = array(); process_array_variables($vars, ', '); $array_vars_processed = true; }
?>
		
<div class="section-head " style="color:#d3d3d3; font-size:16px; font-weight:normal; width:100%;    border-radius:0px;  text-align:right;   padding-left:0px;  padding-right:0px;  padding-top:0px;  padding-bottom:0px;  ">
<span>Time Remaining: </span><span id="TimerDisplay">05:00:00</span>
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
<div id="fb_fld-SummaryViewBak" rf-field="true" class="" style="float:left; width:; margin-bottom:9px; <?php echo $visible; ?>" >

<!-- Block Code End -->


</div>
<!-- Form Field End -->


<div style="clear:both;"></div><!-- Clear -->

<?php
$visible = '';
$enabled = '';
?>

<!-- Form Field Start -->
<div id="fb_fld-SummaryView" rf-field="true" class="" style="float:left; width:100%; margin-bottom:9px; <?php echo $visible; ?>" >

<?php
// Process Array Variables.
if(!isset($array_vars_processed)){ $vars = array(); process_array_variables($vars, ', '); $array_vars_processed = true; }
?>
		
<div class="body-copy " style="color:#d3d3d3; font-size:10pt; font-weight:normal; width:100%;    border-radius:0px;  text-align:left;   padding-left:0px;  padding-right:0px;  padding-top:0px;  padding-bottom:0px;  ">
Summary View
</div>

</div>
<!-- Form Field End -->


<div style="clear:both;"></div><!-- Clear -->

<?php
$visible = '';
$enabled = '';
?>

<!-- Form Field Start -->
<div id="fb_fld-QuestionView" rf-field="true" class="" style="float:left; width:100%; margin-bottom:9px; <?php echo $visible; ?>" >


<!-- bucket start -->
<div id="QuestionView" class="bucket" style="position:relative; height:127px; width:; " >
<?php
$visible = '';
$enabled= '';
?>

<!-- Bucket Item - Responsive Form Field Start -->
<div id="fb_fld-questionText" rf-field="true" class="Question" style="<?php echo $visible; ?> float:left; width:100%; z-index:1; margin-bottom:15px">
				
				
<?php
// Process Array Variables.
if(!isset($array_vars_processed)){ $vars = array(); process_array_variables($vars, ', '); $array_vars_processed = true; }
?>
		
<div class="body-copy " style="color:#d3d3d3; font-size:16px; font-weight:normal; width:95%;    border-radius:0px;  text-align:left;   padding-left:0px;  padding-right:0px;  padding-top:0px;  padding-bottom:0px;  ">
Loading...
</div>

</div>
<!-- Form Field End -->

<?php
$visible = '';
$enabled= '';
?>

<!-- Bucket Item - Responsive Form Field Start -->
<div id="fb_fld-Answer" rf-field="true" class="" style="<?php echo $visible; ?> float:left; width:120; z-index:1; margin-bottom:15px">
				
				<div class="fb-checkbox-wrapper" style="98%">

	<!-- Form Element Start --><fieldset data-role="controlgroup" style="border:0; padding:0px; margin:0px;">
	<?php $_SESSION['Answer'] = isset($_SESSION['Answer']) ? $_SESSION['Answer'] : array(""); ?>

	<?php if(!isset($_SESSION['Answer_is'])) { $_SESSION['Answer_is'] = 1; } ?>
	<?php if(!isset($_SESSION['Answer_processed'])) { $_SESSION['Answer_processed'] = false; } ?>

<div class="heading-main" style="  color:#d3d3d3; font-size:16px;  width:0px; height:0px; font-weight:normal; ">&nbsp;
	<div style="position:relative">
		<div id="Answer-validation-style-3-line" class="validation-style-3-line" style="display:none;">&nbsp;</div>
		<div id="Answer-validation-style-3-icon" class="validation-style-3-icon" style="display:none;">&nbsp;</div>
		<div id="Answer-validation-style-3-message" class="validation-style-3-message errormsg" style="display:none;">&nbsp;</div>
	</div>
</div>
<div class="fbtooltip-Answer fbtooltip" style="display:none;"></div>



<?php $rowcount_Answer = 10; $count_row_Answer = 0; ?>
<?php
!isset($count_Answer) ? $count_Answer = 1 : $count_Answer++;
$count_row_Answer++;
$count_Answer == 1 || $count_row_Answer == 1 ? print '<div class="fb-multiselect-column grouped fields radio-column" style="width:; ; float:left;">' : print '';
?>

<div class="ui radio checkbox">
<label for="Answer0" class="radio-inline ui radio checkbox <?php $value = '1'; ?> "  style=" color:#d3d3d3;  "  >
<input type="radio" name="Answer" id="Answer0"  value="1"  style=" color:#d3d3d3;  "  class=" "
									
<?php if($_SESSION['Answer'] == html_entity_decode('1', ENT_QUOTES) || 
	( ('1' == htmlentities('', ENT_QUOTES) || '1' == '') && $_SESSION['Answer_processed'] == false)) { 
	echo "checked=\"checked\""; }
?>
onClick=TEP_EXAM.controller.choiceRadioClicked(this.value)  />
<span class="rf-multiselect-item-label">1</span>
</label> <br/>
</div><!-- Close Non-Selected DIV -->

<?php if($count_row_Answer == $rowcount_Answer) { echo '</div><!-- Close Row DIV -->'; $count_row_Answer = 0; } ?>

<?php
!isset($count_Answer) ? $count_Answer = 1 : $count_Answer++;
$count_row_Answer++;
$count_Answer == 1 || $count_row_Answer == 1 ? print '<div class="fb-multiselect-column grouped fields radio-column" style="width:; ; float:left;">' : print '';
?>

<div class="ui radio checkbox">
<label for="Answer1" class="radio-inline ui radio checkbox <?php $value = '2'; ?> "  style=" color:#d3d3d3;  "  >
<input type="radio" name="Answer" id="Answer1"  value="2"  style=" color:#d3d3d3;  "  class=" "
									
<?php if($_SESSION['Answer'] == html_entity_decode('2', ENT_QUOTES) || 
	( ('2' == htmlentities('', ENT_QUOTES) || '2' == '') && $_SESSION['Answer_processed'] == false)) { 
	echo "checked=\"checked\""; }
?>
onClick=TEP_EXAM.controller.choiceRadioClicked(this.value)  />
<span class="rf-multiselect-item-label">2</span>
</label> <br/>
</div><!-- Close Non-Selected DIV -->

<?php if($count_row_Answer == $rowcount_Answer) { echo '</div><!-- Close Row DIV -->'; $count_row_Answer = 0; } ?>

<?php
!isset($count_Answer) ? $count_Answer = 1 : $count_Answer++;
$count_row_Answer++;
$count_Answer == 1 || $count_row_Answer == 1 ? print '<div class="fb-multiselect-column grouped fields radio-column" style="width:; ; float:left;">' : print '';
?>

<div class="ui radio checkbox">
<label for="Answer2" class="radio-inline ui radio checkbox <?php $value = '3'; ?> "  style=" color:#d3d3d3;  "  >
<input type="radio" name="Answer" id="Answer2"  value="3"  style=" color:#d3d3d3;  "  class=" "
									
<?php if($_SESSION['Answer'] == html_entity_decode('3', ENT_QUOTES) || 
	( ('3' == htmlentities('', ENT_QUOTES) || '3' == '') && $_SESSION['Answer_processed'] == false)) { 
	echo "checked=\"checked\""; }
?>
onClick=TEP_EXAM.controller.choiceRadioClicked(this.value)  />
<span class="rf-multiselect-item-label">3</span>
</label> <br/>
</div><!-- Close Non-Selected DIV -->

<?php if($count_row_Answer == $rowcount_Answer) { echo '</div><!-- Close Row DIV -->'; $count_row_Answer = 0; } ?>

<?php
!isset($count_Answer) ? $count_Answer = 1 : $count_Answer++;
$count_row_Answer++;
$count_Answer == 1 || $count_row_Answer == 1 ? print '<div class="fb-multiselect-column grouped fields radio-column" style="width:; ; float:left;">' : print '';
?>

<div class="ui radio checkbox">
<label for="Answer3" class="radio-inline ui radio checkbox <?php $value = '4'; ?> "  style=" color:#d3d3d3;  "  >
<input type="radio" name="Answer" id="Answer3"  value="4"  style=" color:#d3d3d3;  "  class=" "
									
<?php if($_SESSION['Answer'] == html_entity_decode('4', ENT_QUOTES) || 
	( ('4' == htmlentities('', ENT_QUOTES) || '4' == '') && $_SESSION['Answer_processed'] == false)) { 
	echo "checked=\"checked\""; }
?>
onClick=TEP_EXAM.controller.choiceRadioClicked(this.value)  />
<span class="rf-multiselect-item-label">4</span>
</label> <br/>
</div><!-- Close Non-Selected DIV -->

<?php if($count_row_Answer == $rowcount_Answer) { echo '</div><!-- Close Row DIV -->'; $count_row_Answer = 0; } ?>

<?php if($count_row_Answer != 0) { echo '</div>'; } ?><?php
// Even if we have no results we still create a column for AJAX calls. 
if($count_row_Answer == 0){
	echo "<div class=\"fb-multiselect-column radio-column\" style=\"width:; ; float:left;\" ></div>";
}
?>

<div class="fb-checkbox-clear" style="clear:both;"></div>
</fieldset></div>
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
<div id="fb_fld-QuestionMetaView" rf-field="true" class="" style="float:left; width:100%; margin-bottom:9px; <?php echo $visible; ?>" >


<!-- bucket start -->
<div id="QuestionMetaView" class="bucket" style="position:relative; height:113px; width:; " >
<?php
$visible = '';
$enabled= '';
?>

<!-- Bucket Item - Responsive Form Field Start -->
<div id="fb_fld-DifficultyRadio" rf-field="true" class="" style="<?php echo $visible; ?> float:left; width:300px; z-index:1; margin-bottom:15px">
				
				<div class="fb-checkbox-wrapper" style="98%">

	<!-- Form Element Start --><fieldset data-role="controlgroup" style="border:0; padding:0px; margin:0px;">
	<?php $_SESSION['DifficultyRadio'] = isset($_SESSION['DifficultyRadio']) ? $_SESSION['DifficultyRadio'] : array(""); ?>

	<?php if(!isset($_SESSION['DifficultyRadio_is'])) { $_SESSION['DifficultyRadio_is'] = 1; } ?>
	<?php if(!isset($_SESSION['DifficultyRadio_processed'])) { $_SESSION['DifficultyRadio_processed'] = false; } ?>

<div class="heading-main" style="  color:#d3d3d3; font-size:16px;  width:0px; height:0px; font-weight:normal; ">&nbsp;
	<div style="position:relative">
		<div id="DifficultyRadio-validation-style-3-line" class="validation-style-3-line" style="display:none;">&nbsp;</div>
		<div id="DifficultyRadio-validation-style-3-icon" class="validation-style-3-icon" style="display:none;">&nbsp;</div>
		<div id="DifficultyRadio-validation-style-3-message" class="validation-style-3-message errormsg" style="display:none;">&nbsp;</div>
	</div>
</div>
<div class="fbtooltip-DifficultyRadio fbtooltip" style="display:none;"></div>



<?php $rowcount_DifficultyRadio = 10; $count_row_DifficultyRadio = 0; ?>
<?php
!isset($count_DifficultyRadio) ? $count_DifficultyRadio = 1 : $count_DifficultyRadio++;
$count_row_DifficultyRadio++;
$count_DifficultyRadio == 1 || $count_row_DifficultyRadio == 1 ? print '<div class="fb-multiselect-column grouped fields radio-column" style="width:; ; float:left;">' : print '';
?>

<div class="ui radio checkbox">
<label for="DifficultyRadio0" class="radio-inline ui radio checkbox <?php $value = 'Easy'; ?> "  style=" color:#d3d3d3;  "  >
<input type="radio" name="DifficultyRadio" id="DifficultyRadio0"  value="Easy"  style=" color:#d3d3d3;  "  class=" "
									
<?php if($_SESSION['DifficultyRadio'] == html_entity_decode('Easy', ENT_QUOTES) || 
	( ('Easy' == htmlentities('', ENT_QUOTES) || 'Easy' == '') && $_SESSION['DifficultyRadio_processed'] == false)) { 
	echo "checked=\"checked\""; }
?>
onClick="TEP_EXAM.controller.difficultyRadioClicked(this.value.toLowerCase());"  />
<span class="rf-multiselect-item-label">Easy</span>
</label> <br/>
</div><!-- Close Non-Selected DIV -->

<?php if($count_row_DifficultyRadio == $rowcount_DifficultyRadio) { echo '</div><!-- Close Row DIV -->'; $count_row_DifficultyRadio = 0; } ?>

<?php
!isset($count_DifficultyRadio) ? $count_DifficultyRadio = 1 : $count_DifficultyRadio++;
$count_row_DifficultyRadio++;
$count_DifficultyRadio == 1 || $count_row_DifficultyRadio == 1 ? print '<div class="fb-multiselect-column grouped fields radio-column" style="width:; ; float:left;">' : print '';
?>

<div class="ui radio checkbox">
<label for="DifficultyRadio1" class="radio-inline ui radio checkbox <?php $value = 'Hard'; ?> "  style=" color:#d3d3d3;  "  >
<input type="radio" name="DifficultyRadio" id="DifficultyRadio1"  value="Hard"  style=" color:#d3d3d3;  "  class=" "
									
<?php if($_SESSION['DifficultyRadio'] == html_entity_decode('Hard', ENT_QUOTES) || 
	( ('Hard' == htmlentities('', ENT_QUOTES) || 'Hard' == '') && $_SESSION['DifficultyRadio_processed'] == false)) { 
	echo "checked=\"checked\""; }
?>
onClick="TEP_EXAM.controller.difficultyRadioClicked(this.value.toLowerCase());"  />
<span class="rf-multiselect-item-label">Hard</span>
</label> <br/>
</div><!-- Close Non-Selected DIV -->

<?php if($count_row_DifficultyRadio == $rowcount_DifficultyRadio) { echo '</div><!-- Close Row DIV -->'; $count_row_DifficultyRadio = 0; } ?>

<?php if($count_row_DifficultyRadio != 0) { echo '</div>'; } ?><?php
// Even if we have no results we still create a column for AJAX calls. 
if($count_row_DifficultyRadio == 0){
	echo "<div class=\"fb-multiselect-column radio-column\" style=\"width:; ; float:left;\" ></div>";
}
?>

<div class="fb-checkbox-clear" style="clear:both;"></div>
</fieldset></div>
</div>
<!-- Form Field End -->

<?php
$visible = '';
$enabled= '';
?>

<!-- Bucket Item - Responsive Form Field Start -->
<div id="fb_fld-GuessedCheck" rf-field="true" class="" style="<?php echo $visible; ?> float:left; width:120; z-index:1; margin-bottom:15px">
				
				<div class="fb-checkbox-wrapper" style="98%">

	<!-- Form Element Start --><fieldset data-role="controlgroup" style="border:0; padding:0px; margin:0px;">
	<?php $_SESSION['GuessedCheck'] = isset($_SESSION['GuessedCheck']) ? $_SESSION['GuessedCheck'] : array(""); ?>

	<?php if(!isset($_SESSION['GuessedCheck_is'])) { $_SESSION['GuessedCheck_is'] = 1; } ?>
	<?php if(!isset($_SESSION['GuessedCheck_processed'])) { $_SESSION['GuessedCheck_processed'] = false; } ?>

<div class="heading-main" style="  color:#d3d3d3; font-size:16px;  width:0px; height:0px; font-weight:normal; ">&nbsp;
	<div style="position:relative">
		<div id="GuessedCheck-validation-style-3-line" class="validation-style-3-line" style="display:none;">&nbsp;</div>
		<div id="GuessedCheck-validation-style-3-icon" class="validation-style-3-icon" style="display:none;">&nbsp;</div>
		<div id="GuessedCheck-validation-style-3-message" class="validation-style-3-message errormsg" style="display:none;">&nbsp;</div>
	</div>
</div>
<div class="fbtooltip-GuessedCheck fbtooltip" style="display:none;"></div>



<?php $rowcount_GuessedCheck = 10; $count_row_GuessedCheck = 0; ?>
<?php
!isset($count_GuessedCheck) ? $count_GuessedCheck = 1 : $count_GuessedCheck++;
$count_row_GuessedCheck++;
$count_GuessedCheck == 1 || $count_row_GuessedCheck == 1 ? print '<div class="fb-multiselect-column fields checkbox-column" style="width:; ; float:left;">' : print '';
?>

<div class="ui checkbox">
<label for="GuessedCheck0" class="checkbox-inline ui checkbox <?php $value = 'Guessed'; ?> "  style=" color:#d3d3d3;  "  >
<input type="checkbox" name="GuessedCheck[]" id="GuessedCheck0"  value="1"  style=" color:#d3d3d3;  "  class=" "
						
<?php 
// explode default token/value if possible for multiple default values.
$dynamic_array = explode('|', '');
$dynamic_array_list = explode('|', '');

// set default values
if(is_array($_SESSION['GuessedCheck']) && in_array(html_entity_decode("1", ENT_QUOTES), $_SESSION['GuessedCheck']) ||
	('1' == htmlentities('', ENT_QUOTES) || '1' == '' ||
	(is_array($dynamic_array) && in_array("1", $dynamic_array) || is_array($dynamic_array_list) && in_array("1", $dynamic_array_list) ) && 
	$_SESSION['GuessedCheck_processed'] == false)) { 
	echo 'checked="checked"'; 
}
?>	
onClick="TEP_EXAM.controller.guessedCheckboxClicked(this.checked);"  />
<span class="rf-multiselect-item-label">Guessed</span>
</label> 
</div><!-- Close Non-Selected DIV -->

<?php if($count_row_GuessedCheck == $rowcount_GuessedCheck) { echo '</div><!-- Close Row DIV -->'; $count_row_GuessedCheck = 0; } ?>

<?php if($count_row_GuessedCheck != 0) { echo '</div>'; } ?><?php
// Even if we have no results we still create a column for AJAX calls. 
if($count_row_GuessedCheck == 0){
	echo "<div class=\"fb-multiselect-column checkbox-column\" style=\"width:; ; float:left;\" ></div>";
}
?>

<div class="fb-checkbox-clear" style="clear:both;"></div>
</fieldset></div>
</div>
<!-- Form Field End -->

<div style="clear:both;"></div>
</div><!-- bucket end -->


</div>
<!-- Form Field End -->


<div style="clear:both;"></div><!-- Clear -->

<?php
$visible = '';
$enabled = '';
?>

<!-- Form Field Start -->
<div id="fb_fld-IntermissionView" rf-field="true" class="" style="float:left; width:100%; margin-bottom:9px; <?php echo $visible; ?>" >


<!-- bucket start -->
<div id="IntermissionView" class="bucket" style="position:relative; height:156px; width:; " >
<?php
$visible = '';
$enabled= '';
?>

<!-- Bucket Item - Responsive Form Field Start -->
<div id="fb_fld-sectionEnd" rf-field="true" class="SectionEnd" style="<?php echo $visible; ?> float:left; width:100%; z-index:1; margin-bottom:15px">
				
				
<?php
// Process Array Variables.
if(!isset($array_vars_processed)){ $vars = array(); process_array_variables($vars, ', '); $array_vars_processed = true; }
?>
		
<div class="body-copy " style="color:#d3d3d3; font-size:10pt; font-weight:normal; width:95%;    border-radius:0px;  text-align:left;   padding-left:0px;  padding-right:0px;  padding-top:0px;  padding-bottom:0px;  ">
<p>You are moving on to Section <span id="next-section">2</span> of the exam. This is not a scheduled break. If you choose to take an unscheduled break at this time, the timer will continue to run. Once you begin the next section of 50 questions, you cannot return to this section to check your answers.</p>
</div>

</div>
<!-- Form Field End -->

<?php
$visible = '';
$enabled= '';
?>

<!-- Bucket Item - Responsive Form Field Start -->
<div id="fb_fld-examSubmitted" rf-field="true" class="ExamSubmitted" style="<?php echo $visible; ?> float:left; width:100%; z-index:1; margin-bottom:15px">
				
				
<?php
// Process Array Variables.
if(!isset($array_vars_processed)){ $vars = array(); process_array_variables($vars, ', '); $array_vars_processed = true; }
?>
		
<div class="body-copy " style="color:#d3d3d3; font-size:10pt; font-weight:normal; width:95%;    border-radius:0px;  text-align:left;   padding-left:0px;  padding-right:0px;  padding-top:0px;  padding-bottom:0px;  ">
<p>Your exam has been submitted and you are now moving on to the survey. It is required that you complete the survey to have access to your exam results.</p>
</div>

</div>
<!-- Form Field End -->

<?php
$visible = '';
$enabled= '';
?>

<!-- Bucket Item - Responsive Form Field Start -->
<div id="fb_fld-examEnd" rf-field="true" class="ExamEnd" style="<?php echo $visible; ?> float:left; width:100%; z-index:1; margin-bottom:15px">
				
				
<?php
// Process Array Variables.
if(!isset($array_vars_processed)){ $vars = array(); process_array_variables($vars, ', '); $array_vars_processed = true; }
?>
		
<div class="body-copy " style="color:#d3d3d3; font-size:10pt; font-weight:normal; width:95%;    border-radius:0px;  text-align:left;   padding-left:0px;  padding-right:0px;  padding-top:0px;  padding-bottom:0px;  ">
<p>You have completed the exam and are now ready to submit your answers. Once submitted, you cannot return to this section to check your answers.</p>
</div>

</div>
<!-- Form Field End -->

<?php
$visible = '';
$enabled= '';
?>

<!-- Bucket Item - Responsive Form Field Start -->
<div id="fb_fld-sectionEndBreak" rf-field="true" class="SectionEndBreak" style="<?php echo $visible; ?> float:left; width:100%; z-index:1; margin-bottom:15px">
				
				
<?php
// Process Array Variables.
if(!isset($array_vars_processed)){ $vars = array(); process_array_variables($vars, ', '); $array_vars_processed = true; }
?>
		
<div class="body-copy " style="color:#d3d3d3; font-size:10pt; font-weight:normal; width:95%;    border-radius:0px;  text-align:left;   padding-left:0px;  padding-right:0px;  padding-top:0px;  padding-bottom:0px;  ">
<p>You are moving on to your scheduled break. Once you begin the break you cannot return to this section to check your answers.</p>
</div>

</div>
<!-- Form Field End -->

<?php
$visible = '';
$enabled= '';
?>

<!-- Bucket Item - Responsive Form Field Start -->
<div id="fb_fld-scheduledBreak" rf-field="true" class="ScheduledBreak" style="<?php echo $visible; ?> float:left; width:100%; z-index:1; margin-bottom:15px">
				
				
<?php
// Process Array Variables.
if(!isset($array_vars_processed)){ $vars = array(); process_array_variables($vars, ', '); $array_vars_processed = true; }
?>
		
<div class="body-copy " style="color:#d3d3d3; font-size:10pt; font-weight:normal; width:95%;    border-radius:0px;  text-align:left;   padding-left:0px;  padding-right:0px;  padding-top:0px;  padding-bottom:0px;  ">
<p>STOP! Please read carefully. During this scheduled break, the exam timer will pause for up to <span id="break-time">15 minutes</span>.</p>
<p>If you choose to take this scheduled break, you may leave the computer and time spent away (within the allowed break time) will not be deducted from your exam.</p>
<p>WARNING: You may not access study materials, or discuss this exam with anyone.</p>
<p>You are responsible for keeping track of your scheduled break time and being prepared to start Section Three at the end of the break.</p>
<p>If you do not click the 'Next' button before the break ends, the exam timer will automatically resume.</p>
<p>When you are done with your break, or if you wish to skip it, click the 'Next' button to continue the exam.</p>
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
<div id="fb_fld-ErrorView" rf-field="true" class="" style="float:left; width:100%; margin-bottom:9px; <?php echo $visible; ?>" >


<!-- bucket start -->
<div id="ErrorView" class="bucket" style="position:relative; height:156px; width:; " >
<?php
$visible = '';
$enabled= '';
?>

<!-- Bucket Item - Responsive Form Field Start -->
<div id="fb_fld-netError" rf-field="true" class="ErrorMessage" style="<?php echo $visible; ?> float:left; width:100%; z-index:1; margin-bottom:15px">
				
				
<?php
// Process Array Variables.
if(!isset($array_vars_processed)){ $vars = array(); process_array_variables($vars, ', '); $array_vars_processed = true; }
?>
		
<div class="body-copy " style="color:#d3d3d3; font-size:10pt; font-weight:normal; width:95%;    border-radius:0px;  text-align:center;   padding-left:0px;  padding-right:0px;  padding-top:0px;  padding-bottom:0px;  ">
<h1>Unable to Start Your Exam</h1>
<p>We were not able to communicate with the server. Please check your network connection then try closing this page and starting again.</p>
</div>

</div>
<!-- Form Field End -->

<?php
$visible = '';
$enabled= '';
?>

<!-- Bucket Item - Responsive Form Field Start -->
<div id="fb_fld-browserError" rf-field="true" class="ErrorMessage" style="<?php echo $visible; ?> float:left; width:100%; z-index:1; margin-bottom:15px">
				
				
<?php
// Process Array Variables.
if(!isset($array_vars_processed)){ $vars = array(); process_array_variables($vars, ', '); $array_vars_processed = true; }
?>
		
<div class="body-copy " style="color:#d3d3d3; font-size:10pt; font-weight:normal; width:95%;    border-radius:0px;  text-align:center;   padding-left:0px;  padding-right:0px;  padding-top:0px;  padding-bottom:0px;  ">
<h1>Unable to Start Your Exam</h1>
<p>This application requires certain modern browser features to be available and enabled. Please <a href="http://whatbrowser.org/">check you have an up to date browser installed</a> and try again.</p>
</div>

</div>
<!-- Form Field End -->

<?php
$visible = '';
$enabled= '';
?>

<!-- Bucket Item - Responsive Form Field Start -->
<div id="fb_fld-startError" rf-field="true" class="ErrorMessage" style="<?php echo $visible; ?> float:left; width:100%; z-index:1; margin-bottom:15px">
				
				
<?php
// Process Array Variables.
if(!isset($array_vars_processed)){ $vars = array(); process_array_variables($vars, ', '); $array_vars_processed = true; }
?>
		
<div class="body-copy " style="color:#d3d3d3; font-size:10pt; font-weight:normal; width:95%;    border-radius:0px;  text-align:center;   padding-left:0px;  padding-right:0px;  padding-top:0px;  padding-bottom:0px;  ">
<h1>Unable to Start Your Exam</h1>
<p>Something went wrong on our end but don’t worry, we’ll get it figured out. Try closing this page and starting again.</p>
</div>

</div>
<!-- Form Field End -->

<?php
$visible = '';
$enabled= '';
?>

<!-- Bucket Item - Responsive Form Field Start -->
<div id="fb_fld-submitError" rf-field="true" class="ErrorMessage" style="<?php echo $visible; ?> float:left; width:100%; z-index:1; margin-bottom:15px">
				
				
<?php
// Process Array Variables.
if(!isset($array_vars_processed)){ $vars = array(); process_array_variables($vars, ', '); $array_vars_processed = true; }
?>
		
<div class="body-copy " style="color:#d3d3d3; font-size:10pt; font-weight:normal; width:95%;    border-radius:0px;  text-align:center;   padding-left:0px;  padding-right:0px;  padding-top:0px;  padding-bottom:0px;  ">
<h1>Unable to Submit Your Exam</h1>
<p>Something went wrong on our end but don’t worry, you haven’t lost anything. Your exam answers are saved in your browser. Please be sure not to clear your browser data until you are able to submit your exam. Our technical support team has been notified and will get in touch soon.</p>
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
<div id="fb_fld-NavQuestion" rf-field="true" class="" style="float:left; width:100%; margin-bottom:9px; <?php echo $visible; ?>" >


<!-- bucket start -->
<div id="NavQuestion" class="bucket" style="position:relative; height:69px; width:; " >
<?php
$visible = '';
$enabled= '';
?>

<!-- Bucket Item - Responsive Form Field Start -->
<div id="fb_fld-Previous" rf-field="true" class="" style="<?php echo $visible; ?> float:left; width:; z-index:1; margin-bottom:15px">
				
				
<style type="text/css" scoped>

#Previous{

}

#Previous:hover{

}

#Previous:focus{

}

</style>
		

<input class="nolabel btn  " type="button" name="Previous" id="Previous"  value="Previous" onClick=TEP_EXAM.controller.prevButtonClicked();   />
</div>
<!-- Form Field End -->

<?php
$visible = '';
$enabled= '';
?>

<!-- Bucket Item - Responsive Form Field Start -->
<div id="fb_fld-Mark" rf-field="true" class="" style="<?php echo $visible; ?> float:left; width:; z-index:1; margin-bottom:15px">
				
				
<style type="text/css" scoped>

#Mark{

}

#Mark:hover{

}

#Mark:focus{

}

</style>
		

<input class="nolabel btn  " type="button" name="Mark" id="Mark"  value="Mark" onClick=TEP_EXAM.controller.markButtonClicked();   />
</div>
<!-- Form Field End -->

<?php
$visible = '';
$enabled= '';
?>

<!-- Bucket Item - Responsive Form Field Start -->
<div id="fb_fld-Next" rf-field="true" class="" style="<?php echo $visible; ?> float:left; width:; z-index:1; margin-bottom:15px">
				
				
<style type="text/css" scoped>

#Next{

}

#Next:hover{

}

#Next:focus{

}

</style>
		

<input class="nolabel btn  " type="button" name="Next" id="Next"  value="Next" onClick=TEP_EXAM.controller.nextButtonClicked();   />
</div>
<!-- Form Field End -->

<?php
$visible = '';
$enabled= '';
?>

<!-- Bucket Item - Responsive Form Field Start -->
<div id="fb_fld-Review" rf-field="true" class="" style="<?php echo $visible; ?> float:left; width:; z-index:1; margin-bottom:15px">
				
				
<style type="text/css" scoped>

#Review{

}

#Review:hover{

}

#Review:focus{

}

</style>
		

<input class="nolabel btn  " type="button" name="Review" id="Review"  value="Review" onClick=TEP_EXAM.controller.reviewButtonClicked();   />
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
<div id="fb_fld-NavSummary" rf-field="true" class="" style="float:left; width:100%; margin-bottom:9px; <?php echo $visible; ?>" >


<!-- bucket start -->
<div id="NavSummary" class="bucket" style="position:relative; height:200px; width:; " >
<?php
$visible = '';
$enabled= '';
?>

<!-- Bucket Item - Responsive Form Field Start -->
<div id="fb_fld-591442" rf-field="true" class="" style="<?php echo $visible; ?> float:left; width:100%; z-index:1; margin-bottom:15px">
				
				
<?php
// Process Array Variables.
if(!isset($array_vars_processed)){ $vars = array(); process_array_variables($vars, ', '); $array_vars_processed = true; }
?>
		
<div class="body-copy " style="color:#d3d3d3; font-size:10pt; font-weight:normal; width:95%;    border-radius:0px;  text-align:left;   padding-left:0px;  padding-right:0px;  padding-top:0px;  padding-bottom:0px;  ">
<p>Click on a question to review.</p>
</div>

</div>
<!-- Form Field End -->

<?php
$visible = '';
$enabled= '';
?>

<!-- Bucket Item - Responsive Form Field Start -->
<div id="fb_fld-PreviousSummary" rf-field="true" class="" style="<?php echo $visible; ?> float:left; width:; z-index:1; margin-bottom:15px">
				
				
<style type="text/css" scoped>

#PreviousSummary{

}

#PreviousSummary:hover{

}

#PreviousSummary:focus{

}

</style>
		

<input class="nolabel btn  " type="button" name="PreviousSummary" id="PreviousSummary"  value="Review All" onClick=TEP_EXAM.controller.reviewAllButtonClicked();   />
</div>
<!-- Form Field End -->

<?php
$visible = '';
$enabled= '';
?>

<!-- Bucket Item - Responsive Form Field Start -->
<div id="fb_fld-ReviewMarked" rf-field="true" class="" style="<?php echo $visible; ?> float:left; width:; z-index:1; margin-bottom:15px">
				
				
<style type="text/css" scoped>

#ReviewMarked{

}

#ReviewMarked:hover{

}

#ReviewMarked:focus{

}

</style>
		

<input class="nolabel btn  " type="button" name="ReviewMarked" id="ReviewMarked"  value="Review Marked" onClick=TEP_EXAM.controller.reviewMarkedButtonClicked();   />
</div>
<!-- Form Field End -->

<?php
$visible = '';
$enabled= '';
?>

<!-- Bucket Item - Responsive Form Field Start -->
<div id="fb_fld-ReviewIncomplete" rf-field="true" class="" style="<?php echo $visible; ?> float:left; width:; z-index:1; margin-bottom:15px">
				
				
<style type="text/css" scoped>

#ReviewIncomplete{

}

#ReviewIncomplete:hover{

}

#ReviewIncomplete:focus{

}

</style>
		

<input class="nolabel btn  " type="button" name="ReviewIncomplete" id="ReviewIncomplete"  value="Review Incomplete" onClick=TEP_EXAM.controller.reviewIncompleteButtonClicked();   />
</div>
<!-- Form Field End -->

<?php
$visible = '';
$enabled= '';
?>

<!-- Bucket Item - Responsive Form Field Start -->
<div id="fb_fld-NextSummary" rf-field="true" class="" style="<?php echo $visible; ?> float:left; width:; z-index:1; margin-bottom:15px">
				
				
<style type="text/css" scoped>

#NextSummary{

}

#NextSummary:hover{

}

#NextSummary:focus{

}

</style>
		

<input class="nolabel btn  " type="button" name="NextSummary" id="NextSummary"  value="End Section" onClick=TEP_EXAM.controller.endSectionButtonClicked();   />
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
<div id="fb_fld-NavIntermission" rf-field="true" class="" style="float:left; width:100%; margin-bottom:9px; <?php echo $visible; ?>" >


<!-- bucket start -->
<div id="NavIntermission" class="bucket" style="position:relative; height:200px; width:; " >
<?php
$visible = '';
$enabled= '';
?>

<!-- Bucket Item - Responsive Form Field Start -->
<div id="fb_fld-spinner" rf-field="true" class="spinner" style="<?php echo $visible; ?> float:left; width:100%; z-index:1; margin-bottom:15px">
				
				<img class="form-image " src="<?php echo $_SESSION['MAX_PATH']; ?>images/ajax-loader.gif" title=""   name="spinner" id="spinner" />
</div>
<!-- Form Field End -->

<?php
$visible = '';
$enabled= '';
?>

<!-- Bucket Item - Responsive Form Field Start -->
<div id="fb_fld-ReviewIntermission" rf-field="true" class="" style="<?php echo $visible; ?> float:left; width:100%; z-index:1; margin-bottom:15px">
				
				
<style type="text/css" scoped>

#ReviewIntermission{

}

#ReviewIntermission:hover{

}

#ReviewIntermission:focus{

}

</style>
		

<input class="nolabel btn  " type="button" name="ReviewIntermission" id="ReviewIntermission"  value="Review" onClick=TEP_EXAM.controller.reviewIntermissionButtonClicked();   />
</div>
<!-- Form Field End -->

<?php
$visible = '';
$enabled= '';
?>

<!-- Bucket Item - Responsive Form Field Start -->
<div id="fb_fld-NextIntermission" rf-field="true" class="" style="<?php echo $visible; ?> float:left; width:100%; z-index:1; margin-bottom:15px">
				
				
<style type="text/css" scoped>

#NextIntermission{

}

#NextIntermission:hover{

}

#NextIntermission:focus{

}

</style>
		

<input class="nolabel btn  " type="button" name="NextIntermission" id="NextIntermission"  value="Next" onClick=TEP_EXAM.controller.nextIntermissionButtonClicked();   />
</div>
<!-- Form Field End -->


</div><!-- bucket end -->


</div>
<!-- Form Field End -->


<div style="clear:both;"></div><!-- Clear -->


</form>
<?php if(isset($_SESSION["e_message"])){  ?>
<div class="err-msg"><?php echo html_entity_decode($_SESSION["e_message"], ENT_QUOTES); ?></div>
<?php unset($_SESSION["e_message"]); } ?>

<div style="clear:both;"></div>
</div>

</body>
</html>
