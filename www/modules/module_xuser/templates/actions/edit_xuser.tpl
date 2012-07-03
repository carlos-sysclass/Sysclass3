{if $T_XUSER_OPTIONS}
<div class="blockContents box border">
	<div class="headerTools">
		{foreach name="pagamento_options_iteration" key="option_index" item="option" from=$T_XUSER_OPTIONS}
			<span>
				<a href="{$option.href}" {if $option.selected} class="selected" {/if} title="{$option.hint}">
					<img src="{$option.image}">
					{$option.text}
				</a>
			</span>
		{/foreach}
	</div>
</div>
<div class="clear"></div>
{/if}

{capture name="t_edit_user_tabbers"}
	{foreach name="edit_user_iteration" key="index" item="item" from=$T_MODULE_XUSER_FORM_TABS}
		{capture name=$index}
			{include file=$item.template}
		{/capture}
	
		{eF_template_printBlock
			tabber 			= $item.title 
			title 			= $item.title
			data			= $smarty.capture.$index
			contentclass	= $item.contentclass
		}
	{/foreach}
{/capture}


{eF_template_printBlock 
	title=$smarty.const._MODULE_XUSER_EDITXUSER
	data=$smarty.capture.t_edit_user_tabbers
	tabs = $T_MODULE_XUSER_FORM_TABS
}