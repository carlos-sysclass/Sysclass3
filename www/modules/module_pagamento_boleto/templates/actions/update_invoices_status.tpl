<div class="flat_area">
	<div class="grid_6 box border">
		<h2 style="margin-bottom: 0;">{$smarty.const._MODULE_PAGAMENTO_SEND_FILE}</h2>
		<a class="toggle" href="#">&nbsp;</a><a class="toggle" href="#">&nbsp;</a>
		{$T_MODULE_PAGAMENTO_BOLETO_FILE_FORM.javascript}
		<form {$T_MODULE_PAGAMENTO_BOLETO_FILE_FORM.attributes}>
			{$T_MODULE_PAGAMENTO_BOLETO_FILE_FORM.hidden}
			<div class="blockContents">
				<div class="toggle_container">
				<!-- 
					<div class="grid_16">
						<label>{$T_MODULE_PAGAMENTO_BOLETO_FILE_FORM.file_title.label}</label> 
						{$T_MODULE_PAGAMENTO_BOLETO_FILE_FORM.file_title.html}
						{$T_MODULE_PAGAMENTO_BOLETO_FILE_FORM.file_title.error}
					</div>
				 -->
					<div class="grid_16">
						<label>{$T_MODULE_PAGAMENTO_BOLETO_FILE_FORM.file_upload.label}</label> 
						{$T_MODULE_PAGAMENTO_BOLETO_FILE_FORM.file_upload.html}
						{$T_MODULE_PAGAMENTO_BOLETO_FILE_FORM.file_upload.error}
					</div>
					<div class="grid_16">
						<button class="button_colour round_all" type="submit" name="{$T_MODULE_PAGAMENTO_BOLETO_FILE_FORM.submit_apply.name}" value="{$T_MODULE_PAGAMENTO_BOLETO_FILE_FORM.submit_apply.value}">
							<img width="24" height="24" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/white/bended_arrow_right.png">
							<span>{$T_MODULE_PAGAMENTO_BOLETO_FILE_FORM.submit_apply.value}</span>
						</button>
					</div>
				</div>
			</div>
		</form>
	</div>
	<div class="clear"></div>
	<div class="grid_16 box border">
		<h2 style="margin-bottom: 0;">{$smarty.const._MODULE_PAGAMENTO_SENDED_FILES}</h2>
		<a class="toggle" href="#">&nbsp;</a><a class="toggle" href="#">&nbsp;</a>
		<div class="flat_area">
			<div class="toggle_container">
				<div id="invoices_send_files_tree" class="file_tree file_tree_sended_files"></div>
			</div>
		</div>
	</div>
</div>
<style type="text/css">
{literal}
	.file_tree li.selected {
		background-color: #1C5EA0;
		color: #ffffff;
	}
	.file_tree li.selected a {
		background-color: #1C5EA0;
		color: #ffffff !important;
	}
	.jqueryFileTree li.file_header {
		color: #333377; 
	}
	.file, .file_header {
		float: left;
		clear: both;
		width: 98%;
	}
	.file a, .file_header a {
		float: left;
		width: 98%;
	}
	.filepart {
		padding: 0;
		float: left;
		white-space: nowrap;
	}
	.fileprefix {
		width: 13%;
		float: left;
		text-align: center;
	}
	.filename {
		float: left;
	}
	.filetime {
		float: right;
		width: 22%;
		text-align: center;
	}
	.filesize {
		float: right;
		width: 22%;
		text-align: center;
	}

	
{/literal}
</style>

{include file="$T_MODULE_PAGAMENTO_BOLETO_BASEDIR/templates/includes/javascript.tpl"}