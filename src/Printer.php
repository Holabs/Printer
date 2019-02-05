<?php


namespace Holabs;

use Holabs\Printer\Configuration;
use Holabs\Printer\Helpers;
use Holabs\Printer\IEntityStorage;
use Holabs\Printer\IFormGenerator;
use Holabs\Printer\ITemplateStorage;
use Holabs\Printer\Job;
use Holabs\Printer\PreviewEntity;
use Latte\Loaders\FileLoader;
use Latte\Loaders\StringLoader;
use Nette\Application\UI\ITemplateFactory;
use Nette\Bridges\ApplicationLatte\TemplateFactory;
use Nette\Utils\ArrayHash;


/**
 * @author       Tomáš Holan <mail@tomasholan.eu>
 * @package      holabs/printer
 * @copyright    Copyright © 2017, Tomáš Holan [www.tomasholan.eu]
 */
class Printer implements IPrinter {

	/** @var Configuration */
	private $config;

	/** @var IEntityStorage */
	private $entityStorage;

	/** @var ITemplateStorage */
	private $templateStorage;

	/** @var ITemplateFactory|TemplateFactory */
	private $templateFactory;

	/** @var IFormGenerator */
	private $formGenerator;

	public function __construct(
		Configuration $config,
		IEntityStorage $entityStorage,
		ITemplateStorage $templateStorage,
		ITemplateFactory $templateFactory,
		IFormGenerator $formGenerator = NULL
	) {
		$this->config = $config;
		$this->entityStorage = $entityStorage;
		$this->templateStorage = $templateStorage;
		$this->templateFactory = $templateFactory;
		$this->formGenerator = $formGenerator;
	}

	/**
	 * @return IEntityStorage
	 */
	public function getEntityStorage(): IEntityStorage {
		return $this->entityStorage;
	}

	/**
	 * @return ITemplateStorage
	 */
	public function getTemplateStorage(): ITemplateStorage {
		return $this->templateStorage;
	}

	/**
	 * @param $id
	 * @param $objIds
	 * @return Job
	 */
	public function createJob($id, ... $objIds): Job {

		$objIds = array_unique($objIds);

		$layout = (new FileLoader())->getContent($this->getConfig()->getLayoutPath());

		$template = $this->getTemplateStorage()->getTemplate($id);
		$form = $this->formGenerator !== NULL ? $this->formGenerator->generate($template) : NULL;
		$preview = !count($objIds) ? new PreviewEntity($template->getClass()) : FALSE;
		$entities = $preview
			? [$preview]
			: $this->getEntityStorage()->findEntities($template->getClass(), $objIds);
		$params = [];
		$options = Helpers::validateOptions($this->getConfig()->getDefaultParams(), $template->getOptions());
		$params['background'] = $options['background'];
		$params['margin'] = $options['margin'];
		$params['size'] = $options['size'];
		$params['isAutoPrintEnabled'] = $this->getConfig()->isAutoPrintEnabled();
		$params['template'] = $template;
		$params['name'] = $template->getName();
		$params['entities'] = $entities;
		$params['options'] = $options;

		$engine = $this->templateFactory->createTemplate()->getLatte()->setLoader(
			new StringLoader(
				[
					'template' => '{extends layout}' . $template->getSource(),
					'layout'   => $layout
				]
			)
		);


		return new Job($engine, $form, $params);
	}

	/**
	 * @return Configuration
	 */
	protected function getConfig(): Configuration {
		return $this->config;
	}

}