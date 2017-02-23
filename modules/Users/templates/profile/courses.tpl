<div class="row">
	<div class="col-md-12">
		<div class="add-portfolio">
			<span>{translateToken value="New courses arriving this month"}</span>
			<a href="#" class="btn icn-only green">{translateToken value="Enroll a new course"} <i class="m-icon-swapright m-icon-white"></i></a>
		</div>
	</div>
</div>
<!--end add-portfolio-->
{foreach $T_USER_COURSES as $id => $course}
<div class="row portfolio-block">
	<div class="col-md-5">
		<div class="portfolio-text">
			{if $course.image}
			<img src="{Plico_RelativePath file=$course.image}" alt="" />
			{/if}
			<div class="portfolio-text-info">
				<h4>{$course.name}</h4>
				<p>{$course.description}</p>
			</div>
		</div>
	</div>
	<div class="col-md-7">
		<div class="portfolio-info">
			Enroll Date
			<span>{formatTimestamp value=$course.active_in_course fmt=$T_SETTINGS_.php_date_fmt}</span>
		</div>
		<div class="portfolio-info">
			Total Units
			<span>{$course.num_units}</span>
		</div>
		<div class="portfolio-info">
			Score
			<span>{$course.score}</span>
		</div>
	</div>
	<!--
	<div class="col-md-2">
		<div class="portfolio-btn">
			<a href="#" class="btn bigicn-only"><span>Manage</span></a>
		</div>
	</div>
	-->
</div>
{/foreach}
<!--end row-->
<!--end row-->
