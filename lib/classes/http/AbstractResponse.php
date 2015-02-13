<?php

namespace phrame\http;

abstract class AbstractResponse {

	/* Set the HTTP response code of the response. If `$code` is not given,
	 * return the currently set response code. */
	public abstract function code($code = null);

	/* Set a header in the response. */
	public abstract function header($name, $value);

	/* Set the content type of the response using the `Content-Type`
	 * header. */
	public function content_type($type) {
		$this->header('Content-Type', $type);
	}

	/* Send a cookie back to the client. Provide a lifespan in seconds from
	 * the current time; if this is null, the cookie has an unlimited
	 * lifespan. Optionally provide a domain and path for the cookie. */
	public abstract function cookie($name, $value,
		$lifespan = null, $domain = null, $path = null);

	/* Request that a cookie be deleted from the client. */
	public abstract function delete_cookie($name,
		$domain = null, $path = null);

	/* Issue a redirect to another URL. Note that this does NOT exit the
	 * current process. Keep in mind that relying on the client to respect
	 * this header and close the connection for you is potentially a huge
	 * security hole.
	 *
	 * The response code is also set here. The response code should be a
	 * 3XX code. */
	public abstract function redirect($url, $code);

	/* Start buffering PHP print output to the body of this response. */
	public abstract function start_buffer();

	/* Write buffered PHP print output to the body of this response and
	 * stop buffering. */
	public abstract function flush_buffer();

	/* Discard buffered PHP print output from the body of this response and
	 * stop buffering. */
	public abstract function clear_buffer();
}

?>
