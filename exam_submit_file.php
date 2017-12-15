<?php
require_once 'defines.php';
require_once 'Db.php';

echo "Submitting exam...\n";
$examID = submitExam(4975); // specify user ID
if ($examID)
{
    updateMissedQuestions($examID);
}


// Save exam json data from file to Exams table with id.
// Replies with "OK" if success, other string if error.
function submitExam($userID)
{
	// Fetch the raw POST body containing the message
	$postBody = file_get_contents('./exam_data.json');

	// JSON decode the body to an array of message data
	$data = json_decode($postBody, true);
	if ($data) 
	{
		$examID = $data['id'];

		// Save data to file
		//file_put_contents('exam_data.txt', print_r($data, true));
		
		$db = new Db();
								
		try
		{
			// Save to database
			$result = $db->queryPrepared("UPDATE " . EXAMS_TABLE . " SET " . 
			"breakStartTime = ?, breakEndTime = ?, elapsedTime = ?, " .
			"answers = ? WHERE id = ?",
			array(
				$data['breakStartTime'], 
				$data['breakEndTime'], 
				$data['elapsedTime'],
				json_encode($data['answers']),
				$examID));

			if ($result)
			{
				// Calculate score and results for exam.
				$results = scoreExam($examID);
				$score = $results['score'];
				$answers = json_encode($results['answers']);

				// Save results to database.
				$sql = "UPDATE " . EXAMS_TABLE . " SET score = ${score}, " . 
                "results = '${answers}' " . "WHERE id=${examID}";
				
				$result = $db->query($sql);

				if ($result)
				{
					// Reply with submission confirmation
					echo "OK";
                    return $examID;
				}
			}
		}
		catch(DbException $e)
		{
            // If could not save exam data.
            $errorStr = "User id: " . $userID . "\n" . $e->getMessage() . "\n" . 
                $e->getTraceAsString();
            error_log($errorStr);

            // E-mail error             
            error_log($errorStr, 1, "alberto@therapyexamprep.com");        
            
            echo "Error saving exam.";
			return null;
		}
	}
    // Unable to decode JSON
    else
    {
        // E-mail error             
        $errorStr = "User id:" . $userID . "\n" . "Unable to decode JSON\n";
        error_log($errorStr, 1, "alberto@therapyexamprep.com");        

        // Log error
        $errorStr .= "Post body below: \n" . $postBody;
        error_log($errorStr);

        echo "Error decoding data.";
        return null;
    }		
}

// Calculate exam score and result for each answer.
// Returns array containing score and list of questions with status of each:
// "Correct", "Incorrect", "Unanswered" 
function scoreExam($examID)
{
	$db = new Db();
    // Get info and associated table id's from Exams table.
    $queryStr = "SELECT questions," . 
    "answerOrder, answers " .   
    "FROM " . EXAMS_TABLE . " WHERE id=" . $examID;

    $rows = $db->select($queryStr);
    $rows = $rows[0]; // Get first row

    // Get list of question id's.
    $questionList = json_decode($rows["questions"]);
    $QuestionsStr = implode(",", $questionList);    

    // Order in which answer choices were presented on exam.
    $answerOrder = json_decode($rows["answerOrder"]);

	// User answers.
	$answers = json_decode($rows["answers"]);

    // Get selected exam questions
    $queryStr = "SELECT `id`,`mc_answer`,`is_experimental` FROM " . 
    "`Questions` WHERE `id` IN ({$QuestionsStr}) ORDER BY " . 
    "FIELD(id, {$QuestionsStr})";

    $rows = $db->select($queryStr);

    // Save data in aray indexed by question id
    $questions = array();
    foreach ($rows as $value)
    {
        $questions[(int)$value['id']] = array(
            "answer" => (int)$value['mc_answer'],
			"isExperimental" => (bool)$value['is_experimental']);
    }

	$score = 0; 

	$answers_ = array();
	foreach ($answers as $answer)
	{
		$id = $answer->id;
		if ($answer->choice == '')
		{
			array_push($answers_, "Unanswered");
		}
		elseif($answerOrder[$answer->choice-1] != $questions[$id]['answer'])
		{
			array_push($answers_, "Incorrect");
		}
		else
		{
			if (!$questions[$id]['isExperimental'])
			{
				$score++;
			}
			array_push($answers_, "Correct");
		}
	}
	return array('score' => $score, 'answers' => $answers_);
}

// Update missed count in database for questions that were answered incorrectly.
function updateMissedQuestions($examID)
{
    $db = new Db();

    try
    {
        $queryStr = "SELECT questions, results FROM " . EXAMS_TABLE . 
            " WHERE id = ${examID}";

        $rows = $db->select($queryStr);
        $rows = $rows[0]; // Get first row

        if ($rows["results"])
        {
            // Get list of question id's.
            $questionList = json_decode($rows["questions"]);
            $resultsList =json_decode($rows["results"]);

            $i = 0;
            foreach($questionList as $question)
            {
                if ($resultsList[$i] == "Incorrect")
                {
                    incrementMissedCount($question);
                }
                $i++;
            }
        }
    }
    catch (DbException $e)
    {
		// If could not update exam questions, log.
		$errorStr = "ExamID " . $examID . "\n" . $e->getMessage() . "\n" . 
			$e->getTraceAsString();
		error_log($errorStr);

		// E-mail error             
        error_log($errorStr, 1, "alberto@therapyexamprep.com");        
    }
}


?>