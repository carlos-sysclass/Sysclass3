{extends file="layout/default.tpl"}
{block name="content"}


{has_permission resource="Users" action="change-password" assign="canChangePassword"}

{has_role role="Instructor" assign="isTeacher"}



<!-- BEGIN PAGE CONTENT-->
<form id="form-users" role="form" class="form-validate" method="post" action="{$T_FORM_ACTION}">
	<div class="form-body">
		<div class="row profile">
			<div class="col-md-12">
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
						<i class="fa fa-edit"></i>
						{translateToken value="Your info"}
					</h5>
					{include file="`$T_MODULE_TPLPATH`/profile/personal.tpl"}
				</div>

				{if (isset($T_SECTION_TPL['address']) &&  ($T_SECTION_TPL['address']|@count > 0))}
				<div class="form-body">
					<h5 class="form-section margin-bottom-10 margin-top-10">
						<i class="fa fa-globe"></i>
						{translateToken value="Address"}
					</h5>
					    {foreach $T_SECTION_TPL['address'] as $template}
				        	{include file=$template}
					    {/foreach}
				</div>
				{/if}
				<div class="form-body">
					<h5 class="form-section margin-bottom-10 margin-top-10">
						<i class="fa fa-camera"></i>
						{translateToken value="Upload picture"}
					</h5>
					{include file="`$T_MODULE_TPLPATH`/profile/avatar.tpl"}
				</div>
				{if $isTeacher}
					<div class="form-body">
						<h5 class="form-section margin-bottom-10 margin-top-10">
							<i class="fa fa-camera"></i>
							{translateToken value="Instructor Info"}
						</h5>
						{include file="`$T_MODULE_TPLPATH`/profile/curriculum.tpl"}
					</div>
				{/if}

				{if $canChangePassword}
					<div class="form-body">
						<h5 class="form-section margin-bottom-10 margin-top-10">
							<i class="fa fa-hash"></i>
							{translateToken value="Change your Password"}
						</h5>
						{include file="`$T_MODULE_TPLPATH`/profile/password.tpl"  T_CHECK_OLD=true}
					</div>
				{/if}

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
					<button class="btn green" type="submit">{translateToken value="Save Changes"}</button>
				</div>
			</div>
			<!--END TABS-->
		</div>
	</div>
</form>
<!-- END PAGE CONTENT-->
{/block}
