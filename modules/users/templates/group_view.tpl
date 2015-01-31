
{extends file="layout/default.tpl"}
{block name="content"}
<div class="row margin-top-20">
	<div class="col-md-12">
		<div class="table-responsive backgrid-table">
			<table class="table table-striped table-bordered table-hover table-full-width data-table" id="group_view_table">
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th>{translateToken value="Name"}</th>
						<th class="">{translateToken value="Description"}</th>
						<th class="text-center table-boolean">{translateToken value="Active"}</th>
						<th class="text-center table-options">{translateToken value="Actions"}</th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>
	</div>
</div>
{/block}
