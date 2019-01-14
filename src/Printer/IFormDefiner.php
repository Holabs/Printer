<?php


namespace Holabs\Printer;


/**
 * @author       Tomáš Holan <mail@tomasholan.eu>
 * @package      holabs/print
 * @copyright    Copyright © 2017, Tomáš Holan [www.tomasholan.eu]
 */
interface IFormDefiner {

	/**
	 * @return array
	 */
	public function getFormDefinition(): array;

}