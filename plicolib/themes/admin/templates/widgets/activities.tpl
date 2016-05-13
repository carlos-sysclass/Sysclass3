<div class="widget widget-activity">
	<div class="widget-body">
		{if isset($T_DATA.filters)}
			<ul class="filters notif" data-filter-list="{$T_DATA.id}">
				<li>Filtrar por:</li>
				{foreach key="index" item="filter" from=$T_DATA.filters}
					<li class="glyphicons {$filter.icon}" 
					{if $filter.title}data-toggle="tooltip" data-placement="bottom" data-original-title="{$filter.title}"{/if}
					data-filter-selector=".{$index}"><i></i></li>
				{/foreach}
				<li class="glyphicons more_windows active" data-toggle="tooltip" data-placement="bottom" data-original-title="Todos os Contatos" data-filter-selector="*">
					<i></i>
				</li>
			</ul>
			<div class="clearfix"></div>
		{/if}
		<ul class="activities" id="{$T_DATA.id}">
			{foreach item="activity" from=$T_DATA.activities}
			<li class="{$activity.type}" {if isset($T_DATA.selected) && ($T_DATA.selected == $activity.codigo)}selected{/if}>
				<span style="float: left" class="glyphicons activity-icon {$T_DATA.icons[$activity.type]}"><i></i></span>
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
					{include file="widgets/activities-child.tpl" T_CHILD=$activity.tree T_PARENT_DATA=$T_DATA}
				{/if}
			</li>
			{/foreach}
		</ul>
	</div>
</div>
