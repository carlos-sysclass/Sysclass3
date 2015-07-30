{assign var="context" value=$T_DATA.data}
{foreach $context.content as $content}
    {if ($content.type == 'image')}
        <div align="center">
            <img class="img-responsive" src="{$content.url}"/>
        </div>
    {elseif ($content.type == 'text')}
        <div align="center">
            {$content.html}
        </div>
    {/if}
{/foreach}
