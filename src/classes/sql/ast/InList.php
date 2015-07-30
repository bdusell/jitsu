<?php

namespace jitsu\sql\ast;

/* A list on the right side of an `IN` operator.
 *
 * <in-list> ->
 *   <simple-in-list> |
 *   <select-in-list> |
 *   <table-in-list>
 */
abstract class InList extends Node {

}

?>
