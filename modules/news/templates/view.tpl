{extends file="layout/default.tpl"}
{block name="content"}
<div class="row margin-top-20">
	<div class="col-md-12">
		<div class="table-responsive backgrid-table">
			<table class="table table-striped table-bordered table-hover table-full-width data-table" id="sample_2">
				<thead>
					<tr>
						<th>#</th>
						<th>{translateToken value="Title"}</th>
						<th class="text-center unix-moment-since">{translateToken value="Published"}</th>
						<th class="text-center unix-moment-since">{translateToken value="Expires"}</th>
						<th class="text-center">{translateToken value="User"}</th>
						<th class="text-center table-options">{translateToken value="Actions"}</th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>
	</div>
</div>
{/block}