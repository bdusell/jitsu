<?php

namespace phrame\sql\ast;

/* A primary key table constraint.
 *
 * <primary-key-constraint> ->
 *   ["CONSTRAINT" <identifier>]
 *   "PRIMARY" "KEY" "(" <identifier>+{","} ")"
 */
class PrimaryKeyConstraint extends ColumnGroupTableConstraint {

}

?>
