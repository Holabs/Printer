<?php


namespace Holabs\Printer;


/**
 * @author       Tomáš Holan <mail@tomasholan.eu>
 * @package      holabs/printer
 * @copyright    Copyright © 2017, Tomáš Holan [www.tomasholan.eu]
 */
class Configuration {

	/** @var string */
	private $layout;

	/** @var bool */
	private $autoPrint = FALSE;

	/** @var array */
	private $params;

	/**
	 * Configuration constructor.
	 * @param string $layout
	 * @param bool   $autoPrint
	 * @param array  $params
	 */
	public function __construct(string $layout, bool $autoPrint = FALSE, array $params = []) {
		$this->layout = $layout;
		$this->params = $params;
		$this->autoPrint = $autoPrint;
	}

	/**
	 * @return string
	 */
	public function getLayoutPath(): string {
		return $this->layout;
	}

	/**
	 * @return array
	 */
	public function getDefaultParams(): array {
		return $this->params;
	}

	/**
	 * @return bool
	 */
	public function isAutoPrintEnabled(): bool {
		return $this->autoPrint;
	}




}