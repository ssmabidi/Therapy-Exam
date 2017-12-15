<?php
require_once 'defines.php';

define('PRACTICE_EXAM_PROD_ID', 98);
define('EXAM_VERSION', 2);

require_once 'Db.php';

header("Content-Type: text/plain"); 

switch($_SERVER['REQUEST_METHOD'])
{
	case 'GET':		
		switch ($_GET["action"])
		{
			case 'new':
			newExam($_GET["examNum"], $_GET["examAttempt"], $_GET["timeScale"]);
			break;
			
			case 'getQuestions':
			getQuestions($_GET["id"]);
			break;	

			case 'reset':
			resetExam($_GET["userID"], $_GET["examNum"]);
			break;

			case 'delete':
			deleteExam($_GET["userID"], $_GET["examNum"], $_GET["examID"]);
            break;            
		}
		break;
	case 'POST': 
		$examID = submitExam();

        if ($examID)
        {
            updateMissedQuestions($examID);
        }
		break;
}

// Used to 'reset' a user's in-progress exam.
// Remove attempt number from user's exam data and restore attempt count.
// Zero out exam attempt in database.
function resetExam($userID, $examNum)
{
	require_once '/home/afonseca/www/amember/bootstrap.php';

    // Get aMember user array
    $user = Am_Di::getInstance()->userTable->findBy(array('user_id'=>$userID));
    
    if (!empty($user))
    {
        $user = $user[0]; // first element

        // Get exams record from user data
        $exams = $user->data()->getBlob("exams");
        
        if ($exams)
        {
            $exams = json_decode($exams);
			$exam = &$exams[$examNum-1];

			// Remove last exam attempt ID
			$examID = array_pop($exam->attemptIDs);	
			
			// Restore attempt count
			$exam->attemptsRemaining++;			

			// Update exam attempts in user account
			$user->data()->setBlob("exams", json_encode($exams));
			$user->data()->update();    		

            if ($examID)
            {
                // Zero out exam and attempt number in exam database
                $db = new Db();
                                        
                try
                {
                    // Save to database
                    $result = $db->queryPrepared("UPDATE " . EXAMS_TABLE . " SET " . 
                    "examNum = ?, attemptNum = ? WHERE id = ?",
                    array(0, 0, $examID));
                }
                catch(DbException $e)
                {
                    // If could not reset.
                    $errorStr = "Could not reset exam for user ID: " . $userID . 
                    " examNum: " . $examNum . "\n" . $e->getMessage() . "\n" . 
                        $e->getTraceAsString();
                    error_log($errorStr);

                    // E-mail error             
                    error_log($errorStr, 1, "alberto@therapyexamprep.com");                        
                }            
            }
		}
	}	
}

// Used to 'delete' a user's previously saved exam attempt.
// Remove attempt number from user's exam data and restore attempt count.
// Zero out exam attempt in database.
function deleteExam($userID, $examNum, $examID)
{
	require_once '/home/afonseca/www/amember/bootstrap.php';

    // Get aMember user array
    $user = Am_Di::getInstance()->userTable->findBy(array('user_id'=>$userID));
    
    if (!empty($user))
    {
        $user = $user[0]; // first element

        // Get exams record from user data
        $exams = $user->data()->getBlob("exams");
        
        if ($exams)
        {
            $exams = json_decode($exams);
			$exam = &$exams[$examNum-1];

            // Remove attempt ID from exam record
            $index = array_search($examID, $exam->attemptIDs);
            if ($index !== false)
            {
                unset($exam->attemptIDs[$index]);
                $exam->attemptIDs = array_values($exam->attemptIDs);

                // Restore attempt count
                $exam->attemptsRemaining++;			

                // Update exam attempts in user account
                $user->data()->setBlob("exams", json_encode($exams));
                $user->data()->update();    		                
            }			

            if ($examID)
            {
                // Zero out exam and attempt number in exam database
                $db = new Db();
                                        
                try
                {
                    // Save to database
                    $result = $db->queryPrepared("UPDATE " . EXAMS_TABLE . " SET " . 
                    "examNum = ?, attemptNum = ? WHERE id = ?",
                    array(0, 0, $examID));
                }
                catch(DbException $e)
                {
                    // If could not delete.
                    $errorStr = "Could not delete exam for user ID: " . $userID . 
                    " examNum: " . $examNum . "\n" . $e->getMessage() . "\n" . 
                        $e->getTraceAsString();
                    error_log($errorStr);

                    // E-mail error             
                    error_log($errorStr, 1, "alberto@therapyexamprep.com");                        
                }            
            }
		}
	}	
}

