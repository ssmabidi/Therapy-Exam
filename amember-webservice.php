<?php
require_once '/home/afonseca/www/amember/bootstrap.php';

// session_start();
// session var check

define('COURSES_CATEGORY_ID', 1);
define('PRACTICE_EXAM_PROD_ID', 98);


switch($_SERVER['REQUEST_METHOD'])
{
	case 'GET':
    {
		switch ($_GET["action"])
		{
			case 'hasActiveCourseProduct':
            hasActiveCourseProduct($_GET["userID"]);
			break;

            case 'getInProgressExamInfo':
            getInProgressExamInfo($_GET["userID"], $_GET["examID"]);
            break;
		}
        break;
    }
}

// Return "true" response if user has active course product.
function hasActiveCourseProduct($userID)
{
    // Get aMember user array
    $user = Am_Di::getInstance()->userTable->findBy(array('user_id'=>$userID));
    
    if (!empty($user))
    {
        $user = $user[0]; // first element

        // Get array of products by category
        $products = Am_Di::getInstance()->productCategoryTable->getCategoryProducts();

        $prodIds = $user->getActiveProductIds();

        // If user has active course product
        if (!empty(array_intersect($prodIds, $products[COURSES_CATEGORY_ID])))
        {
            $response = array("result"=>"true");
            header('Content-type: application/json');
            echo json_encode($response);
            return;
        }
    }

    $response = array("result"=>"false");
    header('Content-type: application/json');
    echo json_encode($response);
}

function getInProgressExamInfo($userID, $examID)
{
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
            
            foreach ($exams as $i => $exam)
            {
                $attempt = array_search($examID, 
                    $exam->attemptIDs);
                
                if ($attempt !== false)
                {
                    $examNum = $i + 1;
                    $attemptNum = $attempt + 1;

                    $response = array("examNum"=>$examNum, "attemptNum"=>$attemptNum);
                    header('Content-type: application/json');
                    echo json_encode($response);

                    return; 
                }
            }
        }
    }

    $response = array("examNum"=>0, "attemptNum"=>0);
    header('Content-type: application/json');
    echo json_encode($response);    
}

?>