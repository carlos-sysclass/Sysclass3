{if $T_THEME_SETTINGS->options.show_footer && $T_CONFIGURATION.show_footer}
 {if $T_CONFIGURATION.additional_footer}
 	<div class="clear"></div>
 	<div id="footer" class="footer">
  		{$T_CONFIGURATION.additional_footer}
  	</div>
 {else}
  <div id="footer" class="footer">
   <a href = "{$smarty.const._MAGESTERURL}">{$smarty.const._MAGESTERNAME}</a> (version {$smarty.const.G_VERSION_NUM}) &bull; {$T_VERSION_TYPE} Edition &bull; <a href = "index.php?ctg=contact">{$smarty.const._CONTACTUS}</a>
  </div>
 {/if}
{/if}