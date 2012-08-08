<?php

use li3_flash_message\extensions\storage\FlashMessage;
use lithium\action\Dispatcher;

Dispatcher::applyFilter('_callable', function($self, $params, $chain) {
	return FlashMessage::bindTo($chain->next($self, $params, $chain));
});

?>