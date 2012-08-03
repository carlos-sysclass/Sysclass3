jQuery(document).ready(function() {
	
	jQuery('.sectionOption').find('nav ul li:nth-child(4n+0)').addClass('lastOptionSection');
	jQuery('.sectionOption:nth-child(2n+0)').addClass('lastSection');
	
	jQuery('#changeAccount').click(function(){
		jQuery('#showAccountsContainer').filter(':not(:animated)').fadeToggle();
		return false;
	});
	
	jQuery('#openChatList').click(function(){
		jQuery('#showChatUsersContainer').filter(':not(:animated)').fadeToggle();
		return false;
	});
	
	if (jQuery('#changeAccountBtn').size() > 0) {
		
		jQuery('#showAccountsContainer').css({
			'left': (jQuery('#changeAccountBtn').offset().left - 303 ) + 'px'
		});
	}
	
	if (jQuery('#openChatListBtn').size() > 0) {
		jQuery('#showChatUsersContainer').css({
			'left': (jQuery('#openChatListBtn').offset().left - 303 ) + 'px'
		});
	}
});
