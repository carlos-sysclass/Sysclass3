{* Smarty template for includes/personal.php *}
<script>{if $T_BROWSER == 'IE6'}{assign var='globalImageExtension' value='gif'}var globalImageExtension = 'gif';{else}{assign var='globalImageExtension' value='png'}var globalImageExtension = 'png';{/if}</script>
<script>

 var areYouSureYouWantToCancelConst ='{$smarty.const._AREYOUSUREYOUWANTTOCANCELJOB}';
 var sessionType ='{$smarty.session.s_type}';
 var editUserLogin ='{$smarty.get.edit_user}';
 var operationCategory ='{$smarty.get.op}';
 var jobAlreadyAssignedConst ='{$smarty.const._JOBALREADYASSIGNED}';
 var jobDoesNotExistConst ='{$smarty.const._JOBDOESNOTEXIST}';
 var noPlacementsAssigned ='{$smarty.const._NOPLACEMENTSASSIGNEDYET}';
 var onlyImageFilesAreValid ='{$smarty.const._ONLYIMAGEFILESAREVALID}';

 var userHasLesson ='{$smarty.const._USERHASTHELESSON}';
 var serverName ='{$smarty.const.G_SERVERNAME}';

 var msieBrowser ='{$smarty.const.MSIE_BROWSER}';
 var sessionLogin ='{$smarty.session.s_login}';
 var clickToChangeStatus ='{$smarty.const._CLICKTOCHANGESTATUS}';
 var youHaventSetAdditionalAccounts ='{$smarty.const._MAPPEDACCOUNTSUCCESSFULLYDELETED}';
 var openFacebookSession ='{$T_OPEN_FACEBOOK_SESSION}';
 var currentOperation ='{$T_OP}';
var isInfoToolDisabled = {$T_CONFIGURATION.disable_tooltip != 1};

var jobsRows = new Array();
var branchesValues = new Array();
var jobValues = new Array();
var branchPositionValues = new Array();

var tabberLoadingConst = "{$smarty.const._LOADINGDATA}";
var enableMyJobSelect = false;

</script>


{************************************************** My Account **********************************************}
{******* contains: my Settings|my Profile, mapped accounts, HCD tabs, my Payments ***************************}
{*---------------------------------- My Status ----------------------------------*}
{*------- contains: my Lessons, my Courses, my Groups, my Certifications -------*}
{*----------------------------------------- PRESENTATION SETUP ACCORDING TO TYPE OF MANAGEMENT ----------------------------------------------*}
{capture name = 't_user_code'}

