<?php
namespace Sysclass\Modules\Forms;
/**
 * Module Class File
 * @filesource
 */
use Sysclass\Models\Courses\Course as Course,
    Sysclass\Models\Enrollments\Course as Enrollment,
    Sysclass\Models\Forms\Fields;
/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 */
/**
 * @RoutePrefix("/module/forms")
 */
class FormsModule extends \SysclassModule 
{

    protected function isUserAllowed($action, $args) {
        // THIS MODULE IS ALWAYS PUBLIC
        return true;
    }
}
