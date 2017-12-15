<?php
require_once 'defines.php';
require_once '/home/afonseca/www/products/practice-exam/Db.php';
require_once '/home/afonseca/www/products/practice-exam/vendor/httpful.phar'; // http://phphttpclient.com/
require_once '/home/afonseca/public_html/amember/library/Am/Lite.php';

use Httpful\Request;


// Set debug flag from URL
$debug = isset($_GET['debug']);

class ScoreReport {

    private $examID;
    private $result;

    public function __construct($examID, $access){
        if (!$examID)
        {
            throw new Exception("Exam ID is NULL");
        }

        $this->examID = $examID;        

        if ($access == "admin")
        {
            $this->result = $this->loadDataFromDataBase($this->examID);
        }
        // Check if logged in user is exam owner
        else
        {
            $user = Am_Lite::getInstance()->getUser();
            if ($user)
            {
                $userID = $user[user_id];

                $db = new Db();
                try
                {
                    $queryStr = "SELECT id FROM " . EXAMS_TABLE . " WHERE " . 
                        "id = ${examID} AND userID = ${userID}";   
                    $rows = $db->select($queryStr);

                    // If user id matches exam, continue init.
                    if ($rows && $rows[0]['id'] == $examID)
                    {
                        $this->result = $this->loadDataFromDataBase($this->examID);
                    }
                    // User id doesn't match, throw exception
                    else
                    {
                        throw new Exception("No exam match for user id: " . $userID);
                    }                  
                }
                catch(DbException $e)
                {
                    // bubble up db exception
                    throw new Exception("Db exception in ScoreReport constructor.", 
                        0, $e); 
                }
            }
        }
    }

    private function percentage($correct,$total)
    {
        return round(($correct/$total)*100);
    }

    private function isQuestionNonExperimental($question)
    {
        return $question['experimental']!=1;
    }

    private function isAnswerCorrect($choice, $answerOrder, $answer)
    {
        return ($choice != '' && $answerOrder[$choice-1] == $answer);
    }

    function getUsername()
    {  
        $db = new Db();

        try
        {
            $queryStr = "SELECT login FROM afonseca_amember.am_user WHERE " . 
                "user_id = " . $this->result['userID'];   
            $rows = $db->select($queryStr);
            return $rows[0]['login'];
        }
        catch(DbException $e)
        {
            return null; 
        }
    }

    private function overallConfidenceAnalysisHelper($should_return_small_table,$systems = '',
        $content_sections = '')
    {
        if ($this->result['version'] == 1)
            $pass_numbers = unserialize(V1_PASS_NUMBERS);
        else $pass_numbers = unserialize(V2_PASS_NUMBERS);

        $answers = $this->result ['answers'];
        $questions = $this->result['questions'];
        $answerOrder = $this->result['answerOrder'];

        $overall_confidence_analysis = array();
           
        if ($should_return_small_table)
        {
            $small_table_data = array();
            $small_table_data['total'] = 0;
            $small_table_data['sum'] = 0;
            $small_table_data['hard'] = 0;
            $small_table_data['correct'] = 0;
        }
        else
        {

            for ($i=0; $i < sizeof($systems); $i++) { 
                for ($j=0; $j < sizeof($content_sections); $j++) {
                    $overall_confidence_analysis[$content_sections[$j]]
                    [$systems[$i]]['sum'] = 0;
                    $overall_confidence_analysis[$content_sections[$j]]
                    [$systems[$i]]['hard'] = 0;
                    $overall_confidence_analysis[$content_sections[$j]]
                    [$systems[$i]]['total'] = 0;
                    $overall_confidence_analysis[$content_sections[$j]]
                    [$systems[$i]]['correct'] = 0;

                }
            }
        }

        foreach ($answers as $answer)
        {
            $id = $answer->id;
            if ($this->isQuestionNonExperimental($questions[$id]))
            {
                if (!$should_return_small_table && in_array($questions[$id]['content_section'], 
                    $content_sections))
                {
                    if (in_array($questions[$id]['system'], $systems))
                    {
                        $overall_confidence_analysis[$questions[$id]['content_section']]
                        [$questions[$id]['system']]['total']++;
                        
                        if ($answer->guessed == 1)
                            $overall_confidence_analysis[$questions[$id]['content_section']]
                        [$questions[$id]['system']]['sum']++;
                        elseif ($answer->numTimesChanged!='')
                            $overall_confidence_analysis[$questions[$id]['content_section']]
                        [$questions[$id]['system']]['sum']++;
                        if ($answer->difficulty=="hard")
                            $overall_confidence_analysis[$questions[$id]['content_section']]
                        [$questions[$id]['system']]['hard']++;
                        if ($this->isAnswerCorrect($answer->choice, $answerOrder,
                         $questions[$id]['answer']))
                            $overall_confidence_analysis[$questions[$id]['content_section']]
                        [$questions[$id]['system']]['correct']++;
                    }
                    elseif ($this->result['version'] == 1) {
                        $overall_confidence_analysis[$questions[$id]['content_section']]
                        ['Other Systems']['total']++;
                        if ($answer->guessed == 1)
                            $overall_confidence_analysis[$questions[$id]['content_section']]
                        ['Other Systems']['sum']++;
                        elseif ($answer->numTimesChanged!='')
                            $overall_confidence_analysis[$questions[$id]['content_section']]
                        ['Other Systems']['sum']++;
                        if ($answer->difficulty=="hard")
                            $overall_confidence_analysis[$questions[$id]['content_section']]
                        ['Other Systems']['hard']++;
                        if ($this->isAnswerCorrect($answer->choice, $answerOrder,
                         $questions[$id]['answer']))
                            $overall_confidence_analysis[$questions[$id]['content_section']]
                        ['Other Systems']['correct']++;
                    }
                }
                elseif ($should_return_small_table)
                {
                    $small_table_data['total']++;
                    
                    if ($answer->guessed == 1)
                        $small_table_data['sum']++;
                    elseif ($answer->numTimesChanged!='')
                        $small_table_data['sum']++;
                    if ($answer->difficulty=="hard")
                        $small_table_data['hard']++;
                    if ($answer->choice != '' && $answerOrder[$answer->choice-1] ==
                     $questions[$id]['answer'])
                        $small_table_data['correct']++;
                }
            }
        }
        $table = array();
        $table['classes'] = 'footable';
        $table_row = 0;
        $table_column = 0;
        
        $row = 0;
        $column = 0;
        $table['head_array'][$table_column]['attribute_heading'] = 'data-class';
        $table['head_array'][$table_column]['attribute_value'] = 'expand';
        $table['head_array'][$table_column]['value'] = 'Content Section';
        $table_column ++;
        if ($should_return_small_table)
        {
            $table['head_array'][$table_column]['attribute_heading'] = 'data-class';
            $table['head_array'][$table_column]['attribute_value'] = 'expand';
            $table['head_array'][$table_column]['value'] = '';
            $table_column ++;
        }
        else {
            foreach ($systems as $system) {
                $table['head_array'][$table_column]['attribute_heading'] = 'data-hide';
                $table['head_array'][$table_column]['attribute_value'] = 'phone,tablet';
                $table['head_array'][$table_column]['value'] = $system;
                $table_column ++;
            }
        }



        foreach ($overall_confidence_analysis as $key=> $overall_confidence) {
            $column = 0;
            if ($row == 0) {
                
            }
            foreach ($overall_confidence as $key2=> $overall) {
                if ($column == 0)
                {
                    $table['body_array'][$table_row][$table_column]['value'] = 
                    $content_sections[$row];
                    $table_column ++;
                }
                $pass_number = $pass_numbers[$content_sections[$row]][$systems[$column]];
                $confidence_level = $this->getConfidenceLevel($overall['total'], 
                    $overall['sum'], $overall['hard'], $overall['correct'], $pass_number);
                $table['body_array'][$table_row][$table_column]['class'] = $confidence_level['class'];
                $table['body_array'][$table_row][$table_column]['value'] = $confidence_level['value'];
                $column++;
                $table_column ++;
            }
            $row++;
            $table_row ++;
            $table_column = 0;
        }
        if ($should_return_small_table)
        {
            $table['body_array'][$table_row][$table_column]['value'] = 'Non System Domains';
            $table_column ++;
            $confidence_level = $this->getConfidenceLevel($small_table_data['total'], 
                $small_table_data['sum'], $small_table_data['hard'], $small_table_data['correct'], 3);
            $table['body_array'][$table_row][$table_column]['class'] = $confidence_level['class'];
            $table['body_array'][$table_row][$table_column]['value'] = $confidence_level['value'];
        }
        return $this -> printTable($table);
    }

    private function getConfidenceLevel($total, $sum, $hard, $correct, $pass_number)
    {

        $class = 'confidence_cell ';
        if ($total > 0 )
        {
            $sum_percentage = $this->percentage($sum,$total);
            $hard_percentage = $this->percentage($hard,$total);
        }
        else
        {
            $sum_percentage = 0;
            $hard_percentage = 0;
        }
        if ($sum_percentage >= 36 || $hard_percentage >= 76)
        {
            $class .= ' low';
            if ($correct < $pass_number)
            {
                $class .= '_fail';
            }
            else $class .= "_pass";
            $value = 'Low';
        }
        elseif (($sum_percentage >= 11 && $sum_percentage <= 35) 
            || ($hard_percentage >= 26 && $hard_percentage <= 75))
        {
            $class .= ' average';
            if ($correct < $pass_number)
            {
                $class .= "_fail";
            }
            else $class .= "_pass";
            $value = 'Average';
        }
        elseif ($sum_percentage <= 10 || $hard_percentage <= 25)
        {
            $class .= ' high';
            if ($correct < $pass_number)
                $class .= "_fail";
            else $class .= "_pass";
            $value = 'High';
        }
        if ($correct < $pass_number)
            $value .= "/Fail";
        else $value .= "/Pass";
        return array(
            'class' => $class,
            'value' => $value
        );
        
    }
    
