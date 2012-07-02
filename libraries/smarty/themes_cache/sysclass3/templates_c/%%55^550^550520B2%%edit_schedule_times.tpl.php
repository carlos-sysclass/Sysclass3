<?php /* Smarty version 2.6.26, created on 2012-06-06 14:40:26
         compiled from /srv/www/vhosts/local.sysclass.com/www/modules/module_xcontent//templates/actions/edit_schedule_times.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', '/srv/www/vhosts/local.sysclass.com/www/modules/module_xcontent//templates/actions/edit_schedule_times.tpl', 18, false),array('function', 'eF_template_printBlock', '/srv/www/vhosts/local.sysclass.com/www/modules/module_xcontent//templates/actions/edit_schedule_times.tpl', 138, false),)), $this); ?>
<?php ob_start(); ?>
<div class="blockContents form-list-itens">
	<!-- 
	<div class="grid_12">
		<label><?php echo @__XCONTENT_START_DATE; ?>
:</label>
		<span>dasdas</span>
	</div>
	<div class="grid_12">
		<label><?php echo @__XCONTENT_END_DATE; ?>
:</label>
		<span>dasdas</span>
	</div>
	 -->
	<div class="grid_24">
		<label><?php echo @__XCONTENT_SCOPE; ?>
:</label>
		<span><?php echo $this->_tpl_vars['T_XCONTENT_SCHEDULE']['scope']; ?>
</span>
	</div>
	
	<?php if (count($this->_tpl_vars['T_XCONTENT_SCOPE_FIELDS']) > 0): ?>
		<?php $_from = $this->_tpl_vars['T_XCONTENT_SCOPE_FIELDS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['field']):
?>
			<div class="grid_24">
				<label><?php echo $this->_tpl_vars['T_XCONTENT_SCHEDULE'][$this->_tpl_vars['field']]['label']; ?>
:</label>
				<span><?php echo $this->_tpl_vars['T_XCONTENT_SCHEDULE'][$this->_tpl_vars['field']]['value']; ?>
</span>
			</div>
		<?php endforeach; endif; unset($_from); ?>			
	<?php endif; ?>
	<div class="grid_24">
		<label><?php echo $this->_tpl_vars['msarty']['const']['__XCONTENT_CONTENT']; ?>
</label>
	</div>
	<ul class="default-list">
	<?php $_from = $this->_tpl_vars['T_XCONTENT_SCHEDULE']['contents']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['it_content'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['it_content']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['content_data']):
        $this->_foreach['it_content']['iteration']++;
?>
		<li>
			<div class="grid_24 <?php if ($this->_tpl_vars['content_data']['required'] == 1): ?>xcontentRequired<?php elseif ($this->_tpl_vars['content_data']['required'] == 0): ?>xcontentNoRequired<?php endif; ?>">
				<label><?php echo $this->_foreach['it_content']['iteration']; ?>
.</label>
				<span><?php echo $this->_tpl_vars['content_data']['course']; ?>
</span> &raquo;
				<span><?php echo $this->_tpl_vars['content_data']['lesson']; ?>
</span> &raquo; 
				<span><?php echo $this->_tpl_vars['content_data']['content']; ?>
</span>
				<button class="form-icon ScheduleContentDelete" 
					onclick="doAjaxDeleteScheduleContent(<?php echo $this->_tpl_vars['T_XCONTENT_SCHEDULE']['id']; ?>
, <?php echo $this->_tpl_vars['content_data']['course_id']; ?>
, <?php echo $this->_tpl_vars['content_data']['content_id']; ?>
, this); return false;">
					<img class="sprite16 sprite16-close" src="images/others/transparent.gif" />
				</button>
			</div>			
			
		</li>
	<?php endforeach; endif; unset($_from); ?>
		<li>
			<div class="grid_24">
				<img class="sprite16 sprite16-arrow_right" src="images/others/transparent.gif" />
				<a href="<?php echo $this->_tpl_vars['T_XCONTENT_BASEURL']; ?>
&action=append_new_content_to_schedule&xschedule_id=<?php echo $this->_tpl_vars['T_XCONTENT_SCHEDULE']['id']; ?>
">
					Adicionar um novo conteúdo
				</a>
			</div>
		</li>
	</ul>
</div>

<form action="<?php echo $_SERVER['request_uri']; ?>
" method="post">

	<input type="hidden" name="xschedule_id" value="<?php echo $this->_tpl_vars['T_XCONTENT_SCHEDULE_ID']; ?>
" />

	<ul class="default-list">
		<!-- MODELO DE LISTA PARA HORÁRIOS DO CURSO -->
		<li class="container_24" id="schedule_clonable" style="display: none;">
			<input type="hidden" name="index[new][]" value="-1" class="indexField" />
			<div class="grid_7">
				<label>Data:</label>
				<input name="date[new][]" value="" alt="date" class="no-button dateField" />
			</div>
			<div class="grid_7">
				<label>Início:</label>
				<input name="start[new][]" value="<?php echo $this->_tpl_vars['schedule']['start']; ?>
" alt="time" class="startField" />
			</div>
			<div class="grid_7">
				<label>Término:</label>
				<input name="end[new][]" value="<?php echo $this->_tpl_vars['schedule']['end']; ?>
" alt="time" class="endField" />
			</div>
			<div class="grid_3">
				<button class="form-icon contentScheduleEdit">
					<img class="sprite16 sprite16-edit" src="images/others/transparent.gif" />
				</button>
				<button class="form-icon contentScheduleDelete">
					<img class="sprite16 sprite16-close" src="images/others/transparent.gif" />
				</button>
				<button class="form-icon contentScheduleSave" style="display:none">
					<img class="sprite16 sprite16-success" src="images/others/transparent.gif" />
				</button>			
				<button class="form-icon contentScheduleCancel" style="display:none">
					<img class="sprite16 sprite16-arrow_left" src="images/others/transparent.gif" />
				</button>
			</div>
		</li>
		<?php $_from = $this->_tpl_vars['T_XCONTENT_SCHEDULE_TIMES']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['schedule_it'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['schedule_it']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['schedule_key'] => $this->_tpl_vars['schedule']):
        $this->_foreach['schedule_it']['iteration']++;
?>
			<li class="container_24">
				<input type="hidden" name="index[new][]" value="<?php echo $this->_tpl_vars['schedule']['index']; ?>
" class="indexField" />
				<div class="grid_7">
					<label>Data:</label>
					<input name="date[<?php echo $this->_tpl_vars['schedule']['index']; ?>
]" value="#filter:date-<?php echo $this->_tpl_vars['schedule']['start']; ?>
#" alt="date" class="no-button dateField" readonly="readonly" style="background: transparent; border-color: transparent"/>
				</div>
				<div class="grid_7">
					<label>Início:</label>
					<input name="start[<?php echo $this->_tpl_vars['schedule']['index']; ?>
]" value="#filter:time-<?php echo $this->_tpl_vars['schedule']['start']; ?>
#" alt="time" class="startField" readonly="readonly" style="background: transparent; border-color: transparent" />
				</div>
				<div class="grid_7">
					<label>Término:</label>
					<input name="end[<?php echo $this->_tpl_vars['schedule']['index']; ?>
]" value="#filter:time-<?php echo $this->_tpl_vars['schedule']['end']; ?>
#" alt="time" class="endField" readonly="readonly" style="background: transparent; border-color: transparent" />
				</div>
				<div class="grid_3">
					<button class="form-icon contentScheduleEdit">
						<img class="sprite16 sprite16-edit" src="images/others/transparent.gif" />
					</button>
					<button class="form-icon contentScheduleDelete">
						<img class="sprite16 sprite16-close" src="images/others/transparent.gif" />
					</button>
					<button class="form-icon contentScheduleSave" style="display:none">
						<img class="sprite16 sprite16-success" src="images/others/transparent.gif" />
					</button>			
					<button class="form-icon contentScheduleCancel" style="display:none">
						<img class="sprite16 sprite16-arrow_left" src="images/others/transparent.gif" />
					</button>
				</div>
			</li>
		<?php endforeach; endif; unset($_from); ?>
		<!-- FIM : MODELO DE LISTA PARA HORÁRIOS DO CURSO -->
	</ul>
	<div class="grid_24" style="margin-top: 20px;" align="center">
		<button class="form-button icon-add contentScheduleInsert" type="button" name="contentScheduleSubmit" value="contentScheduleSubmit">
			<img width="29" height="29" src="images/transp.png">
			<span><?php echo @__XCONTENT_SCHEDULE_ADD; ?>
</span>
		</button>		
		<!-- 
		<button class="form-button icon-save contentScheduleSave" type="button" name="contentScheduleSubmit" value="contentScheduleSubmit">
			<img width="29" height="29" src="images/transp.png">
			<span><?php echo @__XCONTENT_SCHEDULE_SAVE; ?>
</span>
		</button>
		 -->
	</div>
</form>
<?php $this->_smarty_vars['capture']['t_edit_schedule_times'] = ob_get_contents(); ob_end_clean(); ?>
<?php echo smarty_function_eF_template_printBlock(array('title' => @__XCONTENT_EDIT_SCHEDULE,'data' => $this->_smarty_vars['capture']['t_edit_schedule_times'],'contentclass' => 'blockContents'), $this);?>