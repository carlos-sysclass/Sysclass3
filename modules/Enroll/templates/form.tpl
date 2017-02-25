{extends file="layout/default.tpl"}
{block name="content"}

<div class="form-body">
	<ul class="nav nav-tabs">
		<li class="active">
			<a href="#tab_1_1" data-toggle="tab">{translateToken value="General"}</a>
		</li>
		<li class="admittance-type-item admittance-type-grouping hidden">
			<a href="#tab_1_2" data-toggle="tab">{translateToken value="Grouping options"}</a>
		</li>

		{if (isset($T_SECTION_TPL['enroll.courses']) &&  ($T_SECTION_TPL['enroll.courses']|@count > 0))}
			<li class="">
				<a href="#tab_1_3" data-toggle="tab">{translateToken value="Programs"}</a>
			</li>
		{/if}
		{if (isset($T_SECTION_TPL['enroll.fields']) &&  ($T_SECTION_TPL['enroll.fields']|@count > 0))}
			<li class="">
				<a href="#tab_1_4" data-toggle="tab">{translateToken value="Fields"}</a>
			</li>
		{/if}
		
	</ul>
	<div class="tab-content">
		<div class="tab-pane fade active in" id="tab_1_1">
			<form role="form" class="form-validate" method="post" action="{$T_FORM_ACTION}" id="form-{$T_MODULE_ID}">
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label class="control-label">{translateToken value="Name"}</label>
							<input name="name" value="" type="text" placeholder="{translateToken value="Name"}" class="form-control" data-rule-required="true" data-rule-minlength="3" />
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label class="control-label">{translateToken value="Subtitle"}</label>
							<input name="subtitle" value="" type="text" placeholder="{translateToken value="Subtitle"}" class="form-control" data-rule-minlength="5" />
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label class="control-label">{translateToken value="Url"}</label>
							<div class="input-group">
								<span class="input-group-addon">https://signup-itaipu.sysclass.com/</span>
								<input name="identifier" value="" type="text" placeholder="{translateToken value="Url"}" class="form-control" data-rule-required="true" data-rule-minlength="3" />
							</div>
						</div>
					</div>
				</div>
				<h5 class="form-section margin-bottom-10 margin-top-10">
					<i class="fa fa-calendar"></i>
					{translateToken value="Open Period"}
					<span class="badge badge-warning tooltips pull-right" data-original-title="{translateToken value='Especiy the start and final date for this rule be avaliable. If you don\'t specify the final date, its duration will be underterminate.'}">
                        <i class="fa fa-question"></i>
                    </span>
				</h5>

				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label">
								{translateToken value="Start Date"}
							</label>
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								<input type="text" name="start_date" value="" data-format="date" data-format-from="isodate" class="form-control date-picker">
							</div>
							
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label">{translateToken value="Finish Date"}</label>
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								<input type="text" name="end_date" value="" data-format="date" data-format-from="isodate" class="form-control date-picker">
							</div>
						</div>
					</div>
				</div>

				<h5 class="form-section margin-bottom-10 margin-top-10">
					<i class="fa fa-cogs"></i>
					{translateToken value="Course Format"}
					<span class="badge badge-warning tooltips pull-right" data-original-title="{translateToken value='You can define the criterias for user admitance. This information is used to make course grouping control, and the calculate the course desired roadmap.'}">
                        <i class="fa fa-question"></i>
                    </span>
				</h5>

				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
                            <label>
                                <input type="radio" name="admittance_type" data-update="admittance_type" class="icheck-me" data-skin="square" data-color="green" value="individual"> {translateToken value='Individual'}
                            </label>
                        </div>
                    </div>
					<div class="col-md-6">
						<div class="form-group">
                            <label>
                                <input type="radio" name="admittance_type" data-update="admittance_type" class="icheck-me" data-skin="square" data-color="blue" value="grouping"> {translateToken value='Group-based'}
                            </label>
                        </div>
                    </div>
				</div>
				<div class="admittance-type-container">
					<div class="admittance-type-item admittance-type-individual hidden">
					    <div class="alert alert-success">
				        	<button aria-hidden="true" data-dismiss="alert" class="close" type="button"></button>
					        <p>
					            <strong>TIP!</strong>
					            On <strong>Individual</strong> Admission type - the user will be enrolled in his/her own individual program. The roadmap will be calculated individually.
					        </p>
					    </div>
					</div>
					<div class="admittance-type-item admittance-type-grouping hidden">
					    <div class="alert alert-info">
					    	<button aria-hidden="true" data-dismiss="alert" class="close" type="button"></button>
					        <p>
					            <strong>TIP!</strong>
					            On <strong>Grouping-based</strong> Admission type, the user is allocated to a group, based on his/hers admittance date. Each group are dynamically created based on the criteria defined below. You can create groups based on days of a month, the total number of users, or even.</p>
					    </div>
					</div>
				</div>
				<div class="clearfix"></div>
				<div class="form-actions nobg">
					<button class="btn btn-success save-action" type="button">{translateToken value="Save changes"}</button>
				</div>
			</form>
		</div>
		<div class="tab-pane fade in admittance-type-item admittance-type-grouping hidden" id="tab_1_2">

			<h5 class="form-section margin-bottom-10 margin-top-10">
				<i class="fa fa-cogs"></i>
				{translateToken value="Enrollment Dates"}
				<span class="badge badge-warning tooltips pull-right" data-original-title="{translateToken value='You can select one of two ways to define the grouping creation. A fixed way, when you manualy dwefined the dates for admittance, or the dynamic way, when you set the parameters for the system automatically create the grouping.'}" data-placement="bottom">
                    <i class="fa fa-question"></i>
                </span>
			</h5>
			
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
                        <label>
                            <input type="radio" name="interval_definition" data-update="interval_definition" class="icheck-me" data-skin="square" data-color="green" value="fixed"> {translateToken value='Fixed'}
                        </label>
                    </div>
                </div>
				<div class="col-md-6">
					<div class="form-group">
                        <label>
                            <input type="radio" name="interval_definition" data-update="interval_definition" class="icheck-me" data-skin="square" data-color="blue" value="dynamic"> {translateToken value='Dynamic'}
                        </label>
                    </div>
                </div>
			</div>

			<div class="interval-definition-container">
				<div class="interval-definition-item interval-definition-fixed hidden">
				    <div class="row">
				        <div class="col-md-12" id="fixed_grouping-create-container">
				            <ul class="list-group ui-sortable margin-bottom-10 items-container">
				            </ul>
				            <!--
				            <a class="btn btn-sm btn-primary btn-link add-period-action" href="javascript: void(0);">
				                <i class="fa fa-plus"></i>
				                {translateToken value="Create Period"}

				            </a>
				            -->
				            <a class="btn btn-sm btn-link add-item-action" href="javascript: void(0);">
				                <i class="fa fa-plus"></i>
				                {translateToken value="New Group"}

				            </a>
				        </div>
				    </div>

				</div>
				<div class="interval-definition-item interval-definition-dynamic hidden">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label class="control-label">{translateToken value="Group name template"}
									<span class="badge badge-warning tooltips pull-right" data-original-title="{translateToken value='This filed will be used to create '}">
										<i class="fa fa-question"></i>
									</span>
								</label>
								<input name="name" value="" type="text" placeholder="{translateToken value="Grouping Name template"}" class="form-control" data-rule-required="true" data-rule-minlength="3" />
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<label class="control-label">
								{translateToken value="Interval Rules"}
							</label>
							<div class="form-group form-group-inline">
								<div class="inline-item">
									<label class="control-label">Each </label>
								</div>
								<!--
								<div class="inline-item">
									<input name="units" value="" type="text" placeholder="{translateToken value="Units"}" class="form-control" data-rule-required="true" data-rule-number="3" />
								</div>
								-->
								<div class="inline-item">
									<select class="select2-me form-control input-block-level" name="interval_rule_type" style="min-width: 150px;">
										<option value="-1">{translateToken value="Select a Period"}</option>
										<option value="week">{translateToken value="Week"}</option>
										<option value="month">{translateToken value="Month"}</option>
										<!--
										<option value="year">{translateToken value="Year"}</option>
										-->
									</select>
								</div>
								<div class="interval-rule-type-item interval-rule-type-week hidden">
									<div class="inline-item">
										<label class="control-label"> starting on weekday
										</label>
									</div>
									<div class="inline-item">
										<select class="select2-me form-control input-block-level" name="usergroups"  style="min-width: 125px;">
											<option value="0">{translateToken value="Sunday"}</option>
											<option value="1">{translateToken value="Monday"}</option>
											<option value="2">{translateToken value="Tuesday"}</option>
											<option value="3">{translateToken value="Wednesday"}</option>
											<option value="4">{translateToken value="Thursday"}</option>
											<option value="5">{translateToken value="Friday"}</option>
											<option value="6">{translateToken value="Saturday"}</option>
										</select>
									</div>
								</div>
								<div class="interval-rule-type-item interval-rule-type-month hidden">
									<div class="inline-item">
										<label class="control-label"> starting on day
										</label>
									</div>
									<div class="inline-item">
										<select class="select2-me form-control input-block-level" name="usergroups" style="min-width: 80px;">
											{for $day=1 to 31}
											<option value="{$day}">{$day}</option>
											{/for}
										</select>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<h5 class="form-section margin-bottom-10">
				<i class="fa fa-cogs"></i>
				{translateToken value="User Limit"}
				<span class="badge badge-warning tooltips pull-right" data-original-title="{translateToken value='You can set the maximum number of users allowed to enter in a grouping. If you don\'t want to limit, set this field to \'0\'.'}" data-placement="bottom">
                    <i class="fa fa-question"></i>
                </span>
			</h5>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label class="control-label">{translateToken value="Maximum users"}
						</label>
						<input name="limit_by_students_count" value="" type="text" placeholder="{translateToken value="Maximum users"}" class="form-control" data-rule-required="true" data-rule-number="true" />
					</div>
                </div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="control-label">{translateToken value="Action if the maximum is reached"}
						</label>
						<select class="select2-me form-control input-block-level" name="usergroups" data-placeholder="{translateToken value='Select a action'}" data-format-attr="id">
							<option value="-1">{translateToken value="Select a action"}</option>
							<option value="">{translateToken value="Block admission"}</option>
							<option value="">{translateToken value="Enroll in another group"}</option>
							<option value="">{translateToken value="Send to the waiting list"}</option>
						</select>
					</div>
                </div>
			</div>
			<div class="form-actions nobg">
				<button class="btn btn-success save-action" type="button">{translateToken value="Save changes"}</button>
			</div>

		</div>
		

		{if (isset($T_SECTION_TPL['enroll.courses']) &&  ($T_SECTION_TPL['enroll.courses']|@count > 0))}
			<div class="tab-pane fade in" id="tab_1_3">
		    {foreach $T_SECTION_TPL['enroll.courses'] as $template}
		        {include file=$template}
		    {/foreach}
		    </div>
		{/if}

		{if (isset($T_SECTION_TPL['enroll.fields']) &&  ($T_SECTION_TPL['enroll.fields']|@count > 0))}
			<div class="tab-pane fade in" id="tab_1_4">
		    {foreach $T_SECTION_TPL['enroll.fields'] as $template}
		        {include file=$template}
		    {/foreach}
		    </div>
		{/if}
	</div>
