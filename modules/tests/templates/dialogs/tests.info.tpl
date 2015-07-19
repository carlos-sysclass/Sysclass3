<div id="tests-info-modal" class="modal fade" role="dialog" aria-labelledby="{translateToken value='Test Overview'}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/template" id="tests_info_modal-template">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        </button>
        <h4 class="modal-title">
            <i class="fa fa-list-ol"></i>
            {translateToken value='Test Info'}: <strong><%= model.name %></strong>
        </h4>
    </div>
    <div class="modal-body content-container">
        <% if (!_.isEmpty(model.info)) { %>
            <p class="">
                <strong class="text-default"><%= model.info %></strong>
            </p>
        <% } %>
        <ul class="media-list">
            <li class="media">
                <div class="">
                    <p class="">
                        <span>{translateToken value="Total Questions"}:</span>
                        <strong class="text-default pull-right"><%= model.total_questions %></strong>
                    </p>
                    <hr />
                    <p class="">
                        <span>{translateToken value="Instructors"}:</span>
                        <strong class="text-default pull-right">
                            <%
                            var total_size = _.size(model.instructors);
                            _.each(model.instructors, function(instructor, index) {
                                print(instructor.name + " " + instructor.surname);
                                if (index < (total_size - 1)) {
                                    print(", ");
                                }
                            });
                            %>
                        </strong>
                        <div class="clearfix"></div>
                    </p>
                    <hr />
                    <p class="">
                        <span>{translateToken value="Time limit"}:</span>
                        <strong class="text-default pull-right"><%= model.time_limit %></strong>
                    </p>
                    <hr />
                    <p class="">
                        <span>{translateToken value="Repetition Limit"}</span>
                        <strong class="text-default pull-right"><%= model.test_repetition %></strong>
                    </p>
                    <hr />
                </div>
            </li>
        </ul>
    </div>
    <div class="modal-footer">
        <a href="/module/tests/open/<%= model.id %>" data-dismiss="modal" class="btn btn-primary">
            {translateToken value="Do now!"}
        </a>
        <button type="button" class="btn btn-default" data-dismiss="modal">
            {translateToken value="Close"}
        </button>
    </div>
</script>
