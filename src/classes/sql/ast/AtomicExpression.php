<?php

namespace jitsu\sql\ast;

/* An expression which is syntactically atomic; there can be no ambiguity as to
 * how it associates with other operators.
 *
 * <atomic-expression> ->
 *   <column-reference> |
 *   <placeholder> |
 *   <function-call> |
 *   <cast-expression> |
 *   <exists-expression> |
 *   <select-expression>
 */
abstract class AtomicExpression extends Expression {

}

?>
