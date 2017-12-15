<?php
require_once '/home/afonseca/www/amember/bootstrap.php';
require_once 'Db.php';

define("QUESTIONS_TABLE", "Questions_v2");

function question_details_admin(){
	$user = am_Di::getInstance()->auth->getUserId();
	$db = new Db();
	$q_id = (int)$_GET['id'];
	
	if($q_id) {
		$q1 = $db->select('SELECT * FROM ' . QUESTIONS_TABLE . ' WHERE is_active = 1 AND `id` = '.$q_id);
		if(!$q1){
			'<h3>Question not found!</h3>';
			exit;
		}
	}else {
		$q1 = $db->select('SELECT * FROM ' . QUESTIONS_TABLE . ' WHERE is_active = 1 LIMIT 1');
		$q_id = $q1[0]['id'];
	}
	$prev = $db->select('SELECT `id` FROM ' . QUESTIONS_TABLE . ' WHERE `id` < '.$q_id.' ORDER BY `id` DESC LIMIT 1');
	if($prev){
		$prev = $prev[0]['id'];
	}else $prev = false;
	
	$next = $db->select('SELECT `id` FROM ' . QUESTIONS_TABLE . ' WHERE `id` > '.$q_id.' ORDER BY `id` ASC LIMIT 1');
	if($next){
		$next = $next[0]['id'];
	}else $next = false;
	
	$system = $q1[0]['system'];
	$content_section = $q1[0]['content_section'];
	$content_subsection = $q1[0]['content_subsection'];

	$question = $q1[0]['question'];
	$image_url = $q1[0]['image_url'];
	$video_url = $q1[0]['video_url'];
	
	$options = array($q1[0]['answer1'], $q1[0]['answer2'], $q1[0]['answer3'], $q1[0]['answer4']);

	$rationale = $q1[0]['rationale'];
	$rationales = array($q1[0]['rationale1'], $q1[0]['rationale2'], $q1[0]['rationale3'], $q1[0]['rationale4']);

	$correct_answer = $q1[0]['mc_answer'];

	$is_experimental = $q1[0]['is_experimental'];

	$clinical_focus = $q1[0]['clinical_focus'];
	$stem = $q1[0]['stem'];
	$kw_stem = $q1[0]['keywords_in_stem'];
	$kw_answer = $q1[0]['keywords_in_answer'];
	$kw_previous = $q1[0]['keywords_in_previous'];

	$kwords = array($q1[0]['keywords_in_1st'], $q1[0]['keywords_in_2nd'], $q1[0]['keywords_in_3rd'], $q1[0]['keywords_in_4th']);

	$rationale_options = array($q1[0]['mc_option1'], $q1[0]['mc_option2'], $q1[0]['mc_option3'], $q1[0]['mc_option4']);

	if($prev) $prev = 'page0.php?id='.$prev;
	if($next) $next = 'page0.php?id='.$next;
	?>
	<h1 class="review-title">Review All</h1>
	<p>
		<a class="back_button" href="page0.php?id=<?php echo $q_id;?>">JUMP TO</a>
		<input type="number" id="jumpto" value="<?php echo $q_id;?>">
		<script>
			$('#page0').submit(function(e){
				e.preventDefault();
			});
			$('#jumpto').keyup(function(e){
				var code = (e.keyCode ? e.keyCode : e.which);
				if(code == 13){
					e.preventDefault();
					var v = $(this).val();
					location.href = 'page0.php?id='+v;
				}
			});
			$('#jumpto').blur(function(){
				var v = $(this).val();
				$(this).parent().find('.back_button').attr('href', 'page0.php?id='+v);
			});
		</script>
	</p>
	<input type="hidden" id="question_id" value="<?php echo $q_id;?>">
	<div class="question-controls">
		<div>
			<?php if($prev){?>
			<a href="<?php echo $prev;?>">&lt;&lt;</a>
			<?php }?>
		</div>
		<div>Question ID <strong><?php echo $q_id;?></strong></div>
		<div>
			<?php if($next){?>
			<a href="<?php echo $next;?>">&gt;&gt;</a>
			<?php }?>
		</div>
	</div>
	<?php
	if($is_experimental){ ?>
	<div class="experimental">Experimental</div>
	<?php }?>
	<section class="question_block question_classification">
		<p>System: <b><?php echo $system;?></b></p>
		<p>Content Section: <b><?php echo $content_section;?></b></p>
		<?php if(strtolower($content_section) == 'non-system domains' || strtolower($content_section) == 'non system domains'){?>
			<p>Content Subsection: <b><?php echo $content_subsection;?></b></p>
		<?php }?>
	</section>
	<p class="question"><?php
		if($image_url) echo '<img src="'.$image_url.'" alt="Picture">';
		echo str_replace(array('MOST', 'BEST', 'INITIAL'), array('<strong>MOST</strong>', '<strong>BEST</strong>', '<strong>INITIAL</strong>'), $question);
	?></p>
	<p>Correct Answer: <b><?php echo $correct_answer;?></b></p>
	<?php
	foreach($options as $k => $option){?>
		<p <?php if($correct_answer == $k + 1) echo 'class="correct_answer"';?>><label><span class="radio <?php if($correct_answer == $k + 1) echo 'checked';?>"></span> <b><?php echo $k + 1;?></b>. <?php echo $options[$k];?></label></p>
	<?php } ?>

	<p class="rationale-block"><b>Rationale:</b> <?php echo $rationale;?></p>
	<h3>Choice Rationale Reasoning</h3>
	<p><b>1.</b> <?php echo $rationales[0];?></p>
	<p><b>2.</b> <?php echo $rationales[1];?></p>
	<p><b>3.</b> <?php echo $rationales[2];?></p>
	<p><b>4.</b> <?php echo $rationales[3];?></p>

	<h3 style="background:#EAEAEA; padding:20px; text-align: center;">Exam Prep Course Extra Guidance</h3>
	
	<h3>Clinical Focus</h3>
	<p><?php echo $clinical_focus;?></p>
	
	<h3>TEP Exam Process</h3>
	<p><b>Stem</b>: <?php echo $stem;?></p>
	<p><b>Keywords in Stem</b>: <?php echo $kw_stem;?></p>
	<p><b>Keywords in Answer</b>: <?php echo $kw_answer;?></p>
	
	<h3>Keywords in Question</h3>
	<p><b>Previous sentence</b>: <?php echo $kw_previous;?></p>
	<p><b>4th sentence</b>: <?php echo $kwords[3];?></p>
	<p><b>3rd sentence</b>: <?php echo $kwords[2];?></p>
	<p><b>2nd sentence</b>: <?php echo $kwords[1];?></p>
	<p><b>1st sentence</b>: <?php echo $kwords[0];?></p>
	
	<h3>Test Taking Rationale</h3>
	<?php
	foreach($rationale_options as $k => $option){?>
	<p><b>Choice <?php echo $k + 1;?></b> <?php echo $rationale_options[$k];?></p>
	<?php }?>
	<?php if(isset($video_url) && strlen($video_url)){?>
		<h3>Video Walkthrough</h3>
		<?php if(strpos($video_url, '<iframe')) echo $video_url; else{?>
		<iframe src="<?php echo $video_url;?>" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
		<?php }?>
	<?php }?>
	
	<div class="question-controls bottom-controls">
		<div>
			<?php if($prev){?>
			<a href="<?php echo $prev;?>">&lt;&lt;</a>
			<?php }?>
		</div>
		<div>Question ID <strong><?php echo $q_id;?></strong></div>
		<div>
			<?php if($next){?>
			<a href="<?php echo $next;?>">&gt;&gt;</a>
			<?php }?>
		</div>
	</div>
	<?php
}