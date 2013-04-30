{if $T_CURRENT_USER->user.user_type == 'administrator' || $T_CURRENT_USER->user.user_type == 'professor'}
	<div class="headerTools">
		{if $T_CURRENT_USER->user.user_type == 'administrator'}
			<span {if $T_GRADEBOOK_ACTION == "edit_rule_calculation"}class="selected"{/if}>
		        <a href="{$T_GRADEBOOK_BASEURL}&action=edit_rule_calculation">Regras para Cálculo</a>
			</span>
			
			<span {if $T_GRADEBOOK_ACTION == "edit_total_calculation"}class="selected"{/if}>
		        <a href="{$T_GRADEBOOK_BASEURL}&action=edit_total_calculation">Ordem de Cálculo</a>
			</span>
		{/if}
		{if $T_GRADEBOOK_SELECTED_USER_NAME}
	        <a href="{$T_GRADEBOOK_BASEURL}&action=students_grades"><img src="images/back.png" alt="Voltar para as Notas dos Alunos" title="Voltar para as Notas dos Alunos"
	        															 style="width:35px; height: 35px; margin: -10px 0px;"></a>
			<span {if $T_GRADEBOOK_ACTION == "students_grades"}class="selected"{/if}>
				<strong>Aluno:</strong> {$T_GRADEBOOK_SELECTED_USER_NAME} {$T_GRADEBOOK_SELECTED_USER_SURNAME}({$T_GRADEBOOK_SELECTED_USER_LOGIN})
			</span>
			<span {if $T_GRADEBOOK_ACTION == "students_grades"}class="selected"{/if}>
				<strong>Turma:</strong> {$T_GRADEBOOK_SELECTED_USER_CLASSES}
			</span>
		{/if}
	</div>
{/if}