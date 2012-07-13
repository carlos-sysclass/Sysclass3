{include file="$T_XENROLLMENT_BASEDIR/templates/includes/javascript.tpl"}

{include file="$T_XENROLLMENT_BASEDIR/templates/includes/xenrollment.options.tpl"}
{if $T_XENROLLMENT_MAIN_TEMPLATE}
	{include file="$T_XENROLLMENT_BASEDIR/templates/$T_MODULE_ENROLLMENT_TEMPLATE"}
	
	
	
{elseif $T_XENROLLMENT_TEMPLATES && $T_XENROLLMENT_TEMPLATES|@count == 1}
	{assign var="item" value=$T_XENROLLMENT_TEMPLATES[0]}
	{capture name=$index}
		{include file=$item.template}
	{/capture}
	{eF_template_printBlock
		title 			= $item.title
		data			= $smarty.capture.$index
		contentclass	= $item.contentclass
		class			= $item.class
	}
	

{elseif $T_XENROLLMENT_TEMPLATES && $T_XENROLLMENT_TEMPLATES|@count > 1}
	{capture name="t_enrollment_tabbers"}
		{foreach name="edit_user_iteration" key="index" item="item" from=$T_XENROLLMENT_TEMPLATES}
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
		tabs = $T_XENROLLMENT_TEMPLATES
	}
{else}
	{include file="$T_XENROLLMENT_BASEDIR/templates/actions/$T_XENROLLMENT_ACTION.tpl"}
{/if}