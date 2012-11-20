



{capture name = 't_gradebook_professor_code'}

<table class="style1">
	<thead>
		<tr>
			<th>{$smarty.const._GRADEBOOK_LESSON_NAME}</th>
			<th>{$smarty.const.__GRADEBOOK_GROUP_SCORE}</th>
			<th>{$smarty.const.__GRADEBOOK_FINAL_SCORE}</th>
			<th>{$smarty.const._GRADEBOOK_PUBLISH}</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>
{/capture}

{eF_template_printBlock title=$smarty.const._GRADEBOOK_NAME data=$smarty.capture.t_gradebook_professor_code image=$T_GRADEBOOK_BASELINK|cat:'images/gradebook_logo.png' absoluteImagePath = 1}	