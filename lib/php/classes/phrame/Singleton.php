<?php

/* A mixin which turns a class into a singleton.
 *
 * Usage:
 *     class MySingleton {
 *         use Singleton;
 *         public function __construct() {
 *             // initialization code
 *         }
 *     }
 *     $a = MySingleton::instance();
 *     $b = MySingleton::instance();
 *     // $a and $b point to the same object
 */

namespace phrame;

trait Singleton {

	private static $instance = null;

	public static function instance() {
		if(self::$instance === null) {
			$class = get_called_class();
			return self::$instance = new $class();
		} else {
			return self::$instance;
		}
	}

	final private function __clone() {}
}

?>
