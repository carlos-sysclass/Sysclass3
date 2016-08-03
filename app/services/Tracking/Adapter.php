<?php
namespace Sysclass\Services\Tracking;

use Phalcon\Mvc\User\Component,
    Sysclass\Services\Tracking\Interfaces\ITracking,
    Sysclass\Services\Tracking\Exception as TrackingException;

class Adapter extends Component implements ITracking
{
    protected $backend_class = null;
    protected $backend = null;

    public function initialize() {
        $backend = $this->environment->tracking->backend;

        $this->setBackend($backend);
        $this->backend->initialize();
    }

    public function setBackend($backend) {
        $backend = ucfirst(strtolower($backend));
        $class = "\\Sysclass\\Services\\Tracking\\Backend\\{$backend}";
        if (class_exists($class)) {
            $this->backend = new $class();
        } else {
            throw new TrackingException("NO_BACKEND_DISPONIBLE", TrackingException::NO_BACKEND_DISPONIBLE);
        }
        return true;
    }

    /* PROXY/ADAPTER PATTERN */
    public function generateTrackingTag() {
        return $this->backend->generateTrackingTag();
    }
}
