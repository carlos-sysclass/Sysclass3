<?php /* Smarty version 2.6.26, created on 2012-06-04 14:50:47
         compiled from /srv/www/vhosts/local.sysclass.com/www/modules/module_xcourse/templates/blocks/xcourse.user.activity.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', '/srv/www/vhosts/local.sysclass.com/www/modules/module_xcourse/templates/blocks/xcourse.user.activity.tpl', 4, false),array('modifier', 'round', '/srv/www/vhosts/local.sysclass.com/www/modules/module_xcourse/templates/blocks/xcourse.user.activity.tpl', 74, false),)), $this); ?>
<?php if ($this->_tpl_vars['T_USER_COURSE_PROGRESS']): ?>
	<ul id="xcourse-activity-list">
		<?php $_from = $this->_tpl_vars['T_USER_COURSE_PROGRESS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['course_iterator'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['course_iterator']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['index'] => $this->_tpl_vars['course']):
        $this->_foreach['course_iterator']['iteration']++;
?>
			<?php if (count($this->_tpl_vars['course']->course['activities']) > 0): ?>
				<?php $_from = $this->_tpl_vars['course']->course['activities']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['activity_iterator'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['activity_iterator']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['index'] => $this->_tpl_vars['current_activity']):
        $this->_foreach['activity_iterator']['iteration']++;
?>
				
					<?php if (($this->_foreach['course_iterator']['iteration'] <= 1) && ($this->_foreach['activity_iterator']['iteration'] <= 1)): ?>
						<?php $this->assign('first_course_id', ($this->_tpl_vars['course']->course['id'])); ?>
						<?php $this->assign('first_lesson_id', ($this->_tpl_vars['current_activity']['id'])); ?>
					<?php endif; ?>
					
				<li class="course_lesson_<?php echo $this->_tpl_vars['course']->course['id']; ?>
_<?php echo $this->_tpl_vars['current_activity']['id']; ?>
" id="course_lesson_<?php echo $this->_tpl_vars['course']->course['id']; ?>
_<?php echo $this->_tpl_vars['current_activity']['id']; ?>
">
				
					<div class="col2-3">
						<?php $this->assign('course_id', ($this->_tpl_vars['course']->course['id'])); ?>
						<?php $this->assign('lesson_id', ($this->_tpl_vars['current_activity']['id'])); ?>
						<?php if ($this->_tpl_vars['T_CURRENT_UNITS'][$this->_tpl_vars['course_id']][$this->_tpl_vars['lesson_id']] > 0): ?>
                        	<a href="<?php echo $_SESSION['s_type']; ?>
.php?ctg=content&view_unit=<?php echo $this->_tpl_vars['T_CURRENT_UNITS'][$this->_tpl_vars['course_id']][$this->_tpl_vars['lesson_id']]; ?>
" title="<?php echo @_STARTLESSON; ?>
" id="videoClassStudent">
			                    <button class="xcourse-open-lesson">
			                    	<span>
										<img src="images/play.png" alt="<?php echo @_XCOURSE_OPENLESSONS; ?>
">
									</span>
			                    </button>
                    		</a>
                    	<?php else: ?>
		                    <button class="xcourse-open-lesson">
		                    	<span>
									<img src="images/play.png" alt="<?php echo @_XCOURSE_OPENLESSONS; ?>
">
								</span>
		                    </button>
						<?php endif; ?>	                 
	                    <h3 id="titleProgressStudent"><?php echo $this->_tpl_vars['course']->course['name']; ?>
<br />
	                    	<?php if ($this->_tpl_vars['current_activity']['firstlink']): ?>
	                    		<a href="<?php echo $this->_tpl_vars['current_activity']['firstlink']; ?>
&lessons_ID=<?php echo $this->_tpl_vars['current_activity']['id']; ?>
&from_course=<?php echo $this->_tpl_vars['course']->course['id']; ?>
">
		                    		<?php echo @__XCOURSE_MODULE; ?>
 <?php echo $this->_tpl_vars['current_activity']['lesson_index']; ?>
: <?php echo $this->_tpl_vars['current_activity']['name']; ?>

		                    	</a>
		                    <?php else: ?>
		                    	<?php if ($this->_tpl_vars['T_CURRENT_UNITS'][$this->_tpl_vars['course_id']][$this->_tpl_vars['lesson_id']] > 0): ?>
			                    	<a href="<?php echo $_SESSION['s_type']; ?>
.php?ctg=content&view_unit=<?php echo $this->_tpl_vars['T_CURRENT_UNITS'][$this->_tpl_vars['course_id']][$this->_tpl_vars['lesson_id']]; ?>
">
			                    		<?php echo @__XCOURSE_MODULE; ?>
 <?php echo $this->_tpl_vars['current_activity']['lesson_index']; ?>
: <?php echo $this->_tpl_vars['current_activity']['name']; ?>

			                    	</a>
		                    	<?php else: ?>
			                    	<a>
			                    		<?php echo @__XCOURSE_MODULE; ?>
 <?php echo $this->_tpl_vars['current_activity']['lesson_index']; ?>
: <?php echo $this->_tpl_vars['current_activity']['name']; ?>

			                    	</a>
		                    	<?php endif; ?>
		                    <?php endif; ?>
	                    </h3>
	                   
	              		 <div id="ativitesProgress">
							
							<p class="col2-3p"><?php echo $this->_tpl_vars['current_activity']['information']['general_description']; ?>
</p>
							<br/>
							
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
		                    <div class="ui-progress-indicator"><?php echo round($this->_tpl_vars['current_activity']['progress']['overall_progress']); ?>
%</div>
							<div class="ui-progress-bar"><?php echo round($this->_tpl_vars['current_activity']['progress']['overall_progress']); ?>
</div>
	                   </div>
					</div>
					
					
					
					<?php $this->assign('course_index', ($this->_tpl_vars['course']->course['id'])); ?>
					<!-- 
					<div class="course_details_guidance">
						<?php if (count($this->_tpl_vars['T_XCOURSE_STUDENT_GUIDANCE_LINKS'][$this->_tpl_vars['course_index']]) > 0): ?>
							<ul>
								<li><?php echo @__XCOURSE_STUDENT_GUIDANCE; ?>
</li>
							</ul>
							<ul>
								
								<?php $_from = $this->_tpl_vars['T_XCOURSE_STUDENT_GUIDANCE_LINKS'][$this->_tpl_vars['course_index']]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['guidance']):
?>
									<li><a target="<?php echo $this->_tpl_vars['guidance']['target']; ?>
" href="<?php echo $this->_tpl_vars['guidance']['link']; ?>
"><?php echo $this->_tpl_vars['guidance']['title']; ?>
</a></li>
								<?php endforeach; endif; unset($_from); ?>
							</ul>
						<?php endif; ?>
					</div>
					 -->
				</li>
				<?php endforeach; endif; unset($_from); ?>
			<?php endif; ?>
		<?php endforeach; endif; unset($_from); ?>
	</ul>
	<div id="module_xcourse_content_list_tree_frontend_student"></div>
	
	<script type="text/javascript">
		var FIRST_COURSE_ID = <?php echo $this->_tpl_vars['first_course_id']; ?>
;
		var FIRST_LESSON_ID = <?php echo $this->_tpl_vars['first_lesson_id']; ?>
;
	</script>

        
	<!--
	<div class="clear"><br/></div>		
	 <div id="list-content" style="float:left; width:100%; padding-bottom:10px; ">
	   <div class="title"><br/> 1 <?php echo @__XCOURSE_CONTENT; ?>
</div>
	   <div class="rule2"></div>
	 </div>
	
	<div class="clear"><br/></div>	
	
	<div id="list-lesson" style="float:left; width:100%; padding-bottom:10px;  ">
		<div class="clear"><br/></div>	
		   <div class="title"><br/>2 <?php echo @__XCOURSE_LESSON_NAME; ?>
</div>
		<div class="rule2"></div>
	</div>
	-->
	
	
	
<?php endif; ?>



