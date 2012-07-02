{include file="$T_XPOLL_BASEDIR/templates/includes/javascript.tpl"}

{if $T_XPOLL_MAIN_TEMPLATE}
	{include file="$T_XPOLL_BASEDIR/templates/$T_XPOLL_TEMPLATE"}	
{elseif $T_XPOLL_TEMPLATES && $T_XPOLL_TEMPLATES|@count == 1}
	{assign var="item" value=$T_XPOLL_TEMPLATES|@reset}
	{capture name="item_block_data"}
		{include file=$item.template}
	{/capture}
	
	{eF_template_printBlock
		title 			= $item.title
		data			= $smarty.capture.item_block_data
		contentclass	= $item.contentclass
		class			= $item.class
		options			= $item.options
	}
{elseif $T_XPOLL_TEMPLATES && $T_XPOLL_TEMPLATES|@count > 1}
	{capture name="t_enrollment_tabbers"}
		{foreach name="edit_user_iteration" key="index" item="item" from=$T_XPOLL_TEMPLATES}
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
		title=$smarty.const.__ENROLLMENT_TABS
		data=$smarty.capture.t_enrollment_tabbers
		tabs = $T_XPOLL_TEMPLATES
	}
{else}

	
	{assign var="action_item" value="`$T_XPOLL_BASEDIR`templates/actions/$T_XPOLL_ACTION.tpl"}

	{include file="$action_item"}
{/if}

