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
            'enroll.user.block' => function($data, $self) {
                // CREATE BLOCK CONTEXT
                $self->putComponent("select2");
                $self->putComponent("data-tables", "select2");
                $self->putScript("scripts/utils.datatables");

                $self->putModuleScript("user.block");

                $block_context = $self->getConfig("blocks\\enroll.user.block\context");
                $self->putItem("enroll_user_block_context", $block_context);

                $self->putSectionTemplate("enroll", "blocks/enroll.user");

                return true;

            }
        );
    }
    /*
    protected function getDatatableItemOptions() {

        if ($this->request->hasQuery('block')) {
            return array(
                'check'  => array(
                    //'icon'        => 'icon-check',
                    //'link'        => $baseLink . "block/" . $item['id'],
                    //'text'            => $this->translate->translate('Disabled'),
                    //'class'       => 'btn-sm btn-danger',
                    'type'          => 'switch',
                    //'state'           => 'disabled',
                    'attrs'         => array(
                        'data-on-color' => "success",
                        'data-on-text' => $this->translate->translate('YES'),
                        'data-off-color' =>"danger",
                        'data-off-text' => $this->translate->translate('NO')
                    )
                )
            );
        } else {
            return parent::getDatatableItemOptions();
        }
    }
    */
}
