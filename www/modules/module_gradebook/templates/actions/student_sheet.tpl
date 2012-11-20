{capture name = 't_gradebook_professor_code'}
	<div class="course-lesson-autocomplete-container">
	    <label>Selecione uma disciplina:</label>
	    <input class="course-lesson-autocomplete" value="" />
	</div>
	<div id="gradebook-student-lesson-sheet"></div>
{/capture}
{eF_template_printBlock title=$smarty.const._GRADEBOOK_NAME data=$smarty.capture.t_gradebook_professor_code image=$T_GRADEBOOK_BASELINK|cat:'images/gradebook_logo.png' absoluteImagePath = 1}	