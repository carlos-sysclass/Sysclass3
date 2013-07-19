{capture name="t_send_return_file"}
	<div class="grid_24">
		{$T_XPAY_BOLETO_FILE_FORM.javascript}
		<form {$T_XPAY_BOLETO_FILE_FORM.attributes}>
			{$T_XPAY_BOLETO_FILE_FORM.hidden}
			<div class="grid_24">
				<label>{$T_XPAY_BOLETO_FILE_FORM.instance_type.label}:</label> 
				{$T_XPAY_BOLETO_FILE_FORM.instance_type.html}
				{$T_XPAY_BOLETO_FILE_FORM.instance_type.error}
			</div>
			<div class="grid_24">
				<label>{$T_XPAY_BOLETO_FILE_FORM.file_upload.label}:</label> 
				{$T_XPAY_BOLETO_FILE_FORM.file_upload.html}
				{$T_XPAY_BOLETO_FILE_FORM.file_upload.error}
			</div>

			<div align="center" style="margin-top: 20px;" class="grid_24">
				<button value="{$T_XPAY_BOLETO_FILE_FORM.submit_apply.value}" name="{$T_XPAY_BOLETO_FILE_FORM.submit_apply.name}" type="submit" class="form-button icon-save">
					<img height="29" width="29" src="images/transp.png">
					<span>{$T_XPAY_BOLETO_FILE_FORM.submit_apply.value}</span>
				</button>		
			</div>
		</form>
	</div>
	<div class="clear"></div>
{/capture}

{sC_template_printBlock 
	title =	$smarty.const.__XPAY_BOLETO_INVOICES_STATUS
	data  =	$smarty.capture.t_send_return_file
}


{capture name="t_file_list_queue"}
	<div class="headerTools">
		<span>
			<img 
				src = "images/others/transparent.png"
				class="imgs_cont sprite16 sprite16-go_into"
				border = "0"
			/>
			<a href="{$T_XPAY_BOLETO_BASEURL}&action=call_cron_event">Processar Arquivo(s) Agora</a>
		</span>
	</div>
	<div class="clear"></div>
	
	<table class="style1">
		<thead>
			<tr>
				<th>{$smarty.const.__XPAY_BOLETO_FILE_NAME}</th>
				<th style="text-align: center">{$smarty.const.__XPAY_BOLETO_FILE_TIME}</th>
				<th style="text-align: center">{$smarty.const.__XPAY_BOLETO_FILE_SIZE}</th>
			</tr>
		</thead>
		<tbody>
			{foreach item="method" from=$T_XPAY_BOLETO_FILE_QUEUE}
				{if $method.files|@count > 0}
					<tr>
						<th colspan="2">{$smarty.const.__XPAY_PAYMENT_METHOD}: {$method.name}</th>
						<th style="text-align: center">{$method.size}</th>
					</tr>				
					{foreach item="file" from=$method.files}
						<tr>
							<td>{$file.name}</td>
							<td align="center">#filter:timestamp_time-{$file.timestamp}#</td>
							<td align="center">{$file.size}</td>
						</tr>
					{/foreach}
				{/if}
			{/foreach}
		</tbody>
	</table>
{/capture}

{sC_template_printBlock 
	title =	$smarty.const.__XPAY_BOLETO_FILE_QUEUE_LIST
	data  =	$smarty.capture.t_file_list_queue
}