<?php

namespace phrame;

/* A bag of site configuration settings with some special methods on top. */
class SiteConfig extends Config {

	/* Set the base URL of the site. If any of the `scheme`, `host`, or
	 * `path` can be parsed from the new value, they are set
	 * accordingly. */
	public function set_base_url($url) {
		$parts = parse_url($url);
		foreach(array('scheme', 'host', 'path') as $name) {
			if(array_key_exists($name, $parts)) {
				$this->set($name, $parts[$name]);
			}
		}
	}

	/* Get the configured base URL for the site. */
	public function get_base_url() {
		return $this->scheme . '://' . $this->host . $this->base_path;
	}

	public function get_base_path() {
		$path = $this->path;
		if($path === '') {
			return '';
		} else {
			$path = trim($path, '/');
			if($path === '') {
				return '/';
			} else {
				return '/' . $path . '/';
			}
		}
	}

	/* Given a relative path, append it to the `path` to form an absolute
	 * path, with the exception that if both are the empty string, return
	 * the empty string (allowing the bare domain to serve as the URL). */
	public function make_path($rel_path) {
		return $this->base_path . $rel_path;
	}

	public function remove_path($abs_path) {
		return StringUtil::remove_prefix($abs_path, $this->base_path);
	}

	/* Given a relative path, append it to the `base_url` to form an
	 * absolute URL. */
	public function make_url($rel_path) {
		return (
			$this->scheme . '://' .
			$this->host .
			$this->make_path($rel_path)
		);
	}

	/* Set the locale. If the value is an array of strings, elements serve
	 * as fallbacks for those preceding. */
	public function set_locale($value) {
		setlocale(LC_ALL, $value);
	}

	public function get_locale() {
		return setlocale('0');
	}
}

?>
