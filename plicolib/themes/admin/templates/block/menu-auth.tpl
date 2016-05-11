

	<div id="menu" class="hidden-phone">
		
		<span class="profile"> 
			<a class="img" href="javascript: void(0);">
					<img src="http://www.placehold.it/74x74/232323&amp;text=SSRA" alt="{$T_USER['nome']}" />
			</a>
			<span>
				<strong>{$T_USER['nome']}</strong> 
				<!-- <a href="<?php echo getURL(array('my_account')); ?>">edit account</a> -->
			</span>
		</span>
		<div class="separator bottom"></div>
		<!--
		<div id="search">
			<input type="text" placeholder="Quick search ..." />
			<button class="glyphicons search">
				<i></i>
			</button>
		</div>
		-->
		<ul>

			{foreach item="item" from=$T_MENU}
				{include file="block/menu-item-auth.tpl" T_ITEM=$item}
			{/foreach}
			{foreach item="item" from=$T_LEFTBAR_MENU}
				{include file="block/leftbar-item-auth.tpl" T_ITEM=$item}
			{/foreach}
		</ul>
		<div class="clearfix" style="clear: both"></div>
	</div>
	