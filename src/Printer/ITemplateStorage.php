<?php


namespace Holabs\Printer;


/**
 * @author       Tomáš Holan <mail@tomasholan.eu>
 * @package      holabs/printer
 * @copyright    Copyright © 2017, Tomáš Holan [www.tomasholan.eu]
 */
interface ITemplateStorage {

	/**
	 * @param string $id
	 * @return ITemplate
	 */
	public function getTemplate(string $id): ITemplate;

}