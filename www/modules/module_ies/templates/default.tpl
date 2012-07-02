{include file="$T_IES_BASEDIR/templates/includes/javascript.tpl"}
 
{if $T_IES_MAIN_TEMPLATE}
	{include file="$T_IES_BASEDIR/templates/$T_IES_MAIN_TEMPLATE"}
{else}
	{include file="$T_IES_BASEDIR/templates/actions/$T_IES_ACTION.tpl"}
{/if}