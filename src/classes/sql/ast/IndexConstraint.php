<?php

namespace jitsu\sql\ast;

/* A primary key table constraint.
 *
 * <index-constraint> ->
 *   "INDEX" [<identifier>] "(" <identifier>+{","} ")"
 */
class IndexConstraint extends ColumnGroupTableConstraint {

}

?>
