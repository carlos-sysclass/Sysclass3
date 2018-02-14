{extends file="layout/default.tpl"}
{block name="content"}


{has_permission resource="Users" action="change-password" assign="canChangePassword"}

{has_role role="Teacher" assign="isTeacher"}



<!-- BEGIN PAGE CONTENT-->
<form id="form-users" role="form" class="form-validate" method="post" action="{$T_FORM_ACTION}">
	<div class="form-body">
		<div class="row profile">
			<div class="col-md-12">
				<script type="text/javascript">
				$(document).ready(function(){
				   $('#profile a:first').tab('show');
				});
				</script>
				<ul class="nav nav-tabs" id="profile">
					<li class="active"><a href="#yourinfo" data-toggle="tab">Your info</a></li>
					<!-- li><a href="#academic" data-toggle="tab">Academic</a></li-->
					<li><a href="#documents" data-toggle="tab">Documents</a></li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="yourinfo">
						<!--BEGIN TABS-->
						<!-- <div class="tab-pane" id="tab_1_1"> -->
						{*include /file="`$T_MODULE_TPLPATH`/profile.overview.tpl"*}
						<!-- </div> -->
						<!-- ADD USER WIDGET -->
						<div class="widget-container">
						{* include file="pages/widget-container.tpl" *}
						</div>
						
						<div class="form-body">
							<h5 class="form-section margin-bottom-10 margin-top-10">
								<i class="fa fa-camera"></i>
								{translateToken value="Upload your picture"}
							</h5>
							{include file="`$T_MODULE_TPLPATH`/profile/avatar.tpl"}
						</div>
						<div class="form-body">
							<h5 class="form-section margin-bottom-10 margin-top-10">
								<i class="fa fa-graduation-cap"></i>
								{translateToken value="Academic info"}
							</h5>
							<div class="col-md-3">
								<div class="form-group">
									<label class="control-label">{translateToken value="Student ID"}</label>
									<input name="id" readonly="readonly" value="{$T_EDIT_USER.id}" type="text" placeholder="{translateToken value="Student ID"}" class="form-control" />
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label class="control-label">{translateToken value="Program"}</label>
									<input name="enrollment_program" readonly="readonly" value="{$T_EDIT_USER.attrs.courses}" type="text" placeholder="{translateToken value="Program"}" class="form-control" />
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label class="control-label">{translateToken value="Enrollment date"}</label>
									<input name="enrollment_date" readonly="readonly" value="{$T_EDIT_USER.enrollments[0].start_date}" type="text" placeholder="{translateToken value="Enrollment date"}" class="form-control" />
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label class="control-label">{translateToken value="Start date"}</label>
									<input name="enrollment_start_date" readonly="readonly" value="{$T_EDIT_USER.userreport.first_access}" type="text" placeholder="{translateToken value="Start date"}" class="form-control" />
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label class="control-label">{translateToken value="Completion date"}</label>
									<input name="enrollment_term" readonly="readonly" value="{$T_EDIT_USER.term_date}" type="text" placeholder="{translateToken value="Completion date"}" class="form-control" />
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label class="control-label">{translateToken value="Days remaining in this term"}</label>
									<input name="enrollment_days_term" readonly="readonly" value="{$T_EDIT_USER.days_end_term}" type="text" placeholder="{translateToken value="Days remaining"}" class="form-control" />
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label class="control-label">{translateToken value="Last login"}</label>
									<input name="enrollment_days_term" readonly="readonly" value="{$T_EDIT_USER.userreport.last_enrollment}" type="text" placeholder="{translateToken value="Last login"}" class="form-control" />
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label class="control-label">{translateToken value="Number of logins"}</label>
									<input name="enrollment_days_term" readonly="readonly" value="{$T_EDIT_USER.userreport.n_access}" type="text" placeholder="{translateToken value="Number of logins"}" class="form-control" />
								</div>
							</div>
						</div>
						
						<div class="clearfix"></div>
						<div class="form-body">
							<h5 class="form-section margin-bottom-10 margin-top-10">
								<i class="fa fa-user"></i>
								{translateToken value="General info"}
							</h5>
							{include file="`$T_MODULE_TPLPATH`/profile/personal.tpl"}
						</div>
						{if (isset($T_SECTION_TPL['address']) &&  ($T_SECTION_TPL['address']|@count > 0))}
						<div class="clearfix"></div>
						<div class="form-body">
							<h5 class="form-section margin-bottom-10 margin-top-10">
								<i class="fa fa-globe"></i>
								{translateToken value="Address"}
							</h5>
								{if (isset($T_EDIT_USER.attrs) &&  ($T_EDIT_USER.attrs|@count > 0))}
									{foreach $T_EDIT_USER.attrs as $key => $value}
										{if $key == 'address' }
										<div class="col-md-6">
											<div class="form-group">
													<label class="control-label">{translateToken value=$key|user_attrs_translate}</label>
													<input name="attrs_{$key}" value="{$value}" type="text" placeholder="{translateToken value="$key|user_attrs_translate"}" class="form-control" />
											</div>
										</div>
										{/if}
									{/foreach}
								{/if}
								
								<div class="col-md-6">
									<div class="form-group">
										<label class="control-label">{translateToken value="City/Borough/District"}</label>
										<input name="city" value="" type="text" placeholder="{translateToken value="City/Borough/District"}" class="form-control" data-rule-minlength="3" />
									</div>
								</div>
							<div class="clearfix"></div>
							<div class="col-md-4">
									<div class="form-group">
										<label class="control-label">{translateToken value="State/Province"}</label>
										<input name="state" value="" type="text" placeholder="{translateToken value="State/Province"}" class="form-control" />
									</div>
								</div>
								{if (isset($T_EDIT_USER.attrs) &&  ($T_EDIT_USER.attrs|@count > 0))}
									{foreach $T_EDIT_USER.attrs as $key => $value}
										{if $key == 'zip_code'}
										<div class="col-md-4">
											<div class="form-group">
													<label class="control-label">{translateToken value=$key|user_attrs_translate}</label>
													<input name="attrs_{$key}" value="{$value}" type="text" placeholder="{translateToken value="$key|user_attrs_translate"}" class="form-control" />
											</div>
										</div>
										{/if}
									{/foreach}
								{/if}
								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label">{translateToken value="Country"}</label>
										<select class="select2-me form-control" name="country" data-format-as="country-list">
											{foreach $T_COUNTRY_CODES as $key => $code}
												<option value="{$key}">{$code}</option>
											{/foreach}
										</select>
									</div>
								</div>
								
							    {*foreach $T_SECTION_TPL['address'] as $template*}
							    	{*include file=$template*}
							    {*/foreach*}
							    
							    
						</div>
						{/if}
						<div class="clearfix"></div>
						<!--  {if $isTeacher}
							<div class="form-body">
								<h5 class="form-section margin-bottom-10 margin-top-10">
									<i class="fa fa-camera"></i>
									{translateToken value="Instructor info2"}
								</h5>
								{include file="`$T_MODULE_TPLPATH`/profile/curriculum.tpl"}
							</div>
						{/if} -->
						{if $canChangePassword}
							<div class="form-body">
								<h5 class="form-section margin-bottom-10 margin-top-10">
									<i class="fa fa-lock"></i>
									{translateToken value="Change password"}
								</h5>
								{include file="`$T_MODULE_TPLPATH`/profile/password.tpl"  T_CHECK_OLD=true}
							</div>
						{/if}
					</div>
					<div class="tab-pane" id="academic">
						<div class="col-md-3">
							<div class="form-group">
								<label class="control-label">{translateToken value="Student ID"}</label>
								<input name="id" readonly="readonly" value="{$T_EDIT_USER.id}" type="text" placeholder="{translateToken value="Student ID"}" class="form-control" />
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label class="control-label">{translateToken value="Enrollment date"}</label>
								<input name="enrollment_date" readonly="readonly" value="{$T_EDIT_USER.enrollments[0].start_date}" type="text" placeholder="{translateToken value="Enrollment date"}" class="form-control" />
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label class="control-label">{translateToken value="Start date"}</label>
								<input name="enrollment_date" readonly="readonly" value="{$T_EDIT_USER.userreport.first_access}" type="text" placeholder="{translateToken value="Date of first login"}" class="form-control" />
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label class="control-label">{translateToken value="Days remaning"}</label>
								<input name="enrollment_date" readonly="readonly" value="{$T_EDIT_USER.days_end_term}" type="text" placeholder="{translateToken value="Date of first login"}" class="form-control" />
							</div>
						</div>
						{if (isset($T_EDIT_USER.attrs) &&  ($T_EDIT_USER.attrs|@count > 0))}
							{foreach $T_EDIT_USER.attrs as $key => $value}
								{if $key == 'english_communication' || $key == 'higher_school' || $key == 'secondary_school' || $key == 'area_of_study' }
								<div class="col-md-6">
									<div class="form-group">
											<label class="control-label">{translateToken value=$key|user_attrs_translate}</label>
											<input name="attrs_{$key}" value="{$value}" type="text" placeholder="{translateToken value="$key|user_attrs_translate"}" class="form-control" />
									</div>
								</div>
								{/if}
							{/foreach}
						{/if}
					</div>
					<div class="tab-pane" id="documents">
						<div class="form-body">
							{include file="`$T_MODULE_TPLPATH`/profile/documents.tpl"}
						</div>
					</div>	
				</div>
				<!--
				<div id="tab_1-4" class="tab-pane">
					<div class="form-body">
					{*include file="`$T_MODULE_TPLPATH`/profile/courses.tpl"*}
					</div>
				</div>
				-->
				<!--
				<div id="tab_5-4" class="tab-pane">
					<form action="#" class="">
						<table class="table table-bordered table-striped">
							<tr>
								<td>
									Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus..
								</td>
								<td>
									<label class="uniform-inline">
									<input type="radio" name="optionsRadios1" value="option1" />
									Yes
									</label>
									<label class="uniform-inline">
									<input type="radio" name="optionsRadios1" value="option2" checked />
									No
									</label>
								</td>
							</tr>
							<tr>
								<td>
									Enim eiusmod high life accusamus terry richardson ad squid wolf moon
								</td>
								<td>
									<label class="uniform-inline">
									<input type="checkbox" value="" /> Yes
									</label>
								</td>
							</tr>
							<tr>
								<td>
									Enim eiusmod high life accusamus terry richardson ad squid wolf moon
								</td>
								<td>
									<label class="uniform-inline">
									<input type="checkbox" value="" /> Yes
									</label>
								</td>
							</tr>
							<tr>
								<td>
									Enim eiusmod high life accusamus terry richardson ad squid wolf moon
								</td>
								<td>
									<label class="uniform-inline">
									<input type="checkbox" value="" /> Yes
									</label>
								</td>
							</tr>
						</table>
						<div class="margin-top-10">
							<a href="#" class="btn green">Save Changes</a>
							<a href="#" class="btn default">Cancel</a>
						</div>
					</form>
				</div>
				-->
				<!--
				<div class="row profile-account">
					<div class="col-md-3">

					</div>

					<div class="col-md-9">

					</div>
				</div>
				-->
				<!-- </div> -->
				<!--end tab-pane-->
				<!--
				<div class="tab-pane" id="tab_1_4">
					{*include file="`$T_MODULE_TPLPATH`/profile.courses.tpl"*}
				</div>
				-->
				<!--end tab-pane-->
				<!--end tab-pane-->
			</div>
			<div class="col-md-12">
				<div class="form-body margin-top-10">
					<button class="btn green" type="submit">{translateToken value="Save changes"}</button>
				</div>
			</div>
			<!--END TABS-->
		</div>
	</div>
</form>
<!-- END PAGE CONTENT-->
{/block}
