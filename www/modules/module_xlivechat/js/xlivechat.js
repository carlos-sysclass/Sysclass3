var windowFocus = true;
var username;
var chatHeartbeatCount = 0;
var minChatHeartbeat = 1000;
var maxChatHeartbeat = 33000;
var chatHeartbeatTime = 5000;
var originalTitle;
var blinkOrder = 0;

var chatboxFocus = new Array();
var newMessages = new Array();
var newMessagesWin = new Array();
var chatBoxes = new Array();
var chatIsStarted = false;

jQuery(document).ready(function(){
	originalTitle = document.title;
	if (typeof(startChatSystem) == 'boolean' && startChatSystem) {
		startChatSession();
		chatIsStarted = true;
	}
	

	jQuery([window, document]).blur(function(){
		windowFocus = false;
	}).focus(function(){
		windowFocus = true;
		document.title = originalTitle;
	});
	
});

function restructureChatBoxes() {
	align = 0;
	for (x in chatBoxes) {
		chatboxtitle = chatBoxes[x];

		if (jQuery("#chatbox_"+chatboxtitle).css('display') != 'none') {
			if (align == 0) {
				jQuery("#chatbox_"+chatboxtitle).css('right', '20px');
			} else {
				width = (align)*(225+7)+20;
				jQuery("#chatbox_"+chatboxtitle).css('right', width+'px');
			}
			align++;
		}
	}
}

function chatWith(chatuser) {
	if (!chatIsStarted) {
		startChatSession();	
		chatIsStarted = true;
	}
	
	
	jQuery(".xlivechat_conversa_title").text(chatuser);	
	createChatBox(chatuser, true);
	jQuery("#chatbox_"+chatuser+" .chatboxtextarea").focus();
}

function chatWithRetorn(chatuser) {
	if (!chatIsStarted) {
		startChatSession();	
		chatIsStarted = true;
	}
	
	jQuery(".xlivechat_conversa_title").text(chatuser);
	jQuery("#queue_"+chatuser).css('color', 'green');
	jQuery("#xlivechat_conversa_user").load("administrator.php?ctg=module&op=module_xlivechat&action=xlivechat_messagens",{usersuport:chatuser});
	
}

function substitutePeriodForUndelineOrUnderscore(value) {
	return new String(value).replace(/\./g, "_");
}

