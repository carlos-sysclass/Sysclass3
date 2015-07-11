<div id="block_roadmap" data-widget-id="questions-list-widget" data-course-id="{$T_ENTITY_ID}">
    <div class="row">
        <div class="col-md-12">
            <ul class="list-group ui-sortable margin-bottom-10 items-container">
            </ul>

            <a class="btn btn-sm btn-link add-item-action" href="/module/questions/add">
                <i class="fa fa-plus"></i>
                {translateToken value="Create Question"}
            </a>
            <a class="btn btn-sm btn-link select-question" data-target="#questions-select-modal" data-toogle="modal" >
                <i class="fa fa-database"></i>
                {translateToken value="Question's Database"}
            </a>
        </div>
    </div>
</div>
<script type="text/template" id="question-item">
    <a class="btn btn-sm btn-default tooltips drag-handler" data-original-title="{translateToken value="Drag to reposition item"} ">
        <i class="fa fa-arrows"></i>
    </a>

    <span class="<% if (model.active == "0") { %>text-danger<% } else { %>text-primary<% } %>"
    >
        <%= model.question.title %>
    </span>

    <span class="btn btn-sm btn-circle btn-default disabled">
        <!-- <i class="fa fa-question"></i> -->
        <%= model.question.type %>
    </span>
    <% if (model.question.difficulty == "Easy") { %>
        <span class="btn btn-sm btn-circle green disabled">
            <%= model.question.difficulty %>
        </span>
    <% } else if (model.question.difficulty == "Normal") { %>
        <span class="btn btn-sm btn-circle yellow disabled">
            <%= model.question.difficulty %>
        </span>
    <% } else if (model.question.difficulty == "Hard") { %>
        <span class="btn btn-sm btn-circle red disabled">
            <%= model.question.difficulty %>
        </span>
    <% } else if (model.question.difficulty == "Very Hard") { %>
        <span class="btn btn-sm btn-circle dark disabled">
            <%= model.question.difficulty %>
        </span>
    <% } %>

    <div class="list-file-item-options">
        <% if (!_.isUndefined(model.id)) { %>
            <span class="btn btn-default btn-sm"><span class="counter">0</span> / <span class="total">0</span></span>
            <!--
            <a class="btn btn-sm btn-primary tooltips edit-item-detail" href="javascript: void(0);" data-original-title="Edit grouping info">
                <i class="fa fa-edit"></i>
            </a>

            <a class="btn btn-sm btn-info view-item-detail tooltips" href="javascript: void(0);" data-original-title="View details">
                <i class="fa fa-info-circle"></i>
            </a>
            -->
            <input type="checkbox" name="active" id="question_active_<%= model.id %>" class="form-control bootstrap-switch-me tooltips" data-original-title="{translateToken value="Toogle Active"}" data-wrapper-class="item-option" data-size="small" data-on-color="success" data-on-text="{translateToken value='ON'}" data-off-color="danger" data-off-text="{translateToken value='OFF'}" value="1" data-value-unchecked="0">
        <% } %>
        <a class="btn btn-sm btn-danger delete-item-action" href="javascript: void(0);"
            data-toggle="confirmation"
            data-original-title="{translateToken value="Are you sure?"}"
            data-placement="left"
            data-singleton="true"
            data-popout="true"
            data-btn-ok-icon="fa fa-trash"
            data-btn-ok-class="btn-sm btn-danger"
            data-btn-cancel-icon="fa fa-times"
            data-btn-cancel-class="btn-sm btn-warning"
            data-btn-ok-label="{translateToken value="Yes"}"
            data-btn-cancel-label="{translateToken value="No"}"
        >
            <i class="fa fa-trash"></i>
        </a>
    </div>
    <div class="row form-inline">
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label">{translateToken value="Question Points"}:</label>
                <input name="points[<%= model.id %>]" data-update="points" value="" type="text" placeholder="{translateToken value="Points"}" class="form-control input-xsmall" data-rule-required="true" data-rule-number="true" data-rule-min="1" data-rule-max="100" />
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label">{translateToken value="Weight"}:</label>
                <select class="select2-me" name="weights[<%= model.id %>]" data-update="weight" data-rule-required="true" placeholder="weight">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>
            </div>
        </div>
    </div>
</script>
