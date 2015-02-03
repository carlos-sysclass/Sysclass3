<table class="sortedTable" style="width:100%" >
	<thead>
		<tr>
			<th>Nome</th>
			<th>Tipo</th>
			<th>Login</th>
			<th>Classe</th>
			<th>Curso</th>
			<th>Lição</th>
			<th>Endereço</th>
			<th>Bairro</th>
			<th>Cidade</th>
			<th>Estado</th>
		</tr>
	</thead>
	<tbody>
		{if $T_XENROLLMENT_USERSINFO}
			{foreach name='enrollment_list' key='key' item='enroll' from=$T_XENROLLMENT_USERSINFO}
			<tr>
				<td>{$enroll.name} {$enroll.surname}</td>
				<td>{$enroll.user_type}</td>
				<td>{$enroll.login}</td>
				<td>{$enroll.class}</td>
				<td>{$enroll.course}</td>
				<td>{$enroll.lesson}</td>
				<td>{$enroll.address}</td>
				<td>{$enroll.neighborhood}</td>
				<td>{$enroll.city}</td>
				<td>{$enroll.state}</td>
			</tr>
			{/foreach}
		{else}
			<tr>
				<td colspan="10" style="text-align:center">Nenhum registro encontrado</td>
			</tr>
		{/if}
	</tbody>
</table>