<div>
  <div class="row">
    <div class="col-sm-12 col-md-12">
      <div class="col-md-12 no-padding inter-navsuper">
        <ul class="nav nav-tabs col-md-8 no-padding widget-tabs" role="tablist">
          <li role="presentation" class="active">
            <a href="#tab_messages_messages" aria-controls="tab_program_description" role="tab" data-toggle="tab">
              <i class="fa fa-inbox"></i>
              <span class="progress-indicator messages-indicator">
                <span class="counter"></span>
                <span class="singular">{translateToken value="Message"}</span>
                <span class="plural">{translateToken value="Messages"}</span>
              </span>
              
            </a>
          </li>
          <!--
          <li role="presentation">
            <a href="#tab_messages_forum" aria-controls="tab_program_courses" role="tab" data-toggle="tab">
              <i class="fa fa-commenting"></i>
              <span class="progress-indicator forum-indicator">
                <span class="counter"></span>
                <span class="singular">{translateToken value="Forum"}</span>
                <span class="plural">{translateToken value="Forum"}</span>
              </span>
            </a>
          </li>
          <li role="presentation">
          	<a href="#tab_messages_faq" aria-controls="tab_course_units" role="tab" data-toggle="tab">
              <i class="fa fa-question-circle-circle"></i>
              <span class="progress-indicator faq-indicator">
                <span class="counter"></span>
                <span class="singular">{translateToken value="FAQ"}</span>
                <span class="plural">{translateToken value="FAQ"}</span>
              </span>
            </a>
          </li>
          -->
        </ul>
        <ul class="dir-menu-bar">
          <li class="dropdown">
            <a href="javascript: void(0);" data-close-others="true" data-toggle="dropdown" class="dropdown-toggle">
              <i class="fa fa-envelope" aria-hidden="true"></i>
            </a>
            <ul class="dropdown-menu pull-right">
              <li>
                <a href="javascript: void(0);" class="dialogs-messages-send-action" data-mode="user">
                  <i class="fa fa-envelope" aria-hidden="true"></i>
                  {translateToken value="Send e-mail"}
                </a>              
              </li>
              <li class="divider"></li>
              {foreach $T_MESSAGES_GROUP_RECEIVERS as $receiver}
              <li>
                <a href="javascript: void(0);" class="dialogs-messages-send-action" data-mode="group" data-group-id="{$receiver.id}">
                  <i class="fa {$receiver.image} fa-{$receiver.image}" aria-hidden="true"></i>
                  {$receiver.name}
                </a>              
              </li>
              {/foreach}
            </ul>
          </li>
          <li>
            <a href="javascript: void(0);" class="dialogs-messages-search-action tooltips" data-original-title="{translateToken value='Search'}">
              <i class="fa fa-search" aria-hidden="true"></i>
            </a>
            <div class="search-container">
              <input class="form-control" name="_search" />
            </div>
          </li>
        </ul>
      </div>
      <!-- Tab panes -->
    </div>
    <div class="col-sm-12 col-md-12 inter-navsuper-tabs">
      <div class="tab-content tab-content-messages">
        <div role="tabpanel" class="tab-pane active" id="tab_messages_messages">
          <div class="row">
            <div class="col-md-12">
              {include "`$smarty.current_dir`/../blocks/widget-table.tpl" T_MODULE_CONTEXT=$T_MESSAGES_BLOCK_CONTEXT.messages T_MODULE_ID="messages"}

              
            </div>
          </div>
        </div>
        <div id="message-body-container">
          <div class="modal-header">
              <button type="button" class="btn btn-xs pull-right btn-link reply-action tooltips" aria-label="{translateToken value="Forward"}" data-original-title="{translateToken value='Forward'}">
                  <i class="fa fa-mail-forward"></i>
              </button>
              <button type="button" class="btn btn-xs pull-right btn-link reply-action tooltips" aria-label="{translateToken value="Reply"}" data-original-title="{translateToken value='Reply'}">
                  <i class="fa fa-mail-reply"></i>
              </button>
              <button type="button" class="btn btn-xs pull-right btn-danger close-action tooltips" aria-label="{translateToken value="Back"}" data-original-title="{translateToken value='Back'}">
                  <i class="fa fa-arrow-left"></i>
              </button>
              <h4 class="modal-title">
                  <span data-update="subject">dsadas</span>
              </h4>
          </div>
          <div class="modal-header">
            <div>
              <strong>From:</strong>
              <span data-update="from.name">asdasd</span> <span data-update="from.surname"></span> &lt;<span data-update="from.email"></span>&gt;
            </div>
            <div>
              <strong>Date:</strong>
              <span data-update="timestamp" data-format-from="unix-timestamp" data-format="datetime">asdasdas</span>
            </div>
          </div>
          <div class="modal-body">
            <div data-update="body">bodfy</div>
          </div>
        </div>
        <!--
        <div role="tabpanel" class="tab-pane" id="tab_messages_forum">
        </div>
        <div role="tabpanel" class="tab-pane" id="tab_messages_faq">
        </div>
        -->
      </div>
    </div>
  </div>
</div>