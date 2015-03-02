<div id="permission-block">
    <!-- INJECT A TABLE WITHIN TEMPLATES FOR PERMISSION MANAGEMENT -->
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label">{translateToken value="Can messages be sent to this group?"}</label>
                <select class="select2-me form-control" name="behaviour_allow_messages" data-rule-required="true">
                    <option value="0">{translateToken value="No"}</option>
                    {foreach $T_BLOCK_MESSAGES_GROUPS as $key => $value}
                        <option value="{$value.id}">{translateToken value="Yes! In Message Group"} "{$value.name}"</option>
                    {/foreach}
                </select>
            </div>
        </div>
    </div>
</div>