{**************** DASHBOARD PAGE ********************}
    {if $T_CURRENT_USER->coreAccess.calendar != 'hidden' && $T_CONFIGURATION.disable_calendar != 1}
        {capture name = "moduleCalendar"}
			<tr><td class = "moduleCell">
            	{capture name='t_calendar_code'}
                	{if $smarty.session.s_type == "administrator"}
                    	{assign var="calendar_ctg" value = "users&edit_user=`$smarty.get.edit_user`"}
					{else}
                    	{assign var="calendar_ctg" value = "personal"}
                    {/if}
                    {eF_template_printCalendar ctg=$calendar_ctg events=$T_CALENDAR_EVENTS timestamp=$T_VIEW_CALENDAR}

				{/capture}
                {assign var="calendar_title" value = `$smarty.const._CALENDAR`&nbsp;(#filter:timestamp-`$T_VIEW_CALENDAR`#)}

                {eF_template_printBlock title=$calendar_title data=$smarty.capture.t_calendar_code image='32x32/calendar.png' options=$T_CALENDAR_OPTIONS link=$T_CALENDAR_LINK}

                </td></tr>
		{/capture}
	{/if}

{* INCLUIR CODIGO PARA FACEBOOK, SE NECESSARIO, BUSCAR DE includes/social.tpl *}

{* INCLUIR CODIGO PARA FORUM, SE NECESSARIO, BUSCAR DE includes/social.tpl *}

	{if $T_NEWS && $T_CURRENT_USER->coreAccess.news != 'hidden' && $T_CONFIGURATION.disable_news != 1}
        {capture name = "moduleNewsList"}
                                 <tr><td class = "moduleCell">
                                           {capture name='t_news_code'}
                                         <table class = "cpanelTable">
                                         {foreach name = 'news_list' item = "item" key = "key" from = $T_NEWS}
                                          <tr><td>{$smarty.foreach.news_list.iteration}. <a title = "{$item.title}" href = "{$smarty.server.PHP_SELF}?ctg=news&view={$item.id}&lessons_ID=all&popup=1" target = "POPUP_FRAME" onclick = "eF_js_showDivPopup('{$smarty.const._ANNOUNCEMENT}', 1);">{$item.title}</a></td>
                                           <td class = "cpanelTime">#filter:user_login-{$item.users_LOGIN}#, <span title = "#filter:timestamp_time-{$item.timestamp}#">{$item.time_since}</span></td></tr>
                                         {foreachelse}
                                          <tr><td class = "emptyCategory">{$smarty.const._NOANNOUNCEMENTSPOSTED}</td></tr>
                                         {/foreach}
                                         </table>
                                           {/capture}
                                             {eF_template_printBlock title=$smarty.const._ANNOUNCEMENTS data=$smarty.capture.t_news_code image='32x32/announcements.png' array=$T_NEWS options = $T_NEWS_OPTIONS link = $T_NEWS_LINK}
                                   </td></tr>
    	{/capture}
	{/if}

{* INCLUIR CODIGO PARA T_LESSON_COMMENTS, SE NECESSARIO, BUSCAR DE includes/social.tpl *}

{* INCLUIR CODIGO PARA T_MY_RELATED_USERS, SE NECESSARIO, BUSCAR DE includes/social.tpl *}       

{* INCLUIR CODIGO PARA T_EVENTS, SE NECESSARIO, BUSCAR DE includes/social.tpl *}

            {*Inner table modules *}
          {foreach name = 'module_inner_tables_list' key = key item = moduleItem from = $T_INNERTABLE_MODULES}
          
              {capture name = $key|replace:"_":""} {*We cut off the underscore, since scriptaculous does not seem to like them*}
                  <tr><td class = "moduleCell">
                      {if $moduleItem.smarty_file}
                          {include file = $moduleItem.smarty_file}
                      {else}
                          {$moduleItem.html_code}
                      {/if}
                  </td></tr>
              {/capture}
          {/foreach}
<!--  -->
	{* NEWSLETTERS LINKS... TRANSFORMAR EM MODULO QUANDO POSSIVEL *}
	{capture name="t_newsletters_links_code"}
	<tr><td>
		<div class="blockContents">
			<h3 style="margin: 0; padding: 0 0 10pt 0;">Acompanhe mensalmente as notícias da ULT.</h3>
			<table class="cpanelTable">
				<tbody>
					<tr><td><a target="_blank" href="http://ult.com.br/newsletter/news_ult_ago.html" title="Comunicado Agosto">Edição 07 - Agosto 2011</a></td></tr>
					<tr><td><a target="_blank" href="http://ult.com.br/newsletter/news_ult_jul.html" title="Comunicado Julho">Edição 06 - Julho 2011</a></td></tr>
					<tr><td><a target="_blank" href="http://ult.com.br/newsletter/news_ult_jun.html" title="Comunicado Julho">Edição 05 - Junho 2011</a></td></tr>
					<tr><td><a target="_blank" href="http://ult.com.br/newsletter/news_ult_mai.html" title="Comunicado Maio">Edição 04 - Maio 2011</a></td></tr>
					<tr><td><a target="_blank" href="http://ult.com.br/newsletter/news_ult_abr.html" title="Comunicado Maio">Edição 03 - Abril 2011</a></td></tr>
					<tr><td><a target="_blank" href="http://ult.com.br/newsletter/news_ult_mar.html" title="Comunicado Março">Edição 02 - Março 2011</a></td></tr>
					<tr><td><a target="_blank" href="http://ult.com.br/newsletter/news_ult_fev.html" title="Comunicado Fevereiro">Edição 01 - Fevereiro 2011</a></td></tr>
				</tbody>
			</table>
		</div>
	</td></tr>
	{/capture}
	{* FIM DE NEWSLETTERS LINKS *}

{* INCLUIR CODIGO PARA moduleMessagesList, SE NECESSARIO, BUSCAR DE includes/social.tpl *}

<table style = "width:100%"><tr><td>
                        <div id="sortableList">
                            <div style="float: right; width:49%;height: 100%;">
                                <ul class="sortable" id="secondlist" style="height:100%;width:100%;">
 
 {if !in_array('moduleCalendar', $T_POSITIONS) && $smarty.capture.moduleCalendar && $T_CONFIGURATION.disable_calendar != 1}
                     <li onmousedown = "showBorders(event)" onmouseup = "hideBorders(event)" id="secondlist_moduleCalendar">
                         <table class = "singleColumnData">
                             {$smarty.capture.moduleCalendar}
                         </table>
                     </li>
 {/if}
 
 {*foreach name = 'module_inner_tables_list' key = key item = module from = $T_INNERTABLE_MODULES*}
        {*assign var = module_name value = $key|replace:"_":""*}
        {*if !in_array($module_name, $T_POSITIONS)*}
        <!-- 
			<li onmousedown = "showBorders(event)" onmouseup = "hideBorders(event)" id="secondlist_{$module_name}">
				<table class = "singleColumnData">
                	{*$smarty.capture.$module_name*}
            	</table>
			</li>
 		-->
     {*/if*}
    {*/foreach*}
	{if !in_array('moduleNewsList', $T_POSITIONS) && $smarty.capture.moduleNewsList}
			<li onmousedown = "showBorders(event)" onmouseup = "hideBorders(event)" id="secondlist_moduleNewsList">
			<table class = "singleColumnData">
				{$smarty.capture.moduleNewsList}
			</table>
		</li>
	{/if}
	<li onmousedown = "showBorders(event)" onmouseup = "hideBorders(event)" id="secondlist_newsletterslinks">
    	<table class = "singleColumnData">
        	{eF_template_printBlock 
				title = $smarty.const._NEWSLETTERS_LINKS 
				data= $smarty.capture.t_newsletters_links_code
			}
		</table>
	</li>
	
    <li onmousedown = "showBorders(event)" onmouseup = "hideBorders(event)" id="secondlist_modulepagamento">
    	<table class = "singleColumnData">
    		{$smarty.capture.modulepagamento }
    	</table>
    </li>
	<li onmousedown = "showBorders(event)" onmouseup = "hideBorders(event)" id="secondlist_modulerss">
    	<table class = "singleColumnData">
        	{$smarty.capture.modulerss  }
		</table>
	</li>
    <li onmousedown = "showBorders(event)" onmouseup = "hideBorders(event)" id="secondlist_moduleyoutube">
    	<table class = "singleColumnData">
    		{$smarty.capture.modulesocial }
    	</table>
    </li>
    <li onmousedown = "showBorders(event)" onmouseup = "hideBorders(event)" id="secondlist_moduleyoutube">
    	<table class = "singleColumnData">
    		{$smarty.capture.moduleyoutube }
    	</table>
    </li>
 
 {*
 Incluir, se necessário: 
 	- moduleForumList
 	- moduleProjectsList
 	- moduleCommentsList
 	 
 *}
                                    <li id = "second_empty" style = "display:none;height:5px;border:1px dashed gray"></li>
                                </ul>
                            </div>

                            {****** Left column ******}
	<div style="width:50%; height:100%;margin-left:1px;">
    	<ul class="sortable" id="firstlist" style="height:100%;width:100%;">
        	<li onmousedown = "showBorders(event)" onmouseup = "hideBorders(event)" id="firstlist_modulebillboard">
            	<table class = "singleColumnData">
                	{$smarty.capture.modulebillboard}
				</table>
			</li>
			                                
    
 {*
 Incluir, se necessário: 
 	- moduleWall
 	- moduleRelatedPeople
 	- moduleEventsList
 	- moduleMessagesList
 *}
  {*///MODULES INNERTABLES APPEARING*}


         <li id = "first_empty" style = "display:none;height:5px;border:1px dashed gray"></li>
                                </ul>
                            </div>


                        </div>
    </td></tr></table>

<script>
// Translations
var noMessageInFolderConst = "{$smarty.const._NOMESSAGESINFOLDER}";
var phpSelf = "{$smarty.server.PHP_SELF}";
var currentOperation ='{$T_OP}';
</script>
{/capture}
{*------------------------------------------------------- ACTUAL PRESENTATION ---------------------------------------------------------------*}
{eF_template_printBlock title = $smarty.const._EDITUSER data = $smarty.capture.t_user_code image='32x32/tools.png' }
