<?php


namespace Holabs\Printer;


/**
 * @author       Tomáš Holan <mail@tomasholan.eu>
 * @package      holabs/printer
 * @copyright    Copyright © 2017, Tomáš Holan [www.tomasholan.eu]
 */
class PreviewEntity {

	/** @var string */
	private $preview_entity_class;

	/**
	 * PreviewEntity constructor.
	 * @param string $preview_entity_class
	 */
	public function __construct(string $preview_entity_class) {
		$this->preview_entity_class = $preview_entity_class;
	}

	/**
	 * @param string $name
	 * @param array $arguments
	 * @return string
	 */
	public function __call($name, $arguments) {
		return "{$this->preview_entity_class}::{$name}()";
	}

	/**
	 * @param string $name
	 * @return string
	 */
	public function __get($name) {
		return "{$this->preview_entity_class}::{$name}";
	}

	/**
	 * @param string $name
	 * @param mixed $value
	 */
	public function __set($name, $value) {
		return;
	}


}