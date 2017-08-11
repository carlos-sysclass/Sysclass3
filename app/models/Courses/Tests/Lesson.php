<?php
namespace Sysclass\Models\Courses\Tests;

use Sysclass\Models\Content\Unit as BaseUnit;
use Sysclass\Models\Courses\Tests\ExecutionQuestions;

class Lesson extends BaseUnit {
	protected $assignedData = null;

	public function initialize() {
		parent::initialize();

		$this->setSource("mod_lessons");

		$this->hasOne(
			"id",
			"Sysclass\\Models\\Courses\\Tests\\Test",
			"id",
			array('alias' => 'Test')
		);

		$this->hasMany(
			"id",
			"Sysclass\\Models\\Courses\\Tests\TestQuestions",
			"lesson_id",
			array('alias' => 'TestQuestions')
		);

		$this->hasMany(
			"id",
			"Sysclass\\Models\\Courses\\Tests\ExecutionQuestions",
			"lesson_id",
			array('alias' => 'ExecutionQuestions')
		);

		$this->hasManyToMany(
			"id",
			"Sysclass\\Models\\Courses\\Tests\TestQuestions",
			"lesson_id", "question_id",
			"Sysclass\\Models\\Courses\\Questions\Question",
			"id",
			array('alias' => 'Questions')
		);

	}

	public function shuffleTestQuestions($executionId, $readonly = false) {
		$test = $this->getTest();
		$questions = $this->getTestQuestions();
		$executionQuestions = $this->getExecutionQuestions(array(
			'conditions' => 'execution_id = ?0',
			'bind' => array($executionId),
			'order' => "position ASC",
		));
		$result = array();

		$questions_size = $test->test_max_questions == 0 ? $questions->count() : $test->test_max_questions;

		if ($executionQuestions->count() == 0) {

			$questionsArray = $questions->toArray();

			if ($test->randomize_questions || $questions_size < $questions->count()) {
				// IF LESS QUESTIONS THAN TOTAL, WILL RANDOMIZE

				$questions_indexes = array_rand(
					$questionsArray,
					min(intval($questions_size), count($questionsArray))
				);

			} else {
				$questions_indexes = array_keys($questionsArray);
			}

			if (!is_array($questions_indexes)) {
				$questions_indexes = array($questions_indexes);
			}

			foreach ($questions as $i => $question) {
				if (in_array($i, $questions_indexes)) {
					$result[] = $question;
				}
			}
			// SHUFFLE IF IT'S NEEDED
			if (!$readonly && $test->randomize_questions) {
				shuffle($result);
			}

			foreach ($result as $index => $question) {
				$object = new ExecutionQuestions();
				$object->execution_id = $executionId;
				$object->lesson_id = $this->id;
				$object->question_id = $question->question_id;
				$object->position = $index + 1;
				$object->save();
			}

		} else {
			foreach ($executionQuestions as $executionQuestion) {
				foreach ($questions as $question) {
					if ($question->question_id == $executionQuestion->question_id) {
						$result[] = $question;
					}
				}
			}
			if (!$readonly && $test->randomize_questions) {
				shuffle($result);

				// SAVE THE NEW ORDER

			}
		}
		return $result;
	}

	public function shuffleTestQuestionOptions($executionId, $testQuestions, $readonly = false) {
		$testDataQuestions = array();

		$testModel = $this->getTest();

		foreach ($testQuestions as $i => $question) {
			$testDataQuestions[$i] = $question->toArray();
			$questionModel = $question->getQuestion();

			// RANDOMIZE ANSWERS ??
			if ($testModel->randomize_answers && (
				$questionModel->type_id == "simple_choice" ||
				$questionModel->type_id == "multiple_choice"
			)) {
				$executionQuestion = ExecutionQuestions::findFirst([
					'conditions' => 'execution_id = ?0 AND lesson_id = ?1 AND question_id = ?2',
					'bind' => [$executionId, $this->id, $question->question_id],
				]);

				if ($executionQuestion) {
					if ($readonly) {
						$questionModel->options = json_decode($executionQuestion->options, true);
					} else {
						$questionModel->shuffleOptions();
						$executionQuestion->options = json_encode($questionModel->options);
						$executionQuestion->save();
					}
				}

			} else {

			}
			$testDataQuestions[$i]['question'] = $questionModel->toArray();

		}

		return $testDataQuestions;

	}

	public function assign(array $data, $dataColumnMap = NULL, $whiteList = NULL) {
		$this->assignedData = $data;
		return parent::assign($data, $dataColumnMap, $whiteList);
	}

	public function beforeSave() {
		$this->type = 'test';
	}

	public function afterCreate() {
		// SAVE THE LINKED TEST
		$testModel = new Test();
		if (array_key_exists('test', $this->assignedData) && is_array($this->assignedData['test'])) {
			$testModel->assign($this->assignedData['test']);
		}
		$testModel->id = $this->id;
		$testModel->save();
	}

	public function afterSave() {
		// SAVE THE LINKED TEST
		if (array_key_exists('test', $this->assignedData) && is_array($this->assignedData['test'])) {
			$testModel = new Test();
			$testModel->assign($this->assignedData['test']);
			$testModel->id = $this->id;
			$testModel->save();
		}
	}

	protected function resetOrder($lesson_id) {
		$manager = \Phalcon\DI::GetDefault()->get("modelsManager");

		$phql = "UPDATE Sysclass\\Models\\Courses\\Tests\TestQuestions
            SET position = -1 WHERE lesson_id = :lesson_id:";

		return $manager->executeQuery(
			$phql,
			array(
				'lesson_id' => $this->id,
			)
		);
	}

	public function setQuestionOrder(array $order_ids) {
		$status = self::resetOrder();
		$manager = \Phalcon\DI::GetDefault()->get("modelsManager");

		foreach ($order_ids as $index => $question_id) {
			$phql = "UPDATE Sysclass\\Models\\Courses\\Tests\TestQuestions
                SET position = :position:
                WHERE id = :id: AND lesson_id = :lesson_id:";

			$status->success() && $status = $manager->executeQuery(
				$phql,
				array(
					'position' => $index + 1,
					'id' => $question_id,
					'lesson_id' => $this->id,
				)
			);

		}

		return $status->success();
	}

	public function calculateTestScore() {
		$questions = $this->getTestQuestions();
		$testScore = 0;
		foreach ($questions as $question) {
			$testScore += $question->points * $question->weight;
		}

		return $testScore;
		//return $testData;

	}

	public function getUserPendingTests() {
		$di = $this->getDI();
		$user_id = $di->get("user")->id;

		$manager = $di->get("modelsManager");

		$phql = "SELECT tl.*
                FROM
                    Sysclass\\Models\\Enrollments\\CourseUsers ecu
                LEFT JOIN Sysclass\\Models\\Content\\Program c
                    ON (ecu.course_id = c.id)
                LEFT JOIN Sysclass\\Models\\Content\\Course cl
                    ON (c.id = cl.course_id)
                LEFT JOIN Sysclass\\Models\\Courses\\Tests\\Lesson tl
                    ON (tl.class_id = cl.id)
                WHERE ecu.user_id = :user_id: AND tl.type = 'test'
                    AND tl.id NOT IN (SELECT DISTINCT test_id FROM Sysclass\\Models\\Courses\\Tests\\Execution cte WHERE cte.user_id = :user_id: and pass = 1)
        ";
		//echo $phql;

		$status = $manager->executeQuery(
			$phql,
			array(
				'user_id' => $user_id,
			)
		);

		return $status;

	}

}
