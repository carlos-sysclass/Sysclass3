<div id="dialogs-roles-resources" class="modal fade" role="dialog" aria-labelledby="gridSystemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">
                    <i class="fa fa-lock"></i>
                    {translateToken value='Manage Role Permissions'}
                </h4>
            </div>
            <div class="modal-body">
				<div class="row margin-top-20">
					<div class="col-md-12">
						{include "`$smarty.current_dir`/../blocks/table.tpl" 
						FORCE_INIT=1
						T_MODULE_CONTEXT=$T_ROLES_RESOURCES_DIALOG_CONTEXT
						T_MODULE_ID="roles_resources"}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

{block name="content"}
	<div class="modal-dialog modal-lg">
    	<div class="modal-content">
		    <div class="modal-header">
	    		<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
	        	<h4 class="modal-title">{translateToken value="Send Message"}</h4>
	      	</div>
	      	<div class="modal-body">
	        	<div class="content">
               		<div class="alert alert-danger display-hide">
                  		<button class="close" data-dismiss="alert"></button>
                  		{translateToken value="You have some form errors. Please, check below."}
               		</div>
					<div class="form-group">
							<label class="control-label visible-ie8 visible-ie9">{translateToken value=""}</label>

					</div>
					<div class="form-group">
						<label class="control-label visible-ie8 visible-ie9">{translateToken value=""}</label>
						<textarea class="wysihtml5 form-control placeholder-no-fix" id="body" name="body" rows="6" placeholder="{translateToken value="Message Body"}" ></textarea>
					</div>
				{* foreach $T_MOD_MESSAGES_FORM.attachment as $fileupload *}
				<!--
				<div class="form-group">
					<label class="control-label visible-ie8 visible-ie9">{$fileupload.label}</label>
					<div class="fileupload fileupload-new" data-provides="fileupload">
                    	<span class="btn default btn-file">
	                        <span class="fileupload-new"><i class="icon-paper-clip"></i> Select file</span>
	                        <span class="fileupload-exists"><i class="icon-undo"></i> Change</span>
	                    	<input type="file" name="{$fileupload.name}" class="default" />
						</span>
	                    <span class="fileupload-preview" style="margin-left:5px;"></span>
						<a href="#" class="close fileupload-exists" data-dismiss="fileupload" style="float: none; margin-left:5px;"></a>
					</div>
				</div>
				-->
				{* /foreach *}
				<!-- END FORM -->        
				</div>
	      	</div>
	      	<div class="modal-footer">
	        	<button type="submit" class="btn blue">{translateToken value="Send"}</button>
	        	<button type="button" class="btn default" data-dismiss="modal">{translateToken value="Close"}</button>
				<div class="copyright pull-left">
					&copy; 2014 WiseFlex
				</div>
	      	</div>
		</div>
	</div>
{/block}