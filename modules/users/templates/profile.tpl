{extends file="layout/default.tpl"}
{block name="content"}
<!-- BEGIN PAGE CONTENT-->
<div class="row">
	<div class="col-md-12">
		<!-- BEGIN PAGE TITLE & BREADCRUMB-->
		<h3 class="page-title">
			{translateToken value="Your Profile"} <small>{translateToken value="See your profile info, change your password and more."}</small>
		</h3>
		<!--
		<ul class="page-breadcrumb breadcrumb">
			<li class="btn-group">
				<button type="button" class="btn blue dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true">
				<span>Actions</span> <i class="icon-angle-down"></i>
				</button>
				<ul class="dropdown-menu pull-right" role="menu">
					<li><a href="#">Action</a></li>
					<li><a href="#">Another action</a></li>
					<li><a href="#">Something else here</a></li>
					<li class="divider"></li>
					<li><a href="#">Separated link</a></li>
				</ul>
			</li>
			<li>
				<i class="icon-home"></i>
				<a href="index.html">Home</a>
				<i class="icon-angle-right"></i>
			</li>
			<li>
				<a href="#">Extra</a>
				<i class="icon-angle-right"></i>
			</li>
			<li><a href="#">User Profile</a></li>
		</ul>
		-->
		<!-- END PAGE TITLE & BREADCRUMB-->
	</div>
