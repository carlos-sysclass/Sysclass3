<?php /* Smarty version 2.6.26, created on 2012-06-04 14:50:47
         compiled from /srv/www/vhosts/local.sysclass.com/www/modules/module_xcourse/templates/blocks/xcourse.academic_calendar.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'eF_truncate', '/srv/www/vhosts/local.sysclass.com/www/modules/module_xcourse/templates/blocks/xcourse.academic_calendar.tpl', 21, false),array('modifier', 'strlen', '/srv/www/vhosts/local.sysclass.com/www/modules/module_xcourse/templates/blocks/xcourse.academic_calendar.tpl', 58, false),)), $this); ?>
<?php $_from = $this->_tpl_vars['T_XCOURSE_ACADEMIC_CALENDAR']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['course_id'] => $this->_tpl_vars['course_academic']):
?>
	<?php $this->assign('course_calendar_capture', "course_calendar_".($this->_tpl_vars['course_id'])); ?> 
	
	<?php if ($this->_tpl_vars['course_academic']['lessons']): ?>
		<?php ob_start(); ?>
			<tr>
				<th><?php echo @__LESSON; ?>
</th>
				<th width="15%"><?php echo @__START_DATE; ?>
</th>
				<th width="15%"><?php echo @__END_DATE; ?>
</th>
			</tr>
		<?php $this->_smarty_vars['capture']['calendar_table_header'] = ob_get_contents(); ob_end_clean(); ?>
		<?php ob_start(); ?>
			<?php $_from = $this->_tpl_vars['course_academic']['lessons']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['lesson_id'] => $this->_tpl_vars['lessons_times']):
?>
				<?php if (! is_null ( $this->_tpl_vars['lessons_times']['start_date'] ) || ! is_null ( $this->_tpl_vars['lessons_times']['end_date'] )): ?>
					<tr>
						<td>
							<a href="<?php echo $this->_tpl_vars['T_XCOURSE_BASEURL']; ?>
student.php?ctg=module&op=module_xcourse&action=load_academic_calendar_lesson&course_id=<?php echo $this->_tpl_vars['course_id']; ?>
&lesson_id=<?php echo $this->_tpl_vars['lesson_id']; ?>
&popup=1" 
							   target="POPUP_FRAME"
							   onclick="eF_js_showDivPopup('<?php echo $this->_tpl_vars['lessons_times']['name']; ?>
', 1)"
							   >
								<?php echo ((is_array($_tmp=$this->_tpl_vars['lessons_times']['name'])) ? $this->_run_mod_handler('eF_truncate', true, $_tmp, 70) : smarty_modifier_eF_truncate($_tmp, 70)); ?>

							</a>
				
						</td>
						<td align="center"><?php if ($this->_tpl_vars['lessons_times']['start_date']): ?>#filter:date-<?php echo $this->_tpl_vars['lessons_times']['start_date']; ?>
#<?php else: ?>N/A<?php endif; ?></td>
						<td align="center"><?php if ($this->_tpl_vars['lessons_times']['end_date']): ?>#filter:date-<?php echo $this->_tpl_vars['lessons_times']['end_date']; ?>
#<?php else: ?>N/A<?php endif; ?></td>
					</tr>
				<?php endif; ?>
			<?php endforeach; endif; unset($_from); ?>
		<?php $this->_smarty_vars['capture'][$this->_tpl_vars['course_calendar_capture']] = ob_get_contents(); ob_end_clean(); ?>
	<?php elseif ($this->_tpl_vars['course_academic']['series']): ?>
		<?php ob_start(); ?>
			<tr>
				<th><?php echo @__DESCRIPTION; ?>
</th>
				<th width="15%"><?php echo @__START_DATE; ?>
</th>
				<th width="15%"><?php echo @__END_DATE; ?>
</th>
			</tr>
		<?php $this->_smarty_vars['capture']['calendar_table_header'] = ob_get_contents(); ob_end_clean(); ?>

	
	
		<?php ob_start(); ?>
			<?php $_from = $this->_tpl_vars['course_academic']['series']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['lesson_id'] => $this->_tpl_vars['lessons_times']):
?>
					<tr>
						<td><?php echo ((is_array($_tmp=$this->_tpl_vars['lessons_times']['name'])) ? $this->_run_mod_handler('eF_truncate', true, $_tmp, 70) : smarty_modifier_eF_truncate($_tmp, 70)); ?>
</td>
						<td align="center"><?php if ($this->_tpl_vars['lessons_times']['start']): ?>#filter:date-<?php echo $this->_tpl_vars['lessons_times']['start']; ?>
#<?php else: ?>N/A<?php endif; ?></td>
						<td align="center"><?php if ($this->_tpl_vars['lessons_times']['end']): ?>#filter:date-<?php echo $this->_tpl_vars['lessons_times']['end']; ?>
#<?php else: ?>N/A<?php endif; ?></td>
					</tr>
				
			<?php endforeach; endif; unset($_from); ?>
		<?php $this->_smarty_vars['capture'][$this->_tpl_vars['course_calendar_capture']] = ob_get_contents(); ob_end_clean(); ?>
	<?php endif; ?>
<?php endforeach; endif; unset($_from); ?>

<ul id="xcourse-academic-calendar">
	<?php $_from = $this->_tpl_vars['T_XCOURSE_ACADEMIC_CALENDAR']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['course_id'] => $this->_tpl_vars['course_academic']):
?>
		<?php $this->assign('course_calendar_capture', "course_calendar_".($this->_tpl_vars['course_id'])); ?>
		<?php if (strlen($this->_smarty_vars['capture'][$this->_tpl_vars['course_calendar_capture']]) > 100): ?>
		
			<?php if ($this->_tpl_vars['course_academic']['lessons']): ?>
				<?php $this->assign('course_calendar_class', "course_".($this->_tpl_vars['course_id'])); ?>
			<?php elseif ($this->_tpl_vars['course_academic']['series']): ?>
				<?php $this->assign('course_calendar_class', "course_lesson_".($this->_tpl_vars['course_id'])."_".($this->_tpl_vars['course_academic']['lesson']['id'])); ?>
			<?php endif; ?>
					
			<li class="<?php echo $this->_tpl_vars['course_calendar_class']; ?>
">
				<ul class="default-list">
					<li>
						<?php if ($this->_tpl_vars['course_academic']['lessons']): ?>
							<!-- 
							<div style="text-align: center"><?php echo ((is_array($_tmp=$this->_tpl_vars['course_academic']['course']['name'])) ? $this->_run_mod_handler('eF_truncate', true, $_tmp, 80) : smarty_modifier_eF_truncate($_tmp, 80)); ?>
</div>
							 -->
						<?php elseif ($this->_tpl_vars['course_academic']['series']): ?>
							<div style="text-align: center"><?php echo ((is_array($_tmp=$this->_tpl_vars['course_academic']['lesson']['name'])) ? $this->_run_mod_handler('eF_truncate', true, $_tmp, 80) : smarty_modifier_eF_truncate($_tmp, 80)); ?>
</div>
						<?php endif; ?>
					</li>
					<li style="border-bottom: none;">
						<table class="style1 default-table">
							<thead>
								<?php echo $this->_smarty_vars['capture']['calendar_table_header']; ?>

							</thead>
							<tbody>
								<?php echo $this->_smarty_vars['capture'][$this->_tpl_vars['course_calendar_capture']]; ?>

							</tbody>
						</table>
					</li>
				</ul>
			</li>
		<?php endif; ?>
	<?php endforeach; endif; unset($_from); ?>
</ul>