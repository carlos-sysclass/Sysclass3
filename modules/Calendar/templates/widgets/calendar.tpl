<div class="row">
	<div class="col-md-2 portlet-sidebar">
		<h4 class="section-title">{translateToken value="Filter Options"}
			<a href="javascript:void(0);" class="close btn-xs pull-right">
				<i class="fa fa-times"></i>
			</a>
		</h4>
		<div class="form-group">
			<label class="control-label">{translateToken value="Event Source"}</label>
			<select class="select2-me form-control" name="event_type">
				<option value="">{translateToken value="All"}</option>
				{foreach $T_CALENDAR_SOURCES as $source}
					<option value="{$source.class_name}">{$source.name}</option>
				{/foreach}
			</select>
		</div>
	</div>
	<div class="col-md-12">
		<div id="calendar"></div>
	</div>

</div>

<div class="modal fade" id="calendar-dialog" tabindex="-1" role="basic" aria-hidden="true" data-animation="false">
	<div class="modal-dialog modal-wide">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title">
					<i class="fa fa-calendar"></i>
					{translateToken value="Event Details"}: <small class="event-title"></small></h4>
			</div>
			<div class="modal-body">
				<div class="event-description"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn default" data-dismiss="modal">Close</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
<!-- /.modal-dialog -->
</div>

<!-- /.modal-create-dialog -->
<div class="modal fade" id="calendar-create-dialog" tabindex="-1" role="basic" aria-hidden="true" data-animation="false">
	<div class="modal-dialog modal-wide">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title event-title">{translateToken value="Event Creation"}</h4>
			</div>
			<div class="modal-body">
				<form id="form-calendar-event-creation" role="form" class="form-validate">
					<div class="form-body">

						<div class="tab-content">
							<div class="tab-pane fade active in" id="tab_1_1">
								<div class="form-group">
									<label class="control-label">{translateToken value="Name"}</label>
									<input id="name-modal" name="name" value="" type="text" placeholder="Name" class="form-control" data-rule-required="true" data-rule-minlength="3" />
								</div>
								<div class="form-group">
									<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
									<label class="control-label">{translateToken value="Description"}</label>
									<textarea class="wysihtml5 form-control placeholder-no-fix" id="description" name="description" rows="6" placeholder="{translateToken value="Put your description here..."}" data-rule-required="true"></textarea>
								</div>
								<div class="form-group">
									<label class="control-label">{translateToken value="Date"}</label>
									<input id="date" name="date" type="text" class="form-control" readonly />
								</div>
								
								<div class="form-group">
									<label class="control-label">{translateToken value="Event Type"}</label>
								 	<div class="controls">
								 		<input type="hidden" class="select2-me form-control input-block-level" id="type_id" name="type_id" data-url="/module/event_types/items/me/combo" data-select-search="true" data-placeholder="Pesquisar..." data-rule-required="true"/>
								 	</div>
								</div>

								{if (isset($T_SECTION_TPL['permission']) &&  ($T_SECTION_TPL['permission']|@count > 0))}
								    {foreach $T_SECTION_TPL['permission'] as $template}
								        {include file=$template}
								    {/foreach}
								{/if}

								<div class="clearfix"></div>
							</div>
						</div>

					</div>
					<div class="form-actions nobg">
						<button class="btn btn-success" type="submit">{translateToken value="Save changes"}</button>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn default" data-dismiss="modal">Close</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
<!-- /.modal-create-dialog -->
</div>