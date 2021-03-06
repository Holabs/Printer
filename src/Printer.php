<?php


namespace Holabs;

use Holabs\Printer\Configuration;
use Holabs\Printer\Helpers;
use Holabs\Printer\IEntityStorage;
use Holabs\Printer\IFormGenerator;
use Holabs\Printer\ITemplate;
use Holabs\Printer\ITemplateStorage;
use Holabs\Printer\Job;
use Holabs\Printer\PreviewEntity;
use Latte\Loaders\FileLoader;
use Latte\Loaders\StringLoader;
use Nette\Application\LinkGenerator;
use Nette\Application\UI\ITemplateFactory;
use Nette\Bridges\ApplicationLatte\TemplateFactory;


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

	/** @var LinkGenerator|null */
	private $linkGenerator;

	/** @var IFormGenerator|null */
	private $formGenerator;

	public function __construct(
		Configuration $config,
		IEntityStorage $entityStorage,
		ITemplateStorage $templateStorage,
		ITemplateFactory $templateFactory,
		LinkGenerator $linkGenerator = null,
		IFormGenerator $formGenerator = null
	) {
		$this->config = $config;
		$this->entityStorage = $entityStorage;
		$this->templateStorage = $templateStorage;
		$this->templateFactory = $templateFactory;
		$this->linkGenerator = $linkGenerator;
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

		$template = $this->getTemplateStorage()->getTemplate($id);
		$entities = !count($objIds) ? [] : $this->getEntityStorage()->findEntities($template->getClass(), $objIds);

		return $this->jobFactory($template, ... $entities);
	}

	/**
	 * @param string|int $id
	 * @param object[] $entities
	 * @return Job
	 */
	public function createJobFromEntities($id, ... $entities): Job {

		$entities = array_unique($entities);
		$template = $this->getTemplateStorage()->getTemplate($id);
		return $this->jobFactory($template, ... $entities);
	}



	protected function jobFactory(ITemplate $template, ... $entities) {

		$layout = (new FileLoader())->getContent($this->getConfig()->getLayoutPath());
		$entities = !count($entities) ? [new PreviewEntity($template->getClass())] : $entities;
		$form = $this->formGenerator !== NULL ? $this->formGenerator->generate($template) : NULL;

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
		$params['linker'] = $this->linkGenerator;

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