// Validate if the user account allows for this exam attempt
function canUserTakeExam($examNum, $examAttempt)
{
    global $isStaging;

    // Bypass check in staging environment
    if ($isStaging)
    {
        return true;
    }

	$exams = getUserExamsRecord();

	if ($exams)
	{
		// If not valid exam number
		if (count($exams) < $examNum)
		{
            $user = Am_Di::getInstance()->auth->getUser();
            
            // Log error
            $errorStr = "canUserTakeExam() returning false for user " . 
                $user->login . ". ExamNum passed is {$examNum} but only " . 
                count($exams) . " available for user." . "\n";
            error_log($errorStr);

            // E-mail error             
            error_log($errorStr, 1, "alberto@therapyexamprep.com");
            
			return false;
		}

		// Get single exam record
		$exam = $exams[$examNum - 1];
		
		if ($exam->attemptsRemaining <= 0)
		{
            $user = Am_Di::getInstance()->auth->getUser();
            
            // Log error
            $errorStr = "canUserTakeExam() returning false for user " . 
                $user->login . ". " . $exam->attemptsRemaining . 
                " attempts remaining for user." . "\n";
            error_log($errorStr);

            // E-mail error             
            error_log($errorStr, 1, "alberto@therapyexamprep.com");
            
			return false;
		}

		// If attempt in progress
		if (count($exam->attemptIDs) >= $examAttempt)
		{
            // Log error
            $errorStr = "canUserTakeExam() returning false for user " . 
                $user->login . ". Exam already in progress.\n";
            error_log($errorStr);

            // E-mail error             
            error_log($errorStr, 1, "alberto@therapyexamprep.com");
            
			return false;
		}
	}		
	else
	{
        $user = Am_Di::getInstance()->auth->getUser();
        
		// Log error
		$errorStr = "canUserTakeExam() returning false for user " . 
            $user->login . ". Unable to get user's exam record.\n";
		error_log($errorStr);

		// E-mail error             
		error_log($errorStr, 1, "alberto@therapyexamprep.com");
        
		return false;
	}

	return true;
}

// Get exams record from user account.
function getUserExamsRecord()
{
	$user = Am_Di::getInstance()->auth->getUser();

	// Get exams record from user data
	$exams = $user->data()->getBlob("exams");

	if ($exams)
	{
		$exams = json_decode($exams);
		return $exams;
	}
	else
	{
		return null;
	}
}

// Add ID of exam attempt to user record and update remaining attempts. 
function addAttemptToUserRecord($examNum, $examID)
{
    global $isStaging;
    
    // Bypass for staging environment.
    if ($isStaging)
    {
        return;
    }

	$user = Am_Di::getInstance()->auth->getUser();

	$exams = getUserExamsRecord();

	if ($exams)
	{
		// Get single exam record
		$exam = &$exams[$examNum - 1];

		// Add exam id to user's attempts list
		array_push($exam->attemptIDs, $examID);
		
		// Consume attempt
		$exam->attemptsRemaining--;

		// Update exam attempts in user account
		$user->data()->setBlob("exams", json_encode($exams));
		$user->data()->update();    	
	}
}

// Set end date for user subscription when they take first exam.
// Only Practice Exam lifetime subscriptions are affected.
function startExamSubscription()
{	
	$user = Am_Di::getInstance()->auth->getUser();

	$access = Am_Di::getInstance()->accessTable->findFirstBy(array(
						'user_id' => $user->pk(),
						'product_id' => PRACTICE_EXAM_PROD_ID
				), 'begin_date DESC');

	if ($access && $access->isLifetime())
	{
		$access->updateQuick('expire_date', sqlDate('+60 days'));
		$user->checkSubscriptions();
	}
}

