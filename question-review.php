<?php
require_once '/home/afonseca/www/amember/bootstrap.php';
require_once 'Db.php';

function getUsername($userID = null)
{
    if ($userID)
    {
        try
        {
        	$db = new Db();
            $queryStr = "SELECT login FROM afonseca_amember.am_user WHERE user_id = " . $userID;        
            $rows = $db->select($queryStr);
            echo $rows[0]['login'];
        }
        catch(DbException $e)
        {
            echo "Unknown"; 
        }
    }
    else
    {
        // Get aMember logged in user
        $user = am_Di::getInstance()->auth->getUser();

        if ($user)
        {
            echo $user->login;
        }
    }
}

function is_owner_of_test(){
	global $userID;
	$user = am_Di::getInstance()->auth->getUserId();
	return $user == $userID;
}

function displayReviewRows()
{
    global $answers, $results;
	
	$answer_number = count($answers);
	echo '<input type="hidden" id="exam_id" value="',intval($_GET['id']),'">';
    for ($i = 0; $i < $answer_number; $i++) {
		if($i % 50 == 0) echo '<tr><td colspan="3" class="section_title">Section ',($i / 50 + 1),'</td></tr>';
        $q = $i+1;
        $id = $answers[$i]->id;
        $result = $results[$i];

        $url = 'question.php?exam='.intval($_GET['id']).'&id='.$id;

        echo "<tr class='",($results[$i]=='Correct'?'correct':'incorrect'),"'>\n";      
        echo "<td>${q}</td>";
        echo "<td class='result-cell'>${result}</td>";
        echo "<td><a href='",$url,"'>Review</a></td>";
        echo "</tr>\n"; 
    }    
}

// Returns true if logged in user has an active course product.
function isUserInCourse()
{
    $category_id = 1; // Courses category

    // Get aMember logged in user
    $user = am_Di::getInstance()->auth->getUser();
    
    if($user) 
    {
        // Get array of products by category
        $products = Am_Di::getInstance()->productCategoryTable->getCategoryProducts();

        $prodIds = $user->getActiveProductIds();

        // If user has active course product
        if (!empty(array_intersect($prodIds, $products[$category_id])))
        {
            return true;
        }            
    }

    return false;
}

