<?php


namespace Holabs\Printer;


use Nette\Application\UI\Form;

/**
 * @author       Tomáš Holan <mail@tomasholan.eu>
 * @package      holabs/printer
 * @copyright    Copyright © 2017, Tomáš Holan [www.tomasholan.eu]
 */
interface IFormGenerator {

	const OPTION_TYPE = 'type',
		OPTION_LABEL = 'label',
		OPTION_REQUIRED = 'required',
		OPTION_NULLABLE = 'nullable',
		OPTION_ITEMS = 'items',
		OPTION_PLACEHOLDER = 'placeholder';

		/**
		 * Generate form if any for user input
		 * @param IFormDefiner $holder
		 * @return Form|null
		 */
		public function generate(IFormDefiner $holder): ?Form;

}