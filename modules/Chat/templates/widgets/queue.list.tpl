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

    <!--
    <div class="backgrid-table">
        <table class="table table-striped table-bordered table-hover table-full-width data-table queue-list" id="view-{$T_ADVISOR_QUEUE_LIST_CONTEXT.block_id}">
            <thead>
                <tr>
                    {foreach $T_ADVISOR_QUEUE_LIST_CONTEXT.datatable_fields as $field}
                        <th class="{$field.sClass} {if isset($field.sType)}{$field.sType}{/if}">
                            {if !isset($field.label)}
                                {$field.mData}
                            {else}
                                {translateToken value=$field.label}
                            {/if}
                        </th>
                    {/foreach}
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
    -->
</div>
<script type="text/template" id="queue-list-item-template">
    <% console.warn(model); %>
    {foreach $T_ADVISOR_QUEUE_LIST_CONTEXT.datatable_fields as $field}
        <td class="{$field.sClass} {if isset($field.sType)}{$field.sType}{/if}">
            {if $field.mData == "rawoptions"}
                <a href="javascript:;" class="btn btn-primary view-chat-action tooltips" data-original-title="{translateToken value='View Chat'}">
                    <i class="fa fa-eye"></i>
                </a>

               <a href="javascript: alert('not disponible yet');" class=" tooltips" data-original-title="{translateToken value='Assign to Me'}">
                    <i class="fa fa-user text-success"></i>
                    
                </a>
                <a href="javascript: alert('not disponible yet');" class=" tooltips" data-original-title="{translateToken value='Set Resolution'}">
                    <i class="fa fa-clock-o text-warning"></i>
                    
                </a>
                <a href="javascript: alert('not disponible yet');" class=" tooltips" data-original-title="{translateToken value='Delete Chat'}">
                    <i class="fa fa-close text-danger"></i>
                    
                </a>

                <div class="btn-group pull-right" style="margin-top:-6px;">
                    <a aria-expanded="false" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" href="javascript:;">
                        <i class="fa fa-cog"></i>
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu pull-right">
                        <li>
                            <a href="javascript: alert('not disponible yet');">
                                <i class="fa fa-user text-success"></i>
                                Assign to Me 
                            </a>
                        </li>
                        <li>
                            <a href="javascript: alert('not disponible yet');">
                                <i class="fa fa-clock-o text-warning"></i>
                                Set Resolution
                            </a>
                        </li>
                        <li>
                            <a href="javascript: alert('not disponible yet');">
                                <i class="fa fa-close text-danger"></i>
                                Delete Chat
                            </a>
                        </li>
                    </ul>
                </div>
            {else}
                <%= model.{$field.mData} %>
            {/if}
        </td>
    {/foreach}
    
    <% if (model.online) { %>
        <i class="fa fa-circle text-success"></i>
    <% } else { %>
        <i class="fa fa-circle text-danger"></i>
    <% } %>
    <%= model.requester.name %> (<%= model.requester.email %>)

    

</script>

<!--
<script type="text/template" id="queue-list-item-template">
    <% if (model.online) { %>
        <i class="fa fa-circle text-success"></i>
    <% } else { %>
        <i class="fa fa-circle text-danger"></i>
    <% } %>
    <%= model.requester.name %> (<%= model.requester.email %>)
    <div class="btn-group pull-right" style="margin-top:-6px;">
        <a aria-expanded="false" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" href="javascript:;">
            <i class="fa fa-cog"></i>
            <i class="fa fa-angle-down"></i>
        </a>
        <ul class="dropdown-menu pull-right">
            <li>
                <a href="javascript:;" class="view-chat-action">
                    <i class="fa fa-eye text-primary"></i>
                    Open Chat
                </a>
            </li>
            <li>
                <a href="javascript: alert('not disponible yet');">
                    <i class="fa fa-user text-success"></i>
                    Assign to Me 
                </a>
            </li>
            <li>
                <a href="javascript: alert('not disponible yet');">
                    <i class="fa fa-clock-o text-warning"></i>
                    Set Resolution
                </a>
            </li>
            <li>
                <a href="javascript: alert('not disponible yet');">
                    <i class="fa fa-close text-danger"></i>
                    Delete Chat
                </a>
            </li>
        </ul>
    </div>

</script>
-->
