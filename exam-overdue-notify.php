<?php
// Called from system Cron
require_once '/home/afonseca/www/products/practice-exam/Db.php';
require_once '/home/afonseca/www/amember/bootstrap.php';

$exams = queryForOverdueExams();

if (!empty($exams))
{    
    // Init X2CRM API            
    $URL = "https://therapyexamprep.com/x2crm/index.php/api2";
    $username = "admin";
    $APIKey = "RqUF8CxjFVMVRbTNbes3bh5GmlDqdz0o";
    $crm = new X2CRM_API($URL, $username, $APIKey);	

    foreach ($exams as $id => $userID)
    {
        tagUserRecordInCRM($crm, $userID);
    }
}

/* Query Exams database for exams that became overdue by 2 hours
   within the past 24 hours.
   Returns: Array of examID => userID
*/
function queryForOverdueExams()
{
    $db = new Db();
    $exams = array();

    try
    {
        $queryStr = "SELECT id, userID from Exams WHERE examNum > 0 AND " . 
            "examSurveyID IS NULL AND DATE_ADD(timestamp, interval " . 
            "(5 *  60 * timeScale + 120) MINUTE) > DATE_SUB(NOW(), " . 
            "INTERVAL 24 HOUR) AND NOW() > DATE_ADD(timestamp, " . 
            "interval (5 *  60 * timeScale + 120) MINUTE)";

        $rows = $db->select($queryStr);
        
        foreach ($rows as $row)
        {
            $key = $row['id'];
            $exams[$key] = $row['userID'];
        }
    }
    catch (DbException $e)
    {
		$errorStr = "queryForOverdueExams():\n" . $e->getMessage() . "\n" . 
			$e->getTraceAsString();
		error_log($errorStr);

		// E-mail error             
        error_log($errorStr, 1, "alberto@therapyexamprep.com");        
    }
    
    return $exams;
}

function tagUserRecordInCRM($crm, $userID)
{
    // Get aMember user array
    $user = Am_Di::getInstance()->userTable->findBy(array('user_id'=>$userID));
    
    if (!empty($user))
    {
        $user = $user[0]; // first element
        $email = $user->email;

        $contact = $crm->findContactFromEmail($email);
        if ($contact)
        {
            $crm->addTagToContact($contact->id, "#notify_exam_overdue");
        }
    }
}

?>