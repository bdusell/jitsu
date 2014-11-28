<?php

/* A set of utilities for dealing with *OrderedMaps*. An *OrderedMap* is
 * equivalent to an ordinary PHP array; its mapping of keys to values and its
 * ordering are significant. Every *OrderedMap* is a valid *OrderedSet*, *Map*,
 * and *Sequence*. */
class OrderedMapUtil {

	/* Split an *OrderedMap* into a *List* of chunks of `$n` elements
	 * each. */
	public static function chunk($omap, $n) {
		return array_chunk($omap, $n, true);
	}

	/* Create an *OrderedMap* from two *Sequences*, where one is for keys and
	 * the other is for values. */
	public static function from_stacks($key_seq, $value_seq) {
		return array_combine($key_stack, $value_stack);
	}
}

?>
