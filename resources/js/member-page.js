function showModal(e) {
	e.stopPropagation();
	parent.swal("Extended Time", "Checking this box will extend the practice exam timer by 1.5x or 2x for a total of 7.5 to 10 hours repsectively.\n\nOnly select this option if you will be eligible to use this same time accomodation on the actual exam.");
}
jQuery(document).ready(function($){
	var popup_already_displayed = false;
	$('body').on('click', '#extendedCheck1', function(e){
		if(!popup_already_displayed && $(this).is(':checked')){
			popup_already_displayed = true;
			showModal(e);
		}
	});
	
	$('a.video-link, .video-link a').click(function(e){
		e.preventDefault();
		var url = $(this).attr('href');
		window.parent.jQuery('.play-video-link').click();
	});
	
	if(localStorage.getItem('ExamState') !== null){
		var examState = localStorage.getItem('ExamState');
		if(examState) examState = jQuery.parseJSON(examState);
		var id = examState.id;
		$('.in-progress').each(function(){
			if($(this).data('id') == id){
				var exam = $(this).data('exam');
				var h2 = $(this).parents('.exam').find('h2');
				h2_text = h2.text();
				// 'https://therapyexamprep.com/rackforms/output/forms/practiceexam/page1.php?exam='+exam+'&attempt='+id+'';
				//h2.html('<a class="window_target" target="_blank" href="https://therapyexamprep.com/rackforms/output/forms/practiceexam/page0.php">'+h2_text+'</a>');
				h2.html('<a class="window_target" target="_blank" href="https://therapyexamprep.com/products/practice-exam/client/practiceexam/page0.php">'+h2_text+'</a>');
			}
		});
		$('.exams').on('click', '.window_target', function(e){
			e.preventDefault();
			var href = $(this).attr('href');
			window.open(href, 'Form Page', "scrollbars=yes,resizable=yes,width=927,height=869,left=160,top=170");
		})
	}
	
	$('#Submit').click(function(e){
		//window.open('https://therapyexamprep.com/rackforms/output/forms/practiceexam-tutorial/page0.php', '_blank', 'width=927,height=869,left=160,top=170');
		window.open('https://therapyexamprep.com/products/practice-exam/client/practiceexam-tutorial/page0.php', '_blank', 'width=927,height=869,left=160,top=170');
	});
	
	$('html, body', window.parent.document).animate({ scrollTop: 0}, 1);
});