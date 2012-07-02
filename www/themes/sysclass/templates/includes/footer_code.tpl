{if $T_THEME_SETTINGS->options.show_footer && $T_CONFIGURATION.show_footer}
 {if $T_CONFIGURATION.additional_footer}
  {$T_CONFIGURATION.additional_footer}
 {else}
  <div>
  	<a href = "{$smarty.const._MAGESTERURL}">{$smarty.const._MAGESTERNAME}</a> 
  	({$smarty.const._VERSION} {$smarty.const.G_VERSION_NUM}) <!-- &nbsp;&bull;&nbsp; {$T_VERSION_TYPE} &nbsp;&bull;&nbsp; <a href = "index.php?ctg=contact">{$smarty.const._CONTACTUS}</a>--></div>
 {/if}
{/if}
