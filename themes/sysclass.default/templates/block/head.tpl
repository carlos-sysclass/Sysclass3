<meta charset="utf-8" />
<title>{if $T_CONFIGURATION.site_name}{$T_CONFIGURATION.site_name}{else}{$smarty.const._MAGESTERNAME}{/if} | {if $T_CONFIGURATION.site_motto}{$T_CONFIGURATION.site_motto}{else}{$smarty.const._THENEWFORMOFADDITIVELEARNING}{/if}</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport" />
<meta content="" name="description" />
<meta content="" name="author" />
<meta name="MobileOptimized" content="320">

{foreach item="css" from=$T_STYLESHEETS}
	<link rel="stylesheet" href="{Plico_GetResource file=$css}" />
{/foreach}
 
<link rel="shortcut icon" href="favicon.ico" />
    