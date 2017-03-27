<div class="form-horizontal" action="#" id ="blocks-enroll-courses">
    <div class="alert alert-info">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button"></button>
        <p>
            {translateToken value="Select the courses available on this enrollment package."}
        </p>
    </div>
    <div class="form-body">
        <div class="form-group">
            <label class="">{translateToken value="Add program"}</label>
            <input type="hidden" class="select2-me form-control col-md-12" name="course" data-placeholder="{translateToken value='Please select'}" data-url="/module/courses/items/me/combo"
            data-format-as="attr"
            data-format-as-value="name"
             />
        </div>
    </div>
    <div class="row margin-top-20">
        <div class="col-md-12">
            {include "`$smarty.current_dir`/../blocks/table.tpl" 
            T_MODULE_CONTEXT=$T_ENROLL_COURSES_CONTEXT
            T_MODULE_ID="enroll_courses"}
        </div>
    </div>
</div>