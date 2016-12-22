<?php
namespace Sysclass\Models\Content;

use Plico\Mvc\Model,
    Sysclass\Models\Content\UnitContent as Content,
    Sysclass\Models\Content\Course,
    Sysclass\Models\Content\Program,
    Sysclass\Models\Users\User;

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
            "Sysclass\\Models\\Content\\Progress\\Unit",
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

        $this->hasOne(
            "id",
            "Sysclass\\Models\\Content\\UnitTest",
            "id",
            array('alias' => 'Test')
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
        }
        $programs = $user->getPrograms([
            "conditions" => 'approved = 1'
        ]);

        if ($programs->count() > 0) {
            //$course_ids = array_column($courses->toArray(), 'id');

            $program_ids = [];
            foreach($programs as $program) {
                    $program_ids[] = $program->id;
            }

            switch($by) {
                case 'content' : {
                    // FROM CONTENT, LOAD ALL PARENTS
                    $content = Content::findFirstById($entity_id);
                    if (!$content) {
                        return self::getContentPointers($user, 'unit');
                    }
                    $unit = $content->getUnit();
                    $course = $unit->getCourse();
                    $program = $course->getProgram();
                    if (!in_array($program->id, $program_ids)) {
                        return self::getContentPointers($user, 'unit');
                    }

                    break;
                }
                case 'unit' : {
                    // FROM UNIT, LOAD ALL PARENTS AND GET THE FIRST CONTENT
                    // FROM CONTENT, LOAD ALL PARENTS
                    $unit = Unit::findFirstById($entity_id);
                    if (!$unit) {
                        return self::getContentPointers($user, 'course');
                    }
                    $course = $unit->getCourse();
                    $program = $course->getProgram();
                    if (!in_array($program->id, $program_ids)) {
                        return self::getContentPointers($user, 'course');
                    }
                    $content = $unit->getContents()->getFirst();

                    break;
                }
                case 'course' : {
                    // FROM COURSE, LOAD THE PROGRAM AND GET THE FIRST UNIT, AND FIRST CONTENT
                    // FROM UNIT, LOAD ALL PARENTS AND GET THE FIRST CONTENT
                    // FROM CONTENT, LOAD ALL PARENTS
                    $course = Course::findFirstById($entity_id);
                    if (!$course) {
                        return self::getContentPointers($user, 'program');
                    }
                    $program = $course->getProgram();
                    if (!in_array($program->id, $program_ids)) {
                        return self::getContentPointers($user, 'program');
                    }

                    $unit = $course->getUnits()->getFirst();
                    $content = $unit->getContents()->getFirst();

                    break;
                }
                case 'program' : {
                    // FROM COURSE, GET THE FIRST COURSE, AND THE FIRST UNIT, AND THE FIRST CONTENT
                    // FROM COURSE, LOAD THE PROGRAM AND GET THE FIRST UNIT, AND FIRST CONTENT
                    // FROM UNIT, LOAD ALL PARENTS AND GET THE FIRST CONTENT
                    // FROM CONTENT, LOAD ALL PARENTS
                    $program = Program::findFirstById($entity_id);
                    if (!$program) {
                        return self::getContentPointers($user, 'default');
                    }
                    $course = $program->getCourses()->getFirst();
                    if ($course) {
                        $unit = $course->getUnits()->getFirst();
                    } else {
                        $unit = false;
                    }
                    if ($unit) {
                        $content = $unit->getContents()->getFirst();    
                    } else {
                        $content = false;
                    }

                    break;
                }
                case 'default' :
                default : {
                    $program = $programs->getFirst();
                    if ($program) {
                        $course = $program->getCourses()->getFirst();
                    } else {
                        $course = false;
                    }
                    if ($course) {
                        $unit = $course->getUnits()->getFirst();    
                    } else {
                        $unit = false;
                    }
                    
                    if ($unit) {
                        $content = $unit->getContents()->getFirst();    
                    } else {
                        $content = false;
                    }
                    
                    break;
                }
            }

            if (!in_array($program->id, $program_ids)) {
                return false;
            }

            return array(
                'program'   => $program,
                'course'    => $course,
                'unit'      => $unit,
                'content'   => $content
            );
        }
        return false;
    }

    public function getFullTree(User $user = null, $only_active = false) {
        if (is_null($user)) {
            $user = \Phalcon\DI::getDefault()->get('user');
        }

        $result = $this->toArray();
        if ($professor =  $this->getProfessor()) {
            $result['professor'] = $professor->toArray();
        } else {
            $result['professor'] = array();
        }
        $result['contents'] = array();

        if ($only_active) {
            $contents = $this->getContents([
                'conditions' => "active = 1"
            ]);
        } else {
            $contents = $this->getContents();
        }

        foreach($contents as $content) {
            $result['contents'][] = $content->getFullTree($user, $only_active);
        }

        if ($this->type == "test" && ($test = $this->getTest())) {
            // LOAD TEST DETAILS
            $result['test'] = $test->toArray();
            $result['test']['executions'] = $test->getExecutions(array(
                'conditions' => "user_id = ?0",
                'bind' => array($user->id)
            ))->toArray();
            $result['test']['questions'] = $test->getQuestions()->toArray();
        }

        $progress = $this->getProgress(array(
            'conditions' => "user_id = ?0",
            'bind' => array($user->id)
        ));

        if ($progress) {
            $result['progress'] = $progress->toArray();   
            $result['progress']['factor'] = floatval($result['progress']['factor']);
        } else {
            $result['progress'] = array(
                'factor' => 0
            );
        }

        return $result;
    }

}
