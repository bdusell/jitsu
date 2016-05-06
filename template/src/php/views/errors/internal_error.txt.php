500: Internal Server Error

<?php if(isset($sql_error)): ?>
<?= $sql_error ?>


<?php endif; ?>
<?= $stack_trace ?>
