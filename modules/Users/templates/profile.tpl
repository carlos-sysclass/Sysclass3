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
					<li><a href="#academic" data-toggle="tab">Academic</a></li>
					<!-- li><a href="#documents" data-toggle="tab">Documents</a></li-->
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
								{translateToken value="Upload picture"}
							</h5>
							{include file="`$T_MODULE_TPLPATH`/profile/avatar.tpl"}
						</div>
						<div class="form-body">
							<h5 class="form-section margin-bottom-10 margin-top-10">
								<i class="fa fa-address-book"></i>
								{translateToken value="Name and Contact"}
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
										{if $value.field_name == 'zip_code' || $value.field_name == 'address' }
										<div class="col-md-6">
											<div class="form-group">
													<label class="control-label">{translateToken value=$value.field_name|user_attrs_translate}</label>
													<input name="{$value.field_name}" value="{$value.field_value}" type="text" placeholder="{translateToken value="$value.field_name|user_attrs_translate"}" class="form-control" />
											</div>
										</div>
										{/if}
									{/foreach}
								{/if}
							
							    {foreach $T_SECTION_TPL['address'] as $template}
							    	{include file=$template}
							    {/foreach}
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
					{if (isset($T_EDIT_USER.attrs) &&  ($T_EDIT_USER.attrs|@count > 0))}
						{foreach $T_EDIT_USER.attrs as $key => $value}
							{if $value.field_name == 'english_communication' || $value.field_name == 'courses' || $value.field_name == 'higher_school' || $value.field_name == 'secondary_school' || $value.field_name == 'area_of_study' }
							<div class="col-md-6">
								<div class="form-group">
										<label class="control-label">{translateToken value=$value.field_name|user_attrs_translate}</label>
										<input name="{$value.field_name}" value="{$value.field_value}" type="text" placeholder="{translateToken value="$value.field_name|user_attrs_translate"}" class="form-control" />
								</div>
							</div>
							{/if}
						{/foreach}
					{/if}
					</div>
					<div class="tab-pane" id="documents">
						<div class="form-body">
							<h5 class="form-section margin-bottom-10 margin-top-10">
								<i class="fa fa-camera"></i>
								{translateToken value="Upload Documents"}
							</h5>
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
