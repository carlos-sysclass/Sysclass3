{if $T_AWS_MAIN_TEMPLATE}
	{include file="$T_AWS_BASEDIR/templates/$T_AWS_MAIN_TEMPLATE"}
{else}
	{include file="$T_AWS_BASEDIR/templates/actions/$T_AWS_ACTION.tpl"}
{/if}

{if $T_AWS_TEMPLATES && $T_AWS_TEMPLATES|@count == 1}
	{assign var="item" value=$T_AWS_TEMPLATES|@reset}
		
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
{elseif $T_AWS_TEMPLATES && $T_AWS_TEMPLATES|@count > 1}
	{capture name="t_enrollment_tabbers"}
		{foreach name="edit_user_iteration" key="index" item="item" from=$T_AWS_TEMPLATES}
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
		title="_AWS_TABS"
		data=$smarty.capture.t_enrollment_tabbers
		tabs = $T_AWS_TEMPLATES
	}
{/if}

{include file="$T_AWS_BASEDIR/templates/includes/javascript.tpl"}