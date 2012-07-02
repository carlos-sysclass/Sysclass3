<div class="grid_24 box border" style="margin-top: 15px;">
			<div class="headerTools">
            	                
                <span>
                    
                  	<img src = "/themes/sysclass/images/icons/small/grey/users.png" title = "{$smarty.const._NEWGROUP}" alt = "{$smarty.const._NEWGROUP}">
                    <a href="{$T_REQUEST_BASEURL}{$T_TYPE_USER}.php?ctg=module&op=module_xrequest&action=get_xrequest_protocol">{$smarty.const._REQUEST_LIST_PROTOCOL}</a>	
                  
                </span>
                
                
			</div>
</div>
<div class="clear"></div>


<div class="blockContents">
 	{$T_XREQUEST_NEW_FORM.javascript}
	<form {$T_XREQUEST_NEW_FORM.attributes}>
		{$T_XREQUEST_NEW_FORM.hidden}
		<div class="grid_12">
			{$T_XREQUEST_NEW_FORM.user_id.html}
			
			
			{$T_XREQUEST_NEW_FORM.username.html}
			<br />

			<label>{$T_XREQUEST_NEW_FORM.type.label}:</label>
			{$T_XREQUEST_NEW_FORM.type.html}
			{$T_XREQUEST_NEW_FORM.type.error}
			<br />
			<br />
			<label>{$T_XREQUEST_NEW_FORM.desc.label}:</label>
			{$T_XREQUEST_NEW_FORM.desc.html}
			{$T_XREQUEST_NEW_FORM.desc.error}
			
		</div>
		<div class="clear"></div>
		<div class="grid_24" style="margin-top: 20px;">
			<button class="button_colour round_all" type="submit" name="{$T_XREQUEST_NEW_FORM.submit_XREQUEST.name}" value="{$T_XREQUEST_NEW_FORM.submit_XREQUEST.value}">
				<img width="24" height="24" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/white/bended_arrow_right.png">
				<span>{$T_XREQUEST_NEW_FORM.submit_XREQUEST.value}</span>
			</button>
		</div>
		<div class="clear"></div>
	</form>
</div>