<?php /* Smarty version 2.6.26, created on 2012-06-14 14:41:31
         compiled from includes/barra_topo.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'includes/barra_topo.tpl', 145, false),)), $this); ?>
<?php if ($_SESSION['s_type'] == ""): ?>
<?php else: ?>
	<!-- Barra Topo Black -->
	<!-- <div id="topBarContainer"> 

		<section id="topBar" class="wrap"> -->
			<!-- Avatar n Hello -->
<!--			<section id="avatarHello">
				<img src = "view_file.php?file=<?php echo $this->_tpl_vars['T_CURRENT_USER_AVATAR']['avatar']; ?>
" 
					 title="<?php echo @_CURRENTAVATAR; ?>
" 
					 alt="<?php echo @_CURRENTAVATAR; ?>
" 
					 width = "<?php echo $this->_tpl_vars['T_CURRENT_USER_AVATAR']['width']; ?>
" 
					 height = "<?php echo $this->_tpl_vars['T_CURRENT_USER_AVATAR']['height']; ?>
"
					 id="avatarTopBar" />

				<hgroup id="titleHello">
					<h2><?php echo @__WELCOME; ?>
 <?php echo $this->_tpl_vars['T_CURRENT_USER']->user['name']; ?>
 <?php echo $this->_tpl_vars['T_CURRENT_USER']->user['surname']; ?>
</h2>
					<h3>
						<?php if ($this->_tpl_vars['T_CURRENT_USER']->coreAccess['dashboard'] != 'hidden'): ?>
							<?php echo @__LOGGEDINAS; ?>
 
							<a href = "<?php echo $_SESSION['s_type']; ?>
.php?<?php if ($_SESSION['s_type'] == 'administrator'): ?>ctg=users&edit_user=<?php echo $_SESSION['s_login']; ?>
<?php else: ?>ctg=personal<?php endif; ?>" class="headerText"><?php echo $this->_tpl_vars['T_CURRENT_USER_TYPE']; ?>
</a>
						<?php else: ?>
							<?php echo @__LOGGEDINAS; ?>
 <?php echo $this->_tpl_vars['T_CURRENT_USER_TYPE']; ?>

						<?php endif; ?>
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
                <img src = "view_file.php?file=<?php echo $this->_tpl_vars['T_CURRENT_USER_AVATAR']['avatar']; ?>
" 
                     title="<?php echo @_CURRENTAVATAR; ?>
" 
                     alt="<?php echo @_CURRENTAVATAR; ?>
" 
                     width = "<?php echo $this->_tpl_vars['T_CURRENT_USER_AVATAR']['width']; ?>
" 
                     height = "<?php echo $this->_tpl_vars['T_CURRENT_USER_AVATAR']['height']; ?>
"
                     style="margin-top: 10px; "
                     id="avatar" />
				
				<span class="user-detail" > 
					<span class="name "><?php echo @__WELCOME; ?>
 <?php echo $this->_tpl_vars['T_CURRENT_USER']->user['name']; ?>
 <?php echo $this->_tpl_vars['T_CURRENT_USER']->user['surname']; ?>
</span><br /> 
					<span class="text">
						<?php if ($this->_tpl_vars['T_CURRENT_USER']->coreAccess['dashboard'] != 'hidden'): ?>
							<?php echo @__LOGGEDINAS; ?>
 
							<a href = "<?php echo $_SESSION['s_type']; ?>
.php?<?php if ($_SESSION['s_type'] == 'administrator'): ?>ctg=users&edit_user=<?php echo $_SESSION['s_login']; ?>
<?php else: ?>ctg=personal<?php endif; ?>" class="headerText"><?php echo $this->_tpl_vars['T_CURRENT_USER_TYPE']; ?>
</a>
						<?php else: ?>
							<?php echo @__LOGGEDINAS; ?>
 <?php echo $this->_tpl_vars['T_CURRENT_USER_TYPE']; ?>

						<?php endif; ?>
					</span>
				</span> 
				<div class="icon-over-menu">
					<?php if (! $this->_tpl_vars['T_NO_PERSONAL_MESSAGES']): ?>
						<?php if ($this->_tpl_vars['T_UNREAD_MESSAGES'] > 0): ?>
							<span>
								<?php echo @__YOUHAVE; ?>
 
								<a href = "<?php echo $_SESSION['s_type']; ?>
.php?ctg=messages">
									<?php if ($this->_tpl_vars['T_UNREAD_MESSAGES'] > 1): ?>
										<button class="inputo-top">
											<img src="themes/sysclass3/images/icon-msg2.png" alt="<?php echo @__NEWMESSAGES; ?>
" title="<?php echo @__YOUHAVE; ?>
 <?php echo @__NEWMESSAGE; ?>
">
										</button>
									<?php else: ?>
										<button class="inputo-top">
											<img src="themes/sysclass3/images/icon-msg2.png" alt="<?php echo @__NEWMESSAGES; ?>
" title="<?php echo @__YOUHAVE; ?>
 <?php echo @__NEWMESSAGES; ?>
">
										</button>
									<?php endif; ?>
								</a>
							</span>

							<span>

								<a href = "<?php echo $_SESSION['s_type']; ?>
.php?ctg=messages">
									<img src="themes/sysclass3/images/icon-msg2.png" alt="<?php echo @__NEWMESSAGES; ?>
" title="<?php echo @__YOUDONTHAVE; ?>
 <?php echo @__NEWMESSAGES; ?>
">
								</a>
							</span>


						<?php else: ?>
							<a href = "<?php echo $_SESSION['s_type']; ?>
.php?ctg=messages">
								<button class="inputo-top-perfil">
									<img src="themes/sysclass3/images/icons/msg.png" alt="<?php echo @__NEWMESSAGES; ?>
" title="<?php echo @__YOUDONTHAVE; ?>
 <?php echo @__NEWMESSAGES; ?>
"></a>
								</button>

							<?php endif; ?> 
						<?php endif; ?>

				</div>
				<?php if (count($this->_tpl_vars['T_BAR_ADDITIONAL_ACCOUNTS']) > 0): ?>
					<a href="#" title="Alterar acesso" id="changeAccount">
						<button class="inputo-top-change-account" type="button" id="changeAccountBtn" style="color: #fff;">
							<img  src="images/others/transparent.png" alt="Acessar como" title="Acesso como" />
						</button>
					</a>
					
					<div class="showAccounts" id="showAccountsContainer">
						<span class="setaShowAccounts"></span>
						<p class="altAcessoTitle">Alterar acesso</p>
						<ul class="dropdown">
							<?php $_from = $this->_tpl_vars['T_BAR_ADDITIONAL_ACCOUNTS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['additional_accounts'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['additional_accounts']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
        $this->_foreach['additional_accounts']['iteration']++;
?>
								<li><a href="javascript: changeAccount('<?php echo $this->_tpl_vars['item']['login']; ?>
');">#filter:login-<?php echo $this->_tpl_vars['item']['login']; ?>
#</a></li>
							<?php endforeach; endif; unset($_from); ?>
						</ul>
					</div>
				<?php endif; ?>
				<!--
				<div class="inputo-top" id="input-search-button">
				<img src="themes/sysclass3/images/icon-lupa.png" alt="<?php echo @_FIND; ?>
" title="<?php echo @_FIND; ?>
">
				</div>
				-->
				<a href="<?php echo @G_SERVERNAME; ?>
index.php?logout=true" class="inputo-top-logout">
					
						<img class="inputo-top-logout-icon " src="images/others/transparent.png" alt="<?php echo @_LOGOUT; ?>
" title="<?php echo @_LOGOUT; ?>
">
						<span><?php echo @_LOGOUT; ?>
</span>
					
				</a>
				<!-- 						
				<button class="inputo-top" id="input-search-button">
				 <img class="inputo-top-icon" src="images/others/transparent.png" alt="<?php echo @_FIND; ?>
" title="<?php echo @_FIND; ?>
">
				</button>
				-->
				<div class="inputo-top-search">
					<form action = "<?php echo @G_SERVERNAME; ?>
<?php echo $_SESSION['s_type']; ?>
.php?ctg=control_panel&op=search" method = "post">
						<button name="search_submit" type="submit" class="inputo-top" value="submitted">
							<img class="inputo-top-icon" src="images/others/transparent.png" alt="<?php echo @_FIND; ?>
" title="<?php echo @_FIND; ?>
">
						</button>
						<div class="colorinp-top">
							<input  
								type="text" 
								name="search_text"
								value = "<?php if (isset ( $_POST['search_text'] )): ?><?php echo $_POST['search_text']; ?>
<?php else: ?><?php echo @_SEARCH; ?>
...<?php endif; ?>"
								onclick="if(this.value=='<?php echo @_SEARCH; ?>
...')this.value='';" onblur="if(this.value=='')this.value='<?php echo @_SEARCH; ?>
...';"
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
				   <a href="<?php echo @G_SERVERNAME; ?>
<?php echo $_SESSION['s_type']; ?>
.php" title="<?php echo @_DIMDIM_MEETING; ?>
">
					   <button class="inputo-top-notification" type="button">
						   <img class="inputo-top-notification-icon" src="images/others/transparent.png" alt="<?php echo @_DIMDIM_MEETING; ?>
" title="<?php echo @_DIMDIM_MEETING; ?>
">
					   </button>
				   </a>
				 </div>
				-->
				<?php if ($_SESSION['s_type'] == 'student'): ?>
					<div class="inputo-top-cash">
						<!-- 
						  <div class="alert-cash">
							<span>50</span>
						  </div>
						-->
						<a href="<?php echo @G_SERVERNAME; ?>
<?php echo $_SESSION['s_type']; ?>
.php?ctg=module&op=module_xpay" title="<?php echo @__XPAY_VIEW_MY_STATEMENT; ?>
">
							<button class="inputo-top-cash" type="button">
								<img class="inputo-top-cash-icon" src="images/others/transparent.png" alt="<?php echo @__XPAY_DOPAYMENTS; ?>
" title="<?php echo @__XPAY_VIEW_MY_STATEMENT; ?>
">
							</button>
						</a>
					</div>
				<?php endif; ?>

				<a href="<?php echo @G_SERVERNAME; ?>
<?php echo $_SESSION['s_type']; ?>
.php?ctg=personal" title="<?php echo @_MYPROFILE; ?>
" >
					<button class="inputo-top-perfil" type="button">
						<img class="inputo-top-perfil-icon" src="images/others/transparent.png" alt="<?php echo @_MYPROFILE; ?>
" title="<?php echo @_MYPROFILE; ?>
">
					</button>
				</a>
				<?php if ($_SESSION['s_type'] == 'student'): ?>

					<a target="POPUP_FRAME" onclick="eF_js_showDivPopup('<?php echo @_INFOFORLESSON; ?>
', 2)" href="javascript: void(0);" title="<?php echo @__XCOURSE_STUDENT_GUIDANCE; ?>
" id="xcourse_lesso_info_link">
						<button class="inputo-top-info" type="button">
							<img class="inputo-top-info-icon" src="images/others/transparent.png" alt="<?php echo @__XCOURSE_STUDENT_GUIDANCE; ?>
" title="<?php echo @__XCOURSE_STUDENT_GUIDANCE; ?>
">
						</button>
					</a>
				<?php endif; ?>
				
				<!-- 
					<a onclick="javascript:chatWith('suporteult')"href="javascript: void(0);" title="<?php echo @_MODULE_XLIVECHAT_NAME; ?>
">
						<button class="xlivechat_button" type="button">
							<img class="xlivechat-icon" src="images/others/transparent.png" alt="<?php echo @_MODULE_XLIVECHAT_NAME; ?>
" title="<?php echo @_MODULE_XLIVECHAT_NAME; ?>
">
						</button>
					</a>
				 -->			
					
				<div class="separador" style="display: none;" id="separador-icon-top-menu"></div>
				<div id="module_lesson_top_link_change"></div>
			</div>
		</div>
	</div>
<?php endif; ?>