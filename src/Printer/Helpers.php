<?php


namespace Holabs\Printer;

use Nette\InvalidStateException;
use Nette\Utils\ObjectMixin;


/**
 * @author       Tomáš Holan <mail@tomasholan.eu>
 * @package      holabs/printer
 * @copyright    Copyright © 2017, Tomáš Holan [www.tomasholan.eu]
 */
class Helpers {

	/**
	 * @param array $expected
	 * @param array $config
	 * @return array|string
	 */
	public static function validateOptions(array $expected, array $config) {

		if ($extra = array_diff_key((array)$config, $expected)) {
			$hint = ObjectMixin::getSuggestion(array_keys($expected), key($extra));
			$extra = $hint ? key($extra) : implode(", ", array_keys($extra));
			throw new InvalidStateException(
				"Unknown configuration option $extra" . ($hint ? ", did you mean $hint?" : '.')
			);
		}

		return self::merge($config, $expected);
	}

	/**
	 * Merges configurations. Left has higher priority than right one.
	 * @return array|string
	 */
	public static function merge($left, $right) {
		if (is_array($left) && is_array($right)) {
			foreach ($left as $key => $val) {
				if (is_int($key)) {
					$right[] = $val;
				} else {
					if (isset($right[$key])) {
						$val = static::merge($val, $right[$key]);
					}
					$right[$key] = $val;
				}
			}

			return $right;
		} elseif ($left === NULL && is_array($right)) {
			return $right;
		} else {
			return $left;
		}
	}

}