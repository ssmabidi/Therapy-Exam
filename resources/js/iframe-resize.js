jQuery(document).ready(function($){
	var iframes = {};
	$('.rackforms-iframe').each(function(){
		var id = $(this).attr('id');
		iframes[id] = [0, $(this)];
		$(this).load(function(){
			var id = $(this).attr('id');
			var hght = iframes[id][1].contents().find('html').height();
			iframes[id][1].height(hght);
			iframes[id][0] = hght;
		});
	});
	
	function iframe_resize_check(){
		for (var l in iframes) {
			try{
				var hght = iframes[l][1].contents().find('html').height();
				if(hght != iframes[l][0]){
					iframes[l][0] = hght;
					iframes[l][1].height(hght);
				}
			}catch(e){
				console.log(e);
			}
		}
	}
	$(window).resize(iframe_resize_check);
	setInterval(iframe_resize_check, 700);
});