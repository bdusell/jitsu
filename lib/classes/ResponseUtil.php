<?php

namespace phrame;

/* Utility methods for getting information about the current HTTP response
 * being sent. */
class ResponseUtil {

	use Singleton;

	protected function instantiate() {
		return new http\CurrentResponse;
	}
}

?>
