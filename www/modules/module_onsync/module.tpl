{*Smarty template*}

{if $smarty.session.s_type == "administrator"}
    {capture name = 't_onsync_server'}
                {$T_ONSYNC_FORM.javascript}
                <form {$T_ONSYNC_FORM.attributes}>
                    {$T_ONSYNC_FORM.hidden}
                    <table class = "formElements">
                        <tr><td class = "labelCell">{$smarty.const._ONSYNC_ONSYNCSERVERNAME}:&nbsp;</td>
                            <td class = "elementCell">{$T_ONSYNC_FORM.server.html}</td>
                            <td class = "elementCell" align="left" width="100%">&nbsp;<a href="javascript:void(0)" onClick="document.getElementById('server_input').value = 'http://'" ><img src="images/16x16/go_into.png" title="{$smarty.const._ONSYNC_RESETDEFAULTSERVER}" alt="{$smarty.const._ONSYNC_RESETDEFAULTSERVER}" border =0 style="vertical-align:middle"/></a> </td>
                            <td class = "formError">{$T_ONSYNC_FORM.server.error}</td></tr>
                        <tr><td></td><td >&nbsp;</td></tr>
                        <tr><td></td><td class = "submitCell">{$T_ONSYNC_FORM.submit_onsync_server.html}</td></tr>
                    </table>
                </form>
    {/capture}

    {sC_template_printBlock title=$smarty.const._ONSYNC_ONSYNCSERVER data=$smarty.capture.t_onsync_server absoluteImagePath=1 image=$T_ONSYNC_MODULE_BASELINK|cat:'images/onsync32.png'}

{else}
    {if $smarty.get.add_onsync || $smarty.get.edit_onsync}
        {capture name = 't_insert_onsync_code'}
                    {$T_ONSYNC_FORM.javascript}
                    <form {$T_ONSYNC_FORM.attributes}>
                        {$T_ONSYNC_FORM.hidden}
                        <table class = "formElements">
                        	<tr><td class = "labelCell">{$T_ONSYNC_FORM.account_ID.label}:&nbsp;</td>
                                <td class = "elementCell">{$T_ONSYNC_FORM.account_ID.html}</td>
                                <td class = "formError">{$T_ONSYNC_FORM.account_ID.error}</td></tr>
                                
                            <tr><td class = "labelCell">{$T_ONSYNC_FORM.classes_ID.label}:&nbsp;</td>
                                <td class = "elementCell">{$T_ONSYNC_FORM.classes_ID.html}</td>
                                <td class = "formError">{$T_ONSYNC_FORM.classes_ID.error}</td></tr>
                                
                            <tr><td class = "labelCell">{$T_ONSYNC_FORM.topic.label}:&nbsp;</td>
                                <td class = "elementCell">{$T_ONSYNC_FORM.topic.html}</td>
                                <td class = "formError">{$T_ONSYNC_FORM.topic.error}</td></tr>
                            <tr><td class = "labelCell">{$smarty.const._ONSYNC_DATE}:&nbsp;</td>
                                <td class = "elementCell"><table><tr><td>{$T_ONSYNC_FORM.day.html}</td>
                                                                     <td>{$T_ONSYNC_FORM.month.html}</td>
                                                                     <td>{$T_ONSYNC_FORM.year.html}</td>
                                                                     </tr></table>
                            <tr><td class = "labelCell">{$smarty.const._ONSYNC_TIME}:&nbsp;</td>
                                <td class = "elementCell"><table><tr><td>{$T_ONSYNC_FORM.hour.html}</td>
                                                                     <td>{$T_ONSYNC_FORM.minute.html}</td>
                                                                     </tr></table>
                            <tr><td class = "labelCell">{$T_ONSYNC_FORM.timezone.label}:&nbsp;</td>
                                <td class = "elementCell">{$T_ONSYNC_FORM.timezone.html}</td>
                                <td class = "formError">{$T_ONSYNC_FORM.timezone.error}</td></tr>

                            <tr><td class = "labelCell">{$T_ONSYNC_FORM.duration.label}:&nbsp;</td>
                                <td class = "elementCell"><table><tr><td>{$T_ONSYNC_FORM.duration.html}</td>
                                                                     <td></td>
                                                                     </tr></table>
                            
                            <tr><td class = "labelCell">{$T_ONSYNC_FORM.friendly_url.label}:&nbsp;</td>
                                <td class = "elementCell">{$T_ONSYNC_FORM.friendly_url.html}</td>
                                <td class = "formError">{$T_ONSYNC_FORM.friendly_url.error}</td></tr>


                            <tr><td class = "labelCell">{$T_ONSYNC_FORM.password.label}:&nbsp;</td>
                                <td class = "elementCell">{$T_ONSYNC_FORM.password.html}</td>
                                <td class = "formError">{$T_ONSYNC_FORM.password.error}</td></tr>
                            <tr><td></td><td >&nbsp;</td></tr>

                            <tr><td></td><td class = "submitCell">{$T_ONSYNC_FORM.submit_onsync.html}</td></tr>
                        </table>
                    </form>

        {/capture}

        {capture name = 't_onsync_users'}
     
                            {literal}
                            <script>
                            function ajaxSendMails() {
                                var url =  '{/literal}{$T_ONSYNC_MODULE_BASEURL}&edit_onsync={$smarty.get.edit_onsync}&mail_users=1{literal}&postAjaxRequest=1';
                                if ($('onsyncUsersTable_currentFilter')) {
			                		url = url+'&filter='+$('onsyncUsersTable_currentFilter').innerHTML;
			             		}
                                $('mail_image').writeAttribute('src', 'images/others/progress1.gif').show();
                                new Ajax.Request(url, {
                                    method:'get',
                                    asynchronous:true,
                                    onSuccess: function (transport) {

                                    alert(transport.responseText + " {/literal}{$smarty.const._ONSYNC_EMAILSENTSUCCESFFULLY}{literal}");
                                    if (transport.responseText == "0") {
                                        $('mail_image').hide().setAttribute('src', 'images/16x16/error_delete');
                                    } else {
                                        $('mail_image').hide().setAttribute('src', 'images/16x16/success.png');
                                    }
                                    new Effect.Appear($('mail_image'));
                                    window.setTimeout('Effect.Fade("mail_image")', 2500);
                                    window.setTimeout("$('mail_image').writeAttribute('src', 'images/16x16/mail_forward.png')", 3500);
                                    window.setTimeout("new Effect.Appear($('mail_image'))", 3500);

                                    }
                                });
                            }
                            </script>
                            {/literal}

                    <table style = "width:100%">
                    <tr><td width="2%"><a href="javascript:void(0);" onClick="ajaxSendMails()"><img src= "images/16x16/mail_forward.png" id="mail_image" border = 0 /></a></td>
                        <td align="left">{$smarty.const._ONSYNC_NOTIFYUSERSVIAEMAIL}</td>
                    </tr>
                    </table>
                    
