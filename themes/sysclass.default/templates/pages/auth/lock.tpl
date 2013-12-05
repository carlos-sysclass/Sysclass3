{extends file="layout/lock.tpl"}
{block name="content"}
  <div class="page-lock">
    <div class="page-logo">
      <a class="brand" href="index.html">
      <img src="{Plico_GetResource file='img/logo-login.png'}" alt="logo" />
      </a>
    </div>

    <div class="page-body">
      {if isset($T_MESSAGE) && $T_MESSAGE|@count > 0} 
        <div class="alert alert-{$T_MESSAGE.type}">
          <button class="close" data-dismiss="alert"></button>
          <span>{$T_MESSAGE.message}</span>
        </div>
      {/if}
      <img class="page-lock-img" src="{Plico_RelativePath file=$T_BIG_USER_AVATAR.avatar}" width="{$T_BIG_USER_AVATAR.width}" alt="">
      <div class="page-lock-info">
        <h1>{$T_CURRENT_USER->user.name} {$T_CURRENT_USER->user.surname}</h1>
        <span class="email">{$T_CURRENT_USER->user.email}</span>
        <span class="locked">Locked</span>
        <!-- <form class="form-inline" action="index.html"> -->
        {$T_LOGIN_FORM.javascript}
            <form {$T_LOGIN_FORM.attributes}>
              {$T_LOGIN_FORM.hidden}
              <input type="hidden" name="{$T_LOGIN_FORM.login.name}" value="{$T_CURRENT_USER->user.login}"/>

              <div class="input-group input-medium">
                <input type="{$T_LOGIN_FORM.password.type}" class="form-control" placeholder="{$T_LOGIN_FORM.password.label}" name="{$T_LOGIN_FORM.password.name}" id="{$T_LOGIN_FORM.password.name}" autocomplete="off">
                <span class="input-group-btn">        
                <button type="submit" class="btn blue icn-only" name="{$T_LOGIN_FORM.submit_login.name}" value="{$T_LOGIN_FORM.submit_login.value}"><i class="m-icon-swapright m-icon-white"></i></button>
                </span>
              </div>
          <!-- /input-group -->
              <div class="relogin">
                <a href="/login">{translateToken value='Not'} {$T_CURRENT_USER->user.name} {$T_CURRENT_USER->user.surname} ?</a>
              </div>
            </form>

      </div>
    </div>
    <div class="page-footer">
      &copy; 2014 WiseFlex
    </div>
  </div>
{/block}