{capture name="t_instances_list"}
<table class="style1" style="width: 100%">
	<thead>
		<tr>
			<th style="text-align: center;">Id</th>
			<th>Nome</th>
			<th style="text-align: center;">Estado</th>
			<th style="text-align: center;">Endereço IP</th>
			<th style="text-align: center;">Arquitetura</th>
			<th style="text-align: center;">Opções</th>
		</tr>
	</thead>
	<tbody>
		{foreach item="instance" from=$T_AWS_INSTANCES}
		<tr>
		 	<td style="text-align: center;">{$instance.instanceId}</td>
		 	<td>{$instance.tagSet.Name}</td>
		 	<td style="text-align: center;">{$instance.instanceState.code} - {$instance.instanceState.name}</td>
		 	<td style="text-align: center;">{$instance.ipAddress}</td>
		 	<td style="text-align: center;">{$instance.architecture}</td>
		 	<td style="text-align: center;">
		 		{if $instance.instanceState.code == 80}
		 			<a href="{$T_AWS_BASEURL}&action=start_instance&instance_id={$instance.instanceId}">Iniciar</a>
		 		{elseif $instance.instanceState.code == 16}
		 			<a href="{$T_AWS_BASEURL}&action=stop_instance&instance_id={$instance.instanceId}">Parar</a>
		 		{/if}
		 	</td>
		</tr>
		{/foreach}
	</tbody>
</table>
{/capture}
{eF_template_printBlock
	title 			= $smarty.const.__AWS_INSTANCE_LIST
	data			= $smarty.capture.t_instances_list
	class			= "block"
	contentclass	= "blockContents"
} 