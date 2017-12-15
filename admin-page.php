<?php
require_once '/home/afonseca/www/products/practice-exam/Db.php';
require_once '/home/afonseca/www/products/practice-exam/vendor/httpful.phar'; // http://phphttpclient.com/
use Httpful\Request;

function getUserIDFromLogin($login)
{
  $db = new Db();
  $userID = null;
  
  $queryStr = "SELECT user_id FROM afonseca_amember.am_user WHERE login = '" . $login . "'";        
  
  try
  {
    $rows = $db->select($queryStr);
	return $rows[0]['user_id'];
    
  }
  catch(DbException $e)
  {
    return null; 
  }
}

function displayInProgressInfo()
{
    global $userID;
    global $inProgressID; // id of exam in progress

    // Get in-progress exam info.        
    $url = "https://therapyexamprep.com/products/amember-webservice.php";
    $url .= "?action=getInProgressExamInfo";
    $url .= "&userID=${userID}";
    $url .= "&examID=${inProgressID}";
    $response = Request::get($url)->send();

    $examNum = $response->body->examNum;
    $attemptNum = $response->body->attemptNum;

    if ($examNum)
    {
        echo "<br/>Exam " . $examNum . ", Attempt " . $attemptNum . 
            " - In Progress<br/>";
        echo "<button type='button' class='reset-button' onclick='onResetButtonClick(${userID}, 
            ${examNum});'>Reset</button>";        
    }
}

?>