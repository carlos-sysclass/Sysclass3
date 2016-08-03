<?php
namespace Sysclass\Modules\Permission;
/**
 * Module Class File
 * @filesource
 */
use Sysclass\Models\Acl\Resource as AclResource;
/**
 * Manage and control the system permission system
 * @package Sysclass\Modules
 */
/**
 * @RoutePrefix("/module/permission")
 */
class PermissionModule extends \SysclassModule
{
    protected function getDatatableItemOptions() {
        // TODO: THINK ABOUT MOVING THIS TO config.yml
        if ($this->request->hasQuery('block')) {
        	return array(
                'check'  => array(
                    //'icon'  		=> 'icon-check',
                    //'link'  		=> $baseLink . "block/" . $item['id'],
                    //'text' 			=> $this->translate->translate('Disabled'),
                    //'class' 		=> 'btn-sm btn-danger',
                    'type'			=> 'switch',
                    //'state'			=> 'disabled',
                    'attrs'			=> array(
			        	'data-on-color' => "success",
			        	'data-on-text' => $this->translate->translate('YES'),
			        	'data-off-color' =>"danger",
			        	'data-off-text'	=> $this->translate->translate('NO')
                    )
                )
            );
        }
    }

}

