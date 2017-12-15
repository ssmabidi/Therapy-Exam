
$(document).ready(function(){
	
	/* =================  code for touch and release of buttons =========================*/

	$("input[type=button]").on('touchstart', function () {
		$(this).css('background-color','#3096DA');
	});
	$("input[type=button]").on('touchend', function () {
		$(this).css('background-color','#2c81ba');
	});

	$("#ReviewMarked").on('touchstart', function () {
		$(this).css('background-color','#F9D441 !important');
	});
	$("#ReviewMarked").on('touchend', function () {
		$(this).css('background-color','#f1c40f !important');
	});


});
