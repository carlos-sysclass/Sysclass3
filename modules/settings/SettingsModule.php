<?php
/**
 * Module Class File
 * @filesource
 */
/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 * @todo think about move this module to PlicoLib
 */
class SettingsModule extends SysclassModule
{
    protected $legalValues = array();
    protected $defaults = array();

    public function __construct() {
        parent::__construct();

        $this->legalValues = array(
            'course_id',
            'class_id',
            'lesson_id',
            'teste_id'
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
    }
    /**
     * Returns a JSON string object to the browser when hitting the root of the domain
     *
     * @url GET /
     */
    public function getAction() {
       if ($results = $this->getSettings(true)) {
            return $results;
        } else {
            return $this->notAuthenticatedError();
        }
    }
    /**
     * Returns a JSON string object to the browser when hitting the root of the domain
     *
     * @url POST /
     */
    public function saveAction()
    {
        if ($user = $this->getCurrentUser()) {
            // SAVE SETTINGS FOR CURRENT USER
            $putData = $this->getHttpData(func_get_args());
            $values = array();
            foreach($this->legalValues as $id) {
                if (array_key_exists($id, $putData)) {
                    $values[] = array(
                        'user_id'   => $user['id'],
                        'item'      => $id,
                        'value'     => $putData[$id]
                    );
                }
            }
            $this->db->StartTrans();
            foreach ($values as $value) {
                $this->db->Execute(sprintf(
                    "DELETE FROM user_settings WHERE user_id = %d AND item = '%s'",
                    $value['user_id'],
                    $value['item']
                ));
                $this->db->Execute(sprintf(
                    "INSERT INTO user_settings (user_id, item, value) VALUES (%d, '%s', '%s')",
                    $value['user_id'],
                    $value['item'],
                    $value['value']
                ));
            }
            $this->db->CompleteTrans();

            return $this->getSettings(true);
        } else {
            return $this->notAuthenticatedError();
        }
    }

    public function getSettings($mergeWithDefaults = false) {
       if ($user = $this->getCurrentUser()) {
            // SAVE SETTINGS FOR CURRENT USER
            $results = $this->db->GetAssoc(sprintf(
                "SELECT item, value FROM user_settings WHERE user_id = %d",
                $user['id']
            ));
            if ($mergeWithDefaults) {
                $results = array_merge($this->defaults, $results);
            }

            return $results;
        } else {
            return false;
        }
    }

    public function get($key) {
       if ($user = $this->getCurrentUser()) {
            // SAVE SETTINGS FOR CURRENT USER
            $value = $this->db->GetOne(sprintf(
                "SELECT value FROM user_settings WHERE user_id = %d AND item = '%s'",
                $user['id'],
                $key
            ));
            if ($value === FALSE) {
                // SEARCH ON DEFAULTS
                return $this->defaults[$key];
            }
            return $value;
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
