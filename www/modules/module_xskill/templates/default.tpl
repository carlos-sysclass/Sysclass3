{if $T_XSKILL_MAIN_TEMPLATE}
	{include file="$T_XSKILL_BASEDIR/templates/$T_XSKILL_MAIN_TEMPLATE"}
{elseif $T_XSKILL_TEMPLATES && $T_XSKILL_TEMPLATES|@count == 1}
	{assign var="item" value=$T_XSKILL_TEMPLATES|@reset}
		
	{capture name=$index}
		{include file=$item.template}
	{/capture}
			
	{sC_template_printBlock
		title 			= $item.title
		data			= $smarty.capture.$index
		contentclass	= $item.contentclass
		class			= $item.class
		options			= $item.options
	}
{elseif $T_XSKILL_TEMPLATES && $T_XSKILL_TEMPLATES|@count > 1}
	{capture name="t_enrollment_tabbers"}
		{foreach name="edit_user_iteration" key="index" item="item" from=$T_XSKILL_TEMPLATES}
			{capture name=$index}
				{include file=$item.template}
			{/capture}
		
			{sC_template_printBlock
				tabber 			= $item.title 
				title 			= $item.title
				data			= $smarty.capture.$index
				class			= $item.class
				contentclass	= $item.contentclass
			}
		{/foreach}
	{/capture}
	
	{sC_template_printBlock 
		title="_XSKILL_TABS"
		data=$smarty.capture.t_enrollment_tabbers
		tabs = $T_XSKILL_TEMPLATES
	}
{else}
	{include file="$T_XSKILL_BASEDIR/templates/actions/$T_XSKILL_ACTION.tpl"}
{/if}
{include file="$T_XSKILL_BASEDIR/templates/includes/javascript.tpl"}