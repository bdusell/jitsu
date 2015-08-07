<?php

namespace jitsu\sql\ast;

/* A single, complete, executable statement in SQL.
 *
 * <statement> ->
 *   <select-statement> |
 *   <insert-statement> |
 *   <update-statement> |
 *   <delete-statement> |
 *   <create-table-statement> |
 *   <drop-table-statement>
 */
abstract class Statement extends Node {

}

?>
