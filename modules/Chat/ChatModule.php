<?php
namespace Sysclass\Modules\Chat;
/**
 * Module Class File
 * @filesource
 */
/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 */
/**
 * @RoutePrefix("/module/chat")
 */
class ChatModule extends \SysclassModule
{
    protected function getDatatableItemOptions() {
        if ($this->request->hasQuery('block')) {
            return array(
                'view'  => array(
                    'icon'  => 'fa fa-eye',
                    'link' => "javascript: void(0);",
                    'class' => 'btn btn-primary datatable-actionable tooltips',
                    'attrs'         => array(
                        'data-on-color' => "success",
                        'data-original-title' => $this->translate->translate('View Chat'),
                        'data-placement' => 'left'
                    )
                ),
                'assign'  => array(
                    'icon'  => 'fa fa-user',
                    'link' => "javascript: alert('not disponible yet');",
                    'class' => 'btn btn-success view-chat-action tooltips',
                    'attrs'         => array(
                        'data-on-color' => "success",
                        'data-original-title' => $this->translate->translate('Assign to Me'),
                        'data-placement' => 'left'
                    )
                ),
                'resolution'  => array(
                    'icon'  => 'fa fa-clock-o',
                    'link' => "javascript: alert('not disponible yet');",
                    'class' => 'btn btn-warning view-chat-action tooltips',
                    'attrs' => array(
                        'data-on-color' => "success",
                        'data-original-title' => $this->translate->translate('Set Resolution'),
                        'data-placement' => 'left'
                    )
                ),
                'remove'  => array(
                    'icon'  => 'fa fa-close',
                    'class' => 'btn btn-danger tooltips',
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
}
