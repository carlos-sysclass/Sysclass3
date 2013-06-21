<ul>
{foreach key="classe_id" item="classe" from=$T_XWEBTUTORIA_CLASSES}
	<li>
		{if !$T_XWEBTUTORIA_IN_LESSON_MODE}
			<span class="webtutoria-course-name">{$T_XWEBTUTORIA_COURSES[$classe_id]->course.name}</span>
			&nbsp;&raquo;&nbsp;
			<span class="webtutoria-classe-name">{$classe.name}</span>
		{/if}
		<ul>
			{if $T_XWEBTUTORIA_ITENS[$classe_id]}
				{foreach key="item_id" item="item" from=$T_XWEBTUTORIA_ITENS[$classe_id]}
					<li>{$item.body}</li>
				{/foreach}
			{else}
				{$smarty.const.__XWEBTUTORIA_NO_ITENS_FOUND}
			{/if}
		</ul>
	</li>
{/foreach}
</ul>