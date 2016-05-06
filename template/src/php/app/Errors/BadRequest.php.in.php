<?= '<?php' ?>


namespace <?= $namespace ?>\Errors;

class BadRequest extends HttpError {

	public function getStatusCode() {
		return 400;
	}

	public function getReasonString() {
		return 'Bad Request';
	}
}
