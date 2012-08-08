# Flash Message Plugin for Lithium

The Flash Message (`li3_flash_message`) plugin provides a straightforward interface for displaying status messages to the user.


## Goals

- Use existing session storage
- Eliminate message content from controllers
- Easily localize messages
- Use filters to integrate into existing workflow


## Integration

```
<?php

// config/bootstrap/libraries.php:

Libraries::add('li3_flash_message');

?>
```