function newExam($examNum, $examAttempt, $timeScale)
{	
	require_once '/home/afonseca/www/amember/bootstrap.php';

	if (empty($examNum) || empty($examAttempt))
	{
		// Get aMember logged in user
	    $user = am_Di::getInstance()->auth->getUser();

		// Log error
		$errorStr = "User " . $user->login . " attempted to take exam with missing arguments. examNum: ${examNum} examAttempt: ${examAttempt} timeScale: ${timeScale}\n";
		error_log($errorStr);

		// E-mail error             
		error_log($errorStr, 1, "alberto@therapyexamprep.com");
        
		return 0;
	}

	require_once '/home/afonseca/www/amember/bootstrap.php';

	$userID = Am_Di::getInstance()->auth->getUserId();

	// Handle user not signed in.
	if (!$userID)
	{
		// Log error
		$errorStr = "User attempted to take exam but is not signed in. examNum: ${examNum} examAttempt: ${examAttempt} timeScale: ${timeScale}\n";
		error_log($errorStr);

		// E-mail error             
		error_log($errorStr, 1, "alberto@therapyexamprep.com");
        
		// Return id 0 (error condition)
		return 0;
	}
	
	// Check user record
	if (!canUserTakeExam($examNum, $examAttempt))
	{
		// Get aMember logged in user
	    $user = am_Di::getInstance()->auth->getUser();
        
		// Log error
		$errorStr = "User " . $user->login . " attempted to take exam but is not eligible. examNum: ${examNum} examAttempt: ${examAttempt} timeScale: ${timeScale}\n";
		error_log($errorStr);

		// E-mail error             
		error_log($errorStr, 1, "alberto@therapyexamprep.com");
        
		// Return id 0 (error condition)
		return 0;
	}

	$db = new Db();

	// Generate exam questions
	$questions = NULL;

	try
	{
		// If first time, generate new questions.
		if ($examAttempt == 1)
		{
			$questions = generateExamQuestions($db, $examNum);
		}
		// Shuffle same exam set.
		else
		{
			$exams = getUserExamsRecord();
			$exam = $exams[$examNum - 1];
            $questions = generateExamQuestions($db, $examnNum,
                $exam->attemptIDs[0]);
		}
	}
	catch (DbException $e)
	{
		// Get aMember logged in user
	    $user = am_Di::getInstance()->auth->getUser();

		// If could not generate exam questions, log.
		$errorStr = "User " . $user->login . "\n" . $e->getMessage() . "\n" . 
			$e->getTraceAsString();
		error_log($errorStr);

		// E-mail error             
		error_log($errorStr, 1, "alberto@therapyexamprep.com");
		
		// Return id 0 (error condition)
		return 0;
	}

	$questionsString = json_encode($questions);

	// Randomize exam answer order
	$answerOrder = array (1, 2, 3, 4);
	shuffle($answerOrder);
	$orderString = json_encode($answerOrder);

	try
	{
		// Save exam entry in db and return id
		$db->queryPrepared("INSERT INTO " . EXAMS_TABLE .  
		" (version, userID, examNum, attemptNum, timeScale, questions, answerOrder) " . 
		"VALUES (?, ?, ?, ?, ?, ?, ?)",
		array(
            EXAM_VERSION,
			$userID, 
			$examNum, 
			$examAttempt,
			$timeScale, 
			$questionsString,  
			$orderString));

		// Update user record
		addAttemptToUserRecord($examNum, $db->lastInsertId());

		echo $db->lastInsertId();
	}
	catch (DbException $e)
	{
		// Get aMember logged in user
	    $user = am_Di::getInstance()->auth->getUser();
        
		// Log error
		$errorStr = "User " . $user->login . " attempted to take exam but unable to save entry to db. examNum: ${examNum} examAttempt: ${examAttempt} timeScale: ${timeScale}\n";
		error_log($errorStr);

		// E-mail error             
		error_log($errorStr, 1, "alberto@therapyexamprep.com");
        
		// Return id 0 (error condition)
		return 0;
	}

	// Start subscription on first attempt.
	if ($examAttempt == 1)
	{
		startExamSubscription();	
	}
}

