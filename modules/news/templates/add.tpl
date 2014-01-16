{extends file="layout/default.tpl"}
{block name="content"}
<form role="form" class=""0 method="post" action="">
	<div class="form-body">
		<h3 class="form-section">{translateToken value='General'}</h3>
		<div class="form-group">
			<label class="control-label">{translateToken value='Title'}</label>
			<input name="name" value="" type="text" placeholder="Name" class="form-control" />
		</div>
		<div class="form-group">
			<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
			<label class="control-label">{translateToken value='Content'}</label>
			<textarea class="wysihtml5 form-control placeholder-no-fix" id="data" name="data" rows="6" placeholder="{translateToken value='Put your content here...'}" ></textarea>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">{translateToken value='Start Date'}</label>
					<input class="form-control input-medium date-picker"  size="16" type="text" value="" />
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">{translateToken value='Start Time'}</label>
					<input type="text" class="form-control timepicker-24 input-medium">
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">{translateToken value='Expiration Date'}</label>
					<input class="form-control input-medium date-picker"  size="16" type="text" value="" />
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">{translateToken value='Expiration Time'}</label>
					<input type="text" class="form-control timepicker-24 input-medium">
				</div>
			</div>
		</div>
		<div id="permission-block">
			<h3 class="form-section">{translateToken value='Permission Rules'}
				<small>- {translateToken value='Who can see your annoucement?'}</small>
				<div class="pull-right">
					<a class="btn btn-link new-permission-action" id="ajax-demo" data-toggle="modal">
						<i class="icon-plus"></i>
						{translateToken value='New Permission'}
					</a>
				</div>
			</h3>
			
			<!-- INJECT A TABLE WITHIN TEMPLATES FOR PERMISSION MANAGEMENT -->
			<div class="form-group">
				<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
				<label class="control-label control-inline">{translateToken value='Access mode:'}  </label>
				<select class="select2-me input-xlarge" name="sexo" data-rule-required="1" data-rule-min="1">
					<option value="1">Only users that match the permissions below</option>
					<option value="2">Only users that do not match the permissions below</option>
				</select>
			</div>
			<!--
			<select class="select2-me input-xlarge" name="sexo" data-url="/module/permission/combo/items" data-select-search="true">
			</select>
			-->
			
			
			<div class="table-responsive">
				<table class="table table-striped table-hover">
					<thead>
						<tr>
							<th>#</th>
							<th>{translateToken value='Permission'}</th>
							<th class="text-right">{translateToken value='Actions'}</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>1</td>
							<td>
								
							</td>
							<td class="text-right">
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

<!--
array(8) {
  ["id"]=>
  string(1) "8"
  ["title"]=>
  string(44) "Get paid to do research in Tokyo this summer"
  ["timestamp"]=>
  string(10) "1388800800"
  ["expire"]=>
  string(10) "1389578400"
  ["classe_id"]=>
  string(1) "0"
  ["login"]=>
  string(5) "admin"
  ["lesson_id"]=>
  string(1) "0"
}
-->
	</div>
	<div class="form-actions nobg">
		<button class="btn btn-success" type="submit">{translateToken value='Save Changes'}</button>
	</div>
</form>
{/block}