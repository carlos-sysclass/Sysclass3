{*include file="$T_MODULE_POLOS_BASEDIR/templates/includes/payment_options.tpl"*}

{if $T_MODULE_POLO_MAIN_TEMPLATE}
	{include file="$T_MODULE_POLOS_BASEDIR/templates/$T_MODULE_POLOS_MAIN_TEMPLATE"}
{else}
	{include file="$T_MODULE_POLOS_BASEDIR/templates/actions/$T_MODULE_POLOS_ACTION.tpl"}
{/if}

{*include file="$T_MODULE_POLOS_BASEDIR/templates/includes/javascript.tpl"*}