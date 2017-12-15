// Practice Exam
// Copyright 2017 Therapy Exam Prep
// Author: Alberto Fonseca

// urls - production
const ERROR_URL = "https://therapyexamprep.com/products/practice-exam/exam-error-handler.php";

// urls - staging
//const ERROR_URL = "https://therapyexamprep.com/products/practice-exam-staging/exam-error-handler.php";

var errorData = {
    user: "John Done",
    error: 'ERROR_TEST',
    client: JSON.stringify(browserReportSync())
};

var request = $.ajax({
    type: "POST",
    url: ERROR_URL,
    data: JSON.stringify(errorData),
});

request.done(function (response, textStatus, jqXHR)
{
});

request.fail(function (jqXHR, textStatus, errorThrown)
{
    alert("Unable to communicate with the server. " + 
        "Please check your internet connection and try again.");
});