    private function displayOverallAnalysisTableHelper($systems,$content_sections)
    {


        $answers = $this->result ['answers'];
        $questions = $this->result['questions'];
        $answerOrder = $this->result['answerOrder'];

        $overall_analysis = array();

        for ($i=0; $i < sizeof($systems); $i++) { 
            for ($j=0; $j < sizeof($content_sections); $j++) { 
                $overall_analysis[$content_sections[$j]]
                [$systems[$i]]['total'] = 0;
                $overall_analysis[$content_sections[$j]]
                [$systems[$i]]['correct'] = 0;
            }
            $overall_analysis['total'][$systems[$i]] = 0;
            $overall_analysis['correct'][$systems[$i]] = 0;
            $overall_analysis['percentage'][$systems[$i]] = 0;
            $overall_analysis['missed'][$systems[$i]] = 0;
        }
        if ($this->result['version'] == 1)
            $pass_numbers = unserialize(V1_PASS_NUMBERS);
        else $pass_numbers = unserialize(V2_PASS_NUMBERS);
        
        foreach ($answers as $answer)
        {
            $id = $answer->id;
            if ($this->isQuestionNonExperimental($questions[$id]))
            {
                if (in_array($questions[$id]['content_section'], $content_sections))
                {
                    if (in_array($questions[$id]['system'], $systems))
                    {
                        $overall_analysis[$questions[$id]['content_section']]
                        [$questions[$id]['system']]['total']++;
                        if ($this->isAnswerCorrect($answer->choice, $answerOrder, 
                            $questions[$id]['answer']))
                            $overall_analysis[$questions[$id]['content_section']]
                            [$questions[$id]['system']]['correct']++;
                    }
                    elseif ($this->result['version'] == 1)
                    {
                        $overall_analysis[$questions[$id]['content_section']]
                        ["Other Systems"]['total']++;
                        if ($this->isAnswerCorrect($answer->choice, $answerOrder, 
                            $questions[$id]['answer']))
                            $overall_analysis[$questions[$id]['content_section']]
                            ["Other Systems"]['correct']++;
                    }
                }
                if ($questions[$id]['content_section']!="Non System Domains")
                {
                    if (in_array($questions[$id]['system'], $systems))
                    {
                        $overall_analysis['total'][$questions[$id]['system']]++;
                        if (!$this->isAnswerCorrect($answer->choice, 
                            $answerOrder, $questions[$id]['answer']))
                            $overall_analysis['missed'][$questions[$id]['system']]++;
                        if ($this->isAnswerCorrect($answer->choice, $answerOrder, 
                            $questions[$id]['answer']))
                            $overall_analysis['correct'][$questions[$id]['system']]++;
                        $overall_analysis['percentage'][$questions[$id]['system']] = 
                        $this->percentage($overall_analysis['correct'][$questions[$id]['system']]
                            ,$overall_analysis['total'][$questions[$id]['system']]);
                    }
                    elseif ($this->result['version'] == 1)
                    {
                        $overall_analysis['total']["Other Systems"]++;
                        if (!$this->isAnswerCorrect($answer->choice, 
                            $answerOrder, $questions[$id]['answer']))
                            $overall_analysis['missed']["Other Systems"]++;
                        if ($this->isAnswerCorrect($answer->choice, $answerOrder, 
                            $questions[$id]['answer']))
                            $overall_analysis['correct']["Other Systems"]++;
                        $overall_analysis['percentage']["Other Systems"] = 
                        $this->percentage($overall_analysis['correct']["Other Systems"]
                            ,$overall_analysis['total']["Other Systems"]);
                    }
                }
            }
        }
        $table_row = 0;
        $table_column = 0;

        $table = array();
        $table['classes'] = 'footable overall-analysis-table';
        $row = 0;
        foreach ($content_sections as $content_section) {
            $overall = $overall_analysis[$content_section];
            $column = 0;
            if ($row == 0)
            {
                $table['head_array'][$table_column]['attribute_heading'] = 'data-class';
                $table['head_array'][$table_column]['attribute_value'] = 'expand';
                $table['head_array'][$table_column]['value'] = 'Content Section';
                $table_column ++;
                foreach ($systems as $system)
                {
                    $table['head_array'][$table_column]['attribute_heading'] = 'data-hide';
                    $table['head_array'][$table_column]['attribute_value'] = 'phone,tablet';
                    $table['head_array'][$table_column]['value'] = $system;
                    $table_column ++;
                }
            }
            foreach ($overall as $overall_) {
                if ($column == 0)
                {
                    $table['body_array'][$table_row][$table_column]['value'] = 
                    $content_sections[$row];
                    $table_column ++;
                }
                if ($overall_['total'] > 0)
                    $table['body_array'][$table_row][$table_column]['value'] = 
                $overall_['correct']."/".$overall_['total'];
                else $table['body_array'][$table_row][$table_column]['value'] = '-';
                $table_column ++;
                $column++;
            }
            
            $row++;
            $table_row ++;
            $table_column = 0;
            if ($row == 3)
                break;
        }

        $header_column = array('Missed','Correct','# to Pass','% Correct');
        $arr = array('missed','correct','pass_number','percentage');
        for ($i=0; $i < 4; $i++) { 
            for ($j=0; $j < sizeof($systems); $j++) { 
                if ($j==0)
                {
                    $table['body_array'][$table_row][$table_column]['value'] = $header_column[$i];
                    $table_column ++;
                }
                if ($arr[$i] == "percentage")
                    $table['body_array'][$table_row][$table_column]['value'] = 
                $overall_analysis['correct'][$systems[$j]]."/".$overall_analysis['total'][$systems[$j]]."\n".$overall_analysis[$arr[$i]][$systems[$j]]."%";
                else if ($arr[$i] == "correct" && 
                    $overall_analysis['correct'][$systems[$j]] < 
                    $pass_numbers[$systems[$j]]['overall'])
                {
                    $table['body_array'][$table_row][$table_column]['class'] = 'low-score';
                    $table['body_array'][$table_row][$table_column]['value'] = 
                    $overall_analysis[$arr[$i]][$systems[$j]];
                }
                else if($arr[$i] == "pass_number")
                {
                    $table['body_array'][$table_row][$table_column]['value'] = 
                    $pass_numbers[$systems[$j]]['overall'];
                }
                else $table['body_array'][$table_row][$table_column]['value'] = 
                    $overall_analysis[$arr[$i]][$systems[$j]];
                
                $table_column ++;
            }
            $table_row ++;
            $table_column = 0;
        }
        
        return $this->printTable($table);
    }

    function getCourseGuidanceText()
    {
        $answers = $this->result ['answers'];
        $questions = $this->result['questions'];
        $answerOrder = $this->result['answerOrder'];

        $score = $this->getScore();
        if ($score < COURSE_GUIDANCE_SCORE_LIMIT)
        {
            $text = <<<EOT
            <h2>Immediate Action Steps to Take</h2>
            <ul>
                <li>Review your missed questions and develop your specific and detailed focus topics.</li>
                <li>Organize your focus topics systematically to follow the 1 System, 1 body part or condition and relevant content sections.</li>
                <li>When studying, use TEP’s exam process to review the question and understand why the correct answer is right and the others are wrong from the content aspect and test-taking view.</li>
                <li>Be sure to make a brain dump from your specific topics to use for review later on.</li>
            </ul>
            <br/>
            <h2>Short Term Goals</h2>
            Score: Improve by 10 points by focusing on using the exam process and begin with the STEM.<br/>
            Timing: Decrease your timing on the questions by 5 seconds when starting with the STEM.<br/>
            System: Increase by 10 points by breaking down your specific focus topics and studying each system per week.<br/>
            Content Section: Increase by 10 points by looking at patterns of how the questions are asked.<br/>
            Test-taking Skills: Decrease the number of guesses and changed answers by using the exam process.<br/>
            Focus: Take breaks during each section after every 10 questions to improve your focus.
EOT;
        }
        else
        {
            $text = <<<EOT
            <h2>Immediate Action Steps to Take</h2>
            <ul>
                <li>Review your missed questions, organize and develop your specific focus topics.</li>
                <li>Begin to incorporate weaker topics from before for review.</li>
                <li>When studying, be sure to correlate clinical examples to help retain information.</li>
                <li>Update your braindump to reflect the areas for the next exam.</li>
            </ul>
            <br/>
            <h2>Short Term Goals</h2>
            Score: Improve by 5 points by focusing on using the exam process and begin with the STEM.<br/>
            Timing: Aim to answer questions around 45 seconds.<br/>
            System: Focus on your weakest system and continue to review the other systems.<br/>
            Content Section: Focus on your weakest system and continue to review other content sections.<br/>
            Test-taking Skills: Goal is to have few guesses and changed answers.<br/>
            Focus: Take breaks during each section after every 10 questions to improve your focus.
EOT;
        }

        $userID = $this->result['userID'];

        // Check if user has active course product        
        $url = "https://therapyexamprep.com/products/amember-webservice.php";
        $url .= "?action=hasActiveCourseProduct";
        $url .= "&userID=${userID}";
        $response = Request::get($url)->send();

        // Return text if in course.
        if ($response->body->result == "true")
        {
            return $text;
        }
        // Return upsell text if not in course.
        else
        {
            $text = <<<EOT
            <p>The following additional guidance is provided to those enrolled in our exam prep course:</p>
            <ol>
            <li>Immediate 3-4 action steps to take when studying</li>
            <li>Important short term goals to focus on in 6 key areas</li>
            </ol>
EOT;
            return $text;
        }
    }

    function getFocusText()
    {
        if ($this->result['survey']['lostFocus'] == "Yes")
        {
            return FOCUS_LOST;
        }
        else
        {
            return FOCUS_NOT_LOST;
        }
    }

    function getMindSetText()
    {
        if ($this->result['survey']['mindsetStart'] == 'Good')
        {
            if ($this->result['survey']['mindsetDuring'] == 'Good')
            {
                return GOOD_MINDSET;
            }
            elseif($this->result['survey']['mindsetAfter'] == "I passed")
            {
                return GOOD_MINDSET;
            }
        }
        elseif($this->result['survey']['mindsetAfter'] == "I passed")
        {
            if ($this->result['survey']['mindsetDuring'] == 'Good'){
                return GOOD_MINDSET;
            }
        }
        return NOT_GOOD_MINDSET;
    }

    function getScore(){
        $answers = $this->result ['answers'];
        $questions = $this->result['questions'];
        $answerOrder = $this->result['answerOrder'];

        $score = 0;
        foreach ($answers as $answer)
        {
            $id = $answer->id;
            if ($this->isQuestionNonExperimental($questions[$id]))
            {
                if ($this->isAnswerCorrect($answer->choice, $answerOrder, $questions[$id]['answer']))
                {
                    $score++;
                }
            }
        }

        return $score;
    }

    function getStatus(){
        $score = $this->getScore();
        
        if ($score >= GREEN_PASSING_SCORE)
            return '<span class="green">&nbsp;PASS&nbsp;</span>';
        else if ($score >= YELLOW_PASSING_SCORE)
            return '<span class="yellow">&nbsp;PASS&nbsp;</span>';
        else if ($score >= PASSING_SCORE)
            return '<span class="orange">&nbsp;PASS&nbsp;</span>';
        else
            return '<span class="red">&nbsp;FAIL&nbsp;</span>';
    }

    function getExamNum(){
        return $this->result['examNum'];
    }

    function getScoreBulletChart()
    {
        $score = $this->getScore();
        return "<div class='_svg'></div><script>doStuff($score);</script>";
    }

    function topSystemsChart()
    {
        $answers = $this->result ['answers'];
        $questions = $this->result['questions'];
        $answerOrder = $this->result['answerOrder'];
        if ($this->result['version'] == 1)
        {
            $all_systems = array('Musculoskeletal','Neuromuscular'
                    ,'CardioPulmonary');
        }
        else $all_systems = array('Musculoskeletal','Neuromuscular', 'CardioPulmonary',
         'Integumentary', 'Metabolic and Endocrine', 'GI', 'GU', 'Lymphatic', 'System Interactions');

        $systems = array();        
        $section_missed = array();
        $system_correct = array();
        for ($i=0; $i <5 ; $i++) { 
            $section_missed[$i] = 0;
        }
        $index = 0;
        foreach ($answers as $answer)
        {
            $section = floor($index/QUESTIONS_PER_SECTION);
            $id = $answer->id;
            
            if ($this->isQuestionNonExperimental($questions[$id]) && 
                $questions[$id]['content_section'] != "Non System Domains")
            {
                if (!$this->isAnswerCorrect($answer->choice, 
                    $answerOrder, $questions[$id]['answer']))
                {
                    $section_missed[$section]++;
                    if (!in_array($questions[$id]['system'],$all_systems))
                    {
                        if (isset($systems['Other Systems']))
                            $systems['Other Systems']++;
                        else $systems['Other Systems'] = 1;
                    }
                    else
                    {
                        if (isset($systems[$questions[$id]['system']]))
                            $systems[$questions[$id]['system']]++;
                        else $systems[$questions[$id]['system']] = 1;
                    }
                }
                else
                {
                    if (!in_array($questions[$id]['system'],$all_systems))
                    {
                        if (isset($system_correct['Other Systems']))
                            $system_correct['Other Systems'] ++;
                        else $system_correct['Other Systems'] = 1;
                    }
                    else
                    {
                        if (isset($system_correct[$questions[$id]['system']]))
                            $system_correct[$questions[$id]['system']] ++;
                        else $system_correct[$questions[$id]['system']] = 1;
                    }
                }
            }
        }
        arsort($systems);

        $index = 1;
        $final = array();
        array_push($final, array('System', 'Missed', array('role'=> 'style'), 
            array('role'=> 'annotation'),'Correct', array('role'=> 'style')));
        foreach ($systems as $key => $value) {
            if ($index < 4)
            {
                if(!isset($system_correct[$key]))
                    $system_correct[$key] = 0;
                array_push($final, array($key, $value, '#ce4d4a',$value, $system_correct[$key],
                 'color:gray; stroke-color:gray; stroke-width:2; opacity: 0.1'));
            }
            else break;
            $index++;
        }
        $final = json_encode($final);

        return "<div id='systems_chart_div'></div><script>drawMissedSystemsChart($final);</script>";
    }

    function topContentSectionsChart()
    {
        $answers = $this->result ['answers'];
        $questions = $this->result['questions'];
        $answerOrder = $this->result['answerOrder'];

        $content_sections = array();
        $section_missed = array();
        for ($i=0; $i <5 ; $i++) { 
            $section_missed[$i] = 0;
        }
        $index = 0;
        foreach ($answers as $answer)
        {
            $section = floor($index/QUESTIONS_PER_SECTION);
            $id = $answer->id;
               
            if ($this->isQuestionNonExperimental($questions[$id]))
            {
                if (!$this->isAnswerCorrect($answer->choice, 
                    $answerOrder, $questions[$id]['answer']))
                {
                    $section_missed[$section]++;

                    if (isset($content_sections[$questions[$id]['content_section']]))
                        $content_sections[$questions[$id]['content_section']]++;
                    else $content_sections[$questions[$id]['content_section']] = 1;
                }
                else
                {
                    if (isset($contentsection_correct[$questions[$id]['content_section']]))
                        $contentsection_correct[$questions[$id]['content_section']] ++;
                    else $contentsection_correct[$questions[$id]['content_section']] = 1;
                }
            }
        }
        arsort($content_sections);

        $index = 1;
        $final = array();
        array_push($final, array('Content Section', 'Missed', array('role'=> 'style'),
         array('role'=> 'annotation'),'Correct', array('role'=> 'style')));
        foreach ($content_sections as $key => $value) {
            if ($index < 4)
            {
                if(!isset($contentsection_correct[$key]))
                    $contentsection_correct[$key] = 0;
                array_push($final, array($key, $value, '#ce4d4a',$value, 
                    $contentsection_correct[$key], 
                    'color:gray; stroke-color:gray; stroke-width:2; opacity: 0.1'));
            }
            else break;
            $index++;
        }
        $final = json_encode($final);

        return "<div id='contentsections_chart_div'></div><script>drawMissedContentSectionsChart($final);</script>";
    }


