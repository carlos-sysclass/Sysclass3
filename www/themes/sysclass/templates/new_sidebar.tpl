{include file = "includes/header.tpl"}

<!-- <script language = "JavaScript" type = "text/javascript" src="js/jquery/.1.5.2.min"></script>  -->
<script language = "JavaScript" type = "text/javascript" src="js/menu/simpla.js"></script>


<script language = "JavaScript" type = "text/javascript">

    // Translations used in the sidebar.js script
    var translations = new Array();
    translations['lessons'] = '{$smarty.const._LESSONS}';
    translations['servername'] = '{$smarty.const.G_SERVERNAME}';
    translations['onlineusers'] = '{$smarty.const._ONLINEUSERS}';
    translations['nousersinroom'] = '{$smarty.const._THEREARENOOTHERUSERSRIGHTNOWINTHISROOM}';
    translations['redirectedtomain']= '{$smarty.const._REDIRECTEDTOMAGESTERMAIN}';
    translations['chatroomdeleted'] = '{$smarty.const._CHATROOMDELETEDBYOWNER}';
    translations['s_type'] = '{$smarty.session.s_type}';
    translations['s_login'] = '{$smarty.session.s_login}';
    translations['clicktochange'] = '{$smarty.const._CLICKTOCHANGESTATUS}';
    translations['userisonline'] = '{$smarty.const._USERISONLINE}';
    translations['and'] = '{$smarty.const._AND}';
    translations['hours'] = '{$smarty.const._HOURS}';
    translations['minutes'] = '{$smarty.const._MINUTES}';
    translations['userjustloggedin']= '{$smarty.const._USERJUSTLOGGEDIN}';
    translations['user'] = '{$smarty.const._USER}';
    translations['sendmessage'] = '{$smarty.const._SENDMESSAGE}';
    translations['web'] = '{$smarty.const._WEB}';
	 translations['user_stats'] = '{$smarty.const._USERSTATISTICS}';
	 translations['user_settings'] = '{$smarty.const._USERPROFILE}';
	 translations['logout_user'] = '{$smarty.const._LOGOUTUSER}';

    // Global variables
    var menuCount = '{$T_MENUCOUNT}'; // How many menus are initially loaded?
    var browser = '{$T_BROWSER}';
    var active_id = '{$T_ACTIVE_ID}'; // What is the id of the menu item that should be set as activated (gray background)
    var activeMenu = '{$T_ACTIVE_MENU}'; // What is the active menu? (active_id exists within that menu)
    var setActiveMenu = 0; // Has the active menu been explicitly set by the mainFrame - behave differently in fixUpperMenu

    // Chat related
    var chatroomIntervalId = 0; // This id relates to the periodical functionality of updating the active chat room, if the chat tab is open
    var chatactivityIntervalId = 0; // This id relates to the periodical functionality of checking for any chat activity, if the chat tab is closed
    var chatOptionIsEnabled = '{$T_CHATENABLED}'; // Is the chat feature enabled in the system in general?
    var chatEnabled = 0; // This global variable is used to denote whether the chat menu is currently open or not
    var onlyViewChat = '{$T_ONLY_VIEW_CHAT}'; // User is only allowed to view chat window, not write on it
    var chat_listmenu = -1; // Global variable to denote the listmenu element of the chat tab - used to hide/show that menu

    // Facebook related
    {if $T_OPEN_FACEBOOK_SESSION}
    var facebook_api_key = "{$T_FACEBOOK_API_KEY}";
    var facebook_should_update_status = "{$T_SHOULD_UPDATE_STATUS}";
    {else}
    var facebook_api_key = 0;
    var facebook_should_update_status = 0;
    {/if}
    var __shouldTriggerNextNotifications = false;

    // Get unread messages

 {if !$T_NO_MESSAGES}var startUpdater = true;{else}var startUpdater = false;{/if}
 {if $T_CONFIGURATION.updater_period}var updaterPeriod = '{$T_CONFIGURATION.updater_period}';{else}var updaterPeriod = 100000;{/if}

    var arrow_status = "down"; // Initialize toggle arrows

    {if $T_BROWSER == 'IE6' || $T_BROWSER == 'IE7'}
     var table_style_size = "90%";
    {else}
     var table_style_size = "100%";
    {/if}

    var chatRoomDoesNotExistError = "{$smarty.const._CHATROOMDOESNOTEXIST_ERROR}";
    var chatRoomIsNotEnabled = "{$smarty.const._CHATROOMISNOTENABLED_ERROR}";
    var redicrectedToMagesterMain = "{$smarty.const._REDIRECTEDTOMAGESTERMAIN}";
    var chatRoomHasBeenDeactivated = "{$smarty.const._CHATROOMHASBEENDEACTIVATED}";


</script>

