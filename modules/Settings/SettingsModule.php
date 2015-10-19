<?php
namespace Sysclass\Modules\Settings;
/**
 * Module Class File
 * @filesource
 */
use Sysclass\Models\Users\Settings;
/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 * @todo think about move this module to Phalcon, as a model
 */
/**
 * @RoutePrefix("/module/settings")
 */
class SettingsModule extends \SysclassModule
{
    protected $legalValues = array();
    protected $defaults = array();

    public function initialize() {
        parent::init();

        $this->legalValues = array(
            'course_id',
            'class_id',
            'lesson_id'
        );

        $this->defaults = array(
            'course_id' => null,
            'class_id' => null,
            'lesson_id' => null,
            'teste_execution_id' => null,
            // @todo Create a hierachical method to get and save values
            // THIS IS NOT SAVED ON POST SETTINGS, BECAUSE THESE VALUES ARE NOT ON $this->legalValues ARRAY
            'js_date_fmt'   => 'mm/dd/yyyy',
            'php_date_fmt'  => 'm/d/Y'
        );

        //$this->db = \DatabaseManager::db();

    }
    /**
     * Returns a JSON string object to the browser when hitting the root of the domain
     *
     * @Get("/")
     */
    public function getRequest() {
        $this->response->setContentType('application/json', 'UTF-8');

        if ($results = $this->getSettings(true)) {
            $this->response->setJsonContent($results);
            return $results;
        } else {
            $this->response->setJsonContent($this->notAuthenticatedError());
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
       if ($user = $this->getCurrentUser(true)) {
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
            $this->db->StartTrans();
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
            $this->db->CompleteTrans();

            return true;
        } else {
            return false;
        }
    }

}
