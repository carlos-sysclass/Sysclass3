{assign var="notifications" value=$T_DATA.data}
{has_permission resource="Notification" action="dismiss" assign="dismissAllowed"}

<ul class="carroussel">
{foreach $notifications as $index => $note}
	<li data-entity-id="{$note.id}">
		<div class="alert alert-{$note.type} no-margin {if $dismissAllowed && $note.stick == 0}alert-dismissible{/if} notification-alert" role="alert">
		  {if $dismissAllowed && $note.stick == 0}
		  	<button type="button" class="btn btn-link btn-xs pull-right dismiss-action">
		  		<i class="fa fa-times"></i>
		  	</button>
		  {/if}
		  {$note.message}
		  
		  {if isset($note.link_href)}
		  <div class="pull-right">
		  	<a href="{$note.link_href}" target="_blank">
		  		{$note.link_text}
		  	</a>
		  </div>
		  {/if}
		  
		</div>
	</li>
{foreachelse}
	<li>
		<div class="alert alert-success no-margin" role="alert">{translateToken value="No new notifications."}</div>
	</li>
{/foreach}
</ul>

<script type="text/template" id="notification-lastest-empty">
	<li>
		<div class="alert alert-success no-margin" role="alert">{translateToken value="No new notifications."}</div>
	</li>
</script>