<?php /* Smarty version 2.6.26, created on 2012-06-04 14:50:47
         compiled from /srv/www/vhosts/local.sysclass.com/www/modules/module_polos/templates/blocks/polo.data.tpl */ ?>
<ul class="default-list module_polo_info_user">
	<li>	
		<p>
			<span><?php echo @_MODULE_POLOS_CONTATO; ?>
</span><br/>
			<span class="info_polos_module_polos_user">
			<?php echo $this->_tpl_vars['T_MODULE_POLOS_INFO_USER']['contato']; ?>

			</span>
		</p>
	</li>
	<li>
		<p>
			<span><?php echo @_MODULE_POLO; ?>
</span><br/>
			<span class="info_polos_module_polos_user">
			<?php echo $this->_tpl_vars['T_MODULE_POLOS_INFO_USER']['razao_social']; ?>

			</span>
		</p>
	</li>
	<?php if ($this->_tpl_vars['T_MODULE_POLOS_INFO_USER']['email'] <> null): ?>
	<li>
		<a title="<?php echo $this->_tpl_vars['T_MODULE_POLOS_INFO_USER']['razao_social']; ?>
" href = "<?php echo $this->_tpl_vars['T_MODULE_POLOS_INFO_USER_LINK']; ?>
&rec=1" onclick = "eF_js_showDivPopup('<?php echo @_MODULE_POLOS_EMAIL; ?>
', 1);" target = "POPUP_FRAME">
			<span><?php echo @_MODULE_POLOS_EMAIL; ?>
</span><br/>
			<span class="info_polos_module_polos_user">
			<?php echo $this->_tpl_vars['T_MODULE_POLOS_INFO_USER']['email']; ?>

			</span>
			<span class="icon_maps_polo_user_block">
					<img 
						src = "images/others/transparent.png"
						class="imgs_cont sprite16 sprite16-send-email"
						title = "<?php echo $this->_tpl_vars['item']['title']; ?>
" 
						alt = "<?php echo $this->_tpl_vars['item']['title']; ?>
"
						border = "0"
					/>
			</span>
		</a>
	</li>
	<?php endif; ?>
	<li>
		<p>
			<span><?php echo @_MODULE_POLOS_TELEFONE; ?>
</span><br/>
			<span class="info_polos_module_polos_user">
			<?php echo $this->_tpl_vars['T_MODULE_POLOS_INFO_USER']['telefone']; ?>

			</span>
		</p>
	</li>
<li>
	<p>
		<span><?php echo @_MODULE_POLOS_ENDERECO_BLOCK; ?>
</span><br/>
		<span class="info_polos_module_polos_user">
		<?php echo $this->_tpl_vars['T_MODULE_POLOS_INFO_USER']['endereco']; ?>
, <?php echo $this->_tpl_vars['T_MODULE_POLOS_INFO_USER']['numero']; ?>
 <?php echo $this->_tpl_vars['T_MODULE_POLOS_INFO_USER']['cidade']; ?>
-<?php echo $this->_tpl_vars['T_MODULE_POLOS_INFO_USER']['uf']; ?>
 -<?php echo $this->_tpl_vars['T_MODULE_POLOS_INFO_USER']['cep']; ?>
 <?php echo $this->_tpl_vars['T_MODULE_POLOS_INFO_USER']['bairro']; ?>

		</span>
			<span class="icon_maps_polo_user_block">
					<img 
						src = "images/others/transparent.png"
						class="imgs_cont sprite16 sprite16-maps"
						title = "<?php echo $this->_tpl_vars['item']['title']; ?>
" 
						alt = "<?php echo $this->_tpl_vars['item']['title']; ?>
"
						border = "0"
						id="show_local_polos_maps"
					/>
			</span>
		
		
	</p>
</li>
</ul>

<div id="local_polos_maps" style="display:none;" title="<?php echo $this->_tpl_vars['T_MODULE_POLOS_INFO_USER']['razao_social']; ?>
">
   <iframe width="640" height="480" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" 
   src="http://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=pt-PT&amp;geocode=&amp;q=<?php echo $this->_tpl_vars['T_MODULE_POLOS_INFO_USER']['geo_lat']; ?>
,<?php echo $this->_tpl_vars['T_MODULE_POLOS_INFO_USER']['geo_lng']; ?>
+(<?php echo $this->_tpl_vars['T_MODULE_POLOS_INFO_USER']['razao_social']; ?>
)&amp;aq=&amp;sll=37.771008,-122.41175&amp;sspn=0.055295,0.111494&amp;t=h&amp;g=37.771008,+-122.41175&amp;ie=UTF8&amp;ll=-25.430175,-49.282694&amp;spn=0.037207,0.054932&amp;z=14&amp;iwloc=A&amp;output=embed"></iframe>
	
	
	
</div>