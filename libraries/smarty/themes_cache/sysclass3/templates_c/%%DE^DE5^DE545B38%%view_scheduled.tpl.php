<?php /* Smarty version 2.6.26, created on 2012-06-08 14:16:45
         compiled from /srv/www/vhosts/local.sysclass.com/www/modules/module_xcontent//templates/actions/view_scheduled.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', '/srv/www/vhosts/local.sysclass.com/www/modules/module_xcontent//templates/actions/view_scheduled.tpl', 11, false),array('modifier', 'eF_truncate', '/srv/www/vhosts/local.sysclass.com/www/modules/module_xcontent//templates/actions/view_scheduled.tpl', 50, false),array('function', 'eF_template_printBlock', '/srv/www/vhosts/local.sysclass.com/www/modules/module_xcontent//templates/actions/view_scheduled.tpl', 75, false),)), $this); ?>
<?php ob_start(); ?>
<?php if ($this->_tpl_vars['T_EXTENDED_USERTYPE'] == 'administrator' || $this->_tpl_vars['T_CURRENT_USER']->moduleAccess['xcontent'] == 'change'): ?>
	<div class="headerTools">
		<span>
			<img class="sprite16 sprite16-add" src="images/others/transparent.gif">
	    	<a href="<?php echo $this->_tpl_vars['T_XCONTENT_BASEURL']; ?>
&action=new_schedule">Cadastrar novo agendamento</a>
		</span>
	</div>
<?php endif; ?>
<?php $_from = $this->_tpl_vars['T_XCONTENT_SCOPES']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['scope']):
?>
	<?php if (count($this->_tpl_vars['T_XCONTENT_SCHEDULES'][$this->_tpl_vars['scope']['id']]) > 0): ?>
		<div class="clear"></div>
		<h3><?php echo @__XCONTENT_SCOPE; ?>
: <?php echo $this->_tpl_vars['scope']['name']; ?>
</h3>
	
		<table class="_XCONTENT_SCHEDULE_LIST static">
			<thead>
				<tr class="topTitle">
					<th style="text-align: center;"><?php echo @__XCONTENT_COURSE_OR_COURSES; ?>
</th>
					<th style="text-align: center;"><?php echo @__XCONTENT_CONTENT_OR_CONTENTS; ?>
</th>
					<th style="text-align: center;">Período</th>
					<?php $_from = $this->_tpl_vars['scope']['fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['field']):
?>
						<th style="text-align: center;"><?php echo $this->_tpl_vars['field']['label']; ?>
</th>
					<?php endforeach; endif; unset($_from); ?>
					<th style="text-align: center;">Opções</th>
				</tr>
			</thead>
			<tbody>
				<?php $_from = $this->_tpl_vars['T_XCONTENT_SCHEDULES'][$this->_tpl_vars['scope']['id']]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['schedule']):
?>
				<tr>
					<td><?php echo $this->_tpl_vars['schedule']['total_courses']; ?>
 <?php echo @__XCONTENT_COURSE_OR_COURSES; ?>
</td>
					<td><?php echo $this->_tpl_vars['schedule']['total_contents']; ?>
 <?php echo @__XCONTENT_CONTENT_OR_CONTENTS; ?>
</td>
					
					<td align="center">
						<?php if ($this->_tpl_vars['schedule']['start']): ?>
							#filter:date-<?php echo $this->_tpl_vars['schedule']['start']; ?>
#
							<?php if ($this->_tpl_vars['schedule']['end']): ?>
								&raquo; #filter:date-<?php echo $this->_tpl_vars['schedule']['end']; ?>
#
							<?php else: ?>
								&raquo; &#8734;
							<?php endif; ?>
						<?php elseif ($this->_tpl_vars['schedule']['end']): ?>
							&#8734; &raquo; #filter:date-<?php echo $this->_tpl_vars['schedule']['end']; ?>
#
						<?php else: ?>
							N/A
						<?php endif; ?>
						
					</td>
					<?php $_from = $this->_tpl_vars['scope']['fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['field']):
?>
						<?php $this->assign('field_name', ($this->_tpl_vars['field']['name'])); ?>
						<td align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['schedule'][$this->_tpl_vars['field_name']]['value'])) ? $this->_run_mod_handler('eF_truncate', true, $_tmp, 40) : smarty_modifier_eF_truncate($_tmp, 40)); ?>
</td>
					<?php endforeach; endif; unset($_from); ?>			
					<td>
						<div>
							<?php if ($this->_tpl_vars['T_EXTENDED_USERTYPE'] == 'administrator' || $this->_tpl_vars['T_CURRENT_USER']->moduleAccess['xcontent'] == 'change'): ?>
							<button class="form-icon contentScheduleEdit" onclick="window.location.href = '<?php echo $this->_tpl_vars['T_XCONTENT_BASEURL']; ?>
&action=edit_schedule_times&xschedule_id=<?php echo $this->_tpl_vars['schedule']['id']; ?>
'; return false;">
								<img class="sprite16 sprite16-edit" src="images/others/transparent.gif" />
							</button>
							<?php endif; ?>
							<button class="form-icon contentScheduleEdit" onclick="window.location.href = '<?php echo $this->_tpl_vars['T_XCONTENT_BASEURL']; ?>
&action=view_scheduled_users&xschedule_id=<?php echo $this->_tpl_vars['schedule']['id']; ?>
'; return false;">
								<img class="sprite16 sprite16-calendar" src="images/others/transparent.gif" />
							</button>
							<button class="form-icon contentScheduleDelete" onclick="deleteSchedule(<?php echo $this->_tpl_vars['schedule']['id']; ?>
); return false;">
								<img class="sprite16 sprite16-close" src="images/others/transparent.gif" />
							</button>
						</div>
					
					</td>
				</tr>
				<?php endforeach; endif; unset($_from); ?>	
			</tbody>
		</table>
	<?php endif; ?>
<?php endforeach; endif; unset($_from); ?>
<?php $this->_smarty_vars['capture']['t_view_scheduled'] = ob_get_contents(); ob_end_clean(); ?>
<?php echo smarty_function_eF_template_printBlock(array('title' => @__XCONTENT_VIEW_SCHEDULED,'data' => $this->_smarty_vars['capture']['t_view_scheduled'],'contentclass' => 'blockContents'), $this);?>