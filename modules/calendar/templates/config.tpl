{extends file="layout/default.tpl"}
{block name="content"}
<div class="row" id="calendar-container">
    <div class="col-md-3 col-sm-12">
        <!-- BEGIN DRAGGABLE EVENTS PORTLET-->
        <h3 class="event-form-title">Draggable Events</h3>
        <div id="external-events">
            <form class="inline-form">
                <input type="text" value="" class="form-control" placeholder="Event Title..." id="event_title"/><br/>
                <a href="javascript:;" id="event-add-action" class="btn default">
                Add Event </a>
            </form>
            <hr/>
            <div id="event_box"></div>
            <label for="drop-remove">
            <input type="checkbox" id="drop-remove"/>remove after drop </label>
            <hr class="visible-xs"/>
        </div>
        <!-- END DRAGGABLE EVENTS PORTLET-->
    </div>
    <div class="col-md-9 col-sm-12">
        <div id="calendar"></div>
    </div>
</div>

{/block}
