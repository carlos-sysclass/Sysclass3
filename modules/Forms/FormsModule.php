<?php
namespace Sysclass\Modules\Forms;
/**
 * Module Class File
 * @filesource
 */
use Sysclass\Models\Courses\Course as Course,
    Sysclass\Models\Enrollments\CourseUsers as Enrollment,
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

    protected function isUserAllowed($action, $module_id = null) {
        // THIS MODULE IS ALWAYS PUBLIC
        return true;
    }
}
