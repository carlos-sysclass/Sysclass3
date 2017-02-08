<div id="report-field-add-dialog" class="modal fade" role="dialog" aria-labelledby="gridSystemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">
                    <i class="fa fa-field"></i>
                    {translateToken value='Field List'}
                </h4>
            </div>
            <div class="modal-body" align="center">
                <ul class="nav nav-tabs">
                </ul>
                <div class="tab-content">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default close-action" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/template" id="report-field-add-category-tab-template">
    <li>
        <a href="#tab-report-add-field-<%= model.name %>" data-toggle="tab"><%= model.name %></a>
    </li>
</script>

<script type="text/template" id="report-field-add-category-tab-content-template">
    <div class="tab-pane fade in" id="tab-report-add-field-<%= model.name %>">
        <ul class="report-field-list">
        </ul>
        <div class="clearfix"></div>
    </div>
</script>


<script type="text/template" id="report-field-add-field-item-template">
    <li class="col-md-3 <% if (model.freeze) { %> freeze bg-grey<% } else if (model.selected) { %> bg-green<% } %>" data-field-name="<%= model.name %>">

        <%= model.label %>
        <span class="label label-sm label-default pull-right"><%= model.type %></span>
    </li>
</script>