</div>


<script type="text/template" id="grouping-edit-item">

    <a href="#" class="btn btn-sm editable-me <% if (data.active == "0") { %>text-danger<% } %>"
        data-type="text"
        data-name="class.name"
        data-send="never"
        data-original-title="Class Name"
        data-inputclass="form-control"

    >
        <%= data.name %>

    </a>
    <% if (data.start_date != 0 || data.end_date != 0) { %>
    <i>
        <small>
        <% if (data.start_date != 0) { %>
            {translateToken value="From"}
            <strong><%= $SC.module("views").formatValue(data.start_date, "date", "unix-timestamp") %></strong>
        <% } %>
        <% if (data.end_date != 0) { %>
            {translateToken value="To"}
            <strong><%= $SC.module("views").formatValue(data.end_date, "date", "unix-timestamp") %></strong>
        <% } %>
        </small>
    </i>
    <% } else { %>
        <i>
            <small class="text-danger"><strong>No dates Defined</strong></small>
        </i>
    <% } %>

    <div class="list-file-item-options">
        <% if (typeof data.id !== 'undefined') { %>
            <span class="btn btn-default btn-sm"><span class="counter">0</span> / <span class="total">0</span></span>

            <a class="btn btn-sm btn-primary tooltips edit-item-detail" href="javascript: void(0);" data-original-title="Edit grouping info">
                <i class="fa fa-edit"></i>
            </a>
            <!--
            <a class="btn btn-sm btn-info view-item-detail tooltips" href="javascript: void(0);" data-original-title="View details">
                <i class="fa fa-info-circle"></i>
            </a>
            -->
            <input type="checkbox" name="active" class="form-control bootstrap-switch-me tooltips" data-original-title="{translateToken value="Toogle Active"}" data-wrapper-class="item-option" data-size="small" data-on-color="success" data-on-text="{translateToken value='ON'}" data-off-color="danger" data-off-text="{translateToken value='OFF'}" <% if (data.active == "1") { %>checked="checked"<% } %> value="1">

        <% } %>
        <a class="btn btn-sm btn-danger delete-item-action" href="javascript: void(0);"
            data-toggle="confirmation"
            data-original-title="{translateToken value="Are you sure?"}"
            data-placement="left"
            data-singleton="true"
            data-popout="true"
            data-btn-ok-icon="fa fa-trash"
            data-btn-ok-class="btn-sm btn-danger"
            data-btn-cancel-icon="fa fa-times"
            data-btn-cancel-class="btn-sm btn-warning"
            data-btn-ok-label="{translateToken value="Yes"}"
            data-btn-cancel-label="{translateToken value="No"}"
        >
            <i class="fa fa-trash"></i>
        </a>
    </div>
    <% if (_.has(data, 'class')) { %>
    <div class="detail-container">
        <h5 class="form-section no-margin margin-bottom-5">Details</h5>
        <% if (!_.isEmpty(data.class.description)) { %>
        <div class="row">
            <div class="col-md-12 col-sm-12">
                    <span>{translateToken value="Description"}</span>
                    <p class="">
                        <strong><%= data.class.description %></strong>
                    </p>
            </div>
        </div>
        <hr class="no-margin margin-bottom-5"></hr>
        <% } %>
        <div class="row">
            <div class="col-md-6 col-sm-6">
                <p>
                    <span>{translateToken value="Total Lessons"}</span>
                    <% if (data.class.total_lessons == 0) { %>
                        <strong class="text-danger pull-right"><%= data.class.total_lessons %></strong>
                    <% } else { %>
                        <strong class="text-primary pull-right"><%= data.class.total_lessons %></strong>
                    <% } %>

                </p>
            </div>
            <% if (_.isObject(data.class.instructors)) { %>
            <div class="col-md-6 col-sm-6">
                <div>
                    <span>{translateToken value="Instructors"}</span>
                    <ul class="pull-right">
                        <%
                            var instructors = _.map(data.class.instructors, function(data) {
                                return _.pick(data, "name", "surname");
                            });
                        %>
                        <% _.each(instructors, function(item) { %>
                            <li class="text-primary"><strong><% print(item.name  + " " + item.surname); %></strong></li>
                        <% }); %>
                    </ul>
                </div>
            </div>
            <% } else { %>
                <div class="col-md-6 col-sm-6">
                    <p>
                        <span>{translateToken value="Instructors"}</span>
                        <strong class="text-danger pull-right">{translateToken value="No Instructors defined"}</strong>
                    </p>
                </div>
            <% } %>
        </div>

    <% } %>
</script>

{/block}