    function getConfidenceRatingChart()
    {
        $answers = $this->result ['answers'];
        $questions = $this->result['questions'];
        $answerOrder = $this->result['answerOrder'];

        $changed = 0;
        $correct = 0;

        $hard = 0;
        $easy = 0;
        $guesses = 0;

        foreach ($answers as $answer)
        {
            $id = $answer->id;
            
            if ($this->isQuestionNonExperimental($questions[$id]))
            {
                if ($answer->numTimesChanged!='')
                {
                    $changed ++;
                    if ($this->isAnswerCorrect($answer->choice, $answerOrder, $questions[$id]['answer']))
                    {
                        $correct ++;
                    }
                }
                if ($answer->difficulty=="hard") {
                    $hard ++;
                }
                else if ($answer->difficulty=="easy")
                {
                    $easy ++;
                }
                if ($answer->guessed == 1)
                    $guesses ++;
            }
        }
        $confidence_chart = 1;
        if ($hard == 0 && $easy == 0 && $guesses == 0)
            $confidence_chart = 0;
        $changed_answers_chart = 1;
        if ($changed == 0)
            $changed_answers_chart = 0;
        $incorrect = $changed - $correct;
        $data = array("hard"=>$hard,"easy"=>$easy,"guesses"=>$guesses,
            "correct"=>$correct,"incorrect"=>$incorrect,
            "changed_answers_chart"=>$changed_answers_chart,
            "confidence_chart"=>$confidence_chart);

        $data = json_encode($data);
        if ($changed_answers_chart == 1 && $confidence_chart == 1)
            return "<ul class='new_ul'><li><div class='pie_chart' id='confidenceRatingChart'></div></li><li><div class='pie_chart' id='changedAnswersChart'></div></li></ul><script>drawPieCharts($data);</script>";
        else if ($changed_answers_chart == 1)
            return "<div class='one_chart pie_chart' id='changedAnswersChart'></div><script>drawPieCharts($data);</script>";
        else if ($confidence_chart == 1)
            return "<div class='one_chart pie_chart' id='confidenceRatingChart'></div><script>drawPieCharts($data);</script>";
        else return "";
    }

    private function getTopSystem($nth, $shouldReturnName)
    {
        $answers = $this->result ['answers'];
        $questions = $this->result['questions'];
        $answerOrder = $this->result['answerOrder'];
        $three_systems = array('Musculoskeletal','Neuromuscular'
                ,'CardioPulmonary');
                    

        $systems = array();        
        $section_missed = array();
        for ($i=0; $i <5 ; $i++) { 
            $section_missed[$i] = 0;
        }
        $index =0;
        foreach ($answers as $answer)
        {
            $section = floor($index/QUESTIONS_PER_SECTION);
            $id = $answer->id;
            
            if ($this->isQuestionNonExperimental($questions[$id]))
            {
                if (!$this->isAnswerCorrect($answer->choice, 
                    $answerOrder, $questions[$id]['answer']))
                {
                    $section_missed[$section]++;
                    if (!in_array($questions[$id]['system'],$three_systems))
                    {
                        if (isset($systems['Other Systems']))
                            $systems['Other Systems']++;
                        else $systems['Other Systems'] = 1;
                    }
                    else
                    {
                        if (isset($systems[$questions[$id]['system']]))
                            $systems[$questions[$id]['system']]++;
                        else $systems[$questions[$id]['system']] = 1;
                    }
                }
            }
        }

        arsort($systems);
        $index = 1;
        foreach ($systems as $key => $value) {
            if ($index == $nth){
                if ($shouldReturnName)
                    return $key;
                else return $value;
            }
            $index ++;
        }
    }

    private function getTopContentSection($nth, $shouldReturnName)
    {
        $answers = $this->result ['answers'];
        $questions = $this->result['questions'];
        $answerOrder = $this->result['answerOrder'];

        $content_sections = array();
        $section_missed = array();
        for ($i=0; $i <5 ; $i++) { 
            $section_missed[$i] = 0;
        }
        $index =0;
        foreach ($answers as $answer)
        {
            $section = floor($index/QUESTIONS_PER_SECTION);
            $id = $answer->id;
               
            if ($this->isQuestionNonExperimental($questions[$id]))
            {
                if (!$this->isAnswerCorrect($answer->choice, 
                    $answerOrder, $questions[$id]['answer']))
                {
                    $section_missed[$section]++;

                    if (isset($content_sections[$questions[$id]['content_section']]))
                        $content_sections[$questions[$id]['content_section']]++;
                    else $content_sections[$questions[$id]['content_section']] = 1;
                }
            }
        }
        arsort($content_sections);
        
        $index = 1;
        foreach ($content_sections as $key => $value) {
            if ($index == $nth){
                if ($shouldReturnName)
                    return $key;
                else return $value;
            }
            $index++;
        }
    }

    private function getSystemBreakdown($system_name)
    {
        $table = array();
        if ($this->result['version'] == 1)
            $pass_numbers = unserialize(V1_PASS_NUMBERS);
        else $pass_numbers = unserialize(V2_PASS_NUMBERS);
        $header_column = array('Evaluation','Examination','Intervention');
        
        $answers = $this->result ['answers'];
        $questions = $this->result['questions'];
        $answerOrder = $this->result['answerOrder'];
        $all_content_sections = array('Evaluation', 'Examination',
            'Intervention','Non System Domains');
        $breakdown = array();
        $breakdown['total'] = 0;
        $breakdown['correct'] = 0;

        $content_sections = array('Evaluation', 'Examination'
            , 'Intervention');

        for ($i=0; $i < 3; $i++) {
            for ($j=0; $j < 5; $j++) {
                $breakdown[$j][$content_sections[$i]]
                ['correct'] = 0;
                $breakdown[$j][$content_sections[$i]]
                ['total'] = 0;

            }

            $breakdown[$content_sections[$i]]
            ['total_correct'] = 0;
            $breakdown[$content_sections[$i]]
            ['total'] = 0;

        }

        for ($j=0; $j < 5; $j++) {
            $breakdown[$j]['missed'] = 0;
            $breakdown[$j]['correct'] = 0;
            $breakdown[$j]['total'] = 0;
        }

        $index =0;
        foreach ($answers as $answer)
        {
            $section = floor($index/QUESTIONS_PER_SECTION);
            $index++;
            $id = $answer->id;

            if ($this->isQuestionNonExperimental($questions[$id]))
            {
                if ($questions[$id]['content_section'] != "Non System Domains")
                {
                    if ($questions[$id]['system'] == $system_name || $system_name == 'Other Systems')
                    {
                        $breakdown['total']++;
                        
                        if(!$this->isAnswerCorrect($answer->choice, 
                            $answerOrder, $questions[$id]['answer']))
                        {
                            $breakdown[$section]['missed'] ++;
                        }
                        else
                        {
                            $breakdown[$section]['correct'] ++;
                            $breakdown['correct']++;
                        }
                        $breakdown[$section]['total'] ++;
                        $breakdown[$section]['percentage'] = 
                        $this->percentage($breakdown[$section]['correct'],
                            $breakdown[$section]['total']);
                        if (in_array($questions[$id]['content_section'],
                            $content_sections))
                        {
                            $breakdown[$questions[$id]['content_section']]
                            ['total']++;
                            $breakdown[$section][$questions[$id]['content_section']]
                            ['total']++;
                            if ($this->isAnswerCorrect($answer->choice, $answerOrder, 
                                $questions[$id]['answer']))
                            {
                                $breakdown[$section][$questions[$id]['content_section']]
                                ['correct']++;
                                $breakdown[$questions[$id]['content_section']]
                                ['total_correct']++;
                            }
                            if ($breakdown[$questions[$id]['content_section']]
                                ['total'] > 0)
                                $breakdown[$questions[$id]['content_section']]['percentage'] 
                            = $this->percentage($breakdown[$questions[$id]['content_section']]
                                ['total_correct']
                                ,$breakdown[$questions[$id]['content_section']]
                                ['total']);
                        }
                    }
                }
            }
        }
        $table['classes'] = 'footable';
        $table_row = 0;
        $table_column = 0;
        for ($j=0; $j < 3; $j++) {
            if ($j == 0)
            {
                $table['head_array'][$table_column]['attribute_heading'] = 'data-class';
                $table['head_array'][$table_column]['attribute_value'] = 'expand';
                $table['head_array'][$table_column]['value'] = 'Content Section';
                $table_column ++;
                for($k = 1; $k < 6; $k++)
                {
                    $table['head_array'][$table_column]['attribute_heading'] = 'data-hide';
                    $table['head_array'][$table_column]['attribute_value'] = 'phone,tablet';
                    $table['head_array'][$table_column]['value'] = 'Section '.$k;
                    $table_column ++;
                }

                $table['head_array'][$table_column]['attribute_heading'] = 'data-hide';
                $table['head_array'][$table_column]['attribute_value'] = 'phone,tablet';
                $table['head_array'][$table_column]['value'] = 'Correct';
                $table_column ++;
                $table['head_array'][$table_column]['value'] = '# to Pass';
                $table_column ++;
                $table['head_array'][$table_column]['value'] = '% Correct';

            }                 
            for ($i=0; $i < 5; $i++) {
                if ($i == 0)
                {
                    $table['body_array'][$table_row][$table_column]['value'] = $header_column[$j];
                    $table_column ++;
                }
                if($breakdown[$i][$all_content_sections[$j]]
                ['total'] > 0)
                    $table['body_array'][$table_row][$table_column]['value'] = $breakdown[$i]
                    [$all_content_sections[$j]]
                    ['correct']."/".$breakdown[$i][$all_content_sections[$j]]
                    ['total'];
                else $table['body_array'][$table_row][$table_column]['value'] = '-';
                $table_column ++;
            }

            if ($breakdown[$all_content_sections[$j]]
            ['total_correct'] < $pass_numbers[$all_content_sections[$j]]
            [$system_name])
                $table['body_array'][$table_row][$table_column]['class'] = "low-score";
            if ($breakdown[$all_content_sections[$j]]
            ['total'] > 0) {
                $table['body_array'][$table_row][$table_column]['value'] = 
                $breakdown[$all_content_sections[$j]]
                ['total_correct'];
                $table_column ++;
                $table['body_array'][$table_row][$table_column]['value'] = 
                $pass_numbers[$all_content_sections[$j]]
                [$system_name];
                $table_column ++;
                $table['body_array'][$table_row][$table_column]['value'] = 
                $breakdown[$all_content_sections[$j]]
                ['total_correct']."/".$breakdown[$all_content_sections[$j]]
                ['total']."\n".$this->percentage($breakdown[$all_content_sections[$j]]
                ['total_correct'],$breakdown[$all_content_sections[$j]]
                ['total'])."%";
            }
            else {
                $table['body_array'][$table_row][$table_column]['value'] = '-';
                $table_column ++;
                $table['body_array'][$table_row][$table_column]['value'] = '-';
                $table_column ++;
                $table['body_array'][$table_row][$table_column]['value'] = '-';
            }
            $table_row ++;
            $table_column = 0;
        }
        $table['body_array'][$table_row][$table_column]['value'] = 'Missed';
        $table_column ++;
        for ($i=0; $i < 5; $i++) {
            $table['body_array'][$table_row][$table_column]['value'] = 
            $breakdown[$i]['missed'];
            $table_column ++;
        }
        $table['body_array'][$table_row][$table_column]['value'] = '-';
        $table_column ++;
        $table['body_array'][$table_row][$table_column]['value'] = '-';
        $table_column ++;
        $table['body_array'][$table_row][$table_column]['value'] = '-';

        $table_row ++;
        $table_column = 0;
        $table['body_array'][$table_row][$table_column]['value'] = 'Correct';
        $table_column ++;
        for ($i=0; $i < 5; $i++) {
            if ($breakdown[$i]['correct'] < 
                $pass_numbers[$system_name][$i+1])
            {
                $table['body_array'][$table_row][$table_column]['class'] = 'low-score';
            }
            $table['body_array'][$table_row][$table_column]['value'] = $breakdown[$i]['correct'];
            $table_column ++;
        }
        $table['body_array'][$table_row][$table_column]['value'] = $breakdown['correct'];
        $table_column ++;
        $table['body_array'][$table_row][$table_column]['value'] = '-';
        $table_column ++;
        $table['body_array'][$table_row][$table_column]['value'] = '-';
        $pass_numbers_sum = 0;
        $table_row ++;
        $table_column = 0;
        $table['body_array'][$table_row][$table_column]['value'] = '# to Pass';
        $table_column ++;
        for ($i=0; $i < 5; $i++) {
            if ($pass_numbers[$system_name][$i+1] > 0)
                $table['body_array'][$table_row][$table_column]['value'] = 
                $pass_numbers[$system_name][$i+1];
            else $table['body_array'][$table_row][$table_column]['value'] = '-';
            $pass_numbers_sum += $pass_numbers[$system_name][$i+1];
            $table_column ++;
        }
        $table['body_array'][$table_row][$table_column]['value'] = '-';
        $table_column ++;
        $table['body_array'][$table_row][$table_column]['value'] = $pass_numbers_sum;
        $table_column ++;
        $table['body_array'][$table_row][$table_column]['value'] = '-';

        $table_row ++;
        $table_column = 0;
        $table['body_array'][$table_row][$table_column]['value'] = '% Correct';
        $table_column ++;
        for ($i=0; $i < 5; $i++) {
            $table['body_array'][$table_row][$table_column]['value'] = 
            $breakdown[$i]['correct']."/".$breakdown[$i]['total']."\n"
            .$breakdown[$i]['percentage']."%";
            $table_column ++;
        }
        $table['body_array'][$table_row][$table_column]['value'] = 
        $breakdown['correct']."/".$breakdown['total']."\n".
        $this->percentage($breakdown['correct']
            ,$breakdown['total'])."%";
        $table_column ++;
        $table['body_array'][$table_row][$table_column]['value'] = '-';
        $table_column ++;
        $table['body_array'][$table_row][$table_column]['value'] = '-';
        if ($this->result['version'] != 1 && 
            !in_array($system_name, array('Musculoskeletal','Neuromuscular',
                'CardioPulmonary')))
            return '<h2>'.$system_name.'</h2>'.$this->printTable($table);
        return $this->printTable($table);
    }

