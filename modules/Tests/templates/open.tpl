{extends file=$T_EXTENDS_FILE}
{block name="content"}
<form id="form-{$T_MODULE_ID}" role="form" method="post" action="/module/tests/execute/{$T_TEST.id}">
    {assign var="last_try" value=$T_TEST.executions|@end}

    {include file="./open_content.tpl" T_TEST=$T_TEST }

    <div class="form-actions nobg text-center">
        {if $T_TEST.test_repetition <= 0 || $T_TEST.executions|@count < $T_TEST.test_repetition}
        <button class="btn btn-primary" type="submit">
            {if $last_try}
                {translateToken value="Try again"}
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
{assign var="last_try" value=$T_TEST.executions|@end}
    <form id="form-{$T_MODULE_ID}" role="form" method="post" action="/module/tests/execute/{$T_TEST.id}">
        <div class="modal-footer">
            {if $T_TEST.test_repetition <= 0 || $T_TEST.executions|@count < $T_TEST.test_repetition}
            <button class="btn btn-primary" type="submit">
                    {if $last_try}
                        {translateToken value="Retry!"}
                    {else}
                        {translateToken value="Do it now!"}
                    {/if}
            </button>
            {/if}

            <button type="button" class="btn btn-default" data-dismiss="modal">
                {translateToken value="Close"}
            </button>
        </div>
    </form>
{/block}


