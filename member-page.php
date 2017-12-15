<?php
require_once '/home/afonseca/www/amember/bootstrap.php';
require_once 'Db.php';

function earlyTestRetake($exam, $attempt){
	$attempt = (int)$attempt;
	$exam = (int)$exam;
	if($attempt > 1 && $exam){
		$db = new Db();
		// Get aMember logged in user
		if(!Am_Di::getInstance()->auth->getUserId()) {
			Am_Di::getInstance()->auth->checkExternalLogin(Am_Di::getInstance()->request);
		}
		$user_id = (int)am_Di::getInstance()->auth->getUserId();
		if($user_id <= 0) return false;
		
		$res = $db->select("SELECT `timestamp` FROM `Exams` WHERE `userID` = '".$user_id."' AND `examNum` = '".$exam."' AND `attemptNum` = ".($attempt - 1));
		if($res && isset($res[0]['timestamp']) && time() - strtotime($res[0]['timestamp']) < 60*60*24*30) return true;
	}
	return false;
}

function displayUserExams()
{
    $db = new Db();

    // Get aMember logged in user
	if(!Am_Di::getInstance()->auth->getUserId())
	{
		Am_Di::getInstance()->auth->checkExternalLogin(Am_Di::getInstance()->request);
	}
    $user = am_Di::getInstance()->auth->getUser();
	
    if($user) 
    {
        // Get exams user data
        $exams = $user->data()->getBlob("exams");
        if ($exams) {
            $exams = json_decode($exams);

			$exam_in_progress = false;
			$all_attempts = array();
			foreach ($exams as $exam) {
				if(!empty($exam->attemptIDs) && count($exam->attemptIDs)) $all_attempts = array_merge($all_attempts, $exam->attemptIDs);
			}
			if(count($all_attempts)){
				$queryStr = "SELECT `id` FROM `Exams` WHERE `id` IN (" . implode(', ', $all_attempts) ." ) AND `examSurveyID` IS NULL LIMIT 1";
				$rows = $db->select($queryStr);
				if (count($rows)) $exam_in_progress = $rows[0]['id'];
			}
            // Display all exam records
			echo '<div class="exams">';
            foreach ($exams as $i => $exam) 
            {
                $examNum = $i + 1;
                $attemptsRemaining = $exam->attemptsRemaining;
                $attempts = $exam->attemptIDs;
                $attemptsInfo = array();

                $isInProgress = false;

                if (!empty($attempts))
                {
                    // Get info for each attempt
                    foreach ($attempts as $attemptID)
                    {
                        $queryStr = "SELECT `timestamp`,`attemptNum`," .   
                        "`score`,`examSurveyID` FROM `Exams` WHERE `id`=" . 
                        $attemptID;

                        $rows = $db->select($queryStr);
                        $rows = $rows[0]; // Get first row

                        // If exam survey not recorded, it is in progress.
                        if ($rows["examSurveyID"] == null) {
                            $isInProgress = $attemptID;
                            break;
                        }

                        $date = date("m-d-Y", strtotime($rows["timestamp"]));
                        array_push($attemptsInfo, array("id" => $attemptID,
                            "date" => $date,
                            "attempt" => $rows["attemptNum"],
                            "score" => $rows["score"]));
                    }
                }

                if ($isInProgress)
                {
                    echo '<div class="exam inprog ip">';
                    echo "<div class='in-progress' data-exam='".$examNum."' data-id='".$isInProgress."'>In Progress</div>";
                    echo "<h2>Exam ${examNum}</h2>";
                    echo '</div>';
                    continue;
                }
                else
                {
                    // Display exam header
                    echo '<div class="exam ',($attemptsRemaining?'':'compl'),'">';
					
					if ($attemptsRemaining == 0){
                        $lastAttempt = end($attemptsInfo);
                        if ($lastAttempt) {
                            $date = $lastAttempt['date'];
                            echo "<div class='completed'>Completed on ".date('F j, Y', strtotime(str_replace('-', '/', $date)))."</div>";
                        }
					}else echo "<div class='attempts'><b>${attemptsRemaining}</b> Attempt",($attemptsRemaining > 1?'s':'')," Remaining</div>";
					echo "<h2>Exam ${examNum}</h2>";
                   
				   echo '<div>'; // Attempts list
                    $i = 0;
                    $attempted = count($attemptsInfo);
                    foreach ($attemptsInfo as $info) {  $i++; $info["date"] = str_replace('-', '/', $info["date"]);?>
                        <div class="attempt">
                            <div>
                                <div class="cell"><?php echo "Attempt <b>" . $info["attempt"]. '</b>';?></div>
                                <div class="cell date"><?php echo date('F j, Y', strtotime($info["date"]));?></div>
                                <div class="cell"><?php echo "Score <b>" . $info["score"] . '</b>';?></div>
                                <?php $reportURL = 'https://therapyexamprep.com/members/practice-exams/score-report/?id='.$info['id'];
									$reviewURL = 'https://therapyexamprep.com/rackforms/output/forms/member-practice-exams/review.php?id='.$info['id'];
								?>
                                <div class="break-point cell"><?php echo "<a target='_parent' href=\"${reportURL}\">Score Report</a>";?></div>
                                <div class="cell"><?php echo "<a href=\"${reviewURL}\">Review</a>";?></div>
                                <div class="clear"></div>
                            </div>
                        </div>
                        <?php
                    }

                    if ($attemptsRemaining > 0) {
                        // Get attempt number.
                        $attemptNum = 1;
                        $lastAttempt = end($attemptsInfo);
                        if ($lastAttempt)
                        {
                            $attemptNum = $lastAttempt["attempt"] + 1;
                        }

                        $url = "page1.php?exam=${examNum}&attempt=${attemptNum}";
                        
						for($j = 0; $j < $attemptsRemaining; $j++){
							if($j == 0) echo '<div class="attempt"><h3><a href="'.$url.'">Attempt ',($j + $attempted + 1),'</a></h3></div>';
							else echo '<div class="attempt"><h3>Attempt ',($j + $attempted + 1),'</h3></div>';
						}
                    }
						echo '</div>'; // Close attempts list
                    echo "</div>"; // Close exam div
                }
            }
			echo '</div>'; // exams
        }
    }
	else
	{
		echo "Unable to get logged in user.";
	}
}
?>