    private function getContentSectionBreakDownTable($content_section_name)
    {
        if ($this->result['version'] == 1)
        {
            $header_column = array('Musculoskeletal','Neuromuscular',
                'CardioPulmonary','Other Systems');
            $pass_numbers = unserialize(V1_PASS_NUMBERS);
            $content_sections_breakdown_systems = array('Musculoskeletal',
                'Neuromuscular','CardioPulmonary','Other Systems');
            $content_sections_breakdown_systems2 = array('Musculoskeletal','Neuromuscular'
                ,'CardioPulmonary','Other Systems');
        }
        else {
            $header_column = array('Musculoskeletal','Neuromuscular','CardioPulmonary',
                'Integumentary', 'Metabolic and Endocrine', 'GI', 'GU',
                 'Lymphatic', 'System Interactions');
            $pass_numbers = unserialize(V2_PASS_NUMBERS);
            $content_sections_breakdown_systems = array('Musculoskeletal','Neuromuscular'
                ,'CardioPulmonary', 'Integumentary', 'Metabolic and Endocrine',
                 'GI', 'GU', 'Lymphatic', 'System Interactions');
            $content_sections_breakdown_systems2 = array('Musculoskeletal','Neuromuscular'
                ,'CardioPulmonary', 'Integumentary', 'Metabolic and Endocrine',
                 'GI', 'GU', 'Lymphatic', 'System Interactions');
        }
        $answers = $this->result ['answers'];
        $questions = $this->result['questions'];
        $answerOrder = $this->result['answerOrder'];
        $all_content_sections = array('Evaluation', 'Examination',
            'Intervention','Non System Domains');
        

        $breakdown = array();

        $main_content_sections = array('Evaluation','Examination','Intervention');

        foreach ($main_content_sections as $main_content_section) {
            $breakdown['total'] = 0;
            $breakdown['correct'] = 0;
            for ($i=0; $i < 5; $i++) { 
                $breakdown[$i]['correct']=0;
                $breakdown[$i]['total']=0;
                $breakdown[$i]['missed']=0;
                $breakdown[$i]['percentage']=0;
            }
            foreach ($content_sections_breakdown_systems as $system) {
                $breakdown[$system]['correct']=0;
                $breakdown[$system]['total']=0;
                $breakdown[$system]['percentage']=0;
                for ($i=0; $i < 5; $i++) { 
                    $breakdown[$i][$system]['correct']=0;
                    $breakdown[$i][$system]['total']=0;
                }
            }
        }

        $index =0;
        foreach ($answers as $answer)
        {
            $section = floor($index/QUESTIONS_PER_SECTION);
            $index++;
            $id = $answer->id;

            if ($this->isQuestionNonExperimental($questions[$id]))
            {
                if ($questions[$id]['content_section'] == $content_section_name)
                {
                    $breakdown['total']++;
                    $breakdown
                    [$section]['total']++;
                    if ($this->isAnswerCorrect($answer->choice, $answerOrder,
                     $questions[$id]['answer']))
                    {
                        $breakdown
                        ['correct']++;
                        $breakdown
                        [$section]['correct']++;
                    }
                    else
                    {
                        $breakdown
                        [$section]['missed']++;
                    }
                    $breakdown
                    [$section]['percentage'] = 
                    $this->percentage($breakdown
                        [$section]['correct'],
                        $breakdown
                        [$section]['total']);
                    if (in_array($questions[$id]['system'], $content_sections_breakdown_systems2))
                    {
                        $breakdown
                        [$questions[$id]['system']]['total']++;
                        $breakdown
                        [$section][$questions[$id]['system']]['total']++;
                        if ($this->isAnswerCorrect($answer->choice, $answerOrder,
                         $questions[$id]['answer']))
                        {
                            $breakdown
                            [$questions[$id]['system']]['correct']++;
                            $breakdown
                            [$section][$questions[$id]['system']]['correct']++;
                        }
                        $breakdown
                        [$questions[$id]['system']]['percentage'] = 
                        $this->percentage($breakdown[$questions[$id]['system']]['correct']
                            ,$breakdown[$questions[$id]['system']]['total']);
                    }
                    else
                    {
                        $breakdown
                        ['Other Systems']['total']++;
                        $breakdown
                        [$section]['Other Systems']['total']++;
                        if ($this->isAnswerCorrect($answer->choice, $answerOrder,
                         $questions[$id]['answer']))
                        {
                            $breakdown
                            ['Other Systems']['correct']++;
                            $breakdown
                            [$section]['Other Systems']['correct']++;
                        }
                        $breakdown
                        ['Other Systems']['percentage'] = 
                        $this->percentage($breakdown['Other Systems']['correct'],$breakdown
                            ['Other Systems']['total']);
                    }
                }
            }
        }
        
        $table = array();
        $table['classes'] = 'footable';
        $table_row = 0;
        $table_column = 0;

        for ($j=0; $j < sizeof($header_column); $j++) { 
            if ($j == 0)
            {
                $table['head_array'][$table_column]['attribute_heading'] = 'data-class';
                $table['head_array'][$table_column]['attribute_value'] = 'expand';
                $table['head_array'][$table_column]['value'] = 'System';
                $table_column ++;
                for($k = 1; $k < 6; $k++)
                {
                    $table['head_array'][$table_column]['attribute_heading'] = 'data-hide';
                    $table['head_array'][$table_column]['attribute_value'] = 'phone,tablet';
                    $table['head_array'][$table_column]['value'] = 'Section '.$k;
                    $table_column ++;
                }
                

                $table['head_array'][$table_column]['attribute_heading'] = 'data-hide';
                $table['head_array'][$table_column]['attribute_value'] = 'phone,tablet';
                $table['head_array'][$table_column]['value'] = 'Correct';
                $table_column ++;
                $table['head_array'][$table_column]['value'] = '# to Pass';
                $table_column ++;
                $table['head_array'][$table_column]['value'] = '% Correct';

            }
            for ($i=0; $i < 5; $i++) { 
                if ($i == 0)
                {
                    $table['body_array'][$table_row][$table_column]['value'] = 
                    $header_column[$j];
                    $table_column ++;
                }
                if ($breakdown[$i]
                [$content_sections_breakdown_systems[$j]]['total'] > 0)
                    $table['body_array'][$table_row][$table_column]['value'] = 
                    $breakdown[$i]
                    [$content_sections_breakdown_systems[$j]]['correct'].
                    "/".$breakdown[$i]
                    [$content_sections_breakdown_systems[$j]]['total'];
                else {
                    $table['body_array'][$table_row][$table_column]['value'] = "-";
                }
                $table_column ++;

                
            }
            if ($breakdown
                [$content_sections_breakdown_systems[$j]]['total'] > 0)
            {
                if ($breakdown
                [$content_sections_breakdown_systems[$j]]['correct'] < 
                $pass_numbers[$content_section_name]
                [$content_sections_breakdown_systems[$j]])
                    $table['body_array'][$table_row][$table_column]['class'] = "low-score";
                $table['body_array'][$table_row][$table_column]['value'] = $breakdown
                [$content_sections_breakdown_systems[$j]]['correct'];
                $table_column ++;

                $table['body_array'][$table_row][$table_column]['value'] = 
                $pass_numbers[$content_section_name]
                [$content_sections_breakdown_systems[$j]];
                $table_column ++;
                $table['body_array'][$table_row][$table_column]['value'] = $breakdown
                [$content_sections_breakdown_systems[$j]]['correct'].
                "/".$breakdown[$content_sections_breakdown_systems[$j]]['total']."\n".
                $this->percentage($breakdown[$content_sections_breakdown_systems[$j]]['correct']
                    ,$breakdown
                    [$content_sections_breakdown_systems[$j]]['total'])."%";
            }
            else {
                $table['body_array'][$table_row][$table_column]['value'] = '-';
                $table_column ++;
                $table['body_array'][$table_row][$table_column]['value'] = '-';
                $table_column ++;
                $table['body_array'][$table_row][$table_column]['value'] = '-';
            }
                $table_row ++;
                $table_column = 0;
        }
        $table['body_array'][$table_row][$table_column]['value'] = 'Missed';
        $table_column ++;
        for ($i=0; $i < 5; $i++) {
            $table['body_array'][$table_row][$table_column]['value'] = $breakdown
            [$i]['missed'];
            $table_column ++;
        }
        $table['body_array'][$table_row][$table_column]['value'] = '-';
        $table_column ++;
        $table['body_array'][$table_row][$table_column]['value'] = '-';
        $table_column ++;
        $table['body_array'][$table_row][$table_column]['value'] = '-';
        $table_row ++;
        $table_column = 0;
        $table['body_array'][$table_row][$table_column]['value'] = 'Correct';
        $table_column ++;
        for ($i=0; $i < 5; $i++) {
            if ($breakdown[$i]['correct']
             < $pass_numbers[$content_section_name][$i+1])
                $table['body_array'][$table_row][$table_column]['class'] = 'low-score';
            $table['body_array'][$table_row][$table_column]['value'] = $breakdown
            [$i]['correct'];
            $table_column ++;
        }
        $table['body_array'][$table_row][$table_column]['value'] = $breakdown['correct'];
        $table_column ++;
        $table['body_array'][$table_row][$table_column]['value'] = '-';
        $table_column ++;
        $table['body_array'][$table_row][$table_column]['value'] = '-';
        $table_row ++;
        $table_column = 0;

        $table['body_array'][$table_row][$table_column]['value'] = '# to Pass';
        $table_column ++;
        $pass_numbers_sum = 0;
        for ($i=0; $i < 5; $i++) {
            $table['body_array'][$table_row][$table_column]['value'] = 
            $pass_numbers[$content_section_name]
            [$i+1]."</td>";
            $pass_numbers_sum += $pass_numbers[$content_section_name]
            [$i+1];
            $table_column ++;
        }
        $table['body_array'][$table_row][$table_column]['value'] = '-';
        $table_column ++;
        $table['body_array'][$table_row][$table_column]['value'] = $pass_numbers_sum;
        $table_column ++;
        $table['body_array'][$table_row][$table_column]['value'] = '-';
        $table_row ++;
        $table_column = 0;
        $table['body_array'][$table_row][$table_column]['value'] = '% Correct';
        $table_column ++;
        for ($i=0; $i < 5; $i++) {
            $table['body_array'][$table_row][$table_column]['value'] = $breakdown
            [$i]['correct']."/".$breakdown[$i]['total']."\n".
            $this->percentage($breakdown[$i]['correct'],$breakdown
            [$i]['total'])."%";
            $table_column ++;
        }
        $table['body_array'][$table_row][$table_column]['value'] = $breakdown['correct']."/".
        $breakdown
        ['total']."\n".$this->percentage($breakdown['correct'],$breakdown
            ['total'])."%";
        $table_column ++;
        $table['body_array'][$table_row][$table_column]['value'] = '-';
        $table_column ++;
        $table['body_array'][$table_row][$table_column]['value'] = '-';

        return $this->printTable($table);
    }

    function getAttemptNum(){
        return $this->result['attemptNum'];
    }

