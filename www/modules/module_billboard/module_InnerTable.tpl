{* template functions for inner table *}
{capture name = 't_inner_table_code}
    <table>
        <tr><td>
        	<div class="module_billboard_item">
        		{$T_BILLBOARD_INNERTABLE}
        	</div>
        </td></tr>
    </table>
{/capture}
{literal}
<style type="text/css">
.module_billboard_item {
	color: #000000;
	background-color: #ffffff;

	text-align: justify;
}
.module_billboard_item ol {
	margin: 0;
	padding: 0;
	list-style-type: disc;
}
.module_billboard_item p {
	margin: 0;
	line-height: 1.15;
	text-indent: 0pt;
	padding-bottom: 10.0pt
}
.module_billboard_item .bolder {
	font-weight: bold
}
.module_billboard_item ol li {
	padding-left: 0pt;
	line-height: 1.15;
	margin-left: 36.0pt;
	padding-bottom: 10.0pt
}
.module_billboard_item object {
	text-align: center;
}
</style>
{/literal}
{eF_template_printBlock title = $smarty.const._BILLBOARD_BILLBOARDPAGE data = $smarty.capture.t_inner_table_code image = $T_BILLBOARD_MODULE_BASELINK|cat:'images/note_pinned32.png' absoluteImagePath=1 options = $T_BILLBOARD_INNERTABLE_OPTIONS}
