<?= '<?php' ?>


namespace <?= $namespace ?>\Errors;

class NotAcceptable extends HttpError {

	public function getStatusCode() {
		return 406;
	}

	public function getReasonString() {
		return 'Not Acceptable';
	}
}