    function getElapsedTime(){
        $elapsedTime = $this->result ['elapsedTime'];

        $timeString = sprintf('%02d:%02d',($elapsedTime/3600%60), $elapsedTime/60%60);
        if ($this->result['timeScale'] > 1.0)
        {
            if ($this->result['timeScale'] == 2.0)
            {
                $timeString .= ' <span class="time-extension">(2x time extension)</span>';
            }
            else
            {
                $timeString .= ' <span class="time-extension">(' . 
                    $this->result['timeScale'] . 'x time extension)</span>';
            }
        }  
        return $timeString;
    }

    function getFirstTopSystem(){
        return $this->getTopSystem(1,false);
    }

    function getSecondTopSystem(){
        return $this->getTopSystem(2,false);

    }

    function getThirdTopSystem(){
        return $this->getTopSystem(3,false);

    }

    function getFirstTopContentSection(){
        return $this->getTopContentSection(1,false);
    }

    function getSecondTopContentSection(){
        return $this->getTopContentSection(2,false);

    }

    function getThirdTopContentSection(){
        return $this->getTopContentSection(3,false);
    }

    function getFirstTopSystemName(){
        return $this->getTopSystem(1,true);
    }

    function getSecondTopSystemName(){
        return $this->getTopSystem(2,true);
    }

    function getThirdTopSystemName(){
        return $this->getTopSystem(3,true);

    }

    function getFirstTopContentSectionName(){
        return $this->getTopContentSection(1,true);
    }

    function getSecondTopContentSectionName(){
        return $this->getTopContentSection(2,true);

    }

    function getThirdTopContentSectionName(){
        return $this->getTopContentSection(3,true);
    }

    function getLongestQuestionTime(){
        $answers = $this->result ['answers'];
        $questions = $this->result['questions'];

        $longest_time = 0;
        foreach ($answers as $answer)
        {
            $id = $answer->id;
            if ($answer->timeElapsed >= $longest_time)
            {
                $longest_time = $answer->timeElapsed;
                $longest_time_section = $questions[$id]['content_section'];
            }
        }
        
        return sprintf('%02d:%02d', ($longest_time/60%60), $longest_time%60);
    }

    function getLongestSectionToComplete(){
        $answers = $this->result ['answers'];
        
        $times_by_section = array();
        $times_by_section['time'] = array();
        $times_by_section['elapsed'] = array();
        $times_by_section['average'] = array();
        $times_by_section['slowest'] = array();
        $times_by_section['fastest'] = array();
        for($i = 0; $i < 5; $i++)
        {
            $times_by_section['time'][$i] = 0;
        }
        

        $index = 0;
        foreach ($answers as $answer)
        {
            $id = $answer->id;

            $section = floor($index/QUESTIONS_PER_SECTION);
            $index++;
            $id = $answer->id;
            $times_by_section['time'][$section]+=$answer->timeElapsed;
            if (isset($times_by_section['slowest'][$section]) 
                && isset($times_by_section['fastest'][$section]))
            {
                if ($answer->timeElapsed > $times_by_section['slowest'][$section])
                    $times_by_section['slowest'][$section] = $answer->timeElapsed;
                if ($answer->timeElapsed < $times_by_section['fastest'][$section])
                    $times_by_section['fastest'][$section] = $answer->timeElapsed;
            }
            else
            {
                $times_by_section['slowest'][$section] = $answer->timeElapsed;
                $times_by_section['fastest'][$section] = $answer->timeElapsed;
            }
            $times_by_section['elapsed'][$section] = sprintf('%02d:%02d',
             ($times_by_section['time'][$section]/3600%60), 
             $times_by_section['time'][$section]/60%60);
            $times_by_section['average'][$section] = 
            round($times_by_section['time'][$section]/QUESTIONS_PER_SECTION);
        }
        $times = array_keys($times_by_section['time'], max($times_by_section['time']));
        $times = $times[0];
        
        return ($times+1);
    }

    function getNumGuesses(){
        $answers = $this->result ['answers'];
        $questions = $this->result['questions'];

        $guessed_count = 0;
        foreach ($answers as $answer)
        {
            $id = $answer->id;
            if ($this->isQuestionNonExperimental($questions[$id]))
            {
                if ($answer->guessed == 1)
                    $guessed_count++;
            }
        }
        return $guessed_count;
    }
    
    function getNumMarkedHard(){
        $answers = $this->result ['answers'];
        $questions = $this->result['questions'];

        $hard_count = 0;
        foreach ($answers as $answer)
        {
            $id = $answer->id;   
            if ($this->isQuestionNonExperimental($questions[$id]))
            {
                if ($answer->difficulty=="hard")
                    $hard_count++;
            }
        }
        return $hard_count;
    }

    function getConfidenceChartWarning()
    {
        $answers = $this->result ['answers'];
        $questions = $this->result['questions'];

        $blank_difficulty = 0;
        foreach ($answers as $answer)
        {
            $id = $answer->id;   
            if ($this->isQuestionNonExperimental($questions[$id]))
            {
                if ($answer->difficulty=="")
                    $blank_difficulty++;
            }
        }
        if ($blank_difficulty >= BLANK_DIFFICULTY_LIMIT)
            return '<div class="alert alert-warning" role="alert"><i class="material-icons">warning</i> You did not mark the difficulty (easy/hard) of enough questions during your exam to provide you an accurate confidence analysis.</div>';
        else return "";
    }

    function getUnansweredCountWarning()
    {
        $answers = $this->result ['answers'];

        $unanswered_questions = 0;
        foreach ($answers as $answer)
        {
            if ($answer->choice == '')
                $unanswered_questions ++;
        }
        if ($unanswered_questions > 0)
            return '<div class="alert alert-warning" role="alert"><i class="material-icons">warning</i> You left '.$unanswered_questions.' questions unanswered on your exam.</div>';
        else return "";
    }
    
    function displaySystemBreakdownTables() {
    
        $this->displayMusculoskeletalBreakdownTable();
        $this->displayNeuromuscularBreakdownTable();
        $this->displayCardioPulmonaryBreakdownTable();
        $this->displayIntegumentaryBreakdownTable();
        $this->displayMetabolicBreakdownTable();
        $this->displayLymphaticBreakdownTable();
        $this->displayGIBreakdownTable();
        $this->displayGUBreakdownTable();
        $this->displaySystemInteractionBreakdownTable();
        $this->displayOtherSystemsBreakdownTable();
    }

    function displayContentSectionsBreakdownTables() {
        $this->displayEvaluationBreakdownTable();
        $this->displayExaminationBreakdownTable();
        $this->displayInterventionBreakdownTable();        
    }

    function displayEvaluationBreakdownTable()
    {
        return $this->getContentSectionBreakDownTable('Evaluation');             
    }

    
    
    
    
    function displayExaminationBreakdownTable()
    {
        return $this->getContentSectionBreakDownTable('Examination');                     
    }

    function displayInterventionBreakdownTable()
    {
        return $this->getContentSectionBreakDownTable('Intervention');                                       
    }

    function displayOtherSystemsBreakdownTable() {
        // Don't show unless legacy version
        if ($this->result['version'] != 1)
        {
            return;
        }
        return $this->getSystemBreakdown("Other Systems");
        
    }


    

    function displayCardioPulmonaryBreakdownTable() {
        return $this->getSystemBreakdown("CardioPulmonary");
    }

    
    function displayNeuromuscularBreakdownTable() {

        return $this->getSystemBreakdown('Neuromuscular');
    }

    function displayMusculoskeletalBreakdownTable() {

        return $this->getSystemBreakdown('Musculoskeletal');
    } 

    function displayIntegumentaryBreakdownTable()
    {

        // Don't display for legacy version
        if ($this->result['version'] == 1)
        {
            return;
        }
        else return $this->getSystemBreakdown('Integumentary');
        
    }

    function displayMetabolicBreakdownTable()
    {
        // Don't display for legacy version
        if ($this->result['version'] == 1)
        {
            return;
        }

        else return $this->getSystemBreakdown('Metabolic and Endocrine');
                    
    }

    function displayGIBreakdownTable()
    {
        // Don't display for legacy version
        if ($this->result['version'] == 1)
        {
            return;
        }
        else return $this->getSystemBreakdown('GI');
                     
    }

    function displayGUBreakdownTable()
    {
        // Don't display for legacy version
        if ($this->result['version'] == 1)
        {
            return;
        }

        else return $this->getSystemBreakdown('GU');
                                     
    }

    function displayLymphaticBreakdownTable()
    {
        // Don't display for legacy version
        if ($this->result['version'] == 1)
        {
            return;
        }

        else return $this->getSystemBreakdown('Lymphatic');
                                          
    }

    function displaySystemInteractionBreakdownTable()
    {
        // Don't display for legacy version
        if ($this->result['version'] == 1)
        {
            return;
        }

        else return $this->getSystemBreakdown('System Interactions');
                                 
    }

    function displayNonSystemDomainValue()
    {
        $answers = $this->result ['answers'];
        $questions = $this->result['questions'];
        $answerOrder = $this->result['answerOrder'];

        $non_system_domains_correct=0;
        foreach ($answers as $answer)
        {
            $id = $answer->id;
            if ($this->isQuestionNonExperimental($questions[$id]))
            {
                if ($questions[$id]['content_section'] == 'Non System Domains')
                {
                    if ($this->isAnswerCorrect($answer->choice, $answerOrder, 
                        $questions[$id]['answer']))
                    {
                        $non_system_domains_correct++;
                    }
                }
            }
        }

        return $non_system_domains_correct;
    }

