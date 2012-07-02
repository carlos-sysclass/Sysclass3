<ul class="xcontent_forum_lessons_list">
	{foreach key="lesson_id" item="lesson_messages" from=$T_FORUM_LESSON_MESSAGE}
		<li class="lesson_{$lesson_id}">
			<div>
				{eF_template_printForumMessages data=$lesson_messages forum_lessons_ID=$lesson_id limit=10 }
			</div>
		</li>
	{/foreach}
</ul>