<div id="message-container">
{if isset($T_MESSAGE)}
	<div class="plico-notify {$T_MESSAGE['type']}">{$T_MESSAGE['message']}</div>
{/if}
</div>