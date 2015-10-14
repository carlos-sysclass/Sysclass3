{extends file="layout/default.tpl"}


{block name="content"}
<form id="form-agreement" role="form" class="form-validate" method="post" action="{$T_FORM_ACTION}">
	<div class="form-body note">
		{block name="inner-content"}{/block}
		<div class="">
			<h4 class="block">
			<input type="checkbox" name="viewed_license" class="icheck-me" data-skin="square" data-color="green" value="1"> 
			{translateToken value="I confirm that I have read and accept the above terms"}
			</h4>

        </div>
		<div class="row">
		</div>
	</div>
	<div class="form-actions nobg">
		<button class="btn btn-success" type="submit">{translateToken value="Confirm"}</button>
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