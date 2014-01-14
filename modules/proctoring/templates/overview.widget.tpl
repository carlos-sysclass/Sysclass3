{if $T_DATA.data.polo|@count > 0}
<div class="row">
	<div class="col-md-12">
		<p>
			<span>Proctoring Center:</span>
			<strong class="text-default pull-right">{$T_DATA.data.polo['nome']}</strong>
		</p>
		<p>
			<span>Phone:</span>
			<strong class="text-default pull-right">{$T_DATA.data.polo['telefone']}</strong>
		</p>
		<p>
			<a href="#" target="_blank">View map</a>
		</p>
		<!-- GET THIS DATA FROM SCHEDULE/CALENDAR MODULE -->
		<p>
			<dt>Next Exam:</dt>
			<dd>Dez, 22 at 7:35PM Central Time</dd>
		</p>
		<a class="btn btn-sm btn-success btn-disabled" href="javascript:void(0);">Confirm</a>	
		<a class="btn btn-sm btn-warning pull-right btn-disabled" href="javascript:void(0);">Re-Schedule</a>	
	</div>
</div>
{/if}