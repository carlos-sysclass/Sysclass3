<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    </button>
    {block name="dialog-title"}
    <h4 class="modal-title">
		{$T_PAGE_TITLE}
		{if isset($T_PAGE_SUBTITLE)}
			<small>{$T_PAGE_SUBTITLE}</small>
		{/if}
    </h4>
    {/block}
</div>
<div class="modal-body content-container">
	{block name="breadcrumb"}
		{include file="block/breadcrumb.tpl"}
	{/block}
	{if isset($T_MESSAGE)}
	<div class="alert alert-{$T_MESSAGE.type} alert-dismissable">
		<button data-dismiss="alert" class="close" type="button"></button>
		{$T_MESSAGE.message}
	</div>
	{/if}

	{block name="dialog-content"}1
	{/block}
</div>
{block name="dialog-footer"}
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">
        {translateToken value="Close"}
    </button>
</div>
{/block}


