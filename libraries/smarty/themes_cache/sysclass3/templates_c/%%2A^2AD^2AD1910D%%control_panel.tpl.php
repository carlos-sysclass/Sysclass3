<?php /* Smarty version 2.6.26, created on 2012-06-08 14:12:03
         compiled from includes/control_panel.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'cat', 'includes/control_panel.tpl', 11, false),array('modifier', 'eF_truncate', 'includes/control_panel.tpl', 151, false),array('modifier', 'count', 'includes/control_panel.tpl', 233, false),array('modifier', 'replace', 'includes/control_panel.tpl', 263, false),array('function', 'eF_template_printBlock', 'includes/control_panel.tpl', 50, false),array('function', 'eF_template_printProjects', 'includes/control_panel.tpl', 93, false),array('function', 'eF_template_printForumMessages', 'includes/control_panel.tpl', 106, false),array('function', 'eF_template_printPersonalMessages', 'includes/control_panel.tpl', 121, false),array('function', 'eF_template_printComments', 'includes/control_panel.tpl', 134, false),array('function', 'cycle', 'includes/control_panel.tpl', 177, false),)), $this); ?>
<?php if ($this->_tpl_vars['T_OP'] == 'search'): ?>
        <?php ob_start(); ?>
		<tr><td class = "moduleCell">
				<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "includes/module_search.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
			</td></tr>
		<?php $this->_smarty_vars['capture']['moduleSearchResults'] = ob_get_contents(); ob_end_clean(); ?>
	<?php elseif (isset ( $this->_tpl_vars['T_OP_MODULE'] )): ?>
		<?php ob_start(); ?>
		<tr><td class = "moduleCell">
				<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ((is_array($_tmp=((is_array($_tmp=@G_MODULESPATH)) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['T_OP']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['T_OP'])))) ? $this->_run_mod_handler('cat', true, $_tmp, '/module.tpl') : smarty_modifier_cat($_tmp, '/module.tpl')), 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
			</td></tr>
		<?php $this->_smarty_vars['capture']['importedModule'] = ob_get_contents(); ob_end_clean(); ?>
	<?php else: ?>

				<?php if ($this->_tpl_vars['T_CONFIGURATION']['disable_news'] != 1 && $this->_tpl_vars['T_CURRENT_USER']->coreAccess['news'] != 'hidden' && ( ! $this->_tpl_vars['_admin_'] && $this->_tpl_vars['T_CURRENT_LESSON']->options['news'] )): ?>
        <?php ob_start(); ?>
			<tr><td class = "moduleCell">
				<?php ob_start(); ?>
					<table class = "">
						<?php $_from = $this->_tpl_vars['T_NEWS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['news_list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['news_list']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
        $this->_foreach['news_list']['iteration']++;
?>
							<tr><td style="text-align:left;"><?php echo $this->_foreach['news_list']['iteration']; ?>
. <a title = "<?php echo $this->_tpl_vars['item']['title']; ?>
" href = "<?php echo $_SERVER['PHP_SELF']; ?>
?ctg=news&view=<?php echo $this->_tpl_vars['item']['id']; ?>
&popup=1" target = "POPUP_FRAME" onclick = "eF_js_showDivPopup('<?php echo @_ANNOUNCEMENT; ?>
', 1);"><?php echo $this->_tpl_vars['item']['title']; ?>
</a></td>
								<td class = "cpanelTime">#filter:user_login-<?php echo $this->_tpl_vars['item']['users_LOGIN']; ?>
#, <span title = "#filter:timestamp_time-<?php echo $this->_tpl_vars['item']['timestamp']; ?>
#"><?php echo $this->_tpl_vars['item']['time_since']; ?>
</span></td></tr>
							<?php endforeach; else: ?>
							<tr><td class = "emptyCategory">	<?php echo @_NOANNOUNCEMENTSPOSTED; ?>
</td></tr>
						<?php endif; unset($_from); ?>
					</table>
				<?php $this->_smarty_vars['capture']['t_news_code'] = ob_get_contents(); ob_end_clean(); ?>

				<?php echo smarty_function_eF_template_printBlock(array('title' => @_ANNOUNCEMENTS,'content' => $this->_smarty_vars['capture']['t_news_code'],'image' => '32x32/announcements.png','options' => $this->_tpl_vars['T_NEWS_OPTIONS'],'link' => $this->_tpl_vars['T_NEWS_LINK'],'expand' => $this->_tpl_vars['T_POSITIONS_VISIBILITY']['moduleNewsList']), $this);?>

			</td></tr>
		<?php $this->_smarty_vars['capture']['moduleNewsList'] = ob_get_contents(); ob_end_clean(); ?>
	<?php endif; ?>

        
		
    	<?php if ($this->_tpl_vars['T_PROJECTS']): ?>
        <?php ob_start(); ?>
			<tr><td class = "moduleCell">
				<?php ob_start(); ?>
					<?php echo smarty_function_eF_template_printProjects(array('data' => $this->_tpl_vars['T_PROJECTS'],'limit' => 5), $this);?>

				<?php $this->_smarty_vars['capture']['t_projects_code'] = ob_get_contents(); ob_end_clean(); ?>

				<?php echo smarty_function_eF_template_printBlock(array('title' => @_PROJECTS,'data' => $this->_smarty_vars['capture']['t_projects_code'],'image' => '32x32/projects.png','options' => $this->_tpl_vars['T_PROJECTS_OPTIONS'],'link' => $this->_tpl_vars['T_PROJECTS_LINK'],'expand' => $this->_tpl_vars['T_POSITIONS_VISIBILITY']['moduleProjectsList']), $this);?>

			</td></tr>
		<?php $this->_smarty_vars['capture']['moduleProjectsList'] = ob_get_contents(); ob_end_clean(); ?>
	<?php endif; ?>

        <?php if (( $this->_tpl_vars['T_CURRENT_LESSON']->options['forum'] ) && $this->_tpl_vars['T_FORUM_MESSAGES']): ?>
        <?php ob_start(); ?>
			<tr><td class = "moduleCell">
				<?php ob_start(); ?>
					<?php echo smarty_function_eF_template_printForumMessages(array('data' => $this->_tpl_vars['T_FORUM_MESSAGES'],'forum_lessons_ID' => $this->_tpl_vars['T_FORUM_LESSONS_ID'],'limit' => 3), $this);?>

				<?php $this->_smarty_vars['capture']['t_forum_messages_code'] = ob_get_contents(); ob_end_clean(); ?>



				<?php echo smarty_function_eF_template_printBlock(array('title' => @_RECENTMESSAGESATFORUM,'data' => $this->_smarty_vars['capture']['t_forum_messages_code'],'image' => '32x32/forum.png','options' => $this->_tpl_vars['T_FORUM_OPTIONS'],'link' => $this->_tpl_vars['T_FORUM_LINK'],'expand' => $this->_tpl_vars['T_POSITIONS_VISIBILITY']['moduleForumList']), $this);?>

			</td></tr>
		<?php $this->_smarty_vars['capture']['moduleForumList'] = ob_get_contents(); ob_end_clean(); ?>
	<?php endif; ?>

        <?php if ($this->_tpl_vars['T_PERSONAL_MESSAGES']): ?>
        <?php ob_start(); ?>
			<tr><td class = "moduleCell">
				<?php ob_start(); ?>
					<?php echo smarty_function_eF_template_printPersonalMessages(array('data' => $this->_tpl_vars['T_PERSONAL_MESSAGES']), $this);?>

				<?php $this->_smarty_vars['capture']['t_personal_messages_code'] = ob_get_contents(); ob_end_clean(); ?>

				<?php echo smarty_function_eF_template_printBlock(array('title' => @_RECENTUNREADPERSONALMESSAGES,'data' => $this->_smarty_vars['capture']['t_personal_messages_code'],'image' => '32x32/mail.png','options' => $this->_tpl_vars['T_PERSONAL_MESSAGES_OPTIONS'],'link' => $this->_tpl_vars['T_PERSONAL_MESSAGES_LINK'],'expand' => $this->_tpl_vars['T_POSITIONS_VISIBILITY']['modulePersonalMessagesList']), $this);?>

			</td></tr>
		<?php $this->_smarty_vars['capture']['modulePersonalMessagesList'] = ob_get_contents(); ob_end_clean(); ?>
	<?php endif; ?>

        <?php if (( $this->_tpl_vars['T_CURRENT_LESSON']->options['comments'] ) && $this->_tpl_vars['T_COMMENTS'] && $this->_tpl_vars['T_CONFIGURATION']['disable_comments'] != 1): ?>
        <?php ob_start(); ?>
			<tr><td class = "moduleCell">
				<?php ob_start(); ?>
					<?php echo smarty_function_eF_template_printComments(array('data' => $this->_tpl_vars['T_COMMENTS']), $this);?>

				<?php $this->_smarty_vars['capture']['t_comments_code'] = ob_get_contents(); ob_end_clean(); ?>

				<?php echo smarty_function_eF_template_printBlock(array('title' => @_RECENTCOMMENTS,'data' => $this->_smarty_vars['capture']['t_comments_code'],'image' => '32x32/note.png','link' => $this->_tpl_vars['T_COMMENTS_LINK'],'expand' => $this->_tpl_vars['T_POSITIONS_VISIBILITY']['moduleCommentsList']), $this);?>

			</td></tr>
		<?php $this->_smarty_vars['capture']['moduleCommentsList'] = ob_get_contents(); ob_end_clean(); ?>
	<?php endif; ?>

        <?php if ($this->_tpl_vars['T_COMPLETED_TESTS']): ?>
        <?php ob_start(); ?>
			<tr><td class = "moduleCell">
					<?php ob_start(); ?>
						<table border = "0" width = "100%">
							<?php unset($this->_sections['completed_test']);
$this->_sections['completed_test']['name'] = 'completed_test';
$this->_sections['completed_test']['loop'] = is_array($_loop=$this->_tpl_vars['T_COMPLETED_TESTS']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['completed_test']['max'] = (int)10;
$this->_sections['completed_test']['show'] = true;
if ($this->_sections['completed_test']['max'] < 0)
    $this->_sections['completed_test']['max'] = $this->_sections['completed_test']['loop'];
$this->_sections['completed_test']['step'] = 1;
$this->_sections['completed_test']['start'] = $this->_sections['completed_test']['step'] > 0 ? 0 : $this->_sections['completed_test']['loop']-1;
if ($this->_sections['completed_test']['show']) {
    $this->_sections['completed_test']['total'] = min(ceil(($this->_sections['completed_test']['step'] > 0 ? $this->_sections['completed_test']['loop'] - $this->_sections['completed_test']['start'] : $this->_sections['completed_test']['start']+1)/abs($this->_sections['completed_test']['step'])), $this->_sections['completed_test']['max']);
    if ($this->_sections['completed_test']['total'] == 0)
        $this->_sections['completed_test']['show'] = false;
} else
    $this->_sections['completed_test']['total'] = 0;
if ($this->_sections['completed_test']['show']):

            for ($this->_sections['completed_test']['index'] = $this->_sections['completed_test']['start'], $this->_sections['completed_test']['iteration'] = 1;
                 $this->_sections['completed_test']['iteration'] <= $this->_sections['completed_test']['total'];
                 $this->_sections['completed_test']['index'] += $this->_sections['completed_test']['step'], $this->_sections['completed_test']['iteration']++):
$this->_sections['completed_test']['rownum'] = $this->_sections['completed_test']['iteration'];
$this->_sections['completed_test']['index_prev'] = $this->_sections['completed_test']['index'] - $this->_sections['completed_test']['step'];
$this->_sections['completed_test']['index_next'] = $this->_sections['completed_test']['index'] + $this->_sections['completed_test']['step'];
$this->_sections['completed_test']['first']      = ($this->_sections['completed_test']['iteration'] == 1);
$this->_sections['completed_test']['last']       = ($this->_sections['completed_test']['iteration'] == $this->_sections['completed_test']['total']);
?>
								<tr><td>
										<a href = "<?php echo $_SERVER['PHP_SELF']; ?>
?ctg=tests&show_solved_test=<?php echo $this->_tpl_vars['T_COMPLETED_TESTS'][$this->_sections['completed_test']['index']]['id']; ?>
" style = "float:left">
											<?php echo ((is_array($_tmp=$this->_tpl_vars['T_COMPLETED_TESTS'][$this->_sections['completed_test']['index']]['name'])) ? $this->_run_mod_handler('eF_truncate', true, $_tmp, 50) : smarty_modifier_eF_truncate($_tmp, 50)); ?>
</a>
										<span style = "float:right">#filter:user_login-<?php echo $this->_tpl_vars['T_COMPLETED_TESTS'][$this->_sections['completed_test']['index']]['users_LOGIN']; ?>
#, #filter:timestamp_interval-<?php echo $this->_tpl_vars['T_COMPLETED_TESTS'][$this->_sections['completed_test']['index']]['timestamp']; ?>
# <?php echo @_AGO; ?>
</span>
									</td></tr>
								<?php endfor; endif; ?>
						</table>
					<?php $this->_smarty_vars['capture']['t_done_tests_code'] = ob_get_contents(); ob_end_clean(); ?>

					<?php echo smarty_function_eF_template_printBlock(array('title' => @_PENDINGTESTS,'data' => $this->_smarty_vars['capture']['t_done_tests_code'],'image' => '32x32/tests.png','options' => $this->_tpl_vars['T_DONE_QUESTIONS_OPTIONS'],'link' => $this->_tpl_vars['T_DONE_QUESTIONS_LINK']), $this);?>

				</td></tr>
			<?php $this->_smarty_vars['capture']['moduleDoneTests'] = ob_get_contents(); ob_end_clean(); ?>
		<?php endif; ?>

        <?php if (( $this->_tpl_vars['T_CURRENT_LESSON']->options['lessons_timeline'] ) && isset ( $this->_tpl_vars['T_TIMELINE_EVENTS'] )): ?>
		<?php ob_start(); ?>
			<tr><td class = "moduleCell">
					<?php ob_start(); ?>
						<!--ajax:lessonTimelineTable-->
						<table class = "sortedTable" style = "width:100%" noFooter = "true" size = "<?php echo $this->_tpl_vars['T_TIMELINE_EVENTS_SIZE']; ?>
" sortBy = "0" id = "lessonTimelineTable" useAjax = "1" rowsPerPage = "10" limit="10" url = "<?php echo $_SERVER['PHP_SELF']; ?>
?ctg=social&op=timeline&lessons_ID=<?php echo $_SESSION['s_lessons_ID']; ?>
<?php if (isset ( $_GET['topics_ID'] )): ?>&topics_ID=<?php echo $_GET['topics_ID']; ?>
<?php endif; ?>&">
							<tr style="display:none" class = "topTitle">
								<td class = "topTitle noSort" name="description"><?php echo @_SKILL; ?>
</td>
								<td class = "topTitle noSort" name="surname" ><?php echo @_SPECIFICATION; ?>
</td>
								<td class = "topTitle noSort" name="timestamp" ><?php echo @_TIMESTAMP; ?>
</td>
							</tr>
							<?php if (isset ( $this->_tpl_vars['T_TIMELINE_EVENTS'] )): ?>
								<?php $_from = $this->_tpl_vars['T_TIMELINE_EVENTS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['events_list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['events_list']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['event']):
        $this->_foreach['events_list']['iteration']++;
?>
									<tr class = "<?php echo smarty_function_cycle(array('values' => "oddRowColor, evenRowColor"), $this);?>
">
										<td class = "elementCell">
											<img src = "view_file.php?file=<?php echo $this->_tpl_vars['event']['avatar']; ?>
" title="<?php echo @_CURRENTAVATAR; ?>
" alt="<?php echo @_CURRENTAVATAR; ?>
" width = "<?php echo $this->_tpl_vars['event']['avatar_width']; ?>
" height = "<?php echo $this->_tpl_vars['event']['avatar_height']; ?>
" style="vertical-align:middle" />
										</td>
										<td width="1px">&nbsp;</td>
										<td width="100%"><?php echo $this->_tpl_vars['event']['message']; ?>
 <span class="timeago"><?php echo $this->_tpl_vars['event']['time']; ?>
</span> <br/>
									</tr>
								<?php endforeach; endif; unset($_from); ?>
							</table>
							<!--/ajax:lessonTimelineTable-->
						<?php else: ?>
					<tr><td colspan = 3>
							<table width = "100%">
								<tr><td class = "emptyCategory"><?php echo @_NORELATEDPEOPLEFOUND; ?>
</td></tr>
							</table>
						</td>
					</tr>
                </table>
				<!--/ajax:lessonTimelineTable-->
            <?php endif; ?>
		<?php $this->_smarty_vars['capture']['t_timeline_code'] = ob_get_contents(); ob_end_clean(); ?>

		<?php echo smarty_function_eF_template_printBlock(array('title' => @_TIMELINE,'data' => $this->_smarty_vars['capture']['t_timeline_code'],'image' => '32x32/user_timeline.png','options' => $this->_tpl_vars['T_TIMELINE_OPTIONS'],'link' => $this->_tpl_vars['T_TIMELINE_LINK']), $this);?>

	</td></tr>
<?php $this->_smarty_vars['capture']['moduleTimeline'] = ob_get_contents(); ob_end_clean(); ?>
<?php endif; ?>

<?php if ($this->_tpl_vars['_student_'] && $this->_tpl_vars['T_CURRENT_USER']->coreAccess['content'] != 'hidden' && $this->_tpl_vars['T_CURRENT_LESSON']->options['content_tree']): ?>
	<?php ob_start(); ?>
		<tr><td class = "moduleCell">
				<?php ob_start(); ?>
					<?php echo $this->_tpl_vars['T_CONTENT_TREE']; ?>

				<?php $this->_smarty_vars['capture']['t_content_tree'] = ob_get_contents(); ob_end_clean(); ?>
				<?php echo smarty_function_eF_template_printBlock(array('title' => @_CURRENTCONTENT,'data' => $this->_smarty_vars['capture']['t_content_tree'],'image' => "32x32/content.png",'alt' => ((is_array($_tmp=((is_array($_tmp='<span class = "emptyCategory">')) ? $this->_run_mod_handler('cat', true, $_tmp, @_NOCONTENTFOUND) : smarty_modifier_cat($_tmp, @_NOCONTENTFOUND)))) ? $this->_run_mod_handler('cat', true, $_tmp, '</span>') : smarty_modifier_cat($_tmp, '</span>')),'options' => $this->_tpl_vars['T_TREE_OPTIONS'],'expand' => $this->_tpl_vars['T_POSITIONS_VISIBILITY']['moduleContentTree']), $this);?>

			</td></tr>
        <?php $this->_smarty_vars['capture']['moduleContentTree'] = ob_get_contents(); ob_end_clean(); ?>
    <?php endif; ?>

<?php if ($this->_tpl_vars['T_FILE_MANAGER']): ?>
	<?php ob_start(); ?>
		<tr><td class = "moduleCell">
				<?php ob_start(); ?>
					<?php echo $this->_tpl_vars['T_FILE_MANAGER']; ?>

				<?php $this->_smarty_vars['capture']['t_digital_library'] = ob_get_contents(); ob_end_clean(); ?>

				<?php echo smarty_function_eF_template_printBlock(array('title' => @_SHAREDFILES,'data' => $this->_smarty_vars['capture']['t_digital_library'],'image' => "32x32/file_explorer.png",'link' => $this->_tpl_vars['T_FILE_LIST_LINK'],'options' => $this->_tpl_vars['T_FILES_LIST_OPTIONS'],'expand' => $this->_tpl_vars['T_POSITIONS_VISIBILITY']['moduleDigitalLibrary']), $this);?>

			</td></tr>
        <?php $this->_smarty_vars['capture']['moduleDigitalLibrary'] = ob_get_contents(); ob_end_clean(); ?>
    <?php endif; ?>

<?php if ($this->_tpl_vars['_admin_']): ?>
	<?php ob_start(); ?>
		<?php $_from = $this->_tpl_vars['T_CONTROL_PANEL_GROUPS_ORDER']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['grupo']):
?>
			<?php if (count($this->_tpl_vars['grupo']['itens']) > 0): ?>
				<section id="sectionOption-<?php echo $this->_tpl_vars['i']; ?>
" class="sectionOption">
					<h3><a href="#" title="<?php echo $this->_tpl_vars['grupo']['title']; ?>
"><?php echo $this->_tpl_vars['grupo']['title']; ?>
</a></h3>
					<nav>
						<ul>
							<?php $_from = $this->_tpl_vars['grupo']['itens']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['option']):
?>
								<li>
									<a href="<?php echo $this->_tpl_vars['option']['href']; ?>
" class="optionLinkImage optionLink-<?php echo $this->_tpl_vars['option']['class']; ?>
" title="<?php echo $this->_tpl_vars['option']['text']; ?>
"><span></span></a>
									<a href="<?php echo $this->_tpl_vars['option']['href']; ?>
" class="optionLinkText" title="<?php echo $this->_tpl_vars['option']['text']; ?>
"><?php echo $this->_tpl_vars['option']['text']; ?>
</a>
								</li>
							<?php endforeach; endif; unset($_from); ?>
						</ul>
					</nav>
				</section>
			<?php endif; ?>
		<?php endforeach; endif; unset($_from); ?>
	<?php $this->_smarty_vars['capture']['moduleIconFunctions'] = ob_get_contents(); ob_end_clean(); ?>
<?php else: ?>
	<?php if ($this->_tpl_vars['T_CURRENT_USER']->coreAccess['control_panel'] != 'hidden' && ( ! $this->_tpl_vars['_student_'] || ( $this->_tpl_vars['T_CURRENT_LESSON'] && $this->_tpl_vars['T_CURRENT_LESSON']->options['show_student_cpanel'] ) )): ?>
		<?php ob_start(); ?>
			<tr><td class = "moduleCell">
				<?php echo smarty_function_eF_template_printBlock(array('title' => @_OPTIONS,'columns' => 4,'links' => $this->_tpl_vars['T_CONTROL_PANEL_OPTIONS'],'image' => '32x32/options.png','expand' => $this->_tpl_vars['T_POSITIONS_VISIBILITY']['moduleIconFunctions'],'groups' => $this->_tpl_vars['T_CONTROL_PANEL_GROUPS']), $this);?>

			</td></tr>
		<?php $this->_smarty_vars['capture']['moduleIconFunctions'] = ob_get_contents(); ob_end_clean(); ?>
	<?php endif; ?>
<?php endif; ?>


<?php $_from = $this->_tpl_vars['T_INNERTABLE_MODULES']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['module_inner_tables_list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['module_inner_tables_list']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['moduleItem']):
        $this->_foreach['module_inner_tables_list']['iteration']++;
?>
	<?php ob_start(); ?> 		<tr><td class = "moduleCell">
                <?php if ($this->_tpl_vars['moduleItem']['smarty_file']): ?>
                    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['moduleItem']['smarty_file'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                <?php else: ?>
                    <?php echo $this->_tpl_vars['moduleItem']['html_code']; ?>

                <?php endif; ?>
            </td></tr>
        <?php $this->_smarty_vars['capture'][((is_array($_tmp=$this->_tpl_vars['key'])) ? $this->_run_mod_handler('replace', true, $_tmp, '_', "") : smarty_modifier_replace($_tmp, '_', ""))] = ob_get_contents(); ob_end_clean(); ?>
    <?php endforeach; endif; unset($_from); ?>

<?php endif; ?>

<?php ob_start(); ?>
	<?php if ($this->_tpl_vars['_student_'] || $this->_tpl_vars['_professor_']): ?>

		<tr><td class = "moduleCell">
				<table class = "horizontalBlock">
					<tr>
						<td>
							<!-- 
							<a class = "rightOption" href="javascript:void(0);" onclick="location='<?php echo $_SERVER['PHP_SELF']; ?>
?ctg=lessons';<?php if ($this->_tpl_vars['T_NO_HORIZONTAL_MENU']): ?>top.sideframe.hideAllLessonSpecific();<?php endif; ?>"><img src = "images/32x32/go_back.png" alt = "<?php echo @_CHANGELESSON; ?>
" title = "<?php echo @_CHANGELESSON; ?>
" class = "handle"></a>
							-->
							<span class = "leftOption"><?php echo $this->_tpl_vars['T_CURRENT_LESSON']->lesson['name']; ?>
</span>
							<?php $_from = $this->_tpl_vars['T_HEADER_OPTIONS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['header_options_list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['header_options_list']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
        $this->_foreach['header_options_list']['iteration']++;
?>
								<a class = "leftOption" href = "<?php echo $this->_tpl_vars['item']['href']; ?>
" target = "<?php echo $this->_tpl_vars['item']['target']; ?>
"><img src = "images/<?php echo $this->_tpl_vars['item']['image']; ?>
" alt = "<?php echo $this->_tpl_vars['item']['text']; ?>
" title = "<?php echo $this->_tpl_vars['item']['text']; ?>
" onclick = "<?php echo $this->_tpl_vars['item']['onClick']; ?>
" class = "handle"></a>
								<?php endforeach; endif; unset($_from); ?>
						</td></tr>
				</table>
			</td></tr>
	<?php endif; ?>
        <tr>
         <td class = "moduleCell">
    	<?php if ($this->_tpl_vars['_admin_']): ?>
    	   <ul id="control_panel_list">
    	<?php else: ?>
             <div id="sortableList">
                 <div style="float: right; width:49.5%;height: 100%;">
                    <ul class="sortable" id="secondlist" style="width:100%;">
        <?php endif; ?>
    					<?php $_from = $this->_tpl_vars['T_POSITIONS_SECOND']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['positions_first'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['positions_first']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['module']):
        $this->_foreach['positions_first']['iteration']++;
?>
							<li id="secondlist_<?php echo $this->_tpl_vars['module']; ?>
">
								<table class = "singleColumnData">
									<?php echo $this->_smarty_vars['capture'][$this->_tpl_vars['module']]; ?>

								</table>
							</li>
						<?php endforeach; endif; unset($_from); ?>
						
						
						
						<?php if (! in_array ( 'modulePersonalMessages' , $this->_tpl_vars['T_POSITIONS'] ) && $this->_smarty_vars['capture']['modulePersonalMessages']): ?>
							<li id="secondlist_modulePersonalMessages">
								<table class = "singleColumnData">
									<?php echo $this->_smarty_vars['capture']['modulePersonalMessages']; ?>

								</table>
							</li>
						<?php endif; ?>
						<?php if (! in_array ( 'moduleNewDirection' , $this->_tpl_vars['T_POSITIONS'] ) && $this->_smarty_vars['capture']['moduleNewDirection']): ?>
							<li id="secondlist_moduleNewDirection">
								<table class = "singleColumnData">
									<?php echo $this->_smarty_vars['capture']['moduleNewDirection']; ?>

								</table>
							</li>
						<?php endif; ?>
						
																		<?php if (! in_array ( 'moduleNewLesson' , $this->_tpl_vars['T_POSITIONS'] ) && $this->_smarty_vars['capture']['moduleNewLesson']): ?>
							<li id="secondlist_moduleNewLesson">
								<table class = "singleColumnData">
									<?php echo $this->_smarty_vars['capture']['moduleNewLesson']; ?>

								</table>
							</li>
						<?php endif; ?>

						<?php if (! in_array ( 'moduleNewsList' , $this->_tpl_vars['T_POSITIONS'] ) && $this->_smarty_vars['capture']['moduleNewsList']): ?>
							<li id="secondlist_moduleNewsList">
								<table class = "singleColumnData">
									<?php echo $this->_smarty_vars['capture']['moduleNewsList']; ?>

								</table>
							</li>
						<?php endif; ?>
						<?php if (! in_array ( 'moduleCalendar' , $this->_tpl_vars['T_POSITIONS'] ) && $this->_smarty_vars['capture']['moduleCalendar'] && $this->_tpl_vars['T_CONFIGURATION']['disable_calendar'] != 1): ?>
							<li id="secondlist_moduleCalendar">
								<table class = "singleColumnData">
									<?php echo $this->_smarty_vars['capture']['moduleCalendar']; ?>

								</table>
							</li>
						<?php endif; ?>
						<?php if (! in_array ( 'moduleForumList' , $this->_tpl_vars['T_POSITIONS'] ) && $this->_smarty_vars['capture']['moduleForumList']): ?>
							<li id="secondlist_moduleForumList">
								<table class = "singleColumnData">
									<?php echo $this->_smarty_vars['capture']['moduleForumList']; ?>

								</table>
							</li>
						<?php endif; ?>
						<?php if (! in_array ( 'modulePersonalMessagesList' , $this->_tpl_vars['T_POSITIONS'] ) && $this->_smarty_vars['capture']['modulePersonalMessagesList']): ?>
							<li id="secondlist_modulePersonalMessagesList">
								<table class = "singleColumnData">
									<?php echo $this->_smarty_vars['capture']['modulePersonalMessagesList']; ?>

								</table>
							</li>
						<?php endif; ?>
						<?php if (! in_array ( 'moduleDoneTests' , $this->_tpl_vars['T_POSITIONS'] ) && $this->_smarty_vars['capture']['moduleDoneTests']): ?>
							<li id="secondlist_moduleDoneTests">
								<table class = "singleColumnData">
									<?php echo $this->_smarty_vars['capture']['moduleDoneTests']; ?>

								</table>
							</li>
						<?php endif; ?>
						<?php if (! in_array ( 'moduleCommentsList' , $this->_tpl_vars['T_POSITIONS'] ) && $this->_smarty_vars['capture']['moduleCommentsList'] && $this->_tpl_vars['T_CONFIGURATION']['disable_comments'] != 1): ?>
							<li id="secondlist_moduleCommentsList">
								<table class = "singleColumnData">
									<?php echo $this->_smarty_vars['capture']['moduleCommentsList']; ?>

								</table>
							</li>
						<?php endif; ?>

						<?php if (! in_array ( 'moduleTimeline' , $this->_tpl_vars['T_POSITIONS'] ) && $this->_smarty_vars['capture']['moduleTimeline']): ?>
							<li id="secondlist_moduleTimeline">
								<table class = "singleColumnData">
									<?php echo $this->_smarty_vars['capture']['moduleTimeline']; ?>

								</table>
							</li>
						<?php endif; ?>
						<?php if (! in_array ( 'moduleProjectsList' , $this->_tpl_vars['T_POSITIONS'] ) && $this->_smarty_vars['capture']['moduleProjectsList'] && $this->_tpl_vars['T_CONFIGURATION']['disable_projects'] != 1): ?>
							<li id="firstlist_moduleProjectsList">
								<table class = "singleColumnData">
									<?php echo $this->_smarty_vars['capture']['moduleProjectsList']; ?>

								</table>
							</li>
						<?php endif; ?>
						<?php if (! in_array ( 'moduleDigitalLibrary' , $this->_tpl_vars['T_POSITIONS'] ) && $this->_smarty_vars['capture']['moduleDigitalLibrary']): ?>
							<li id="secondlist_moduleDigitalLibrary">
								<table class = "singleColumnData">
									<?php echo $this->_smarty_vars['capture']['moduleDigitalLibrary']; ?>

								</table>
							</li>
						<?php endif; ?>

												<?php $_from = $this->_tpl_vars['T_INNERTABLE_MODULES']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['module_inner_tables_list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['module_inner_tables_list']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['module']):
        $this->_foreach['module_inner_tables_list']['iteration']++;
?>
							<?php $this->assign('module_name', ((is_array($_tmp=$this->_tpl_vars['key'])) ? $this->_run_mod_handler('replace', true, $_tmp, '_', "") : smarty_modifier_replace($_tmp, '_', ""))); ?>
							<?php if (! in_array ( $this->_tpl_vars['module_name'] , $this->_tpl_vars['T_POSITIONS'] )): ?>
								<li id="secondlist_<?php echo $this->_tpl_vars['module_name']; ?>
">
									<table class = "singleColumnData">
										<?php echo $this->_smarty_vars['capture'][$this->_tpl_vars['module_name']]; ?>

									</table>
								</li>
							<?php endif; ?>
						<?php endforeach; endif; unset($_from); ?>
                <?php if (! $this->_tpl_vars['_admin_']): ?>
                        <li id = "second_empty" style = "display:none;"></li>
                    </ul>
                </div>
                <div style="width:50%; height:100%;">
                    <ul class="sortable" id="firstlist" style="width:100%;">
                <?php endif; ?>
						<?php $_from = $this->_tpl_vars['T_POSITIONS_FIRST']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['positions_first'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['positions_first']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['module']):
        $this->_foreach['positions_first']['iteration']++;
?>
							<li id="firstlist_<?php echo $this->_tpl_vars['module']; ?>
">
								<table class = "singleColumnData">
									<?php echo $this->_smarty_vars['capture'][$this->_tpl_vars['module']]; ?>

								</table>
							</li>
						<?php endforeach; endif; unset($_from); ?>
						<?php if (! in_array ( 'moduleIconFunctions' , $this->_tpl_vars['T_POSITIONS'] ) && $this->_smarty_vars['capture']['moduleIconFunctions']): ?>
                            <?php if ($this->_tpl_vars['_admin_']): ?>
    							<!-- Container -->
    							<section id="container">
    
    								<!-- Options sections -->
    								<div id="optionsSections">
    
    									<div class="navDiv navDivTop">
    										<?php echo $this->_smarty_vars['capture']['moduleIconFunctions']; ?>

    									</div>
    				
    								</div>
    								<!-- /end options sections -->
    							</section>
    							<!-- /end content -->
    						<?php else: ?>
                                <li id="firstlist_moduleIconFunctions">
                                    <table class = "singleColumnData">
                                        <?php echo $this->_smarty_vars['capture']['moduleIconFunctions']; ?>

                                    </table>
                                </li>
    						<?php endif; ?>
						<?php endif; ?>
						<?php if (! in_array ( 'moduleContentTree' , $this->_tpl_vars['T_POSITIONS'] ) && $this->_smarty_vars['capture']['moduleContentTree']): ?>
							<li id="firstlist_moduleContentTree">
								<table class = "singleColumnData">
									<?php echo $this->_smarty_vars['capture']['moduleContentTree']; ?>

								</table>
							</li>
						<?php endif; ?>
						<?php if (! in_array ( 'moduleLessonSettings' , $this->_tpl_vars['T_POSITIONS'] ) && $this->_smarty_vars['capture']['moduleLessonSettings']): ?>
							<li id="firstlist_moduleLessonSettings">
								<table class = "singleColumnData">
									<?php echo $this->_smarty_vars['capture']['moduleLessonSettings']; ?>

								</table>
							</li>
						<?php endif; ?>
                <?php if ($this->_tpl_vars['_admin_']): ?>
                   </ul>
                <?php else: ?>
                        <li id = "first_empty" style = "display:none;"></li>
                    </ul>
                </div>
            </div>
                <?php endif; ?>
        </td>
    </tr>
<?php $this->_smarty_vars['capture']['moduleControlPanel'] = ob_get_contents(); ob_end_clean(); ?>