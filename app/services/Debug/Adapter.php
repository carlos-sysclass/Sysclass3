<?php 
namespace Sysclass\Services\Debug;

use Phalcon\Mvc\User\Component;

class Adapter extends Component
{
	public function __construct() {
	    if ($this->environment->run->debug) {
    	    \Kint::enabled(true);
    	} else {
    		\Kint::enabled(false);
    	}
	}

	public function dump() {
		return call_user_func_array(
			array("\Kint", "dump"), func_get_args()
		);
	}
}