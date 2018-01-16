{extends file="layout/lock.tpl"}
{block name="content"}
  <div class="page-lock">
    <div class="page-logo">
      <a class="brand" href="index.html">
      <img src="{Plico_GetResource file='img/logo.png'}" alt="logo" />
      </a>
    </div>

    <div class="page-body">
      {if isset($T_MESSAGE) && $T_MESSAGE|@count > 0}
        <div class="alert alert-{$T_MESSAGE.type}">
          <button class="close" data-dismiss="alert"></button>
          <span>{$T_MESSAGE.message}</span>
        </div>
      {/if}
      {if ({$T_LOGGED_USER.avatars[0].url})}
        <img src="{$T_LOGGED_USER.avatars[0].url}" class="page-lock-img"alt="" />
      {else}
        <img src="{Plico_GetResource file='images/placeholder/avatar.png'}" class="page-lock-img"alt="" />
      {/if}

      <div class="page-lock-info">
        <h1>{$T_LOGGED_USER.name} {$T_LOGGED_USER.surname}</h1>
        <span class="email">{$T_LOGGED_USER.email}</span>
        <span class="locked">Locked</span>
        <!-- <form class="form-inline" action="index.html"> -->
            <form class="login-form" action="/login" method="post">
              <input type="hidden" name="login" value="{$T_LOGGED_USER.login}"/>

              <div class="input-group input-medium">
                <input type="password" id="password" name="password" placeholder="Password" autocomplete="off" class="form-control placeholder-no-fix">
                <span class="input-group-btn">
                <button type="submit" class="btn blue icn-only" name="submit_login" value="Click to access"><i class="m-icon-swapright m-icon-white"></i></button>
                </span>
              </div>

              <!-- /input-group -->
                <div class="relogin">
                <a href="/login">{translateToken value =  "Not"} {$T_LOGGED_USER.name} {$T_LOGGED_USER.surname} ?</a>
              </div>
            </form>
      </div>
    </div>
    <div class="page-footer">
      &copy; Copyright 2018 • WiseFlex Knowledge Systems LLC.
    </div>
  </div>
{/block}
