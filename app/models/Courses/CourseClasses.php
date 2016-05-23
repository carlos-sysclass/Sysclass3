<?php
namespace Sysclass\Models\Courses;

use Plico\Mvc\Model,
    Sysclass\Models\Courses\Classe;

class CourseClasses extends Model
{
    protected $assignedData = null;
    public function initialize()
    {
        $this->setSource("mod_roadmap_courses_to_classes");
        
        $this->belongsTo("course_id", "Sysclass\\Models\\Courses\\Course", "id",  array('alias' => 'Course'));
        $this->belongsTo("class_id", "Sysclass\\Models\\Courses\\Classe", "id",  array('alias' => 'Classe'));

        //$this->hasMany("class_id", "Sysclass\\Models\\Courses\\Classe", "id",  array('alias' => 'Classe'));

    }

    public function assign(array $data, $dataColumnMap = NULL, $whiteList = NULL) {
        $this->assignedData = $data;
        return parent::assign($data, $dataColumnMap, $whiteList);
    }

    public function beforeValidationOnCreate() {
        if (is_null($this->class_id)) {
            // CREATE A NEW CLASS
            if (array_key_exists('classe', $this->assignedData)) {
                $classe = new Classe();
                $classe->assign($this->assignedData['classe']);
                $classe->save();
                $this->class_id = $classe->id;
            }
        }
        return true;
    }

    public function beforeValidation() {
        $this->active = (int) $this->active;
    }


    public function findFull() {
    	$args = func_get_arg(0);
    	/*

		$params = array(
		    'models'     => array('CourseClasses'),
		    'columns'    => array('id', 'name', 'status'),
		    'conditions' => array(
		        array(
		            "created > :min: AND created < :max:",
		            array("min" => '2013-01-01',   'max' => '2014-01-01'),
		            array("min" => PDO::PARAM_STR, 'max' => PDO::PARAM_STR),
		        ),
		    ),
		    // or 'conditions' => "created > '2013-01-01' AND created < '2014-01-01'",
		    'group'      => array('id', 'name'),
		    'having'     => "name = 'Kamil'",
		    'order'      => array('name', 'id'),
		    'limit'      => 20,
		    'offset'     => 20,
		    // or 'limit' => array(20, 20),
		);
		$queryBuilder = new \Phalcon\Mvc\Model\Query\Builder($params);
		*/
	
		$depinject = \Phalcon\DI::getDefault();

		$phql = 'SELECT
            c2c.id,
            c2c.course_id,
            c2c.class_id,
            clp.period_id,
            c2c.start_date,
            c2c.end_date,
            c2c.position,
            c2c.active,
            cl.area_id,
            cl.name,
            cl.description,
            cl.instructor_id,
            COUNT(l.id) as total_lessons,
            cl.active,
            c.id,
            c.name,
            c.active,
            cp.id,
            cp.name,
            cp.max_classes,
            cp.active
        FROM Sysclass\Models\Courses\CourseClasses c2c
        LEFT JOIN Sysclass\Models\Courses\Course c ON(c2c.course_id = c.id)
        LEFT JOIN Sysclass\Models\Courses\Classe cl ON(c2c.class_id = cl.id)
        LEFT JOIN Sysclass\Models\Courses\Lesson l ON(cl.id = l.class_id)
        LEFT JOIN Sysclass\Models\Courses\ClassePeriods clp ON(c2c.class_id = clp.class_id)
        LEFT JOIN Sysclass\Models\Courses\CoursePeriods cp ON(clp.period_id = cp.id AND cp.course_id = c.id)
		';

        if (array_key_exists('conditions', $args)) {
            $phql .= " WHERE " . $args['conditions'];
        }

        var_dump($args );


		$query = $depinject->get("modelsManager")->createQuery($phql);

        if (array_key_exists('bind', $args)) {
            $query->setBindParams($args['bind']);
        }



		print_r($query->getSql());

        $rows = $query->execute();

        //print_r($rows);
    }

    public function toFullClassArray() {
        $result = $this->toArray();

        $course = $this->getCourse();
        $result['course'] = $course->toArray();

        $class = $this->getClasse();
        $result['classe'] = $class->toFullArray();

        $lessons = $class->getLessons();

        $result['classe']['lessons'] = $lessons->toArray();

        return $result;
    }
}
