<?php
/**
 * Module Class File
 * @filesource
 */
namespace Sysclass\Modules\Institution;
/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 */
use Sysclass\Models\Organizations\Organization;
/**
 * @RoutePrefix("/module/institution")
 */
class InstitutionModule extends \SysclassModule implements \IWidgetContainer, \ILinkable, \IBreadcrumbable, \IBlockProvider
{
    // IBlockProvider
    public function registerBlocks() {
        return array(
            'institution.social-gadgets' => function($data, $self) {
                //$this->putComponent("modal");
                //$this->putModuleScript("dialog.permission");
                //$this->putSectionTemplate(null, "blocks/permission");
                $self->putSectionTemplate("bottom", "blocks/social-gadgets");

                return true;

            }
        );
    }

    public function getWidgets($widgetsIndexes = array())
    {

        $itemsData = $this->model("institution")->getItem(1);

        //exit;

        //$this->putItem("institution", $itemsData);

        if (in_array('institution.overview', $widgetsIndexes)) {
            //$this->putModuleScript("widget.institution");

        	return array(
        		'institution.overview' => array(
       				//'title' 	=> 'User Overview',
                    'id'        => 'institution-widget',
       				'template'	=> $this->template("widgets/overview"),
                    'panel'     => true,
                    'data' => $itemsData
        		)
        	);
        }
    }
    /* ILinkable */
    public function getLinks() {
        //$data = $this->getItemsRequest();
        
        if ($this->acl->isUserAllowed(null, "Institution", "View")) {

            $items = $this->model("institution")->addFilter(array(
                'active'    => true
            ))->getItems();
            //$items = $this->module("permission")->checkRules($itemsData, "institution", 'permission_access_mode');

            return array(
                'administration' => array(
                    array(
                        'count' => count($items),
                        'text'  => $this->translate->translate('Organization'),
                        'icon'  => 'fa fa-university',
                        'link'  => $this->getBasePath() . 'edit/1'
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
            ),
            array(
                'icon'  => 'fa fa-university',
                'link'  => $this->getBasePath() . "edit/1",
                'text'  => $this->translate->translate("Organizations")
            )
        );

        $request = $this->getMatchedUrl();
        switch($request) {
            case "view" : {
                $breadcrumbs[] = array('text'   => $this->translate->translate("View"));
                break;
            }
            case "add" : {
                $breadcrumbs[] = array('text'   => $this->translate->translate("New Organization"));
                break;
            }
            case "edit/{id}" : {
                $breadcrumbs[] = array('text'   => $this->translate->translate("Edit Organization"));
                break;
            }
        }
        return $breadcrumbs;
    }

    /**
     * [ add a description ]
     *
     * @Get("/item/me/{id}")
     */
    /*
    public function getItemRequest($id) {

        $editItem = $this->model("institution")->getItem($id);
        Organization::
        // TODO CHECK IF CURRENT USER CAN VIEW THE NEWS

        //$editItem['logo'] = $this->model("dropbox")->getItem($editItem['logo_id']);
        return $editItem;
    }
    */
    /**
     * [ add a description ]
     *
     * @url POST /item/me
     */
    public function addItemRequest($id)
    {
        return $this->invalidRequestError($this->translate->translate("There's no multi-organization support yet!"), "error");

        if ($userData = $this->getCurrentUser()) {
            $data = $this->getHttpData(func_get_args());

            $itemModel = $this->model("institution");
            $data['login'] = $userData['login'];

            if (!empty($data['website']) && !preg_match('/(http[s]?:\/\/)/', $data['website']))
            {
                $data['website'] = 'http://' . $data['website'];
            }

            /*if (!preg_match('/(https:\/\/)/', $data['facebook']) && !preg_match('/(http:\/\/)/', $data['facebook']))
            {
                $data['facebook'] = 'https://' . $data['facebook'];
            }
            */

            if (($data['id'] = $itemModel->addItem($data)) !== FALSE) {
                return $this->createRedirectResponse(
                    $this->getBasePath() . "edit/" . $data['id'],
                    $this->translate->translate("Institution created with success"),
                    "success"
                );
            } else {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                return $this->invalidRequestError("A problem ocurred when tried to save you data. Please try again.", "error");
            }
        } else {
            return $this->notAuthenticatedError();
        }
    }
    /**
     * [ add a description ]
     *
     * @Put("/item/me/{id}")
     */
    /*
    public function setItemRequest($id)
    {
        if ($userData = $this->getCurrentUser()) {
            $data = $this->getHttpData(func_get_args());

            $itemModel = $this->model("institution");

            if (!empty($data['website']) && !preg_match('/(http[s]?:\/\/)/', $data['website']))
            {
                $data['website'] = 'http://' . $data['website'];
            }

            if ($itemModel->setItem($data, $id) !== FALSE) {
                $response = $this->createAdviseResponse($this->translate->translate("Organization updated with success"), "success");
                return array_merge($response, $data);
            } else {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                return $this->invalidRequestError("A problem ocurred when tried to save you data. Please try again.", "error");
            }
        } else {
            return $this->notAuthenticatedError();
        }
    }
    */

    /**
     * [ add a description ]
     *
     * @Delete("/item/me/{id}")
     */
    public function deleteItemRequest($id)
    {
        return $this->invalidRequestError($this->translate->translate("You can't delete the last institution in the system!"), "error");

        if ($userData = $this->getCurrentUser()) {
            $data = $this->getHttpData(func_get_args());

            $itemModel = $this->model("institution");
            // TODO Create some  way to make check if this entity can be removed!
            if ($itemModel->deleteItem($id) !== FALSE) {
                $response = $this->createAdviseResponse($this->translate->translate("Institution removed with success"), "success");
                return $response;
            } else {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                return $this->invalidRequestError("A problem ocurred when tried to save you data. Please try again.", "error");
            }
        } else {
            return $this->notAuthenticatedError();
        }
    }

    /**
     * Get all news visible to the current user
     *
     * @Get("/items/me")
     * @Get("/items/me/{type}")
     */
    public function getItemsRequest($type)
    {
        $currentUser    = $this->getCurrentUser(true);
        $dropOnEmpty = !($currentUser->getType() == 'administrator' && $currentUser->user['user_types_ID'] == 0);

        $itemsCollection = $this->model("institution");
        $itemsData = $itemsCollection->getItems();
        $items = $this->module("permission")->checkRules($itemsData, "institution", 'permission_access_mode');

        if ($type === 'combo') {
            $q = $_GET['q'];

            $items = $itemsCollection->filterCollection($items, $q);

            foreach($items as $item) {
                // @todo Group by course
                $result[] = array(
                    'id'    => intval($item['id']),
                    'name'  => $item['name']
                );
            }
            return $result;
        } elseif ($type === 'datatable') {
            $itemsData = $this->model("institution")->getItems();
            $items = $this->module("permission")->checkRules($itemsData, "institution", 'permission_access_mode');


            $items = array_values($items);
            foreach($items as $key => $item) {
                $items[$key]['country_code'] = $this->translateHttpResource(sprintf(
                    "img/flags/%s.png",
                    strtolower($item['country_code'])
                ));

                $items[$key]['options'] = array(
                    'edit'  => array(
                        'icon'  => 'icon-edit',
                        'link'  => $this->getBasePath() . "edit/" . $item['id'],
                        'class' => 'btn-sm btn-primary'
                    )/*,
                    'remove'    => array(
                        'icon'  => 'icon-remove',
                        'class' => 'btn-sm btn-danger'
                    ),
                    'website'    => array(
                        'icon'  => 'icon-link',
                        'link'  => $item['website'],
                        'class' => 'btn-sm'
                    ),
                    'facebook'    => array(
                        'icon'  => 'icon-facebook',
                        'link'  => $item['facebook'],
                        'class' => 'btn-sm'
                    )
                    */
                );
            }
            return array(
                'sEcho'                 => 1,
                'iTotalRecords'         => count($items),
                'iTotalDisplayRecords'  => count($items),
                'aaData'                => array_values($items)
            );
        }
        $itemsData = $this->model("institution")->getItems();
        $items = $this->module("permission")->checkRules($itemsData, "institution", 'permission_access_mode');

        return array_values($items);
    }
}
