<?php
// Called from shell.
// Arguments: Exam ID, email address

require_once 'defines.php';
require_once 'Db.php';

$examID = $argv[1];
$email = $argv[2];
$feedback = $argv[3];

if($examID && $email)
{
    tagForSendEmail($examID, $email, $feedback);
}

// Tag user's CRM contact record to trigger sending of email follow-up.
// Called after exam survey has been submitted.
function tagForSendEmail($examID, $email, $feedback)
{
    $db = new Db();

    try
    {
        $queryStr = "SELECT examNum, attemptNum, score FROM " . EXAMS_TABLE . 
            " WHERE id = ${examID}";

        $rows = $db->select($queryStr);
        $rows = $rows[0]; // Get first row

        $tag = null;

        // If first exam and attempt
        if ($rows["examNum"] == 1 && $rows["attemptNum"] == 1)
        {
            /*// If passing score
            if ($rows["score"] >= PASSING_SCORE)
            {
            }*/
            $tag = "#email_exam1_submitted";
        }
        else if ($rows["examNum"] == 1 && $rows["attemptNum"] == 2)
        {
            // Passed
            if ($rows["score"] >= PASSING_SCORE)
            {
                $tag = "#email_exam1_2_submitted_pass";
            }
            // Failed
            else
            {
                $tag = "#email_exam1_2_submitted_fail";
            }
        }

        if ($tag)
        {            
            // Init X2CRM API            
            require_once '/home/afonseca/www/custom/X2CRM_API.php';
            $URL = "https://therapyexamprep.com/x2crm/index.php/api2";
            $username = "admin";
            $APIKey = "RqUF8CxjFVMVRbTNbes3bh5GmlDqdz0o";
            $crm = new X2CRM_API($URL, $username, $APIKey);	

            $contact = $crm->findContactFromEmail($email);
            if ($contact)
            {
                $crm->addTagToContact($contact->id, $tag);

                // If first exam and attempt
                if ($rows["examNum"] == 1 && $rows["attemptNum"] == 1)
                {
                    // Tag record if we want to respond to feedback type
                    if ($feedback == FEEDBACK_NOT_REALISTIC)
                    {
                        $crm->addTagToContact($contact->id, "#email_feedback_not_realistic");                    
                    }
                    else if ($feedback == FEEDBACK_NOT_FAIR)
                    {
                        $crm->addTagToContact($contact->id, "#email_feedback_not_fair");
                    }
                    else if ($feedback == FEEDBACK_NOT_REALISTIC_OR_FAIR)
                    {
                        $crm->addTagToContact($contact->id, "#email_feedback_neither");
                    }
                }
            }
        }
    }
    catch (DbException $e)
    {
		// If could not set tag, log.
		$errorStr = "User " . $email . "\n" . $e->getMessage() . "\n" . 
			$e->getTraceAsString();
		error_log($errorStr);

		// E-mail error             
        error_log($errorStr, 1, "alberto@therapyexamprep.com");        
    }
}

?>