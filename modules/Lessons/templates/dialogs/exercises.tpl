<div id="unit-exercises-dialog" class="modal fade" role="dialog" aria-labelledby="{translateToken value='Unit Exercises'}" aria-hidden="true">
    <div class="modal-dialog modal-wide">
        <div class="modal-content">
			<form role="form" class="form-validate" method="post" action="{$T_FORM_ACTION}">
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
					<button class="btn btn-success" type="submit">{translateToken value="Send"}</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">{translateToken value="Cancel"}</button>
	            </div>
	        </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/template" id="tab_unit_exercises-details-template">
	
</script>
<script type="text/template" id="tab_unit_exercises-question-combine-template">
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
<script type="text/template" id="tab_unit_exercises-question-true_or_false-template">
	<h5 class="section-title">
		<span class="label label-primary">{translateToken value="Question"} #<%= model.model_index+1 %></span>
		<i><%= model.question %></i>
	</h5>
	<div class="answer-container">
        <div class="answer-container">
			<label class="control-label">{translateToken value="The answer is..."}</label>
            <ul class="list-group">
                <li>
                    <label>
                        <input type="radio" name="answer[<%= model.answer_index %>]" data-update="answers.<%= model.answer_index %>" class="icheck-me" data-skin="square" data-color="green" value="1"> {translateToken value='TRUE'}
                    </label>
                </li>
                <li>
                    <label>
                        <input type="radio" name="answer[<%= model.answer_index %>]" data-update="answers.<%= model.answer_index %>" class="icheck-me" data-skin="square" data-color="red" value="0"> {translateToken value='FALSE'}
                    </label>
                </li>
            </ul>
        </div>
	</div>
</script>
<script type="text/template" id="tab_unit_exercises-question-simple_choice-template">
	<h5 class="section-title">
		<span class="label label-primary">{translateToken value="Question"} #<%= model.model_index+1 %></span>
		<i><%= model.question %></i>
	</h5>
	<div class="answer-container">
		<ul class="list-group">
		<% _.each(model.options, function(option, index) { %>
			<li>
				<label>
					<input type="radio" name="answer[<%= model.answer_index %>]" data-update="answers.<%= model.answer_index %>" class="icheck-me" data-skin="square" data-color="green" value="<%= option.index %>"> <%= option.choice %>
				</label>
			</li>
		<% }); %>
		</ul>
	</div>
</script>
<script type="text/template" id="tab_unit_exercises-question-multiple_choice-template">
	<h5 class="section-title">
		<span class="label label-primary">{translateToken value="Question"} #<%= model.model_index+1 %></span>
		<i><%= model.question %></i>
	</h5>
	<div class="answer-container">
		<ul class="list-group">
		<% _.each(model.options, function(option, index) { %>
			<li>
				<label>
					<input type="checkbox" name="answer[<%= model.answer_index %>]" data-update="answers.<%= model.answer_index %>" class="icheck-me" data-skin="square" data-color="green" value="<%= option.index %>"> <%= option.choice %>
				</label>
			</li>
		<% }); %>
		</ul>
	</div>
</script>
<script type="text/template" id="tab_unit_exercises-question-fill_blanks-template">
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
<script type="text/template" id="tab_unit_exercises-question-free_text-template">
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
