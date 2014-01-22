{if $T_DATA.data.polo|@count > 0}
<div class="row">
	<div class="col-md-6">
		<img class="img-responsive" alt="" src="{Plico_GetResource file='img/logo-institution.png'}" />
		<br />
		<p class="text-center">
			<a href="http://www.ult.edu.br" target="_blank">www.ult.edu.br</a>
		</p>
		<p  class="text-center">
			<a href="#" target="_blank">View map</a>
		</p>
    </div>    
	<div class="col-md-6">
		<p>
			<strong class="text-default pull-right">{$T_DATA.data.polo['nome']}</strong>
		</p>
		<p>
			<strong class="text-default pull-right">{$T_DATA.data.polo['telefone']}</strong>
		</p>
	</div>
</div>
<hr />
<div class="row">
	<div class="col-md-12">
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