/**
	Displays question review details
*/
function question_details($isAdmin = false){
	$user = am_Di::getInstance()->auth->getUserId();
	$db = new Db();
	$exam_id = (int)$_GET['exam'];
	if(isset($_GET['type'])) $type = (int)$_GET['type'];
	else $type = 0;
	$q_id = (int)$_GET['id'];

	$exam = $db->select('SELECT Exams.userID, Exams.timeScale, Exams.answers, Exams.answerOrder, Exams.results, Exams.attemptNum FROM Exams WHERE id = ' . $exam_id);
	$examUserID = $exam[0]['userID'];

    // If not admin
    if (!$isAdmin)
    {
        /**
            Checking if current user is the one who took the exam
        */
        if($user != $exam[0]['userID']){
            echo '<h2>You are not authotized to see this page</h2>';
            return false;
        }
    }
	$timescale = (float)$exam[0]['timeScale']?$exam[0]['timeScale']:1;
	$attemptNum = $exam[0]['attemptNum'];
	$questions = json_decode($exam[0]['answers']);
	$results = json_decode($exam[0]['results']);
	$order = explode(',', str_replace(array('[',']'), '', $exam[0]['answerOrder']));

	$current_question = 0; $time = 0;
	$initial = $correct = $guessed = false; $difficulty = '';
	$q1 = array();
	foreach($questions as $k => $q){
		if(!$q_id){
			$correct = false;
			if($type == 0) $q_id = $q->id;
			else{
				if($results[$k] == 'Correct') $correct = true;
				if(($correct && $type == 2) || (!$correct && $type == 1)) $q_id = $q->id;
			}
		}
		if( $q->id == $q_id){
			if($q_id || !$type){
				$answer = $q->choice;//$order[$q->choice - 1];
				if($results[$k] == 'Correct') $correct = true;
			}
			$current_question = $k;
			$time = $q->timeElapsed;
			$guessed = $q->guessed;
			$difficulty = $q->difficulty;
			$initial = $q->firstChoice;
			$q1 = $db->select('SELECT * FROM Questions WHERE id = ' . $q->id);
			break;
		}
	}
	
	$system = $q1[0]['system'];
	$content_section = $q1[0]['content_section'];
	$content_subsection = $q1[0]['content_subsection'];

	$question = $q1[0]['question'];
	$image_url = $q1[0]['image_url'];
	$video_url = $q1[0]['video_url'];
	//if(!$image_url) $image_url = 'https://therapyexamprep.com/products/practice-exam-staging/resources/images/xray.jpg';
	
	$options = array($q1[0]['answer1'], $q1[0]['answer2'], $q1[0]['answer3'], $q1[0]['answer4']);

	$rationale = $q1[0]['rationale'];
	$rationales = array($q1[0]['rationale1'], $q1[0]['rationale2'], $q1[0]['rationale3'], $q1[0]['rationale4']);

	$correct_answer = array_search($q1[0]['mc_answer'], $order) + 1;

	$is_experimental = $q1[0]['is_experimental'];

	$clinical_focus = $q1[0]['clinical_focus'];
	$stem = $q1[0]['stem'];
	$kw_stem = $q1[0]['keywords_in_stem'];
	$kw_answer = $q1[0]['keywords_in_answer'];
	$kw_previous = $q1[0]['keywords_in_previous'];

	$kwords = array($q1[0]['keywords_in_1st'], $q1[0]['keywords_in_2nd'], $q1[0]['keywords_in_3rd'], $q1[0]['keywords_in_4th']);

	$rationale_options = array($q1[0]['mc_option1'], $q1[0]['mc_option2'], $q1[0]['mc_option3'], $q1[0]['mc_option4']);
	
	$review_types = array('All', 'Incorrect', 'Correct');
	
	$prev = $next = false;
	if($type == 1 || $type == 2){
		if($current_question < count($questions) - 1) for($i = $current_question + 1; $i < count($results); $i++){
			if(($results[$i] != 'Correct' && $type == 1) || ($results[$i] == 'Correct' && $type == 2)){
				$next = 'question.php?exam='.$exam_id.'&id='.$questions[$i]->id.'&type='.$type;
				break;
			}
		}
		if($current_question > 0) for($i = $current_question - 1; $i >= 0; $i--){
			if(($results[$i] != 'Correct' && $type == 1) || ($results[$i] == 'Correct' && $type == 2)){
				$prev = 'question.php?exam='.$exam_id.'&id='.$questions[$i]->id.'&type='.$type;
				break;
			}
		}
	}else{
		if($current_question > 0) $prev = 'question.php?exam='.$exam_id.'&id='.$questions[$current_question - 1]->id.'&type=0';
		if($current_question < count($questions) - 1) $next = 'question.php?exam='.$exam_id.'&id='.$questions[$current_question + 1]->id.'&type=0';
	}
	?>
	<h1 class="review-title">Review <?php echo $review_types[$type];?></h1>
	<p><a class="back_button" href="review.php?id=<?php echo $exam_id;?>">Back</a></p>
	<p>Name: <span id="username"><?php if (!$isAdmin) {getUsername();} else {getUsername($examUserID);} ?></span></p>
	<input type="hidden" id="question_id" value="<?php echo $q_id;?>">
	<div class="section_title">Section <?php echo floor($current_question / 50) + 1;?></div>
	<div class="question-controls">
		<div>
			<?php if($prev){?>
			<a href="<?php echo $prev;?>">&lt;&lt;</a>
			<?php }?>
		</div>
		<div>Question <?php echo $current_question + 1;?></div>
		<div>
			<?php if($next){?>
			<a href="<?php echo $next;?>">&gt;&gt;</a>
			<?php }?>
		</div>
	</div>
	<div class="qresult<?php if($correct) echo ' correct'; else echo ' incorrect';?>"><?php if($correct) echo 'Correct'; else echo 'Incorrect';?></div>
	<?php
	if($is_experimental){ ?>
	<div class="experimental">Experimental</div>
	<?php }?>
	<section class="question_block question_classification">
		<p>Time: <b <?php if($time > 72 * $timescale) echo 'class="too_much_time"';?>><?php echo ($time?$time:0), ' seconds';?></b></p>
		<p>System: <b><?php echo $system;?></b></p>
		<p>Content Section: <b><?php echo $content_section;?></b></p>
		<?php if(strtolower($content_section) == 'non-system domains' || strtolower($content_section) == 'non system domains'){?>
			<p>Content Subsection: <b><?php echo $content_subsection;?></b></p>
		<?php }?>
	</section>
	<section class="question_block question_stats">
		<p>Your difficulty rating: <b><?php echo $difficulty?ucfirst($difficulty):'N/A';?></b></p>
		<p>Guessed: <b><?php echo $guessed?'Yes':'No';?></b></p>
	</section>
	<p class="question" data-id="<?php echo $exam_id;?>" data-attempt="<?php echo $attemptNum;?>"><?php
		if($image_url) echo '<img src="'.$image_url.'" alt="Picture">';
		echo str_replace(array('MOST', 'BEST', 'INITIAL'), array('<strong>MOST</strong>', '<strong>BEST</strong>', '<strong>INITIAL</strong>'), $question);
	?></p>
	<p>Initial Answer: <b><?php echo $initial; if($initial == $correct_answer) echo ' &#10003;';?></b></p>
	<?php
	foreach($options as $k => $option){?>
		<p <?php if($correct_answer == $k + 1) echo 'class="correct_answer"';?>><label><span class="radio <?php if($answer == $k + 1) echo 'checked';?>"></span> <b><?php echo $k + 1;?></b>. <?php echo $options[$order[$k] - 1];?></label></p>
	<?php } ?>

	<p class="rationale-block"><b>Rationale:</b> <?php echo $rationale;?></p>
	<h3>Choice Rationale Reasoning</h3>
	<p><b>1.</b> <?php echo $rationales[$order[0] - 1];?></p>
	<p><b>2.</b> <?php echo $rationales[$order[1] - 1];?></p>
	<p><b>3.</b> <?php echo $rationales[$order[2] - 1];?></p>
	<p><b>4.</b> <?php echo $rationales[$order[3] - 1];?></p>

	<?php if(isUserInCourse() || $isAdmin){?>
		<h3 style="background:#EAEAEA; padding:20px; text-align: center;">Exam Prep Course Extra Guidance</h3>
		
		<h3>Clinical Focus</h3>
		<p><?php echo $clinical_focus;?></p>
		
		<h3>Question</h3>
		<p class="question" data-id="<?php echo $exam_id;?>" data-attempt="<?php echo $attemptNum;?>"><?php
			if($image_url) echo '<img src="'.$image_url.'" alt="Picture">';
			$question = explode('.', str_replace(array('MOST', 'BEST', 'INITIAL'), array('<strong>MOST</strong>', '<strong>BEST</strong>', '<strong>INITIAL</strong>'), $question));
			$line = '<span class="stem-line">'.array_pop($question).'</span>';
			echo implode('.', $question),'.',$line;
		?></p>
		<p>Initial Answer: <b><?php echo $initial; if($initial == $correct_answer) echo ' &#10003;';?></b></p>
		<?php
		foreach($options as $k => $option){?>
			<p <?php if($correct_answer == $k + 1) echo 'class="correct_answer"';?>><label><span class="radio <?php if($answer == $k + 1) echo 'checked';?>"></span> <b><?php echo $k + 1;?></b>. <?php echo $options[$order[$k] - 1];?></label></p>
		<?php } ?>
		
		<h3>TEP Exam Process</h3>
		<p><b>Stem</b>: <?php echo $stem;?></p>
		<p><b>Keywords in Stem</b>: <?php echo $kw_stem;?></p>
		<p><b>Keywords in Answer</b>: <?php echo $kw_answer;?></p>
		
		<h3>Keywords in Question</h3>
		<p><b>Previous sentence</b>: <?php echo $kw_previous;?></p>
		<p><b>4th sentence</b>: <?php echo $kwords[$order[3] - 1];?></p>
		<p><b>3rd sentence</b>: <?php echo $kwords[$order[2] - 1];?></p>
		<p><b>2nd sentence</b>: <?php echo $kwords[$order[1] - 1];?></p>
		<p><b>1st sentence</b>: <?php echo $kwords[$order[0] - 1];?></p>
		
		<h3>Test Taking Rationale</h3>
		<?php
		foreach($rationale_options as $k => $option){?>
		<p><b>Choice <?php echo $k + 1;?></b> <?php echo $rationale_options[$order[$k] - 1];?></p>
		<?php }?>
		<?php if(isset($video_url) && strlen($video_url)){?>
			<h3>Video Walkthrough</h3>
			<?php if(strpos($video_url, '<iframe')) echo $video_url; else{?>
			<iframe src="<?php echo $video_url;?>" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
			<?php }?>
		<?php }?>
	<?php }elseif(!isset($_COOKIE['closedalert-'.$exam_id.'-'.$attemptNum]) || !$_COOKIE['closedalert-'.$exam_id.'-'.$attemptNum]) {?>
		<div class="alert alert-info alert-dismissable">
			<a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
			<strong class="material-icons">Info!</strong> Those enrolled in our exam prep course get <a href="/wp-content/themes/trades-child/images/Review-3.jpg" class="fancyboxable">extra guidance</a> when reviewing each exam question.
		</div>
	<?php }?>
	<div class="question-controls bottom-controls">
		<div>
			<?php if($prev){?>
			<a href="<?php echo $prev;?>">&lt;&lt;</a>
			<?php }?>
		</div>
		<div>Question <?php echo $current_question + 1;?></div>
		<div>
			<?php if($next){?>
			<a href="<?php echo $next;?>">&gt;&gt;</a>
			<?php }?>
		</div>
	</div>
	<?php
}
?>