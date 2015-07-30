<?php

namespace jitsu\sql\ast;

/* An expression appearing in the initial list of a SELECT clause.
 *
 * <column-expression> ->
 *   <simple-column-expression> |
 *   <wildcard-column-expression>
 */
abstract class ColumnExpression extends Node {

}

?>
