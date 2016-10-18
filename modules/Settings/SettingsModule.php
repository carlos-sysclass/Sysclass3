<?php
namespace Sysclass\Modules\Settings;
/**
 * Module Class File
 * @filesource
 */
use Sysclass\Models\System\Settings as SystemSettings, 
    Sysclass\Models\Users\Settings,
    Sysclass\Models\Courses\Course;
/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 * @todo think about move this module to Phalcon, as a model
 */
/**
 * @RoutePrefix("/module/settings")
 */
class SettingsModule extends \SysclassModule implements \ISectionMenu, \ILinkable
{
    protected $legalValues = array(
        'content_id',
        'unit_id',
        'course_id',
        'program_id'
    );
    
    protected $defaults = array(
        'content_id' => null,
        'unit_id' => null,
        'course_id' => null,
        'program_id' => null,
        // @todo Create a hierachical method to get and save values
        // THIS IS NOT SAVED ON POST SETTINGS, BECAUSE THESE VALUES ARE NOT ON $this->legalValues ARRAY
        'js_date_fmt'   => 'mm/dd/yyyy',
        'php_date_fmt'  => 'm/d/Y'
    );
    /* ISectionMenu */
    public function getSectionMenu($section_id) {
        /*
        if ($section_id == "topbar") {

            // INJECT THE HELP BUTTON

            $this->putCss('css/pageguide/pageguide');
            $this->putScript('plugins/pageguide/pageguide.min');

            $this->putScript("scripts/ui.menu.settings");

            $menuItem = array(
                'id'        => "open-pageguide-action",
                'icon'      => ' fa fa-question',
                'text'      => $this->translate->translate('Help'),
                'type'      => ''
            );

            return $menuItem;
        }
        */
        return false;
    }

    /* ILinkable */
    public function getLinks() {
        //$data = $this->getItemsRequest();
        
        if ($this->acl->isUserAllowed(null, $this->module_id, "Manage")) {
            return array(
                'administration' => array(
                    array(
                        //'count' => count($items),
                        'text'  => $this->translate->translate('Settings'),
                        'icon'  => 'fa fa-code-fork',
                        'link'  => $this->getBasePath() . 'manage'
                    )
                )
            );
        }
    }


    
    
    /**
     * Returns a JSON string object to the browser when hitting the root of the domain
     *
     * @Get("/")
     */
    public function getRequest() {
        if ($user = $this->user) {
            $this->response->setContentType('application/json', 'UTF-8');
            
            if ($results = $this->getSettings(true)) {
                $results['user_id'] = $user->id;
                if (!is_null($user->websocket_key)) {
                    $results['websocket_key'] = $user->websocket_key;
                }

                $results['websocket_port'] = $this->environment->websocket->port;
                $results['websocket_ssl_port'] = $this->environment->websocket->ssl_port;

                $course = Course::findFirst(array(
                    'conditions' => "id = ?0",
                    'columns' => 'name',
                    'bind' => array($results['course_id'])
                ));
                $results['course_name'] = $course->name;

                $this->response->setJsonContent($results);
                return $results;
            } else {
                $this->response->setJsonContent($this->notAuthenticatedError());
            }
        }
    }
    /**
     * Returns a JSON string object to the browser when hitting the root of the domain
     *
     * @Post("/")
     */
    public function saveRequest()
    {
        if ($user = $this->getCurrentUser(true)) {
            // SAVE SETTINGS FOR CURRENT USER
            $putData = $this->getHttpData(func_get_args());

            $values = array();
            foreach($this->legalValues as $id) {
                //print_r($id);
                //
                //
                //
                if (array_key_exists($id, $putData)) {
                    $values[] = array(
                        'user_id'   => $user->id,
                        'item'      => $id,
                        'value'     => $putData[$id]
                    );
                }
            }

            foreach ($values as $value) {
                $settings = new Settings();
                $settings->assign($value);
                $settings->save();
            }
            return $this->getRequest();
        } else {
            $this->response->setJsonContent($this->notAuthenticatedError());
        }
    }

    public function getSettings($mergeWithDefaults = false) {
       if ($user = $this->user) {
            // GET USER LANGUAGE AND LOCALIZATION INFO
            //language_id
            //var_dump($user->getLanguage()->toArray());
            //exit;
            // SAVE SETTINGS FOR CURRENT USER
            $settings = $user->getSettings();

            if ($settings) {
                $results = array();

                foreach($settings->toArray() as $item) {
                    $results[$item['item']] = $item['value'];
                }
                if ($mergeWithDefaults) {
                    $results = array_merge($this->defaults, $results);
                }

                $results['language'] = $user->getLanguage()->js_code;

                return $results;
            }
        } else {
            return false;
        }
    }