// Get questions from db for each section based on:
// Practice Exam Breakdown Format
// https://docs.google.com/spreadsheets/d/13BqE2ghbT6rUtQ1-w_yPiVUNO2skueo7iIvee0830sY/edit?usp=sharing
// $attemptID - id of first exam attempt (optional)
// If $attemptID is passed in, we use the list of questions from this attempt
// in our queries so the same question set is returned shuffled.
function generateExamQuestions($db, $examNum, $attemptID = null)
{
    $firstAttemptQuestions = null; // list of questions from first exam attempt

    // If this isn't first attempt
    if ($attemptID)
    {
        // Get list of questions for passed in exam id
        $queryStr = "SELECT questions FROM " . EXAMS_TABLE . 
            " WHERE id=${attemptID}"; 
        $rows = $db->select($queryStr);
        $rows = $rows[0];
        $questionList = json_decode($rows["questions"]);
        $firstAttemptQuestions = implode(",", $questionList);        
    }
    // Questions Table Structure
    // [Content section][System][Number required][List of question id's]
    $questionsTable = array(
        "Evaluation" => array("Musculoskeletal"
                                => array("required" => 20, "ids" => array()),
                            "Neuromuscular"
                                => array("required" => 16, "ids" => array()),
                            "CardioPulmonary"
                                => array("required" => 9, "ids" => array()),
                            "Integumentary"
                                => array("required" => 4, "ids" => array()),
                            "Metabolic and Endocrine"
                                => array("required" => 4, "ids" => array()),
                            "GI"
                                => array("required" => 3, "ids" => array()),
                            "GU"
                                => array("required" => 3, "ids" => array()),
                            "Lymphatic"
                                => array("required" => 3, "ids" => array()),
                            "System Interactions"
                                => array("required" => 10, "ids" => array())),
        "Examination" => array("Musculoskeletal"
                                => array("required" => 19, "ids" => array()),
                            "Neuromuscular"
                                => array("required" => 15, "ids" => array()),
                            "CardioPulmonary"
                                => array("required" => 8, "ids" => array()),
                            "Integumentary"
                                => array("required" => 3, "ids" => array()),
                            "Metabolic and Endocrine"
                                => array("required" => 0, "ids" => array()),
                            "GI"
                                => array("required" => 1, "ids" => array()),
                            "GU"
                                => array("required" => 1, "ids" => array()),
                            "Lymphatic"
                                => array("required" => 1, "ids" => array()),
                            "System Interactions"
                                => array("required" => 0, "ids" => array())),
        "Intervention" => array("Musculoskeletal"
                                => array("required" => 18, "ids" => array()),
                            "Neuromuscular"
                                => array("required" => 15, "ids" => array()),
                            "CardioPulmonary"
                                => array("required" => 9, "ids" => array()),
                            "Integumentary"
                                => array("required" => 4, "ids" => array()),
                            "Metabolic and Endocrine"
                                => array("required" => 3, "ids" => array()),
                            "GI"
                                => array("required" => 2, "ids" => array()),
                            "GU"
                                => array("required" => 2, "ids" => array()),
                            "Lymphatic"
                                => array("required" => 2, "ids" => array()),
                            "System Interactions"
                                => array("required" => 0, "ids" => array())),
            
        "Non System Domains" => array("Assistive Devices"
                                => array("required" => 5, "ids" => array()),
                            "Therapeutic Modalities"
                                => array("required" => 7, "ids" => array()),
                            "Safety & Protection"
                                => array("required" => 5, "ids" => array()),
                            "Professional Responsibilities"
                                => array("required" => 4, "ids" => array()),
                            "Research & EBP"
                                => array("required" => 4, "ids" => array()))
    );

    $excludeStr = ""; // string of questions to exclude

    // If not first exam, build exclusion list from any past exam questions
    if ($examNum > 1)
    {        
        // Get aMember logged in user id
    	$userID = Am_Di::getInstance()->auth->getUserId();

        $queryStr = "SELECT questions FROM " . EXAMS_TABLE . 
            " WHERE userID = ${userID} AND attemptNum = 1";
        $rows = $db->select($queryStr);

        $questionList = array();

        foreach ($rows as $row)
        {
            $questions = json_decode($row["questions"]);
            $questionList = array_unique(array_merge($questionList, $questions));
        }

        if (!empty($questionList))
        {
            $excludeStr = implode("','", $questionList);

            // E-mail debug log
            /*$user = am_Di::getInstance()->auth->getUser();
            $errorStr = "User " . $user->login . " examNum: ${examNum} examAttempt: ${examAttempt} excluded questions: ${excludeStr}\n";
            error_log($errorStr, 1, "alberto@therapyexamprep.com");*/
        }
    }

	// Get questions from db for each system in each content section
    foreach ($questionsTable as $contentSection => $systems)
    {
        foreach ($systems as $system => $data)
        {
            $numRequired = $data["required"];

            $queryStr = "SELECT DISTINCT id FROM " . QUESTIONS_TABLE . " WHERE " . 
            "content_section = '${contentSection}' AND system = '${system}' " .  
            "AND is_active = TRUE AND is_experimental IS NULL AND " . 
            "id NOT IN ('{$excludeStr}') ORDER BY RAND() LIMIT ${numRequired}";

            // Select questions from first attempt list
            if ($firstAttemptQuestions)
            {
                $queryStr = "SELECT id FROM " . QUESTIONS_TABLE . " WHERE " . 
                "content_section = '${contentSection}' AND system = '${system}' " .  
                "AND id IN ({$firstAttemptQuestions}) ORDER BY RAND() " . 
                "LIMIT ${numRequired}";
            }

            // Slightly different query for Non System Domains
            if ($contentSection === "Non System Domains")
            {
                $queryStr = "SELECT DISTINCT id FROM " . QUESTIONS_TABLE . 
                " WHERE content_section = '${contentSection}' AND " . 
                "content_subsection = '${system}' " .  
                "AND is_active = TRUE AND is_experimental IS NULL AND " . 
                "id NOT IN ('{$excludeStr}') ORDER BY RAND() " . 
                "LIMIT ${numRequired}";

                // Select questions from first attempt list
                if ($firstAttemptQuestions)
                {
                    $queryStr = "SELECT id FROM " . QUESTIONS_TABLE . 
                    " WHERE content_section = '${contentSection}' AND " . 
                    "content_subsection = '${system}' " .  
                    "AND id IN ({$firstAttemptQuestions}) ORDER BY RAND() " . 
                    "LIMIT ${numRequired}";        
                }
            }
            
            $rows = $db->select($queryStr);

            // Abort if didn't get required number of questions
            if (count($rows) != $numRequired)
            {
                $msg = "generateExamQuestions() ";
                $msg .= $contentSection . ":" . $system . " ";
                $msg .= "requested ${numRequired}, got " . count($rows);
                $msg .= "\nQuery: " . $queryStr;
                throw new DbException($msg);
                return null;
            }

            // Save question ID's in questions table
            foreach ($rows as $value)
            {
                $questionsTable[$contentSection][$system]["ids"][] = (int)$value['id'];
            }            
        }
    }

	// Experimental
    $queryStr = "SELECT DISTINCT id FROM " . QUESTIONS_TABLE . 
        " WHERE is_active=TRUE AND is_experimental=TRUE AND " . 
        "id NOT IN ('{$excludeStr}') ORDER BY RAND() LIMIT 50";
        
    // Select questions from first attempt list        
    if ($firstAttemptQuestions)
    {
        $queryStr = "SELECT id FROM " . QUESTIONS_TABLE . " WHERE " .  
        "is_experimental=TRUE AND id IN ({$firstAttemptQuestions}) " . 
        "ORDER BY RAND() LIMIT 50";
    }

	$rows = $db->select($queryStr);

	// Abort if not required number of questions
	if (count($rows) != 50)
	{
		$msg = "generateExamQuestions() ";
		$msg .= "Experimental ";
		$msg .= "requested 50, got " . count($rows);
		$msg .= "\nQuery: " . $queryStr;
		throw new DbException($msg);
		return null;
	}

	// Save question ID's
	$experimental = array();
	foreach ($rows as $value)
	{
		array_push($experimental, (int)$value['id']);
	}	

    // Set up system variable references to original table arrays

    // Evaluation
    $evalMusc = &$questionsTable["Evaluation"]["Musculoskeletal"]["ids"];
    $evalNeuro = &$questionsTable["Evaluation"]["Neuromuscular"]["ids"];
    $evalCardio = &$questionsTable["Evaluation"]["CardioPulmonary"]["ids"];
    $evalInteg = &$questionsTable["Evaluation"]["Integumentary"]["ids"];
    $evalMetabolic = &$questionsTable["Evaluation"]["Metabolic and Endocrine"]["ids"];
    $evalGI = &$questionsTable["Evaluation"]["GI"]["ids"];
    $evalGU = &$questionsTable["Evaluation"]["GU"]["ids"];
    $evalLymphatic = &$questionsTable["Evaluation"]["Lymphatic"]["ids"];
    $evalSystemInter = &$questionsTable["Evaluation"]["System Interactions"]["ids"];

    // Examination
    $examMusc = &$questionsTable["Examination"]["Musculoskeletal"]["ids"];
    $examNeuro = &$questionsTable["Examination"]["Neuromuscular"]["ids"];
    $examCardio = &$questionsTable["Examination"]["CardioPulmonary"]["ids"];
    $examInteg = &$questionsTable["Examination"]["Integumentary"]["ids"];
    $examMetabolic = &$questionsTable["Examination"]["Metabolic and Endocrine"]["ids"];
    $examGI = &$questionsTable["Examination"]["GI"]["ids"];
    $examGU = &$questionsTable["Examination"]["GU"]["ids"];
    $examLymphatic = &$questionsTable["Examination"]["Lymphatic"]["ids"];
    $examSystemInter = &$questionsTable["Examination"]["System Interactions"]["ids"];

    // Intervention
    $interMusc = &$questionsTable["Intervention"]["Musculoskeletal"]["ids"];
    $interNeuro = &$questionsTable["Intervention"]["Neuromuscular"]["ids"];
    $interCardio = &$questionsTable["Intervention"]["CardioPulmonary"]["ids"];
    $interInteg = &$questionsTable["Intervention"]["Integumentary"]["ids"];
    $interMetabolic = &$questionsTable["Intervention"]["Metabolic and Endocrine"]["ids"];
    $interGI = &$questionsTable["Intervention"]["GI"]["ids"];
    $interGU = &$questionsTable["Intervention"]["GU"]["ids"];
    $interLymphatic = &$questionsTable["Intervention"]["Lymphatic"]["ids"];
    $interSystemInter = &$questionsTable["Intervention"]["System Interactions"]["ids"];

    // Non System Domains
    $nsdAssistive = &$questionsTable["Non System Domains"]["Assistive Devices"]["ids"];
    $nsdTherapeutic = &$questionsTable["Non System Domains"]["Therapeutic Modalities"]["ids"];
    $nsdSafety = &$questionsTable["Non System Domains"]["Safety & Protection"]["ids"];
    $nsdProfessional = &$questionsTable["Non System Domains"]["Professional Responsibilities"]["ids"];
    $nsdResearch = &$questionsTable["Non System Domains"]["Research & EBP"]["ids"];
    
    // Number of required questions per exam set, defined in excel sheet table
    $numQuestionsPerSet = array(
        // Each content section has questions per system as follows:
        // Musculoskeletal, Neuromuscular, CardioPulmonary, Integumentary,
        // Metabolic/ Endocrine, GI, GU, Lymphatic, System Interaction
        // The exception is Non System Domains which has the following:
        // Assistive, Therapeutic, Safey, Professional, Research
        array( // Set 1
        array(4, 3, 2, 1, 1, 1, 1, 1, 2), // Evaluation
        array(4, 3, 1, 0, 0, 0, 0, 0, 0), // Examination
        array(4, 3, 2, 1, 1, 0, 0, 0, 0), // Intervention
        array(1, 1, 1, 1, 1)),            // Non System Domains
                
        array( // Set 2
        array(4, 3, 2, 0, 1, 1, 1, 1, 2), // Evaluation
        array(4, 3, 2, 1, 0, 0, 1, 0, 0), // Examination
        array(3, 4, 1, 1, 0, 0, 0, 0, 0), // Intervention
        array(1, 2, 1, 0, 1)),            // Non System Domains
                
        array( // Set 3
        array(4, 3, 2, 1, 0, 0, 0, 1, 2), // Evaluation
        array(3, 3, 2, 1, 0, 1, 0, 0, 0), // Examination
        array(3, 3, 2, 1, 1, 1, 1, 0, 0), // Intervention
        array(1, 1, 1, 1, 1)),            // Non System Domains

        array( // Set 4
        array(4, 4, 1, 1, 1, 1, 1, 0, 2), // Evaluation
        array(4, 3, 2, 0, 0, 0, 0, 1, 0), // Examination
        array(4, 2, 2, 1, 0, 0, 0, 1, 0), // Intervention
        array(1, 2, 1, 1, 0)),            // Non System Domains
        
        array( // Set 5
        array(4, 3, 2, 1, 1, 0, 0, 0, 2), // Evaluation
        array(4, 3, 1, 1, 0, 0, 0, 0, 0), // Examination
        array(4, 3, 2, 0, 1, 1, 1, 1, 0), // Intervention
        array(1, 1, 1, 1, 1)));           // Non System Domains        


    // Create temporary sets with number of questions for each
    // content section, system, and reference to questions table
    // so we can populate final exam sections in next step.
    $sets = array();
    $i = 0;

    foreach ($numQuestionsPerSet as $numInSet)
    {
        $sets[$i] = array(// all sections holder
        
        // Evaluation
        array(
                array("system" => &$evalMusc,
                    "count" => $numInSet[0][0]),
                array("system" => &$evalNeuro,
                    "count" => $numInSet[0][1]),
                array("system" => &$evalCardio,
                    "count" => $numInSet[0][2]),
                array("system" => &$evalInteg,
                    "count" => $numInSet[0][3]),
                array("system" => &$evalMetabolic,
                    "count" => $numInSet[0][4]),
                array("system" => &$evalGI,
                    "count" => $numInSet[0][5]),
                array("system" => &$evalGU,
                    "count" => $numInSet[0][6]),
                array("system" => &$evalLymphatic,
                    "count" => $numInSet[0][7]),
                array("system" => &$evalSystemInter,
                    "count" => $numInSet[0][8])),
                                    
        // Examination
        array(
                array("system" => &$examMusc,
                    "count" => $numInSet[1][0]),
                array("system" => &$examNeuro,
                    "count" => $numInSet[1][1]),
                array("system" => &$examCardio,
                    "count" => $numInSet[1][2]),
                array("system" => &$examInteg,
                    "count" => $numInSet[1][3]),
                array("system" => &$examMetabolic,
                    "count" => $numInSet[1][4]),
                array("system" => &$examGI,
                    "count" => $numInSet[1][5]),
                array("system" => &$examGU,
                    "count" => $numInSet[1][6]),
                array("system" => &$examLymphatic,
                    "count" => $numInSet[1][7]),
                array("system" => &$examSystemInter,
                    "count" => $numInSet[1][8])),

        // Intervention
        array(
                array("system" => &$interMusc,
                    "count" => $numInSet[2][0]),
                array("system" => &$interNeuro,
                    "count" => $numInSet[2][1]),
                array("system" => &$interCardio,
                    "count" => $numInSet[2][2]),
                array("system" => &$interInteg,
                    "count" => $numInSet[2][3]),
                array("system" => &$interMetabolic,
                    "count" => $numInSet[2][4]),
                array("system" => &$interGI,
                    "count" => $numInSet[2][5]),
                array("system" => &$interGU,
                    "count" => $numInSet[2][6]),
                array("system" => &$interLymphatic,
                    "count" => $numInSet[2][7]),
                array("system" => &$interSystemInter,
                    "count" => $numInSet[2][8])),

        // Non System Domains
        array(
                array("system" => &$nsdAssistive,
                    "count" => $numInSet[3][0]),
                array("system" => &$nsdTherapeutic,
                    "count" => $numInSet[3][1]),
                array("system" => &$nsdSafety,
                    "count" => $numInSet[3][2]),
                array("system" => &$nsdProfessional,
                    "count" => $numInSet[3][3]),
                array("system" => &$nsdResearch,
                    "count" => $numInSet[3][4])));

        $i++; // next set
    }

	// Question ids for each exam section
	$sections = array(array(), array(), array(), array(), array());
    
	// Loop through each set
	foreach ($sets as $numInSet => $set)
	{		
		foreach ($set as $contentSectionNum => $contentSection)
		{
			foreach ($contentSection as $entryNum => $entry)
			{
				for ($j = 0; $j < $entry["count"]; $j++)
				{
                    // remove question id from questions table
                    $id = array_pop($sets[$numInSet][$contentSectionNum][$entryNum]["system"]);

                    // and add to exam section
                    $sections[$numInSet][] = $id;
				}
			}
		}

		// Add experimental to end of set.
		for ($i = 0; $i < 10; $i++)
		{
            $id = array_pop($experimental);
            $sections[$numInSet][] = $id;
		}
	}

	// Randomize questions in each section and add to master list
	$questions = array();	
	foreach ($sections as $section)
	{
		shuffle($section);
		$questions = array_merge($questions, $section);
    }
        
	return $questions;
}

