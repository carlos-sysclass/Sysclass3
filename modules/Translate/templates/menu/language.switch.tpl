{assign var="item" value=$T_MENU_ITEM}
{* T_MENU_INDEX *}
<li class="mega-menu-dropdown dropdown language">
    {foreach $item.items as $subitem}
        {if $item.type == 'language' && isset($subitem.selected) && $subitem.selected}
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" aria-expanded="true">
                <img alt="" src="{Plico_GetResource file="img/flags/`$subitem.country_code|strtolower`.png"}"/>
                <span class="username">
                     {$subitem.country_code|@strtoupper}
                </span>
                <i class="fa fa-angle-down"></i>
            </a>
            {break}
        {/if}
    {/foreach}

    {assign var="total_itens" value=$item.items|@count}
    {assign var="total_itens" value=4}
    {assign var="max_per_col" value=(($total_itens/3)|ceil)}

    <ul class="dropdown-menu" style="min-width: 700px;">
        <li>
            {assign var="new_column" value=true}
            <!-- Content container to add padding -->
            <div class="mega-menu-content">
                <div class="row">
                {foreach $item.items as $subitem}
                    {if $new_column}
                        <div class="col-md-4">
                        <ul class="mega-menu-submenu">
                        {assign var="new_column" value=false}
                    {/if}

                    {if isset($subitem.code) && $subitem.code}
                        <li>
                            <a href="#" data-callback="change-language" data-language="{$subitem.code}">
                                <img alt="" src="{Plico_GetResource file="img/flags/`$subitem.country_code|strtolower`.png"}"/> {$subitem.local_name}
                            </a>
                        </li>
                    {else}
                        <li class="divider"></li>
                        <li>
                            <a href="{$subitem.link}">{$subitem.text}</a>
                        </li>
                    {/if}

                    {if ($subitem@iteration is div by $max_per_col) || ($subitem@last)}
                        </ul>
                        </div>
                        {assign var="new_column" value=true}
                    {/if}
                {/foreach}
                </div>
            </div>
        </li>
    </ul>
</li>
<!--
                    {foreach $item.items as $subitem}
                        {if $item.type == 'language' && (!isset($subitem.selected) || !$subitem.selected)}
                            {if isset($subitem.code) && $subitem.code}
                                <li class="col-md-4">
                                    <a href="#" data-callback="change-language" data-language="{$subitem.code}">
                                        <img alt="" src="{Plico_GetResource file="img/flags/`$subitem.country_code|strtolower`.png"}"/> {$subitem.local_name}
                                    </a>
                                </li>
                            {else}
                                <li class="divider"></li>
                                <li>
                                    <a href="{$subitem.link}">{$subitem.text}</a>
                                </li>
                            {/if}
                        {/if}
                    {/foreach}
                    </ul>
                </li>
-->
