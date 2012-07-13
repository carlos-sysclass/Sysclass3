<div class="headerTools">
	{if $T_XPAY_ACTION != "view_user_course_statement"}
		<span>
			<img 
				src = "images/others/transparent.png"
				class="imgs_cont sprite16 sprite16-go_into"
				border = "0"
			/>				
			<a href="{$T_XPAY_BASEURL}&action=view_user_course_statement&xuser_id={$T_XPAY_STATEMENT.user_id}&x{$T_XPAY_STATEMENT.module_type}_id={$T_XPAY_STATEMENT.module_id}">Ver extrato do Aluno</a>
		</span>
	{/if}
	{if $T_XPAY_IS_ADMIN && $T_XPAY_ACTION != "simulate_due_balance_negociation"}
		<span>
			<img 
				src = "images/others/transparent.png"
				class="imgs_cont sprite16 sprite16-go_into"
				border = "0"
			/>				
			<a href="{$T_XPAY_BASEURL}&action=simulate_due_balance_negociation&xuser_id={$T_XPAY_STATEMENT.user_id}&x{$T_XPAY_STATEMENT.module_type}_id={$T_XPAY_STATEMENT.module_id}">Simular (re)negociação</a>
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