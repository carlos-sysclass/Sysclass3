<div class="title">{$smarty.const._MODULE_XLIVECHAT_NAME}</div>
<br />
<div class="list_user_chat">
{foreach item = "range" from = $T_LIST_USER}
<a onclick="javascript:chatWithRetorn('{$range.from}')" href="javascript: void(0);" 
title="{$smarty.const._MODULE_XLIVECHAT_NAME}" id="queue_{$range.from}" class="xlivechat_agua_atendimento">
<span>{$range.from}</span><span>({$range.queue})</span><br />
</a>
{/foreach}
</div>

<div class="xlivechat_conversa">
	<div class="xlivechat_conversa_title"></div>
	<div id="xlivechat_conversa_user">
	{*include file="$T_MODULE_XREQUEST_BASEDIR/templates/includes/xlivechat_messagens.tpl"*}
	</div>
</div>
