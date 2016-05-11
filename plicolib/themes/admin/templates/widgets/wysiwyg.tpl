<div class="widget row-fluid">
	{if isset($T_DATA.action)}
	<form action="{$T_DATA.action}" method="post">
	{/if}
		<div class="widget-body">
			<textarea id="mustHaveId" class="wysihtml5 span12" rows="5" placeholder="Digite a sua mensagem" name="message"></textarea>
			<hr class="separator">
			<div class="form-actions">
				<button class="btn btn-icon btn-primary glyphicons circle_ok" type="submit"><i></i>Save</button>
				<button class="btn btn-icon btn-default glyphicons circle_remove" type="button"><i></i>Cancel</button>
			</div>

		</div>
	{if isset($T_DATA.action)}
	</form>
	{/if}
</div>