<div class="grid_24 box border" style="margin-top: 15px;">
	<div class="headerTools">
		{if $T_TYPE_USER == "administrator"} <span> <img
			src="/themes/sysclass/images/icons/small/grey/users.png"
			title="{$smarty.const._NEWGROUP}" alt="{$smarty.const._NEWGROUP}"> <a
			href="{$T_REQUEST_BASEURL}{$T_TYPE_USER}.php?ctg=module&op=module_xrequest&action=get_xrequest">{$smarty.const._REQUEST_LIST_TYPE}</a>
	
		</span> <span> <img
			src="/themes/sysclass/images/icons/small/grey/users.png"
			title="{$smarty.const._MODULE_XREQUEST_ADDTYPE}"
			alt="{$smarty.const._MODULE_XREQUEST_ADDTYPE}"> <a
			href="{$T_REQUEST_BASEURL}{$T_TYPE_USER}.php?ctg=module&op=module_xrequest&action=add_xrequest">{$smarty.const._REQUEST_ADD_TYPE}</a>
	
		</span> <span> <img
			src="/themes/sysclass/images/icons/small/grey/users.png"
			title="{$smarty.const.__XREQUEST_NEW_STATUS}"
			alt="{$smarty.const._XREQUEST_NEW_STATUS}"> <a
			href="{$T_REQUEST_BASEURL}{$T_TYPE_USER}.php?ctg=module&op=module_xrequest&action=status_xrequest">{$smarty.const._XREQUEST_NEW_STATUS}</a>
	
		</span> <span> <img
			src="/themes/sysclass/images/icons/small/grey/users.png"
			title="{$smarty.const._XREQUEST_LIST_STATUS}"
			alt="{$smarty.const._XREQUEST_LIST_STATUS}"> <a
			href="{$T_REQUEST_BASEURL}{$T_TYPE_USER}.php?ctg=module&op=module_xrequest&action=get_xrequest_status">{$smarty.const._XREQUEST_LIST_STATUS}</a>
	
		</span> {/if} <span> <img
			src="/themes/sysclass/images/icons/small/grey/users.png"
			title="{$smarty.const._REQUEST_LIST_PROTOCOL}"
			alt="{$smarty.const._REQUEST_LIST_PROTOCOL}"> <a
			href="{$T_REQUEST_BASEURL}{$T_TYPE_USER}.php?ctg=module&op=module_xrequest&action=get_xrequest_protocol">{$smarty.const._REQUEST_LIST_PROTOCOL}</a>
	
		</span> <span> <img
			src="/themes/sysclass/images/icons/small/grey/users.png"
			title="{$smarty.const._REQUEST_ADD_TYPE}"
			alt="{$smarty.const._REQUEST_ADD_TYPE}"> <a
			href="{$T_REQUEST_BASEURL}{$T_TYPE_USER}.php?ctg=module&op=module_xrequest&action=new_xrequest">{$smarty.const._REQUEST_NEW_PROTOCOL}</a>
	
		</span>
	
	</div>
</div>