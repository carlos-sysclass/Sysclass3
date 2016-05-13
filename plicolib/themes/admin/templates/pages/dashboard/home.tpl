{extends file="layout/default-auth.tpl"}
{block name="content"}

<div class="innerLR">

{if $T_PAGE_LAYOUT}
	{foreach $T_PAGE_LAYOUT.rows as $row_id => $row}
		<div class="row-fluid">
		{foreach $row as $column_id => $column}
			<div class="span{$column.weight['md']} {if $T_PAGE_LAYOUT.sortable}column sortable{/if}">
			{if $T_WIDGETS|@count > 0}
				{foreach $T_WIDGETS as $widget}
					{if $widget.weight == $column_id}
						{if isset($widget.data.template)}
							{include file="`$widget.data.template`.tpl" T_DATA=$widget.data}
						{else if isset($widget.data.type)}
							{include file="widgets/`$widget.data.type`.tpl" T_DATA=$widget.data}
						{else}
							{include file="widgets/widget.tpl" T_DATA=$widget.data}
						{/if}
					{/if}
				{/foreach}
			{/if}
			</div>
		{/foreach}
		</div>
	{/foreach}
{else}
	{if $T_WIDGETS|@count > 0}
		{foreach $T_WIDGETS as $widget}
			{if $widget@first}
				<div class="row">
			{/if}
			{if $widget.type == 'separator'}
				</div>
				<div class="separator bottom"></div>
				{if !$widget@last}
				<div class="row">
				{/if}
			{/if}
			<div class="col-lg-{$widget.weight}">
				{if isset($widget.data.template)}
					{include file="`$widget.data.template`.tpl" T_DATA=$widget.data}
				{else}
					{include file="widgets/`$widget.type`.tpl" T_DATA=$widget.data}
				{/if}
			</div>
			{if $widget@last}
				</div>
				<div class="separator bottom"></div>
			{/if}
		{/foreach}
	{/if}
{/if}

	{*foreach item="widget" from=$T_WIDGETS*}
		{*include file="widgets/`$widget.type`.tpl" T_DATA=$widget.data*}
		<!-- <div class="separator bottom"></div> -->
	{*/foreach*}
	
<!--
	<div class="widget">
		<div class="widget-head">
			<h4 class="heading glyphicons cardio"><i></i><?php echo $translate->_('website_traffic'); ?></h4>
		</div>
		<div class="widget-body">
			<div class="btn-group separator bottom pull-right">
				<button id="websiteTraffic24Hours" class="btn btn-default">24 <?php echo $translate->_('hours'); ?></button>
				<button id="websiteTraffic7Days" class="btn btn-default">7 <?php echo $translate->_('days'); ?></button>
				<button id="websiteTraffic14Days" class="btn btn-default">14 <?php echo $translate->_('days'); ?></button>
				<button id="websiteTrafficClear" class="btn btn-default" disabled="disabled"><?php echo $translate->_('clear'); ?></button>
			</div>
			<div class="clearfix" style="clear: both;"></div>
			<div id="placeholder" style="height: 200px;"></div>
			<div id="overview" style="height: 40px;"></div>
		</div>
	</div>
-->
</div>
<div class="separator bottom"></div>
{/block}