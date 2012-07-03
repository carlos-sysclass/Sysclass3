{if $T_USER_COURSE_PROGRESS}
	<ul id="xcourse-activity-list">
		{foreach name="course_iterator" key="index" item="course" from=$T_USER_COURSE_PROGRESS}
			{if $course->course.activities|@count > 0 }
				{foreach name="activity_iterator" key="index" item="current_activity" from=$course->course.activities}
				
					{if $smarty.foreach.course_iterator.first && $smarty.foreach.activity_iterator.first}
						{assign var="first_course_id" value="`$course->course.id`"}
						{assign var="first_lesson_id" value="`$current_activity.id`"}
					{/if}
					
				<li class="course_lesson_{$course->course.id}_{$current_activity.id}" id="course_lesson_{$course->course.id}_{$current_activity.id}">
				
					<div class="col2-3">
						{assign var="course_id" value="`$course->course.id`"}
						{assign var="lesson_id" value="`$current_activity.id`"}
						
						{if $T_CURRENT_UNITS[$course_id][$lesson_id] > 0}
                        	<a href="{$smarty.session.s_type}.php?ctg=content&view_unit={$T_CURRENT_UNITS[$course_id][$lesson_id]}" title="{$smarty.const._STARTLESSON}" id="videoClassStudent">
			                    <button class="xcourse-open-lesson">
			                    	<span>
										<img src="images/play.png" alt="{$smarty.const._XCOURSE_OPENLESSONS}">
									</span>
			                    </button>
                    		</a>
                    	{else}
		                    <button class="xcourse-open-lesson">
		                    	<span>
									<img src="images/play.png" alt="{$smarty.const._XCOURSE_OPENLESSONS}">
								</span>
		                    </button>
						{/if}	                 
	                    <h3 id="titleProgressStudent">{$course->course.name}<br />
	                    	{if $current_activity.firstlink}
	                    		<a href="{$current_activity.firstlink}&lessons_ID={$current_activity.id}&from_course={$course->course.id}">
		                    		{$smarty.const.__XCOURSE_MODULE} {$current_activity.lesson_index}: {$current_activity.name}
		                    	</a>
		                    {else}
		                    	{if $T_CURRENT_UNITS[$course_id][$lesson_id] > 0}
			                    	<a href="{$smarty.session.s_type}.php?ctg=content&view_unit={$T_CURRENT_UNITS[$course_id][$lesson_id]}">
			                    		{$smarty.const.__XCOURSE_MODULE} {$current_activity.lesson_index}: {$current_activity.name}
			                    	</a>
		                    	{else}
			                    	<a>
			                    		{$smarty.const.__XCOURSE_MODULE} {$current_activity.lesson_index}: {$current_activity.name}
			                    	</a>
		                    	{/if}
		                    {/if}
	                    </h3>
	                   
	              		 <div id="ativitesProgress">
							
							<p class="col2-3p">{$current_activity.information.general_description}</p>
							
							
							<!--
							<button name="configurar" type="button" class="event-confnormal" value="configurar" >
	                            <img src="images/transp.png" class="imgs_calendar" width="29" height="29" />
	                            <span>Dia do mês</span>
	                        </button>
	                        <button name="configurar" id="button-lesson" type="button" class="event-conf" value="configurar" >
	                            <img src="images/transp.png"  class="imgs_seta" width="29" height="29" />
	                            <span>definir nome</span>
	                        </button>
	                        <button name="content" type="button" id="button-content" class="event-conf" value="configurar" class="xcourse_content_btn">
	                            <img src="images/transp.png"  class="imgs_cont" width="29" height="29" />
	                            <span>conteúdo</span>
	                        </button>
	                       -->
	                        
	                   </div>
	                   
	                   
	                    <div id="progressIndicator">
		                    <div class="ui-progress-indicator">{$current_activity.progress.overall_progress|@round}%</div>
							<div class="ui-progress-bar">{$current_activity.progress.overall_progress|@round}</div>
	                   </div>
					</div>
					
					
					
					{assign var="course_index" value="`$course->course.id`"}
					<!-- 
					<div class="course_details_guidance">
						{if $T_XCOURSE_STUDENT_GUIDANCE_LINKS[$course_index]|@count > 0}
							<ul>
								<li>{$smarty.const.__XCOURSE_STUDENT_GUIDANCE}</li>
							</ul>
							<ul>
								
								{foreach item="guidance" from=$T_XCOURSE_STUDENT_GUIDANCE_LINKS[$course_index]}
									<li><a target="{$guidance.target}" href="{$guidance.link}">{$guidance.title}</a></li>
								{/foreach}
							</ul>
						{/if}
					</div>
					 -->
				</li>
				{/foreach}
			{/if}
		{/foreach}
	</ul>
	<div id="module_xcourse_content_list_tree_frontend_student"></div>
	
	<script type="text/javascript">
		var FIRST_COURSE_ID = {$first_course_id};
		var FIRST_LESSON_ID = {$first_lesson_id};
	</script>

        
	<!--
	<div class="clear"><br/></div>		
	 <div id="list-content" style="float:left; width:100%; padding-bottom:10px; ">
	   <div class="title"><br/> 1 {$smarty.const.__XCOURSE_CONTENT}</div>
	   <div class="rule2"></div>
	 </div>
	
	<div class="clear"><br/></div>	
	
	<div id="list-lesson" style="float:left; width:100%; padding-bottom:10px;  ">
		<div class="clear"><br/></div>	
		   <div class="title"><br/>2 {$smarty.const.__XCOURSE_LESSON_NAME}</div>
		<div class="rule2"></div>
	</div>
	-->
	
	
	
{/if}




