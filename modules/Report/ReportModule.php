<?php
namespace Sysclass\Modules\Report;
/**
 * Module Class File
 * @filesource
 */
use Sysclass\Models\Reports\Report;
/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 */
/**
 * @RoutePrefix("/module/report")
 */
class ReportModule extends \SysclassModule implements \ILinkable, \IBreadcrumbable, \IActionable
{

    /* ILinkable */
    public function getLinks() {
        //$data = $this->getItemsRequest();
        
        if ($this->acl->isUserAllowed(null, "Report", "View")) {

            $total = Report::count("active = 1");
            $report = Report::FindFirst();

            if ($total == 1) {
                $report = Report::FindFirst("active = 1");
                $link = 'view/' . $report->id;
            } elseif ($total > 1) {
                $link = 'manage';
            } else {
                return false;
            }

            return array(
                'administration' => array(
                    array(
                        'count' => $total,
                        'text'  => $this->translate->translate('Reports'),
                        'icon'  => 'fa fa-cogs',
                        'link'  => $this->getBasePath() . $link
                    )
                )
            );
        }
    }

    /* IBreadcrumbable */
    public function getBreadcrumb() {
        $breadcrumbs = array(
            array(
                'icon'  => 'icon-home',
                'link'  => $this->getSystemUrl('home'),
                'text'  => $this->translate->translate("Home")
            )
        );

        $request = $this->getMatchedUrl();
        switch($request) {
            case "view" : {
                $breadcrumbs[] = array(
                    'icon'  => 'fa fa-list',
                    'link'  => $this->getBasePath() . "view",
                    'text'  => $this->translate->translate("Enrollment")
                );
                return $breadcrumbs;
                break;
            }
            case "add" : {
                $breadcrumbs[] = array(
                    'icon'  => 'fa fa-list',
                    'link'  => $this->getBasePath() . "view",
                    'text'  => $this->translate->translate("Enrollment")
                );
                $breadcrumbs[] = array(
                    'text'   => $this->translate->translate("New Enrollment Guideline"),
                    'icon'  => 'fa fa-add-circle',
                    );
                return $breadcrumbs;
                break;
            }
            case "edit/{id}" : {
                $breadcrumbs[] = array(
                    'icon'  => 'fa fa-list',
                    'link'  => $this->getBasePath() . "view",
                    'text'  => $this->translate->translate("Enrollment")
                );
                $breadcrumbs[] = array(
                    'text'   => $this->translate->translate("Edit Enrollment Guideline"),
                    'icon' => 'fa fa-pencil'
                );
                return $breadcrumbs;
                break;
            }
        }
    }

    /* IActionable */
    public function getActions() {
        $request = $this->getMatchedUrl();

        $actions = array(
            'view'  => array(
                array(
                    'text'      => $this->translate->translate('New Enrollment Guideline'),
                    'link'      => $this->getBasePath() . "add",
                    'class'     => "btn-primary",
                    'icon'      => 'icon-plus'
                )/*,
                array(
                    'separator' => true,
                ),
                array(
                    'text'      => 'Add New 2',
                    'link'      => $this->getBasePath() . "add",
                    //'class'       => "btn-primary",
                    //'icon'      => 'icon-plus'
                )*/
            )
        );

        return $actions[$request];
    }

    public function getDatatableItemOptions() {
        if ($this->_args['model'] == 'courses') {
            return array(
                'enroll' => array(
                    'icon'  => 'fa fa-users',
                    'link'  => 'javascript:void(0);',
                    'class' => 'btn-sm btn-primary datatable-actionable',
                ),
                'remove'  => array(
                    'icon'  => 'fa fa-remove',
                    'class' => 'btn-sm btn-danger'
                )
            );
        }
        return parent::getDatatableItemOptions();
    }

    /**
     * [ add a description ]
     *
     * @Get("/manage")
     * @allow(resource=report, action=edit)
     */

    public function managePage()
    {
        print_r($this->getConfig('reports'));
        exit;
        // MUST SHOW ALL AVALIABLE CALENDARS TYPES
        //$this->createClientContext("manage");
        if (!$this->createClientContext("manage")) {
            $this->entryPointNotFoundError($this->getSystemUrl('home'));
        }

        $this->display($this->template);
    }

    /**
     * [ add a description ]
     *
     * @Get("/view/{identifier}")
     * @allow(resource=report, action=view)
     */

    public function viewReportPage($identifier)
    {

        $report = Report::findFirstById($identifier);

        if (!$report) {
            $this->entryPointNotFoundError($this->getBasePath() . "manage");
        }

        $datasources = $this->getConfig('reports');

        if (array_key_exists($report->datasource, $datasources)) {
            $report_options = $report->mergeOptions($datasources[$report->datasource]);
        } else {
            $report_options = json_decode($report->options);
        }

        if (!is_array($report_options)) {
            return $this->invalidRequestError();
        }

        // LOAD CONDITIONS, FILTER, AND FILEDS 
        $context = array(
            'datatable_fields' => array()
        );
        // GENERATE DATATABLE CONTEXT
        foreach($report_options['fields'] as $field) {
            $context['datatable_fields'][] = $report_options['datafields'][$field];
        }

        $this->report_id = $this->random->uuid(); 

        $this->setCache("report_config/" . $this->report_id , $report_options);
        $context['model-id'] = $this->report_id;

        

        $this->createClientContext("view", $context, 'view/{identifier}');
        
        $this->putItem('module_context', $context);

        $this->putItem('page_title', $this->translate->translate($report->name));
        $this->putItem('page_subtitle', $this->translate->translate($report->description));


        $this->display($this->template);
    }

    /**
     * [ add a description ]
     * 
     * @Get("/items/{model}")
     * @Get("/items/{model}/{type}")
     * @Get("/items/{model}/{type}/{filter}")
     */
    public function getItemsRequest($model, $type, $filter) {
        $cache = $this->getCache("report_config/" . $model);

        if (is_null($cache)) {
            return parent::getItemsRequest($model, $type, $filter);
        } else {
            return parent::getItemsRequest($cache['model'], $type, $filter, $cache['fields']);
        }
    }

}
