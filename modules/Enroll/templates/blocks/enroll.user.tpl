<div class="form-body" id="block-enroll-user">
    <div class="row">
        <div class="form-group">
            
            <label class="">{translateToken value="Search for new Course"}</label>
            <input type="hidden" class="select2-me form-control col-md-12" name="course" data-placeholder="{translateToken value='Please, Select'}" data-url="/module/courses/items/me/combo/" />

        </div>
    </div>

    <div class="row margin-top-20">
        {include "`$smarty.current_dir`/../blocks/table.tpl" T_MODULE_CONTEXT=$T_ENROLL_USER_BLOCK_CONTEXT             T_MODULE_ID=$T_ENROLL_USER_BLOCK_CONTEXT.block_id}
    </div>
</div>
<script>
_before_init_functions.push(function() {
    $SC.addResource("{$T_ENROLL_USER_BLOCK_CONTEXT.block_id}_context", {$T_ENROLL_USER_BLOCK_CONTEXT|@json_encode nofilter});
});
</script>
