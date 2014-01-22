{if isset($T_DATA.data.notification)}
	{assign var="notifications" value=$T_DATA.data.notification}
{/if}
{assign var="user_details" value=$T_DATA.data.user_details}
<div class="row">
	<div class="col-md-3" id="users-avatar">
		<img class="page-lock-img" src="{Plico_RelativePath file=$T_BIG_USER_AVATAR.avatar}" width="100%" alt="">
		<a class="profile-edit btn btn-xs btn-primary" href="/module/users/profile">Edit</a>
	</div>
	<div class="col-md-4 list-fixed-size">
		<h4>{$user_details.name} {$user_details.surname}</h4>
		<p class="text-muted"><strong>Class </strong><span>Distance Learning</span></p>
		<p><strong>Location </strong><span>{$user_details.uf}, {$user_details.country_code}</span></p>
		<p><strong>Language </strong><span>{$user_details.languages_NAME}</span></p>
		<p class="text-muted"><strong>GPA </strong><span>3.6/95</span></p>
	</div>
	<div class="col-md-5">
		{if isset($notifications)}
		<table class="table table-hover no-space">
	        <thead>
				{foreach $notifications as $key => $notif}
	           	<tr>
					<td><strong class="text-{$notif.type}">{$notif.count}</strong></td>
					<td {if !isset($notif.link) || !$notif.link}colspan="2"{/if}>{$notif.text}</td>
					<td>
						{if isset($notif.link)}
							<a class="btn btn-xs btn-{$notif.type}" href="{$notif.link.link}">{$notif.link.text}</a>	
						{/if}
					</td>
	           	</tr>
	           	{/foreach}
<!--
				<tr>
	              <td><strong>12</strong></td>
	              <td>Messages</td>
	              <td>
	              	<a href="javascript:void(0);" class="btn btn-xs btn-primary">Read</a>	
	              </td>
	           </tr>
	           <tr>
	              <td><strong>3</strong></td>
	              <td colspan="2">New Topics in the forum</td>
	           </tr>
	           <tr>
	              <td><strong>16</strong></td>
	              <td colspan="2">Credit hours completed</td>
	           </tr>
	           <tr>
	              <td><strong>7</strong></td>
	              <td colspan="2">New calendar events</td>
	           </tr>
	           <tr>
	              <td><strong class="text-danger">2</strong></td>
	              <td>Payments Due</td>
	              <td>
	              	<a class="btn btn-xs btn-danger" href="javascript:void(0);">Pay</a>	
	              </td>
	           </tr>
-->
	        </thead>
        </table>
        {else}
        {/if}
	</div>
</div>
<!--
<div id="users-edit-avatar-dialog" tabindex="-1" role="basic" aria-hidden="true" class="modal fade">
	<div class="modal-dialog modal-wide">
    	<div class="modal-content">
      		<div class="modal-header">
        		<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
        		<h4 class="modal-title">{translateToken value='Please select an option'}</h4>
      		</div>
      		<div class="modal-body form">
        		<div class="content">
	        		<div class="alert alert-danger display-hide">
              			<button class="close" data-dismiss="alert"></button>
              			{translateToken value='You have some form errors. Please check below.'}
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
	        	<button type="submit" class="btn blue">{translateToken value='Send'}</button>
	        	<button type="button" class="btn default" data-dismiss="modal">{translateToken value='Close'}</button>
				<div class="copyright pull-left">&copy; 2014 WiseFlex</div>
	      	</div>
	    </div>
	</div>
</div>
-->