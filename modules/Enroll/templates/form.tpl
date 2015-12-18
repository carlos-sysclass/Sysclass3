{extends file="layout/default.tpl"}
{block name="content"}

<form id="form-{$T_MODULE_ID}" role="form" class="form-validate" method="post" action="{$T_FORM_ACTION}">
	<div class="form-body">
		<ul class="nav nav-tabs">
			<li class="active">
				<a href="#tab_1_1" data-toggle="tab">{translateToken value="General"}</a>
			</li>
			<li class="">
				<a href="#tab_1_2" data-toggle="tab">{translateToken value="Grouping Options"}</a>
			</li>
			<li class="">
				<a href="#tab_1_3" data-toggle="tab">{translateToken value="Enrollment Fields"}</a>
			</li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane fade active in" id="tab_1_1">
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label class="control-label">{translateToken value="Name"}</label>
							<input name="name" value="" type="text" placeholder="{translateToken value="Name"}" class="form-control" data-rule-required="true" data-rule-minlength="3" />
						</div>
					</div>
				</div>
				<h5 class="form-section margin-bottom-10 margin-top-10">
					<i class="fa fa-calendar"></i>
					{translateToken value="Avaliability Period"}
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
								<span class="input-group-addon"><i class="icon-calendar"></i></span>
								<input type="text" name="start_date" value="" data-format="date" data-format-from="isodate" class="form-control date-picker">
							</div>
							
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label">{translateToken value="Finish Date"}</label>
							<div class="input-group">
								<span class="input-group-addon"><i class="icon-calendar"></i></span>
								<input type="text" name="start_date" value="" data-format="date" data-format-from="isodate" class="form-control date-picker">
							</div>
						</div>
					</div>
				</div>

				<h5 class="form-section margin-bottom-10 margin-top-10">
					<i class="fa fa-cogs"></i>
					{translateToken value="Admittance Method"}
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
                                <input type="radio" name="admittance_type" data-update="admittance_type" class="icheck-me" data-skin="square" data-color="blue" value="grouping"> {translateToken value='Grouping-Based'}
                            </label>
                        </div>
                    </div>
				</div>
				<div class="admittance-type-container">
					<div class="admittance-type-individual">
					    <div class="alert alert-success hidden">
				        	<button aria-hidden="true" data-dismiss="alert" class="close" type="button"></button>
					        <p>
					            <strong>TIP!</strong>
					            On <strong>Individual</strong> Admittance Type, the user is accepted regardless of others students, and your roadmap is calculated  individually.
					        </p>
					    </div>
					</div>
					<div class="admittance-type-grouping">
					    <div class="alert alert-info">
					    	<button aria-hidden="true" data-dismiss="alert" class="close" type="button"></button>
					        <p>
					            <strong>TIP!</strong>
					            On <strong>Grouping-based</strong> Admittance Type, the users are allocated in groups, based on his admittance dates. Each group are dynamically created based on the criterias below. You can create groups based on days of a month, the total number of students or even both.</p>
					    </div>
					</div>
				</div>
				<!--
				<h5 class="form-section margin-bottom-10 margin-top-10">
					<i class="fa fa-calendar"></i>
					{translateToken value="Conclusion"}
					<span class="badge badge-warning tooltips pull-right" data-original-title="{translateToken value='Especiy the start and final date for this rule be avaliable. If you do notspecify thfinal date, its duration will be underterminate.'}">
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
								<span class="input-group-addon"><i class="icon-calendar"></i></span>
								<input type="text" name="start_date" value="" data-format="date" data-format-from="isodate" class="form-control date-picker">
							</div>
							
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label">{translateToken value="Finish Date"}</label>
							<div class="input-group">
								<span class="input-group-addon"><i class="icon-calendar"></i></span>
								<input type="text" name="start_date" value="" data-format="date" data-format-from="isodate" class="form-control date-picker">
							</div>
						</div>
					</div>
				</div>
				-->



			    <!--
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label">{translateToken value='Email'}</label>
							<input name="email" value="" type="text" placeholder="{translateToken value='Email'}" class="form-control" data-rule-required="true" data-rule-email="true" />
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label">{translateToken value="Primary Group"}</label>
							<select class="select2-me form-control input-block-level" name="usergroups" data-placeholder="{translateToken value='Primary Group'}" multiple="multiple" data-format-attr="id">
								<option value="-1">{translateToken value="Select a group"}</option>
								{foreach $T_GROUPS as $group}
									<option value="{$group.id}">{$group.name}</option>
								{/foreach}
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
		                    <label class="control-label">{translateToken value="Active"}</label>
		                    <input type="checkbox" name="active" class="form-control bootstrap-switch-me" data-wrapper-class="block" data-size="small" data-on-color="success" data-on-text="{translateToken value='ON'}" data-off-color="danger" data-off-text="{translateToken value='OFF'}" checked="checked" value="1">
		                </div>
					</div>
				</div>
				-->
				<div class="clearfix"></div>
			</div>
			<div class="tab-pane fade in" id="tab_1_2">

				<h5 class="form-section margin-bottom-10 margin-top-10">
					<i class="fa fa-cogs"></i>
					{translateToken value="Admittance Method"}
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
                                <input type="radio" name="admittance_type" data-update="admittance_type" class="icheck-me" data-skin="square" data-color="blue" value="grouping"> {translateToken value='Grouping-Based'}
                            </label>
                        </div>
                    </div>
				</div>					    

				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label class="control-label">{translateToken value="Grouping name template"}
								<span class="badge badge-warning tooltips pull-right" data-original-title="{translateToken value='This filed will be used to create '}">
									<i class="fa fa-question"></i>
								</span>
							</label>
							<input name="name" value="" type="text" placeholder="{translateToken value="Grouping Name template"}" class="form-control" data-rule-required="true" data-rule-minlength="3" />
						</div>
					</div>
				</div>
			</div>
			<div class="tab-pane fade in" id="tab_1_3">
			    <div class="alert alert-info">
			    	<button aria-hidden="true" data-dismiss="alert" class="close" type="button"></button>
			        <p>
			            <strong>TIP!</strong>
			            Here, you select the mandatory and opcional fields needed to complete the registration process6</p>
			    </div>
			</div>
		</div>
	</div>
	<div class="form-actions nobg">
		<button class="btn btn-success" type="submit">{translateToken value="Save Changes"}</button>
	</div>
</form>
{/block}
