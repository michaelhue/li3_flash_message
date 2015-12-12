# Flash Message Plugin for Lithium

The Flash Message (`li3_flash_message`) plugin provides a straightforward interface for displaying status messages to the user.


## Goals

- Use existing session storage
- Eliminate message content from controllers
- Easily localize messages
- Use filters to integrate into existing workflow


## Integration

```php
<?php

// config/bootstrap/libraries.php:

Libraries::add('li3_flash_message');

?>
```

## Usage

Here is an example of adding flash messages in a controller:

```php
<?php
namespace app\controllers;

use lithium\security\Auth;
use li3_flash_message\extensions\storage\FlashMessage;

class AdministratorsController extends \lithium\action\Controller {
    public function login() {
        if (Auth::check('admin', $this->request)) {
            FlashMessage::write('Logged you in!');
            return $this->redirect(array('Employees::index'));
        }

        $loginFailed = false;
        if ($this->request->data){
            $loginFailed = true;
        }
        return compact('loginFailed');
    }

    public function logout() {
        FlashMessage::write('Logged you out!');
        Auth::clear('admin');
        return $this->redirect('/');
    }
}
?>
```

To include the messages in your template, simply add:

    <?= $this->flashMessage->show() ?>

By default, `libraries/li3_flash_message/app/views/elements/flash_message.html.php` will be used to render your flash message. You can create `app/views/elements/flash_message.html.php` to override it and render it however you like!

For a full example of using this library, please see [the relevant chapter in "Step by step Li3"](http://gavd.github.io/step-by-step-web-apps-with-lithium-php/flash-messages.html).