function createChatBox(chatboxtitle,minimizeChatBox) {
	
	var sanitizeChatboxtitle = substitutePeriodForUndelineOrUnderscore(chatboxtitle);
	
	if (jQuery("#chatbox_"+sanitizeChatboxtitle).length > 0) {
		if (jQuery("#chatbox_"+chatboxtitle).css('display') == 'none') {
			jQuery("#chatbox_"+sanitizeChatboxtitle).css('display','block');
			restructureChatBoxes();
		}
		jQuery("#chatbox_"+sanitizeChatboxtitle+" .chatboxtextarea").focus();
		return;
	}
	
	jQuery(" <div />" ).attr("id","chatbox_"+sanitizeChatboxtitle)
	.addClass("chatbox")
	.html('<div class="chatboxhead" onclick="javascript:toggleChatBoxGrowth(\''+chatboxtitle+'\')" ><div class="chatboxtitle">'+chatboxtitle+'</div><div class="chatboxoptions"><a href="javascript:void(0)" onclick="javascript:toggleChatBoxGrowth(\''+chatboxtitle+'\')">-</a> <a href="javascript:void(0)" onclick="javascript:closeChatBox(\''+chatboxtitle+'\')">X</a></div><br clear="all"/></div><div class="chatboxcontent"></div><div class="chatboxinput"><textarea class="chatboxtextarea" onkeydown="javascript:return checkChatBoxInputKey(event,this,\''+chatboxtitle+'\');"></textarea></div>')
	.appendTo(jQuery("#module_xlivechat_container"));
			   
	jQuery("#chatbox_"+sanitizeChatboxtitle).css('bottom', '0px');
	
	chatBoxeslength = 0;
/*
	for (x in chatBoxes) {
		if (jQuery("#chatbox_"+chatBoxes[x]).css('display') != 'none') {
			chatBoxeslength++;
		}
	}
*/
	if (chatBoxeslength == 0) {
		jQuery("#chatbox_"+sanitizeChatboxtitle).css('right', '20px');
	} else {
		width = (chatBoxeslength)*(225+7)+20;
		jQuery("#chatbox_"+sanitizeChatboxtitle).css('right', width+'px', 'float', 'right');
	}
	
	chatBoxes.push(sanitizeChatboxtitle);

	if (minimizeChatBox == 1) {
		minimizedChatBoxes = new Array();

		if (jQuery.cookie('chatbox_minimized')) {
			minimizedChatBoxes = jQuery.cookie('chatbox_minimized').split(/\|/);
		}
		minimize = 0;
		for (j=0;j<minimizedChatBoxes.length;j++) {
			if (minimizedChatBoxes[j] == sanitizeChatboxtitle) {
				minimize = 1;
			}
		}

		if (minimize == 1) {
			jQuery('#chatbox_'+sanitizeChatboxtitle+' .chatboxcontent').css('display','none');
			jQuery('#chatbox_'+sanitizeChatboxtitle+' .chatboxinput').css('display','none');
		}
	}

	chatboxFocus[sanitizeChatboxtitle] = false;

	jQuery("#chatbox_"+sanitizeChatboxtitle+" .chatboxtextarea").blur(function(){
		chatboxFocus[sanitizeChatboxtitle] = false;
		jQuery("#chatbox_"+sanitizeChatboxtitle+" .chatboxtextarea").removeClass('chatboxtextareaselected');
	}).focus(function(){
		chatboxFocus[sanitizeChatboxtitle] = true;
		newMessages[sanitizeChatboxtitle] = false;
		jQuery('#chatbox_'+sanitizeChatboxtitle+' .chatboxhead').removeClass('chatboxblink');
		jQuery("#chatbox_"+sanitizeChatboxtitle+" .chatboxtextarea").addClass('chatboxtextareaselected');
	});

	jQuery("#chatbox_"+sanitizeChatboxtitle).click(function() {
		if (jQuery('#chatbox_'+sanitizeChatboxtitle+' .chatboxcontent').css('display') != 'none') {
			jQuery("#chatbox_"+sanitizeChatboxtitle+" .chatboxtextarea").focus();
		}
	});

	jQuery("#chatbox_"+sanitizeChatboxtitle).show();
}


