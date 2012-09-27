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

<div class="headerTools">
	<span>
		Grupos Atuais:
	</span>
	{foreach name="group_loop" item = "group" from = $T_GRADEBOOK_GROUPS}
	<span class="gradebook-group-header" id="gradebook-group-header-{$group.id}">
    	<a href="javascript: void(0);" class="indexer-numbered">{$smarty.foreach.group_loop.iteration}</a>
        <a href="javascript: _sysclass('load', 'gradebook').loadGroupRules({$group.id});">{$group.name}</a>
	</span>
	{/foreach}
	<span>
    	<a href="javascript: void(0);" class="indexer-numbered">+</a>
        <a href="javascript: _sysclass('load', 'gradebook').addGroup();">Adicionar</a>
	</span>
	<!-- 
	<span>
    	<a href="javascript: void(0);" class="indexer-numbered">#</a>
        <a href="javascript: _sysclass('load', 'gradebook').addGroup();">Editar</a>
	</span>
	 -->
	<span>
    	<a href="javascript: void(0);" class="indexer-numbered">-</a>
        <a href="javascript: _sysclass('load', 'gradebook').deleteGroup();">Excluir</a>
	</span>
</div>

<div class="clear"></div>

<div class="headerTools">
	<span>
		<img src="{$T_GRADEBOOK_BASELINK|cat:'images/add.png'}" alt="{$smarty.const._GRADEBOOK_ADD_COLUMN}" title="{$smarty.const._GRADEBOOK_ADD_COLUMN}" style="vertical-align:middle">
		<a href="{$T_GRADEBOOK_BASEURL}&add_column=1&popup=1" target="POPUP_FRAME" onclick="eF_js_showDivPopup('{$smarty.const._GRADEBOOK_ADD_COLUMN}', 0)">{$smarty.const._GRADEBOOK_ADD_COLUMN}</a>&nbsp;
	</span>
	<!-- 
	<span>
		&nbsp;<img src="{$T_GRADEBOOK_BASELINK|cat:'images/compute_score.png'}" alt="{$smarty.const._GRADEBOOK_COMPUTE_SCORE_GRADE}" title="{$smarty.const._GRADEBOOK_COMPUTE_SCORE_GRADE}" style="vertical-align:middle">
		<a href="{$T_GRADEBOOK_BASEURL}&compute_score_grade=1">{$smarty.const._GRADEBOOK_COMPUTE_SCORE_GRADE}</a>&nbsp;
	</span>
	<span>
		&nbsp;<img src="{$T_GRADEBOOK_BASELINK|cat:'images/xls.png'}" alt="{$smarty.const._GRADEBOOK_EXPORT_EXCEL}" title="{$smarty.const._GRADEBOOK_EXPORT_EXCEL}" style="vertical-align:middle">
		<a href="javascript:void(0)" onclick="location=('{$T_GRADEBOOK_BASEURL}&export_excel='+Element.extend(this).next().options[this.next().options.selectedIndex].value)">{$smarty.const._GRADEBOOK_EXPORT_EXCEL}</a>
		<select id="excel" name="excel">
			<option value="one">{$smarty.const._GRADEBOOK_EXPORT_EXCEL_ONE}</option>
			<option value="all">{$smarty.const._GRADEBOOK_ALL_LESSONS}</option>
		</select>&nbsp;
	</span>
	 -->
</div>

<div id="gradebook-group-rules-container">
</div>


<div style="clear: both; height: 5px;"></div>
{/capture}

{eF_template_printBlock title=$smarty.const._GRADEBOOK_NAME data=$smarty.capture.t_gradebook_code image=$T_GRADEBOOK_BASELINK|cat:'images/gradebook_logo.png' absoluteImagePath = 1}