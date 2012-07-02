{* template functions for inner table *}
{capture name = 't_onsync_list_code'}
    <table border = "0" width = "100%">
        <tr class = "topTitle">
            <td class = "topTitle">{$smarty.const._ONSYNC_TOPIC}</td>
            <td class = "topTitle" width="20%">{$smarty.const._ONSYNC_DATE}</td>
            <td class = "topTitle" width="10%">{$smarty.const._ONSYNCDURATION}</td>
            <td class = "topTitle" align="center">{$smarty.const._OPERATIONS}</td>
        </tr>

        {foreach name =onsync item =meeting from = $T_ONSYNC_INNERTABLE}
        <tr class = "{cycle values = "oddRowColor, evenRowColor"}">
            <td>{if $T_ONSYNC_CURRENTLESSONTYPE != "student"}<a href = "{$T_ONSYNC_MODULE_BASEURL}&edit_onsync={$meeting.internal_ID}" class = "editLink">{$meeting.topic}</a>{else}{$meeting.topic}{/if}</td>
            <td><span title = " #filter:timestamp_time-{$meeting.timestamp}#">{$meeting.time_remaining}</span></td>
            <td>{$meeting.duration} min</td>
            <td align = "center">

            {if $meeting.status == 2}
                <img border = "0" src = "{$T_ONSYNC_MODULE_BASELINK}images/server_client_exchange.png" class = "inactiveImage" title = "{$smarty.const._ONSYNCFINISHED}" alt = "{$smarty.const._ONSYNCFINISHED}" />
            {else}
                {if $T_ONSYNC_CURRENTLESSONTYPE == "student"}
                   	<a href = "{$meeting.joining_url}" class = "editLink" target="_blank"><img border = "0" src = "{$T_ONSYNC_MODULE_BASELINK}images/server_client_exchange.png" title = "{$smarty.const._ONSYNCJOINMEETING}" alt = "{$smarty.const._ONSYNCJOINMEETING}" /></a>
                {else}
                   	<a href = "{$meeting.joining_url}"  class = "editLink" target="_blank"><img border = "0" src = "{$T_ONSYNC_MODULE_BASELINK}images/server_client_exchange.png" title = "{$smarty.const._ONSYNCJOINMEETING}" alt = "{$smarty.const._ONSYNCJOINMEETING}" /></a>
                {/if}
            {/if}
            </td>

        </tr>
        {foreachelse}
        <tr><td colspan="5" class = "emptyCategory">{$smarty.const._ONSYNCNOMEETINGSCHEDULED}</td></tr>
        {/foreach}
    </table>
{/capture}


{eF_template_printBlock title=$smarty.const._ONSYNC_ONSYNCLIST data=$smarty.capture.t_onsync_list_code absoluteImagePath=1 image=$T_ONSYNC_MODULE_BASELINK|cat:'images/onsync32.png' options=$T_MODULE_ONSYNC_INNERTABLE_OPTIONS}
