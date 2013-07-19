{if $smarty.session.s_type == ""}
{else}
	<!-- Barra Topo Black -->
	<div class="barra-topo">
		<div class="menutop container_24">
			<div class="menutop-over" id="menutop-over" >
                <img src="view_file.php?file={$T_CURRENT_USER_AVATAR.avatar}" 
                     title="{$smarty.const._CURRENTAVATAR}" 
                     alt="{$smarty.const._CURRENTAVATAR}" 
                     width="{$T_CURRENT_USER_AVATAR.width}" 
                     height="{$T_CURRENT_USER_AVATAR.height}"
                     style="margin-top: 10px; "
                     id="avatar"
               	/>
				<span class="user-detail" > 
					<span class="name ">{$smarty.const.__WELCOME} {$T_CURRENT_USER->user.name} {$T_CURRENT_USER->user.surname}</span><br /> 
					<span class="text">
						{if $T_CURRENT_USER->coreAccess.dashboard != 'hidden'}
							{$smarty.const.__LOGGEDINAS} 
							<a href = "{$smarty.session.s_type}.php?{if $smarty.session.s_type == "administrator"}ctg=users&edit_user={$smarty.session.s_login}{else}ctg=personal{/if}" class="headerText">
								{$T_CURRENT_USER_TYPE}
							</a>
						{else}
							{$smarty.const.__LOGGEDINAS} {$T_CURRENT_USER_TYPE}
						{/if}
					</span>
				</span> 
				<div class="icon-over-menu">
					{if !$T_NO_PERSONAL_MESSAGES}
					{if $T_UNREAD_MESSAGES > 0}
					<span>
						{$smarty.const.__YOUHAVE} 
						<a href = "{$smarty.session.s_type}.php?ctg=messages">
							{if $T_UNREAD_MESSAGES > 1}
							<button class="inputo-top">
								<img src="themes/sysclass3/images/icon-msg2.png" alt="{$smarty.const.__NEWMESSAGES}" title="{$smarty.const.__YOUHAVE} {$smarty.const.__NEWMESSAGE}">
							</button>
							{else}
							<button class="inputo-top">
								<img src="themes/sysclass3/images/icon-msg2.png" alt="{$smarty.const.__NEWMESSAGES}" title="{$smarty.const.__YOUHAVE} {$smarty.const.__NEWMESSAGES}">
							</button>
							{/if}
						</a>
					</span>
					<span>
						<a href = "{$smarty.session.s_type}.php?ctg=messages">
							<img src="themes/sysclass3/images/icon-msg2.png" alt="{$smarty.const.__NEWMESSAGES}" title="{$smarty.const.__YOUDONTHAVE} {$smarty.const.__NEWMESSAGES}">
						</a>
					</span>
					{else}
					<a href = "{$smarty.session.s_type}.php?ctg=messages">
						<button class="inputo-top-perfil">
							<img src="themes/sysclass3/images/icons/msg.png" alt="{$smarty.const.__NEWMESSAGES}" title="{$smarty.const.__YOUDONTHAVE} {$smarty.const.__NEWMESSAGES}">
						</button>
					</a>
					{/if} 
					{/if}
				</div>
				{if $T_BAR_ADDITIONAL_ACCOUNTS|@count > 0}
					<a href="#" title="{$smarty.const._CHANGE_ACCOUNT} " id="changeAccount">
						<button class="inputo-top-change-account" type="button" id="changeAccountBtn" style="color: #fff;">
							<img  src="images/others/transparent.png" alt="Acessar como" title="{$smarty.const._CHANGE_ACCOUNT} " />
						</button>
					</a>
					<div class="topMenuItensContainer" id="showAccountsContainer">
						<span class="setaShowAccounts"></span>
						<p class="altAcessoTitle">{$smarty.const._CHANGE_ACCOUNT}</p>
						<ul class="dropdown">
							{foreach name = 'additional_accounts' item = "item" key = "key" from = $T_BAR_ADDITIONAL_ACCOUNTS}
							<li><a href="javascript: changeAccount('{$item.login}');">#filter:login-{$item.login}#</a></li>
							{/foreach}
						</ul>
					</div>
				{/if}
				<a href="{$smarty.const.G_SERVERNAME}index.php?logout=true" class="inputo-top-logout">
					<img class="inputo-top-logout-icon " src="images/others/transparent.png" alt="{$smarty.const._LOGOUT}" title="{$smarty.const._LOGOUT}">
					<span>{$smarty.const._LOGOUT}</span>
				</a>
				<div class="inputo-top-search">
					<form action = "{$smarty.const.G_SERVERNAME}{$smarty.session.s_type}.php?ctg=control_panel&op=search" method = "post">
						<button name="search_submit" type="submit" class="inputo-top" value="submitted">
							<img class="inputo-top-icon" src="images/others/transparent.png" alt="{$smarty.const._FIND}" title="{$smarty.const._FIND}">
						</button>
						<div class="colorinp-top">
							<input  
								type="text" 
								name="search_text"
								value = "{if isset($smarty.post.search_text)}{$smarty.post.search_text}{else}{$smarty.const._SEARCH}...{/if}"
								onclick="if(this.value=='{$smarty.const._SEARCH}...')this.value='';" onblur="if(this.value=='')this.value='{$smarty.const._SEARCH}...';"
								class = "searchBox" 
								style = ""/>		
							<input type = "hidden" name = "current_location" id = "current_location" value = ""/>					
						</div>
					</form>
				</div>
				{if $smarty.session.s_type == 'student'}
				<div class="inputo-top-cash">
					<a href="{$smarty.const.G_SERVERNAME}{$smarty.session.s_type}.php?ctg=module&op=module_xpay" title="{$smarty.const.__XPAY_VIEW_MY_STATEMENT}">
						<button class="inputo-top-cash" type="button">
							<img class="inputo-top-cash-icon" src="images/others/transparent.png" alt="{$smarty.const.__XPAY_DOPAYMENTS}" title="{$smarty.const.__XPAY_VIEW_MY_STATEMENT}">
						</button>
					</a>
				</div>
				{/if}
				
				<!-- Botão meu perfil ( inicio ) -->
				<a href="{$smarty.const.G_SERVERNAME}{$smarty.session.s_type}.php?ctg=personal" title="{$smarty.const._MYPROFILE}" >
					<button class="inputo-top-perfil" type="button">
						<img class="inputo-top-perfil-icon" src="images/others/transparent.png" alt="{$smarty.const._MYPROFILE}" title="{$smarty.const._MYPROFILE}">
					</button>
				</a>
				<!-- Botão meu perfil ( fim ) -->

				{if $smarty.session.s_type == 'student'}
				<a target="POPUP_FRAME" onclick="sC_js_showDivPopup('{$smarty.const._INFOFORLESSON}', 2)" href="javascript: void(0);" title="{$smarty.const.__XCOURSE_STUDENT_GUIDANCE}" id="xcourse_lesso_info_link">
					<button class="inputo-top-info" type="button">
						<img class="inputo-top-info-icon" src="images/others/transparent.png" alt="{$smarty.const.__XCOURSE_STUDENT_GUIDANCE}" title="{$smarty.const.__XCOURSE_STUDENT_GUIDANCE}">
					</button>
				</a>
				{/if}
				
				<!-- Botão chat ( inicio ) -->
							
				{if $T_XLIVECHAT_IS_ONLINE == 1}
					<!-- 
					<a onclick="javascript:chatWith('suporteult')"href="javascript: void(0);" title="{$smarty.const._MODULE_XLIVECHAT_NAME}">
						<button class="xlivechat_button" type="button">
							<img class="xlivechat-icon" src="images/others/transparent.png" alt="{$smarty.const._MODULE_XLIVECHAT_NAME}" title="{$smarty.const._MODULE_XLIVECHAT_NAME}">
						</button>
					</a>
				 	-->
				
					<a href="javascript: void(0);" title="{$smarty.const._MODULE_XLIVECHAT_NAME}" id="openChatList">
						<button class="xlivechat_button" type="button" id="openChatListBtn">
							<img class="xlivechat-icon" src="images/others/transparent.png" alt="{$smarty.const._MODULE_XLIVECHAT_NAME}" title="{$smarty.const._MODULE_XLIVECHAT_NAME}" />
						</button>
					</a>
					
					<div class="topMenuItensContainer" id="showChatUsersContainer">
						<span class="setaShowAccounts"></span>
						<p class="altAcessoTitle">Chat</p>
						<ul class="dropdown">
							{foreach name = 'additional_accounts' item = "item" key = "login" from = $T_XCHAT_SUPPORT_LIST}
								{if $item.online} 
									<li><a href="javascript: chatWith('{$login}');">{$item.user.name} {$item.user.surname}</a></li>
								{/if}
							{/foreach}
						</ul>
					</div>				
				{/if}
				<!-- Botão chat ( fim ) -->

				<div class="separador" style="display: none;" id="separador-icon-top-menu"></div>
				<div id="module_lesson_top_link_change"></div>
			</div>
		</div>
	</div>
{/if}
