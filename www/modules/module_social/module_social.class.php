<?php

class module_social extends MagesterExtendedModule
{
    // Mandatory functions required for module function
    public function getName()
    {
        return "SOCIAL";
    }

    public function getPermittedRoles()
    {
        return array("professor","student");
    }
	/*
	 public function isLessonModule()
	 {
		return true;
	 }
	*/

    public function getCenterLinkInfo()
    {
        $currentUser = $this -> getCurrentUser();
        if ($currentUser -> getType() == "administrator") {
            return array('title' => _YOUTUBE,
                         'image' => $this -> moduleBaseDir . 'images/youtube32.png',
                         'link' => $this -> moduleBaseUrl);
        }
    }

    public function loadSocialNetworkBlock($blockIndex = null)
    {
     	$currentUser = $this -> getCurrentUser();
        if ($currentUser -> user['user_type'] == "student") {
            $smarty = $this -> getSmartyVar();
            $smarty -> assign("T_SOCIAL_BASEDIR" , $this -> moduleBaseDir);
            $smarty -> assign("T_SOCIAL_BASEURL" , $this -> moduleBaseUrl);
            $smarty -> assign("T_SOCIAL_BASELINK" , $this -> moduleBaseLink);

			$options[] = array('text' => _TWITTER, 'image' => $this -> moduleBaseLink."images/twitter.png", 'href' => 'javascript: void(0);');
			$options[] = array('text' => _ORKUT, 'image' => $this -> moduleBaseLink."images/orkut.png", 'href' => 'javascript: void(0);');
			$options[] = array('text' => _YOUTUBE, 'image' => $this -> moduleBaseLink."images/youtube.png", 'href' => 'javascript: void(0);');

			$smarty -> assign("T_SOCIAL_OPTIONS", $options);
			$smarty -> assign("T_SOCIAL_OPTIONS_SIZE", count($options));

			$this->getParent()->appendTemplate(array(
				'title'		=> _SOCIALNETWORKS,
				//'template'	=> $this -> moduleBaseDir . "module_InnerTable.tpl",
				'columns' 	=> count($options),
				'links' 	=> $options,
				'contentclass'	=> ''
			), $blockIndex);

            return true;
        } else {
            return false;
        }

    }

    /* CURRENT-LESSON ATTACHED MODULE PAGES */
    public function getDashboardModule()
    {
		return true;
    }

    public function getDashboardSmartyTpl()
    {
    	$currentUser = $this->getCurrentUser();
   		if ($currentUser -> user['user_type'] == "student") {
            $smarty = $this -> getSmartyVar();
            $smarty -> assign("T_SOCIAL_BASEDIR" , $this -> moduleBaseDir);
            $smarty -> assign("T_SOCIAL_BASEURL" , $this -> moduleBaseUrl);
            $smarty -> assign("T_SOCIAL_BASELINK" , $this -> moduleBaseLink);

			$options[] = array('text' => _TWITTER, 'image' => $this -> moduleBaseLink."images/twitter.png", 'href' => 'javascript: void(0);');
			$options[] = array('text' => _ORKUT, 'image' => $this -> moduleBaseLink."images/orkut.png", 'href' => 'javascript: void(0);');
			$options[] = array('text' => _YOUTUBE, 'image' => $this -> moduleBaseLink."images/youtube.png", 'href' => 'javascript: void(0);');

			$smarty -> assign("T_SOCIAL_OPTIONS", $options);
			$smarty -> assign("T_SOCIAL_OPTIONS_SIZE", count($options));

            return $this->moduleBaseDir . 'module_InnerTable.tpl';
        } else {
            return false;
        }
    }
}