    public function get($key) {
       if ($user = $this->getCurrentUser(true)) {
            // SAVE SETTINGS FOR CURRENT USER
            $settings = $this->getSettings();

            if (array_key_exists($key, $results)) {
                return $results[$key];
            }
            return $this->defaults[$key];
        } else {
            return false;
        }
    }

    public function put($key, $value) {
       if ($user = $this->getCurrentUser()) {
            // SAVE SETTINGS FOR CURRENT USER
            //$this->db->StartTrans();
            $this->db->Execute(sprintf(
                "DELETE FROM user_settings WHERE user_id = %d AND item = '%s'",
                $user['id'],
                $key
            ));
            $this->db->Execute(sprintf(
                "INSERT INTO user_settings (user_id, item, value) VALUES (%d, '%s', '%s')",
                $user['id'],
                $key,
                $value
            ));
            //$this->db->CompleteTrans();

            return true;
        } else {
            return false;
        }
    }

    /**
     * Returns a JSON string object to the browser when hitting the root of the domain
     *
     * @Get("/manage")
     */
    public function managePage() {
        if ($this->isResourceAllowed("manage")) {

            $settingsRS = SystemSettings::find(
                array(
                    'conditions' => 'changeable = 1',
                    'order' => '[group] ASC, label ASC, name ASC'
                )
            );

            $settings = $settingsRS->toArray();

            $groups = array_unique(array_column($settings, "group"));
            
            $this->putData(array(
                "system_settings_groups" => $groups,
                "system_settings" => $settings
            ));
            
            return $this->handleDefaultRequest();
        } else {
            $this->redirect($this->getSystemUrl('home'), "", 401);
        }
    }

    /**
     * Returns a JSON string object to the browser when hitting the root of the domain
     *
     * @Get("/item/me")
     */
    public function globalSettingsRequest() {
        $settingsRS = SystemSettings::find(
            array(
                'conditions' => 'changeable = 1',
                'order' => '-[group] DESC, label ASC, name ASC'
            )
        );

        $settings = array();
        foreach($settingsRS as $set) {
            $settings[$set->name] = $set->value;
        }

        return $settings;
    }

    /**
     * [ add a description ]
     *
     * @Post("/item/{model}")
     */
    public function addItemRequest($model)
    {
        $this->response->setContentType('application/json', 'UTF-8');

        $model_info = $this->model_info[$model];

        if ($this->isResourceAllowed("create", $model_info)) {
            // TODO CHECK IF CURRENT USER CAN DO THAT
            
            $data = $this->request->getJsonRawBody(true);

            if (!array_key_exists($model, $this->model_info)) {
                $this->eventsManager->fire("module-{$this->module_id}:errorModelDoesNotExists", $model, $data);

                $response = $this->invalidRequestError($this->translate->translate("A problem ocurred when tried to save you data. Please try again."), "warning");
                $this->response->setJsonContent(
                    array_merge($response, $data)
                );
                return true;
            }
            $model_info = $this->model_info[$model];
            
            $model_class = $model_info['class'];

            //$this->eventsManager->collectResponses(true);
            $this->eventsManager->fire("module-{$this->module_id}:beforeModelCreate", $itemModel, $data);

            foreach($data as $key => $value) {
                $itemModel = $model_class::findFirst(array(
                    'conditions' => "name = ?0 AND changeable = 1",
                    'bind' => array($key)
                ));

                if ($itemModel) {
                    $itemModel->value = (string) $value;

                    $itemModel->update();
                }
            }
            

            $this->eventsManager->fire("module-{$this->module_id}:afterModelCreate", $itemModel, $data);
                
            if ($this->request->hasQuery('object')) {
                $this->response->setJsonContent(
                    $this->createAdviseResponse(
                        $this->translate->translate("System settings saved."),
                        "success"
                    )
                );
            } else {
                $this->response->setJsonContent(
                    $this->createRedirectResponse(
                        $this->getBasePath() . "edit/" . $itemModel->id,
                        $this->translate->translate("System settings saved."),
                        "success"
                    )
                );
            }
            return true;
        } else {
            $this->response->setJsonContent(
                $this->notAuthenticatedError()
            );
            return true;
        }
    }

}