<!--ajax:onsyncUsersTable-->
                    <table style = "width:100%" class = "sortedTable" size = "{$T_USERS_SIZE}" sortBy = "0" id = "onsyncUsersTable" useAjax = "1" rowsPerPage = "20"  url = "{$T_ONSYNC_MODULE_BASEURL}&edit_onsync={$smarty.get.edit_onsync}&">
                        <tr class = "topTitle">
                            <td class = "topTitle" name="login">{$smarty.const._LOGIN}</td>
                            <td class = "topTitle" name="name">{$smarty.const._NAME}</td>
                            <td class = "topTitle" name="surname">{$smarty.const._SURNAME}</td>
                            <td class = "topTitle" name="email">{$smarty.const._EMAIL}</td>
                            <td class = "topTitle noSort" name="login" align="center">{$smarty.const._CHECK}</td>
                        </tr>

                        {foreach name = 'users_list' key = 'key' item = 'user' from = $T_USERS}
                            <tr class = "{cycle values = "oddRowColor, evenRowColor"}">
                                <td>
                                {if ($user.pending == 1)}
                                    <span style="color:red;">{$user.login}</span>
                                {else}
                                    {$user.login}
                                {/if}
                                </td>

                                <td>{$user.name}</td>
                                <td>{$user.surname}</td>
                                <td>{$user.email}</td>
                                <td align = "center">
                                    <span style="display:none" id="check_row{$user.login}">{if $user.meeting_ID == $smarty.get.edit_onsync}1{else}0{/if}</span>
                                    <input class = "inputCheckBox" type = "checkbox" onclick="javascript:ajaxPost('{$user.login}', this);" name = "check_{$user.login}" id = "check_row{$user.login}"
                                    {if $user.meeting_ID == $smarty.get.edit_onsync}
                                     checked
                                    {/if}
                                    >
                                </td>
                            </tr>
                        {foreachelse}
                            <tr><td colspan="5" class = "emptyCategory">{$smarty.const._NOUSERSFOUND}</td></tr>
                        {/foreach}
                        </table>
