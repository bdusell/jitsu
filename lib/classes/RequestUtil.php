<?php

namespace phrame;

/* Utility methods for getting information about the current HTTP request
 * that was received. */
class RequestUtil {

	use Singleton;

	protected function instantiate() {
		return new http\CurrentRequest;
	}
}

?>
