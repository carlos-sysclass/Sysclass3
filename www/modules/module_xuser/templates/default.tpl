{if $T_XREQUEST_MAIN_TEMPLATE}
	{include file="$T_XREQUEST_BASEDIR/templates/$T_XREQUEST_MAIN_TEMPLATE"}
{else}
	{include file="$T_XREQUEST_BASEDIR/templates/actions/$T_XREQUEST_ACTION.tpl"}
{/if}

{if $T_XREQUEST_TEMPLATES && $T_XREQUEST_TEMPLATES|@count == 1}
	{assign var="item" value=$T_XREQUEST_TEMPLATES|@reset}
		
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
{elseif $T_XREQUEST_TEMPLATES && $T_XREQUEST_TEMPLATES|@count > 1}
	{capture name="t_enrollment_tabbers"}
		{foreach name="edit_user_iteration" key="index" item="item" from=$T_XREQUEST_TEMPLATES}
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
		title="_XREQUEST_TABS"
		data=$smarty.capture.t_enrollment_tabbers
		tabs = $T_XREQUEST_TEMPLATES
	}
{/if}

{include file="$T_XREQUEST_BASEDIR/templates/includes/javascript.tpl"}
