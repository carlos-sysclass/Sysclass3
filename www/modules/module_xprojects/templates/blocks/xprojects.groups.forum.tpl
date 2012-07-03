{eF_template_printForumMessages data=$T_FORUM_MESSAGES forum_lessons_ID = $T_FORUM_LESSONS_ID limit = 10}

{eF_template_printBlock 
	title=$smarty.const._RECENTMESSAGESATFORUM 
	data=$smarty.capture.t_forum_messages_code 
	image='32x32/forum.png' 
	options=$T_FORUM_OPTIONS 
	link=$T_FORUM_LINK
}