<ul class="ver-inline-menu ver-inline-notabbable ver-inline-menu-noarrow">
    <li class="active block-title">
        <a>
            <i class="{$T_DATA.icon}"></i>
            {$T_DATA.header}
        </a>
    </li>
    <li class="active chat-loader" style="display: none;">
        <a>
            <i class="fa">
                <span class="fa fa-circle-o-notch fa-lg fa-spin"></span>
            </i>
            Connecting
        </a>
    </li>

</ul>
<div class="panel-body">
    <div class="row">
        <div class="col-md-4 col-sm-5 col-xs-4  text-center">
            <img class="avatar img-responsive" alt="" src="{Plico_GetResource file='img/avatar_chat.jpg'}" style="width: 100%;" />
        </div>
        <div class="col-md-8 col-sm-7 col-xs-8">
            <p class="text-muted  text-right">
                <span class="pull-left hidden-xs">{translateToken value="Attendee"}:</span>
                <strong class="text-default">Suzan Smith</strong>
            </p>
            <p class="text-muted  text-right">
                <span class="pull-left hidden-xs">{translateToken value="Local Time"}:</span>
                <strong class="text-default">GMT +3</strong>
            </p>
            <p class="text-muted  text-right">
                <span class="pull-left hidden-xs">{translateToken value="Language"}:</span>
                <strong class="text-default">English</strong>
            </p>
        </div>

    </div>
    <hr />

    <div class="row" id="chat-action-container">
        <div class="col-md-12">
            <div class="text-center">
                <a href="javascript: void(0);" class="btn btn-success start-chat-action">
                    <span><i class="icon-ok-sign"></i> {translateToken value="Start Chat"}</span>
                </a>
            </div>
        </div>
    </div>
</div>



