{if $T_CHILD|@count > 0}
	<ul class="message-child">
		{foreach item="activity" from=$T_CHILD}
		<li {if isset($T_PARENT_DATA.selected) && $T_PARENT_DATA.selected == $activity.codigo}class="selected"{else}style="display: none;"{/if}>
			{if isset($activity.type)}
				<span style="float: left" class="glyphicons activity-icon {$T_PARENT_DATA.icons[$activity.type]}"><i></i></span>
			{/if}
			{if isset($activity.reply)}
				<a style="float: right" href="{$activity.reply}">Responder</a>
			{/if}
			<div class="media-body" >
				<p>{Plico_limitViewedChars text=$activity.message chars=1024}</p>
			</div>
			<div class="clearfix"></div>
			<div class="media-body">
				<blockquote>
					<div class="row-fluid">
						<div class="span4">
							<a title="" href="javascript: void(0);">{$activity.name} | {$activity.email}</cite></a>
						</div>
						<div class="span4 center">
							{if isset($activity.tree) && $activity.tree|@count > 0}
							<a title="" href="javascript: void(0);" class="toggle-childs">
								<span class="glyphicons activity-icon expand"><i></i></span>
							</a>
							{/if}
						</div>
						<div class="span4 right">
							<a title="" href="javascript: void(0);">{Plico_formatDBTimestamp data=$activity.dayof}</a>
						</div>
					</div>
				</blockquote>
			</div>
			{if isset($activity.tree) && $activity.tree|@count > 0}
				{include file="widgets/activities-child.tpl" T_CHILD=$activity.tree T_PARENT_DATA=$T_PARENT_DATA}
			{/if}
		{/foreach}
	</ul>
{/if}