<div id="lessons-dialog-auto_translate" class="modal fade" role="dialog" aria-labelledby="{translateToken value='Lesson Exercises'}" aria-hidden="true">
    <div class="modal-dialog modal-wide">
        <div class="modal-content">
			<form role="form" class="form-validate" method="post" action="{$T_FORM_ACTION}">
	            <div class="modal-header">
	                <h4 class="modal-title">
	                	{translateToken value="Translate"}
	                	<!-- <small>{translateToken value="Translate"}</small> -->
	                </h4>
	            </div>
	            <div class="modal-body">
	            	<div class="form-group">
	            		<label class="control-label">{translateToken value="Translate to"}</label> 
	            	
					    <select class="select2-me form-control" name="locale_code" data-placeholder="{translateToken value="Language"}" style="max-width: 150px;" data-format-as="country">
					        <option></option>
					        {foreach $T_LANGUAGES as $lang}
					            <option value="{$lang.locale_code}" data-country="{$lang.country_code}">{$lang.local_name}</option>
					        {/foreach}
					    </select>
				    </div>
	            </div>
	            <div class="modal-footer">
					<button class="btn btn-success save-action" type="button">{translateToken value="Translate"}</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">{translateToken value="Cancel"}</button>
	            </div>
	        </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!--
<script type="text/template" id="tab_lesson_exercises-details-template">
	
</script>
-->Ç