{include file="$T_XCMS_BASEDIR/templates/includes/javascript.tpl"}

{include file="$T_XCMS_BASEDIR/templates/includes/xcms.options.tpl"}

{if $T_XCMS_MAIN_TEMPLATE}
	{include file="$T_XCMS_BASEDIR/templates/$T_XCMS_MAIN_TEMPLATE"}
{elseif $T_XCMS_TEMPLATES && $T_XCMS_TEMPLATES|@count == 1}
	{assign var="item" value=$T_XCMS_TEMPLATES|@reset}

	{capture name=$index}
		{include file=$item.template}
	{/capture}
			
	{sC_template_printBlock
		title 				= $item.title
		sub_title			= $item.sub_title
		data				= $smarty.capture.$index
		contentclass		= $item.contentclass
		class				= $item.class
		options				= $item.options
		absoluteImagePath	= $item.absoluteImagePath
	}
{elseif $T_XCMS_TEMPLATES && $T_XCMS_TEMPLATES|@count > 1}
		
		{$T_EDITED_PAGE.layout}
		<div class="clearfix" id="layout_margin_top_rigth_index_student">
		{foreach key="index" item="section" from=$T_XCMS_SECTIONS}
			<div class="{$section.class}">
				{foreach key="index" item="blockname" from=$section.blocks}
					{assign var="item" value=$T_XCMS_TEMPLATES.$blockname}
					{if $item.template}
						{capture name=$index}
							{include file=$item.template}
						{/capture}
						
						{sC_template_printBlock
							title 			= $item.title
							sub_title		= $item.sub_title
							data			= $smarty.capture.$index
							class			= $item.class
							contentclass	= $item.contentclass
							options			= $item.options
							link			= $item.link
							absoluteImagePath	= $item.absoluteImagePath
						}
					{elseif $item.links}
						{sC_template_printBlock 
							title = $item.title
							columns = $item.columns
							links = $item.links
						}
					{/if}
				{/foreach}
			</div>
		{/foreach}
		</div>
		<div class="clear">
	{*/capture*}
	
	{*sC_template_printBlock 
		title=$smarty.const.__XCMS_TABS
		data=$smarty.capture.t_cms_tabbers
	*}
	
{else}
	{include file="$T_XCMS_BASEDIR/templates/actions/$T_XCMS_ACTION.tpl"}
{/if}

