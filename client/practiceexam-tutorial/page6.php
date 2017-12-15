<?php
require_once '/home/afonseca/public_html/amember/library/Am/Lite.php';

$username = Am_Lite::getInstance()->getUsername();
$email = Am_Lite::getInstance()->getEmail();
?>
<?php
//--
// PHP Page Script - Generated: January 6, 2017
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
$_SESSION['entry_key_practiceexam-tutorial'] = isset($_SESSION['entry_key_practiceexam-tutorial']) ? $_SESSION['entry_key_practiceexam-tutorial'] : md5(time() + rand(10000, 1000000));

// Form Page Security
$domain_list = explode(',',"");
$ip_limit = 0;
$job_id = '61';
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
$fb_login->persistent_login_job_id = 61;
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
$fb_login->redirect = "page6.php";

$_SESSION['qs']["{$_SESSION['entry_key_practiceexam-tutorial']}"]['fb_login'] = $fb_login;


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
loadPersistantValues(0, $job_id, "page6.php");

// Build 805
if(1 == 0){
    init_stats($job_id, "page6.php", 'form');
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
<title>Practice Exam Tutorial</title>
<link rel="stylesheet" type="text/css" href="<?php echo $_SESSION['MAX_PATH']; ?>formpage.css" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo $_SESSION['MAX_PATH']; ?>print.css" media="print" />
<script type="text/javascript" src="<?php echo $_SESSION['MAX_PATH']; ?>js/jquery/jquery-full.js"></script>


<!-- tinymce -->

<!-- val script -->
<script type="text/javascript">
	var phppath = '<?php echo $_SESSION['MAX_PATH']; ?>';
	var pageName = 'page6.xml';
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
<script src='https://therapyexamprep.com/products/practice-exam/vendor/EventEmitter.min.js'></script>
<script src='https://therapyexamprep.com/products/practice-exam/vendor/easytimer.min.js'></script>
<script src='https://therapyexamprep.com/products/practice-exam/vendor/moment.min.js'></script>
<script src="https://therapyexamprep.com/products/practice-exam/vendor/TextHighlighter.min.js"></script>
<script src="https://therapyexamprep.com/products/practice-exam/vendor/flat-ui/flat-ui.min.js"></script>

<script src="https://therapyexamprep.com/products/practice-exam/resources/js/tutorial.min.js"></script>
<script src="https://therapyexamprep.com/products/practice-exam/resources/js/tutorial-ui.js"></script>
<script src="https://therapyexamprep.com/products/practice-exam/resources/js/flat-ui-app.js"></script>

<!-- Stylesheets -->
<link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="https://therapyexamprep.com/products/practice-exam/vendor/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="https://therapyexamprep.com/products/practice-exam/vendor/flat-ui/flat-ui.min.css">

<link rel="stylesheet" type="text/css" href="https://therapyexamprep.com/products/practice-exam/resources/css/tutorial.css">



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
<div id="rackforms-output-div-page6" class="rackforms-output-div col-lg-9 ui form" style=" font-family: DejaVu Sans, Arial, Helvetica, sans-serif;       width:94%;   ">
<form class="rackforms-output-sortable" action="<?php echo $_SESSION['MAX_PATH']; ?>page6_process.php<?php echo $sid_url; ?>" method="post" enctype="application/x-www-form-urlencoded" name="page6php" id="page6" target="_self" >
<?php
$visible = '';
$enabled = '';
?>

<!-- Form Field Start -->
<div id="fb_fld-TopHeader6" rf-field="true" class="" style="float:left; width:100%; margin-bottom:9px; <?php echo $visible; ?>" >


<!-- bucket start -->
<div id="TopHeader6" class="bucket" style="position:relative; height:69px; width:; " >
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
<div id="fb_fld-Logo_1_2_3_4_5_6_7" rf-field="true" class="" style="<?php echo $visible; ?> float:left; width:33%; z-index:1; margin-bottom:15px">
				
				
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
<div id="fb_fld-HeaderView6" rf-field="true" class="" style="float:left; width:100%; margin-bottom:9px; <?php echo $visible; ?>" >


<!-- bucket start -->
<div id="HeaderView6" class="bucket" style="position:relative; height:69px; width:; " >
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
<span id="questionDisplay">Page 7 of 8</span>
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
<span id="sectionDisplay">Tutorial</span>
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
<span>Time Remaining: </span><span id="TimerDisplay">--:--:--</span>
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
<div id="fb_fld-SummaryView6" rf-field="true" class="" style="float:left; width:; margin-bottom:9px; <?php echo $visible; ?>" >

<!-- Block Code End -->


</div>
<!-- Form Field End -->


<div style="clear:both;"></div><!-- Clear -->

<?php
$visible = '';
$enabled = '';
?>

<!-- Form Field Start -->
<div id="fb_fld-QuestionView6" rf-field="true" class="" style="float:left; width:100%; margin-bottom:9px; <?php echo $visible; ?>" >


<!-- bucket start -->
<div id="QuestionView6" class="bucket" style="position:relative; height:127px; width:; " >
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
<p>Questions can be marked for review by clicking the Mark button. At the end of each exam section, you will have the opportunity to review any questions in that section. Marking questions during your exam will help you identify them later at review time.</p>
<p>Try it now, click the Mark button at the bottom of the screen and notice how the color of the button changes to indicate the question is marked. Click it again to clear the mark.</p>
<p>Click 'Next' to continue...</p>
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
<div id="fb_fld-NavQuestion6" rf-field="true" class="" style="float:left; width:100%; margin-bottom:9px; <?php echo $visible; ?>" >


<!-- bucket start -->
<div id="NavQuestion6" class="bucket" style="position:relative; height:69px; width:; " >
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
		

<input class="nolabel btn  " type="button" name="Previous" id="Previous"  value="Previous" onClick=TEP_EXAM.controller.prevButtonClicked('page5.php');   />
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
		

<input class="nolabel btn  " type="button" name="Next" id="Next"  value="Next" onClick=TEP_EXAM.controller.nextButtonClicked('page7.php');   />
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
