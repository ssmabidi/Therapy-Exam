<?php
require_once 'defines.php';
require_once 'Db.php';

// Record user exam survey results and start shell script for follow-up.
// Returns true on success.
function recordSurvey($examID, $surveyID)
{
    $db = new Db();
                            
    try
    {
        // Update exam table with survey id
        $sql = "UPDATE " . EXAMS_TABLE . " SET examSurveyID = ${surveyID} WHERE " .
            "id=${examID}";
        
        $result = $db->query($sql);

        // Get feedback responses from survey
        $queryStr = "SELECT realistic, fair FROM " . EXAM_SURVEY_TABLE . " WHERE " . 
            "id=${surveyID}";
        
        $rows = $db->select($queryStr);
        $rows = $rows[0]; // Get first row

        // Set feedback value based on survey response
        $feedback = FEEDBACK_NONE;
        if ($rows["realistic"] == "No" && $rows["fair"] == "No")
        {
            $feedback = FEEDBACK_NOT_REALISTIC_OR_FAIR;
        }
        else if ($rows["realistic"] == "No")
        {
            $feedback = FEEDBACK_NOT_REALISTIC;
        }
        else if ($rows["fair"] == "No")
        {
            $feedback = FEEDBACK_NOT_FAIR;
        }

        executeTagShellScript($examID, $feedback);

        return true;
    }
    catch(DbException $e)
    {
        require_once '/home/afonseca/www/amember/library/Am/Lite.php';
        $username = Am_Lite::getInstance()->getUsername();
        
		// If could not submit survey, log error.
		$errorStr = "User " . $username . "\n" . $e->getMessage() . "\n" . 
			$e->getTraceAsString();
		error_log($errorStr);

		// E-mail error             
		error_log($errorStr, 1, "alberto@therapyexamprep.com");

        return false;
    }
}

// Execute shell script to tag CRM contact for e-mail follow-up.
function executeTagShellScript($examID, $feedback)
{
    require_once '/home/afonseca/www/amember/library/Am/Lite.php';

    $email = Am_Lite::getInstance()->getEmail();

    // Exec script call 
    // tagForSendEmail.php is located in practice exam directory but called 
    // from Survey form directory in client/practiceexam-survey/
    shell_exec ("echo /usr/bin/php -q ../../tagForSendEmail.php ${examID} ${email} ${feedback} | at now");    
}

?>