function chatHeartbeat(){

	var itemsfound = 0;
	
	if (windowFocus == false) {
 
		var blinkNumber = 0;
		var titleChanged = 0;
		for (x in newMessagesWin) {
			if (newMessagesWin[x] == true) {
				++blinkNumber;
				if (blinkNumber >= blinkOrder) {
					document.title = x+' says...';
					titleChanged = 1;
					break;	
				}
			}
		}
		
		if (titleChanged == 0) {
			document.title = originalTitle;
			blinkOrder = 0;
		} else {
			++blinkOrder;
		}

	} else {
		for (x in newMessagesWin) {
			newMessagesWin[x] = false;
		}
	}

	for (x in newMessages) {
		if (newMessages[x] == true) {
			if (chatboxFocus[x] == false) {
				//FIXME: add toggle all or none policy, otherwise it looks funny
				jQuery('#chatbox_'+x+' .chatboxhead').toggleClass('chatboxblink');
			}
		}
	}
	
	jQuery.ajax({
	  url: "modules/module_xlivechat/chat.php?action=chatheartbeat",
	 
	  cache: false,
	  dataType: "json",
	  complete : function() {
		setTimeout('chatHeartbeat();',chatHeartbeatTime);
	  },
	  success: function(data) {

		jQuery.each(data.items, function(i,item){
			if (item)	{ // fix strange ie bug

				chatboxtitle = item.f;
				
				var sanitizeChatboxtitle = substitutePeriodForUndelineOrUnderscore(chatboxtitle);

				if (jQuery("#chatbox_"+sanitizeChatboxtitle).length <= 0) {
					createChatBox(chatboxtitle);
				}
				if (jQuery("#chatbox_"+sanitizeChatboxtitle).css('display') == 'none') {
					jQuery("#chatbox_"+sanitizeChatboxtitle).css('display','block');
					restructureChatBoxes();
				}
				
				/// CHECK IF CHATCONTENT IS ON BOTTOM
				
				var is_bottom = checkForContentBoxAtBottom(sanitizeChatboxtitle);
				
				if (item.s == 1) {
					item.f = username;
					item.t = username;
				}

				if (item.s == 2) {
					jQuery("#chatbox_"+sanitizeChatboxtitle+" .chatboxcontent").append('<div class="chatboxmessage"><span class="chatboxinfo">'+item.m+'</span></div>');
					jQuery("#xlivechatboxcontent").append('<div class="chatboxmessage"><span class="chatboxinfo">'+item.m+'</span></div>');
				} else {
					newMessages[sanitizeChatboxtitle] = true;
					newMessagesWin[sanitizeChatboxtitle] = true;
					jQuery("#chatbox_"+sanitizeChatboxtitle+" .chatboxcontent").append('<div class="chatboxmessage"><span class="chatboxmessagefrom">'+item.f+':&nbsp;&nbsp;</span><span class="chatboxmessagecontent">'+item.m+'</span></div>');
					jQuery("#xlivechatboxcontent").append('<div class="chatboxmessage"><span class="chatboxmessagefrom">'+item.f+':&nbsp;&nbsp;</span><span class="chatboxmessagecontent">'+item.m+'</span></div>');
				}
				if (is_bottom) {
					scrollContentBoxToBottom(sanitizeChatboxtitle);
				}

				jQuery("#chatbox_"+sanitizeChatboxtitle+" .chatboxcontent").scrollTop(jQuery("#chatbox_"+sanitizeChatboxtitle+" .chatboxcontent").first().scrollHeight);
				itemsfound += 1;
				
				jQuery("#xlivechatboxcontent").scrollTop(jQuery("#chatbox_"+sanitizeChatboxtitle+" .chatboxcontent").first().scrollHeight);
				itemsfound += 1;
			}
		});
		
		chatHeartbeatCount++;
		
		if (itemsfound > 0) {
			chatHeartbeatTime = minChatHeartbeat;
			chatHeartbeatCount = 1;
		} else if (chatHeartbeatCount >= 10) {
			chatHeartbeatTime *= 2;
			chatHeartbeatCount = 1;
			if (chatHeartbeatTime > maxChatHeartbeat) {
				chatHeartbeatTime = maxChatHeartbeat;
			}
		}
	}});
	
	
}

function checkForContentBoxAtBottom(chatboxtitle) {
	var sanitizeChatboxtitle = substitutePeriodForUndelineOrUnderscore(chatboxtitle);
	
	var contentBox = jQuery("#chatbox_"+sanitizeChatboxtitle+" .chatboxcontent");
	if (contentBox[0].scrollHeight - contentBox.scrollTop() <= contentBox.outerHeight()) {
		is_bottom = true;
	} else {
		is_bottom = false;
	}
	
	return is_bottom;
}

function scrollContentBoxToBottom(chatboxtitle) {
	var sanitizeChatboxtitle = substitutePeriodForUndelineOrUnderscore(chatboxtitle);
	var contentBox = jQuery("#chatbox_"+sanitizeChatboxtitle+" .chatboxcontent");
	contentBox.animate({scrollTop: contentBox[0].scrollHeight});
}


