<?php 
namespace Sysclass\Services\MessageBus;

use Phalcon\Mvc\User\Component,
	Sysclass\Collections\MessageBus\Event,
    Phalcon\Mvc\Dispatcher;

class Manager extends Component
{
	protected $types = array(
		'user',
		'api'
	);

	public function initialize() {
		foreach($this->types as $type) {
			$this->eventsManager->attach($type, function ($event, $component, $data) use ($type) {
				$status = $this->publish(
					$type,
					$event->getType(),
					$data
				);
			});
		}
		
	}

	public function publish($type, $name, $data, $priority = 10) {
		$event = new Event();
		$data = array(
			'type' => $type, 
			'name' => $name,
    		'data' => $data,
    		'priority' => $priority,
    	);
    	$event->assign($data);

    	return $event->save();
	}

	public function receive($unqueue = false, $type = null, $name = null) {
		$conditions = array("processed" => false);
		//$conditions = array();

		if (!is_null($type)) {
			$conditions['type'] = $type;
		}
		if (!is_null($name)) {
			$conditions['name'] = $name;
		}

		$events = Event::find(array($conditions));
 		return $events;
	}

	public function unqueue($id) {
		$queue = Event::findById($id);
		$queue->processed = true;

		return $queue->save();
	}
}