
{extends file="layout/default.tpl"}
{block name="content"}
<div class="row margin-top-20">
	<div class="col-md-12">
		<div class="table-responsive backgrid-table">
			<table class="table table-striped table-bordered table-hover table-full-width data-table" id="sample_2">
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th>{translateToken value="Name"}</th>
						<th class="">{translateToken value="Login"}</th>
						<th class="">{translateToken value="User Type"}</th>
						<th class="unix-moment-since">{translateToken value="User Since"}</th>
						<th class="unix-moment-since">{translateToken value="last Login"}</th>
						<th class="text-center table-options">{translateToken value="Actions"}</th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>
	</div>
</div>
{/block}
