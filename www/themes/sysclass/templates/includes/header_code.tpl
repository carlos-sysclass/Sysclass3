 <!--  MENU  -->
<div class="grid_8">
	<a href="index.php" class="logo"><span>Magester</span></a>
</div>

{if $smarty.session.s_login}
<div class="grid_3 prefix_5"  style="margin-right: 0;">
		<div class="user_box round_all" style="margin-bottom: 10px;">
			{if isset($T_AVATAR)}
		    	<img src = "{if isset($T_ABSOLUTE_AVATAR_PATH)}{$T_AVATAR}{else}view_file.php?file={$T_AVATAR}{/if}" border = "0" title="{$smarty.const._GOTODASHBOARD}" alt="{$smarty.const._GOTODASHBOARD}" width = "55" />
			{else}
		     	<img src = "{$smarty.const.G_SYSTEMAVATARSURL}unknown_small.png" border = "0" title="{$smarty.const._MAGESTERNAME}" alt="{$smarty.const._MAGESTERNAME}" width = "55" />
			{/if}
			<h2>{$T_CURRENT_USER->user.user_type}</h2>
			<h3><a class="text_shadow" href="{$smarty.session.s_type}.php?{if $smarty.session.s_type == "administrator"}ctg=users&edit_user={$smarty.session.s_login}{else}ctg=personal{/if}">{$T_CURRENT_USER->user.name} {$T_CURRENT_USER->user.surname}</a></h3>
			<ul>
				<li><a href="#">profile</a><span class="divider">|</span></li>
				<li><a href="#">settings</a><span class="divider">|</span></li>
				<li><a href="index.php?logout=true">{$smarty.const._LOGOUT}</a></li>
			</ul>
		</div><!-- #user_box -->
		<form id="search_side"><input type="text" onclick="value=''" value="Search..." class="round_all"></form>
</div>
{/if}            
            
<!-- 
 
 <div id = "logo">
  <a href = "{if $smarty.session.s_login}{$smarty.server.PHP_SELF}{else}index.php{/if}">
  	<img src = "{$T_LOGO}" title = "{$T_CONFIGURATION.site_name}" alt = "{$T_CONFIGURATION.site_name}" border = "0">
	</a>
 </div>
 
  -->
 
 
 {if $smarty.session.s_login}
 <!-- 
 <div id = "logout_link" style = "float:right;margin-top:5px" align="right">
  {* Merged header with mobile horizontal interface *}
  {if $T_THEME_SETTINGS->options.sidebar_interface != 0}
   {* First row *}

   {if isset($T_ONLINE_USERS_LIST)} <script> var startUpdater = true; </script>{else}<script> var startUpdater = false; </script>{/if}
   {if $T_CONFIGURATION.updater_period}<script> var updaterPeriod = '{$T_CONFIGURATION.updater_period}';</script>{else}<script>var updaterPeriod = 100000;</script>{/if}

   {if isset($T_ONLINE_USERS_LIST) && !$T_CONFIGURATION.disable_online_users}
   {if $T_ONLINE_USERS_COUNT}<span id = "online_users_display" class = "headerText" >{$smarty.const._ONLINEUSERS}&nbsp;({$T_ONLINE_USERS_COUNT})</span><span class = "headerText">&nbsp;|</span>{/if}
   {/if}

   {* Logout *}
  {elseif $smarty.server.PHP_SELF|basename == 'index.php'}
  
   <span class = "headerText">{$smarty.const._YOUARECURRENTLYLOGGEDINAS}: </span><a href = "{$smarty.session.s_type}page.php?dashboard={$smarty.session.s_login}" class = "headerText">#filter:login-{$smarty.session.s_login}#</a>
   <a href = "index.php?logout=true" class = "headerText">({$smarty.const._LOGOUT})</a>
  {/if}
  {if $T_THEME_SETTINGS->options.sidebar_interface != 0 && $T_HEADER_CLASS == 'header'}{$smarty.capture.t_path_additional_code}{/if}
 </div>
  -->

 {/if}			
 
 
 {if $T_CONFIGURATION.motto_on_header == 1}
 <!-- 
  <div id = "info">
   <div id = "site_name" class= "headerText">{$T_CONFIGURATION.site_name}</div>
   <div id = "site_motto" class= "headerText">{$T_CONFIGURATION.site_motto}</div>
  </div>
  
	<a class="logo" href="index.html"><span>Adminica Pro II</span></a>
   -->
 {/if}