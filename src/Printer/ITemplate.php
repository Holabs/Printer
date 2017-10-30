<?php


namespace Holabs\Printer;


/**
 * @author       Tomáš Holan <mail@tomasholan.eu>
 * @package      holabs/printer
 * @copyright    Copyright © 2017, Tomáš Holan [www.tomasholan.eu]
 */
interface ITemplate {

	/**
	 * @return mixed
	 */
	public function getId();

	/**
	 * @return string
	 */
	public function getName(): string;

	/**
	 * @return string
	 */
	public function getClass(): string;

	/**
	 * @return array
	 */
	public function getOptions(): array;

	/**
	 * @return string
	 */
	public function getSource(): string;


}