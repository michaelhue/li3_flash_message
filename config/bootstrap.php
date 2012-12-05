<?php

use li3_flash_message\extensions\storage\FlashMessage;
use lithium\action\Dispatcher;

Dispatcher::applyFilter('_callable', function($self, $params, $chain) {
	$object = $chain->next($self, $params, $chain);

	if (is_a($object, 'lithium\action\Controller')) {
		return FlashMessage::bindTo($object);
	}

	return $object;
});

?>