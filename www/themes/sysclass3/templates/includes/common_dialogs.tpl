{if $T_LANGUAGE_LOAD_DIALOG}
	<div id="translation_tooltip">
		<form action="#">
			<select>
				{foreach item="lang" from=$T_LANGUAGE_LANGUAGES}
					<option value="{$lang}">{$lang}</option>
				{/foreach}
			</select>
			<div style="float: right">
				<a href="javascript: void(0);" id="language-save-tokens" type="submit">Save</a>&nbsp;
				<a href="javascript: void(0);" id="language-close-tokens" type="submit">Close</a>
			</div>
				<table id="translation_table" class="style1">
					<thead>
						<tr>
							<th>Token</th>
							<th>Value</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</form>
	</div>
{/if}