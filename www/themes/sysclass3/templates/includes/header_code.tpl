{literal}
<style type="text/css">
.menu .menu-dropdown-subtitle a {
/*  font-weight: bold; */
}
.menu-dropdown-subtitle-selected {

}
div.menu-dropdown-subitem {
    margin: 0 0 0 2%;
    display: none;
    width: 98%;
    float: left;
}

.menu div.menu-dropdown-subitem a:hover {
    color: #666666 !important;
}
</style>
{/literal}
<!-- Header -->
<header id="header">
<!--
	<h1 id="logo">
		<a href="#" title="SysClass">SysClass</a>
	</h1>
-->

	<!-- Menu -->
	
<div class="pagetop">
    <div class="head pagesize">
        <div class="pagetop">
            <div class="head pagesize">
                <div class="head_top">
                    <!-- Logo -->
                    <div class="logo clear">
                        <!--
                        <h1 id="logo">					    
                            <a href="#" title="SysClass">SysClass</a>
                        </h1>
                        -->
                        <a href = "{if $smarty.session.s_login}{$smarty.server.PHP_SELF}{else}index.php{/if}">
                            <img 
                                src="themes/sysclass3/images/logo_ult.png" 
                                class="picture" 
                                title="{$T_CONFIGURATION.site_name}" 
                                alt="{$T_CONFIGURATION.site_name}" 
                                border="0"
                            />
                        </a>
                    </div>
                    <!-- /end logo -->
                </div>
                {if $smarty.session.s_type != "administrator" || $smarty.get.ctg != ''}
                <div class="menu dropdown_menu">
        			<ul class="clear" id="top-menu">
        				<li id="top-menu-home-link">
        					<a href="{$smarty.const.G_SERVERNAME}{$smarty.session.s_type}.php">Home</a>
        				</li>
        				
        				{foreach name = 'outer_menu' key = 'menu_key' item = 'menu' from = $T_MENU}
        						<li>
        							<a 
        								href="{if $menu.link}{$menu.link}{else}javascript: void(0);{/if}" 
        								class="{if $menu.options|@count > 0}has_dropdown{/if}">
        								{* IMAGE *}
        								{$menu.title|sC_truncate:30}
        							</a>
        							{if $menu.options}
        							<ul class="dropdown ui-accordion ui-widget ui-helper-reset" role="tablist"  id="mag_list_menu{$menu_key}">
        								{foreach name = 'options_list' key = 'option_id' item = 'option' from = $menu.options}
        									{if isset($option.html)}
        										<li id="{$option.id}" class="ui-accordion-li-fix {$option.class}" {if $menu_key == 1 && $smarty.session.s_type != "administrator"}name="lessonSpecific"{/if}>{$option.html}</li>
        									{else}
        										<li id="{$option.id}" class = "ui-accordion-li-fix {$option.class}">
        											<a href = "{$option.link}" title="{$option.title}">{$option.title}</a>
        											
        											{if $option.subitens}
        												{foreach name = 'suboptions_list' key = 'suboption_id' item = 'suboption' from = $option.subitens}
        													<div id="{$suboption.id}" class = "menu-dropdown-subitem {$suboption.class}">
        														<a href = "{$suboption.link}" title="{$suboption.title}">{$suboption.title}</a>
        													</div>
        												{/foreach}
        											{/if}
        											
        										</li>
        									{/if}
        								{/foreach}
        							</ul>
        							{/if}
        						</li>
        				{/foreach}
        				<!-- 
        				{*if $T_BAR_ADDITIONAL_ACCOUNTS|@count > 0*}
        				<li>
        					<a href="javascript: void(0); ">{$smarty.const._SWITCHACCOUNT}</a>
        					<ul class="dropdown">
        						{*foreach name = 'additional_accounts' item = "item" key = "key" from = $T_BAR_ADDITIONAL_ACCOUNTS*}
        							<li><a href="javascript: changeAccount('{*$item.login*}');">#filter:login-{*item.login*}#</a></li>
        						{*/foreach*}
        					</ul>
        				</li>
        				{*/if*}
        				 -->
        				<!-- 
        				<li>
        					<a href="javascript: void(0); ">{$smarty.const._MODULES}</a>
        					<ul class="dropdown">
        						{foreach item = "item" key = "key" from = $T_BAR_ADDITIONAL_COURSE_LESSONS_MENU_TOP}
        							<li><a href="{$item.href}">{$item.name}</a></li>
        						{/foreach}
        					</ul>
        				</li>
        				 -->
        			</ul>					
				</div>
                {/if}
			</div>
		</div>
    </div>
</div>
	<!-- /end Menu -->


	<!-- BreadCrumb -->
	<!--<section id="breadcrumb">
		<p>Você está em: <a href="#" title="Home">Home &raquo; </a><a href="#" title="Configurações">Configurações &raquo; </a></p>
	</section>-->
	<!-- /end breadcrumb -->
	
            <div id="dialog-avatar" title="{$smarty.const._MYPROFILE} - {$smarty.const._CHANGEAVATAR}" style="display:none">
                  
                   <fieldset class = "fieldsetSeparator">
                    avatar.php
                       {$T_AVATAR_FORM.javascript}
                           <form {$T_AVATAR_FORM.attributes}>
                            {$T_AVATAR_FORM.hidden}
                            <table class = "formElements">
                         
                             <tr><td class = "labelCell">{$smarty.const._CURRENTAVATAR}:&nbsp;</td>
                              <td class = "elementCell">
                              <img src = "view_file.php?file={$T_CURRENT_USER_AVATAR.avatar}" title="{$smarty.const.T_CURRENTAVATAR}" alt="{$smarty.const._CURRENTAVATAR}" {if isset($T_NEWWIDTH)} width = "{$T_NEWWIDTH}" height = "{$T_NEWHEIGHT}"{/if} /></td></tr>
                            {if !isset($T_CURRENT_USER->coreAccess.users) || $T_CURRENT_USER->coreAccess.users == 'change'}
                             <tr><td class = "labelCell">{$T_AVATAR_FORM.delete_avatar.label}:&nbsp;</td>
                              <td class = "elementCell">{$T_AVATAR_FORM.delete_avatar.html}</td></tr>
                             <tr><td class = "labelCell">{$T_AVATAR_FORM.file_upload.label}:&nbsp;</td>
                              <td class = "elementCell">{$T_AVATAR_FORM.file_upload.html}</td></tr>
                             
                             <tr><td></td>
                              <td class = "elementCell">{$T_AVATAR_FORM.submit_upload_file.html}</td></tr>
                            {/if}
                            </table>
                           </form>
                   </fieldset>
                  
            </div>

	
	{*if !$hide_path}
		<div class="breadcrumb" id="breadcrumb">
			<div class="bread-links pagesize">
				<ul class="clear">
					<li class="first">{$smarty.const.__YOUAREIN_}</li>
					<li>{$title|sC_formatTitlePath}</li>
				</ul>
			</div>
		</div>
	{/if*}

	{if $T_CONFIGURATION.updater_period}<script> var updaterPeriod = '{$T_CONFIGURATION.updater_period}';</script>{else}<script>var updaterPeriod = 100000;</script>{/if}
</header>
<!-- /end Header -->