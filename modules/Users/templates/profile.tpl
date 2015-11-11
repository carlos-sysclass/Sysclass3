{extends file="layout/default.tpl"}
{block name="content"}
<!-- BEGIN PAGE CONTENT-->
<form id="form-users" role="form" class="form-validate" method="post" action="{$T_FORM_ACTION}">
	<div class="form-body">
		<div class="row profile">
			<div class="col-md-12">
				<!--BEGIN TABS-->
				<div class="tabbable tabbable-custom tabbable-full-width">
				<!--
					<ul class="nav nav-tabs">

						<li class="active"><a href="#tab_1_1" data-toggle="tab">{translateToken value="Overview"}</a></li>

						<li class="active"><a href="#tab_1_3" data-toggle="tab">{translateToken value="Account"}</a></li>

						<li class=""><a href="#tab_1_4" data-toggle="tab">{translateToken value="Your Courses"}</a></li>


						<li><a href="#tab_1_6" data-toggle="tab">{translateToken value="Help"}</a></li>

					</ul>
					-->
					<div class="tab-content">

						<!-- <div class="tab-pane" id="tab_1_1"> -->
							{*include file="`$T_MODULE_TPLPATH`/profile.overview.tpl"*}
						<!-- </div> -->

						<!--tab_1_2-->
						<!-- <div class="tab-pane active" id="tab_1_3"> -->
							<div class="row">
								<div class="col-md-3">
									<ul class="list-unstyled profile-nav" style="margin-bottom: 0px;">
										<li>
											{if ({$T_EDIT_USER.avatars[0].url})}
												<img width="100%" src="{$T_EDIT_USER.avatars[0].url}" class="img-responsive" alt="" />
											{else}
												<img width="100%" src="{Plico_GetResource file='images/placeholder/avatar.png'}" class="img-responsive" alt="" />
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
									<ul class="ver-inline-menu tabbable margin-bottom-10">
										<li class="active">
											<a data-toggle="tab" href="#tab_1-1">
											<i class="icon-cog"></i>
											{translateToken value="Personal info"}
											</a>
											<span class="after"></span>
										</li>
										<li ><a data-toggle="tab" href="#tab_1-2"><i class="icon-picture"></i> {translateToken value="Profile Picture"}</a></li>
										<li ><a data-toggle="tab" href="#tab_1-3"><i class="icon-lock"></i> {translateToken value="Change Password"}</a></li>
										<!--
										<li ><a data-toggle="tab" href="#tab_1-4"><i class="icon-lock"></i> {translateToken value="Your Courses"}</a></li>
										-->
										<!--
										<li ><a data-toggle="tab" href="#tab_5-4"><i class="icon-eye-open"></i> {translateToken value="Privacity Settings"}</a></li>
										-->
									</ul>
								</div>
								<div class="col-md-9 profile-info">
									<h1>
										<span data-update="name">{$T_EDIT_USER.name}</span>
										<span data-update="surname"> {$T_EDIT_USER.surname}</span>
										<div class="btn-group">
											<button href="#" class="dropdown-toggle btn btn-default" data-toggle="dropdown" data-close-others="true" aria-expanded="false">
												<img alt="" src="{Plico_GetResource file="img/flags/`$T_LANGUAGE_MENU.country_code|strtolower`.png"}"/>
									      		{$T_LANGUAGE_MENU.name}
									      		<i class="fa fa-angle-down"></i>
									      	</button>
									      	<ul role="menu" class="dropdown-menu pull-right">
												{foreach $T_LANGUAGE_MENU.items as $subitem}
												<li>
													<a href="{$subitem.link}" data-callback="change-language" data-language="{$subitem.code}">
										                <img alt="" src="{Plico_GetResource file="img/flags/`$subitem.country_code|strtolower`.png"}"/>
														{$subitem.name}
													</a>
												</li>
										    {/foreach}
										    </ul>
										</div>
									</h1>

									<p data-update="short_description">{$T_EDIT_USER.short_description}</p>

									<p><a href="#" data-update="website">{$T_EDIT_USER.website}</a></p>

									<div class="tab-content margin-top-20">
										<div id="tab_1-1" class="tab-pane active">
											<div class="form-body">
												{include file="`$T_MODULE_TPLPATH`/profile/personal.tpl"}
											</div>
										</div>

										{*include file="`$T_MODULE_TPLPATH`/profile/address.tpl"*}

										<div id="tab_1-2" class="tab-pane">
											<div class="form-body">
												{include file="`$T_MODULE_TPLPATH`/profile/avatar.tpl"}
											</div>
										</div>
										<div id="tab_1-3" class="tab-pane">
											<div class="form-body">
												{include file="`$T_MODULE_TPLPATH`/profile/password.tpl"  T_CHECK_OLD=true}
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
									</div>

									<!--
									<ul class="list-inline">
										<li class="tooltips" data-original-title="{translateToken value="Your Location"}" data-placement="bottom"><i class="icon-map-marker"></i> {$T_EDIT_USER.uf}, {$T_EDIT_USER.country_code}</li>
										<li><i class="icon-calendar"></i> <span data-update="birthday" data-format="date" data-format-from="isodate" >{$T_EDIT_USER.birthday}</span></li>
										
										{if $T_EDIT_USER.polo_id}
											<li class="tooltips" data-original-title="{translateToken value="Your Proctoring Center"}" data-placement="bottom"><i class="icon-briefcase"></i> {$T_USER_POLO.nome}</li>
										{/if}

										<li><i class="icon-star"></i> Top Seller</li>
										<li><i class="icon-heart"></i> BASE Jumping</li>
										
									</ul>
									-->
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
