<div id="content-info-modal" class="modal fade" role="dialog" aria-labelledby="{translateToken value='Info'}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-label="Close" data-dismiss="modal" class="btn btn-link btn-xs pull-right" type="button">
                    <i class="fa fa-times"></i>
                </button>
                <h4 class="modal-title info-header">
                    <small></small>
                </h4>
            </div>
            <div class="modal-body info-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">
                    {translateToken value="Close"}
                </button>
            </div>
        </div>
    </div>
</div>

<script type="text/template" id="content_course_dialog_header">
    <%= model.name %>
</script>
<script type="text/template" id="content_course_dialog_body">
    <h5 class="form-section margin-bottom-10">
        <i class="fa fa-list"></i>
        Description
    </h5>
    <%= model.description %>

    <h5 class="form-section margin-bottom-10">
        <i class="fa fa-calendar-check-o"></i>
        Objectives
    </h5>
    <%= model.objectives %>
</script>