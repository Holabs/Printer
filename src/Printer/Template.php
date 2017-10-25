<?php


namespace Holabs\Printer;


/**
 * @author       Tomáš Holan <mail@tomasholan.eu>
 * @package      holabs/printer
 * @copyright    Copyright © 2017, Tomáš Holan [www.tomasholan.eu]
 */
class Template implements ITemplate {

	/** @var string */
	private $id;

	/** @var string */
	private $name;

	/** @var string */
	private $class;

	/** @var array */
	private $options;

	/** @var string */
	private $source;

	/**
	 * Template constructor.
	 * @param string $id
	 * @param string $name
	 * @param string $class
	 * @param array  $options
	 * @param string $source
	 */
	public function __construct(string $id, string $name, string $class, array $options, string $source) {
		$this->id = $id;
		$this->name = $name;
		$this->class = $class;
		$this->options = $options;
		$this->source = $source;
	}


	/**
	 * @return string
	 */
	public function getId(): string {
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getClass(): string {
		return $this->class;
	}

	/**
	 * @return array
	 */
	public function getOptions(): array {
		return $this->options;
	}

	/**
	 * @return string
	 */
	public function getSource(): string {
		return $this->source;
	}

}