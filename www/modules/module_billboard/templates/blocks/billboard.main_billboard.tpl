{if $T_BILLBOARD_DATA|@count > 1}
<!-- 
	<ul class="default-list">
		<li>
			<span style="float:left;"><a href="javascript: void(0);" class="billboard-main-previous">{$smarty.const.__PREVIOUS}</a></span>
			<span style="float:right;"><a href="javascript: void(0);" class="billboard-main-next">{$smarty.const.__NEXT}</a></span>
			<div style="text-align: center">&nbsp;</div>
		</li>
	</ul>
-->
{/if}
<ul id="billboard-main-list">
	{foreach key="index" item="billboard" from=$T_BILLBOARD_DATA}
		<li class="course_{$billboard.course_id}">
			<ul class="default-list">
				<li>
					{$billboard.data}
				</li>
			</ul>
			<!-- 
			<div class="blockFooter">
				<span class="to-left">
					<img 
						src = "images/others/transparent.gif"
						class="sprite16 sprite16-calendar3"
						border = "0"/>
						&nbsp;
						<span>#filter:ext-date-{$T_BILLBOARD_DATA.data_registro}#</span>
				
				</span>
				<span class="to-right">
					<img 
						src = "images/others/transparent.gif"
						class="sprite16 sprite16-n_pointer"
						border = "0"/>
					<a title="__BILLBOARD_KNOWN_MORE" href = "{$T_BILLBOARD_BASEURL}">
						<span>{$smarty.const.__BILLBOARD_KNOWN_MORE}</span>
					</a>
				</span>
			</div>
			 -->
		</li>
	{/foreach}
</ul>



