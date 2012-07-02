{if $smarty.session.s_type == ""}
{else}
	<!-- Barra Topo Black -->
	<!-- <div id="topBarContainer"> 

		<section id="topBar" class="wrap"> -->
			<!-- Avatar n Hello -->
<!--			<section id="avatarHello">
				<img src = "view_file.php?file={$T_CURRENT_USER_AVATAR.avatar}" 
					 title="{$smarty.const._CURRENTAVATAR}" 
					 alt="{$smarty.const._CURRENTAVATAR}" 
					 width = "{$T_CURRENT_USER_AVATAR.width}" 
					 height = "{$T_CURRENT_USER_AVATAR.height}"
					 id="avatarTopBar" />

				<hgroup id="titleHello">
					<h2>{$smarty.const.__WELCOME} {$T_CURRENT_USER->user.name} {$T_CURRENT_USER->user.surname}</h2>
					<h3>
						{if $T_CURRENT_USER->coreAccess.dashboard != 'hidden'}
							{$smarty.const.__LOGGEDINAS} 
							<a href = "{$smarty.session.s_type}.php?{if $smarty.session.s_type == "administrator"}ctg=users&edit_user={$smarty.session.s_login}{else}ctg=personal{/if}" class="headerText">{$T_CURRENT_USER_TYPE}</a>
						{else}
							{$smarty.const.__LOGGEDINAS} {$T_CURRENT_USER_TYPE}
						{/if}
					</h3>
				</hgroup>
			</section>-->
			<!-- /end avatar n hello -->


			<!-- Icons Status -->
<!--			<section class="iconStatus topStatus">
				<nav>
					<ul class="borderRightEnable">
						<li class="alertIconStatus spriteIconStatus">
							<a href="#">Alertas</a>
							<span class="statusQtde statusQtdeWhite">5</span>
						</li>
						<li class="msgIconStatus spriteIconStatus">
							<a href="#">Mensagens</a>
							<span class="statusQtde statusQtdeWhite">5</span>
						</li>
						<li class="configIconStatus spriteIconStatus">
							<a href="#">Configurações</a>
						</li>
					</ul>

					<ul>
						<li class="chatIconStatus spriteIconStatus">
							<a href="#">Bate-papo</a>
						</li>
						<li class="boxsIconStatus spriteIconStatus">
							<a href="#">Boxs</a>
						</li>
						<li class="priceIconStatus spriteIconStatus">
							<a href="#">Price</a>
							<span class="statusQtde statusQtdeOrange">5</span>
						</li>
					</ul>
				</nav>
			</section>-->
			<!-- /end icons status -->


			<!-- Status Right Fix -->
<!--			<section class="fixStatus topStatus">
				<nav>
					<ul>
						<li class="calendarIconStatus spriteIconStatus noIndent">
							<a href="#">December 28, 2011</a>
						</li>
						<li class="searchIconStatus spriteIconStatus">
							<a href="#">Procurar</a>
						</li>
						<li class="exitIconStatus spriteIconStatus noIndent">
							<a href="#">Logout</a>
						</li>
					</ul>
				</nav>
			</section>-->
			<!-- /end status right fix -->
