{capture name = 't_poll_code'}
	{if $T_ACTION == 'view' || !$T_POLL.isopen}
	<table>
		<tr><td class = "blockHeader" colspan = "100%">{$T_POLL.title}</td></tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td style = "text-align:left" colspan = "100%"><b>{$T_POLL.question}</b></td></tr>
		<tr><td>&nbsp;</td></tr>
		{section name = 'votes_list' loop = $T_POLL_VOTES}
		<tr>
			<td style = "text-align:left" width="20%">{$T_POLL_VOTES[votes_list].text}</td>
			<td style="text-align=left" width="30%">
				<img src="images/others/bar.jpg" width="{$T_POLL_VOTES[votes_list].width}" height="15"/>
			</td>
			<td style="text-align=left">{$T_POLL_VOTES[votes_list].perc*100}% </td>
		</tr>
		{/section}
	</table>
	{else}
		{$T_POLL_FORM.javascript}
		<form {$T_POLL_FORM.attributes}>
			{$T_POLL_FORM.hidden}
			<table class = "formElements" style = "width:100%">
				<tr><td class = "blockHeader" >{$T_POLL.title}</td></tr>
				<tr><td class = "">{$T_POLL.question}</td></tr>
				<tr><td class = "elementCell">{$T_POLL_FORM.options.html}</td></tr>
				<tr><td class = "submitCell">{$T_POLL_FORM.submit_poll.html}</td></tr>
				<tr><td>{$smarty.const._TOTALVOTES}: {$T_POLL_TOTALVOTES}</td></tr>
				<tr><td><a href="{$smarty.server.PHP_SELF}?ctg=forum&poll={$T_POLL.id}&action=view">{$smarty.const._VIEWRESULTS}</a></td></tr>
			</table>
		</form>
	{/if}
{/capture}
{$smarty.capture.t_poll_code}
