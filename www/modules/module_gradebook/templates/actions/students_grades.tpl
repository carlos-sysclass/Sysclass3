{*include file="$T_GRADEBOOK_BASEDIR/templates/includes/dialog.add_group.tpl"*}

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
        <a href="javascript: _sysclass('load', 'gradebook').loadGroupGrades({$group.id});">{$group.name}</a>
	</span>
	{/foreach}
</div>

<div id="gradebook-group-grades-container">
</div>

{/capture}

{eF_template_printBlock title=$smarty.const._GRADEBOOK_NAME data=$smarty.capture.t_gradebook_code image=$T_GRADEBOOK_BASELINK|cat:'images/gradebook_logo.png' absoluteImagePath = 1}

<script>
{literal}
	function changeGrade(gid, el){

		Element.extend(el);
		var grade = $('grade_'+gid).value;
		var url = '{/literal}{$T_GRADEBOOK_BASEURL}{literal}&action=change_grade&gid='+gid+'&grade='+grade;

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
{/literal}
</script>
{include file="$T_GRADEBOOK_BASEDIR/templates/includes/javascript.tpl"}