    function displayNonSystemsDomainTable() {
        $answers = $this->result ['answers'];
        $questions = $this->result['questions'];
        $answerOrder = $this->result['answerOrder'];

        $non_system_domains = array();

        if ($this->result['version'] == 1)
            $pass_numbers = unserialize(V1_PASS_NUMBERS);
        else $pass_numbers = unserialize(V2_PASS_NUMBERS);

        $content_subsections = array('Assistive Devices','Therapeutic Modalities',
            'Safety & Protection','Professional Responsibilities','Research & EBP');

        foreach ($content_subsections as $content_subsection) {
            for ($i=0; $i < 5; $i++) {
                $non_system_domains[$content_subsection][$i]['total'] = 0;
                $non_system_domains[$content_subsection][$i]['correct'] = 0;
            }
            $non_system_domains[$content_subsection]['total'] = 0;
            $non_system_domains[$content_subsection]['correct'] = 0;
        }
        for ($i=0; $i < 5; $i++) {
            $non_system_domains['missed'][$i] = 0;
            $non_system_domains['correct'][$i] = 0;
            $non_system_domains['total'][$i] = 0;
        }
        $non_system_domains['final_total'] = 0;
        $non_system_domains['final_correct'] = 0;
        $non_system_domains['final_percentage'] = 0;

        $index = 0;
        foreach ($answers as $answer)
        {
            $id = $answer->id;
            $section = floor($index/QUESTIONS_PER_SECTION);
            $index++;
            $id = $answer->id;
            if ($this->isQuestionNonExperimental($questions[$id]))
            {
                if ($questions[$id]['content_section'] == "Non System Domains")
                {
                    if (in_array($questions[$id]['content_subsection'], $content_subsections))
                    {
                        $non_system_domains[$questions[$id]['content_subsection']][$section]
                        ['total']++;
                        $non_system_domains[$questions[$id]['content_subsection']]['total']++;
                        if ($this->isAnswerCorrect($answer->choice, $answerOrder, 
                            $questions[$id]['answer']))
                        {
                            $non_system_domains[$questions[$id]['content_subsection']][$section]
                            ['correct']++;
                            $non_system_domains[$questions[$id]['content_subsection']]['correct']++;
                        }
                    }
                    if (!$this->isAnswerCorrect($answer->choice, $answerOrder,
                     $questions[$id]['answer']))
                        $non_system_domains['missed'][$section]++;
                    else
                    {
                        $non_system_domains['correct'][$section]++;
                        $non_system_domains['final_correct']++;
                    }
                    $non_system_domains['final_total']++;
                    $non_system_domains['total'][$section]++;
                    $non_system_domains['percentage'][$section] = 
                        $this->percentage($non_system_domains['correct'][$section],
                            $non_system_domains['total'][$section]);

                }
            }
        }
        $arr = array('Missed','Correct','# to Pass','% Correct');
        $arr2 = array('missed','correct','pass_number','percentage');
        $table = array();
        $table['classes'] = 'footable';
        $table_row = 0;
        $table_column = 0;

        $row = 0;
        $pass_numbers_sum = 0;
        foreach ($content_subsections as $content_subsection) {
            if ($row == 0)
            {
                $table['head_array'][$table_column]['attribute_heading'] = 'data-class';
                $table['head_array'][$table_column]['attribute_value'] = 'expand';
                $table['head_array'][$table_column]['value'] = 'Domain';
                $table_column ++;
                for($k = 1; $k < 6; $k++)
                {
                    $table['head_array'][$table_column]['attribute_heading'] = 'data-hide';
                    $table['head_array'][$table_column]['attribute_value'] = 'phone,tablet';
                    $table['head_array'][$table_column]['value'] = 'Section '.$k;
                    $table_column ++;
                }

                $table['head_array'][$table_column]['attribute_heading'] = 'data-hide';
                $table['head_array'][$table_column]['attribute_value'] = 'phone,tablet';
                $table['head_array'][$table_column]['value'] = 'Correct';
                $table_column ++;
                $table['head_array'][$table_column]['value'] = '# to Pass';
                $table_column ++;
                $table['head_array'][$table_column]['value'] = '% Correct';

            }
            for ($i=0; $i < 5; $i++) {
                if ($i == 0)
                {
                    $table['body_array'][$table_row][$table_column]['value'] = $content_subsection;
                    $table_column ++;
                }
                if ($non_system_domains[$content_subsection][$i]['total'] > 0)
                {
                    $table['body_array'][$table_row][$table_column]['value'] = 
                    $non_system_domains[$content_subsection][$i]['correct']."/".
                    $non_system_domains[$content_subsection][$i]['total'];
                }
                else
                {
                    $table['body_array'][$table_row][$table_column]['value'] = "-";
                }
                $table_column ++;
            }
            if ($non_system_domains[$content_subsection]['correct'] < 
            $pass_numbers['Non System Domains'][$content_subsection])
                $table['body_array'][$table_row][$table_column]['class'] = 'low-score';

            $table['body_array'][$table_row][$table_column]['value'] = 
            $non_system_domains[$content_subsection]['correct'];

            $table_column ++;
            $table['body_array'][$table_row][$table_column]['value'] = 
            $pass_numbers['Non System Domains'][$content_subsection];
            $pass_numbers_sum += $pass_numbers['Non System Domains'][$content_subsection];
            $table_column ++;
            if ($non_system_domains[$content_subsection]['total'] > 0)
            {
                $table['body_array'][$table_row][$table_column]['value'] = 
                $non_system_domains[$content_subsection]['correct']."/".
                $non_system_domains[$content_subsection]['total']."\n".
                $this->percentage($non_system_domains[$content_subsection]['correct']
                    ,$non_system_domains[$content_subsection]['total'])."%";
            }
            else $table['body_array'][$table_row][$table_column]['value'] = '-';
            $table_row ++;
            $table_column = 0;
            $row++;
        }
        for ($i=0; $i < 4; $i++) {
            $sum = 0;
            for ($j=0; $j < 5; $j++) {
                if ($j == 0)
                {
                    $table['body_array'][$table_row][$table_column]['value'] = $arr[$i];
                    $table_column ++;
                }
                
                if ($arr2[$i] == "correct" &&
                    $non_system_domains["correct"][$j] < $pass_numbers['Non System Domains'][$j+1])
                {
                    $table['body_array'][$table_row][$table_column]['class'] = 'low-score';
                }
                if ($arr2[$i] == "percentage")
                {
                    $table['body_array'][$table_row][$table_column]['value'] = 
                    $non_system_domains["correct"][$j]."/".$non_system_domains["total"][$j].
                    "\n".$non_system_domains[$arr2[$i]][$j]."%";
                    $sum += $non_system_domains[$arr2[$i]][$j];
                }
                elseif ($arr2[$i] == "pass_number")
                {
                    $table['body_array'][$table_row][$table_column]['value'] = 
                    $pass_numbers['Non System Domains'][$j+1];
                    $sum += $pass_numbers['Non System Domains'][$j+1];
                }
                else
                {
                    $table['body_array'][$table_row][$table_column]['value'] = 
                    $non_system_domains[$arr2[$i]][$j];
                    $sum += $non_system_domains[$arr2[$i]][$j];
                }
                $table_column ++;
            }
            if ($arr2[$i] == "missed")
            {
                $table['body_array'][$table_row][$table_column]['value'] = '-';
                $table_column ++;
                $table['body_array'][$table_row][$table_column]['value'] = '-';
                $table_column ++;
                $table['body_array'][$table_row][$table_column]['value'] = '-';
            }
            elseif ($arr2[$i] == "correct")
            {
                $table['body_array'][$table_row][$table_column]['value'] = $sum;
                $table_column ++;
                $table['body_array'][$table_row][$table_column]['value'] = '-';
                $table_column ++;
                $table['body_array'][$table_row][$table_column]['value'] = '-';
            }
            elseif ($arr2[$i] == "pass_number")
            {
                $table['body_array'][$table_row][$table_column]['value'] = '-';
                $table_column ++;
                $table['body_array'][$table_row][$table_column]['value'] = $pass_numbers_sum;
                $table_column ++;
                $table['body_array'][$table_row][$table_column]['value'] = '-';
            }
            else {
                $table['body_array'][$table_row][$table_column]['value'] = 
                $non_system_domains['final_correct']."/".
                $non_system_domains['final_total']."\n".
                $this->percentage($non_system_domains['final_correct'],
                $non_system_domains['final_total']).
                "%";
                $table_column ++;
                $table['body_array'][$table_row][$table_column]['value'] = '-';
                $table_column ++;
                $table['body_array'][$table_row][$table_column]['value'] = '-';
            }
            $table_row ++;
            $table_column = 0;
        }
        return $this->printTable($table);
    }

    function getNumChangedAnswers()
    {
        $num_changed = 0;
        $answers = $this->result ['answers'];
        $questions = $this->result['questions'];
        foreach ($answers as $answer)
        {
            $id = $answer->id;
            if ($this->isQuestionNonExperimental($questions[$id]))
            {
                if ($answer->numTimesChanged!='')
                    $num_changed ++;
            }
        }
        return $num_changed;
    }
    function displayAnswerConfidenceBySectionTable() {

        for ($i=0; $i < 6; $i++) { 
            $answer_confidence_by_section['guesses'][$i] = 0;
            $answer_confidence_by_section['changed_answers'][$i] = 0;
            $answer_confidence_by_section['correct_after_change'][$i] = 0;
            $answer_confidence_by_section['easy'][$i] = 0;
            $answer_confidence_by_section['hard'][$i] = 0;
        }
    

        $answers = $this->result ['answers'];
        $questions = $this->result['questions'];
        $answerOrder = $this->result['answerOrder'];
        $index = 0;
        foreach ($answers as $answer)
        {
            $id = $answer->id;
            $section = floor($index/QUESTIONS_PER_SECTION);
            $index++;
            $id = $answer->id;
            if ($this->isQuestionNonExperimental($questions[$id]))
            {
                

                if ($answer->guessed == 1)
                {
                    $answer_confidence_by_section['guesses'][$section]++;
                    $answer_confidence_by_section['guesses'][5]++;
                }
                if ($answer->numTimesChanged!='')
                {
                    $answer_confidence_by_section['changed_answers'][$section]++;
                    $answer_confidence_by_section['changed_answers'][5]++;
                    if ($this->isAnswerCorrect($answer->choice, $answerOrder, 
                        $questions[$id]['answer']))
                    {
                        $answer_confidence_by_section['correct_after_change'][$section]++;
                        $answer_confidence_by_section['correct_after_change'][5]++;
                    }
                }
                if ($answer->difficulty=="hard")
                {
                    $answer_confidence_by_section['hard'][$section]++;
                    $answer_confidence_by_section['hard'][5]++;
                }
                if ($answer->difficulty=="easy")
                {
                    $answer_confidence_by_section['easy'][$section]++;
                    $answer_confidence_by_section['easy'][5]++;
                }
            }
        }
        $header_column = array('Guesses','Changed Answers', 'Correct After Change'
            ,'Easy','Hard');
        $table = array();
        $table['classes'] = 'footable';
        $table_row = 0;
        $table_column = 0;

        $row = 0;
        foreach ($answer_confidence_by_section as $answer_confidence) {
            if ($row == 0)
            {
                $table['head_array'][$table_column]['attribute_heading'] = 'data-class';
                $table['head_array'][$table_column]['attribute_value'] = 'expand';
                $table['head_array'][$table_column]['value'] = '';
                $table_column ++;
                for($k = 1; $k < 6; $k++)
                {
                    $table['head_array'][$table_column]['attribute_heading'] = 'data-hide';
                    $table['head_array'][$table_column]['attribute_value'] = 'phone,tablet';
                    $table['head_array'][$table_column]['value'] = 'Section '.$k;
                    $table_column ++;
                }

                $table['head_array'][$table_column]['value'] = 'Total';
            }
            $column = 0;
            foreach ($answer_confidence as $answer) {
                if ($column == 0)
                {
                    $table['body_array'][$table_row][$table_column]['value'] = $header_column[$row];
                    $table_column ++;
                }
                if ($column == 5 && $row < 2)
                {
                    if ($answer >= 15)
                        $table['body_array'][$table_row][$table_column]['class'] = 'low-score';
                    $table['body_array'][$table_row][$table_column]['value'] = $answer;
                }
                elseif ($row == 4 && $answer >= 50) {
                    $table['body_array'][$table_row][$table_column]['class'] = 'low-score';
                    $table['body_array'][$table_row][$table_column]['value'] = $answer;
                }
                else $table['body_array'][$table_row][$table_column]['value'] = $answer;
                $table_column ++;
                $column++;
            }
            $table_row ++;
            $table_column = 0;
            $row++;
        }
        return $this->printTable($table);
    }




    function displayOverallConfidenceAnalysisTable() {
        if ($this->result['version'] == 1)
        {
            $content_sections = array('Evaluation', 'Examination',
            'Intervention','Non System Domains');
            $systems = array('Musculoskeletal', 'Neuromuscular',
                'CardioPulmonary','Other Systems');
            return $this->overallConfidenceAnalysisHelper(false, $systems, $content_sections);
        }
        else
        {
            $content_sections = array('Evaluation', 'Examination',
            'Intervention');
            $systems = array('Musculoskeletal', 'Neuromuscular',
                'CardioPulmonary');
            $table1 = $this->overallConfidenceAnalysisHelper(false, $systems, $content_sections);

            $systems = array('Integumentary', 'Metabolic and Endocrine',
                'GI');
            $table2 = $this->overallConfidenceAnalysisHelper(false, $systems, $content_sections);

            $systems = array('GU', 'Lymphatic',
                'System Interactions');
            $table3 = $this->overallConfidenceAnalysisHelper(false, $systems, $content_sections);

            $small_table = $this->overallConfidenceAnalysisHelper(true);

            return $table1."<br/>".$table2."<br/>".$table3."<br/>".$small_table;

        }
        
    }

    function getTimesBySectionChart()
    {
        $answers = $this->result ['answers'];
        $questions = $this->result['questions'];
        $answerOrder = $this->result['answerOrder'];
        $timeScale = $this->result['timeScale'];
        for ($i=0; $i < 5; $i++) { 
            $times_by_section[$i] = 0;
            $average_times_by_section[$i] = 0;
        }

        $index = 0;
        foreach ($answers as $answer)
        {
            $section = floor($index/QUESTIONS_PER_SECTION);
            $index++;
            $times_by_section[$section]+=$answer->timeElapsed;

            $average_times_by_section[$section] = 
            round($times_by_section[$section]/QUESTIONS_PER_SECTION);
        }
        $total_average = 0;
        foreach ($average_times_by_section as $key => $value) {
            $total_average += $value;
        }
        $total_average /= 5;
        $time = round($total_average);
        $target = 45 * $timeScale;
        $average_times_by_section['target'] = $target;
        if ($time <= $target)
            $average_times_by_section['color'] = 'green';
        else $average_times_by_section['color'] = 'red';
        $average_times_by_section = json_encode($average_times_by_section);
        return "<div id='line_chart_div'></div><script>drawTimesBySectionChart($average_times_by_section);</script>";
    }

