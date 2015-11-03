<?php
namespace Sysclass\Modules\Enroll;
/**
 * Module Class File
 * @filesource
 */
use Sysclass\Models\Courses\Course as Course;
use Sysclass\Models\Enrollments\Course as Enrollment;
/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 */
/**
 * @RoutePrefix("/module/enroll")
 */
class EnrollModule extends \SysclassModule implements \IBlockProvider
{

    /* IBlockProvider */
    public function registerBlocks() {
        return array(
            'enroll.list.table' => function($data, $self) {
                // CREATE BLOCK CONTEXT
                $self->putComponent("data-tables");
                $self->putScript("scripts/utils.datatables");

                $block_context = $self->getConfig("blocks\\enroll.list.table\context");
                $self->putItem("courses_block_context", $block_context);

                $self->putSectionTemplate("courses", "blocks/table");

                return true;

            }
        );
    }

    protected function getDatatableItemOptions() {
        if ($this->request->hasQuery('block')) {
            return array(
                'check'  => array(
                    //'icon'        => 'icon-check',
                    //'link'        => $baseLink . "block/" . $item['id'],
                    //'text'            => self::$t->translate('Disabled'),
                    //'class'       => 'btn-sm btn-danger',
                    'type'          => 'switch',
                    //'state'           => 'disabled',
                    'attrs'         => array(
                        'data-on-color' => "success",
                        'data-on-text' => self::$t->translate('YES'),
                        'data-off-color' =>"danger",
                        'data-off-text' => self::$t->translate('NO')
                    )
                )
            );
        } else {
            return parent::getDatatableItemOptions();
        }
    }
}
