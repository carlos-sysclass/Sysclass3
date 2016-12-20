<div id="additional-address">

    {include "`$smarty.current_dir`/../blocks/table.tpl" 
    
    T_MODULE_CONTEXT=$T_ORGANIZATION_SOCIAL_LIST_CONTEXT
    T_MODULE_ID="organization-list"
    FORCE_INIT=1}


	<a href="javascript:void(0);" class="btn btn-sm btn-link social-addanother">
		<i class="fa fa-plus"></i>
		<span>{translateToken value="Add Social Info"}</span>
	</a>
</div>