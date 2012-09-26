<div id="add-group-rule-dialog" class="form-container" title="Nova regra">
	<form>
		<div>
			<label for="name">Name</label>
			<input type="text" name="name" id="name" />
		</div>
	</form>
</div>


{if $T_GRADEBOOK_MESSAGE}
	<script>
		re = /\?/;
		!re.test(parent.location) ? parent.location = parent.location+'?message={$T_GRADEBOOK_MESSAGE}&message_type=success' : parent.location = parent.location+'&message={$T_GRADEBOOK_MESSAGE}&message_type=success';
	</script>
{/if}

{if $smarty.get.add_column}
{capture name = 't_add_column_code'}
	{$T_GRADEBOOK_ADD_COLUMN_FORM.javascript}
<form {$T_GRADEBOOK_ADD_COLUMN_FORM.attributes}>
	{$T_GRADEBOOK_ADD_COLUMN_FORM.hidden}
	<table style="margin-left:60px;">
		<tr>
			<td class="labelCell">{$T_GRADEBOOK_ADD_COLUMN_FORM.column_name.label}:&nbsp;</td>
			<td class="elementCell">{$T_GRADEBOOK_ADD_COLUMN_FORM.column_name.html}</td>
		</tr>
		<tr>
			<td class="labelCell">{$T_GRADEBOOK_ADD_COLUMN_FORM.column_group_id.label}:&nbsp;</td>
			<td class="elementCell">{$T_GRADEBOOK_ADD_COLUMN_FORM.column_group_id.html}</td>
		</tr>
		<tr>
			<td class="labelCell">{$T_GRADEBOOK_ADD_COLUMN_FORM.column_weight.label}:&nbsp;</td>
			<td class="elementCell">{$T_GRADEBOOK_ADD_COLUMN_FORM.column_weight.html}</td>
		</tr>
		<tr>
			<td class="labelCell">{$T_GRADEBOOK_ADD_COLUMN_FORM.column_refers_to.label}:&nbsp;</td>
			<td class="elementCell">{$T_GRADEBOOK_ADD_COLUMN_FORM.column_refers_to.html}</td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td></td>
			<td class="elementCell">{$T_GRADEBOOK_ADD_COLUMN_FORM.submit.html}</td>
		</tr>
	</table>
</form>
{/capture}
{eF_template_printBlock title=$smarty.const._GRADEBOOK_ADD_COLUMN data=$smarty.capture.t_add_column_code image=$T_GRADEBOOK_BASELINK|cat:'images/gradebook_logo.png' absoluteImagePath = 1}

{else}
{capture name = 't_gradebook_professor_code'}


<div>
	<select id="switch_lesson" name="switch_lesson">
		{foreach name = 'lessons_loop' key = "course_id" item = "course" from = $T_GRADEBOOK_GRADEBOOK_LESSONS}
			<optgroup label="{$course.name}">
			{foreach name = 'lessons_loop' key = "lesson_id" item = "lesson" from = $course.lessons}
					<option value="{$lesson.id}">{$lesson.name}</option>
			{/foreach}
			</optgroup>
		{/foreach}
	</select>
	<!--  LOAD CLASSES BY AJAX -->
	<select id="switch_classe" name="switch_classe">
		{*foreach name = 'lessons_loop' key = "lesson_id" item = "lesson" from = $course.lessons*}
<!-- 				<option value="{$lesson.id}">{$lesson.name}</option>  -->
		{*/foreach*}
	</select>
	
	&nbsp;<img src="{$T_GRADEBOOK_BASELINK|cat:'images/arrow_right.png'}" alt="{$smarty.const._GRADEBOOK_SWITCH_TO}" title="{$smarty.const._GRADEBOOK_SWITCH_TO}" style="vertical-align:middle">
 
	<a href="javascript:void(0)" onclick="location=('{$T_GRADEBOOK_BASEURL}&switch_lesson='+ jQuery('#switch_lesson').val())">{$smarty.const._GRADEBOOK_SWITCH_TO}</a>

	
	
</div>

<div class="clear" style="margin-top: 10px;" ></div>

<div class="headerTools">
	<span class="selected">
<!--     	<img alt="Regras para Cálculo" title="Regras para Cálculo" class="sprite16 sprite16-skills" src="images/others/transparent.gif">  -->
        <a href="javascript: void(0);">Regras para Cálculo</a>
	</span>
	<span>
<!--     	<img alt="Totais" title="Totais" class="sprite16 sprite16-rules" src="images/others/transparent.gif">  -->
        <a href="javascript: void(0);">Totais</a>
	</span>
	<!-- 
	<span>
    	<img alt="Totais" title="Totais" class="sprite16 sprite16-add" src="images/others/transparent.gif">
        <a href="javascript: void(0);">Ntas</a>
	</span>
	 -->
</div>

<div class="clear"></div>

<div class="headerTools">
	<span>
		Regras Atuais:
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
</div>

<div id="gradebook-group-rules-container">
</div>


