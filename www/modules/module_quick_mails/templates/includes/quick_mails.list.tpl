{capture name = "t_inner_table_mail_code"}
<ul class="default-list {$T_QUICK_MAILS_CONTACT_CLASS}">
	{foreach key="index" item="item" from=$T_QUICK_MAILS_CONTACT_LIST}
		<li>
			<a class="event-conf list-item" title="{$item.title}" href="{$item.href}" 
			{if $item.qm_type == 'link'}
				target="_blank"
			{else}
				onclick="sC_js_showDivPopup('{$item.title}', 3);" target="POPUP_FRAME"
			{/if}
			>
				<img src = "images/others/transparent.png" class="imgs_cont sprite{$item.image.size} sprite{$item.image.size}-{$item.image.name}" title="{$item.title}" alt="{$item.title}" border="0" />
				<span>{$item.title}</span>
				<div class="list-item-image">
					<img src="images/others/transparent.png" class="imgs_cont sprite16 sprite16-go_into" title="{$item.title}" alt="{$item.title}" border="0" />
				</div>
			</a>
		</li>
	{/foreach}
</ul>
{/capture}
{$smarty.capture.t_inner_table_mail_code}