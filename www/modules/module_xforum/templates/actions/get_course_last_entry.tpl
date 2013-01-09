{capture name='t_forum_messages_code'}
	<h3>{$smarty.const.__XFORUM_LAST_ENTRIES}</h3>
	{eF_template_printForumMessages 
		data=$T_XFORUM_MESSAGES 
		forum_lessons_ID = $T_XFORUM_EDIT_LESSON.id 
		limit = $T_XFORUM_MESSAGE_LIMIT
	}
{/capture}

<div class="blockContents">
	{$smarty.capture.t_forum_messages_code}
</div>
