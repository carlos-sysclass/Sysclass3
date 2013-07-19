{include file="$T_GRADEBOOK_BASEDIR/templates/includes/dialog.add_group.tpl"}

{if $T_GRADEBOOK_MESSAGE}
	<script>
		re = /\?/;
		!re.test(parent.location) ? parent.location = parent.location+'?message={$T_GRADEBOOK_MESSAGE}&message_type=success' : parent.location = parent.location+'&message={$T_GRADEBOOK_MESSAGE}&message_type=success';
	</script>
{/if}

{capture name = 't_gradebook_code'}

{include file="$T_GRADEBOOK_BASEDIR/templates/includes/lesson_and_classe.switch.navbar.tpl"}
<div class="clear" style="margin-top: 10px;" ></div>

{include file="$T_GRADEBOOK_BASEDIR/templates/includes/action.switch.navbar.tpl"}
<div class="clear"></div>

<!-- 
<form method="post" action="#">
	<fieldset>
		<div>
			<label for="calculation_method">Forma de cálculo: </label>
			<select name="calculation_method">
				<option value="0">Selecione...</option>
				<option value="1">A nota final é sempre a maior nota do grupo.</option>
				<option value="2">A nota final é sempre a última nota do grupo.</option>
			</select>
		</div>
	</fieldset>
</form>
 -->
<table class="style1" style="margin-top: 10px;">
	<thead>
		<tr>
			<th>Nome</th>
			<th>Obrigatório</th>
			<th>Nota Mínima</th>
			<th>Intervalo</th>
			<th>Opções</th>
		</tr>
	</thead>
	<tbody>
		{foreach name = 'columns_loop' key = "id" item = "group" from = $T_GRADEBOOK_GROUPS}
			<tr class="gradebook-group-row" id="gradebook-group-row-{$group.id}">
				<td>{$group.name}</td>
				<td>{$group.require_descr}</td>
				<td>{$group.min_value}</td>
				<td>{$group.range}</td>
				<td>
					 <a class="gradebook-group-mode-down" href="javascript: _sysclass('load', 'gradebook').moveGroupDown({$group.id});"><img class="sprite16 sprite16-arrow_down" src="images/others/transparent.png" border="0"></a>
					 <a class="gradebook-group-mode-up" href="javascript: _sysclass('load', 'gradebook').moveGroupUp({$group.id});"><img class="sprite16 sprite16-arrow_up" src="images/others/transparent.png" border="0"></a>
				</td>
			</tr>
		{foreachelse}
			<tr>
				<td colspan="5">{$smarty.const._NODATAFOUND}</td>
			</tr>
		{/foreach}
	</tbody>
</table>

{/capture}

{sC_template_printBlock title=$smarty.const._GRADEBOOK_NAME data=$smarty.capture.t_gradebook_code image=$T_GRADEBOOK_BASELINK|cat:'images/gradebook_logo.png' absoluteImagePath = 1}
{include file="$T_GRADEBOOK_BASEDIR/templates/includes/javascript.tpl"}