function closeChatBox(chatboxtitle) {
	
	var sanitizeChatboxtitle = substitutePeriodForUndelineOrUnderscore(chatboxtitle);
	
	jQuery('#chatbox_'+sanitizeChatboxtitle).css('display','none');
	
	restructureChatBoxes();

	jQuery.post("chat.php?action=closechat", { chatbox: sanitizeChatboxtitle} , function(data){	
	});

}

function toggleChatBoxGrowth(chatboxtitle) {
	
	sanitizeChatboxtitle = substitutePeriodForUndelineOrUnderscore(chatboxtitle);
	
	
	if (jQuery('#chatbox_'+sanitizeChatboxtitle+' .chatboxcontent').css('display') == 'none') {  
		
		var minimizedChatBoxes = new Array();
		
		if (jQuery.cookie('chatbox_minimized')) {
			minimizedChatBoxes = jQuery.cookie('chatbox_minimized').split(/\|/);
		}

		var newCookie = '';

		for (i=0;i<minimizedChatBoxes.length;i++) {
			if (minimizedChatBoxes[i] != sanitizeChatboxtitle) {
				newCookie += sanitizeChatboxtitle+'|';
			}
		}

		newCookie = newCookie.slice(0, -1)


		jQuery.cookie('chatbox_minimized', newCookie);
		jQuery('#chatbox_'+sanitizeChatboxtitle+' .chatboxcontent').css('display','block');
		jQuery('#chatbox_'+sanitizeChatboxtitle+' .chatboxinput').css('display','block');
		jQuery("#chatbox_"+sanitizeChatboxtitle+" .chatboxcontent").scrollTop(jQuery("#chatbox_"+chatboxtitle+" .chatboxcontent").first().scrollHeight);
	} else {
		
		var newCookie = sanitizeChatboxtitle;

		if (jQuery.cookie('chatbox_minimized')) {
			newCookie += '|'+jQuery.cookie('chatbox_minimized');
		}


		jQuery.cookie('chatbox_minimized',newCookie);
		jQuery('#chatbox_'+sanitizeChatboxtitle+' .chatboxcontent').css('display','none');
		jQuery('#chatbox_'+sanitizeChatboxtitle+' .chatboxinput').css('display','none');
	}
	
}

function checkChatBoxInputKey(event,chatboxtextarea,chatboxtitle) {
	
	var sanitizeChatboxtitle = substitutePeriodForUndelineOrUnderscore(chatboxtitle);
	 
	if(event.keyCode == 13 && event.shiftKey == 0)  {
		message = jQuery(chatboxtextarea).val();
		message = message.replace(/^\s+|\s+$/g,"");
		jQuery(chatboxtextarea).val('');
		jQuery(chatboxtextarea).focus();
		jQuery(chatboxtextarea).css('height','44px');
		if (message != '') {
			jQuery.post("/modules/module_xlivechat/chat.php?action=sendchat", {to: chatboxtitle, message: message} , function(data){
				var is_bottom = checkForContentBoxAtBottom(sanitizeChatboxtitle);
				
				message = message.replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/\"/g,"&quot;");
				jQuery("#chatbox_"+sanitizeChatboxtitle+" .chatboxcontent").append('<div class="chatboxmessage"><span class="chatboxmessagefrom">'+username+':&nbsp;&nbsp;</span><span class="chatboxmessagecontent">'+message+'</span></div>');
				jQuery("#chatbox_"+sanitizeChatboxtitle+" .chatboxcontent").scrollTop(jQuery("#chatbox_"+sanitizeChatboxtitle+" .chatboxcontent").last().scrollHeight);
				
				//if (is_bottom) {
					scrollContentBoxToBottom(sanitizeChatboxtitle);
				//}
			});
		}
		chatHeartbeatTime = minChatHeartbeat;
		chatHeartbeatCount = 1;

		return false;
	}

	var adjustedHeight = chatboxtextarea.clientHeight;
	var maxHeight = 94;

	if (maxHeight > adjustedHeight) {
		adjustedHeight = Math.max(chatboxtextarea.scrollHeight, adjustedHeight);
		if (maxHeight)
			adjustedHeight = Math.min(maxHeight, adjustedHeight);
		if (adjustedHeight > chatboxtextarea.clientHeight)
			jQuery(chatboxtextarea).css('height',adjustedHeight+8 +'px');
	} else {
		jQuery(chatboxtextarea).css('overflow','auto');
	}
	 
}

