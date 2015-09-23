{if $T_PAGE_LAYOUT}
		{foreach $T_PAGE_LAYOUT.rows as $row_id => $row}
			<div class="row">
			{foreach $row as $column_id => $column}
				<div class="col-lg-{$column.weight['lg']} col-md-{$column.weight['md']} col-sm-{$column.weight['sm']} col-xs-{$column.weight['xs']} {if $T_PAGE_LAYOUT.sortable}column sortable{/if}">
				{if $T_WIDGETS|@count > 0}
					{foreach $T_WIDGETS as $widget}
						{if $widget.weight == $column_id}
							{* $widget|@json_encode *}
							{if isset($widget.template)}
								{include file="`$widget.template`.tpl" T_DATA=$widget.data}
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