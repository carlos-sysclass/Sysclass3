{if !$smarty.get.popup && !$T_POPUP_MODE && $smarty.server.PHP_SELF|@basename != 'index.php'}
	<div id="wrapper">
		<div id="topbar" class="main_container container_16 clearfix">
			{include file = "includes/header_code.tpl"}
		</div>
		<div id="main_container" class="main_container container_16 clearfix ui-sortable">
			<!--  MENU  -->
			<div class="clearfix  round_top grid_10" id="nav_top">
				{include file = "includes/nav_top_bar.tpl"}
			</div>	
			<!--  END: MENU  -->
				
			<!-- BREADCRUMBS -->
		 	{if !$hide_path}
		 		<ul class="grad_colour" id="breadcrumb">{$title|sC_formatTitlePath}</ul>
		 	{/if}
	
				
			<!-- SEARCH BAR -->
			{if $smarty.session.s_login}
			<!-- 
				<div class="grid_6" id="search_bar">
					{if $smarty.session.s_type == 'administrator'}
					<form style="margin:0;padding:0;" action = "{$smarty.const.G_SERVERNAME}{$smarty.session.s_type}.php?ctg=control_panel&op=search" method = "post">
					{else}
					<form style="margin:0;padding:0;" action = "{$smarty.const.G_SERVERNAME}{$smarty.session.s_type}.php?ctg=lessons&op=search" method = "post">
					{/if}
						<input 
							type="text" 
							value = "{if isset($smarty.post.search_text)}{$smarty.post.search_text}{else}{$smarty.const._SEARCH}...{/if}"
							name="search_text" 
							class="indent round_all"
							onclick="if(this.value=='{$smarty.const._SEARCH}...')this.value='';" 
							onblur="if(this.value=='')this.value='{$smarty.const._SEARCH}...';"
						>
						<div class="place_search">
							<button class="button_colour round_all">
								<img width="24" height="24" alt="Magnifying Glass" src="/themes/sysclass/images/icons/small/white/magnifying_glass.png">
								<span>Search</span>
							</button>
						</div>     
						<input type = "hidden" name = "current_location" id = "current_location" value = ""/>
					</form>
				</div>
			-->
			{/if}
			<!-- END: SEARCH BAR -->
			
			<!-- LANGUAGE SELECTION -->
			{*$smarty.capture.header_language_code*}
			<!-- END: LANGUAGE SELECTION -->
		
			{$smarty.capture.center_code}
		</div>
		{include file = "includes/footer_code.tpl"}
	</div>
{else}
	{$smarty.capture.center_code}
{/if}
