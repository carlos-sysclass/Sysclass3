<div class="modal fade" id="dialogs-questions-create" tabindex="-1" role="basic" aria-hidden="true" data-animation="false">
    <div class="modal-dialog modal-wide">
        <div class="modal-content">
        	<form id="form-question" role="form" class="form-validate" method="post" action="">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title event-title">{translateToken value="Create Question"}</h4>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" type="submit">{translateToken value="Save Changes"}</button>
                    <button type="button" class="btn default" data-dismiss="modal">{translateToken value="Close"}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/template" id="question-simple_choice-item">
    <div class="input-group ">
        <div class="input-group-btn">
             <span class="btn btn-default drag-handler tooltips" data-original-title="{translateToken value="Click here to move choice"}">
                <i class="fa fa-arrows"></i>
            </span>
        </div>
        <input value="<%= model.choice %>" type="text" placeholder="{translateToken value="Choice"}" class="form-control " />
        <div class="input-group-btn">
            <% if (!model.answer) { %>
            <a class="btn btn-success select-choice-action" type="button">
                {translateToken value="This is correct!"}
            </a>
            <% } %>
            <a class="btn btn-danger remove-choice-action">
                <i class="fa fa-trash"></i>
            </a>
        </div>
    </div>
</script>
