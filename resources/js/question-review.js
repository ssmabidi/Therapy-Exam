jQuery(document).ready(function($){
    $('#Review_Incorrect').click(function(e){
            e.preventDefault();
            var exam_id = $('#exam_id').val();
            location.href = 'question.php?exam='+exam_id+'&id=&type=1';
        });
        $('#Review_Correct').click(function(e){
            e.preventDefault();
            var exam_id = $('#exam_id').val();
            location.href = 'question.php?exam='+exam_id+'&id=&type=2';
        });
        $('#Review_All').click(function(e){
            e.preventDefault();
            var exam_id = $('#exam_id').val();
            location.href = 'question.php?exam='+exam_id+'&id=&type=0';
        });
        
        $('#fb_fld-Back input').click(function(e){
            e.preventDefault();
            history.back();
        });
        
        // if($('.question-controls').size() > 0){
            $('html, body', window.parent.document).animate({ scrollTop: 0}, 1);
        // }
        
        $('.question-controls a').click(function(){
            $('html, body', window.parent.document).animate({ scrollTop: 0}, 100);
        });
		
	$('body').click(function(){
		$('.dropdown-menu', window.parent.document).hide();
	});
});