<body class = "sidebar" >
    <span id = "nobookmarks" style = "display:none">{$smarty.const._YOUHAVENOBOOKMARKS}</span>

    {math assign='T_SB_WIDTH_MINUS_ONE' equation="x-1" x=$T_SIDEBARWIDTH}
<!--     <div id="loading_sidebar" class="loading" style="opacity: 0.9; height: 100%; width: {$T_SB_WIDTH_MINUS_ONE}px; display: block;" ><div style="top: 50%; left:12%; position: absolute;" ><img src="images/others/progress1.gif" style="vertical-align: middle;"/><span style="vertical-align: middle;">{$smarty.const._LOADINGDATA}</span></div></div>  -->
    <div id="loading_sidebar" class="loading" style="opacity: 0.9; height: 100%; width: 229px; display: block;" ><div style="top: 50%; left:12%; position: absolute;" ><img src="images/others/progress1.gif" style="vertical-align: middle;"/><span style="vertical-align: middle;">{$smarty.const._LOADINGDATA}</span></div></div>

    {* Top menu with photo and name - Hiding on click *}
    <div class = "tabmenu" id = "tabmenu" align="center">
        {* Spacer from top *}
        <table><tr height="10px"><td></td></tr></table>

        {* Photo *}
        <div class = "topPhoto" id = "topPhoto" style="height:{$T_NEWHEIGHT}px">
            <a href = "{if $smarty.session.s_type == "administrator"}administrator.php?ctg=users&edit_user={$smarty.session.s_login}{else}{$smarty.session.s_type}.php?ctg=personal{/if}" target = "mainframe">
            {*<a href = "{$smarty.session.s_type}.php?ctg=social&op=dashboard" target = "mainframe">*}
            {if isset($T_AVATAR)}
                <img src = "{if isset($T_ABSOLUTE_AVATAR_PATH)}{$T_AVATAR}{else}view_file.php?file={$T_AVATAR}{/if}" border = "0" title="{$smarty.const._GOTODASHBOARD}" alt="{$smarty.const._GOTODASHBOARD}"
                {if isset($T_NEWWIDTH)} width = "{$T_NEWWIDTH}" height = "{$T_NEWHEIGHT}"{/if} />
            {else}
                <img src = "{$smarty.const.G_SYSTEMAVATARSURL}unknown_small.png" border = "0" title="{$smarty.const._MAGESTERNAME}" alt="{$smarty.const._MAGESTERNAME}" />
            {/if}
            </a>
        </div>

        <div id = "personIdentity">
			<div class="infosignout">
			   	{$smarty.const._WELCOME}, <span>{$smarty.session.s_login}</span> | <a href = "{$smarty.const.G_SERVERNAME}index.php?logout=true" target = "_top">{$smarty.const._LOGOUT}</a>
			</div>
			{if !$T_NO_PERSONAL_MESSAGES}
			<div class="infomessages">
					{if $T_UNREAD_MESSAGES != 0}
						<img src = "images/16x16/mail.{$globalImageExtension}" style="vertical-align:middle; border:0; 'float': left;" title="{$smarty.const._MESSAGES}" alt="{$smarty.const._MESSAGES}" />
						&nbsp;
						<a href = "{$smarty.session.s_type}.php?ctg=messages" target="mainframe">
							{$T_UNREAD_MESSAGES_TEXT}
						</a>
						
					{/if}
				
				<!--
					{if $T_UNREAD_MESSAGES != 0}
						(<a href = "{$smarty.session.s_type}.php?ctg=messages" target="mainframe">{$T_UNREAD_MESSAGES}</a>)
					{/if}
				 -->
				
			</div>
			{/if}
        </div>
        
        
        
        {* Search div *}
        <div>
            {if $smarty.session.s_type == 'administrator'}
                <form style="display: inline;" action = "{$smarty.const.G_SERVERNAME}{$smarty.session.s_type}.php?ctg=control_panel&op=search" method = "post" target="mainframe">
            {else}
                <form style="display: inline;" action = "{$smarty.const.G_SERVERNAME}{$smarty.session.s_type}.php?ctg=lessons&op=search" method = "post" target="mainframe">
            {/if}
            <div class="sidesearch">
                    <div id="search_suggest"></div>
                    <input class = "searchBox" type="text" name="search_text"
                        value = "{if isset($smarty.post.search_text)}{$smarty.post.search_text}{else}{$smarty.const._SEARCH}...{/if}"
                        onclick="if(this.value=='{$smarty.const._SEARCH}...')this.value='';" onblur="if(this.value=='')this.value='{$smarty.const._SEARCH}...';" /> <!-- width:134px;-->
                    <input type = "hidden" name = "current_location" id = "current_location" value = ""/>
            </div>
                </form>
        </div>
    </div>
    
    
    
    
    
	<div id="sidebar">
		<div id="sidebar-wrapper">
			<ul id="main-nav">
		        {*********}
		        {* MENUS *}
		        {*********}
				{foreach name = 'outer_menu' key = 'menu_key' item = 'menu' from = $T_MENU}
				<li><a class="tabHeader nav-top-item" href="javascript: void(0); " style="padding-right: 15px;">{$menu.title|sC_truncate:30}</a>
					<ul style="display: none;" class="menuList" id="mag_list_menu{$menu_key}">
						{foreach name = 'options_list' key = 'option_id' item = 'option' from = $menu.options}
		                    {if isset($option.html)}
		                        <li class = "menuOption" {if $menu_key == 1 && $smarty.session.s_type != "administrator"}name="lessonSpecific"{/if}>{$option.html}</li>
		                    {else}
								<li class = "menuOption">
									<a href = "{$option.link}" title="{$option.title}" target="{$option.target}">{$option.title|sC_truncate:25}</a>
								</li>
							{/if}
						{/foreach}
					</ul>
				</li>
				{/foreach}
				{if isset($T_BAR_ADDITIONAL_ACCOUNTS)}
					<li><a class="tabHeader nav-top-item" href="#" style="padding-right: 15px;">{$smarty.const._SWITCHTO}</a>
						<ul style="display: none;" class="menuList" id="mag_list_menu_switchtool">
							{foreach name = 'additional_accounts' item = "item" key = "key" from = $T_BAR_ADDITIONAL_ACCOUNTS}
								<li class = "menuOption">
									<a href="{$item.login}" class="switchAccountLink" style=float: right;">{$item.login} - <i>{Mg_template_printUsersType user_type=$item.user_type}</i></a>
								</li>
							{/foreach}
						</ul>
					</li>
				{/if}
				<li>
					<!-- http://www.LiveZilla.net Chat Button Link Code -->
					<!--  AO INCLUIR A IMAGEM, COLOCAR A CLASSE "tabChat" no link (a) -->
					<a class="tabHeader nav-top-item tabChat" onclick="javascript: window.open('http://www.ult.com.br/tutoria/chat.php','','width=590,height=550,left=0,top=0,resizable=yes,menubar=no,location=no,status=yes,scrollbars=yes');">
						<img
							src="http://www.ult.com.br/tutoria/image.php?id=01&amp;type=inlay" 
							width="30"
							height="30" 
							border="0" 
							alt="Tutoria" />
						Tutoria
					</a>
				</li>
			</ul>
		</div>
	</div>
    
    
    {* Basic menu called "menu" includes all other menus in successive order: menu1 (always), menu2,..., menuN, logout (always) *}
    <div class = "menu" id = "menu" style="">
        {*********}
        {* MENUS *}
        {*********}
        
        {$T_CHATENABLED}
        
    {*********************************}
    {* NEXT MENU : CHAT TAB *}
    {*********************************}
    {if $T_CHATENABLED == 1}
    	{* PRINT LESSON USERS LIST, PROFESSOR ON FIRST *} 
    {/if}
    </div>
    <input type ="hidden" id = "online_users_text" value="{$smarty.const._ONLINEUSERS}&nbsp;&nbsp;" class ="tabmenu{$T_MENUCOUNT}" />
    
    {*
    <div id="utility_images" style="visibility:visible">
        <img id = "toggleSidebarImage" src = "images/others/blank.gif" onClick = "toggleSidebar('{$smarty.session.s_login}');checkSidebarMode('{$smarty.session.s_login}');" style = "position: absolute; top:4px; right: -1px; cursor: pointer; " align = "right" alt = "{$smarty.const._SHOWHIDE}" title = "{$smarty.const._SHOWHIDE}"/>
    </div>
    *}
