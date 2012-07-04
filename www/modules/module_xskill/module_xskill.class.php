<?php

class module_xskill extends MagesterExtendedModule {
    // CORE MODULE FUNCTIONS
    public function getName() {
        return "XSKILL";
    }
    public function getPermittedRoles() {
        return array("administrator");
    }
    public function isLessonModule() {
        return true;
    }
    
    /* MAIN-INDEPENDENT MODULE INFO, PAGES, TEMPLATES, ETC... */
    
    /* BLOCK FUNCTIONS */
    /* ACTIONS FUNCTIONS */

    /* HOOK ACTIONS FUNCTIONS */  
    /* DATA MODEL FUNCTIONS /*/
    public function loadCourseSkills($course_id) {
    	if (eF_checkParameter($course_id, "id")) {
	    	$course_skills = eF_getTableData(
	    		"module_xskill_course2skills c2skl LEFT JOIN module_xskill skl ON c2skl.skill_id = skl.id",
	    		"skl.id, c2skl.course_id, skl.name, skl.description, c2skl.require, c2skl.provide",
	    		sprintf("c2skl.course_id = %d", $course_id)
			);
			$result = array(
				'require'	=> array(), 
				'provide'	=> array() 
			);
			foreach($course_skills as $skill) {
				if ($skill['require'] == 1 || $skill['provide'] != 1) {
					$result['require'][] = $skill;
				} else {
					$result['provide'][] = $skill;
				}
			}
			return $result;
	    }
    }
    public function loadUserSkills($user_id) {
    	if (eF_checkParameter($user_id, "id")) {
	    	$users_skills = eF_getTableData(
	    		"module_xskill_users u2skl LEFT JOIN module_xskill skl ON u2skl.skill_id = skl.id",
	    		"skl.id, u2skl.user_id, skl.name, skl.description",
	    		sprintf("u2skl.user_id = %d", $user_id)
			);
			return $users_skills;
	    }
    }
    
}
?>