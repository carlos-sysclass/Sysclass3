{capture name = "t_inner_table_mail_code"}
<ul class="default-list {$T_QUICK_MAILS_CONTACT_CLASS}">
	{foreach key="index" item="item" from=$T_QUICK_MAILS_CONTACT_LIST}
		<li>
			<a  class="event-conf list-item" title="{$item.title}" href = "{$item.href}" onclick = "eF_js_showDivPopup('{$item.title}', 3);" target = "POPUP_FRAME">
				<img 
					src = "images/others/transparent.png"
					class="imgs_cont sprite{$item.image.size} sprite{$item.image.size}-{$item.image.name}"
					title = "{$item.title}" 
					alt = "{$item.title}"
					border = "0"
				/>
				
				<span>{$item.title}</span>
				<div class="list-item-image">
					<img 
						src = "images/others/transparent.png"
						class="imgs_cont sprite16 sprite16-go_into"
						title = "{$item.title}" 
						alt = "{$item.title}"
						border = "0"
					/>
				</div>
			</a>
		</li>
	{/foreach}
</ul>
<!-- 
<div class="blockFooter" align="right">
		<img 
			src = "images/others/transparent.gif"
			class="sprite16 sprite16-conversation"
			border = "0"/>
		<a title="{$smarty.const.__QUICK_MAILS_ANOTHER_CONTACTS}" href = "{$T_QUICK_MAILS_BASEURL}">
			<span>{$smarty.const.__QUICK_MAILS_ANOTHER_CONTACTS}</span>
		</a>
</div>
 -->
{/capture}
{$smarty.capture.t_inner_table_mail_code}