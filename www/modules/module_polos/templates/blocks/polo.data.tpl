<ul class="default-list module_polo_info_user">
	<li>	
		<p>
			<span>{$smarty.const._MODULE_POLOS_CONTATO}</span><br/>
			<span class="info_polos_module_polos_user">
			{$T_MODULE_POLOS_INFO_USER.contato}
			</span>
		</p>
	</li>
	<li>
		<p>
			<span>{$smarty.const._MODULE_POLO}</span><br/>
			<span class="info_polos_module_polos_user">
			{$T_MODULE_POLOS_INFO_USER.razao_social}
			</span>
		</p>
	</li>
	{if $T_MODULE_POLOS_INFO_USER.email <> null }
	<li>
		<a title="{$T_MODULE_POLOS_INFO_USER.razao_social}" href = "{$T_MODULE_POLOS_INFO_USER_LINK}&rec=1" onclick = "eF_js_showDivPopup('{$smarty.const._MODULE_POLOS_EMAIL}', 1);" target = "POPUP_FRAME">
			<span>{$smarty.const._MODULE_POLOS_EMAIL}</span><br/>
			<span class="info_polos_module_polos_user">
			{$T_MODULE_POLOS_INFO_USER.email}
			</span>
			<span class="icon_maps_polo_user_block">
					<img 
						src = "images/others/transparent.png"
						class="imgs_cont sprite16 sprite16-send-email"
						title = "{$item.title}" 
						alt = "{$item.title}"
						border = "0"
					/>
			</span>
		</a>
	</li>
	{/if}
	<li>
		<p>
			<span>{$smarty.const._MODULE_POLOS_TELEFONE}</span><br/>
			<span class="info_polos_module_polos_user">
			{$T_MODULE_POLOS_INFO_USER.telefone}
			</span>
		</p>
	</li>
<li>
	<p>
		<span>{$smarty.const._MODULE_POLOS_ENDERECO_BLOCK}</span><br/>
		<span class="info_polos_module_polos_user">
		{$T_MODULE_POLOS_INFO_USER.endereco}, {$T_MODULE_POLOS_INFO_USER.numero} {$T_MODULE_POLOS_INFO_USER.cidade}-{$T_MODULE_POLOS_INFO_USER.uf} -{$T_MODULE_POLOS_INFO_USER.cep} {$T_MODULE_POLOS_INFO_USER.bairro}
		</span>
			<span class="icon_maps_polo_user_block">
					<img 
						src = "images/others/transparent.png"
						class="imgs_cont sprite16 sprite16-maps"
						title = "{$item.title}" 
						alt = "{$item.title}"
						border = "0"
						id="show_local_polos_maps"
					/>
			</span>
		
		
	</p>
</li>
</ul>

<div id="local_polos_maps" style="display:none;" title="{$T_MODULE_POLOS_INFO_USER.razao_social}">
   <iframe width="640" height="480" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" 
   src="http://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=pt-PT&amp;geocode=&amp;q={$T_MODULE_POLOS_INFO_USER.geo_lat},{$T_MODULE_POLOS_INFO_USER.geo_lng}+({$T_MODULE_POLOS_INFO_USER.razao_social})&amp;aq=&amp;sll=37.771008,-122.41175&amp;sspn=0.055295,0.111494&amp;t=h&amp;g=37.771008,+-122.41175&amp;ie=UTF8&amp;ll=-25.430175,-49.282694&amp;spn=0.037207,0.054932&amp;z=14&amp;iwloc=A&amp;output=embed"></iframe>
	
	
	
</div>
