<form {$T_XREQUEST_EDITREQUEST_FORM.attributes}>
		{$T_XREQUEST_EDITREQUEST_FORM.hidden}
		
			<label>{$T_XREQUEST_EDITREQUEST_FORM.name.label}:</label>
			{$T_XREQUEST_EDITREQUEST_FORM.name.html}
		
			<br />
	
			<label>{$T_XREQUEST_EDITREQUEST_FORM.valor.label}:</label>
			{$T_XREQUEST_EDITREQUEST_FORM.valor.html}
			<br />
	
		
			<label>{$T_XREQUEST_EDITREQUEST_FORM.dias_prazo.label}:</label>
			{$T_XREQUEST_EDITREQUEST_FORM.dias_prazo.html}
			<br />
	
			<label>{$T_XREQUEST_EDITREQUEST_FORM.email.label}:</label>
			{$T_XREQUEST_EDITREQUEST_FORM.email.html}
			<br />
		
			<label>{$T_XREQUEST_EDITREQUEST_FORM.status.label}:</label>
			{$T_XREQUEST_EDITREQUEST_FORM.status.html}
		
		
		<div class="clear"></div>

		<div class="grid_16" style="margin-top: 20px;">
			<input class="button_colour round_all"  type="submit" name="{$T_XREQUEST_EDITREQUEST_FORM.submit_xrequest.name}" value="{$T_XREQUEST_EDITREQUEST_FORM.submit_xrequest.value}">
		</div>
		
		<div class="clear"></div>
	</form>