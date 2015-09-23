{if isset($T_DATA.data.notification)}
	{assign var="notifications" value=$T_DATA.data.notification}
{/if}
{assign var="user_details" value=$T_DATA.data.user_details}
<div class="row">
	<div class="col-md-3 col-sm-5 col-xs-5" id="users-avatar">
		<img class="page-lock-img" src="{$user_details.avatars[0].url}" width="100%" alt="">
	</div>
	<div class="col-md-9 col-sm-7 col-xs-7">
		<h3 class="users-panel-username">{$user_details.name} {$user_details.surname}</h3>
		<div class="row">
			<div class="col-md-6 col-sm-12 col-xs-12 list-fixed-size">
				<h6 class="users-panel-username">
					NOME DO CURSO
				</h6>
				<ul class="list-group border-bottom users-panel-links hidden-sm hidden-xs">
					<!--
					<li class="list-group-item">
						<a href="javascript: void(0);">{translateToken value="Grades"}</a>
					</li>
					-->
					<li class="list-group-item">
						<a href="javascript: void(0);">{translateToken value="Reports"}</a>
					</li>
					<li class="list-group-item">
						<a href="javascript: void(0);">{translateToken value="Roadmap"}</a>
					</li>
				</ul>
			</div>
			<div class="col-md-6 col-sm-12 col-xs-12">
				{if isset($notifications)}
				<table class="table table-hover no-space users-panel-notification-table">
			        <thead>
						{foreach $notifications as $key => $notif}
			           	<tr>
			           		<!--
							<td></td>
							<td {if !isset($notif.link) || !$notif.link}colspan="2"{/if}><strong class="text-{$notif.type}">{$notif.count}</strong> {$notif.text}</td>
							-->
							<td>
								<span class="btn btn-xs btn-link text-{$notif.type}"><strong>{$notif.count}</strong></span>
								{$notif.text}
							</td>
							<td align="right">
								{if isset($notif.link)}
									<a class="btn btn-xs btn-{$notif.type}" href="{$notif.link.link}">{$notif.link.text}</a>
								{/if}
							</td>
			           	</tr>
			           	{/foreach}
			        </thead>
		        </table>
		        {else}
		        {/if}
			</div>
		</div>
	</div>
</div>
<!--
<div id="users-edit-avatar-dialog" tabindex="-1" role="basic" aria-hidden="true" class="modal fade">
	<div class="modal-dialog modal-wide">
    	<div class="modal-content">
      		<div class="modal-header">
        		<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
        		<h4 class="modal-title">{translateToken value="Please select an option %s" param1="1" }</h4>
      		</div>
      		<div class="modal-body form">
        		<div class="content">
	        		<div class="alert alert-danger display-hide">
              			<button class="close" data-dismiss="alert"></button>
              			{translateToken value="You have some form errors. Please check below."}
            		</div>
					{*$T_MOD_MESSAGES_FORM.javascript*}
					<form class="form-horizontal container" {*$T_MOD_MESSAGES_FORM.attributes*} >
						{*T_MOD_MESSAGES_FORM.hidden*}
						<div class="form-body">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<div class="radio-list">
			                                <label>
			                                 	<input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked> Upload a File
			                                </label>

											<div class="fileupload fileupload-new" data-provides="fileupload">
												<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 400px; max-height: 300px; line-height: 20px;"></div>
												<div>
													<span class="btn default btn-file">
														<span class="fileupload-new"><i class="icon-paper-clip"></i> Select image</span>
														<span class="fileupload-exists"><i class="icon-undo"></i> Change</span>
														<input type="file" class="default" />
													</span>
													<a href="#" class="btn red fileupload-exists" data-dismiss="fileupload"><i class="icon-trash"></i> Remove</a>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<div class="radio-list">
			                                <label>
			                                 	<input type="radio" name="optionsRadios" id="optionsRadios1" value="option1"> Set up your own gravatar
			                                </label>
			                            </div>
			                        </div>
								</div>
							</div>
						</div>
					</form>
				</div>
	      	</div>
	      	<div class="modal-footer">
	        	<button type="submit" class="btn blue">{translateToken value="Send"}</button>
	        	<button type="button" class="btn default" data-dismiss="modal">{translateToken value="Close"}</button>
				<div class="copyright pull-left">&copy; 2014 WiseFlex</div>
	      	</div>
	    </div>
	</div>
</div>
-->
