{if $T_XLIVECHAT_MAIN_TEMPLATE}
	{include file="$T_XLIVECHAT_BASEDIR/templates/$T_XLIVECHAT_MAIN_TEMPLATE"}
{else}
	{include file="$T_XLIVECHAT_BASEDIR/templates/actions/$T_XLIVECHAT_ACTION.tpl"}
{/if}

{if $T_XLIVECHAT_TEMPLATES && $T_XLIVECHAT_TEMPLATES|@count == 1}
	{assign var="item" value=$T_XLIVECHAT_TEMPLATES|@reset}
		
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
{elseif $T_XLIVECHAT_TEMPLATES && $T_XLIVECHAT_TEMPLATES|@count > 1}
	{capture name="t_enrollment_tabbers"}
		{foreach name="edit_user_iteration" key="index" item="item" from=$T_XLIVECHAT_TEMPLATES}
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
		title="_XLIVECHAT_TABS"
		data=$smarty.capture.t_enrollment_tabbers
		tabs = $T_XLIVECHAT_TEMPLATES
	}
{/if}

{include file="$T_XLIVECHAT_BASEDIR/templates/includes/javascript.tpl"}