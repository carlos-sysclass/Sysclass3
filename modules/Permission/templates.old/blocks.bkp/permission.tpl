<div id="permission-block">
	<h3 class="form-section"><i class="icon-lock"></i> {translateToken value="Permission rules"}
		<small>- {translateToken value="Who can see your data"}</small>
		<div class="pull-right">
			<a class="btn btn-link new-permission-action" id="ajax-demo" data-toggle="modal">
				<i class="fa fa-plus-square"></i>
				{translateToken value="New role"}
			</a>
		</div>
	</h3>
	
	<!-- INJECT A TABLE WITHIN TEMPLATES FOR PERMISSION MANAGEMENT -->
	<div class="form-group">
		<label class="control-label control-inline">{translateToken value="Access mode:"} </label>

		<select class="select2-me input-xlarge" name="permission_access_mode" data-rule-required="1" data-rule-min="1">
			<option value="1">{translateToken value="Only users that match the ALL the permissions below"}</option>
			<option value="2">{translateToken value="Only users that match at least ONE permission below"}</option>
			<option value="3">{translateToken value="Only users that do NOT match ALL the permissions below"}</option>
			<option value="4">{translateToken value="Only users that do NOT match at least ONE permission below"}</option>
		</select>
	</div>
	<div class="table-responsive">
		<table class="table table-striped table-hover">
			<thead>
				<tr>
					<th class="text-center">#</th>
					<th>{translateToken value="Permission"}</th>
					<th class="text-right">{translateToken value="Actions"}</th>
				</tr>
			</thead>
			<tbody></tbody>
		</table>
	</div>
	<script type="text/template" id="permission-block-item-template">
		<td class="text-center">
			<% if (typeof index != 'undefined') { %>
				<%= index %>
			<% } else { %>
				<span class="label label-info">{translateToken value="New"}</span>
			<% } %>
		</td>
		<td><%= text %></td>
		<td class="text-right">
			<a href="#" class="btn btn-sm btn-danger permission-item-remove-action">
				<i class="icon-remove"></i>
			</a>
		</td>
	</script>
	<script type="text/template" id="permission-block-nofound-template">
		<tr>
			<td colspan="3">
				{translateToken value="There's no permission found in this object"}
			</td>
		</tr>
	</script>
</div>