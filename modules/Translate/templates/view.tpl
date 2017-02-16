{extends file="layout/default.tpl"}
{block name="content"}
<div class="row margin-top-20">
	<div class="col-md-12">
		{include "`$smarty.current_dir`/blocks/table.tpl"}
	</div>
</div>
{/block}


{*block name="content"*}
<!--
<div id="translate-view">
	<div class="row margin-top-20">
		<div class="col-md-12">
			<div class="backgrid-table">
				<table class="table table-striped table-bordered table-hover table-full-width data-table" id="translate-table">
					<thead>
						<tr>
							<th class="text-center">{translateToken value="Code"}</th>
							<th class="text-center table-image">{translateToken value="Country"}</th>
							<th>{translateToken value="English Name"}</th>
							<th>{translateToken value=“Native Name"}</th>
							<th class="text-center table-boolean">{translateToken value="RTL"}</th>
							<th class="text-center table-boolean">{translateToken value="Active"}</th>
							<th class="text-center table-options">{translateToken value="Actions"}</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</div>
</div>
-->
{* /block *}
