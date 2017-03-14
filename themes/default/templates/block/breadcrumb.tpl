{if (isset($T_BREADCRUMBS) && $T_BREADCRUMBS|@count > 0)}
<div class="row">
	<div class="col-md-12">
		<ul class="page-breadcrumb breadcrumb">

			{if ($T_ACTIONS && $T_ACTIONS|@count > 0)}
			<li class="btn-group">
				{if $T_ACTIONS|@count == 1}
					{assign var="action" value=$T_ACTIONS|@reset}
					<a href="{if isset($action.link)}{$action.link}{else}#{/if}"class="btn {if isset($action.class)}{$action.class}{/if}">
						{if isset($action.icon)}
							<i class="{$action.icon}"></i>
						{/if}
						{if isset($action.text)}
							{$action.text}
						{/if}
					</a>
				{else}
					<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" data-delay="1000" data-close-others="true">
						<span>{translateToken value="Actions"}</span> <i class="fa fa-angle-down"></i>
					</button>
					<ul class="dropdown-menu pull-right" role="menu">
					{foreach $T_ACTIONS as $key => $action}
						{if isset($action.separator) && $action.separator}
							<li class="divider"></li>
						{else}
							<li>
								<a href="{if isset($action.link)}{$action.link}{else}#{/if}" class="{if isset($action.class)}{$action.class}{/if}">
									{if isset($action.icon)}
										<i class="{$action.icon}"></i>
									{/if}
									{if isset($action.text)}
										{$action.text}
									{/if}
								</a>
							</li>
						{/if}
					{/foreach}
					</ul>
				{/if}
<!--
	<button type="button" class="btn blue dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true">
		<span>Actions</span> <i class="icon-angle-down"></i>
	</button>

-->
			</li>
			{/if}
			{foreach $T_BREADCRUMBS as $key => $bread}
				<li>

					{if isset($bread.icon)}
					   <i class="{$bread.icon}"></i>
					{/if}
					{if isset($bread.link)}
						<a href="{$bread.link}">{$bread.text}</a>
					{else}
						<span>{$bread.text}</span>
					{/if}
					{if !$bread@last}
					   <i class="icon-angle-right"></i>
					{/if}
				</li>
			{/foreach}
		</ul>
	</div>
</div>
{/if}
