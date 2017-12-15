<?php

switch($_SERVER['REQUEST_METHOD'])
{
	case 'POST': 
		handleError();
		break;
}

function handleError()
{
    // Fetch the raw POST body containing the message
	$postBody = file_get_contents('php://input');

	// JSON decode the body to an array of message data
	$data = json_decode($postBody, true);
	if ($data) 
	{
        $userInfo = $data['user'];
        $errorCondition = $data['error'];
        $errorMessage = $data['message'];      
		
        // JSON decode the client info object (browser report)
        $clientInfo = json_decode($data['client'], true);

        // Compose email message
        $message = "<h2>Practice Exam Error</h2>";
        $message .= "<div>User: " . $userInfo . "<br/>";
        $message .= "Error: " . $errorCondition . "<br/>";
        if ($errorMessage)
        {
            $message .= "Message: " . print_r($errorMessage, true) . "<br/>";
        }
        $message .= "<p>";
        $message .= "Browser: " . $clientInfo["browser"]["name"] . " - ";
        $message .= $clientInfo["browser"]["version"] . "<br/>";
        $message .= "OS: " . $clientInfo["os"]["name"] . " - ";
        $message .= $clientInfo["os"]["version"] . "<br/>";
        $message .= "Browser size: " . $clientInfo["viewport"]["width"] . " x " . 
            $clientInfo["viewport"]["height"] . "<br/>";
        $message .= "Screen size: " . $clientInfo["screen"]["width"] . " x " . 
            $clientInfo["screen"]["height"] . " @ " . 
            $clientInfo["screen"]["dppx"] . "x<br/>";
        $message .= "User agent string:<br/>" . $clientInfo["userAgent"];        
        $message .= "</p></div>";

        // Send email
        error_log($message, 1, "alberto@therapyexamprep.com", 
            "Content-Type: text/html; charset=ISO-8859-1");        
    }
}
?>