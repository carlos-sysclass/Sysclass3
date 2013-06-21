{capture name="t_xcontent_schedule"}
<h4>Local de Prova</h4>
<p>Polo {$T_XCONTENT_USERPOLO.nome}</p>
<p>{$T_XCONTENT_USERPOLO.endereco}, {$T_XCONTENT_USERPOLO.numero} {$T_XCONTENT_USERPOLO.complemento}</p>
<p>{$T_XCONTENT_USERPOLO.bairro} - {$T_XCONTENT_USERPOLO.cidade}/{$T_XCONTENT_USERPOLO.uf}</p>
<p>Contato: {$T_XCONTENT_USERPOLO.telefone}</p>
<br />
<h4>Orientações para realização das provas presenciais:</h4>
<p>Ao aluno compete:</p>
<p>1. Agendar antecipadamente o seu dia e horário para a realização da  prova, observando o calendário disponível do seu polo.</p>
<p>2. Conferir o endereço ,telefone e meios de transporte até o  local evitando atrasos.</p>
<p>3. Chegar 15 min antes do início de cada prova.</p>
<p>4. Ter em mãos sua identidade ou qualquer outro documento com foto, bem como sua senha e login para acessar o sistema. Caso o aluno não tenha sua senha e login, ligar para a ULTCuritiba (41) 3016-1212 ou no skipe atendimento.ult com Marcia ou Adriane.</p>
<p>5. Durante a prova, seguir as orientações do Coordenador de Polo para a realização da mesma.</p>
<p>6. Assinar a lista de presença no Polo de Apoio Presencial.</p>
<br />
<form action="{$smarty.server.REQUEST_URI}" method="post">
	{foreach name="content_iterator" item="schedule" from=$T_XCONTENT_SCHEDULE_CONTENTS}
		<div>
			<ul class="default-list">
			{foreach name="it_course" key="course_id" item="by_course" from=$schedule.grouped_content}
				<li>
					<div class="grid_12">
						<!-- <label>{$smarty.foreach.it_course.iteration}.</label> -->
						<a href="javascript: void(0);" onclick="jQuery(this).parent().next().slideToggle('fast');">{$T_XCONTENT_COURSES[$course_id]}</a>
					</div>
					<div class="colapse" style="margin-left: 20px;">
				{foreach name="it_lesson" key="lesson_id" item="by_lesson" from=$by_course}
						<div class="grid_24">
							<!-- <label>{$smarty.foreach.it_lesson.iteration}.</label> -->
							<a href="javascript: void(0);" onclick="jQuery(this).parent().next().slideToggle('fast');">
								{$T_XCONTENT_LESSONS[$lesson_id]} ({$by_lesson|@count} conteúdos)
							</a>
						</div>
						<div class="colapse">
							<ul>
							{foreach name="it_content" item="content_data" from=$by_lesson}
								<li style="margin-left: 40px;">
									<div class="grid_24 {if $content_data.option_index > 0}xcontentAlreadyScheduled{else}xcontentNoScheduled{/if} {if $content_data.required == 1}xcontentRequired{elseif $content_data.required == 0}xcontentNoRequired{/if}">
										<!-- <label>{$smarty.foreach.it_content.iteration}.</label> -->
										<span>{$content_data.content}</span>
										{if $content_data.option_index > 0}
											Agendamento Realizado : Dia #filter:date-{$content_data.option_start}#, entre #filter:time-{$content_data.option_start}# e #filter:time-{$content_data.option_end}#
											<a href="{$T_XCONTENT_BASEURL}&action=select_content_schedule_time&xschedule_id={$schedule.id}&xcontent_id={$content_data.content_id}">[ Alterar ]</a>
										{else}
											<a href="{$T_XCONTENT_BASEURL}&action=select_content_schedule_time&xschedule_id={$schedule.id}&xcontent_id={$content_data.content_id}">
												[ Agendar ]
											</a>  
										{/if}
									</div>
								</li>
							{/foreach}
							</ul>
						</div>
				{/foreach}
					</div>
				</li>
			{/foreach}
			
			
			
			
			
			<!-- 
			
				{assign var="required" value="-1"}
				{foreach name="it_content" item="content_data" from=$schedule.contents}
					{if $required != $content_data.required}
						{assign var="required" value=$content_data.required}
						{if $required}
							<li><h4>Agendamento Obrigatório</h4></li>
						{else}
							<li style="margin-top: 15px;"><h4>Agendamento Opcional</h4></li>
						{/if}
					{/if}
					<li>
						<div class="grid_24 {if $content_data.option_index > 0}xcontentAlreadyScheduled{else}xcontentNoScheduled{/if}">
							<label>{$smarty.foreach.it_content.iteration}.</label>
							<span>{$content_data.course}</span> &raquo;
							<span>{$content_data.lesson}</span> &raquo; 
							<span>{$content_data.content}</span>
							<div>				
							{if $content_data.option_index > 0}
								Agendamento Realizado : Dia #filter:date-{$content_data.option_start}#, entre #filter:time-{$content_data.option_start}# e #filter:time-{$content_data.option_end}#
								<a href="{$T_XCONTENT_BASEURL}&action=select_content_schedule_time&xschedule_id={$schedule.id}&xcontent_id={$content_data.content_id}">[ Alterar ]</a>
							{else}
								<a href="{$T_XCONTENT_BASEURL}&action=select_content_schedule_time&xschedule_id={$schedule.id}&xcontent_id={$content_data.content_id}">
									[ Agendar ]
								</a>  
							{/if}
							</div>
						</div>
					</li>
				{/foreach}
			-->	
			</ul>
		</div>
	{/foreach}
	<div class="clear"></div>
</form>
{/capture}

{eF_template_printBlock
	title 			= $T_XCONTENT_SCHEDULE_ITEM
	data			= $smarty.capture.t_xcontent_schedule
	contentclass	= "blockContents"
	class			= ""
}
