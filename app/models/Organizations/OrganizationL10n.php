<?php
namespace Sysclass\Models\Organizations;

use Plico\Mvc\Model;

class OrganizationL10n extends Model
{
    public function initialize()
    {
        $this->setSource("mod_organization_l10n");

    }

    public static function findUnique($id, $data) {

    	if (array_key_exists('language_code', $data)) {
    		$item = self::findFirst([
    			'conditions' => 'id = ?0 AND language_code = ?1',
    			'bind' => [$id, $data['language_code']]
    		]);

    		if (!$item) {
    			$item = new self();
    			$item->id = $id;
    			$item->assign($data);
    		}

    		return $item;
    	}
    }

}
