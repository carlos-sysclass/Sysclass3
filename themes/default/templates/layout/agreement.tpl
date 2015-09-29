{extends file="layout/default.tpl"}


{block name="content"}
<form id="form-agreement" role="form" class="form-validate" method="post" action="{$T_FORM_ACTION}">
	<div class="form-body">
		{block name="inner-content"}{/block}
		<div class="form-group">
			<label class="control-label">{translateToken value="I Confirm "}</label>
            <input type="checkbox" name="viewed_license" class="form-control bootstrap-switch-me" data-wrapper-class="block" data-size="small" data-on-color="success" data-on-text="{translateToken value='ON'}" data-off-color="danger" data-off-text="{translateToken value='OFF'}" value="1" data-update-single="true" data-value-unchecked="0">
            
        </div>
		<div class="row">
		</div>
	</div>
	<div class="form-actions nobg">
		<button class="btn btn-success" type="submit">{translateToken value="Save Changes"}</button>
	</div>
</form>
<script>
_lazy_init_functions.push(function() {
    // START DATATABLE HERE
    var tableViewClass = $SC.module("view.agreement").start({
    	user_id : {$T_CURRENT_USER.id}
    });
});
</script>
{/block}