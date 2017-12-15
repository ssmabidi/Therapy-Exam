(function ( $ ) {
    $.fn.feedback = function(success, fail) {
    	self=$(this);
		self.find('.dropdown-menu-form').on('click', function(e){e.stopPropagation()})

		self.find('.screenshot').on('click', function(){
			self.find('.cam').removeClass('fa-camera fa-check').addClass('fa-refresh fa-spin');
			html2canvas($(document.body), {
				onrendered: function(canvas) {
					self.find('.screen-uri').val(canvas.toDataURL("image/png"));
					self.find('.cam').removeClass('fa-refresh fa-spin').addClass('fa-check');
				}
			});
		});

		self.find('.do-close').on('click', function(){
			self.find('.dropdown-toggle').dropdown('toggle');
			self.find('.reported, .failed').hide();
			self.find('.report').show(); 
			self.find('.cam').removeClass('fa-check').addClass('fa-camera');
		    self.find('.screen-uri').val('');
		    self.find('textarea').val('');
		});

		failed = function(){
			self.find('.loading').hide();
			self.find('.failed').show();
			if(fail) fail();
		}

		self.find('form').on('submit', function(){
			self.find('.report').hide();
			self.find('.loading').show();
			var comment = $(this).find('textarea[name="comment"]').val();
			
			var iframe = $('#fb-iframe-member-practice-exams_page0');
			var username = $('#username', iframe.contents()).text();
			var qid = $('#question_id', iframe.contents()).val();
			
			var data = {'qid': qid, 'user': username, 'comment': comment};
			$.post( $(this).attr('action'), data, null, 'json').done(function(res){
				if(res.result == 'success'){
					self.find('.loading').hide();
					self.find('.reported').show();
					if(success) success();
				} else failed();
			}).fail(function(){
				failed();
			});
			return false;
		});
	};
}( jQuery ));

jQuery(document).ready(function ($) {
	$('.feedback').feedback();
	
	$('body, iframe').click(function(){
		$('.dropdown-menu').hide();
	});
	
	$('.close-x').click(function(){
		$('.dropdown-menu').hide();
	});
	
	$('.do-close').click(function(){
		$(this).parents('.reported, .failed').eq(0).hide();
		$('.feedback .btn.dropdown-toggle').click();
	});
	
	$('.feedback .btn-primary.dropdown-toggle').click(function(e){
		e.preventDefault();
		e.stopPropagation();
		$(this).siblings('.dropdown-menu').toggle(0);
	});
});