function getQuestions($id)
{	
	require_once '/home/afonseca/www/amember/bootstrap.php';

	$userID = Am_Di::getInstance()->auth->getUserId();

	// Handle user not signed in.
	if (!$userID)
	{
		// Return (error condition)
		return;
	}
    
	$db = new Db();

	$rows = array();
	try
	{
		// Get list of question id's and answer order from exam entry
		$queryStr = "SELECT questions, answerOrder FROM " . EXAMS_TABLE . 
            " WHERE id = {$id} AND userID = {$userID}";
		$rows = $db->select($queryStr);
		$rows = $rows[0]; // Get first row
	}
	catch (DbException $e)
	{
		return;
	}

	$questionList = json_decode($rows["questions"]);
	$QuestionsStr = implode(",", $questionList);
	
	$order = json_decode($rows["answerOrder"]);

	// Get index to map answers in query.
	$i1 = array_search(1, $order) + 1;
	$i2 = array_search(2, $order) + 1;
	$i3 = array_search(3, $order) + 1;
	$i4 = array_search(4, $order) + 1;

	try
	{
		// Get questions from database
		$queryStr = "SELECT id, question, image_url, answer1 AS answer{$i1}, " . 
		"answer2 AS answer{$i2}, answer3 AS answer{$i3}, " . 
        "answer4 AS answer{$i4} FROM " . QUESTIONS_TABLE . 
        " WHERE id IN ({$QuestionsStr})" .
		" ORDER BY FIELD(id, " . $QuestionsStr . ")";

		$rows = $db->select($queryStr);

		//print_r($rows);
		foreach($rows as $k => $row){
			if(isset($row['image_url']) && $row['image_url'])  
            {
                $rows[$k]['question'] = '<img src="'.$row['image_url'].'" alt="Picture">'.$rows[$k]['question'];
    			//$rows[$k]['question'] = '<img src="https://therapyexamprep.com/wp-content/uploads/2017/01/orthkit-678.png">'.$rows[$k]['question'];
            }
		}
		// Return base64 JSON encoded questions string
		echo base64_encode(json_encode($rows));		
	}
	catch (DbException $e)
	{
		return;
	}
}

