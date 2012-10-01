<div class="headerTools">
	{if $T_CURRENT_USER->user.user_type == 'administrator'}
		<span {if $T_GRADEBOOK_ACTION == "edit_rule_calculation"}class="selected"{/if}>
	        <a href="{$T_GRADEBOOK_BASEURL}&action=edit_rule_calculation">Regras para Cálculo</a>
		</span>
		
		<span {if $T_GRADEBOOK_ACTION == "edit_total_calculation"}class="selected"{/if}> 
	        <a href="{$T_GRADEBOOK_BASEURL}&action=edit_total_calculation">Ordem de Cálculo</a>
		</span>
	{/if}
	<span {if $T_GRADEBOOK_ACTION == "students_grades"}class="selected"{/if}> 
        <a href="{$T_GRADEBOOK_BASEURL}&action=students_grades">Notas dos alunos</a>
	</span>

	<!-- 
	<span>
    	<img alt="Totais" title="Totais" class="sprite16 sprite16-add" src="images/others/transparent.gif">
        <a href="javascript: void(0);">Ntas</a>
	</span>
	 -->
</div>