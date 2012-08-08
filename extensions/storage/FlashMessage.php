<?php
/**
 * li3_flash_message plugin for Lithium: the most rad php framework.
 *
 * @copyright     Copyright 2010, Michael Hüneburg
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_flash_message\extensions\storage;

use lithium\core\Libraries;

/**
 * Class for setting, getting and clearing flash messages. Use this class inside your
 * controllers to set messages for your views.
 *
 * {{{
 * // Controller
 * if (!$data) {
 *     FlashMessage::write('Invalid data.');
 * }
 * // or
 * if (!$post) {
 * 	return $this->redirect('Posts::index', array('message' => 'Post not found!'));
 * }
 *
 * // View
 * <?=$this->flashMessage->output(); ?>
 * }}}
 */
class FlashMessage extends \lithium\core\StaticObject {

	/**
	 * Class dependencies.
	 *
	 * @var array
	 */
	protected static $_classes = array(
		'session' => 'lithium\storage\Session'
	);

	/**
	 * Configuration directives for writing, storing, and rendering flash messages.
	 *
	 * @var array
	 */
	protected static $_config = array(
		'session' => array('config' => 'default', 'key' => 'message')
	);

	/**
	 * Stores message keys.
	 *
	 * @var array
	 */
	protected static $_messages = null;

	/**
	 * Used to set configuration parameters for `FlashMessage`.
	 *
	 * @see li3_flash_message\extensions\storage\FlashMessage::$_config
	 * @param array $config Possible key settings:
	 *              - `'classes'` _array_: Sets class dependencies (i.e. `'session'`).
	 *              - `'session'` _array_: Configuration for accessing and manipulating session
	 *                data.
	 * @return array If no parameters are passed, returns an associative array with the current
	 *         configuration, otherwise returns `null`.
	 */
	public static function config(array $config = array()) {
		if (!$config) {
			return static::$_config + array('classes' => static::$_classes);
		}

		foreach ($config as $key => $val) {
			$key = "_{$key}";

			if (isset(static::${$key})) {
				static::${$key} = $val + static::${$key};
			}
		}
	}

	/**
	 * Binds the messaging system to a controller to enable `'message'` option flags in various
	 * controller methods, such as `render()` and `redirect()`.
	 *
	 * @param object $controller An instance of `lithium\action\Controller`.
	 * @param array $options Options.
	 * @return object Returns the passed `$controller` instance.
	 */
	public static function bindTo($controller, array $options = array()) {
		$controller->applyFilter('redirect', function($self, $params, $chain) use ($options) {
			$options =& $params['options'];

			if (isset($params['options']['message'])) {
				FlashMessage::write($params['options']['message']);
				unset($params['options']['message']);
			}
			return $chain->next($self, $params, $chain);
		});

		return $controller;
	}

	/**
	 * Writes a flash message.
	 *
	 * @todo Add closure support to messages
	 * @param string $message Message that will be stored.
	 * @param array $attrs Optional attributes that will be available in the view.
	 * @param string $key Optional key to store multiple flash messages.
	 * @return boolean True on successful write, false otherwise.
	 */
	public static function write($message, array $attrs = array(), $key = 'default') {
		$session = static::$_classes['session'];
		$base = static::$_config['session']['key'];
		$key = "{$base}.{$key}";
		$name = static::$_config['session']['config'];

		if (static::$_messages === null) {
			$path = Libraries::get(true, 'path') . '/config/messages.php';
			static::$_messages = file_exists($path) ? include $path : array();
		}
		$message = isset(static::$_messages[$message]) ? static::$_messages[$message] : $message;
		return $session::write($key, compact('message', 'attrs'), compact('name'));
	}

	/**
	 * Reads a flash message.
	 *
	 * @param string [$key] Optional key.
	 * @return array The stored flash message.
	 */
	public static function read($key = 'default') {
		$session = static::$_classes['session'];
		return $session::read("FlashMessage.{$key}", array('name' => 'default'));
	}

	/**
	 * Clears all flash messages from the session.
	 *
	 * @return void
	 */
	public static function clear() {
		$session = static::$_classes['session'];
		$key = static::$_config['session']['key'];
		$name = static::$_config['session']['config'];
		return $session::delete($key, compact('name'));
	}
}

?>