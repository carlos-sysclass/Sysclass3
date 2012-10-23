<div class="headerTools">
	{if $T_XPAY_IS_ADMIN}
	<span>
		<img 
			src = "images/others/transparent.png"
			class="imgs_cont sprite16 sprite16-generic"
			border = "0"
		/>				
		<a href="{$smarty.const.G_SERVERNAME}?ctg=users&edit_user={$T_XPAY_STATEMENT.login}&op=account">{$smarty.const._EDITUSER}</a>
	</span>
	<span>
		<img 
			src = "images/others/transparent.png"
			class="imgs_cont sprite16 sprite16-user_timeline"
			border = "0"
		/>				
		<a href="{$smarty.server.PHP_SELF}?ctg=users&edit_user={$T_XPAY_STATEMENT.login}&op=status">{$smarty.const._LEARNINGSTATUS}</a>
	</span>
	{/if}
	{if $T_XPAY_ACTION != "view_user_course_statement"}
		<span>
			<img 
				src = "images/others/transparent.png"
				class="imgs_cont sprite16 sprite16-go_into"
				border = "0"
			/>				
			<a href="{$T_XPAY_BASEURL}&action=view_user_course_statement&negociation_id={$T_XPAY_STATEMENT.id}">Ver extrato do Aluno</a>
		</span>
	{/if}
	{if $T_XPAY_IS_ADMIN && $T_XPAY_ACTION != "edit_negociation"}
		<span>
			<img 
				src = "images/others/transparent.png"
				class="imgs_cont sprite16 sprite16-go_into"
				border = "0"
			/>				
			<a href="{$T_XPAY_BASEURL}&action=edit_negociation&negociation_id={$T_XPAY_STATEMENT.id}">Editar Negociação</a>
		</span>
	{/if}
	{if $T_XPAY_ACTION != "do_payment"}
	<span>
		<img 
			src = "images/others/transparent.png"
			class="imgs_cont sprite16 sprite16-arrow_right"
			border = "0"
		/>				
		<a href="{$T_XPAY_BASEURL}&action=do_payment&negociation_id={$T_XPAY_STATEMENT.id}">Realizar Pagamento</a>
	</span>
	{/if}
</div>
<div class="clear"></div>  