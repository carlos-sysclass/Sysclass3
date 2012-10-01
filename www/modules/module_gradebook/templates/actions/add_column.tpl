{if $T_GRADEBOOK_MESSAGE}
	<script>
		var message = "{$T_GRADEBOOK_MESSAGE}";

		{literal}
		parent._sysclass("load","gradebook")._redirectAction(null, {
			"message" :	message, 
			"message_type" : "success"
		});
		
	/*
		re = /\?/;
		!re.test(parent.location) ? 
			parent.location = parent.location+'?message={$T_GRADEBOOK_MESSAGE}&message_type=success' : 
			parent.location = parent.location+'&message={$T_GRADEBOOK_MESSAGE}&message_type=success';
	*/
		{/literal}
	</script>
{/if}

{capture name = 't_add_column_code'}
	{$T_GRADEBOOK_ADD_COLUMN_FORM.javascript}
<form {$T_GRADEBOOK_ADD_COLUMN_FORM.attributes}>
	{$T_GRADEBOOK_ADD_COLUMN_FORM.hidden}
	<table>
		<tr>
			<td class="labelCell">{$T_GRADEBOOK_ADD_COLUMN_FORM.column_name.label}:&nbsp;</td>
			<td class="elementCell">{$T_GRADEBOOK_ADD_COLUMN_FORM.column_name.html}</td>
		</tr>
		<tr>
			<td class="labelCell">{$T_GRADEBOOK_ADD_COLUMN_FORM.column_group_id.label}:&nbsp;</td>
			<td class="elementCell">{$T_GRADEBOOK_ADD_COLUMN_FORM.column_group_id.html}</td>
		</tr>
		<tr>
			<td class="labelCell">{$T_GRADEBOOK_ADD_COLUMN_FORM.column_weight.label}:&nbsp;</td>
			<td class="elementCell">{$T_GRADEBOOK_ADD_COLUMN_FORM.column_weight.html}</td>
		</tr>
		<tr>
			<td class="labelCell">{$T_GRADEBOOK_ADD_COLUMN_FORM.column_refers_to.label}:&nbsp;</td>
			<td class="elementCell">{$T_GRADEBOOK_ADD_COLUMN_FORM.column_refers_to.html}</td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td></td>
			<td class="elementCell">{$T_GRADEBOOK_ADD_COLUMN_FORM.submit.html}</td>
		</tr>
	</table>
</form>
{/capture}
{eF_template_printBlock title=$smarty.const._GRADEBOOK_ADD_COLUMN data=$smarty.capture.t_add_column_code image=$T_GRADEBOOK_BASELINK|cat:'images/gradebook_logo.png' absoluteImagePath = 1}
