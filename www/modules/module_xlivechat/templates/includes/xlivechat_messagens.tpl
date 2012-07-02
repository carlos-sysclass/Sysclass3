<div class="xlivechatboxcontent" id="xlivechatboxcontent">
	{foreach item = "chatmessagens" from = $T_LIST_MESSAGECHAT}
	<span><b>{$chatmessagens.from}</b>: {$chatmessagens.message}</span><br />
	{foreachelse}
	<span><b>Sem Mensagem</b></span><br />
	{/foreach}
</div>

<textarea class="xlivechatboxtextarea" onkeydown="javascript:return checkChatBoxInputKey(event,this,'{$T_LIST_TEXT_ACTION}');" ></textarea>