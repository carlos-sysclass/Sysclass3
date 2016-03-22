<?php 
namespace Sysclass\Services\MessageBus;

use Phalcon\Mvc\User\Component,
	Sysclass\Models\MessageBus\Listeners,
	Sysclass\Collections\MessageBus\Event,
    Phalcon\Mvc\Dispatcher,
    Phalcon\Script\Color;

class Manager extends Component
{
	protected $types = array(
		'user',
		'api',
		'course',
		'unit'
	);

	protected $listeners = array();
	protected $modules = array();

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

		// POPULATE LISTENERS ARRAY, FROM NOTIFICATIONS MODEL
		$DBlisteners = Listeners::find("active = 1");

		foreach($DBlisteners as $listen) {
			if (!array_key_exists($listen->module, $this->modules)) {
				$this->modules[$listen->module] = $this->loader->module($listen->module);
			}
			if (!array_key_exists($listen->type, $this->listeners)) {
				$this->listeners[$listen->type] = array();
			}
			if (!array_key_exists($listen->name, $this->listeners[$listen->type])) {
				$this->listeners[$listen->type][$listen->name] = array();
			}
			$this->listeners[$listen->type][$listen->name][] = array(
				'module' => $listen->module,
				'action' => $listen->action
			);
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

	public function processEvents() {
		$events = $this->messagebus->receive(false);

		$result = array();

		foreach($events as $evt) {
			//$this->module
			if (@isset($this->listeners[$evt->type][$evt->name])) {
				$processing = $this->listeners[$evt->type][$evt->name];

				foreach($processing as $proc) {
					$message = sprintf(
						"Processing Event %:%s calling %s::%s (ID: #%s)",
						$evt->type,
						$evt->name,
						$proc['module'],
						$proc['action'],
						$evt->_id
					);

					fwrite(STDERR, Color::info($message));
					// fwrite(STDOUT, $message . PHP_EOL); // WRITE TO LOG
					
					$result = $this->modules[$proc['module']]->processNotification($proc['action'], $evt);

					if ($result['status'] === TRUE || $result['unqueue'] === TRUE) {
						$this->unqueue($evt->_id);
					}
				}
			}
		}

		return $result;

	}

	public function unqueue($id) {
		$queue = Event::findById($id);
		$queue->processed = true;

		return $queue->save();
	}
}