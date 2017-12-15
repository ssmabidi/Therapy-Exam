
$(document).ready(function(){
	var dpr     = window.devicePixelRatio;
	//alert(dpr);
	var reviewMarkBtn;
	var ReviewIncompleteBtn;
	var reviewMarkBtnFlag = 0;
	var ReviewIncompleteBtnFlag = 0;

	$("#Previous").click(function(){
		// if($(this).attr("disabled"))
		// 	$(this).addClass("disabled");
		goToTopForSmallerDevice();
	});
	
	$("#Next").click(function(){
		// $("#Previous").removeClass("disabled");
		/*
			remove style attr from previous btn in order to remove grey color (mobile color issue)
		 */
		$("#Previous").removeAttr('style');
		checkTwoButtons();
		setReviewBtnWidths();
		goToTopForSmallerDevice();
	});
	$("#Review").click(function(){
		$("#Previous").removeClass('disabled');
		checkTwoButtons();
		setReviewBtnWidths();
		goToTopForSmallerDevice();

		// remove disabled from previous button
		// $("#Previous").attr('disabled',false);
		
	});
	function checkTwoButtons(){
		reviewMarkBtn = $("#ReviewMarked")[0].style.display;
		ReviewIncompleteBtn = $("#ReviewIncomplete")[0].style.display;
		
		if(reviewMarkBtn != "none" ){
			reviewMarkBtnFlag = 1; 
		}else{
			reviewMarkBtnFlag = 0;
		}
		if(ReviewIncompleteBtn != "none" ){
			ReviewIncompleteBtnFlag = 1; 
		}else{
			ReviewIncompleteBtnFlag = 0;
		}
	}
	function setReviewBtnWidths(){
		
		if(reviewMarkBtnFlag == 0){
			$("#fb_fld-ReviewMarked").css('display','none');
			
		}
		if(ReviewIncompleteBtnFlag == 0){
			$("#fb_fld-ReviewIncomplete").css("display","none");
		}
		if(reviewMarkBtnFlag == 0 && ReviewIncompleteBtnFlag == 0){
			$("#fb_fld-PreviousSummary").css("width","50%");
			$("#fb_fld-NextSummary").css("width","50%");
			$("#NavSummary").css({'width':'70%', 'margin':'auto'});
		}
		if(reviewMarkBtnFlag == 0 && ReviewIncompleteBtnFlag == 1){
			$("#fb_fld-PreviousSummary").css("width","33.33%");
			$("#fb_fld-NextSummary").css("width","33.33%");
			$("#fb_fld-ReviewIncomplete").css("width","33.33%");
			$("#fb_fld-ReviewIncomplete").css("display","");
			$("#NavSummary").css('width', '100%');
		}
		if(reviewMarkBtnFlag == 1 && ReviewIncompleteBtnFlag == 0){
			$("#fb_fld-PreviousSummary").css("width","33.33%");
			$("#fb_fld-NextSummary").css("width","33.33%");
			$("#fb_fld-ReviewMarked").css("width","33.33%");
			$("#fb_fld-ReviewMarked").css('display','');
			$("#NavSummary").css('width', '100%');
		}
		if(reviewMarkBtnFlag == 1 && ReviewIncompleteBtnFlag == 1){
			$("#fb_fld-PreviousSummary").css("width","25%");
			$("#fb_fld-NextSummary").css("width","25%");
			$("#fb_fld-ReviewMarked").css("width","25%");
			$("#fb_fld-ReviewIncomplete").css("width","25%");
			$("#fb_fld-ReviewMarked").css('display','');
			$("#fb_fld-ReviewIncomplete").css("display","");
			$("#NavSummary").css('width', '100%');
		}
		

		
	}
	function setWidths(){
		var reviewBtnDisplay = $("#Review")[0].style.display;
		// if reivew btn is visible, set width to 25% each
		if(reviewBtnDisplay != "none"){
			$("#fb_fld-Previous").css("width","25%");
			$("#fb_fld-Mark").css("width","25%");
			$("#fb_fld-Next").css("width","25%");
			$("#fb_fld-Review").css("width","25%");
		}
		else{
			$("#fb_fld-Previous").css("width","33.33%");
			$("#fb_fld-Mark").css("width","33.33%");
			$("#fb_fld-Next").css("width","33.33%");
		}
	}

	function goToTopForSmallerDevice(){
		window_width = $(window).width();
		// updated code... go to top in all devices
		if(window_width >= 1){
				$("html, body").animate({ scrollTop: 0 }, 100);
		}
	}
	var isMobile = {
	    Android: function() {
	        return navigator.userAgent.match(/Android/i);
	    },
	    BlackBerry: function() {
	        return navigator.userAgent.match(/BlackBerry/i);
	    },
	    iOS: function() {
	        return navigator.userAgent.match(/iPhone|iPad|iPod/i);
	    },
	    Opera: function() {
	        return navigator.userAgent.match(/Opera Mini/i);
	    },
	    Windows: function() {
	        return navigator.userAgent.match(/IEMobile/i);
	    },
	    any: function() {
	        return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
	    }
	};

	$("#PreviousSummary").click(function(){
		
		setWidths();
		goToTopForSmallerDevice();
	});
	$("#ReviewMarked").click(function(){
		/* ------ if ReviewMarked is clicked, it shows marked questions and 1st Q should
					have previous disabled
					----------------*/
		// $("#Previous").attr('disabled',true);
		// $("#Previous").addClass('disabled');
		setWidths();
		goToTopForSmallerDevice();
	});
	$("#ReviewIncomplete").click(function(){
		// $("#Previous").addClass('disabled');
		setWidths();
		goToTopForSmallerDevice();
	});
	$("#NextSummary").click(function(){
		$("#fb_fld-ReviewIntermission").css('width','50%');
		goToTopForSmallerDevice();
		
		
	});
	$("#ReviewIntermission").click(function(){
		goToTopForSmallerDevice();
	});
	$("#NextIntermission").click(function(){
		setWidths();
		
		var ReviewIntermission = $("#ReviewIntermission")[0].style.display;

		// if review btn is not displayed (scheduled break). next btn should take full width
		if(ReviewIntermission=="none"){	
			$("#fb_fld-NextIntermission").css('width','100%');
			
		}else{
			$("#fb_fld-NextIntermission").css('width','50%');
			
		}
		goToTopForSmallerDevice();
	});
	$(document.body).on('click', '.marked-question' ,function(){
	   
		setWidths();
		goToTopForSmallerDevice();
	});
	$(document.body).on('click', '.unmarked-question' ,function(){
	   
		setWidths();
		goToTopForSmallerDevice();
	});
/*	setTimeout(function(){
		// $("#NavQuestion").css("visibility","initial").hide();
		var reviewBtnDisplay = $("#Review")[0].style.display;

		if(reviewBtnDisplay != "none"){
			setWidths();
		}
		// $("#NavQuestion").fadeIn(1000);
		checkTwoButtons();
		setReviewBtnWidths();

	},4000);*/
	var previousClass;
	$('#questionDisplay').bind('DOMSubtreeModified', function(){
		setTimeout(function(){
			setWidths();
			checkTwoButtons();
			setReviewBtnWidths();
			
			/* 
					MOBILE COLOR ISSUE
			check if Previous btn has disabled class
			if yes, make color grey
			it is changed in mobile because of touchstart and touchend, however disabled
			*/
			previousClass = $("#Previous").attr('class').split(" ");
			for (var i = 0; i < previousClass.length; i++) {
          		if(previousClass[i]=="disabled"){
          			//console.log('disabled found');
          			$("#Previous").css('background-color','#bdc3c7');
          			break;
          		}else{
          			//$("#Previous").removeAttr('style');
          			
          			$("#Previous").css('background-color','#2c81ba');
          		}
        	}


		},50);
	});

	$('span#sectionDisplay').bind('DOMSubtreeModified', function(){
		setTimeout(function(){
			
			$("#fb_fld-ReviewIntermission").css('width','50%');
			var ReviewIntermission = $("#ReviewIntermission")[0].style.display;
			
			// if review btn is not displayed (scheduled break). next btn should take full width
			if(ReviewIntermission=="none"){	
				$("#fb_fld-NextIntermission").css('width','100%');
			
			}else{
				$("#fb_fld-NextIntermission").css('width','50%');
			}

		},50);
	});

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

	$(document.body).on('touchstart', '.unmarked-question' ,function(){
		$(this).css('background-color','#cacfd2');
	});
	$(document.body).on('touchend', '.unmarked-question' ,function(){
		$(this).css('background-color','#bdc3c7');
	});

	$(document.body).on('touchstart', '.marked-question' ,function(){
		$(this).css('background-color','#F9D441 !important');
	});
	$(document.body).on('touchend', '.marked-question' ,function(){
		$(this).css('background-color','#f1c40f !important');
	});


});


/*
plugin for remove specific style
 (function($)
{
    $.fn.removeStyle = function(style)
    {
        var search = new RegExp(style + '[^;]+;?', 'g');

        return this.each(function()
        {
            $(this).attr('style', function(i, style)
            {
                return style.replace(search, '');
            });
        });
    };
}(jQuery));
*/