function startChatSession(){  
	jQuery.ajax({
	  url: "modules/module_xlivechat/chat.php?action=startchatsession",
	  cache: false,
	  dataType: "json",
	  success: function(data) {
 
		username = data.username;

		jQuery.each(data.items, function(i,item){
			if (item)	{ // fix strange ie bug

				chatboxtitle = item.f;
				
				sanitizeChatboxtitle = substitutePeriodForUndelineOrUnderscore(chatboxtitle);

				if (jQuery("#chatbox_"+sanitizeChatboxtitle).length <= 0) {
					createChatBox(chatboxtitle,1);
				}
				
				if (item.s == 1) {
					item.f = username;
					item.t = username;
				}

				if (item.s == 2) {
					jQuery("#chatbox_"+sanitizeChatboxtitle+" .chatboxcontent").append('<div class="chatboxmessage"><span class="chatboxinfo">'+item.m+'</span></div>');
				} else {
					jQuery("#chatbox_"+sanitizeChatboxtitle+" .chatboxcontent").append('<div class="chatboxmessage"><span class="chatboxmessagefrom">'+item.f+':&nbsp;&nbsp;</span><span class="chatboxmessagecontent">'+item.m+'</span></div>');
				}
			}
		});
		
		for (i=0;i<chatBoxes.length;i++) {
			chatboxtitle = chatBoxes[i];
			jQuery("#chatbox_"+sanitizeChatboxtitle+" .chatboxcontent").scrollTop(
				jQuery("#chatbox_"+sanitizeChatboxtitle+" .chatboxcontent").first().scrollHeight
			);
			setTimeout('jQuery("#chatbox_"+sanitizeChatboxtitle+" .chatboxcontent").scrollTop(jQuery("#chatbox_"+sanitizeChatboxtitle+" .chatboxcontent").first().scrollHeight);', 100); // yet another strange ie bug
		}
	
		setTimeout('chatHeartbeat();',chatHeartbeatTime);
		
	}});
}

/**
 * Cookie plugin
 *
 * Copyright (c) 2006 Klaus Hartl (stilbuero.de)
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 */

jQuery.cookie = function(name, value, options) {
    if (typeof value != 'undefined') { // name and value given, set cookie
        options = options || {};
        if (value === null) {
            value = '';
            options.expires = -1;
        }
        var expires = '';
        if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
            var date;
            if (typeof options.expires == 'number') {
                date = new Date();
                date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
            } else {
                date = options.expires;
            }
            expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
        }
        // CAUTION: Needed to parenthesize options.path and options.domain
        // in the following expressions, otherwise they evaluate to undefined
        // in the packed version for some reason...
        var path = options.path ? '; path=' + (options.path) : '';
        var domain = options.domain ? '; domain=' + (options.domain) : '';
        var secure = options.secure ? '; secure' : '';
        document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
    } else { // only name given, get cookie
        var cookieValue = null;
        if (document.cookie && document.cookie != '') {
            var cookies = document.cookie.split(';');
            for (var i = 0; i < cookies.length; i++) {
                var cookie = jQuery.trim(cookies[i]);
                // Does this cookie string begin with the name we want?
                if (cookie.substring(0, name.length + 1) == (name + '=')) {
                    cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                    break;
                }
            }
        }
        return cookieValue;
    }
};
