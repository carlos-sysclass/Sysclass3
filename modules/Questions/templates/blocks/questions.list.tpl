<div id="block_roadmap" data-widget-id="questions-list-widget" data-course-id="{$T_ENTITY_ID}">

    <div class="row">
        <div class="col-md-12 text-right">
            Maximum Score : <span class="btn btn-default btn-sm total_score">150</span>
            <a href="javascript:void(0);" class="btn btn-info btn-sm show-tips">
                <i class="fa fa-question-circle-circle"></i>{translateToken value="How is it calculated"}</a>
        </div>
        <div class="cleafix"></div>
    </div>
    <hr />
    <div class="alert alert-info display-hide tips-container">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button"></button>
        <p>
            <strong>IMPORTANT</strong>
            Question points are relative to other questions. Use in conjuction with question weights to create the test relative score. The test maximum score, and user score can be defined as:
        </p>
        <div class="row">
            <div class="col-md-4 text-center">
                `M = (p1 * w1) + (p2 * w2) + ...  (pX * wX)`
            </div>
            <div class="col-md-4 text-center">
                `U = (p1 * c1 * w1) + (p2 * c2 *  w2) + ...  (pX * wX)`
            </div>
            <div class="col-md-4 text-center">
                `S = U / M * 100%`
            </div>
        </div>
        <hr />
        <strong>REFERENCE</strong>
        <div class="row">
            <div class="col-md-3">
                `p1` = Question ooint #1<br />
                `p2` = Question point #2<br />
                `pX` = Question point #X<br />
            </div>
            <div class="col-md-3">
                `c1` = Correctness score #1 (0.0 to 1.0)<br />
                `c2` = Correctness score #2 (0.0 to 1.0)<br />
                `cX` = Correctness score #X (0.0 to 1.0)<br />
            </div>
            <div class="col-md-3">
                `w1` = Question weight #1<br />
                `w2` = Question weight #2<br />
                `wX` = Question weight #X
            </div>
            <div class="col-md-3">
                `M` = Maximum score<br />
                `U` = Score<br />
                `S` = Relative score (0 to 100)<br />
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <ul class="list-group ui-sortable margin-bottom-10 items-container">
            </ul>

            <a class="btn btn-sm btn-link create-question" data-target="#questions-select-modal" data-toogle="modal">
                <i class="fa fa-plus"></i>
                {translateToken value="Create question"}
            </a>
            <a class="btn btn-sm btn-link select-question" data-target="#questions-select-modal" data-toogle="modal" >
                <i class="fa fa-database"></i>
                {translateToken value="Questions database"}
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">

        </div>
    </div>
</div>
<script type="text/template" id="question-item">
    <a class="btn btn-sm btn-default tooltips drag-handler" data-original-title="{translateToken value="Drag item to reposition"} ">
        <i class="fa fa-arrows"></i>
    </a>

    <span class="<% if (model.active == "0") { %>text-danger<% } else { %>text-primary<% } %>"
    >
        <%= model.question.title %>
    </span>

    <span class="btn btn-sm btn-circle btn-default disabled">
        <!-- <i class="fa fa-question-circle"></i> -->
        <%= model.question.type.name %>
    </span>
    <% if (model.question.difficulty.name == "Easy") { %>
        <span class="btn btn-sm btn-circle green disabled">
            <%= model.question.difficulty.name %>
        </span>
    <% } else if (model.question.difficulty.name == "Normal") { %>
        <span class="btn btn-sm btn-circle yellow disabled">
            <%= model.question.difficulty.name %>
        </span>
    <% } else if (model.question.difficulty.name == "Hard") { %>
        <span class="btn btn-sm btn-circle red disabled">
            <%= model.question.difficulty.name %>
        </span>
    <% } else if (model.question.difficulty.name == "Very Hard") { %>
        <span class="btn btn-sm btn-circle dark disabled">
            <%= model.question.difficulty.name %>
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
            <input type="checkbox" name="question_active_<%= model.id %>" data-update="active" id="question_active_<%= model.id %>" class="form-control bootstrap-switch-me tooltips" data-original-title="{translateToken value="Toogle active"}" data-wrapper-class="item-option" data-size="small" data-on-color="success" data-on-text="{translateToken value='ON'}" data-off-color="danger" data-off-text="{translateToken value='OFF'}" value="1" data-value-unchecked="0" data-update-single="true">
        <% } else { %>
        <span class="label label-sm label-info">
          <i class="fa fa-exclamation"></i>Not saved yet!
        </span>
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
    <% console.warn(model) %>
    <div class="row form-inline margin-top-10">
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label">{translateToken value='Question'}:</label>
                
                <div class="read-more">
                    <span class="read-more-title">
                        <%= s.truncate(model.question.question, 125, '') %>
                    </span>
                    <% if (model.question.question.length > 125) { %>
                        <a href="javascript:void(0)" class="show-more">{translateToken value='Show more'}</a>
                        <span class="read-more-full">
                            <%= model.question.question %>
                        </span>
                        <a href="javascript:void(0)" class="show-less">{translateToken value='Show less'}</a>
                    <% } %>
                </div>
            </div>
        </div>
    </div>

    <div class="row form-inline margin-top-10">
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label">
                    {translateToken value="Question Points"} :
                </label>
                <input name="points[<%= model.id %>]" data-update="points" value="" type="text" placeholder="{translateToken value="Points"}" class="form-control input-xsmall tooltips" data-original-title="{translateToken value='Please, provide the question points'}" data-rule-required="true" data-rule-number="true" data-rule-min="1" data-rule-max="100" />
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
