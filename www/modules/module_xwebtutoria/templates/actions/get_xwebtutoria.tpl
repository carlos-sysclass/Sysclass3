{foreach key="classe_id" item="classe" from=$T_XWEBTUTORIA_CLASSES}
	{if $T_XWEBTUTORIA_ITENS[$classe_id]}
		{capture name="webtutoria_title"}
			<span class="webtutoria-course-name">{$T_XWEBTUTORIA_COURSES[$classe_id]->course.name}</span>
			&nbsp;&raquo;&nbsp;
			<span class="webtutoria-classe-name">{$classe.name}</span>
		{/capture}
			
		{capture name="webtutoria_body"}
			<ul class="xwebtutoria-list">		
				{foreach key="item_id" item="tutoria" from=$T_XWEBTUTORIA_ITENS[$classe_id]}
					<li class="lista grid_24">
						{include 
							file="$T_XWEBTUTORIA_BASEDIR/templates/includes/xwebtutoria.show_item.tpl"
							T_TUTORIA=$tutoria
						}
					</li>
				{/foreach}
			</ul>
			<div class="clear"></div>
		{/capture}
			
		{eF_template_printBlock
			title 			= $smarty.capture.webtutoria_title
			data			= $smarty.capture.webtutoria_body
			contentclass	= ""
			class			= ""
			options			= ""
			absoluteImagePath	= true
		}
	{/if}
{/foreach}