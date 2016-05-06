<?= '<?php' ?>


namespace <?= $namespace ?>;

use Jitsu\StringUtil;

class Handlers {

	public static function index($data) {
		Tools::page($data, 'pages/index', [
			'page_name' => 'Home'
		]);
	}

	public static function notFound($data) {
		self::otherError($data, 404, 'Not Found');
	}

	public static function internalError($data) {
		if($data->config->get('show_stack_traces', false)) {
			$vars['stack_trace'] = StringUtil::capture(function() use ($data) {
				\Jitsu\printException($data->exception);
			});
			if($data->exception instanceof DatabaseException) {
				$vars['sql_error'] = $data->exception->getErrorString();
			}
			if(!$data->response->sentHeaders()) {
				$data->response->setStatusCode(500, 'Internal Server Error');
				$data->response->setContentType('text/plain');
				Tools::text($data, 'errors/internal_error', $vars);
			} else {
				Tools::page($data, 'errors/internal_error', [
					'title' => 'Internal Error'
				] + $vars);
			}
		} else {
			Tools::page($data, 'errors/internal_error', [
				'title' => 'Internal Error'
			]);
		}
		$ob = $data->config->get('output_buffering', true);
		if($ob) $data->response->flushOutputBuffer();
	}

	public static function otherError($data, $code, $reason) {
		$data->response->setStatusCode($code, $reason);
		$data->response->setContentType('text/plain');
		echo "$code: $reason\n";
	}
}
