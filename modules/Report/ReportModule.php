<?php
namespace Sysclass\Modules\Report;
/**
 * Module Class File
 * @filesource
 */
use Sysclass\Models\Reports\Report,
Sysclass\Models\Reports\ReportDatasource;
/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 */
/**
 * @RoutePrefix("/module/report")
 */
class ReportModule extends \SysclassModule implements \ILinkable, \IBreadcrumbable, \IActionable, \IBlockProvider
{
    /* ILinkable */
    public function getLinks() {
        //$data = $this->getItemsRequest();
        
        if ($this->acl->isUserAllowed(null, "Report", "View")) {

            $total = Report::count("active = 1");
            $report = Report::FindFirst();

            //if ($total == 1) {
            //    $report = Report::FindFirst("active = 1");
            //    $link = 'show/' . $report->id;
            //} elseif ($total > 1) {
                $link = 'view';
            //} else {
            //    return false;
            //}

            return array(
                'administration' => array(
                    array(
                        'count' => $total,
                        'text'  => $this->translate->translate('Reports'),
                        'icon'  => 'fa fa-table',
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
                'icon'  => 'fa fa-home',
                'link'  => $this->getSystemUrl('home'),
                'text'  => $this->translate->translate("Home")
            )
        );

        $request = $this->getMatchedUrl();
        switch($request) {
            case "view" : {
                $breadcrumbs[] = array(
                    'icon'  => 'fa fa-table',
                    'link'  => $this->getBasePath() . "view",
                    'text'  => $this->translate->translate("Reports")
                );
                return $breadcrumbs;
                break;
            }
            case "add" : {
                $breadcrumbs[] = array(
                    'icon'  => 'fa fa-table',
                    'link'  => $this->getBasePath() . "view",
                    'text'  => $this->translate->translate("Reports")
                );
                $breadcrumbs[] = array(
                    'text'   => $this->translate->translate("New Report"),
                    'icon'  => 'fa fa-add-circle',
                    );
                return $breadcrumbs;
                break;
            }
            case "edit/{id}" : {
                $breadcrumbs[] = array(
                    'icon'  => 'fa fa-table',
                    'link'  => $this->getBasePath() . "view",
                    'text'  => $this->translate->translate("Reports")
                );
                $breadcrumbs[] = array(
                    'text'   => $this->translate->translate("Edit Report"),
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
                    'text'      => $this->translate->translate('New Report'),
                    'link'      => $this->getBasePath() . "add",
                    'class'     => "btn-primary",
                    'icon'      => 'fa fa-plus-square'
                )
            )
        );

        return $actions[$request];
    }

    // IBlockProvider
    public function registerBlocks() {
        return array(
            'report.definition' => function($data, $self) {
                $self->putComponent("sprintf");
                $self->putComponent("select2");
                $self->putComponent("datatables");
                $self->putComponent("jquery-builder");


                // CREATE BLOCK CONTEXT
                $block_context = $self->getConfig("blocks\\group.definition\\context");

                $self->putItem("group_definition_static_context", $block_context['static']);
                $self->putItem("group_definition_dynamic_context", $block_context['dynamic']);

                $self->putSectionTemplate("foot", "dialogs/field.add");

                return true;
            }
        );
    }

    /**
     * [ add a description ]
     *
     * @Get("/add")
     */
    public function addPage()
    {
        $datasources = ReportDatasource::find();
        $this->putItem("datasources", $datasources->toArray());

        parent::addPage($id);
    }

    /**
     * [ add a description ]
     *
     * @Get("/edit/{id}")
     */
    public function editPage($id)
    {
        $datasources = ReportDatasource::find();
        $this->putItem("datasources", $datasources->toArray());

        parent::editPage($id);
    }

    /**
     * Loads defaults filters for desired datasource_id
     *
     * @Get("/datasource/{name}")
     */
    public function filterRequest($name)
    {   
        $key = sprintf("reports\\%s", $name);
        $this->response->setJsonContent($this->getConfig($key));
        return true;
    }

    

    public function getDatatableItemOptions($model = "me") {
        if ($model == 'me') {
            $options['show']  = array(
                'icon'  => 'fa fa-eye',
                'link'  => $baseLink . 'show/%id$s',
                'class' => 'btn-sm btn-success tooltips',
                'attrs' => array(
                    'data-original-title' => 'Show'
                )
            );

            $base_options = parent::getDatatableItemOptions($model);

            return array_merge($options, $base_options);
        }
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
        return parent::getDatatableItemOptions($model);
    }

    /**
     * [ add a description ]
     *
     * @Get("/show/{identifier}")
     * @allow(resource=report, action=view)
     */

    public function showReportPage($identifier)
    {
        $report = Report::findFirstById($identifier);

        if (!$report) {
            $this->entryPointNotFoundError($this->getBasePath() . "manage");
        }

        //$datasources = $this->getConfig('reports');
        /*
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
        */
        $context = [
            'entity_id' =>$identifier
        ];
        

        $this->createClientContext("show", $context, 'show/{identifier}');
        
        $this->putItem('module_context', $context);

        $this->putItem('page_title', $this->translate->translate($report->name));

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