<div style="clear: both; height: 5px;"></div>
<!-- 
<table class="sortedTable" style="width:100%">
{foreach name = 'users_loop' key = "id" item = "user" from = $T_GRADEBOOK_LESSON_USERS}
	<tr id="row_{$user.uid}" class="{cycle values = "oddRowColor, evenRowColor"} {if !$user.active}deactivatedTableElement{/if}">
		<td>#filter:login-{$user.users_LOGIN}#</td>
{foreach name = 'grades_loop' key = "id_" item = "grade" from = $user.grades}
		<td class="rightAlign">
			<input type="text" id="grade_{$grade.gid}" value="{$grade.grade}" size="5" maxlength="5" />
			<img class="ajaxHandle" src="{$T_GRADEBOOK_BASELINK|cat:'images/success.png'}" title="{$smarty.const._SAVE}" alt="{$smarty.const._SAVE}" onclick="changeGrade('{$grade.gid}', this)"/>
		</td>
		<td class="leftAlign">&nbsp;</td>
		<td class="leftAlign">&nbsp;</td>
{/foreach}
		<td class="centerAlign">{$user.score}</td>
		<td class="centerAlign">{$user.grade}</td>
		<td class="centerAlign">
			<input class="inputCheckbox" type="checkbox" name="checked_{$user.uid}" id="checked_{$user.uid}" onclick="publishGradebook('{$user.uid}', this);" {if ($user.publish == 1)} checked="checked"{/if} />
		</td>
	</tr>
{foreachelse}
	<tr class="defaultRowHeight oddRowColor">
		<td class="emptyCategory" colspan="100%">{$smarty.const._NODATAFOUND}</td>
	</tr>
{/foreach}
</table>
 -->
{/capture}

{eF_template_printBlock title=$smarty.const._GRADEBOOK_NAME data=$smarty.capture.t_gradebook_professor_code image=$T_GRADEBOOK_BASELINK|cat:'images/gradebook_logo.png' absoluteImagePath = 1}

<script>
{literal}
	/*function deleteColumn(el, id){

		Element.extend(el);
		url = '{/literal}{$T_GRADEBOOK_BASEURL}{literal}&delete_column='+id;

		var img = new Element('img', {id:'img_'+id, src:'{/literal}{$T_GRADEBOOK_BASELINK}{literal}images/progress1.gif'}).setStyle({position:'absolute'});
		img_id = img.identify();
		el.up().insert(img);

		new Ajax.Request(url, {
			method: 'get',
			asynchronous: true,
			onFailure: function(transport){
				img.writeAttribute({src:'{/literal}{$T_GRADEBOOK_BASELINK}{literal}images/delete.png', title:transport.responseText}).hide();
				new Effect.Appear(img_id);
				window.setTimeout('Effect.Fade("'+img_id+'")', 10000);
			},
			onSuccess: function(transport){
				img.hide();
				new Effect.Fade(el.up().up(), {queue:'end'});
			}
		});
	}*/

	/*function importGrades(el, id){

		Element.extend(el);
		url = '{/literal}{$T_GRADEBOOK_BASEURL}{literal}&import_grades='+id;

		var img = new Element('img', {id:'img_'+id, src:'{/literal}{$T_GRADEBOOK_BASELINK}{literal}images/progress1.gif'}).setStyle({position:'absolute'});
		img_id = img.identify();
		el.up().insert(img);

		new Ajax.Request(url, {
			method: 'get',
			asynchronous: true,
			onFailure: function(transport){
				img.writeAttribute({src:'{/literal}{$T_GRADEBOOK_BASELINK}{literal}images/import.png', title:transport.responseText}).hide();
				new Effect.Appear(img_id);
				window.setTimeout('Effect.Fade("'+img_id+'")', 10000);
			},
			onSuccess: function(transport){
				img.hide();
				new Effect.Appear(el.up(), {queue:'end'});
			}
		});
	}*/

	function publishGradebook(uid, el){

		var url = '{/literal}{$T_GRADEBOOK_BASEURL}{literal}&edit_publish=1&uid='+uid;
		var checked = $('checked_'+uid).checked;
		checked ? url += '&publish=1' : url += '&publish=0';
                
		var img_id = 'img_'+uid;
		var position = eF_js_findPos(el);
		var img = document.createElement("img");

		img.style.position = 'absolute';
		img.style.top = Element.positionedOffset(Element.extend(el)).top + 'px';
		img.style.left = Element.positionedOffset(Element.extend(el)).left + 6 + Element.getDimensions(Element.extend(el)).width + 'px';

		img.setAttribute("id", img_id);
		img.setAttribute('src', '{/literal}{$T_GRADEBOOK_BASELINK}{literal}images/progress1.gif');
		el.parentNode.appendChild(img);

		new Ajax.Request(url, {
			method: 'get',
			asynchronous: true,
			onSuccess: function (transport) {
				img.style.display = 'none';
				img.setAttribute('src', '{/literal}{$T_GRADEBOOK_BASELINK}{literal}images/success.png');
				new Effect.Appear(img_id);
				window.setTimeout('Effect.Fade("'+img_id+'")', 1500);
			}
		});
	}

	function changeGrade(gid, el){

		Element.extend(el);
		var grade = $('grade_'+gid).value;
		var url = '{/literal}{$T_GRADEBOOK_BASEURL}{literal}&change_grade='+gid+'&grade='+grade;

		var img = new Element('img', {id:'img_'+gid, src:'{/literal}{$T_GRADEBOOK_BASELINK}{literal}images/progress1.gif'}).setStyle({position:'absolute'});
		img_id = img.identify();
		el.up().insert(img);

		new Ajax.Request(url, {
			method: 'get',
			asynchronous: true,
			onFailure: function(transport){
				img.hide();
				alert(decodeURIComponent(transport.responseText));
			},
			onSuccess: function(transport){
				img.hide();
				new Effect.Appear(el.up(), {queue:'end'});
			}
		});
	}

	/*function exportExcel(el){

		element = document.getElementById(el);
		var selected = element.options[element.selectedIndex].value;
		var url = '{/literal}{$T_GRADEBOOK_BASEURL}{literal}&export_excel='+selected;

		new Ajax.Request(url, {
			method: 'get',
			asynchronous: true,
			onFailure: function(transport){
				alert(decodeURIComponent(transport.responseText));
			},
			onSuccess: function(transport){
			}
		});
	}*/

{/literal}
</script>

{/if}
