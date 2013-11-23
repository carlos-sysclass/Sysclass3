<?php 
class NewsModule extends SysclassModule implements IWidgetContainer, ISummarizable
{
    public function getSummary() {
        $data = $this->dataAction();
        return array(
            'type'  => 'primary',
            'count' => count($data),
            'text'  => self::$t->translate('Announcements'),
            'link'  => array(
                'text'  => self::$t->translate('View'),
                'link'  => $this->getBasePath() . 'all'
            )
        );
    }

    public function getWidgets() {
        $this->putModuleScript("news");
        
        return array(
            'news.latest' => array(
                'type'      => 'news', // USED BY JS SUBMODULE REFERENCE, REQUIRED IF THE WIDGET HAS A JS MODULE
                'id'        => 'news-widget',
                'title'     => self::$t->translate('Announcements'),
                'template'  => $this->template("news.widget"),
                'icon'      => 'bell',
                'box'       => 'dark-blue',
                'tools'		=> array(
                    'search'        => true,
                	'reload'	    => 'javascript:void(0);',
                    'collapse'      => true,
                    'fullscreen'    => true,
                    'remove'        => true
                )
            )
        );
    }
    /**
     * Module Entry Point
     *
     * @url GET /data
     */
    public function dataAction()
    {
        $currentUser    = self::$current_user;

        # Carrega noticias da ultima licao selecionada
        $news = news :: getNews(0, false) /*+ news :: getNews($_SESSION['s_lessons_ID'], false)*/;

        # Filtra comunicado pela classe do aluno
        $userClasses = $this->_getTableDataFlat(
            "users_to_courses",
            "classe_id",
            sprintf("users_LOGIN = '%s'", $currentUser->user['login'])
        );
        /*
        $xentifyModule = $this->loadModule("xentify");
        $user = $this->getCurrentUser();
        */
        foreach ($news as $key => $noticia) {
            if ( !in_array( $noticia['classe_id'], $userClasses['classe_id'] ) && $noticia['classe_id']!=0 ) {
                unset($news[$key]);
            //} elseif ($ajax && $noticia['classe_id']==0) {
            //    unset($news[$key]);
            }
            /*
            if (!$xentifyModule->isUserInScope($user, $noticia['xscope_id'], $noticia['xentify_id'])) {
                unset($news[$key]);
            }
            */
        }
        return array_values($news);
    }

}
