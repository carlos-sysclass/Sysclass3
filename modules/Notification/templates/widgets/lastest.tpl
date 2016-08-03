{assign var="notifications" value=$T_DATA.data}

{foreach $notifications as $note}
	<div class="alert alert-{$note.type}  {if $note.stick == 0}alert-dismissible"{/if} role="alert">
	  {if $note.stick == 0}
	  	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	  {/if}
	  {$note.message}
	  <!--
	  {if isset($note.link_href)}
	  <div class="pull-right">
	  	<a href="{$note.link_href}">
	  		{$note.link_text}
	  	</a>
	  </div>
	  {/if}
	  -->
	</div>
{/foreach}