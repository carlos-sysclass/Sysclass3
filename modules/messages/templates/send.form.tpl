{block name="content"}
  <div class="modal-dialog modal-wide">
	{$T_MOD_MESSAGES_FORM.javascript}
	<form {$T_MOD_MESSAGES_FORM.attributes}>
		{$T_MOD_MESSAGES_FORM.hidden}
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
	        <h4 class="modal-title">{translateToken value='Send Message'}</h4>
	      </div>
	      <div class="modal-body">
	        <div class="content">
               <div class="alert alert-danger display-hide">
                  <button class="close" data-dismiss="alert"></button>
                  {translateToken value='You have some form errors. Please check below.'}
               </div>
				<div class="form-group">
					<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
					<label class="control-label visible-ie8 visible-ie9">{$T_MOD_MESSAGES_FORM.subject.label}</label>
					<input class="{$T_MOD_MESSAGES_FORM.subject.class}" type="{$T_MOD_MESSAGES_FORM.subject.type}" autocomplete="off" placeholder="{$T_MOD_MESSAGES_FORM.subject.label}" name="{$T_MOD_MESSAGES_FORM.subject.name}" id="{$T_MOD_MESSAGES_FORM.subject.name}"/>
				</div>
				<div class="form-group">
					<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
					<label class="control-label visible-ie8 visible-ie9">{$T_MOD_MESSAGES_FORM.body.label}</label>
					<textarea class="{$T_MOD_MESSAGES_FORM.body.class}" id="{$T_MOD_MESSAGES_FORM.body.name}" name="{$T_MOD_MESSAGES_FORM.body.name}" rows="6" placeholder="{$T_MOD_MESSAGES_FORM.body.label}" ></textarea>
				</div>
				{foreach $T_MOD_MESSAGES_FORM.attachment as $fileupload}
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
				{/foreach}
			<!-- END FORM -->        
			</div>
			<!-- BEGIN COPYRIGHT -->

			<!-- END COPYRIGHT -->
	      </div>
	      <div class="modal-footer">
	        <button type="submit" class="btn blue">{translateToken value='Send'}</button>
	        <button type="button" class="btn default" data-dismiss="modal">{translateToken value='Close'}</button>
			<div class="copyright pull-left">
				&copy; 2014 WiseFlex
			</div>
	      </div>
	    </div>
	</form>
  <!-- /.modal-content -->
  </div>
{/block}