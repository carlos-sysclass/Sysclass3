<h3>Grupo {$T_XPROJECTS_TOPIC.id} - {$T_XPROJECTS_TOPIC.title}</h3>

<p>{$smarty.const.__XPROJECTS_YOUR_ACCESS_INFO}</p>

<h3>{$smarty.const.__XPROJECTS_YOUR_ACESS_DATA}</h3>
{foreach item="tag_data" from=$T_XPROJECTS_TOPIC.tag}
<p>
	<strong>{$tag_data.label}:</strong>
	{$tag_data.value}
</p>
{/foreach}