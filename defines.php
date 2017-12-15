<?php

$isStaging = true; // flip to switch environment

if ($isStaging)
{
    define ('EXAMS_TABLE', "Exams_Staging");
    define ('EXAM_SURVEY_TABLE', "Exam_Survey_Staging");
    define ('QUESTIONS_TABLE', "Questions_Staging");
}
// Production
else
{
    define ('EXAMS_TABLE', "Exams");
    define ('EXAM_SURVEY_TABLE', "Exam_Survey");
    define ('QUESTIONS_TABLE', "Questions");
}

define ('PASSING_SCORE', 154);
define ('FEEDBACK_NONE', 0);
define ('FEEDBACK_NOT_REALISTIC', 1);
define ('FEEDBACK_NOT_FAIR', 2);
define ('FEEDBACK_NOT_REALISTIC_OR_FAIR', 3);

define ('GREEN_PASSING_SCORE', 185);
define ('YELLOW_PASSING_SCORE', 170);
define('COURSE_GUIDANCE_SCORE_LIMIT', 170);
define('BLANK_DIFFICULTY_LIMIT', 25);

define('QUESTIONS_PER_SECTION', 50);
define('FOCUS_LOST', "It’s normal to lose focus, you’ll want to be sure you have a strategy in place to regain your focus and not lose a lot of time. If you found yourself having to re­read a question or felt you were getting tired, close your eyes for a moment and clear your mind. When you return to the question, focus on what the question is asking you to be able to pick your answer. Think about other strategies you can use when you are finding your focus is not on there.");
define('FOCUS_NOT_LOST', "Great that you didn’t lose focus during your practice exam simulation. You’ll want to continue to keep up your mental and physical endurance to treat these patient questions. Keep up the good work and continue to do well on your next attempt.");
define('GOOD_MINDSET', "With a good and positive mindset, you learn more from what you have done right and where you can improve for the next exam. With this exam score feedback report, you are now able to narrow your preparation focus areas.");
define('NOT_GOOD_MINDSET', "When you don’t have a good or positive mindset going in, you hurt your chances of a great score. Mistakes are made when your mind is not truly focused and causes more negative feelings than necessary. When taking this practice exam, you want to present yourself as the best clinician you can be as if you were performing evaluations on real patients. For the next practice exam, you’ll want to be sure you are ready to face these patients of yours as if you were actually treating them and not looking at just exam questions.");
define('TIME_SCALE_ONE', "The amount of time with no breaks you have per section is an hour or 72 seconds per question.");
define('TIME_SCALE_ONE_AND_HALF', "The amount of time with no breaks you have per section is an hour and a half or 108 seconds per question.");
define('TIME_SCALE_TWO', "The amount of time with no breaks you have per section is two hours or 144 seconds per question.");
define('PASS_NUMBERS', serialize( array(
			1 => 31,
			2 => 30,
			3 => 31,
			4 => 31,
			5 => 31
		)
	)
);

define('V1_PASS_NUMBERS', serialize( array(
			'Evaluation' => array(
				'Musculoskeletal' => 15,
				'Neuromuscular' => 12,
				'CardioPulmonary' => 9,
				'Other Systems' => 15,
				1 => 10,
				2 => 10,
				3 => 10,
				4 => 10,
				5 => 11
			),
			'Examination' => array(
				'Musculoskeletal' => 17,
				'Neuromuscular' => 13,
				'CardioPulmonary' => 7,
				'Other Systems' => 3,
				1 => 8,
				2 => 7,
				3 => 9,
				4 => 8,
				5 => 8
			),
			'Intervention' => array(
				'Musculoskeletal' => 16,
				'Neuromuscular' => 14,
				'CardioPulmonary' => 9,
				'Other Systems' => 5,
				1 => 10,
				2 => 8,
				3 => 9,
				4 => 8,
				5 => 9
			),
			'Musculoskeletal' => array(
				1 => 9,
				2 => 8,
				3 => 9,
				4 => 11,
				5 => 11,
				'overall' => 48
			),
			'Neuromuscular' => array(
				1 => 8,
				2 => 7,
				3 => 8,
				4 => 8,
				5 => 8,
				'overall' => 39
			),
			'CardioPulmonary' => array(
				1 => 6,
				2 => 6,
				3 => 5,
				4 => 4,
				5 => 4,
				'overall' => 25
			),
			'Non System Domains' => array(
				'Musculoskeletal' => 6,
				'Neuromuscular' => 9,
				'CardioPulmonary' => 3,
				'Other Systems' => 1,
				'Assistive Devices' => 4,
				'Therapeutic Modalities' => 6,
				'Safety & Protection' => 4,
				'Professional Responsibilities' => 3,
				'Research & EBP' => 2,
				1 => 4,
				2 => 4,
				3 => 2,
				4 => 5,
				5 => 4,
				'overall' => 23
			),
			'Other Systems' => array(
				1 => 5,
				2 => 5,
				3 => 5,
				4 => 4,
				5 => 4,
				'overall' => 23
			)
		)
	)
);

