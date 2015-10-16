<?php
abstract class SysclassModule extends BaseSysclassModule
{
    protected $module_id = null;
    protected $module_folder = null;
    protected $module_request;

    public function init($url = null, $method = null, $format = null, $root=NULL, $basePath="", $urlMatch = null)
    {
        parent::init($url, $method, $format, $root, $basePath, $urlMatch);

        $this->createContext($this->module_id);
    }

    /**
     * [ add a description ]
     * @Get("/view")
     */
    public function viewPage()
    {
        $depinject = Phalcon\DI::getDefault();
        if ($depinject->get("acl")->isUserAllowed(null, $this->module_id, "View")) {
            $this->createClientContext("view");
            $this->display($this->template);
        } else {
            $this->redirect($this->getSystemUrl('home'), "", 401);
        }
    }

    /**
     * [ add a description ]
     *
     * @url GET /add
     */
    public function addPage()
    {
        $depinject = Phalcon\DI::getDefault();
        if ($depinject->get("acl")->isUserAllowed(null, $this->module_id, "Create")) {
            if (!$this->createClientContext("add")) {
                $this->entryPointNotFoundError($this->getSystemUrl('home'));
            }
            $this->display($this->template);

        } else {
            $this->redirect($this->getSystemUrl('home'), "", 401);
        }
    }

    /**
     * [ add a description ]
     *
     * @url GET /edit/:id
     */
    public function editPage($id)
    {
        $depinject = Phalcon\DI::getDefault();
        if ($depinject->get("acl")->isUserAllowed(null, $this->module_id, "Edit")) {
            $this->createClientContext("edit", array('entity_id' => $id));
            $this->display($this->template);
        } else {
            $this->redirect($this->getSystemUrl('home'), "", 401);
        }
    }


}
