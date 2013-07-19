{include file="$T_XWEBTUTORIA_BASEDIR/templates/includes/javascript.tpl"}

{if $T_XWEBTUTORIA_MAIN_TEMPLATE}
	{include file="$T_XWEBTUTORIA_BASEDIR/templates/$T_XWEBTUTORIA_TEMPLATE"}	
{elseif $T_XWEBTUTORIA_TEMPLATES && $T_XWEBTUTORIA_TEMPLATES|@count == 1}
	{assign var="item" value=$T_XWEBTUTORIA_TEMPLATES|@reset}
	{capture name="item_block_data"}
		{include file=$item.template}
	{/capture}
	
	{sC_template_printBlock
		title 			= $item.title
		data			= $smarty.capture.item_block_data
		contentclass	= $item.contentclass
		class			= $item.class
		options			= $item.options
	}
{elseif $T_XWEBTUTORIA_TEMPLATES && $T_XWEBTUTORIA_TEMPLATES|@count > 1}
	{capture name="t_enrollment_tabbers"}
		{foreach name="edit_user_iteration" key="index" item="item" from=$T_XWEBTUTORIA_TEMPLATES}
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
		title=$smarty.const.__ENROLLMENT_TABS
		data=$smarty.capture.t_enrollment_tabbers
		tabs = $T_XWEBTUTORIA_TEMPLATES
	}
{else}

	
	{assign var="action_item" value="`$T_XWEBTUTORIA_BASEDIR`templates/actions/$T_XWEBTUTORIA_ACTION.tpl"}

	{include file="$action_item"}
{/if}

