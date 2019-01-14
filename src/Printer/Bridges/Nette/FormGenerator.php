<?php


namespace Holabs\Printer\Bridges\Nette;


use Holabs\Printer\IFormGenerator;
use Holabs\Printer\IFormDefiner;
use Holabs\UI\FormFactory;
use Nette\Application\UI\Form;
use Nette\Forms\Container;
use Nette\Utils\Strings;

/**
 * @author       Tomáš Holan <mail@tomasholan.eu>
 * @package      holabs/printer
 * @copyright    Copyright © 2017, Tomáš Holan [www.tomasholan.eu]
 */
class FormGenerator implements IFormGenerator {

	/** @var FormFactory */
	private $formFactory;

	public function __construct(FormFactory $formFactory) {
		$this->formFactory = $formFactory;
	}


	/**
	 * @inheritdoc
	 */
	public function generate(IFormDefiner $holder): ?Form {
		$options = $holder->getFormDefinition();
		$map = $options[self::OPTION_ITEMS] ?? [];
		$method = $options['FORM_METHOD'] ?? null;

		if (!count($map)) {
			return NULL;
		}

		$form = $this->formFactory->create();

		if ($method !== null) {
			$form->setMethod($method);
		}

		$this->buildContainer($form, $map);

		return $form;
	}

	/**
	 * Build container by array definition
	 */
	protected function buildContainer(Container $container, array $map) {
		foreach ($map as $name => $options) {
			$type = $options[self::OPTION_TYPE];
			unset($options[self::OPTION_TYPE]);

			// find every "-" delete it and uppercase next char
			$fnc = preg_replace_callback('~(\-(.?))~', function($m) {return Strings::upper($m[2] ?? '');}, $type);
			$fnc = 'add' . Strings::firstUpper($fnc);

			$this->{$fnc}($container, $name, $options);
		}
	}

	protected function addText(Container $container, string $name, array $options) {
		$container->addText($name, $options[self::OPTION_LABEL] ?? null)
			->setRequired($options[self::OPTION_REQUIRED] ?? false)
			->setNullable((bool) $options[self::OPTION_NULLABLE] ?? false)
			->setAttribute('placeholder',$options[self::OPTION_PLACEHOLDER] ?? false);
	}

	protected function addPassword(Container $container, string $name, array $options) {
		$container->addPassword($name, $options[self::OPTION_LABEL] ?? null)
			->setRequired($options[self::OPTION_REQUIRED] ?? false)
			->setNullable((bool) $options[self::OPTION_NULLABLE] ?? false)
			->setAttribute('placeholder',$options[self::OPTION_PLACEHOLDER] ?? false);
	}

	protected function addTextArea(Container $container, string $name, array $options) {
		$container->addTextArea($name, $options[self::OPTION_LABEL] ?? null)
			->setRequired($options[self::OPTION_REQUIRED] ?? false)
			->setAttribute('placeholder',$options[self::OPTION_PLACEHOLDER] ?? false);
	}

	protected function addEmail(Container $container, string $name, array $options) {
		$container->addEmail($name, $options[self::OPTION_LABEL] ?? null)
			->setRequired($options[self::OPTION_REQUIRED] ?? false)
			->setNullable((bool) $options[self::OPTION_NULLABLE] ?? false)
			->setAttribute('placeholder',$options[self::OPTION_PLACEHOLDER] ?? false);
	}

	protected function addInteger(Container $container, string $name, array $options) {
		$container->addInteger($name, $options[self::OPTION_LABEL] ?? null)
			->setRequired($options[self::OPTION_REQUIRED] ?? false)
			->setNullable((bool) $options[self::OPTION_NULLABLE] ?? false)
			->setAttribute('placeholder',$options[self::OPTION_PLACEHOLDER] ?? false);
	}

	protected function addUpload(Container $container, string $name, array $options) {
		$container->addUpload($name, $options[self::OPTION_LABEL] ?? null)
			->setRequired($options[self::OPTION_REQUIRED] ?? false);
	}

	protected function addMultiUpload(Container $container, string $name, array $options) {
		$container->addMultiUpload($name, $options[self::OPTION_LABEL] ?? null)
			->setRequired($options[self::OPTION_REQUIRED] ?? false);
	}

	protected function addHidden(Container $container, string $name, array $options) {
		$container->addHidden($name, $options['default'] ?? null);
	}

	protected function addCheckbox(Container $container, string $name, array $options) {
		$container->addCheckbox($name, $options[self::OPTION_LABEL] ?? null)
			->setRequired($options[self::OPTION_REQUIRED] ?? false);
	}

	protected function addRadioList(Container $container, string $name, array $options) {
		$container->addRadioList($name, $options[self::OPTION_LABEL] ?? null, $options[self::OPTION_ITEMS] ?? null)
			->setRequired($options[self::OPTION_REQUIRED] ?? false);
	}

	protected function addCheckboxList(Container $container, string $name, array $options) {
		$container->addCheckboxList($name, $options[self::OPTION_LABEL] ?? null, $options[self::OPTION_ITEMS] ?? null)
			->setRequired($options[self::OPTION_REQUIRED] ?? false);
	}

	protected function addSelect(Container $container, string $name, array $options) {
		$container->addSelect($name, $options[self::OPTION_LABEL] ?? null, $options[self::OPTION_ITEMS] ?? null)
			->setRequired($options[self::OPTION_REQUIRED] ?? false);
	}

	protected function addMultiSelect(Container $container, string $name, array $options) {
		$container->addSelect($name, $options[self::OPTION_LABEL] ?? null, $options[self::OPTION_ITEMS] ?? null)
			->setRequired($options[self::OPTION_REQUIRED] ?? false);
	}

	protected function addSubmit(Container $container, string $name, array $options) {
		$container->addSubmit($name, $options[self::OPTION_LABEL] ?? null);
	}

	protected function addButton(Container $container, string $name, array $options) {
		$container->addButton($name, $options[self::OPTION_LABEL] ?? null);
	}

	protected function addImage(Container $container, string $name, array $options) {
		$container->addImage($name, $options['src'] ?? null, $options[self::OPTION_LABEL] ?? null);
	}

	protected function addContainer(Container $container, string $name, array $options) {
		$c = $container->addContainer($name);
		$this->buildContainer($c, $options[self::OPTION_ITEMS] ?? []);
	}
}