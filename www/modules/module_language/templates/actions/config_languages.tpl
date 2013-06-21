{capture name="t_config_language_block"}
<ul>
	<li>
		<label class="inline">Modo de Debug:</label>
	</li>
	<li>
		<label class="inline">Modo de Debug:</label>
	</li>
	<li>
		<label class="inline">Modo de Debug:</label>
	</li>
</ul>
{/capture}


{sC_template_printBlock
	title 			= $smarty.const.__LANGUAGE_CONFIGURATION_BLOCK_TITLE
	data			= $smarty.capture.t_config_language_block
}