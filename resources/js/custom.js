function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
} 

function openVideoPopup(url){
	var $ = jQuery;
	$('#video .embed-container').html('<script id="am-video-1" src="'+url+'"></script>');
	$('#video-activator').click();
}
	
jQuery(document).ready(function($){
	$('#fb-iframe-member-practice-exams_page0').load(function(){
		var iframe = $('.rackforms-iframe').contents();
		
		if($('.review-image').size() == 0){
			$('body').prepend('<a href="https://therapyexamprep.com/wp-content/themes/trades-child/images/Review-3.jpg" class="review-image"></a>');
			$(".review-image").fancybox(); 
		}
		iframe.find('body').on('click', '.fancyboxable', function(e){
			e.preventDefault();
			parent.jQuery('.review-image').click();
		});
		$('.fancyboxable').fancybox({padding: [2,2,2,2]});
		
		iframe.find('body').on('click', ".alert .close", function(e){
			e.preventDefault();
			var exam = iframe.find('body .question').data('id');
			var attempt = iframe.find('body .question').data('attempt');
			setCookie('closedalert-'+exam+'-'+attempt, '1', 500);
			$(this).parents('.alert').fadeOut(200);
		});
	});
	
	$('.fbx-instance').on('foobox.close', function(e) {
		$('#video .embed-container video')[0].pause();
	});

});