<?php
namespace Sysclass\Services\MessageBus;

use Sysclass\Collections\MessageBus\Event;

interface INotifyable {
	public function getAllActions();
	public function processNotification($action, Event $event);
}