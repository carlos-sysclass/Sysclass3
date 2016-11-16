<div class="modal fade" id="dialogs-messages-send" tabindex="-1" role="basic" aria-hidden="true" data-animation="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        	<form id="form-role" role="form" class="form-validate" method="post" action="">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title event-title">{translateToken value="Send Message"}</h4>
                </div>
                <div class="modal-body">
                    <div class="form-body">
						<div class="form-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">{translateToken value="Send To"}</label>

                                        <select class="select2-me form-control input-block-level" name="group_id" data-placeholder="{translateToken value='Send To'}" multiple="multiple" data-format-attr="id">
                                            {foreach $T_RECEIVERS as $optgroup_id => $groups}
                                                <optgroup label="{$T_MESSAGE_GROUPS[$optgroup_id]}">
                                                {foreach $groups as $group}
                                                    <option value="{$group.id}">{$group.name}</option>
                                                {/foreach}
                                                </optgroup>
                                            {/foreach}
                                        </select>

                                        <select class="select2-me form-control input-block-level" name="user_id" data-placeholder="{translateToken value='Send To'}" multiple="multiple" data-format-attr="id">
                                            {foreach $T_USER_RECEIVERS as $optgroup_id => $user}
                                                <option value="{$user.id}">{$user.name} {$user.surname}</option>
                                            {/foreach}
                                        </select>

                                    </div>
                                </div>
                            </div>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label class="control-label">{translateToken value="Subject"}</label>
                                        <input class="form-control" type="text" autocomplete="off" placeholder="{translateToken value="Subject"}" name="subject" data-rule-required="true" data-rule-minlength="5" />				
                                    </div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
					                    <label class="control-label">{translateToken value="Message Body"}</label>
                                        <textarea class="wysihtml5 form-control placeholder-no-fix" id="body" name="body" rows="6" placeholder="{translateToken value="Message Body"}"  data-rule-required="true" data-rule-minlength="10" ></textarea>
					                </div>
								</div>
							</div>
                            <!--
                            <h5 class="form-section margin-bottom-10">{translateToken value="Attach Files"}</h5>
                            -->
						</div>
					</div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" type="submit">{translateToken value="Send"}</button>
                    <button type="button" class="btn default" data-dismiss="modal">{translateToken value="Close"}</button>
                </div>
            </form>
        </div>
    </div>
</div>
