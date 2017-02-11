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

        $ids = explode("/", $id);

        $id = $id[0];

        $lang_code = $data['locale_code'] ? $data['locale_code'] : $ids[1];

    	//if (array_key_exists('language_code', $data)) {
    		$item = self::findFirst([
    			'conditions' => 'id = ?0 AND locale_code = ?1',
    			'bind' => [$id, $lang_code]
    		]);

    		if (!$item) {
    			$item = new self();
    			$item->id = $id;
    			$item->assign($data);
    		}

    		return $item;
    	//}
    }

}
