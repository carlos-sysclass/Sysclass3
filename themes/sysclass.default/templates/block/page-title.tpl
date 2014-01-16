{if isset($T_PAGE_TITLE)}
	<div class="row">
		<div class="col-md-12">
				<h3 class="page-title">
					{$T_PAGE_TITLE}
					{if isset($T_PAGE_SUBTITLE)}
						<small>{$T_PAGE_SUBTITLE}</small>
					{/if}
				</h3>
		</div>
	</div>
{/if}