<?php

namespace AD7six\Dsn;

class Dsn {

/**
 * parse
 *
 * Parse a url and merge with any extra get arguments defined
 *
 * @param string $string
 * @return array
 */
	public static function parse($string) {
		$url = parse_url($string);
		if (!$url || array_keys($url) === ['path']) {
			return false;
		}

		if (isset($url['query'])) {
			$extra = [];
			parse_str($url['query'], $extra);
			unset($url['query']);
			$url += $extra;
		}

		return $url;
	}

/**
 * get a value out of an array if it exists
 *
 * @param array $data
 * @param string $key
 * @return mixed
 */
	protected static function _get($data, $key) {
		if (isset($data[$key])) {
			return $data[$key];
		}

		return null;
	}

/**
 * Generic/noop handler
 *
 * @param mixed $key
 * @param mixed $config
 * @param mixed $defaults
 * @return array
 */
	protected static function _parse($key, $config, $defaults) {
		$name = isset($config['name']) ? $config['name'] : trim($key, '_');

		$config += $defaults;

		return [$name => $config];
	}

/**
 * Recursively perform string replacements on array values
 *
 * @param array $data
 * @param array $replacements
 * @return array
 */
	protected static function _replace($data, $replacements) {
		if (!$replacements) {
			return $data;
		}

		foreach($data as &$value) {
			$value = str_replace(array_keys($replacements), array_values($replacements), $value);
			if (is_array($value)) {
				$value = static::_replace($value, $replacements);
			}
		}

		return $data;
	}

}
