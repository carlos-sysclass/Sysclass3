{extends file="layout/default.tpl"}
{block name="content"}


{has_permission resource="Users" action="change-password" assign="canChangePassword"}

{has_role role="Teacher" assign="isTeacher"}



<!-- BEGIN PAGE CONTENT-->
<form id="form-users" role="form" class="form-validate" method="post" action="{$T_FORM_ACTION}">
	<div class="form-body">
		<div class="row profile">
			<div class="col-md-12">
				<!--BEGIN TABS-->
						<!-- <div class="tab-pane" id="tab_1_1"> -->
							{*include /file="`$T_MODULE_TPLPATH`/profile.overview.tpl"*}
						<!-- </div> -->

						<!--tab_1_2-->
						<!-- <div class="tab-pane active" id="tab_1_3"> -->
							<div class="row profile-header">
								<div class="col-md-2">
									<ul class="list-unstyled profile-nav" style="margin-bottom: 0px;">
										<li>
											{if ({$T_EDIT_USER.avatars[0].url})}
												<img width="100%" src="{$T_EDIT_USER.avatars[0].url}" class="img-responsive user-profile-image" alt="" />
											{else}
												<img width="100%" src="{Plico_GetResource file='images/placeholder/avatar.png'}" class="img-responsive user-profile-image" alt="" />
											{/if}
											<a href="#" class="profile-edit">edit</a>
										</li>
										{foreach $T_SUMMARY as $key => $value}
											<li>
												<a href="{$value.link.link}" class="text-{$value.type}">{$value.text}
												{if $value.count > 0}
													<span>{$value.count}</span>
												{/if}
												</a>
											</li>
										{/foreach}
									</ul>
								</div>
								<div class="col-md-10">
									<div class="row">
										<div class="col-md-12">
											<h2 class="no-margin">
												<span data-update="name">{$T_EDIT_USER.name}</span>
												<span data-update="surname"> {$T_EDIT_USER.surname}</span>
											</h2>
										</div>
										<div class="row">
											<!-- PUT HERE USER BADGES -->
											<div class="col-md-12 list-separated profile-stat">
		                                        <div class="col-md-4 col-sm-4 col-xs-6">
		                                            <div class="uppercase profile-stat-title"> 37 </div>
		                                            <div class="uppercase profile-stat-text"> Projects </div>
		                                        </div>
		                                        <div class="col-md-4 col-sm-4 col-xs-6">
		                                            <div class="uppercase profile-stat-title"> 51 </div>
		                                            <div class="uppercase profile-stat-text"> Tasks </div>
		                                        </div>
		                                        <div class="col-md-4 col-sm-4 col-xs-6">
		                                            <div class="uppercase profile-stat-title"> 61 </div>
		                                            <div class="uppercase profile-stat-text"> Uploads </div>
		                                        </div>
		                                    </div>
										</div>
									</div>
									<!-- 
									<p data-update="short_description">{$T_EDIT_USER.short_description}</p>
									<p><a href="#" data-update="website">{$T_EDIT_USER.website}</a></p>
 									-->
								</div>
								<div class="clearfix"></div>
							</div>
								<div class="form-body">
									<h5 class="form-section margin-bottom-10 margin-top-10">
										<i class="fa fa-edit"></i>
										{translateToken value="Personal Info"}
									</h5>
									{include file="`$T_MODULE_TPLPATH`/profile/personal.tpl"}
								</div>

								{if (isset($T_SECTION_TPL['address']) &&  ($T_SECTION_TPL['address']|@count > 0))}
								<div class="form-body">
									<h5 class="form-section margin-bottom-10 margin-top-10">
										<i class="fa fa-globe"></i>
										{translateToken value="Street or P.O. Box"}
									</h5>
									    {foreach $T_SECTION_TPL['address'] as $template}
								        	{include file=$template}
									    {/foreach}
									{/if}
								</div>
								<div class="form-body">
								<h5 class="form-section margin-bottom-10 margin-top-10">
									<i class="fa fa-camera"></i>
									{translateToken value="Change your Avatar"}
								</h5>
								
									{include file="`$T_MODULE_TPLPATH`/profile/avatar.tpl"}
								</div>
								{if $isTeacher}
									<div class="form-body">
										<h5 class="form-section margin-bottom-10 margin-top-10">
											<i class="fa fa-camera"></i>
											{translateToken value="Teacher Info"}
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
									</div>

								</div>
							</div>
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
					<div class="margin-top-10">
						<button class="btn green" type="submit">Save Changes</button>
					</div>

				</div>
				<!--END TABS-->
			</div>
		</div>
	</div>
</form>
<!-- END PAGE CONTENT-->
{/block}
