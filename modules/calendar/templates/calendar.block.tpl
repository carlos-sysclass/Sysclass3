<div id="calendar"></div>

<div id="event-filter" style="margin-top: 10px; margin-left: 2px;">
	<form id="form-filter-events" role="form" class="form-validate">
		<div class="form-group">
			<label class="control-label">{translateToken value="Filter Event Type"}</label>
			<select id="event-to-filter" class="select2-me form-control" name="event_type" data-url="/module/event_types/items/me/combo">
				<option value="0">{translateToken value="All"}</option>
				{foreach $T_EVENT_TYPES as $id => $name}
					<option value="{$id}">{$name}</option>
				{/foreach}

			</select>
		</div>
	</form>
</div>

<div class="modal fade" id="calendar-dialog" tabindex="-1" role="basic" aria-hidden="true" data-animation="false">
	<div class="modal-dialog modal-wide">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title event-title">{translateToken value="Event Details"}</h4>
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
				<form id="form-{$T_MODULE_ID}" role="form" class="form-validate" method="post" action="{$T_FORM_ACTION}">
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
									<input id="event-date" class="form-control" readonly />
								</div>
								
								<div class="form-group">
									<label class="control-label">{translateToken value="Event Type"}</label>
								 	<div class="controls">
								 		<input type="hidden" class="select2-me form-control input-block-level" name="event_type" data-url="/module/event_types/items/me/combo" data-select-search="true" data-placeholder="Pesquisar..." />
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
						<button class="btn btn-success" type="submit">{translateToken value="Save Changes"}</button>
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