{extends file="layout/default.tpl"}
{block name="content"}
<div class="row">
      <div class="col-md-12 page-{$T_ERROR_CLASS}">
         <div class="number">
            {$T_ERROR}
         </div>
         <div class="details">
            <h3>{$T_ERROR_TITLE}</h3>
            <p>
               {$T_ERROR_MESSAGE}<br>
               <a href="/dashboard">{translateToken value='Return home'}</a> {translateToken value='or try the search bar below.'}
            </p>
            <form action="#">
               <div class="input-group input-medium">
                  <input type="text" placeholder="{translateToken value='keyword...'}" class="form-control">
                  <span class="input-group-btn">                   
                  <button class="btn blue" type="submit"><i class="icon-search"></i></button>
                  </span>
               </div>
               <!-- /input-group -->
            </form>
         </div>
      </div>
   </div>
{/block}