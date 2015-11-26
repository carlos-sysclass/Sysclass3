<ul class="ver-inline-menu ver-inline-notabbable ver-inline-menu-noarrow">
    <li class="active block-title">
        <a>
            <i class="{$T_DATA.icon}"></i>
            <span class="advidor-title">{$T_DATA.header}</span>
        </a>
    </li>
    <li class="active chat-loader" style="display: none;">
        <a>
            <i class="fa">
                <span class="fa fa-circle-o-notch fa-lg fa-spin"></span>
            </i>
            Connecting
        </a>
    </li>
    <li class="active block-error" style="display: none;">
        <a>
            <i class="{$T_DATA.icon}"></i>
            <span class="advidor-title">{translateToken value="Chat not avaliable"}</span>
        </a>
    </li>
</ul>
<div class="panel-body">
    {include "`$smarty.current_dir`/../blocks/table.tpl" T_MODULE_CONTEXT=$T_ADVISOR_QUEUE_LIST_CONTEXT T_MODULE_ID=$T_ADVISOR_QUEUE_LIST_CONTEXT.block_id FORCE_INIT=1}
</div>
