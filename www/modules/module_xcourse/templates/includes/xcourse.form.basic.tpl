<div class="blockContents">
 	{$T_MODULE_XCOURSE_BASIC_FORM.javascript}
	<form {$T_MODULE_XCOURSE_BASIC_FORM.attributes}>
		{$T_MODULE_XCOURSE_BASIC_FORM.hidden}
		<div class="grid_16">
			<label>{$T_MODULE_XCOURSE_BASIC_FORM.name.label}:</label>
			{$T_MODULE_XCOURSE_BASIC_FORM.name.html}
		</div>	
		<div class="grid_8">
			<label>{$T_MODULE_XCOURSE_BASIC_FORM.ies_id.label}:</label>
			{$T_MODULE_XCOURSE_BASIC_FORM.ies_id.html}
		</div>			
		
		<div class="grid_8">
		{if !$T_CONFIGURATION.onelanguage}
			<label>{$T_MODULE_XCOURSE_BASIC_FORM.languages_NAME.label}:</label>
			{$T_MODULE_XCOURSE_BASIC_FORM.languages_NAME.html}
		{/if}
		</div>
		<div class="grid_8">
			<label>{$T_MODULE_XCOURSE_BASIC_FORM.directions_ID.label}:</label>
			{$T_MODULE_XCOURSE_BASIC_FORM.directions_ID.html}
		</div>
		<div class="grid_8">
		{if $T_CONFIGURATION.disable_payments != 1}
			<label>{$T_MODULE_XCOURSE_BASIC_FORM.price.label}:</label>
			{$T_MODULE_XCOURSE_BASIC_FORM.price.html}
		{/if}
		</div>
		
		<div class="grid_3">
			<label>{$T_MODULE_XCOURSE_BASIC_FORM.enable_start_date.label}:</label>
			{$T_MODULE_XCOURSE_BASIC_FORM.enable_start_date.html}			
		</div>
		<div class="grid_5">
			<label>{$T_MODULE_XCOURSE_BASIC_FORM.start_date.label}:</label>
			{$T_MODULE_XCOURSE_BASIC_FORM.start_date.html}			
		</div>
		<div class="grid_3">
			<label>{$T_MODULE_XCOURSE_BASIC_FORM.enable_end_date.label}:</label>
			{$T_MODULE_XCOURSE_BASIC_FORM.enable_end_date.html}
		</div>
		<div class="grid_5">
			<label>{$T_MODULE_XCOURSE_BASIC_FORM.end_date.label}:</label>
			{$T_MODULE_XCOURSE_BASIC_FORM.end_date.html}
		</div>

		<div class="grid_8">
			<label>{$T_MODULE_XCOURSE_BASIC_FORM.active.label}:</label>
			{$T_MODULE_XCOURSE_BASIC_FORM.active.html}			
		</div>
		<div class="grid_8">
			<label>{$T_MODULE_XCOURSE_BASIC_FORM.show_catalog.label}:</label>
			{$T_MODULE_XCOURSE_BASIC_FORM.show_catalog.html}
		</div>
		<!-- 
		<div class="grid_4">
			<label>{$T_MODULE_XCOURSE_BASIC_FORM.enable_presencial.label}:</label>
			{$T_MODULE_XCOURSE_BASIC_FORM.enable_presencial.html}
		</div>
		<div class="grid_4">
			<label>{$T_MODULE_XCOURSE_BASIC_FORM.enable_web.label}:</label>
			{$T_MODULE_XCOURSE_BASIC_FORM.enable_web.html}
		</div>		
 -->
		<div class="grid_16">
			<label>{$T_MODULE_XCOURSE_BASIC_FORM.terms.label}:</label>
			{$T_MODULE_XCOURSE_BASIC_FORM.terms.html}		
		</div>
<!-- 
				<div class="grid_8">
				<label>{$T_MODULE_XCOURSE_BASIC_FORM.enable_presencial.label}:</label>
				{$T_MODULE_XCOURSE_BASIC_FORM.enable_presencial.html}
				</div>
				<div class="grid_8">
				<label>{$T_MODULE_XCOURSE_BASIC_FORM.price_presencial.label}:</label>
				{$T_MODULE_XCOURSE_BASIC_FORM.price_presencial.html}
				</div>

				<div class="grid_8">
				<label>{$T_MODULE_XCOURSE_BASIC_FORM.price_web.label}:</label>
				{$T_MODULE_XCOURSE_BASIC_FORM.price_web.html}
				</div>
			</div>
 -->
		
		<div class="clear"></div>
		
		<div class="grid_16" style="margin-top: 20px;">
			<button class="button_colour round_all" type="submit" name="{$T_MODULE_XCOURSE_BASIC_FORM.submit_xcourse.name}" value="{$T_MODULE_XCOURSE_BASIC_FORM.submit_xcourse.value}">
				<img width="24" height="24" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/white/bended_arrow_right.png">
				<span>{$T_MODULE_XCOURSE_BASIC_FORM.submit_xcourse.value}</span>
			</button>
		</div>
	</form>
</div>