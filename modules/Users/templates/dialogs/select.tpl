<div id="dialogs-users-select" class="modal fade" role="dialog" aria-labelledby="gridSystemModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form-role" role="form" class="form-validate" method="post" action="">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">
                        <i class="fa fa-lock"></i>
                        {translateToken value='Select User'}
                    </h4>
                </div>
                <div class="modal-body">
                	<form class="form-horizontal" action="#">
    					<div class="form-body">
    						<div class="form-group">
                                <label class="">{translateToken value="Search users"}</label>
                                {if ($T_LOAD_BY_AJAX)}
                            		<input 
                                        type="hidden" 
                                        class="select2-me form-control col-md-12" 
                                        name="user_id" 
                                        data-placeholder="{translateToken value='Please select'}" 
                                        data-url="/module/users/items/me/combo" 
                                        data-format-as-template="%(name)s %(surname)s (%(login)s)" 
                                    />
                                {else}
                                    <select 
                                        class="select2-me form-control col-md-12" 
                                        name="user_id" 
                                        placeholder="{translateToken value='Please select'}" 
                                        data-placeholder="{translateToken value='Please select'}" 
                                        data-format-as-template="%(name)s %(surname)s (%(login)s)" 
                                        data-rule-required="true"
                                    />
                                        <!--
                                            <option value="" selected="selected">{translateToken value='Please select'}</option>
                                        -->
                                        {foreach $T_DIALOG_USER_SELECT_USERS as $user} 
                                        <option value="{$user['id']}">
                                            {$user['name']} {$user['surname']} ({$user['login']})
                                        </option>
                                        {/foreach}
                                    </select>
                                {/if}
    						</div>
    					</div>
                    </form>        
    			</div>
                <div class="modal-footer">
                    <button class="btn btn-success save-action" type="submit">{translateToken value="Select"}</button>
                    <button type="button" class="btn default" data-dismiss="modal">{translateToken value="Cancel"}</button>
                </div>
            </form>
		</div>
	</div>
</div>