    function displayTimesBySectionTable() {
        $answers = $this->result ['answers'];
        $questions = $this->result['questions'];
        $answerOrder = $this->result['answerOrder'];
        $timeScale = $this->result['timeScale'];

        $times_by_section = array();
        $times_by_section['time'] = array();
        $times_by_section['elapsed'] = array();
        $times_by_section['average'] = array();
        $times_by_section['slowest'] = array();
        $times_by_section['fastest'] = array();

        for ($i=0; $i < 5; $i++) { 
            $times_by_section['time'][$i] = 0;
        }

        $index = 0;
        foreach ($answers as $answer)
        {
            $id = $answer->id;
            $section = floor($index/QUESTIONS_PER_SECTION);
            $index++;
            $id = $answer->id;
            $times_by_section['time'][$section]+=$answer->timeElapsed;
            if (isset($times_by_section['slowest'][$section]) 
                && isset($times_by_section['fastest'][$section]))
            {
                if ($answer->timeElapsed > $times_by_section['slowest'][$section])
                    $times_by_section['slowest'][$section] = $answer->timeElapsed;
                if ($answer->timeElapsed < $times_by_section['fastest'][$section])
                    $times_by_section['fastest'][$section] = $answer->timeElapsed;
            }
            else
            {
                $times_by_section['slowest'][$section] = $answer->timeElapsed;
                $times_by_section['fastest'][$section] = $answer->timeElapsed;
            }

            $times_by_section['elapsed'][$section] = sprintf('%02d:%02d',
             ($times_by_section['time'][$section]/3600%60), 
             $times_by_section['time'][$section]/60%60);
            $times_by_section['average'][$section] = 
            round($times_by_section['time'][$section]/QUESTIONS_PER_SECTION);
        }
        
        $header_column = array("Elapsed","Average","Slowest","Fastest");
        $table = array();
        $table['classes'] = 'footable';
        $table_row = 0;
        $table_column = 0;
        
        $row = 0;
        $column = 0;
        foreach ($times_by_section as $key=> $time_by_section) {
            $column = 0;
            if ($row == 0)
            { 
                $table['head_array'][$table_column]['attribute_heading'] = 'data-class';
                $table['head_array'][$table_column]['attribute_value'] = 'expand';
                $table['head_array'][$table_column]['value'] = 'Time';
                $table_column ++;
                for($k = 1; $k < 6; $k++)
                {
                    $table['head_array'][$table_column]['attribute_heading'] = 'data-hide';
                    $table['head_array'][$table_column]['attribute_value'] = 'phone,tablet';
                    $table['head_array'][$table_column]['value'] = 'Section '.$k;
                    $table_column ++;
                }                
            }                    
            else
            {
                foreach ($time_by_section as $time) {
                    if ($column == 0)
                    {
                        $table['body_array'][$table_row][$table_column]['value'] = 
                        $header_column[$row-1];
                        $table_column ++;
                    }
                    if ($key == 'elapsed')
                    {
                        list($hour, $min) = split('[:]', $time);
                        $hour = (int)$hour;
                        $min = (int)$min;
                        if ($hour > 0)
                            $formatted_time = $hour." hr ";
                        else $formatted_time = "";
                        if($min > 0)
                            $formatted_time .= $min." min";
                        elseif ($formatted_time == "")
                            $formatted_time = 0;
                        if ($formatted_time == 0)
                        {
                            $table['body_array'][$table_row][$table_column]['class'] = 'noTime';
                            $table['body_array'][$table_row][$table_column]['value'] = 0;
                            $table_column ++;
                        }
                        elseif ($times_by_section['time'][$column] >= (4320*$timeScale))
                        {
                            $table['body_array'][$table_row][$table_column]['class'] = 'red';
                            $table['body_array'][$table_row][$table_column]['value'] = $formatted_time;
                            $table_column ++;
                        }
                        elseif($times_by_section['time'][$column] >= 
                            (3000*$timeScale) && $times_by_section['time'][$column] < (4320*$timeScale))
                        {
                            $table['body_array'][$table_row][$table_column]['class'] = 'yellow';
                            $table['body_array'][$table_row][$table_column]['value'] = $formatted_time;
                            $table_column ++;
                        }
                        elseif($times_by_section['time'][$column] <= (3000*$timeScale))
                        {
                            $table['body_array'][$table_row][$table_column]['class'] = 'green';
                            $table['body_array'][$table_row][$table_column]['value'] = $formatted_time;
                            $table_column ++;
                        }
                    }
                    elseif($key == 'average')
                    {
                        if ($time > (72*$timeScale))
                        {
                            $table['body_array'][$table_row][$table_column]['class'] = 'red';
                            $table['body_array'][$table_row][$table_column]['value'] = $time." sec";
                            $table_column ++;
                        }
                        elseif($time >=(50*$timeScale) && $time <= (72*$timeScale))
                        {
                            $table['body_array'][$table_row][$table_column]['class'] = 'yellow';
                            $table['body_array'][$table_row][$table_column]['value'] = $time." sec";
                            $table_column ++;
                        }
                        elseif ($time < (50*$timeScale))
                        {
                            $table['body_array'][$table_row][$table_column]['class'] = 'green';
                            $table['body_array'][$table_row][$table_column]['value'] = $time." sec";
                            $table_column ++;
                        }
                    }
                    else {
                        $table['body_array'][$table_row][$table_column]['value'] = $time." sec";
                        $table_column ++;
                    }
                    $column++;
                }
                $table_row ++;
                $table_column = 0;
            }
            $row++;
        }
        return $this->printTable($table);
    }
    
    function displayScoresBySectionTable() {
        $pass_numbers = unserialize(PASS_NUMBERS);
        $scores_by_section = array();
        $scores_by_section['missed']=array();
        $scores_by_section['correct']=array();
        $scores_by_section['pass_number']=array();
        $scores_by_section['percentage']=array();
        $pass_numbers_sum = 0;

        
        for ($i=0; $i < 5; $i++) { 
            $pass_numbers_sum += $pass_numbers[$i+1];
            $scores_by_section['missed'][$i] = 0;
            $scores_by_section['correct'][$i] = 0;
            $scores_by_section['pass_number'][$i] = $pass_numbers[$i+1];
            $scores_by_section['total'][$i] = 0;
        }
        $scores_by_section['pass_number'][5] = $pass_numbers_sum;
        
        $scores_by_section['total'][5] = 0;

        $answers = $this->result ['answers'];
        $questions = $this->result['questions'];
        $answerOrder = $this->result['answerOrder'];

        $index = 0;

        foreach ($answers as $answer)
        {
            $id = $answer->id;
            $section = floor($index/QUESTIONS_PER_SECTION);
            $index++;
            $id = $answer->id;
            if ($this->isQuestionNonExperimental($questions[$id]))
            {
                if ($this->isAnswerCorrect($answer->choice, $answerOrder, $questions[$id]['answer']))
                {
                    if (isset($scores_by_section['correct'][$section]))
                        $scores_by_section['correct'][$section]++;
                    else $scores_by_section['correct'][$section] = 1;
                    
                }
                if (!$this->isAnswerCorrect($answer->choice, $answerOrder, 
                    $questions[$id]['answer']))
                {
                    if (isset($scores_by_section['missed'][$section]))
                        $scores_by_section['missed'][$section]++;
                    else $scores_by_section['missed'][$section] = 1;
                }
                $scores_by_section['total'][$section]++;
                $scores_by_section['total'][5]++;
            }
        }
        $scores_by_section_missed = 0;
        $scores_by_section_correct = 0;
        $scores_by_section_total = 0;
        for ($i=0; $i < 5; $i++) { 
            $scores_by_section_correct += $scores_by_section['correct'][$i];
            $scores_by_section_missed += $scores_by_section['missed'][$i];
            $scores_by_section_total += $scores_by_section['total'][$i];
            $scores_by_section['percentage'][$i] = 
            $this->percentage($scores_by_section['correct'][$i],40);
        }
        $scores_by_section['missed'][5] = $scores_by_section_missed;
        $scores_by_section['correct'][5] = $scores_by_section_correct;
        $scores_by_section['total'][5] = $scores_by_section_total;
  
        $scores_by_section['percentage'][5] = $this->percentage($scores_by_section_correct,200);

        $header_column = array("Missed","Correct","# to Pass","% Correct");
        $table = array();
        $table['classes'] = 'footable';
        $table_row = 0;
        $table_column = 0;       
        $row = 0;
        $column = 0;
        foreach ($scores_by_section as $key=> $scores) {
            if ($key == 'total')
                break;
            
            $column = 0;
            if ($row == 0)
            { 
                $table['head_array'][$table_column]['attribute_heading'] = 'data-class';
                $table['head_array'][$table_column]['attribute_value'] = 'expand';
                $table['head_array'][$table_column]['value'] = '';
                $table_column ++;
                for($k = 1; $k < 6; $k++)
                {
                    $table['head_array'][$table_column]['attribute_heading'] = 'data-hide';
                    $table['head_array'][$table_column]['attribute_value'] = 'phone,tablet';
                    $table['head_array'][$table_column]['value'] = 'Section '.$k;
                    $table_column ++;
                }   
                $table['head_array'][$table_column]['value'] = 'Total';           
            }
            
            foreach ($scores as $score_section) {
                if ($column == 0)
                {
                    $table['body_array'][$table_row][$table_column]['value'] = $header_column[$row];
                    $table_column ++;
                }
                if ($key == 'percentage')
                {
                    $table['body_array'][$table_row][$table_column]['value'] = 
                    $scores_by_section['correct'][$column]."/".
                    $scores_by_section['total'][$column]."\n".$score_section."%";
                }
                else if ($key == 'correct' && $scores_by_section['correct'][$column] < 
                    $scores_by_section['pass_number'][$column])
                {
                    $table['body_array'][$table_row][$table_column]['class'] = 'low-score';
                    $table['body_array'][$table_row][$table_column]['value'] = $score_section;
                }
                else $table['body_array'][$table_row][$table_column]['value'] = 
                    $score_section;
                $table_column ++;
                $column++;
            }
            $table_row ++;
            $table_column = 0;
            $row++;
        }
        return $this->printTable($table);
    }

    private function printTable($table_values)
    {
        $table = "<table";
        if (isset($table_values['classes']))
        {
            $table .= ' class="'.$table_values['classes'].'"';
        }
        $table .= '>';
        $table .= '<thead><tr>';
        $head_values_array = $table_values['head_array'];
        foreach ($head_values_array as $head_value) {
            $table .= '<th';
            if (isset($head_value['attribute_heading']) && 
                isset($head_value['attribute_value']))
            {
                $table .= ' '.$head_value['attribute_heading'].'="';
                $table .= $head_value['attribute_value'].'"';
            }
            $table .= '>'.$head_value['value'].'</th>';

        }
        $table .= '</tr></thead><tbody>';
        
        $values_array = $table_values['body_array'];
        foreach ($values_array as $row) {
            $table .= '<tr>';
            foreach ($row as $td) {
                $table .= '<td';
                if (isset($td['class']))
                {
                    $table .= ' class="'.$td['class'].'"';
                }
                $table .= '>'.$td['value'].'</td>';
            }
            $table .= '</tr>';
        }
        $table .= '</tbody></table>';
        return $table;
    }



    function displayOverallAnalysisTable() {

        if ($this->result['version'] == 1)
        {
            $content_sections = array('Evaluation', 'Examination',
                'Intervention','Non System Domains');
            $systems = array('Musculoskeletal', 'Neuromuscular',
                    'CardioPulmonary','Other Systems');
            return $this->displayOverallAnalysisTableHelper($systems, $content_sections);
        }
    
        $content_sections = array('Evaluation', 'Examination',
                'Intervention');
        $systems = array('Musculoskeletal', 'Neuromuscular',
                'CardioPulmonary');
        $table1 = $this->displayOverallAnalysisTableHelper($systems, $content_sections);
        $systems = array('Integumentary', 'Metabolic and Endocrine',
                'GI');
        $table2 = $this->displayOverallAnalysisTableHelper($systems, $content_sections);
        $systems = array('GU', 'Lymphatic',
                'System Interactions');
        $table3 = $this->displayOverallAnalysisTableHelper($systems, $content_sections);
        return $table1."<br/>".$table2."<br/>".$table3;
        
    }

