{if $T_DATA.data|@count > 0}
<div class="list-group message-recipient-group">
  {foreach $T_DATA.data as $item}
  <a class="list-group-item message-recipient-item" href="{$item.link}" data-target="#message-contact-dialog">
    {if isset($item.icon)}
    <span class="text-{$item.color}"><i class="icon-{$item.icon}"></i></span>
    {/if}
    {$item.text}

  </a>
  {/foreach}
</div>
{/if}


<!--
  <div class="modal-dialog modal-wide">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title">{translateToken value='Send Message'}</h4>
      </div>
      <div class="modal-body">
        <img src="{Plico_GetResource file='img/ajax-modal-loading.gif'}" alt="" class="loading">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn blue">{translateToken value='Send'}</button>
        <button type="button" class="btn default" data-dismiss="modal">{translateToken value='Close'}</button>
      </div>
    </div>
  </div>
</div>
-->
