{foreach key="module_key" item="link" from=$T_TOP_LINKS}
<a href="{$link.href}" title="{$module_key}" id="{$module_key}_change_lesson_id">
	<button class="inputo-top-{$module_key}" type="button">
	  	<img class="inputo-top-{$module_key}-icon" src="images/others/transparent.png">
	</button>
</a>
{/foreach}