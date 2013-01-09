jQuery(document).ready(function() {
	
	jQuery('.sectionOption').find('nav ul li:nth-child(4n+0)').addClass('lastOptionSection');
	jQuery('.sectionOption:nth-child(2n+0)').addClass('lastSection');
	
	jQuery('#changeAccount').click(function(){
		jQuery('#showAccountsContainer').filter(':not(:animated)').fadeToggle();
		return false;
	});
/*	
#	jQuery('#openChatList').click(function(){
#		jQuery('#showChatUsersContainer').css("visibility", "visible").filter(':not(:animated)').fadeToggle();
#		return false;
#	});
*/
	jQuery('#openChatListBtn').click(function(){
                jQuery('#showChatUsersContainer').css("visibility", "visible").filter(':not(:animated)').fadeToggle();
                return false;
        });

	jQuery("#showAccountsContainer").position({
		of: jQuery( "#changeAccountBtn" ),
		my: "right top",
		at: "right bottom",
		offset: "8 8"
	}).hide().css("visibility", "visible");
	
	jQuery("#showChatUsersContainer").show().position({
		of: jQuery( "#openChatListBtn" ),
		my: "right top",
		at: "right bottom",
		offset: "8 8"
	}).hide();
	jQuery("#showChatUsersContainer").css("visibility", "visible");
});