define('V2_PASS_NUMBERS', serialize( array(
			'Evaluation' => array(
				'Musculoskeletal' => 17,
				'Neuromuscular' => 13,
				'CardioPulmonary' => 7,
				'Integumentary' => 3,
				'Metabolic and Endocrine' => 3,
				'GI' => 2,
				'GU' => 2,
				'Lymphatic' => 2,
				'System Interactions' => 8,
				1 => 13,
				2 => 12,
				3 => 10,
				4 => 12,
				5 => 10
			),
			'Examination' => array(
				'Musculoskeletal' => 14,
				'Neuromuscular' => 12,
				'CardioPulmonary' => 6,
				'Integumentary' => 2,
				'Metabolic and Endocrine' => 0,
				'GI' => 1,
				'GU' => 1,
				'Lymphatic' => 1,
				'System Interactions' => 0,
				1 => 6,
				2 => 8,
				3 => 8,
				4 => 8,
				5 => 7
			),
			'Intervention' => array(
				'Musculoskeletal' => 15,
				'Neuromuscular' => 13,
				'CardioPulmonary' => 7,
				'Integumentary' => 3,
				'Metabolic and Endocrine' => 2,
				'GI' => 1,
				'GU' => 1,
				'Lymphatic' => 1,
				'System Interactions' => 0,
				1 => 8,
				2 => 7,
				3 => 10,
				4 => 8,
				5 => 10
			),
			'Musculoskeletal' => array(
				1 => 9,
				2 => 9,
				3 => 8,
				4 => 10,
				5 => 10,
				'overall' => 46
			),
			'Neuromuscular' => array(
				1 => 8,
				2 => 7,
				3 => 8,
				4 => 7,
				5 => 8,
				'overall' => 38
			),
			'CardioPulmonary' => array(
				1 => 4,
				2 => 4,
				3 => 4,
				4 => 4,
				5 => 4,
				'overall' => 20
			),
			'Integumentary' => array(
				1 => 2,
				2 => 1,
				3 => 2,
				4 => 1,
				5 => 2,
				'overall' => 8
			),
			'Metabolic and Endocrine' => array(
				1 => 1,
				2 => 1,
				3 => 1,
				4 => 1,
				5 => 1,
				'overall' => 5
			),
			'GI' => array(
				1 => 1,
				2 => 0,
				3 => 1,
				4 => 1,
				5 => 1,
				'overall' => 4
			),
			'GU' => array(
				1 => 1,
				2 => 1,
				3 => 1,
				4 => 0,
				5 => 1,
				'overall' => 4
			),
			'Lymphatic' => array(
				1 => 1,
				2 => 0,
				3 => 1,
				4 => 1,
				5 => 1,
				'overall' => 4
			),
			'System Interactions' => array(
				1 => 2,
				2 => 1,
				3 => 2,
				4 => 1,
				5 => 2,
				'overall' => 8
			),
			'Non System Domains' => array(
				'Assistive Devices' => 4,
				'Therapeutic Modalities' => 5,
				'Safety & Protection' => 4,
				'Professional Responsibilities' => 3,
				'Research & EBP' => 3,
				1 => 4,
				2 => 4,
				3 => 4,
				4 => 3,
				5 => 4
			)
		)
	)
);

?>