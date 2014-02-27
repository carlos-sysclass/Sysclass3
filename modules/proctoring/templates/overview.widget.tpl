{if $T_DATA.data.polo|@count > 0}
<div class="row">
	<div class="col-md-7 text-center img-vertical-middle">
		<img class="" alt="" src="{Plico_GetResource file='img/logo-polo.png'}" />
    </div>    
	<div class="col-md-5">
		<div class="btn-group-vertical btn-group-fixed-size">
			<a href="javascript: void(0);" class="btn btn-link btn-sm disabled">
				<span class="text-muted"><i class="icon-link"></i>Website</span>
			</a>
			<a href="javascript: void(0);" class="btn btn-link btn-sm disabled">
				<span class="text-muted"><i class="icon-map-marker"></i>View Map</span>
			</a>
			<a href="javascript: void(0);" class="btn btn-link btn-sm disabled">
				<span class="text-muted"><i class="icon-envelope"></i>Contact</span>
			</a>
		</div>
	</div>
</div>
<hr />
<div class="row">
	<div class="col-md-7">
		<!-- GET THIS DATA FROM SCHEDULE/CALENDAR MODULE -->
		<dt class="text-muted">Next Exam:</dt>
		<dd class="text-muted">Dez, 22nd (8:00am - 10:00am)</dd>
	</div>
	<div class="col-md-5">
		<div class="btn-group-vertical btn-group-fixed-size pull-right">
			<a href="javascript: void(0);" class="btn btn-success btn-sm disabled">
				<span><i class="icon-ok-sign"></i>Confirm</span>
			</a>
			<a href="javascript: void(0);" class="btn btn-warning btn-sm disabled">
				<span><i class="icon-remove-sign"></i>Re-Schedule</span>
			</a>
		</div>
	</div>
</div>
{/if}