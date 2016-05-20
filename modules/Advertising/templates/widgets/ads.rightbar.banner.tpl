{assign var="context" value=$T_DATA.data}
{foreach $context.content as $content}
    {if ($content.type == 'image')}
        <div align="center">
        	{if ($content.link)}
        	<a href="{$content.link}" target="_blank">
        	{/if}
            	<img class="img-responsive" src="{$content.url}"/>
            {if ($content.link)}
        	</a>
        	{/if}
        </div>
    {elseif ($content.type == 'text')}
        <div align="center">
            {$content.html}
        </div>
    {/if}
{/foreach}