<!--/ajax:onsyncUsersTable-->
                {* Script for posting ajax requests regarding skill to employees assignments *}
                {literal}
                <script>
                // Wrapper function for any of the 2-3 points where Ajax is used in the module personal
                function ajaxPost(id, el, table_id) {
                     Element.extend(el);

                     var baseUrl =  '{/literal}{$T_ONSYNC_MODULE_BASEURL}{literal}&edit_onsync={/literal}{$smarty.get.edit_onsync}{literal}&postAjaxRequest=1';

                     if (id) {
                         var url = baseUrl + '&user=' + id + '&insert='+el.checked;
                         var img_id   = 'img_'+ id;
						if ($(table_id+'_currentFilter')) {
			                url = url+'&filter='+$(table_id+'_currentFilter').innerHTML;
			            }                         
                     } else if (table_id && table_id == 'onsyncUsersTable') {
                         el.checked ? url = baseUrl + '&addAll=1' : url = baseUrl + '&removeAll=1';
                         var img_id   = 'img_selectAll';
                         if ($(table_id+'_currentFilter')) {
			                url = url+'&filter='+$(table_id+'_currentFilter').innerHTML;
			             }
                     }

                     var position = sC_js_findPos(el);
                     var img      = document.createElement("img");

                     img.style.position = 'absolute';
                     img.style.top      = Element.positionedOffset(Element.extend(el)).top  + 'px';
                     img.style.left     = Element.positionedOffset(Element.extend(el)).left + 6 + Element.getDimensions(Element.extend(el)).width + 'px';

                     img.setAttribute("id", img_id);
                     img.setAttribute('src', 'images/others/progress1.gif');

                     el.parentNode.appendChild(img);

                       new Ajax.Request(url, {
                                 method:'get',
                                 asynchronous:true,
                                 onSuccess: function (transport) {
                                     // Update all form tables
                                     /*
                                     var tables = sort                    </form>edTables.size();
                                     var i;
                                     for (i = 0; i < tables; i++) {
                                         if (sortedTables[i].id == 'onsyncUsersTable') {
                                             sC_js_rebuildTable(i, 0, 'null', 'desc');
                                         }
                                     }
                                     */

                                     img.style.display = 'none';
                                     img.setAttribute('src', 'images/16x16/success.png');
                                     new Effect.Appear(img_id);
                                     window.setTimeout('Effect.Fade("'+img_id+'")', 2500);

                                     }
                            });
                }
                </script>
                {/literal}


        {/capture}

        {capture name = 't_onsync_tabber'}
            <div class="tabber" >
               <div class="tabbertab">
                    <h3>{$smarty.const._ONSYNC_SCHEDULEMEETING}</h3>
                    {sC_template_printBlock title = $smarty.const._ONSYNC_SCHEDULEMEETING data = $smarty.capture.t_insert_onsync_code image = '32x32/calendar.png'}
                </div>
                {if isset($smarty.get.edit_onsync)}
                    <div class="tabbertab{if $smarty.get.tab == "users" } tabbertabdefault {/if}">
                        <h3>{$smarty.const._ONSYNC_MEETINGATTENDANTS}</h3>
                        {sC_template_printBlock title = $smarty.const._ONSYNC_MEETINGATTENDANTS data = $smarty.capture.t_onsync_users image = '32x32/users.png'}
                    </div>
                {/if}
            </div>
        {/capture}

        {sC_template_printBlock title=$smarty.const._ONSYNC_ONSYNCMEETINGDATA data=$smarty.capture.t_onsync_tabber absoluteImagePath=1  image=$T_ONSYNC_MODULE_BASELINK|cat:'images/onsync32.png'}

    {else}
        {capture name = 't_onsync_list_code'}
            {if $T_ONSYNC_CURRENTLESSONTYPE == "professor"}
            <table>
                <tr><td>
                    <a href = "{$T_ONSYNC_MODULE_BASEURL}&add_onsync=1"><img src = "images/16x16/add.png" alt = "{$smarty.const._ONSYNC_ADDONSYNC}" title = "{$smarty.const._ONSYNC_ADDONSYNC}" border = "0" /></a>
                </td><td>
                    <a href = "{$T_ONSYNC_MODULE_BASEURL}&add_onsync=1" title = "{$smarty.const._ONSYNC_ADDONSYNC}">{$smarty.const._ONSYNC_ADDONSYNC}</a>
                </td></tr>
            </table>
            {/if}

            <table class="static" id = "module_onsync_sortedTable" border = "0" width = "100%" sortBy = "0">
                <tr class = "topTitle">
                    <td class = "topTitle">{$smarty.const._ONSYNC_TOPIC}</td>
                    <td class = "topTitle" width="20%">{$smarty.const._ONSYNC_DATE}</td>
                    <td class = "topTitle" width="20%">{$smarty.const._ONSYNCDURATION}</td>
                    <td class = "topTitle" width="20%">{$smarty.const._ONSYNC_FRIENDLY_URL}</td>
                    <td class = "topTitle" align="center">{$smarty.const._OPERATIONS}</td>
                </tr>

                {foreach name =onsync item=meeting from = $T_ONSYNC}
                <tr class = "{cycle values = "oddRowColor, evenRowColor"}">
                    <td>{if $T_ONSYNC_CURRENTLESSONTYPE != "student"}<a href = "{$T_ONSYNC_MODULE_BASEURL}&edit_onsync={$meeting.internal_ID}" class = "editLink">{$meeting.topic}</a>{else}{$meeting.name}{/if}</td>
                    <td>#filter:timestamp_time-{$meeting.timestamp}#</td>
                    <td>{$meeting.duration} min</td>
                    <td>{$meeting.friendly_url}</td>
                    <td align = "center">
                        {if $T_ONSYNC_CURRENTLESSONTYPE == "professor"}
	                        <table>
	                            <tr>
	                            <td width="30%">
	                            	<a href = "{$meeting.joining_url}"  class = "editLink" target="_blank"><img border = "0" src = "{$T_ONSYNC_MODULE_BASELINK}images/server_client_exchange.png" title = "{$smarty.const._ONSYNCJOINMEETING}" alt = "{$smarty.const._ONSYNCJOINMEETING}" /></a>
	                           	</td>
	                            <td width="30%">
	                                <a href = "{$T_ONSYNC_MODULE_BASEURL}&edit_onsync={$meeting.internal_ID}" class = "editLink"><img border = "0" src = "images/16x16/edit.png" title = "{$smarty.const._EDIT}" alt = "{$smarty.const._EDIT}" /></a>
	                            </td>
	                            <td width="30%">
	                                <a href = "{$T_ONSYNC_MODULE_BASEURL}&delete_onsync={$meeting.internal_ID}" onclick = "return confirm('{$smarty.const._ONSYNCAREYOUSUREYOUWANTTODELETEEVENT}')" class = "deleteLink"><img border = "0" src = "images/16x16/error_delete.png" title = "{$smarty.const._DELETE}" alt = "{$smarty.const._DELETE}" /></a>
	                            </td>
	                            </tr>
	                         </table>
                         {else}
                            {if $meeting.mayStart == "0"}
                            	<img border = "0" src = "{$T_ONSYNC_MODULE_BASELINK}images/server_client_exchange.png" class = "inactiveImage" title = "{$smarty.const._ONSYNCJOINMEETING}" alt = "{$smarty.const._ONSYNCJOINMEETING}" />
                            {elseif $meeting.mayStart == "1" }
                            	<a href = "{$meeting.joining_url}"  class = "editLink"><img border = "0" src = "{$T_ONSYNC_MODULE_BASELINK}images/server_client_exchange.png" title = "{$smarty.const._ONSYNCJOINMEETING}" alt = "{$smarty.const._ONSYNCJOINMEETING}" /></a>
                           	{/if}
                         {/if}
                    </td>
                </tr>
                {foreachelse}
                <tr><td colspan="5" class = "emptyCategory">{$smarty.const._ONSYNCNOMEETINGSCHEDULED}</td></tr>
                {/foreach}
            </table>
        {/capture}


        {sC_template_printBlock title=$smarty.const._ONSYNC_ONSYNCLIST data=$smarty.capture.t_onsync_list_code absoluteImagePath=1  image=$T_ONSYNC_MODULE_BASELINK|cat:'images/onsync32.png'}
    {/if}
{/if}

