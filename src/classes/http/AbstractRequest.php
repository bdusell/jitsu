<?php

namespace jitsu\http;

/* An HTTP request. */
abstract class AbstractRequest {

	/* Get the full URL of the request, including the scheme, host name,
	 * path, and query string. This is in raw form and not URL-decoded. */
	public function full_url() {
		return $this->scheme() . '://' . $this->host() . $this->uri();
	}

	/* Get the scheme used for the request, either `'http'` or `'https'`. */
	public abstract function scheme();

	/* Get the protocol/version string used in the request. */
	public abstract function protocol();

	/* Get the host name of the request. */
	public abstract function host();

	/* Get the HTTP method used in the request, _always_ in upper case
	 * (e.g. `'GET'`, `'PUT'`, etc.). */
	public abstract function method();

	/* Get the request URI of the request. This consists of the path and
	 * query string. Note that this is in raw form and not URL-decoded. */
	public abstract function uri();

	/* Get the path part of the requested URI. Note that this is in raw
	 * form and not URL-decoded. */
	public abstract function path();

	/* Get the query string of the requested URI. Note that this is in raw
	 * form and not URL-decoded. */
	public abstract function query_string();

	/* Get form-encoded parameters from the current request.
	 *
	 * If called with no arguments, returns an array mapping the names of
	 * the form-encoded parameters sent in the request to their values. All
	 * keys and values are decoded. The parameters are taken from the
	 * appropriate part of the request based on the HTTP method used and
	 * RESTful conventions. For GET and DELETE, they are parsed from the
	 * query string. For everything else, they are parsed from the request
	 * body.
	 *
	 * If called with the name of a parameter, returns the value of that
	 * single parameter, or null if it does not exist.
	 */
	public abstract function form($name = null);

	/* Get headers sent in the current request.
	 *
	 * If called with no arguments, returns an array mapping the names in
	 * lower case of all headers sent in the request to their values.
	 *
	 * If called with the name of a header (case-insensitive), returns its
	 * value, or null if it was not sent.
	 */
	public abstract function header($name = null);

	/* Alias for `header()`. */
	public function headers() {
		return $this->header();
	}

	/* Get the content type of the request. */
	public function content_type() {
		return $this->header('Content-Type');
	}

	/* Get a list of the acceptable content types of the response as an
	 * associative array mapping content type strings to their respective
	 * quality ratings, ordered in descending order of quality. */
	public function accept() {
		return self::parse_negotiation($this->header('Accept'));
	}

	/* Return whether the request will accept a certain content type. */
	public function accepts($content_type) {
		$accept = $this->accept();
		return (
			array_key_exists($content_type, $accept) &&
			$accept[$content_type] > 0
		);
	}

	private static function parse_negotiation($str) {
		$result = array();
		$parts = preg_split('/\s*,\s*/', $str);
		foreach($parts as $part) {
			$matches = null;
			if(preg_match('/^(.*?)\s*(;\s*q=(.*))?$/', $part, $matches)) {
				$type = $matches[1];
				if(array_key_exists(2, $matches) && is_numeric($matches[3])) {
					$quality = (float) $matches[3];
				} else {
					$quality = 1.0;
				}
				$result[$type] = $quality;
			}
		}
		arsort($result);
		return $result;
	}

	/* Given an array of acceptable content types, return the index of the
	 * content type with the highest quality rating in the `Accept`
	 * header. Return null if no content type is acceptable. */
	public function negotiate_content_type($acceptable) {
		$accept = $this->accept();
		$expected = array_flip($acceptable);
		foreach($accept as $pattern => $quality) {
			$regex = self::ct_pattern_regex($pattern);
			if($regex === null) {
				if(array_key_exists($pattern, $expected)) {
					return $expected[$pattern];
				}
			} else {
				foreach($acceptable as $i => $type) {
					if(preg_match($regex, $type)) {
						return $i;
					}
				}
			}
		}
	}

	private static function ct_pattern_regex($pattern) {
		$wildcard = false;
		$regex = preg_replace_callback(
			'/(\\*)|(.+?)/',
			function($matches) use(&$wildcard) {
				if($matches[1] !== '') {
					$wildcard = true;
					return '[^/]*';
				} else {
					return preg_quote($matches[2], '#');
				}
			},
			$pattern
		);
		if($wildcard) {
			return '#^' . $regex . '$#';
		}
	}

	/* Get the HTTP referrer URI or null if it was not sent. */
	public function referer() {
		return $this->header('Referer');
	}

	/* Correctly spelled alias of `referer`. */
	public function referrer() {
		return $this->referer();
	}

	/* Get cookies sent with the current request, parsed and decoded.
	 *
	 * If called with no arguments, returns an array mapping cookie names
	 * to their values. If called with the name of a cookie, returns the
	 * value of that single cookie, or null if it was not sent. */
	public abstract function cookie($name = null);

	/* Alias for `cookie()`. */
	public function cookies() {
		return $this->cookie();
	}

	/* Return the request body as a single string. */
	public abstract function body();

	/* Get the names of files uploaded in a multipart-formdata request body
	 * and stored in temporary file space via PHP's POST upload mechanism.
	 *
	 * If called with no arguments, returns an array mapping the form
	 * parameter names of all uploaded files to arrays containing
	 * information about each upload.
	 *
	 * If called with the name of a form parameter, returns the info array
	 * for the file uploaded under that name, or null if no file was
	 * uploaded under that name.
	 *
	 * The members of the info array are as follows:
	 *   `name`
	 *     The original name of the file on the client machine.
	 *   `type`
	 *     The MIME type of the file as indicated in the request body (not
	 *     to be trusted for security reasons).
	 *   `size`
	 *     The size in bytes of the uploaded file.
	 *   `tmp_name`
	 *     The temporary filepath of the uploaded file.
	 *   `error`
	 *     Error code for the file upload. See PHP's `UPLOAD_ERR_`
	 *     constants.
	 */
	public abstract function file($name = null);

	/* Alias for `file()`. */
	public function files() {
		return $this->file();
	}
}

?>