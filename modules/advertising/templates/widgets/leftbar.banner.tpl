{assign var="context" value=$T_DATA.data}

{foreach $context.images as $image}
    <div align="center">
    	<img width="100%" src="{Plico_GetResource file=$image}"/>
    </div>
{/foreach}
