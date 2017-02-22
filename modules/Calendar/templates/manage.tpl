{extends file="layout/default.tpl"}
{block name="content"}
<div class="row margin-top-20" id="calendar-container">
    <!--
    <div class="col-md-3 col-sm-12">
        <h3 class="event-form-title">Draggable Events</h3>
        <div id="external-events">
            <form class="inline-form">
                <div class="form-group">
                    <label class="control-label">{translateToken value="Name"}</label>
                    <input name="title" type="text" value="" class="form-control" placeholder="Event Title..." id="event_title"/>
                </div>
                <div class="form-group">
                    <label class="control-label">{translateToken value="Type"}</label>
                    <select class="select2-me form-control" id="calendar-event-source-combo" name="type_id" data-rule-required="1" data-rule-min="1"  data-format-as="color-list">

                    </select>
                </div>
                <div class="form-group">
                    <a href="javascript:;" id="event-add-action" class="btn default">Add Event </a>
                </div>
            </form>
            <hr/>
            <div id="event_box"></div>
            <label for="drop-remove">
            <input type="checkbox" id="drop-remove"/>remove after drop </label>
            <hr class="visible-xs"/>
        </div>
    </div>
    -->
    <div class="col-md-12">
        <div class="portlet box dark-blue calendar" data-portlet-id="calendar-widget" data-portlet-type="calendar">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-calendar"></i>
                </div>
            </div>
            <div class="portlet-body">
                <div id="calendar"></div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="calendar-create-dialog" tabindex="-1" role="basic" aria-hidden="true" data-animation="false">
        <div class="modal-dialog modal-wide">
            <div class="modal-content">
                <form id="form-calendar-event-creation" role="form" class="form-validate">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        <h4 class="modal-title event-title">{translateToken value="Event Creation"}</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-body">
                            <div class="form-group">
                                <label class="control-label">{translateToken value="Type"}</label>
                                <select class="select2-me form-control" name="source_id" data-rule-required="1"  data-format-as="color-list">
                                    <option value="">{translateToken value="Please Select"}</option>
                                    {foreach $T_EVENT_SOURCES as $evt}
                                        <option value="{$evt.id}" data-class="{$evt.class_name}">{$evt.name}</option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label">{translateToken value="Name"}</label>
                                <input name="title" value="" type="text" placeholder="Name" class="form-control" data-rule-required="true" data-rule-minlength="3" />
                            </div>
                            <div class="form-group">
                                <label class="control-label">{translateToken value="Description"}</label>
                                <textarea class="wysihtml5 form-control placeholder-no-fix" id="description" name="description" rows="6" placeholder="{translateToken value="Put your description here..."}" data-rule-required="true"></textarea>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">

                                    <label class="control-label">{translateToken value="Start Date"}</label>
                                    <input class="form-control input-small date-picker"  size="16" type="text" name="start" data-update="start"  data-format="date" data-format-from="unix-timestamp" data-rule-required="true" />
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="control-label">{translateToken value="End Date"}</label>
                                    <input class="form-control input-small date-picker"  size="16" type="text" name="end" data-update="end"  data-format="date" data-format-from="unix-timestamp" data-rule-required="true" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success" type="submit">{translateToken value="Create Event"}</button>
                        <button type="button" class="btn default" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
    <!-- /.modal-create-dialog -->
    </div>
    <!-- TODO: CREATE A DIALOG TO MANAGE THE USER CALENDARS -->
    <!--
    <div class="modal fade" id="calendar-event-source" tabindex="-1" role="basic" aria-hidden="true" data-animation="false">
        <div class="modal-dialog modal-wide">
            <div class="modal-content">
                <form id="form-calendar-event-creation" role="form" class="form-validate">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        <h4 class="modal-title event-title">{translateToken value="Event Creation"}</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="backgrid-table">
                                        <table class="table table-striped table-bordered table-hover table-full-width data-table" id="view-{$T_MODULE_ID}">
                                            <thead>
                                                <tr>
                                                    <th align="center">Avaliable event sources</th>
                                                </tr>
                                            </thead>
                                                {foreach $T_EVENT_SOURCES as $evt}
                                                <tr>
                                                    <td align="center"><div class="label ladel-default {$evt.class_name}">{$evt.name}</div></td>
                                                </tr>
                                                {/foreach}
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-8">

                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success" type="submit">{translateToken value="Create Event"}</button>
                        <button type="button" class="btn default" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    -->
</div>

{/block}