// Save exam POST data to Exams table with id.
// Replies with "OK" if success, other string if error.
function submitExam()
{
	require_once '/home/afonseca/www/amember/library/Am/Lite.php';
	$user = Am_Lite::getInstance()->getUser();

	// Handle user not signed in.
	if (!$user)
	{
		// If user not signed in.
		$errorStr = "User not signed in, unable to submit exam.\n";

		// E-mail error             
        error_log($errorStr, 1, "alberto@therapyexamprep.com");        

        $errorStr .= "\nPost Body Below:\n" . file_get_contents('php://input');            
		error_log($errorStr);
        
        echo "User not signed in.";
		return null;
	}
	
	// Fetch the raw POST body containing the message
	$postBody = file_get_contents('php://input');

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
                // Exam was already submitted so db entry not updated.
                else
                {
                    // Reply with submission confirmation
                    echo "OK";
                    return $examID;
                }                
			}
            // Exam was already submitted so db entry not updated.
            else
            {
                // Reply with submission confirmation
                echo "OK";
                return $examID;
            }
		}
		catch(DbException $e)
		{
            // If could not save exam data.
            $errorStr = "User " . $user['login'] . "\n" . $e->getMessage() . "\n" . 
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
        $errorStr = "User " . $user['login'] . "\n" . "Unable to decode JSON\n";
        error_log($errorStr, 1, "alberto@therapyexamprep.com");        

        // Log error
        $errorStr .= "Post body below: \n" . $postBody;
        error_log($errorStr);

        echo "Error decoding data.";
        return null;
    }		
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
    	require_once '/home/afonseca/www/amember/library/Am/Lite.php';
        
		// If could not update exam questions, log.
		$errorStr = "User " . $user['login'] . "\n" . $e->getMessage() . "\n" . 
			$e->getTraceAsString();
		error_log($errorStr);

		// E-mail error             
        error_log($errorStr, 1, "alberto@therapyexamprep.com");        
    }
}

// Update missed count for question in db.
function incrementMissedCount($questionID)
{
    $db = new Db();

    $sql = "Update Questions SET missed_count = missed_count + 1 WHERE " . 
        "id = ${questionID}";
    $result = $db->query($sql);
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
        QUESTIONS_TABLE . " WHERE `id` IN ({$QuestionsStr}) ORDER BY " . 
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

?>
