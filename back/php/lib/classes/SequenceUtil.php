<?php

/* A set of utilties for dealing with *Sequences*. A *Sequence* is defined as a
 * PHP array whose keys are ignored; only its values and their ordering are
 * significant. Every *Sequence* is a valid *Bag*. */
class SequenceUtil {

	/* Split a *Sequence* into a *List* of chunks of `$n` elements each. */
	public static function chunk($seq, $n) {
		return array_chunk($seq, $n);
	}

	/* Filter the values in a *Sequence* by a predicate. The predicate
	 * should have the signature `function($value)`. */
	public static function filter($seq, $callback) {
		return array_filter($seq, $callback);
	}
}

?>
