<form role="form" action="#">
	<div class="form-body">

		<div class="form-group">
			<label class="control-label">First Name</label>
			<input type="text" placeholder="John" class="form-control" />
		</div>

		<div class="form-group">
			<label class="control-label">Last Name</label>
			<input type="text" placeholder="Doe" class="form-control" />
		</div>
		<div class="form-group">
			<label>Email Address</label>
			<div class="input-group">
				<span class="input-group-addon"><i class="icon-envelope"></i></span>
				<input type="text" placeholder="Email Address" class="form-control">
			</div>
		</div>
		<div class="form-group">
			<label class="control-label">Birthday</label>
			<div class="input-group">                                       
				<span class="input-group-addon"><i class="icon-calendar"></i></span>
				<input type="text" readonly class="form-control datepick" data-format="mm/dd/yyyy" data-date-view-mode="years">
			</div>
		</div>



		<div class="form-group">
			<label class="control-label">Language</label>
			<select name="language" class="form-control select2-me" data-placeholder="Select...">
			</select>
		</div>
		<div class="form-group">
			<label class="control-label">Fuso horário</label>
			<select name="language" class="form-control select2-me" data-placeholder="Select...">
			</select>
		</div>
	</div>
</form>