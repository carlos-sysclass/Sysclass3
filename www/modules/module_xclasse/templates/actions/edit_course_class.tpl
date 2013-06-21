{capture name="t_new_class_form"}
	<script type="text/javascript">
		var slider_value = {if $T_XCLASSE_FORM.max_users.value}{$T_XCLASSE_FORM.max_users.value}{else}0{/if};
	</script>
	
	{literal}
	<style type="text/css">
	.form-list-itens .ui-slider {
	    margin-top: 7px;
	    padding: 0 5px;
	    
	}
	#max-users-slider {
		float: left;
		width: 300px;
	}
	#max-users-text {
	    font-weight: bold;
	    margin: 7px 0 0 10px;
	    padding: 0 5px;
	}
	</style>
	{/literal}
	<div id="_XCOURSE_CLASS_FORM2" class="blockContents">
		{$T_XCLASSE_FORM.javascript}
		<form {$T_XCLASSE_FORM.attributes}>
			{$T_XCLASSE_FORM.hidden}
			<div class="blockContents form-list-itens" style="width: 100%;">
				<div class="grid_24">
					<label for="{$T_XCLASSE_FORM.name.name}">{$T_XCLASSE_FORM.name.label}:&nbsp;</label>
					{$T_XCLASSE_FORM.name.html}
				</div>
				<div class="grid_24">
					<label for="{$T_XCLASSE_FORM.max_users.name}">{$T_XCLASSE_FORM.max_users.label}:&nbsp;</label>
					<span id="max-users-text">{$T_XCLASSE_FORM.max_users.value}</span>
					{$T_XCLASSE_FORM.max_users.html}
				</div>
				<div class="grid_24" style="margin-bottom: 15px;">
					<div id="max-users-slider"></div>
				</div>
				<div class="grid_24">
					<label for="{$T_XCLASSE_FORM.start_date.name}">{$T_XCLASSE_FORM.start_date.label}:&nbsp;</label>
					{$T_XCLASSE_FORM.start_date.html}
				</div>
				<div>
					<label for="{$T_XCLASSE_FORM.end_date.name}">{$T_XCLASSE_FORM.end_date.label}:&nbsp;</label>
					{$T_XCLASSE_FORM.end_date.html}
				</div>
				<div>
					<label for="{$T_XCLASSE_FORM.active.name}">{$T_XCLASSE_FORM.active.label}:</label>
					{$T_XCLASSE_FORM.active.html}
				</div>		
				<div class="clear"></div>
				
				<div class="grid_24" style="margin-top: 20px;" align="center">
					<button class="form-button" type="{$T_XCLASSE_FORM.submit_xclasse_form.type}" name="{$T_XCLASSE_FORM.submit_xclasse_form.name}" value="{$T_XCLASSE_FORM.submit_xclasse_form.value}">
						<img width="29" height="29" src="images/transp.png">
						<span>{$T_XCLASSE_FORM.submit_xclasse_form.value}</span>
					</button>
				</div>
			</div>
		</form>
	</div>
{/capture}


{eF_template_printBlock
	title 			= $smarty.const.__XCLASSE_EDITCLASS
	data			= $smarty.capture.t_new_class_form
	class			= $item.class
	contentclass	= $item.contentclass
}