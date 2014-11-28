<?php

/* Utilties for dealing with *Bags*. A *Bag* is defined as a PHP array whose
 * keys and ordering are ignored; only the values it contains are
 * significant. */
class BagUtil {

	/* Given a *Bag* of arrays, pluck all of the values under a certain
	 * key as a *List*. */
	public static function pluck_list($bag, $key) {
		return array_column($bag, $key);
	}

	/* Given a *Bag* of arrays, pluck all of the values under two keys,
	 * where one names the column for keys and the other for values, as an
	 * *OrderedMap*. */
	public static function pluck_ordered_map($bag, $key_key, $value_key) {
		return array_column($bag, $value_key, $key_key);
	}

	/* Return a *Map* mapping the values in a *Bag* to the number of times
	 * they occur. */
	public static function frequencies($bag) {
		return array_count_values($bag);
	}

	/* Return a *Bag* containing the values in `$bag1` but not in
	 * `$bag2`. */
	public static function difference($bag1, $bag2) {
		return array_diff($bag1, $bag2);
	}

	/* Convert a *Bag* to a *Set*. */
	public static function to_set($bag) {
		return array_fill_keys($bag, true);
	}

	/* Convert a *Bag* into a *List*. */
	public static function to_list($bag) {
		return array_values($bag);
	}
}

?>
