<!-- 
<script type="text/javascript" src="/js/jquery/jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="/js/jquery/jquery-ui-1.8.10.custom.min.js"></script>
-->
<!-- <link rel = "stylesheet" type = "text/css" href = "themes/SysClass/css/jquery-ui.css">  -->


<script>
	{if $T_REDIRECT_PARENT_TO}
		window.parent.eF_js_redrawPage(0, true);
		window.parent.eF_js_showDivPopup('{$smarty.const._NEWCOURSECLASS}');
	{/if}

	var slider_value = {$T_COURSE_FORM.max_users.value};
</script>

<script type="text/javascript" >
var slider_value = {$T_COURSE_FORM.max_users.value};

{literal}
$j(document).ready(function(jQuery) {
	jQuery("#max-users-slider").slider({
		range: "min",
		value: slider_value,
		min: 0,
		max: 150,
		slide: function( event, ui ) {
			jQuery("#max-users-text").html( ui.value );
			jQuery(":input[name='max_users']").val( ui.value );
		}
	});

	jQuery("#max-users-text").html( jQuery( "#max-users-slider" ).slider( "value" ) );
	jQuery(":input[name='max_users']").val( jQuery( "#max-users-slider" ).slider( "value" ) );
});
</script>

<style type="text/css">
fieldset.form-box {
    float: left;
    width: 100%;
    margin: 0;
    padding: 0;
    border: none;
}
fieldset.form-box .line {
    float: left;
    padding: 0;
    width: 100%;
}
fieldset.form-box .line label {
	color: #294A5F;
    float: left;
    font-size: 12px;
    font-weight: bold;
    line-height: 30px;
    margin: 0 10px 5px;
    width: 25%;
}
fieldset.form-box .buttons {
	margin-top : 5px;
	text-align: center;
}
fieldset.form-box .ui-slider{
    float: left;
    margin-top: 7px;
    padding-bottom: 0;
    padding-left: 5px;
    padding-right: 5px;
    padding-top: 0;
    width: 250px;
}
</style>

<style>
#max-users-text {
    float: left;
    font-weight: bold;
    margin-top: 7px;
    padding: 0 5px;
    width: 10%;
}


</style>
{/literal}
{$T_COURSE_FORM.javascript}
<form {$T_COURSE_FORM.attributes}>
	{$T_COURSE_FORM.hidden}
	<fieldset class="form-box">
		<div class="line"><label for="{$T_COURSE_FORM.name.name}">{$T_COURSE_FORM.name.label}:&nbsp;</label>{$T_COURSE_FORM.name.html}</div>
		<div class="line">
			<label for="{$T_COURSE_FORM.max_users.name}">{$T_COURSE_FORM.max_users.label}:&nbsp;</label>
			{$T_COURSE_FORM.max_users.html}
			<div id="max-users-slider"></div>
			<div id="max-users-text">{$T_COURSE_FORM.max_users.value}</div>
			
		</div>
		<div class="line"><label for="{$T_COURSE_FORM.active.name}">{$T_COURSE_FORM.active.label}:&nbsp;</label>{$T_COURSE_FORM.active.html}</div>
		<div class="line"><label for="{$T_COURSE_FORM.start_date.name}">{$T_COURSE_FORM.start_date.label}:&nbsp;</label>{$T_COURSE_FORM.start_date.html}</div>
		<div class="line"><label for="{$T_COURSE_FORM.end_date.name}">{$T_COURSE_FORM.end_date.label}:&nbsp;</label>{$T_COURSE_FORM.end_date.html}</div>

		<div class="line buttons">{$T_COURSE_FORM.submit_courseclass.html}</div>
	</fieldset>
</form>