    function displayExperimentalScoresBySectionTable() {
    
        $answers = $this->result ['answers'];
        $questions = $this->result['questions'];
        $answerOrder = $this->result['answerOrder'];

        $experimental_scores_by_section = array();
        
        for ($i=0; $i < 5; $i++) { 
            $experimental_scores_by_section['missed'][$i] = 0;
            $experimental_scores_by_section['correct'][$i] = 0;
        }

        $index = 0;
        foreach ($answers as $answer)
        {
            $id = $answer->id;
            $section = floor($index/QUESTIONS_PER_SECTION);
            $index++;
            $id = $answer->id;
            if ($questions[$id]['experimental']==1)
            {
                if ($this->isAnswerCorrect($answer->choice, $answerOrder, $questions[$id]['answer']))
                {
                    if (isset($experimental_scores_by_section['correct'][$section]))
                        $experimental_scores_by_section['correct'][$section]++;
                    else $experimental_scores_by_section['correct'][$section] = 1;
                    
                }
                if (!$this->isAnswerCorrect($answer->choice, $answerOrder, 
                    $questions[$id]['answer']))
                {
                    if (isset($experimental_scores_by_section['missed'][$section]))
                        $experimental_scores_by_section['missed'][$section]++;
                    else $experimental_scores_by_section['missed'][$section] = 1;
                }
                if (isset($experimental_scores_by_section['total'][$section]))
                        $experimental_scores_by_section['total'][$section]++;
                    else $experimental_scores_by_section['total'][$section] = 1;
            }
        }
        $experimental_scores_by_section_missed = 0;
        $experimental_scores_by_section_correct = 0;

        for ($i=0; $i < 5; $i++) { 
            $experimental_scores_by_section_correct += 
            $experimental_scores_by_section['correct'][$i];
            $experimental_scores_by_section_missed += 
            $experimental_scores_by_section['missed'][$i];
            $experimental_scores_by_section['percentage'][$i] = 
            $this->percentage($experimental_scores_by_section['correct'][$i],10);
        }

        $experimental_scores_by_section['missed'][5] = 
        $experimental_scores_by_section_missed;
        $experimental_scores_by_section['correct'][5] = 
        $experimental_scores_by_section_correct;
        $experimental_scores_by_section['percentage'][5] = 
        $this->percentage($experimental_scores_by_section_correct,QUESTIONS_PER_SECTION);
        $experimental_scores_by_section['total'][5] = QUESTIONS_PER_SECTION;

        $header_column = array("Missed","Correct","% Correct");
        $table = array();
        $table['classes'] = 'footable';
        $table_row = 0;
        $table_column = 0;
        
        $row = 0;
        $column = 0;

        foreach ($experimental_scores_by_section as $key=> $experimental_scores) {
            $column = 0;
            if ($row == 0)
            {            

                $table['head_array'][$table_column]['attribute_heading'] = 'data-class';
                $table['head_array'][$table_column]['attribute_value'] = 'expand';
                $table['head_array'][$table_column]['value'] = '';
                $table_column ++;
                for($k = 1; $k < 6; $k++)
                {
                    $table['head_array'][$table_column]['attribute_heading'] = 'data-hide';
                    $table['head_array'][$table_column]['attribute_value'] = 'phone,tablet';
                    $table['head_array'][$table_column]['value'] = 'Section '.$k;
                    $table_column ++;
                }   
                $table['head_array'][$table_column]['value'] = 'Total';               
            }           
            foreach ($experimental_scores as $experimental) {
                if ($row<3)
                {
                    if ($column == 0)
                    {
                        $table['body_array'][$table_row][$table_column]['value'] = 
                        $header_column[$row];
                        $table_column ++;
                    }
                    
                    if ($row == 2)
                    {
                        $table['body_array'][$table_row][$table_column]['value'] = 
                        $experimental_scores_by_section['correct'][$column]."/".
                        $experimental_scores_by_section['total'][$column]."\n".
                        $experimental_scores_by_section['percentage'][$column]."%";
                    }
                    else $table['body_array'][$table_row][$table_column]['value'] = $experimental;
                    $table_column ++;
                }
                $column++;
            }
            $table_row ++;
            $table_column = 0;
            $row++;
        }

        return $this->printTable($table);
    }
    function loadDataFromDataBase() {
    
        $db = new Db();
        // Get info and associated table id's from Exams table.
        $queryStr = "SELECT version, userID, examNum, attemptNum, questions," . 
            "answerOrder, answers, elapsedTime, examSurveyID, timeScale " .   
            "FROM " . EXAMS_TABLE . " WHERE id=" . $this->examID;

        $rows = $db->select($queryStr);
        $rows = $rows[0]; // Get first row

        $version = $rows["version"];
        $userID = $rows["userID"];
        $examNum = $rows["examNum"];
        $attemptNum = $rows["attemptNum"];
        $timeScale = $rows['timeScale'];

        // Get list of question id's.
        $questionList = json_decode($rows["questions"]);
        $QuestionsStr = implode(",", $questionList);    

        // Order in which answer choices were presented on exam.
        $answerOrder = json_decode($rows["answerOrder"]);

        // user answers
        $answers = json_decode($rows["answers"]);

        // elapsed time
        $elapsedTime = $rows["elapsedTime"];

        // id of survey in Exam_Survey table.
        $surveyID = $rows["examSurveyID"];

        // Get selected exam questions
        $queryStr = "SELECT `id`,`is_experimental`,`system`," . 
            "`content_section`,`content_subsection`,`mc_answer` FROM " . 
            QUESTIONS_TABLE . " WHERE `id` IN ({$QuestionsStr}) ORDER BY " . 
            "FIELD(id, {$QuestionsStr})";

        $rows = $db->select($queryStr);

        // Save data in aray indexed by question id
        $questions = array();
        foreach ($rows as $value)
        {
            $questions[(int)$value['id']] = array("experimental" => 
                (bool)$value['is_experimental'],
                "system" => $value['system'],
                "content_section" => $value['content_section'],
                "content_subsection" => $value['content_subsection'],
                "answer" => (int)$value['mc_answer']);
        }

        // Get survey data
        $queryStr = "SELECT * FROM  Exam_Survey WHERE id=" . $surveyID;

        $rows = $db->select($queryStr);
        $survey = $rows[0]; // Get first row

        $result['version'] = $version;
        $result['userID'] = $userID;
        $result['answers'] = $answers;
        $result['questions'] = $questions;
        $result['answerOrder'] = $answerOrder;
        $result['examNum'] = $examNum;
        $result['attemptNum'] = $attemptNum;
        $result['survey'] = $survey;
        $result['elapsedTime'] = $elapsedTime;
        $result['timeScale'] = $timeScale;

        return $result;
    }
    function getTimeString()
    {
        $timeScale = $this->result ['timeScale'];
        if ($timeScale == 1.0)
            return TIME_SCALE_ONE;
        elseif ($timeScale == 1.5)
            return TIME_SCALE_ONE_AND_HALF;
        else return TIME_SCALE_TWO;
    }


    function getSurveyResultsText()
    {
        $surveyStr = "<p class='survey-heading'>Exam</p>";
        $surveyStr .= "<p>";
        $surveyStr .= "Did you feel this exam gave you a realistic simulation?<br/>";
        $surveyStr .= $this->result['survey']['realistic'] . "<br/>";
        $surveyStr .= "Did you feel the exam questions were fair?<br/>";
        $surveyStr .= $this->result['survey']['fair'] . "<br/>";
        $surveyStr .= "Did you take unscheduled breaks?<br/>";
        $surveyStr .= $this->result['survey']['tookBreaks'] . "<br/>";
        $surveyStr .= "</p>";

        $surveyStr .= "<p class='survey-heading'>Mindset</p>";
        $surveyStr .= "<p>";
        $surveyStr .= "What was your mindset going into the practice exam?<br/>";
        $surveyStr .= $this->result['survey']['mindsetStart'] . "<br/>";
        $surveyStr .= "What was your mindset throughout the practice exam?<br/>";
        $surveyStr .= $this->result['survey']['mindsetDuring'] . "<br/>";
        $surveyStr .= "What was your mindset after taking the practice exam?<br/>";
        $surveyStr .= $this->result['survey']['mindsetAfter'] . "<br/>";
        $surveyStr .= "</p>";

        $surveyStr .= "<p class='survey-heading'>Focus</p>";
        $surveyStr .= "<p>";
        $surveyStr .= "Did you find your mind losing focus during the exam?<br/>";
        $surveyStr .= $this->result['survey']['lostFocus'] . "<br/>";
        $surveyStr .= "</p>";
        return $surveyStr;
    }

} 

if ($debug)
{
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    echo "<!DOCTYPE html><html><body>";
    echo '<link href="resources/css/score-report-style.css" rel="stylesheet">';
    echo '<script   src="https://code.jquery.com/jquery-3.1.1.min.js"   integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="   crossorigin="anonymous"></script>';
    echo '<script src="https://therapyexamprep.com/products/practice-exam/vendor/d3.js"></script>';
    echo '<script src="https://www.gstatic.com/charts/loader.js"></script>';
    echo '<script src="resources/js/bullet.js"></script>';
    echo '<script src="resources/js/bar_chart.js"></script>';
    echo '<script src="resources/js/line_chart.js"></script>';
    echo '<script src="resources/js/pie_chart.js"></script>';

    $scoreReport = new ScoreReport(1, "admin");

    echo $scoreReport->getConfidenceRatingChart();
    echo "<br>";
    echo $scoreReport->getNumChangedAnswers();
    echo "<br>";
    echo $scoreReport->getTimesBySectionChart();
    echo "<br>";
    echo $scoreReport->getScoreBulletChart();
    echo "<br>";
    echo $scoreReport->getMindSetText();
    echo "<br>topSystemsChart";
    echo $scoreReport->topSystemsChart();
    echo "<br>";
    echo $scoreReport->getTimeString();
    echo "<br>";
    echo $scoreReport->getFocusText();
    echo "<br>";
    echo $scoreReport->topContentSectionsChart();
    echo "<br>";
    echo $scoreReport->getScore();
    echo "<br>getStatus";
    echo $scoreReport->getStatus();
    echo "<br>";
    echo $scoreReport->getExamNum();
    echo "<br>";
    echo $scoreReport->getAttemptNum();
    echo "<br>";
    echo $scoreReport->getElapsedTime();
    echo "<br>getFirstTopSystemName";
    echo $scoreReport->getFirstTopSystemName();
    echo "<br>getFirstTopSystem";
    echo $scoreReport->getFirstTopSystem();
    echo "<br>getSecondTopSystemName";
    echo $scoreReport->getSecondTopSystemName();
    echo "<br>getSecondTopSystem";
    echo $scoreReport->getSecondTopSystem();
    echo "<br>getThirdTopSystemName";
    echo $scoreReport->getThirdTopSystemName();
    echo "<br>getThirdTopSystem";
    echo $scoreReport->getThirdTopSystem();
    echo "<br>";
    echo $scoreReport->getFirstTopContentSectionName();
    echo "<br>";
    echo $scoreReport->getFirstTopContentSection();
    echo "<br>";
    echo $scoreReport->getSecondTopContentSectionName();
    echo "<br>";
    echo $scoreReport->getSecondTopContentSection();
    echo "<br>";
    echo $scoreReport->getThirdTopContentSectionName();
    echo "<br>";
    echo $scoreReport->getThirdTopContentSection();
    echo "<br>";
    echo $scoreReport->getLongestQuestionTime();
    echo "<br>";
    echo $scoreReport->getLongestSectionToComplete();
    echo "<br>";
    echo $scoreReport->getNumGuesses();
    echo "<br>";
    echo $scoreReport->getNumMarkedHard();
    echo "<br>displayOtherSystemsBreakdownTable";
    echo $scoreReport->displayOtherSystemsBreakdownTable();
    echo "<br>displayMusculoskeletalBreakdownTable";
    echo $scoreReport->displayMusculoskeletalBreakdownTable();
    echo "<br>displayNeuromuscularBreakdownTable";
    echo $scoreReport->displayNeuromuscularBreakdownTable();
    echo "<br>displayCardioPulmonaryBreakdownTable";
    echo $scoreReport->displayCardioPulmonaryBreakdownTable();
    echo "<br>displayIntegumentaryBreakdownTable";
    echo $scoreReport->displayIntegumentaryBreakdownTable();
    echo "<br>displayMetabolicBreakdownTable";
    echo $scoreReport->displayMetabolicBreakdownTable();
    echo "<br>displayGIBreakdownTable";
    echo $scoreReport->displayGIBreakdownTable();
    echo "<br>displayGUBreakdownTable";
    echo $scoreReport->displayGUBreakdownTable();
    echo "<br>displayLymphaticBreakdownTable";
    echo $scoreReport->displayLymphaticBreakdownTable();
    echo "<br>displaySystemInteractionsBreakdownTable";
    echo $scoreReport->displaySystemInteractionBreakdownTable();
    echo "<br>displayNonSystemsDomainTable";
    echo $scoreReport->displayNonSystemsDomainTable();
    echo "<br>displayAnswerConfidenceBySectionTable";
    echo $scoreReport->displayAnswerConfidenceBySectionTable();
    echo "<br>";
    echo $scoreReport->getConfidenceChartWarning();
    echo "<br>getUnansweredCountWarning";
    echo $scoreReport->getUnansweredCountWarning();
    echo "<br>displayOverallConfidenceAnalysisTable";
    echo $scoreReport->displayOverallConfidenceAnalysisTable();
    echo "<br>displayTimesBySectionTable";
    echo $scoreReport->displayTimesBySectionTable();
    echo "<br>";
    echo $scoreReport->displayScoresBySectionTable();
    echo "<br>displayOverallAnalysisTable";
    echo $scoreReport->displayOverallAnalysisTable();
    echo "<br>displayExperimentalScoresBySectionTable";
    echo $scoreReport->displayExperimentalScoresBySectionTable();
    echo "<br>";
    echo $scoreReport->displayNonSystemDomainValue();
    echo "<br>displayEvaluationBreakdownTable";
    echo $scoreReport->displayEvaluationBreakdownTable();
    echo "<br>displayExaminationBreakdownTable";
    echo $scoreReport->displayExaminationBreakdownTable();
    echo "<br>displayInterventionBreakdownTable";
    echo $scoreReport->displayInterventionBreakdownTable();
    echo "<br>getCourseGuidanceText";
    echo $scoreReport->getCourseGuidanceText();
    echo "<br>getScore";
    echo $scoreReport->getScore();
    echo "</body></html>";
}
?>