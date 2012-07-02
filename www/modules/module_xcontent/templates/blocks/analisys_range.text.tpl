<div class="xcontent-range-text">
	<div style="float: left;" class="xcontent-level-image">
		<div>IMAGEM</div>
	</div>
	<div style="float: left;" class="xcontent-level-text">
		<h3>{$smarty.const.__XCONTENT_WITHTHISLEVELYOU__}:</h3>
		<p>{$T_XCONTENT_USERLEVEL.text}</p>
		
		<div class="ui-progress-indicator">{$T_XCONTENT_USERSCORE}%</div>
		<div class="ui-progress-bar">{$T_XCONTENT_USERSCORE}</div>
		
		{if isset($T_XCONTENT_USERLEVEL.next_title)}
			<h4>{$T_XCONTENT_USERLEVEL.next_title}</h4>
			
			{foreach item="next_data" from=$T_XCONTENT_USERLEVEL.next}
				<h5>{$next_data.title}</h5>
				<p>{$next_data.text}</p>
			{/foreach}
		{/if}
	</div>
</div>
<div class="clear"></div>