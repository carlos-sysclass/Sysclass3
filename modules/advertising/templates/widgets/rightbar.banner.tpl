{assign var="context" value=$T_DATA.data}
{foreach $context.images as $image}
<div class="panel panel-default">
    <div class="panel-body"  align="center">
        <img width="100%" src="{Plico_GetResource file=$image}"/>
    </div>
</div>
{/foreach}


<!--


    <div align="center">
        <img width="100%" src="/assets/default/img/ads/rightbar.banner1.jpg">
    </div>
    <div align="center">
        <img width="100%" src="/assets/default/img/ads/rightbar.banner2.jpg">
    </div>
    <div align="center">
        <img width="100%" src="/assets/default/img/ads/rightbar.banner3.jpg">
    </div>

            </div>
   </div>
-->
