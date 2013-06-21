{if $T_NEWS && $T_CURRENT_USER->coreAccess.news != 'hidden' && $T_CONFIGURATION.disable_news != 1}
	{capture name = "moduleNewsList"}
		{capture name='t_news_code'}
			{if $T_NEWS|@count > 0}
				<div id="comunicadosStudentContainer">
					<table class = "style1">
					{foreach name = 'news_list' item = "item" key = "key" from = $T_NEWS}
						<tr class="lesson{$item.lessons_ID}">
							<td>{$smarty.foreach.news_list.iteration}. <a title = "{$item.title}" href = "{$smarty.server.PHP_SELF}?ctg=news&view={$item.id}&lessons_ID=all&popup=1" target = "POPUP_FRAME" onclick = "sC_js_showDivPopup('{$smarty.const._ANNOUNCEMENT}', 1);">{$item.title}</a></td>
							<td class = "cpanelTime">#filter:user_login-{$item.users_LOGIN}#, <span title = "#filter:timestamp_time-{$item.timestamp}#">{$item.time_since}</span></td>
						</tr>
					{/foreach}
					</table>
				</div>
			{else}
				<div = "emptyCategory">{$smarty.const._NOANNOUNCEMENTSPOSTED}</div>
			{/if}
		{/capture}
		{*sC_template_printBlock title=$smarty.const._ANNOUNCEMENTS data=$smarty.capture.t_news_code image='32x32/announcements.png' array=$T_NEWS options = $T_NEWS_OPTIONS link = $T_NEWS_LINK*}
	{/capture}
{/if}
{$smarty.capture.t_news_code}