</div>
<div class="row profile">
	<div class="col-md-12">
		<!--BEGIN TABS-->
		<div class="tabbable tabbable-custom tabbable-full-width">
			<ul class="nav nav-tabs">
				<!--
				<li class="active"><a href="#tab_1_1" data-toggle="tab">{translateToken value="Overview"}</a></li>
				-->
				<li class="active"><a href="#tab_1_3" data-toggle="tab">{translateToken value="Account"}</a></li>
				<!--
				<li class=""><a href="#tab_1_4" data-toggle="tab">{translateToken value="Your Courses"}</a></li>
				-->
				<!--
				<li><a href="#tab_1_6" data-toggle="tab">{translateToken value="Help"}</a></li>
				-->
			</ul>
			<div class="tab-content">

				<div class="tab-pane" id="tab_1_1">
					{*include file="`$T_MODULE_TPLPATH`/profile.overview.tpl"*}
				</div>

				<!--tab_1_2-->
				<div class="tab-pane active" id="tab_1_3">


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
								<!--
								<li>
									<a data-toggle="tab" href="#tab_2-1">
									<i class="icon-home"></i>
										Contact info
									</a>
								</li>

								<li ><a data-toggle="tab" href="#tab_3-2"><i class="icon-picture"></i> Change Avatar</a></li>
								-->
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
								<!--
								<div id="tab_2-1" class="tab-pane active">
									{include file="`$T_MODULE_TPLPATH`/profile.address.tpl"}
								</div>
								-->
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
				</div>
				<!--end tab-pane-->
				<!--
				<div class="tab-pane" id="tab_1_4">
					{*include file="`$T_MODULE_TPLPATH`/profile.courses.tpl"*}
				</div>
				-->
				<!--end tab-pane-->
				<!--
				<div class="tab-pane" id="tab_1_6">
					<div class="row">
						<div class="col-md-3">
							<ul class="ver-inline-menu tabbable margin-bottom-10">
								<li class="active">
									<a data-toggle="tab" href="#tab_1">
									<i class="icon-briefcase"></i>
									General Questions
									</a>
									<span class="after"></span>
								</li>
								<li><a data-toggle="tab" href="#tab_2"><i class="icon-group"></i> Membership</a></li>
								<li><a data-toggle="tab" href="#tab_3"><i class="icon-leaf"></i> Terms Of Service</a></li>
								<li><a data-toggle="tab" href="#tab_1"><i class="icon-info-sign"></i> License Terms</a></li>
								<li><a data-toggle="tab" href="#tab_2"><i class="icon-tint"></i> Payment Rules</a></li>
								<li><a data-toggle="tab" href="#tab_3"><i class="icon-plus"></i> Other Questions</a></li>
							</ul>
						</div>
						<div class="col-md-9">
							<div class="tab-content">
								<div id="tab_1" class="tab-pane active">
									<div id="accordion1" class="panel-group">
										<div class="panel panel-default">
											<div class="panel-heading">
												<h4 class="panel-title">
													<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#accordion1_1">
													1. Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry ?
													</a>
												</h4>
											</div>
											<div id="accordion1_1" class="panel-collapse collapse  in">
												<div class="panel-body">
													Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
												</div>
											</div>
										</div>
										<div class="panel panel-default">
											<div class="panel-heading">
												<h4 class="panel-title">
													<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#accordion1_2">
													2. Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry ?
													</a>
												</h4>
											</div>
											<div id="accordion1_2" class="panel-collapse collapse">
												<div class="panel-body">
													Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch   et.
													Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
												</div>
											</div>
										</div>
										<div class="panel panel-success">
											<div class="panel-heading">
												<h4 class="panel-title">
													<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#accordion1_3">
													3. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor ?
													</a>
												</h4>
											</div>
											<div id="accordion1_3" class="panel-collapse collapse">
												<div class="panel-body">
													Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch   et.
													Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
												</div>
											</div>
										</div>
										<div class="panel panel-warning">
											<div class="panel-heading">
												<h4 class="panel-title">
													<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#accordion1_4">
													4. Wolf moon officia aute, non cupidatat skateboard dolor brunch ?
													</a>
												</h4>
											</div>
											<div id="accordion1_4" class="panel-collapse collapse">
												<div class="panel-body">
													3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
												</div>
											</div>
										</div>
										<div class="panel panel-danger">
											<div class="panel-heading">
												<h4 class="panel-title">
													<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#accordion1_5">
													5. Leggings occaecat craft beer farm-to-table, raw denim aesthetic ?
													</a>
												</h4>
											</div>
											<div id="accordion1_5" class="panel-collapse collapse">
												<div class="panel-body">
													3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et
												</div>
											</div>
										</div>
										<div class="panel panel-default">
											<div class="panel-heading">
												<h4 class="panel-title">
													<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#accordion1_6">
													6. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth ?
													</a>
												</h4>
											</div>
											<div id="accordion1_6" class="panel-collapse collapse">
												<div class="panel-body">
													3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et
												</div>
											</div>
										</div>
										<div class="panel panel-default">
											<div class="panel-heading">
												<h4 class="panel-title">
													<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#accordion1_7">
													7. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft ?
													</a>
												</h4>
											</div>
											<div id="accordion1_7" class="panel-collapse collapse">
												<div class="panel-body">
													3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et
												</div>
											</div>
										</div>
									</div>
								</div>
								<div id="tab_2" class="tab-pane">
									<div id="accordion2" class="panel-group">
										<div class="panel panel-warning">
											<div class="panel-heading">
												<h4 class="panel-title">
													<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#accordion2_1">
													1. Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry ?
													</a>
												</h4>
											</div>
											<div id="accordion2_1" class="panel-collapse collapse  in">
												<div class="panel-body">
													<p>
														Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
													</p>
													<p>
														Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
													</p>
												</div>
											</div>
										</div>
										<div class="panel panel-danger">
											<div class="panel-heading">
												<h4 class="panel-title">
													<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#accordion2_2">
													2. Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry ?
													</a>
												</h4>
											</div>
											<div id="accordion2_2" class="panel-collapse collapse">
												<div class="panel-body">
													Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch   et.
													Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
												</div>
											</div>
										</div>
										<div class="panel panel-success">
											<div class="panel-heading">
												<h4 class="panel-title">
													<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#accordion2_3">
													3. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor ?
													</a>
												</h4>
											</div>
											<div id="accordion2_3" class="panel-collapse collapse">
												<div class="panel-body">
													Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch   et.
													Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
												</div>
											</div>
										</div>
										<div class="panel panel-default">
											<div class="panel-heading">
												<h4 class="panel-title">
													<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#accordion2_4">
													4. Wolf moon officia aute, non cupidatat skateboard dolor brunch ?
													</a>
												</h4>
											</div>
											<div id="accordion2_4" class="panel-collapse collapse">
												<div class="panel-body">
													3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
												</div>
											</div>
										</div>
										<div class="panel panel-default">
											<div class="panel-heading">
												<h4 class="panel-title">
													<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#accordion2_5">
													5. Leggings occaecat craft beer farm-to-table, raw denim aesthetic ?
													</a>
												</h4>
											</div>
											<div id="accordion2_5" class="panel-collapse collapse">
												<div class="panel-body">
													3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et
												</div>
											</div>
										</div>
										<div class="panel panel-default">
											<div class="panel-heading">
												<h4 class="panel-title">
													<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#accordion2_6">
													6. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth ?
													</a>
												</h4>
											</div>
											<div id="accordion2_6" class="panel-collapse collapse">
												<div class="panel-body">
													3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et
												</div>
											</div>
										</div>
										<div class="panel panel-default">
											<div class="panel-heading">
												<h4 class="panel-title">
													<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#accordion2_7">
													7. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft ?
													</a>
												</h4>
											</div>
											<div id="accordion2_7" class="panel-collapse collapse">
												<div class="panel-body">
													3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et
												</div>
											</div>
										</div>
									</div>
								</div>
								<div id="tab_3" class="tab-pane">
									<div id="accordion3" class="panel-group">
										<div class="panel panel-danger">
											<div class="panel-heading">
												<h4 class="panel-title">
													<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion3" href="#accordion3_1">
													1. Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry ?
													</a>
												</h4>
											</div>
											<div id="accordion3_1" class="panel-collapse collapse  in">
												<div class="panel-body">
													<p>
														Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et.
													</p>
													<p>
														Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et.
													</p>
													<p>
														Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
													</p>
												</div>
											</div>
										</div>
										<div class="panel panel-success">
											<div class="panel-heading">
												<h4 class="panel-title">
													<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion3" href="#accordion3_2">
													2. Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry ?
													</a>
												</h4>
											</div>
											<div id="accordion3_2" class="panel-collapse collapse">
												<div class="panel-body">
													Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch   et.
													Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
												</div>
											</div>
										</div>
										<div class="panel panel-default">
											<div class="panel-heading">
												<h4 class="panel-title">
													<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion3" href="#accordion3_3">
													3. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor ?
													</a>
												</h4>
											</div>
											<div id="accordion3_3" class="panel-collapse collapse">
												<div class="panel-body">
													Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch   et.
													Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
												</div>
											</div>
										</div>
										<div class="panel panel-default">
											<div class="panel-heading">
												<h4 class="panel-title">
													<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion3" href="#accordion3_4">
													4. Wolf moon officia aute, non cupidatat skateboard dolor brunch ?
													</a>
												</h4>
											</div>
											<div id="accordion3_4" class="panel-collapse collapse">
												<div class="panel-body">
													3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
												</div>
											</div>
										</div>
										<div class="panel panel-default">
											<div class="panel-heading">
												<h4 class="panel-title">
													<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion3" href="#accordion3_5">
													5. Leggings occaecat craft beer farm-to-table, raw denim aesthetic ?
													</a>
												</h4>
											</div>
											<div id="accordion3_5" class="panel-collapse collapse">
												<div class="panel-body">
													3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et
												</div>
											</div>
										</div>
										<div class="panel panel-default">
											<div class="panel-heading">
												<h4 class="panel-title">
													<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion3" href="#accordion3_6">
													6. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth ?
													</a>
												</h4>
											</div>
											<div id="accordion3_6" class="panel-collapse collapse">
												<div class="panel-body">
													3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et
												</div>
											</div>
										</div>
										<div class="panel panel-default">
											<div class="panel-heading">
												<h4 class="panel-title">
													<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion3" href="#accordion3_7">
													7. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft ?
													</a>
												</h4>
											</div>
											<div id="accordion3_7" class="panel-collapse collapse">
												<div class="panel-body">
													3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				-->
				<!--end tab-pane-->
			</div>
		</div>
		<!--END TABS-->
	</div>
</div>
<!-- END PAGE CONTENT-->
{/block}
