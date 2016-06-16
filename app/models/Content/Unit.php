<?php
namespace Sysclass\Models\Content;

use Plico\Mvc\Model,
    Sysclass\Models\Courses\Contents\Content,
    Sysclass\Models\Content\Course,
    Sysclass\Models\Content\Program;

class Unit extends Model
{
    public function initialize()
    {
        $this->setSource("mod_lessons");

		$this->belongsTo(
			"class_id", 
			"Sysclass\\Models\\Content\\Course", 
			"id",
			array('alias' => 'Course')
		);

		$this->hasOne(
            "id",
            "Sysclass\\Models\\Courses\\LessonProgress",
            "lesson_id",
            array('alias' => 'Progress')
        );

        $this->hasMany(
            "id",
            "Sysclass\\Models\\Content\\UnitContent",
            "lesson_id",
            array(
                'alias' => 'Contents',
                'params' => array(
                    'order' => '[Sysclass\Models\Content\UnitContent].position ASC, [Sysclass\Models\Content\UnitContent].id ASC'
                )

            )
        );

        $this->belongsTo(
            "instructor_id",
            "Sysclass\\Models\\Users\\User",
            "id",
            array('alias' => 'Professor')
        );

    }

    public function toFullLessonArray() {
        $result = $this->toArray();

        $classe = $this->getClasse();
        $result['classe'] = $classe->toArray();

        $contents = $this->getContents();
        $result['contents'] = array();

        foreach($contents as $content) {
            $item = $content->toFullContentArray();
            
            $result['contents'][] = $item;
        }
        
        $progress = $this->getProgress();
        if ($progress) {
            $result['progress'] = $progress->toArray();
        } else {
            $result['progress'] = array();
        }

        return $result;
    }
    /**
     * Returns a array containing the current user content "pointer", containing course, program, units which the user is currently on.
     * @param  Sysclass\Models\Users\User $user       The user to be checked, if null, get the current logged user
     * @param  string $by         Select from whick variable will be used to generate the tree. 'content, unit, course, program, default'
     * @param  numeric|bool $entity_id  The value of entity to search for, pass false to get the defaults
     * @return [type]             Array containaing 'content_id, unit_id, course_id, program_id',or false if no info is avaliable
     */
    public static function getContentPointers($user = null, $by = 'default', $entity_id = false) {
        if (is_null($user)) {
            $user = \Phalcon\DI::getDefault()->get('user');
            $courses = $user->getPrograms();
            $course_ids = array_column($courses->toArray(), 'id');
        }
        switch($by) {
            case 'content' : {
                // FROM CONTENT, LOAD ALL PARENTS
                $content = Content::findFirstById($entity_id);
                if (!$content) {
                    return false;
                }
                $unit = $content->getUnit();
                $course = $unit->getCourse();
                $program = $course->getPrograms()->getFirst();
                break;
            }
            case 'unit' : {
                // FROM UNIT, LOAD ALL PARENTS AND GET THE FIRST CONTENT
                // FROM CONTENT, LOAD ALL PARENTS
                $unit = Unit::findFirstById($entity_id);
                if (!$unit) {
                    return false;
                }
                $content = $unit->getContents()->getFirst();
                $course = $unit->getCourse();
                $program = $course->getPrograms()->getFirst();
                break;
            }
            case 'course' : {
                // FROM COURSE, LOAD THE PROGRAM AND GET THE FIRST UNIT, AND FIRST CONTENT
                // FROM UNIT, LOAD ALL PARENTS AND GET THE FIRST CONTENT
                // FROM CONTENT, LOAD ALL PARENTS
                $course = Course::findFirstById($entity_id);
                if (!$course) {
                    return false;
                }
                $unit = $course->getLessons()->getFirst();
                $content = $unit->getContents()->getFirst();
                $program = $course->getPrograms()->getFirst();
                break;
            }
            case 'program' : {
                // FROM COURSE, GET THE FIRST COURSE, AND THE FIRST UNIT, AND THE FIRST CONTENT
                // FROM COURSE, LOAD THE PROGRAM AND GET THE FIRST UNIT, AND FIRST CONTENT
                // FROM UNIT, LOAD ALL PARENTS AND GET THE FIRST CONTENT
                // FROM CONTENT, LOAD ALL PARENTS
                $program = Program::findFirstById($entity_id);
                if (!$program) {
                    return false;
                }
                $course = $program->getClasses()->getFirst();
                $unit = $course->getLessons()->getFirst();
                $content = $unit->getContents()->getFirst();

                break;
            }
            case 'default' :
            default : {
                $program = $user->getPrograms()->getFirst();
                $course = $program->getCourses()->getFirst();
                $unit = $course->getLessons()->getFirst();
                $content = $unit->getContents()->getFirst();
                break;
            }
        }

        if (!in_array($program->id, $course_ids)) {
            return false;
        }

        return array(
            'program'   => $program,
            'course'    => $course,
            'unit'      => $unit,
            'content'   => $content
        );
    }

    public function getFullTree() {
        $result = $this->toArray();
        if ($professor =  $this->getProfessor()) {
            $result['professor'] = $professor->toArray();
        } else {
            $result['professor'] = array();
        }
        $result['contents'] = array();
        $contents = $this->getContents();
        foreach($contents as $content) {
            $result['contents'][] = $content->getFullTree();
        }

        return $result;
    }

}
