<?php
namespace Sysclass\Controllers;

class DashboardController extends \AbstractSysclassController
{
	// ABSTRACT - MUST IMPLEMENT METHODS!
    // 
    /**
     * * Create login and reset password forms
     * @Get("/dashboard")
     * @Get("/dashboard/{dashboard_id}")
     * @Get("/dashboard/{dashboard_id}/{clear}")
     * 
     */
	public function dashboardPage($dashboard_id, $clear)
	{
        $currentUser = $this->getCurrentUser(true);

        //$this->putCss("css/components");
        $this->putScript("plugins/jquery.isonscreen/jquery.isonscreen");

        // CHECK IF USER EXISTS, AND IF THIS MATCH CURRENT USER TYPE
        $dashboardManager = $this->module("dashboard");

        $dashboards = $currentUser->getDashboards();

        if (in_array($dashboard_id, $dashboards) && $dashboardManager->layoutExists($dashboard_id)) {        
            $currentUser->dashboard_id = $dashboard_id;
            $currentUser->save();
        } elseif (in_array($currentUser->dashboard_id, $dashboards) && $dashboardManager->layoutExists($currentUser->dashboard_id)) {
            $dashboard_id = $currentUser->dashboard_id;
        } else {

            if ($ignore_key = array_search($currentUser->dashboard_id, $dashboards)) {
                unset($dashboards[$ignore_key]);    
            }

            reset($dashboards);
            do {
                $dashboard_id = current($dashboards);
            } while (!$dashboardManager->layoutExists($dashboard_id) && next($dashboards));

            if (!$dashboardManager->layoutExists($dashboard_id)) {
                $dashboard_id = 'default';
            }

            $currentUser->dashboard_id = $dashboard_id;
            $currentUser->save();
        }

        $pageLayout = $dashboardManager->loadLayout($dashboard_id, ($clear == "clear"));

        //var_dump($pageLayout);


        $widgets = $dashboardManager->getPageWidgets();

        //var_dump($widgets);
        //exit;


        foreach($widgets as $key => $widget) {
            $this->addWidget($widget[0], $widget[1], $widget[2]);
        }

        $this->putItem("page_layout", $pageLayout);

//        $this->putBlock("institution.social-gadgets");
//        $this->putBlock("chat.quick-sidebar");

        $widgets = array_slice($widgets, 0, 1);

        //$this->putBlock("institution.social-gadgets");
        parent::display('pages/dashboard/default.tpl');
	}

}
