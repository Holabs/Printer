<?php


namespace Holabs;


use Holabs\Printer\Job;

/**
 * @author       Tomáš Holan <mail@tomasholan.eu>
 * @package      holabs/printer
 * @copyright    Copyright © 2017, Tomáš Holan [www.tomasholan.eu]
 */
interface IPrinter {

	public function createJob(string $id, ... $objIds): Job;

}