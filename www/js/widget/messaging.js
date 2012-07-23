(function(jQuery) {
	jQuery.extend({
		messaging : {
			show : function (structMessage) {
				if (structMessage.message_type == 'success') {
					$result = this.success(structMessage.message);
				} else {
					$result = this.error(structMessage.message);
				}
				var scrollTopIndex = 0;
				if (jQuery(".messageBlock").size() > 0) {
					scrollTopIndex = jQuery(".messageBlock").offset().top;	
				}
				scrollTopIndex = scrollTopIndex - jQuery("header#header").height() - 10;
				
				if (jQuery(window).scrollTop() > scrollTopIndex) {
					jQuery(window).scrollTop(scrollTopIndex);
				}
				return $result;
			},
			success : function(text) {
				
				messageHtml = 
					'<div id="messageBlock" class="grid_299 message messageSuccess messageBlock">' + 
		        	'	<span class="messageInner">' +
		        	'		<img class="sprite32 sprite32-success" src="themes/default/images/others/transparent.gif"> <strong>sucesso</strong>' +
		        	'	</span> ' +
		        	'	<span>' + text + '</span>' +
		        	'</div>';
				
				if (jQuery(".messageBlock").size() == 0) {
					jQuery("header#header").after(messageHtml);
				} else {
					jQuery(".messageBlock").replaceWith(messageHtml);
					
				}
			},
			error : function(text) {
				messageHtml = 
					'<div id="messageBlock" class="grid_24 message messageFailure messageBlock">' + 
		        	'	<span class="messageInner">' +
		        	'		<img class="sprite32 sprite32-warning" src="themes/default/images/others/transparent.gif"> <strong>Aviso</strong>' +
		        	'	</span> ' +
		        	'	<span>' + text + '</span>' +
		        	'</div>';
			
				if (jQuery(".messageBlock").size() == 0) {
					jQuery("header#header").after(messageHtml);
				} else {
					jQuery(".messageBlock").replaceWith(messageHtml);
				}
			}
		}
	});
})(jQuery);