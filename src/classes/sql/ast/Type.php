<?php

namespace phrame\sql\ast;

/* A SQL data type, possibly modified by a character set and collation.
 *
 * See subclasses.
 *
 * <type> ->
 *   <type-name>
 *   ["CHARACTER" "SET" <character-set-name>]
 *   ["COLLATE" <collation-name>]
 */
class Type extends Node {

}

?>
