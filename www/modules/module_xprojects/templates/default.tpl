{if $T_XPROJECTS_MAIN_TEMPLATE}
	{include file="$T_XPROJECTS_BASEDIR/templates/$T_XPROJECTS_MAIN_TEMPLATE"}
{elseif $T_XPROJECTS_TEMPLATES && $T_XPROJECTS_TEMPLATES|@count == 1}
	{assign var="item" value=$T_XPROJECTS_TEMPLATES|@reset}
		
	{capture name=$index}
		{include file=$item.template}
	{/capture}
			
	{eF_template_printBlock
		title 			= $item.title
		data			= $smarty.capture.$index
		contentclass	= $item.contentclass
		class			= $item.class
		options			= $item.options
	}
{elseif $T_XPROJECTS_TEMPLATES && $T_XPROJECTS_TEMPLATES|@count > 1}
	{capture name="t_enrollment_tabbers"}
		{foreach name="edit_user_iteration" key="index" item="item" from=$T_XPROJECTS_TEMPLATES}
			{capture name=$index}
				{include file=$item.template}
			{/capture}
		
			{eF_template_printBlock
				tabber 			= $item.title 
				title 			= $item.title
				data			= $smarty.capture.$index
				class			= $item.class
				contentclass	= $item.contentclass
			}
		{/foreach}
	{/capture}
	
	{eF_template_printBlock 
		title="_XPROJECTS_TABS"
		data=$smarty.capture.t_enrollment_tabbers
		tabs = $T_XPROJECTS_TEMPLATES
	}
{else}
	{include file="$T_XPROJECTS_BASEDIR/templates/actions/$T_XPROJECTS_ACTION.tpl"}
{/if}
{include file="$T_XPROJECTS_BASEDIR/templates/includes/javascript.tpl"}