<?php
class module_xforum extends MagesterExtendedModule
{
	const GET_COURSE_LAST_ENTRY = "get_course_last_entry";
    // CORE MODULE FUNCTIONS
    public function getName()
    {
        return "XFORUM";
    }
    public function getPermittedRoles()
    {
        return array("student");
    }
    public function isLessonModule()
    {
        return false;
    }

    /* MAIN-INDEPENDENT MODULE INFO, PAGES, TEMPLATES, ETC... */
    public function getCourseDashboardLinkInfo()
    {
		return array(
			'title' 		=> __XFORUM_NAME,
        	'image'			=> "images/others/transparent.gif",
			'image_class'	=> "sprite32 sprite32-forum",
            'link'  		=> $this -> moduleBaseUrl . "&action=" . self::GET_COURSE_LAST_ENTRY
		);
    }
    public function getModuleData()
    {
    	$this->addModuleData('course_entry_limit', 5);
    	return parent::getModuleData();
    }

    /* ACTIONS FUNCTIONS */
    public function getCourseLastEntryAction()
    {
    	// GET LAST MESSAGES FROM LESSON
    	$smarty = $this->getSmartyVar();
    	if (!$this->getEditedLesson()) {
    		return false;
    	}
    	$currentLesson = $this->getEditedLesson();

    	$moduleData = $this->getModuleData();

    	$forum_messages =
    		eF_getTableData(
    			"f_messages fm JOIN f_topics ft JOIN f_forums ff LEFT OUTER JOIN lessons l ON ff.lessons_ID = l.id",
    			"fm.title, fm.id, ft.id as topic_id, fm.users_LOGIN, fm.timestamp, l.name as lessons_name, lessons_id as show_lessons_id",
    			"ft.f_forums_ID=ff.id AND fm.f_topics_ID=ft.id AND ff.lessons_ID = '".$currentLesson -> lesson['id']."'",
    			"fm.timestamp desc LIMIT " . $moduleData['course_entry_limit']);

    	//var_dump($forum_messages);
    	//exit;

    	$smarty -> assign("T_XFORUM_MESSAGES", $forum_messages);
    	$smarty -> assign("T_XFORUM_EDIT_LESSON", $currentLesson->lesson);
    	$smarty -> assign("T_XFORUM_MESSAGE_LIMIT", $moduleData['course_entry_limit']);
/*
    	var_dump($currentLesson);
    	exit.
*/
		if ($_GET['output'] == 'innerhtml') {
			$result = $smarty -> fetch($this -> moduleBaseDir . "templates/actions/" . $this->getCurrentAction() . ".tpl");
			echo $result;
			exit;
        }
    }
    /* HOOK ACTIONS FUNCTIONS */

    /* DATA MODEL FUNCTIONS /*/
}
