<?= '<?php' ?>


namespace <?= $namespace ?>;

use Jitsu\App\SiteConfig;

class Application extends \Jitsu\App\Application {

	use \Jitsu\App\Databases;

	public function initialize() {
		$this->database('database');
		$this->get('', self::wrap('index'));
		$this->notFound(self::wrap('notFound'));
		$this->error(self::toCallback('internalError'));
	}

	private static function wrap($name) {
		return function($data) use ($name) {
			$res = $data->response;
			$ob = $data->config->get('output_buffering', true);
			if($ob) $res->startOutputBuffering();
			try {
				call_user_func(self::toCallback($name), $data);
			} catch(Errors\HttpError $e) {
				if($ob) {
					$res->clearOutputBuffer();
					$res->startOutputBuffering();
				}
				Handlers::otherError($data, $e->getStatusCode(), $e->getReasonString());
			} catch(\Exception $e) {
				if($ob) $res->clearOutputBuffer();
				throw $e;
			}
			if($ob) $res->flushOutputBuffer();
		};
	}

	private static function toCallback($name) {
		return [__NAMESPACE__ . '\\Handlers', $name];
	}
}
