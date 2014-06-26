{extends file="layout/default.tpl"}
{block name="content"}
<div id="translate-token-view">
	<div class="row margin-top-20">
		<form role="form">
			<div class="col-md-5">
				<div class="form-group">
					<label>Source Language</label>
					<select class="select2-me form-control" name="src_language" data-placeholder="Select an option.">
						{foreach $T_LANGUAGES as $lang}
							<option value="{$lang.code}" {if $lang.code == $T_SYSTEM_LANGUAGE}selected="selected"{/if}>{$lang.local_name} ({$lang.name})</option>
						{/foreach}
					</select>
				</div>
			</div>
			<div class="col-md-2 icon-form-container">
				<span class="icon-exchange"></span>
			</div>
			<div class="col-md-5 pull-right">
				<div class="form-group">
					<label>Destination Language</label>
					<select class="select2-me form-control" name="dst_language">
						{foreach $T_LANGUAGES as $lang}
							<option value="{$lang.code}" {if $lang.code == $T_USER_LANGUAGE}selected="selected"{/if}>{$lang.local_name} ({$lang.name})</option>
						{/foreach}
					</select>
				</div>
			</div>
		</form>
	</div>
	<div class="row margin-top-20">
		<div class="col-md-12">
			<div class="table-responsive backgrid-table">
				<table class="table table-striped table-bordered table-hover table-full-width data-table" id="translate-token-table" data-srclang="{$T_SYSTEM_LANGUAGE}" data-dstlang="{$T_USER_LANGUAGE}">
					<thead>
						<tr>
							<th>{translateToken value="Source"}</th>
							<th>{translateToken value="Destination"}</th>
							<th class="text-center table-options">{translateToken value="Actions"}</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</div>
</div>
{/block}