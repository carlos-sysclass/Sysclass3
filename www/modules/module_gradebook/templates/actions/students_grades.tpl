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

<div id="gradebook-loading" style="width: 100%; text-align: center; padding-top: 50px;">
    <img src="{$T_GRADEBOOK_BASELINK|cat:'images/progress1.gif'}" title="{$smarty.const._SAVE}" alt="{$smarty.const._SAVE}" /> Carregando
</div>

<div id="gradebook-group-grades-container">
</div>

{/capture}

{sC_template_printBlock title=$smarty.const._GRADEBOOK_NAME data=$smarty.capture.t_gradebook_code image=$T_GRADEBOOK_BASELINK|cat:'images/gradebook_logo.png' absoluteImagePath = 1}
{include file="$T_GRADEBOOK_BASEDIR/templates/includes/javascript.tpl"}
