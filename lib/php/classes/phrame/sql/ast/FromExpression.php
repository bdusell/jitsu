<?php

namespace phrame\sql\ast;

/* An expression contained in a FROM clause.
 *
 * <from-expression> ->
 *   <join-expression> |
 *   <table-expression> |
 *   <select-expression-in-from>
 */
abstract class FromExpression extends Node {

}

?>
