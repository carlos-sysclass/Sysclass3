<ul class="xwebtutoria-list">
	{foreach key="item_id" item="tutoria" from=$T_XWEBTUTORIA_LIST}
		<li class="lista">
			{include 
				file="$T_XWEBTUTORIA_BASEDIR/templates/includes/xwebtutoria.show_item.tpl"
				T_TUTORIA=$tutoria
			}
		</li>
	{/foreach}
</ul>
<div class="clear"></div>