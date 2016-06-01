<?php
namespace Sysclass\Modules\Grades;
/**
 * Module Class File
 * @filesource
 */
use Sysclass\Models\Courses\Grades\Grade,
    Sysclass\Models\Courses\Grades\Range;
/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 */
/**
 * @RoutePrefix("/module/grades")
 */
class GradesModule extends \SysclassModule implements \ILinkable, \IBreadcrumbable, \IActionable
{
    //protected $_modelRoute = "grades";

    /* ILinkable */
    public function getLinks() {
        if ($this->acl->isUserAllowed(null, $this->module_id, "View")) {

            $count = Grade::count("active = 1");

            return array(
                'content' => array(
                    array(
                        'count' => $count,
                        'text'  => $this->translate->translate('Grades'),
                        'icon'  => 'fa fa-cogs',
                        'link'  => $this->getBasePath() . 'view'
                    )
                )
            );
        }
    }

    /* IBreadcrumbable */
    public function getBreadcrumb() {
        $breadcrumbs = array(
            array(
                'icon'  => 'fa fa-home',
                'link'  => $this->getSystemUrl('home'),
                'text'  => $this->translate->translate("Home")
            )
        );

        $request = $this->getMatchedUrl();
        switch($request) {
            case "view" : {
                $breadcrumbs[] = array(
                    'icon'  => 'fa fa-briefcase',
                    'link'  => $this->getBasePath() . "view",
                    'text'  => $this->translate->translate("Grades")
                );
                //$breadcrumbs[] = array('text'   => $this->translate->translate("View"));
                break;
            }
            case "add" : {
                $breadcrumbs[] = array(
                    'icon'  => 'fa fa-briefcase',
                    'link'  => $this->getBasePath() . "view",
                    'text'  => $this->translate->translate("Grades")
                );
                $breadcrumbs[] = array('text'   => $this->translate->translate("New Grade Rule"));
                break;
            }
            case "edit/:id" : {
                $breadcrumbs[] = array(
                    'icon'  => 'fa fa-briefcase',
                    'link'  => $this->getBasePath() . "view",
                    'text'  => $this->translate->translate("Grades")
                );
                $breadcrumbs[] = array('text'   => $this->translate->translate("Edit Grade Rule"));
                break;
            }
        }
        return $breadcrumbs;
    }

    /* IActionable */
    public function getActions() {
        $request = $this->getMatchedUrl();

        $actions = array(
            'view'  => array(
                array(
                    'text'      => $this->translate->translate('New Grade Rule'),
                    'link'      => $this->getBasePath() . "add",
                    'class'     => "btn-primary",
                    'icon'      => 'icon-plus'
                )
            )
        );

        return $actions[$request];
    }

    /** 
     * @todo MOVE THIS FUNCTION TO THE MODEL
     */
    public function afterModelCreate($evt, $model, $data) {
        if (array_key_exists('ranges', $data) && is_array($data['ranges']) ) {
            foreach($data['ranges'] as $grade) {
                $range = new Range();
                $range->assign($grade);
                $range->grade_id = $model->id;
                $range->save();
            }
        }
    }

    /** 
     * @todo MOVE THIS FUNCTION TO THE MODEL
     */
    public function afterModelUpdate($evt, $model, $data) {
        if (array_key_exists('ranges', $data) && is_array($data['ranges']) ) {
            $model->getRanges()->delete();
            
            foreach($data['ranges'] as $grade) {
                $range = new Range();
                $range->assign($grade);
                $range->grade_id = $model->id;
                $range->save();
            }
        }
    }

}
