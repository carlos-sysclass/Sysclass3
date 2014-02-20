{if $T_IES_FORM_TABS|@count == 0}


{elseif $T_IES_FORM_TABS|@count == 1}
	{assign var="item" value=$T_IES_FORM_TABS[0]}
	
	{capture name=$index}
		{include file=$item.template}
	{/capture}
		
	{sC_template_printBlock
		title 			= $T_IES_FORM_TABS_TITLE
		data			= $smarty.capture.$index
		contentclass	= $item.contentclass
		class			= $item.class
	}
{else}
	{capture name="t_add_course_tabbers"}
		{foreach name="add_course_iteration" key="index" item="item" from=$T_IES_FORM_TABS}
			{capture name=$index}
				{include file=$item.template}
			{/capture}
		
			{sC_template_printBlock
				tabber 			= $item.title 
				title 			= $item.title
				data			= $smarty.capture.$index
				contentclass	= $item.contentclass
				class			= $item.class
			}
		{/foreach}
	{/capture}
	
	{sC_template_printBlock 
		title= $T_IES_FORM_TABS_TITLE
		data=$smarty.capture.t_add_course_tabbers
		tabs = $T_IES_FORM_TABS
	}
{/if}