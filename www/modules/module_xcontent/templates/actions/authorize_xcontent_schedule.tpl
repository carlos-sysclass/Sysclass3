{capture name="t_xcontent_schedule"}
<!-- 
<h4>Local de Prova</h4>
<p>Polo {$T_XCONTENT_USERPOLO.nome}</p>
<p>{$T_XCONTENT_USERPOLO.endereco}, {$T_XCONTENT_USERPOLO.numero} {$T_XCONTENT_USERPOLO.complemento}</p>
<p>{$T_XCONTENT_USERPOLO.bairro} - {$T_XCONTENT_USERPOLO.cidade}/{$T_XCONTENT_USERPOLO.uf}</p>
<p>Contato: {$T_XCONTENT_USERPOLO.telefone}</p>

<h4>Orientações para realização das provas presenciais:</h4>
<p>Ao aluno compete:</p>
<p>1. Agendar antecipadamente o seu dia e horário para a realização da  prova, observando o calendário disponível do seu polo.</p>
<p>2. Conferir o endereço ,telefone e meios de transporte até o  local evitando atrasos.</p>
<p>3. Chegar 15 min antes do início de cada prova.</p>
<p>4. Ter em mãos sua identidade ou qualquer outro documento com foto, bem como sua senha e login para acessar o sistema. Caso o aluno não tenha sua senha e login, ligar para a ULTCuritiba (41) 3016-1212 ou no skipe atendimento.ult com Marcia ou Adriane.</p>
<p>5. Durante a prova, seguir as orientações do Coordenador de Polo para a realização da mesma.</p>
<p>6. Assinar a lista de presença no Polo de Apoio Presencial.</p>
 -->
<form action="{$smarty.server.REQUEST_URI}" method="post">
	<table id="_XCONTENT_SCHEDULE_LIST" class="display">
		<thead>
			<tr>
				{if $T_XCONTENT_IS_ADMIN}
					<th>Polo</th>
				{/if}
				<th>Data</th>
				<th>Horário</th>
				<th>Aluno</th>
				<th>Curso</th>
				<th>Turma</th>
				<th>Disciplina</th>
				{if !$T_XCONTENT_IS_ADMIN}
				<th>liberar</th>
				{/if}
			</tr>
		</thead>
		<tbody>
			{foreach key="polo_id" item="polo_schedule" from=$T_XCONTENT_SCHEDULE_CONTENTS}
			{foreach item="schedule" from=$polo_schedule}
			{foreach item = "schedule_user" from=$schedule.users}
				<tr>
					{if $T_XCONTENT_IS_ADMIN}
						<td>{$polo_id} - {$T_XCONTENT_POLOS[$polo_id].nome}</td>
					{/if}
					<td align="center">
						{if $schedule_user.scheduled }
							#filter:date-{$schedule_user.start}#
						{else}
							N/A
						{/if}
					</td>
					<td align="center">
						{if $schedule_user.scheduled }
							#filter:time-{$schedule_user.start}# / #filter:time-{$schedule_user.end}#
						{else}
							N/A
						{/if}
					</td>
					<td>{$schedule_user.fullname}</td>
					<td>{$schedule.course_name|eF_truncate:30}</td>
					<td>{$schedule.classe_name}</td>
					<td>{$schedule.lesson_name|eF_truncate:50}</td>
					{if !$T_XCONTENT_IS_ADMIN}
						<td align="center">
							<input name="xcontent_liberado[{$schedule_user.user_id}]" class="xcontent_liberation" type="checkbox" value="{$schedule_user.user_id}"
							onclick = "doAjaxContentLiberation('{$schedule_user.schedule_id}', '{$schedule_user.user_id}', this);"
							 />
							
						</td>
					{/if}
				</tr>
			{/foreach}
			{/foreach}
			{/foreach}	
		</tbody>
	</table>
	
	<div class="clear"></div>
	<div align="center">
		<button class="flatButton" type="submit" name="{$T_MODULE_XUSER_BASIC_FORM.submit_xuser.name}" value="{$T_MODULE_XUSER_BASIC_FORM.submit_xuser.value}">
			<!-- <img width="24" height="24" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/white/bended_arrow_right.png">   -->
			<span>{$smarty.const._SAVE}</span>
		</button>
	</div>
</form>
{/capture}

{eF_template_printBlock
	title 			= $T_XCONTENT_SCHEDULE_ITEM
	data			= $smarty.capture.t_xcontent_schedule
	contentclass	= "blockContents"
	class			= ""
}
