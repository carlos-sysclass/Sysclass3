<h3>{$smarty.const.__XPROJECTS_COORDENATOR_MEMBERS}</h3>
<ul class="default-list">
{foreach item="member" from=$T_XPROJECTS_MEMBERS}
	{if $member.user_type == 'professor'} 
		<li><a href="javascript: void(0);">{$member.username}:</a></li>
	{/if}
{/foreach}
</ul>

<h3>{$smarty.const.__XPROJECTS_STUDENT_MEMBERS}</h3>
<ul class="default-list">
{foreach item="member" from=$T_XPROJECTS_MEMBERS}
	{if $member.user_type == 'student'} 
		<li><a href="javascript: void(0);">{$member.username}:</a></li>
	{/if}
{/foreach}
</ul>