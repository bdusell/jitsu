<?php

/* Usage:
 *
 * ```
 * class MySingleton {
 *     use Singleton;
 *     public function __construct() {
 *         // initialization code
 *     }
 * }
 *
 * $a = MySingleton::instance();
 * $b = MySingleton::instance();
 * // $a and $b point to the same object
 * ```
 * */

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
