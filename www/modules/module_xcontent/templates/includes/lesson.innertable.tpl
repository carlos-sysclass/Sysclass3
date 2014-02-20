{capture name="t_xcontent_schedule"}

<h4>Orientações para realização das provas presenciais:</h4>
<p>Ao aluno compete:</p>
<p>1. Agendar antecipadamente o seu dia e horário para a realização da  prova, observando o calendário disponível do seu polo.</p>
<p>2. Conferir o endereço ,telefone e meios de transporte até o  local evitando atrasos.</p>
<p>3. Chegar 15 min antes do início de cada prova.</p>
<p>4. Ter em mãos sua identidade ou qualquer outro documento com foto, bem como sua senha e login para acessar o sistema. Caso o aluno não tenha sua senha e login, ligar para a ULTCuritiba (41) 3016-1212 ou no skipe atendimento.ult com Marcia ou Adriane.</p>
<p>5. Durante a prova, seguir as orientações do Coordenador de Polo para a realização da mesma.</p>
<p>6. Assinar a lista de presença no Polo de Apoio Presencial.</p>

<form action="{$smarty.server.REQUEST_URI}" method="post">
	{foreach item="schedule" from=$T_XCONTENT_SCHEDULE_CONTENTS}
	<h3>{$schedule.lesson_name} - {$schedule.name}</h3>
	<ul class="default-list">
		{foreach key = "week_day" item = "schedule_item" from=$schedule.schedules}
			{foreach name = "schedule_iterator" item = "schedule_item_hour" from=$schedule_item}
				{if $smarty.foreach.schedule_iterator.first}		
					<li style=" width: 46%; float: left; margin: 10px 1%; padding: 0px 1%;">Dia #filter:date-{$schedule_item_hour.start}# ({$T_XCONTENT_WEEKNAMES[$week_day]})
						<ul style="list-style: none;">
				{/if}
				
						<li style="border-bottom: none;">
							<input name="xcontent_schedule_item[{$schedule_item_hour.schedule_id}]" type="radio" value="{$schedule_item_hour.index}" {if $schedule.selected_option == $schedule_item_hour.index}checked="checked"{/if} >
							<span style="vertical-align: middle">
							{$schedule_item.selected_option}
							#filter:time-{$schedule_item_hour.start}# às #filter:time-{$schedule_item_hour.end}#
							</span>
						</li>
				{if $smarty.foreach.schedule_iterator.last}
					</ul>
				</li>
				{/if}
			{/foreach}
		{/foreach}
	</ul>
	<div class="clear"></div>
	{/foreach}
	<div align="center">
		<button class="flatButton" type="submit" name="{$T_MODULE_XUSER_BASIC_FORM.submit_xuser.name}" value="{$T_MODULE_XUSER_BASIC_FORM.submit_xuser.value}">
			<!-- <img width="24" height="24" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/white/bended_arrow_right.png">   -->
			<span>{$smarty.const._SAVE}</span>
		</button>
	</div>
</form>
{/capture}

{sC_template_printBlock
	title 			= $T_XCONTENT_SCHEDULE_ITEM
	data			= $smarty.capture.t_xcontent_schedule
	contentclass	= "blockContents"
	class			= ""
}
