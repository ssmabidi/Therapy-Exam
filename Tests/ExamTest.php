<?php

use PHPUnit\Framework\TestCase;

/**
 * @covers Exam
 */
final class ExamTest extends TestCase
{
    public function testTotalQuestionCount()
    {
        $questions = generateExamQuestions(new Db(), 1);

        $this->assertCount(250, $questions);        
    }

    public function testNoDuplicateQuestionIDs()
    {
        $questions = generateExamQuestions(new Db(), 1);
        
        $dups = array();
        foreach(array_count_values($questions) as $val => $c)
        {
            if($c > 1)
            {
                $dups[] = $val;            
            } 
        }
        $this->assertCount(0, $dups);
    }

    public function testTypesOfQuestionsPerSection()
    {
        $db = new Db();

        $questions = generateExamQuestions($db, 1);

        // Required question types per exam section
        // Each content section requires the following systems:
        // Musculoskeletal, Neuromuscular, CardioPulmonary, Integumentary, 
        // Metabolic and Endocrine, GI, GU, Lymphatic, System Interactions
        // With the exception of "Non-System Domains" which has:
        // Assistive Devices, Therapeutic Modalities, Safety & Protection,
        // Professional Responsibilities, Research & EBP
        $sectionsRequired = array(
            // section 1
            array("Evaluation" => array(4, 3, 2, 1, 1, 1, 1, 1, 2),
            "Examination" => array(4, 3, 1, 0, 0, 0, 0, 0, 0),
            "Intervention" => array(4, 3, 2, 1, 1, 0, 0, 0, 0),
            "Non-System Domains" => array(1, 1, 1, 1, 1)),

            // section 2
            array("Evaluation" => array(4, 3, 2, 0, 1, 1, 1, 1, 2),
            "Examination" => array(4, 3, 2, 1, 0, 0, 1, 0, 0),
            "Intervention" => array(3, 4, 1, 1, 0, 0, 0, 0, 0),
            "Non-System Domains" => array(1, 2, 1, 0, 1)),

            // section 3
            array("Evaluation" => array(4, 3, 2, 1, 0, 0, 0, 1, 2),
            "Examination" => array(3, 3, 2, 1, 0, 1, 0, 0, 0),
            "Intervention" => array(3, 3, 2, 1, 1, 1, 1, 0, 0),
            "Non-System Domains" => array(1, 1, 1, 1, 1)),

            // section 4
            array("Evaluation" => array(4, 4, 1, 1, 1, 1, 1, 0, 2),
            "Examination" => array(4, 3, 2, 0, 0, 0, 0, 1, 0),
            "Intervention" => array(4, 2, 2, 1, 0, 0, 0, 1, 0),
            "Non-System Domains" => array(1, 2, 1, 1, 0)),

            // section 5
            array("Evaluation" => array(4, 3, 2, 1, 1, 0, 0, 0, 2),
            "Examination" => array(4, 3, 1, 1, 0, 0, 0, 0, 0),
            "Intervention" => array(4, 3, 2, 0, 1, 1, 1, 1, 0),
            "Non-System Domains" => array(1, 1, 1, 1, 1))            
        );

        for ($section = 0; $section < 5; $section++)
        {
            // Initialize question count per content section
            $evalCount = array("Musculoskeletal" => 0,
            "Neuromuscular" => 0,
            "CardioPulmonary" => 0,
            "Integumentary" => 0,
            "Metabolic and Endocrine" => 0,
            "GI" => 0,
            "GU" => 0,
            "Lymphatic" => 0,
            "System Interactions" => 0);
            $examCount = array("Musculoskeletal" => 0,
            "Neuromuscular" => 0,
            "CardioPulmonary" => 0,
            "Integumentary" => 0,
            "Metabolic and Endocrine" => 0,
            "GI" => 0,
            "GU" => 0,
            "Lymphatic" => 0,
            "System Interactions" => 0);
    
            $interCount = array("Musculoskeletal" => 0,
            "Neuromuscular" => 0,
            "CardioPulmonary" => 0,
            "Integumentary" => 0,
            "Metabolic and Endocrine" => 0,
            "GI" => 0,
            "GU" => 0,
            "Lymphatic" => 0,
            "System Interactions" => 0);
    
            $nsdCount = array("Assistive Devices" => 0,
            "Therapeutic Modalities" => 0,
            "Safety & Protection" => 0,
            "Professional Responsibilities" => 0,
            "Research & EBP" => 0);
            
            $experimentalCount = 0;
    
            for ($i = $section * 50; $i < ($section * 50) + 50; $i++)
            {
                $id = $questions[$i];
                $queryStr = "SELECT system, content_section, content_subsection, is_experimental FROM Questions_Staging WHERE id={$id}";
                $rows = $db->select($queryStr);
                $rows = $rows[0]; // Get first row
                        
                if ($rows["is_experimental"])
                {
                    $experimentalCount++;
                }
                else
                {
                    switch ($rows["content_section"])
                    {
                        case "Evaluation":
                        $evalCount[$rows["system"]]++;
                        break;
                        case "Examination":
                        $examCount[$rows["system"]]++;
                        break;
                        case "Intervention":
                        $interCount[$rows["system"]]++;
                        break;
                        case "Non System Domains":
                        $nsdCount[$rows["content_subsection"]]++;
                        break;                
                    }   
                }
        
            }

            $evalCount = array_values($evalCount);
            $examCount = array_values($examCount);
            $interCount = array_values($interCount);
            $nsdCount = array_values($nsdCount);
            
            foreach ($sectionsRequired[$section]["Evaluation"] as $k => $numSystemReq)
            {
                $this->assertEquals($numSystemReq, $evalCount[$k]);
            }

            foreach ($sectionsRequired[$section]["Examination"] as $k => $numSystemReq)
            {
                $this->assertEquals($numSystemReq, $examCount[$k]);
            }

            foreach ($sectionsRequired[$section]["Intervention"] as $k => $numSystemReq)
            {
                $this->assertEquals($numSystemReq, $interCount[$k]);
            }

            foreach ($sectionsRequired[$section]["Non-System Domains"] as $k => $numSystemReq)
            {
                $this->assertEquals($numSystemReq, $nsdCount[$k]);
            }

            $this->assertEquals(10, $experimentalCount);
        }            
    }

    // Test returning the same question set in random order for subsequent
    // attempts of a particular exam.
    // Here we pull the first exam ID from the exams table in the database.
    public function testShuffleQuestions()
    {
        $db = new Db();

		// Get list of question id's from exam entry
		$queryStr = "SELECT questions FROM " . EXAMS_TABLE . " WHERE id = 1";
        $rows = $db->select($queryStr);
        $rows = $rows[0]; // Get first row
        $firstAttemptQuestions = json_decode($rows["questions"]);
        
        // Get shuffled questions
        $questions = generateExamQuestions($db, 1, 1);

        // The two question sets must be identical
        $this->assertCount(0, array_diff($questions, $firstAttemptQuestions));
    }
}
