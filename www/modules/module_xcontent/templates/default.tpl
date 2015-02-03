{include file="$T_MODULE_XCONTENT_BASEDIR/templates/includes/javascript.tpl"}
 
{if $T_MODULE_XCONTENT_MAIN_TEMPLATE}
	{include file="$T_MODULE_XCONTENT_BASEDIR/templates/$T_MODULE_XCONTENT_MAIN_TEMPLATE"}
{else}
	{include file="$T_MODULE_XCONTENT_BASEDIR/templates/actions/$T_MODULE_XCONTENT_ACTION.tpl"}
{/if}

{if $T_XCONTENT_MAIN_TEMPLATE}
	{include file="$T_XCONTENT_BASEDIR/templates/$T_XCONTENT_MAIN_TEMPLATE"}
{elseif $T_XCONTENT_TEMPLATES && $T_XCONTENT_TEMPLATES|@count == 1}
	{assign var="item" value=$T_XCONTENT_TEMPLATES|@reset}
		
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
{elseif $T_XCONTENT_TEMPLATES && $T_XCONTENT_TEMPLATES|@count > 1}
	{capture name="t_enrollment_tabbers"}
		{foreach name="edit_user_iteration" key="index" item="item" from=$T_XCONTENT_TEMPLATES}
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
		title="_XCONTENT_TABS"
		data=$smarty.capture.t_enrollment_tabbers
		tabs = $T_XCONTENT_TEMPLATES
	}
{else}
	{include file="$T_XCONTENT_BASEDIR/templates/actions/$T_XCONTENT_ACTION.tpl"}
{/if}
{include file="$T_XCONTENT_BASEDIR/templates/includes/javascript.tpl"}