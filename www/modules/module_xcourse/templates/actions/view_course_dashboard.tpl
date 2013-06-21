{capture name="t_course_dashboard"}
	<div class="block-links grid_16">
	{foreach key = 'key' item = 'feature' from = $T_XCOURSE_LIST_FEATURES}
		{assign var="feature_name" value="t_xcourses_feature_list_$key"}
			{if isset($feature.title)}
				<div class="grid_2 {cycle values="prefix_1,"}" >
					<a title="{$feature.title}" class="clearfix feature_{$key}" href="{$feature.link}" >
						<img align="middle" class="{$feature.image_class}" src="{$feature.image}">
						<br>{$feature.title}
					</a>
				</div>
			{elseif isset($feature.template)}
				{include file=$feature.template}
			{else}
				<div class="grid_2 {cycle values=",prefix_1"}" >
					<a class="clearfix sda" href="javascript: void(0);">
						<br>{$feature}
					</a>
				</div>
			{/if}
	{/foreach}
	</div>
	<hr />
	{foreach key = 'key' item = 'feature' from = $T_XCOURSE_LIST_FEATURES}
		<div class="loader_container grid_16" id="loader_feature_{$key}"></div>
	{/foreach}
	<div id="_XCOURSE_LOADING_OUTER">
		<div id="_XCOURSE_LOADING">
			<div>{$smarty.const.__LOADING_MESSAGE}</div>
		</div>
	</div>
{/capture}

{sC_template_printBlock
	title 			= $T_XCOURSE_BLOCK_TITLE
	data			= $smarty.capture.t_course_dashboard
	contentclass	= "block"
	class			= ""
}