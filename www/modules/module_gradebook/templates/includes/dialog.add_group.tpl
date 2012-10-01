<div id="add-group-rule-dialog" class="form-container" title="Novo Grupo">
	<form>
		<div>
			<label for="name">Nome</label>
			<input type="text" name="name" id="name" />
		</div>
		<div>
			<label for="require_status">Obrigatório</label>
			
			<select name="require_status" id="require_status">
				<option value="1">Sim</option>
				<option value="2">Obrigatório se nota abaixo do grupo anterior.</option>
				<option value="3">Opcional</option>
			</select>
		</div>
		<div>
			<label for="min_value">Nota Mínima</label>
			<select name="min_value" id="min_value">
				{section name=waistsizes start=1 loop=101}
    				<option value="{$smarty.section.waistsizes.index}">{$smarty.section.waistsizes.index}</option>
    			{/section}
			</select>
		</div>		
	</form>
</div>
