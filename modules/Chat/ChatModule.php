<?php
namespace Sysclass\Modules\Chat;
/**
 * Module Class File
 * @filesource
 */
use Sysclass\Models\Acl\Resource;
/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 */
/**
 * @RoutePrefix("/module/chat")
 */
class ChatModule extends \SysclassModule implements \IBlockProvider
{
    public function registerBlocks() {
        return array(
            'chat.quick-sidebar' => function($data, $self) {

                $self->putComponent("autobahn");
                $self->putComponent("bootstrap-confirmation");
                
                $self->putModuleScript("chat");
                $self->putModuleScript("ui.menu.chat");

                $self->putScript("plugins/sprintf/sprintf.min");

                $resource = Resource::findFirst([
                    'conditions' => '[group] = ?0 AND [name] = ?1',
                    'bind' => ["Chat", "Receive"]
                ]);

                $self->putBlock("users.select.dialog", array(
                    "special-filters" => array(
                        "permission_id" => $resource->id
                    )
                ));

                $self->putSectionTemplate("foot", "blocks/chat");
                $self->putSectionTemplate("sidebar", "blocks/quick-sidebar");

                return true;
            }
        );
    }

    protected function getDatatableItemOptions() {
        if ($this->request->hasQuery('block')) {
            return array(
                'view'  => array(
                    'icon'  => 'fa fa-eye',
                    'link' => "javascript: void(0);",
                    'class' => 'btn-sm btn-primary datatable-actionable tooltips',
                    'attrs'         => array(
                        'data-on-color' => "success",
                        'data-original-title' => $this->translate->translate('View Chat'),
                        'data-placement' => 'left'
                    )
                ),
                'assign'  => array(
                    'icon'  => 'fa fa-user',
                    'link' => "javascript: alert('not disponible yet');",
                    'class' => 'btn-sm btn-success view-chat-action tooltips',
                    'attrs'         => array(
                        'data-on-color' => "success",
                        'data-original-title' => $this->translate->translate('Assign to Me'),
                        'data-placement' => 'left'
                    )
                ),
                'resolution'  => array(
                    'icon'  => 'fa fa-clock-o',
                    'link' => "javascript: alert('not disponible yet');",
                    'class' => 'btn-sm btn-warning view-chat-action tooltips',
                    'attrs' => array(
                        'data-on-color' => "success",
                        'data-original-title' => $this->translate->translate('Set Resolution'),
                        'data-placement' => 'left'
                    )
                ),
                'remove'  => array(
                    'icon'  => 'fa fa-close',
                    'class' => 'btn-sm btn-danger tooltips',
                    'attrs'         => array(
                        'data-on-color' => "success",
                        'data-original-title' => $this->translate->translate('Remove Chat'),
                        'data-placement' => 'left'
                    )
                )
            );
        } else {
            return parent::getDatatableItemOptions();
        }
    }

    protected function isUserAllowed($action, $module_id = null) {

        $allowed = parent::isUserAllowed($action);
        if (!$allowed) {
            switch($action) {
                case "edit" : {
                    return $allowed = parent::isUserAllowed("assign");
                }
            }
        }
        return $allowed;
    }

   
}
