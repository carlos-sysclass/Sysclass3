<div id="lesson-exercises-dialog" class="modal fade" role="dialog" aria-labelledby="{translateToken value='Lesson Exercises'}" aria-hidden="true">
    <div class="modal-dialog modal-wide">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                	{translateToken value="Exercises"}
                	<small>Please answer the questions below.</small>
                </h4>
            </div>
            <div class="modal-body">
	        	<div class="exercises-container">
					<ul class="list-group question-container">
					</ul>
	        	</div>
            </div>
            <div class="modal-footer">
				<button class="btn btn-success" type="button">{translateToken value="Send"}</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">{translateToken value="Cancel"}</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/template" id="tab_lesson_exercises-details-template">
	
</script>
<script type="text/template" id="tab_lesson_exercises-question-combine-template">
	<h5 class="section-title">
		<span class="label label-primary">{translateToken value="Question"} #<%= model.model_index+1 %></span>
		<i><%= model.question %></i>
	</h5>
	<div class="answer-container">
        <div class="alert alert-warning" role="alert">
            Not implemented yet!
        </div>
	</div>
</script>
<script type="text/template" id="tab_lesson_exercises-question-true_or_false-template">
	<h5 class="section-title">
		<span class="label label-primary">{translateToken value="Question"} #<%= model.model_index+1 %></span>
		<i><%= model.question %></i>
	</h5>
	<div class="answer-container">
		<div class="form-group">
			<label class="control-label">{translateToken value="The answer is..."}</label>
			<input type="checkbox" name="answer[<%= model.id %>]" class="form-control bootstrap-switch-me" data-wrapper-class="block" data-size="small" data-on-color="success" data-on-text="{translateToken value='TRUE'}" data-off-color="danger" data-off-text="{translateToken value='FALSE'}" checked="checked" value="1" data-value-unchecked="0">
		</div>
	</div>
</script>
<script type="text/template" id="tab_lesson_exercises-question-simple_choice-template">
	<h5 class="section-title">
		<span class="label label-primary">{translateToken value="Question"} #<%= model.model_index+1 %></span>
		<i><%= model.question %></i>
	</h5>
	<div class="answer-container">
		<ul class="list-group">
		<% _.each(model.options, function(option, index) { %>
			<li>
				<label>
					<input type="radio" name="answer[<%= model.id %>]" class="icheck-me" data-skin="square" data-color="green" value="<%= option.index %>"> <%= option.choice %>
				</label>
			</li>
		<% }); %>
		</ul>
	</div>
</script>
<script type="text/template" id="tab_lesson_exercises-question-multiple_choice-template">
	<h5 class="section-title">
		<span class="label label-primary">{translateToken value="Question"} #<%= model.model_index+1 %></span>
		<i><%= model.question %></i>
	</h5>
	<div class="answer-container">
		<ul class="list-group">
		<% _.each(model.options, function(option, index) { %>
			<li>
				<label>
					<input type="checkbox" name="answer[<%= model.id %>]" class="icheck-me" data-skin="square" data-color="green" value="<%= option.index %>"> <%= option.choice %>
				</label>
			</li>
		<% }); %>
		</ul>
	</div>
</script>
<script type="text/template" id="tab_lesson_exercises-question-fill_blanks-template">
	<h5 class="section-title">
		<span class="label label-primary">{translateToken value="Question"} #<%= model.model_index+1 %></span>
		<i><%= model.question %></i>
	</h5>
	<div class="answer-container">
        <div class="alert alert-warning" role="alert">
            Not implemented yet!
        </div>
	</div>
</script>
<script type="text/template" id="tab_lesson_exercises-question-free_text-template">
	<h5 class="section-title">
		<span class="label label-primary">{translateToken value="Question"} #<%= model.model_index+1 %></span>
		<i><%= model.question %></i>
	</h5>
	<div class="answer-container">
        <div class="alert alert-warning" role="alert">
            Not implemented yet!
        </div>
	</div>
</script>
