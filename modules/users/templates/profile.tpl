{extends file="layout/default.tpl"}
{block name="content"}
<!-- BEGIN PAGE CONTENT-->
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
									<img width="100%" src="{Plico_RelativePath file=$T_BIG_USER_AVATAR.avatar}" class="img-responsive" alt="" />
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
						<div class="col-md-9 profile-info">
							<h1>{$T_EDIT_USER.name} {$T_EDIT_USER.surname}</h1>
							<p>{$T_EDIT_USER.short_description}</p>
							<p><a href="#">{$T_EDIT_USER.website}</a></p>
							<ul class="list-inline">
								<li class="tooltips" data-original-title="{translateToken value="Your Location"}" data-placement="bottom"><i class="icon-map-marker"></i> {$T_EDIT_USER.uf}, {$T_EDIT_USER.country_code}</li>
								{if $T_EDIT_USER.data_nascimento}
									<li><i class="icon-calendar"></i> {$T_EDIT_USER.data_nascimento}</li>
								{/if}
								{if $T_EDIT_USER.polo_id}
									<li class="tooltips" data-original-title="{translateToken value="Your Proctoring Center"}" data-placement="bottom"><i class="icon-briefcase"></i> {$T_USER_POLO.nome}</li>
								{/if}
								<!--
								<li><i class="icon-star"></i> Top Seller</li>
								<li><i class="icon-heart"></i> BASE Jumping</li>
								-->
							</ul>
						</div>
					</div>
					<div class="row profile-account">
						<div class="col-md-3">
							<ul class="ver-inline-menu tabbable margin-bottom-10">
								<li class="active">
									<a data-toggle="tab" href="#tab_1-1">
									<i class="icon-cog"></i>
									{translateToken value="Personal info"}
									</a>
									<span class="after"></span>
								</li>

								<li>
									<a data-toggle="tab" href="#tab_2-1">
									<i class="icon-home"></i>
										Contact info
									</a>
								</li>

								<li ><a data-toggle="tab" href="#tab_3-2"><i class="icon-picture"></i> Change Avatar</a></li>

								<li ><a data-toggle="tab" href="#tab_4-3"><i class="icon-lock"></i> {translateToken value="Change Password"}</a></li>
								<li ><a data-toggle="tab" href="#tab_4-5"><i class="icon-lock"></i> {translateToken value="Your Courses"}</a></li>
								<!--
								<li ><a data-toggle="tab" href="#tab_5-4"><i class="icon-eye-open"></i> {translateToken value="Privacity Settings"}</a></li>
								-->
							</ul>
						</div>

						<div class="col-md-9">
							<div class="tab-content">
								<div id="tab_1-1" class="tab-pane active">
									{include file="`$T_MODULE_TPLPATH`/profile.personal.tpl"}
								</div>

								<div id="tab_2-1" class="tab-pane active">
									{include file="`$T_MODULE_TPLPATH`/profile.address.tpl"}
								</div>

								<div id="tab_3-2" class="tab-pane">
									<p>Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.</p>
									<form action="#" role="form">
										<div class="form-group">
											<div class="thumbnail" style="width: 310px;">
												<img src="http://www.placehold.it/310x170/EFEFEF/AAAAAA&amp;text=no+image" alt="">
											</div>
											<div class="margin-top-10 fileupload fileupload-new" data-provides="fileupload">
												<div class="input-group input-group-fixed">
													<span class="input-group-btn">
													<span class="uneditable-input">
													<i class="icon-file fileupload-exists"></i>
													<span class="fileupload-preview"></span>
													</span>
													</span>
													<span class="btn default btn-file">
													<span class="fileupload-new"><i class="icon-paper-clip"></i> {translateToken value="Select file"}</span>
													<span class="fileupload-exists"><i class="icon-undo"></i> {translateToken value="Change"}</span>
													<input type="file" class="default" />
													</span>
													<a href="#" class="btn red fileupload-exists" data-dismiss="fileupload"><i class="icon-trash"></i> {translateToken value="Remove"}</a>
												</div>
											</div>
											<span class="label label-danger">NOTE!</span>
											<span>
											Attached image thumbnail is
											supported in Latest Firefox, Chrome, Opera,
											Safari and Internet Explorer 10 only
											</span>
										</div>
										<div class="margin-top-10">
											<a href="#" class="btn green">Submit</a>
											<a href="#" class="btn default">Cancel</a>
										</div>
									</form>
								</div>
								<div id="tab_4-3" class="tab-pane">
									{include file="`$T_MODULE_TPLPATH`/profile.password.tpl"}
								 </div>
								<div id="tab_4-5" class="tab-pane">
									{include file="`$T_MODULE_TPLPATH`/profile.courses.tpl"}
								</div>
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
						<!--end col-md-9-->
					</div>
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
		</div>
		<!--END TABS-->
	</div>
</div>
<!-- END PAGE CONTENT-->
{/block}
