{extends file=$T_EXTENDS_FILE}
{block name="content"}
<form id="form-{$T_MODULE_ID}" role="form" method="post" action="/module/tests/execute/{$T_TEST.id}">
    {assign var="last_try" value=$T_TEST.executions|@end}

    {include file="./open_content.tpl" T_TEST=$T_TEST }

    <div class="form-actions nobg text-center">
        {if $T_TEST.test_repetition <= 0 || $T_TEST.executions|@count < $T_TEST.test_repetition}
        <button class="btn btn-primary" type="submit">
            {if $last_try}
                {translateToken value="Try Again"}
            {else}
                {translateToken value="Start!"}
            {/if}
        </button>
        {/if}
    </div>
</form>
{/block}

{block name="dialog-content"}
    {assign var="last_try" value=$T_TEST.executions|@end}
    {include file="./open_content.tpl" T_TEST=$T_TEST }
{/block}
{block name="dialog-footer"}
<div class="modal-footer">
    {if $T_TEST.test_repetition <= 0 || $T_TEST.executions|@count < $T_TEST.test_repetition}
    <a href="/module/tests/open/{$T_TEST.id}" class="btn btn-primary">
        {translateToken value="Do now!"}
    </a>
    {/if}
    <button type="button" class="btn btn-default" data-dismiss="modal">
        {translateToken value="Close"}
    </button>
</div>
{/block}


