	<div style="margin-top: 15px;" class="grid_16 datatable_filter box border">
		<div class="headerTools">
			<label class="inline">{$smarty.const.__XCOURSE_FORM_SELECTCLASS}</label>
	       	<select name="_XCOURSE_USERS_CLASSES_FILTER" class="inline">
	       		{foreach name = 'user_filter_iterator' key = 'filter_key' item = 'filter_value' from = $T_USER_CLASSES_FILTERS}
		    		<option value="{$filter_key}">{$filter_value}</option>
		    	{/foreach}
	        </select>
		</div>
    </div>
    
	<div class="clear"></div>
	
	<table class = "display" id="_XCOURSE_USERS_LIST">
		<thead>
			<tr>
				<th>{$smarty.const._USER} </th>
				<th>{$smarty.const._USERROLE}</th>
				<th>{$smarty.const._ENROLLEDON}</th>
				<th>{$smarty.const._COMPLETEDON}</th>
<!-- 				<th>{$smarty.const._STATUS}</th>  	 -->
<!-- 				<th>{$smarty.const._COMPLETED}</th>  -->
				<th>{$smarty.const._SCORE}</th>
				<th>{$smarty.const._OPERATIONS}</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>