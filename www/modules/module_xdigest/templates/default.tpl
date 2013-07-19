{if $T_XDIGEST_MAIN_TEMPLATE}
	{include file="$T_XDIGEST_BASEDIR/templates/$T_XDIGEST_MAIN_TEMPLATE"}
{else}
	{include file="$T_XDIGEST_BASEDIR/templates/actions/$T_XDIGEST_ACTION.tpl"}
{/if}

{if $T_XDIGEST_TEMPLATES && $T_XDIGEST_TEMPLATES|@count == 1}
	{assign var="item" value=$T_XDIGEST_TEMPLATES|@reset}
		
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
{elseif $T_XDIGEST_TEMPLATES && $T_XDIGEST_TEMPLATES|@count > 1}
	{capture name="t_enrollment_tabbers"}
		{foreach name="edit_user_iteration" key="index" item="item" from=$T_XDIGEST_TEMPLATES}
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
		title="_XDIGEST_TABS"
		data=$smarty.capture.t_enrollment_tabbers
		tabs = $T_XDIGEST_TEMPLATES
	}
{/if}

{include file="$T_XDIGEST_BASEDIR/templates/includes/javascript.tpl"}
