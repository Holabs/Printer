<?php

namespace Holabs\Printer\Bridges\Nette;

use Holabs\Printer;
use Holabs\Printer\Configuration;
use Holabs\Printer\JobFactory;
use Nette\DI\Extensions\ExtensionsExtension;


/**
 * @author       Tomáš Holan <mail@tomasholan.eu>
 * @package      holabs/printer
 * @copyright    Copyright © 2017, Tomáš Holan [www.tomasholan.eu]
 */
class PrinterExtension extends ExtensionsExtension {

	const DS = DIRECTORY_SEPARATOR;

	const SOURCE_PATH = __DIR__ .
	self::DS . '..' .
	self::DS . '..' .
	self::DS . '..';

	const TEMPLATE_PATH = self::SOURCE_PATH . self::DS . 'templates';

	const DEFAULT_LAYOUT_TEMPLATE = self::TEMPLATE_PATH . self::DS . 'layout.latte';

	const DEFAULT_PARAMS = [
		'background' => '#ffffff',
		'margin'     => '2cm',
		'size'		 => 'A4',
	];

	public $defaults = [
		'layout' => self::DEFAULT_LAYOUT_TEMPLATE,
		'auto-print' => TRUE,
		'params' => self::DEFAULT_PARAMS,

	];

	public function loadConfiguration() {

		$this->validateConfig($this->defaults);
		$config = $this->getConfig();

		$configuration = new Configuration($config['layout'], $config['auto-print'], $config['params']);

		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('service'))
			->setFactory(Printer::class);

		$builder->addDefinition($this->prefix('factories.job'))
			->setFactory(JobFactory::class, [$configuration]);
	}

}