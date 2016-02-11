<?php 
namespace Sysclass\Services\Queue;

class Message 
{
	public function __construct($channel, $data = null) {
		$this->data = array(
			'channel' => is_null($channel) ? 'default' : (string) $channel,
			'data' => is_null($data) ? "HELLO" : $data
		);
	}

    public function __toString() {
        return json_encode($this->data);
    }
}