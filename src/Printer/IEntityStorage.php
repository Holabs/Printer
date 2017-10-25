<?php


namespace Holabs\Printer;

use ArrayAccess;
use Nette\Utils\ArrayHash;


/**
 * @author       Tomáš Holan <mail@tomasholan.eu>
 * @package      holabs/printer
 * @copyright    Copyright © 2017, Tomáš Holan [www.tomasholan.eu]
 */
interface IEntityStorage {

	public function findEntities(string $class, ArrayHash $ids): ArrayAccess;

}