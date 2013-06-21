{if $T_USER_TYPE == 'administrator'}
	{include file="$T_XCOURSE_BASEDIR/templates/includes/xcourse.$T_XCOURSE_ACTION.administrator.tpl"}
{elseif $T_USER_TYPE == 'professor'}
	{include file="$T_XCOURSE_BASEDIR/templates/includes/xcourse.$T_XCOURSE_ACTION.professor.tpl"}
{elseif $T_USER_TYPE == 'student'}
	{include file="$T_XCOURSE_BASEDIR/templates/includes/xcourse.$T_XCOURSE_ACTION.student.tpl"}
{/if}