<!--		</section>

	</div>-->
	<!-- /end barra topo black -->

	<div class="barra-topo">
		<div class="menutop container_24">
			<div class="menutop-over" id="menutop-over" >
                <img src = "view_file.php?file={$T_CURRENT_USER_AVATAR.avatar}" 
                     title="{$smarty.const._CURRENTAVATAR}" 
                     alt="{$smarty.const._CURRENTAVATAR}" 
                     width = "{$T_CURRENT_USER_AVATAR.width}" 
                     height = "{$T_CURRENT_USER_AVATAR.height}"
                     style="margin-top: 10px; "
                     id="avatar" />
				
				<span class="user-detail" > 
					<span class="name ">{$smarty.const.__WELCOME} {$T_CURRENT_USER->user.name} {$T_CURRENT_USER->user.surname}</span><br /> 
					<span class="text">
						{if $T_CURRENT_USER->coreAccess.dashboard != 'hidden'}
							{$smarty.const.__LOGGEDINAS} 
							<a href = "{$smarty.session.s_type}.php?{if $smarty.session.s_type == "administrator"}ctg=users&edit_user={$smarty.session.s_login}{else}ctg=personal{/if}" class="headerText">{$T_CURRENT_USER_TYPE}</a>
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
									<img src="themes/sysclass3/images/icons/msg.png" alt="{$smarty.const.__NEWMESSAGES}" title="{$smarty.const.__YOUDONTHAVE} {$smarty.const.__NEWMESSAGES}"></a>
								</button>

							{/if} 
						{/if}

				</div>
				{if $T_BAR_ADDITIONAL_ACCOUNTS|@count > 0}
					<a href="#" title="Alterar acesso" id="changeAccount">
						<button class="inputo-top-change-account" type="button" id="changeAccountBtn" style="color: #fff;">
							<img  src="images/others/transparent.png" alt="Acessar como" title="Acesso como" />
						</button>
					</a>
					
					<div class="showAccounts" id="showAccountsContainer">
						<span class="setaShowAccounts"></span>
						<p class="altAcessoTitle">Alterar acesso</p>
						<ul class="dropdown">
							{foreach name = 'additional_accounts' item = "item" key = "key" from = $T_BAR_ADDITIONAL_ACCOUNTS}
								<li><a href="javascript: changeAccount('{$item.login}');">#filter:login-{$item.login}#</a></li>
							{/foreach}
						</ul>
					</div>
				{/if}
				<!--
				<div class="inputo-top" id="input-search-button">
				<img src="themes/sysclass3/images/icon-lupa.png" alt="{$smarty.const._FIND}" title="{$smarty.const._FIND}">
				</div>
				-->
				<a href="{$smarty.const.G_SERVERNAME}index.php?logout=true" class="inputo-top-logout">
					
						<img class="inputo-top-logout-icon " src="images/others/transparent.png" alt="{$smarty.const._LOGOUT}" title="{$smarty.const._LOGOUT}">
						<span>{$smarty.const._LOGOUT}</span>
					
				</a>
				<!-- 						
				<button class="inputo-top" id="input-search-button">
				 <img class="inputo-top-icon" src="images/others/transparent.png" alt="{$smarty.const._FIND}" title="{$smarty.const._FIND}">
				</button>
				-->
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

				<!-- 
				 <div class="inputo-top-notification">
					 <div class="alert-notification">
					   <span>50</span>
					 </div>
				   <a href="{$smarty.const.G_SERVERNAME}{$smarty.session.s_type}.php" title="{$smarty.const._DIMDIM_MEETING}">
					   <button class="inputo-top-notification" type="button">
						   <img class="inputo-top-notification-icon" src="images/others/transparent.png" alt="{$smarty.const._DIMDIM_MEETING}" title="{$smarty.const._DIMDIM_MEETING}">
					   </button>
				   </a>
				 </div>
				-->
				{if $smarty.session.s_type == 'student'}
					<div class="inputo-top-cash">
						<!-- 
						  <div class="alert-cash">
							<span>50</span>
						  </div>
						-->
						<a href="{$smarty.const.G_SERVERNAME}{$smarty.session.s_type}.php?ctg=module&op=module_xpay" title="{$smarty.const.__XPAY_VIEW_MY_STATEMENT}">
							<button class="inputo-top-cash" type="button">
								<img class="inputo-top-cash-icon" src="images/others/transparent.png" alt="{$smarty.const.__XPAY_DOPAYMENTS}" title="{$smarty.const.__XPAY_VIEW_MY_STATEMENT}">
							</button>
						</a>
					</div>
				{/if}

				<a href="{$smarty.const.G_SERVERNAME}{$smarty.session.s_type}.php?ctg=personal" title="{$smarty.const._MYPROFILE}" >
					<button class="inputo-top-perfil" type="button">
						<img class="inputo-top-perfil-icon" src="images/others/transparent.png" alt="{$smarty.const._MYPROFILE}" title="{$smarty.const._MYPROFILE}">
					</button>
				</a>
				{if $smarty.session.s_type == 'student'}

					<a target="POPUP_FRAME" onclick="eF_js_showDivPopup('{$smarty.const._INFOFORLESSON}', 2)" href="javascript: void(0);" title="{$smarty.const.__XCOURSE_STUDENT_GUIDANCE}" id="xcourse_lesso_info_link">
						<button class="inputo-top-info" type="button">
							<img class="inputo-top-info-icon" src="images/others/transparent.png" alt="{$smarty.const.__XCOURSE_STUDENT_GUIDANCE}" title="{$smarty.const.__XCOURSE_STUDENT_GUIDANCE}">
						</button>
					</a>
				{/if}
				
				
					<a onclick="javascript:chatWith('suporteult')"href="javascript: void(0);" title="{$smarty.const._MODULE_XLIVECHAT_NAME}">
						<button class="xlivechat_button" type="button">
							<img class="xlivechat-icon" src="images/others/transparent.png" alt="{$smarty.const._MODULE_XLIVECHAT_NAME}" title="{$smarty.const._MODULE_XLIVECHAT_NAME}">
						</button>
					</a>
							
					
				<div class="separador" style="display: none;" id="separador-icon-top-menu"></div>
				<div id="module_lesson_top_link_change"></div>
			</div>
		</div>
	</div>
{/if}
