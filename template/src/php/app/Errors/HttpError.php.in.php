<?= '<?php' ?>


namespace <?= $namespace ?>\Errors;

abstract class HttpError extends \RuntimeException {
	public abstract function getStatusCode();
	public abstract function getReasonString();
}