<script type = "text/javascript" src = "js/scripts.php?load={$T_HEADER_LOAD_SCRIPTS}"> </script>
    <!--<script type = "text/javascript" src = "jsslashfiles/menu.js"></script> There is no that file any more....Why?-->
    <div id="dimmer" class = "dimmerDiv" style="display:none;"></div>
    <script>
    if (parent.frames[0] && parent.frames[0].document.getElementById('dimmer')) parent.frames[0].document.getElementById('dimmer').style.display = 'none'
    </script>
    <input type="hidden" value="myhidden" id="hasLoaded" />
{literal}
    <script type = "text/javascript">
        initSidebar({/literal}'{$smarty.session.s_login}'{literal}); //initialization of sidebar according to cookie value
  setMenuPositions();
        //$('userInfo').setStyle({left: -($('nameSurname').positionedOffset().left) + "px"});
        var maximumFramewidth = $('tabmenu').getWidth()-30;
        //$('userInfo').setStyle({width: (maximumFramewidth < 0 ? 0 : maximumFramewidth) + "px"});
  if (top.mainframe && top.mainframe.category) {
         arr = top.mainframe.category.split("&");
         setActiveId(arr[0], arr[1], arr[2], arr[3], arr[4], arr[5], "{/literal}{$smarty.session.s_type}{literal}");
     }
    </script>
{/literal}
</body>
</html>
